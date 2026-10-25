<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\PlanningGroup;
use App\Models\PlanningSlot;
use App\Models\PlanningSwimmer;
use App\Models\PresenceRecord;
use App\Models\PresenceSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Espace coachs → Émargement : séances d'un jour, feuille de présence d'une séance.
 */
class PresenceController extends Controller
{
    /** Séances prévues (créneaux du jour de la semaine) et séances déjà ouvertes à une date. */
    public function index(Request $request): View
    {
        $date = $this->date($request->query('date'));

        $sessions = PresenceSession::with(['group', 'slot'])
            ->withCount(['records', 'records as present_count' => fn ($q) => $q->where('status', 'present')])
            ->whereDate('date', $date)
            ->get();

        $bySlot = $sessions->whereNotNull('planning_slot_id')->keyBy('planning_slot_id');

        $slots = PlanningSlot::with(['group' => fn ($q) => $q->withCount(['swimmers' => fn ($s) => $s->where('is_active', true)])])
            ->active()
            ->where('day_of_week', $date->dayOfWeekIso)
            ->whereHas('group', fn ($q) => $q->where('is_active', true))
            ->chronological()
            ->get();

        // Créneaux désactivés depuis, mais déjà émargés ce jour-là
        $orphelines = $sessions->whereNotNull('planning_slot_id')->reject(fn ($s) => $slots->contains('id', $s->planning_slot_id));

        return view('coach.presence.index', [
            'date' => $date,
            'slots' => $slots,
            'bySlot' => $bySlot,
            'extras' => $sessions->whereNull('planning_slot_id')->concat($orphelines)->sortBy('starts_at'),
            'periods' => PlanningSlot::PERIODS,
        ]);
    }

    /** Ouvre la feuille de présence d'un créneau à une date (création au premier passage). */
    public function open(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'slot' => ['required', 'integer', 'exists:planning_slots,id'],
            'date' => ['required', 'date', 'before_or_equal:' . today()->addDays(7)->toDateString()],
        ]);

        $session = PresenceSession::forSlot(PlanningSlot::findOrFail($data['slot']), Carbon::parse($data['date']), $request->user()->id);

        return redirect()->route('coach.presence.show', $session);
    }

    public function create(Request $request): View
    {
        return view('coach.presence.create', [
            'date' => $this->date($request->query('date')),
            'groups' => PlanningGroup::active()->ordered()->get(),
        ]);
    }

    /** Séance supplémentaire (stage, rattrapage…). */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date', 'before_or_equal:' . today()->addDays(7)->toDateString()],
            'planning_group_id' => ['nullable', 'integer', 'exists:planning_groups,id'],
            'starts_at' => ['nullable', 'date_format:H:i'],
            'ends_at' => ['nullable', 'date_format:H:i', 'after:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
        ], ['title.required' => 'Donnez un titre à la séance (ex. « Stage de Toussaint »).']);

        $session = PresenceSession::create($data + ['created_by' => $request->user()->id]);

        return redirect()->route('coach.presence.show', $session)->with('success', 'Séance créée.');
    }

    /** Feuille de présence. */
    public function show(PresenceSession $session): View
    {
        $session->load(['group', 'slot', 'records']);
        $swimmers = $session->expectedSwimmers();

        return view('coach.presence.show', [
            'session' => $session,
            'swimmers' => $swimmers,
            'records' => $session->records->keyBy('planning_swimmer_id'),
            'others' => PlanningSwimmer::active()->whereNotIn('id', $swimmers->pluck('id'))
                ->orderBy('last_name')->orderBy('first_name')->get(),
            'statuses' => PresenceRecord::STATUSES,
        ]);
    }

    /** Enregistre la feuille : statut par nageur, heure d'arrivée, commentaire. */
    public function update(Request $request, PresenceSession $session): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', 'array'],
            'status.*' => ['nullable', Rule::in(array_keys(PresenceRecord::STATUSES))],
            'arrived_at' => ['nullable', 'array'],
            'arrived_at.*' => ['nullable', 'date_format:H:i'],
            'comment' => ['nullable', 'array'],
            'comment.*' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $existants = $session->records()->get()->keyBy('planning_swimmer_id');
        $autorises = $session->expectedSwimmers()->pluck('id')->flip();
        $maintenant = $session->date->isToday() ? now()->format('H:i') : null;
        $compte = 0;

        foreach ($data['status'] ?? [] as $swimmerId => $status) {
            $swimmerId = (int) $swimmerId;
            if (! $autorises->has($swimmerId)) {
                continue;
            }
            $record = $existants->get($swimmerId);

            if (blank($status)) {
                $record?->delete();   // statut effacé : le nageur n'est plus émargé
                continue;
            }

            $arrivee = $data['arrived_at'][$swimmerId] ?? null;
            if ($status !== 'present') {
                $arrivee = null;
            } elseif (blank($arrivee)) {
                // Heure d'arrivée : celle déjà connue, sinon l'heure actuelle le jour de la séance
                $arrivee = $record?->arrived_at ? substr($record->arrived_at, 0, 5) : $maintenant;
            }

            PresenceRecord::updateOrCreate(
                ['presence_session_id' => $session->id, 'planning_swimmer_id' => $swimmerId],
                [
                    'status' => $status,
                    'arrived_at' => $arrivee,
                    'comment' => $data['comment'][$swimmerId] ?? null,
                    'recorded_by' => $request->user()->id,
                ]
            );
            $compte++;
        }

        $session->update(['notes' => $data['notes'] ?? null]);

        return redirect()->route('coach.presence.show', $session)
            ->with('success', "Émargement enregistré ({$compte} nageur" . ($compte > 1 ? 's' : '') . ').');
    }

    /** Ajoute un nageur hors groupe à la feuille. */
    public function addSwimmer(Request $request, PresenceSession $session): RedirectResponse
    {
        $data = $request->validate(['swimmer' => ['required', 'integer', 'exists:planning_swimmers,id']]);

        PresenceRecord::firstOrCreate(
            ['presence_session_id' => $session->id, 'planning_swimmer_id' => $data['swimmer']],
            [
                'status' => 'present',
                'arrived_at' => $session->date->isToday() ? now()->format('H:i') : null,
                'recorded_by' => $request->user()->id,
            ]
        );

        return redirect()->route('coach.presence.show', $session)->with('success', 'Nageur ajouté à la séance (présent).');
    }

    public function destroy(PresenceSession $session): RedirectResponse
    {
        $date = $session->date->toDateString();
        $session->delete();

        return redirect()->route('coach.presence.index', ['date' => $date])->with('success', 'Séance et émargements supprimés.');
    }

    private function date(?string $value): Carbon
    {
        try {
            $date = $value ? Carbon::createFromFormat('Y-m-d', $value)->startOfDay() : today();
        } catch (\Throwable $e) {
            $date = today();
        }

        return $date->greaterThan(today()->addDays(7)) ? today() : $date;
    }
}
