<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\PlanningGroup;
use App\Models\PlanningSlot;
use App\Models\PlanningSwimmer;
use App\Models\TrainingExercise;
use App\Models\TrainingList;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Espace coachs → Séances : création, publication et diffusion des séances
 * (natation, PPG, séances à la maison) aux nageurs choisis.
 */
class TrainingSessionController extends Controller
{
    /** Séances d'une semaine. */
    public function index(Request $request): View
    {
        $monday = $this->monday($request->query('semaine'));
        $type = array_key_exists($request->query('type'), TrainingSession::TYPES) ? $request->query('type') : null;
        $groupId = $request->integer('groupe') ?: null;
        $status = array_key_exists($request->query('statut'), TrainingSession::STATUSES) ? $request->query('statut') : null;

        $sessions = TrainingSession::with('groups')->withCount('swimmers')
            ->whereBetween('session_date', [$monday->toDateString(), $monday->copy()->addDays(6)->toDateString() . ' 23:59:59'])
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($groupId, fn ($q) => $q->whereHas('groups', fn ($g) => $g->where('planning_groups.id', $groupId)))
            ->orderBy('session_date')->orderBy('starts_at')
            ->get()
            ->groupBy(fn ($s) => $s->session_date->toDateString());

        return view('coach.training.index', [
            'monday' => $monday,
            'sessions' => $sessions,
            'type' => $type,
            'groupId' => $groupId,
            'status' => $status,
            'groups' => PlanningGroup::ordered()->get(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $type = $request->query('type');
        if (! array_key_exists($type, TrainingSession::TYPES)) {
            return redirect()->route('coach.training.index')->with('error', 'Choisissez le type de séance.');
        }

        $session = new TrainingSession([
            'type' => $type,
            'session_date' => $this->dateOrToday($request->query('date')),
            'rounds' => 1,
            'status' => 'draft',
            'content' => $type === 'natation' ? ['blocks' => []] : ['items' => []],
        ]);

        // Pré-remplissage depuis un créneau du planning
        if ($slotId = $request->integer('creneau')) {
            if ($slot = PlanningSlot::find($slotId)) {
                $session->fill(['planning_slot_id' => $slot->id, 'starts_at' => $slot->starts_at, 'ends_at' => $slot->ends_at, 'location' => $slot->location]);
                $session->setRelation('groups', collect([$slot->group])->filter());
            }
        }

        return $this->form($session);
    }

    public function store(Request $request): RedirectResponse
    {
        $session = new TrainingSession(['created_by' => $request->user()->id]);
        $this->save($request, $session);

        return redirect()->route('coach.training.edit', $session)
            ->with('success', $session->isPublished() ? 'Séance enregistrée et publiée.' : 'Séance enregistrée en brouillon.');
    }

    public function show(TrainingSession $training): View
    {
        return view('coach.training.show', [
            'session' => $training->load(['groups', 'swimmers', 'author']),
            'exercises' => $training->exercises(),
        ]);
    }

    public function edit(TrainingSession $training): View
    {
        return $this->form($training->load(['groups', 'swimmers']));
    }

    public function update(Request $request, TrainingSession $training): RedirectResponse
    {
        $this->save($request, $training);

        return redirect()->route('coach.training.edit', $training)
            ->with('success', $training->isPublished() ? 'Séance enregistrée (publiée).' : 'Séance enregistrée (brouillon).');
    }

    /** Publie ou repasse en brouillon. */
    public function toggle(Request $request, TrainingSession $training): RedirectResponse
    {
        if (! $training->isPublished() && ! $training->swimmers()->exists()) {
            return back()->with('error', 'Choisissez au moins un nageur avant de publier la séance.');
        }

        $training->update($training->isPublished()
            ? ['status' => 'draft', 'updated_by' => $request->user()->id]
            : ['status' => 'published', 'published_at' => $training->published_at ?? now(), 'updated_by' => $request->user()->id]);

        return back()->with('success', $training->isPublished() ? 'Séance publiée : visible par les nageurs choisis.' : 'Séance repassée en brouillon.');
    }

    /** Copie en brouillon, une semaine plus tard. */
    public function duplicate(Request $request, TrainingSession $training): RedirectResponse
    {
        $copy = $training->replicate(['published_at', 'status', 'created_by', 'updated_by']);
        $copy->fill([
            'title' => $training->title,
            'session_date' => $training->session_date->copy()->addWeek(),
            'status' => 'draft',
            'created_by' => $request->user()->id,
        ])->save();
        $copy->groups()->sync($training->groups()->pluck('planning_groups.id'));
        $copy->swimmers()->sync($training->swimmers()->pluck('planning_swimmers.id'));

        return redirect()->route('coach.training.edit', $copy)
            ->with('success', 'Copie créée en brouillon pour le ' . $copy->session_date->translatedFormat('l j F') . ' : vérifiez la date et les nageurs.');
    }

    public function destroy(TrainingSession $training): RedirectResponse
    {
        $semaine = $training->session_date->copy()->startOfWeek()->toDateString();
        $training->delete();

        return redirect()->route('coach.training.index', ['semaine' => $semaine])->with('success', 'Séance supprimée.');
    }

    // ------------------------------------------------------------------

    private function form(TrainingSession $session): View
    {
        $swimmers = PlanningSwimmer::active()->with('groups:id')->orderBy('last_name')->orderBy('first_name')->get();

        return view('coach.training.form', [
            'session' => $session,
            'groups' => PlanningGroup::active()->ordered()->get(),
            'swimmers' => $swimmers,
            'slots' => PlanningSlot::with('group')->active()->whereHas('group', fn ($q) => $q->where('is_active', true))->chronological()->get(),
            'exercises' => TrainingExercise::active()->orderBy('category')->orderBy('name')->get(),
            'lists' => collect(array_keys(TrainingList::LISTS))->mapWithKeys(fn ($l) => [$l => TrainingList::labels($l)]),
            'selectedGroups' => $groupes = old('groups', $session->relationLoaded('groups') ? $session->groups->pluck('id')->all() : []),
            // Nouvelle séance depuis un créneau : nageurs du groupe cochés d'office
            'selectedSwimmers' => old('swimmers', $session->relationLoaded('swimmers')
                ? $session->swimmers->pluck('id')->all()
                : $swimmers->filter(fn ($s) => $s->groups->pluck('id')->intersect($groupes)->isNotEmpty())->pluck('id')->all()),
        ]);
    }

    private function save(Request $request, TrainingSession $session): void
    {
        $type = $session->exists ? $session->type : $request->input('type');

        $data = $request->validate([
            'type' => [$session->exists ? 'nullable' : 'required', Rule::in(array_keys(TrainingSession::TYPES))],
            'title' => ['required', 'string', 'max:255'],
            'objective' => ['nullable', 'string', 'max:255'],
            'session_date' => ['required', 'date'],
            'starts_at' => ['nullable', 'date_format:H:i'],
            'ends_at' => ['nullable', 'date_format:H:i', 'after:starts_at'],
            'planning_slot_id' => ['nullable', 'integer', 'exists:planning_slots,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'equipment' => ['nullable', 'array'],
            'equipment.*' => ['string', 'max:100'],
            'instructions' => ['nullable', 'string', 'max:5000'],
            'rounds' => ['nullable', 'integer', 'min:1', 'max:20'],
            'groups' => ['nullable', 'array'],
            'groups.*' => ['integer', 'exists:planning_groups,id'],
            'swimmers' => ['nullable', 'array'],
            'swimmers.*' => ['integer', 'exists:planning_swimmers,id'],
            'action' => ['nullable', Rule::in(['draft', 'publish'])],

            // Natation
            'content.blocks' => ['nullable', 'array', 'max:30'],
            'content.blocks.*.kind' => ['nullable', 'string', 'max:100'],
            'content.blocks.*.title' => ['nullable', 'string', 'max:150'],
            'content.blocks.*.repeat' => ['nullable', 'integer', 'min:1', 'max:20'],
            'content.blocks.*.note' => ['nullable', 'string', 'max:500'],
            'content.blocks.*.lines' => ['nullable', 'array', 'max:40'],
            'content.blocks.*.lines.*.reps' => ['nullable', 'integer', 'min:1', 'max:200'],
            'content.blocks.*.lines.*.distance' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'content.blocks.*.lines.*.stroke' => ['nullable', 'string', 'max:100'],
            'content.blocks.*.lines.*.zone' => ['nullable', Rule::in(array_keys(TrainingSession::ZONES))],
            'content.blocks.*.lines.*.departure' => ['nullable', 'string', 'max:20'],
            'content.blocks.*.lines.*.rest' => ['nullable', 'string', 'max:20'],
            'content.blocks.*.lines.*.equipment' => ['nullable', 'string', 'max:100'],
            'content.blocks.*.lines.*.note' => ['nullable', 'string', 'max:255'],

            // PPG / maison
            'content.items' => ['nullable', 'array', 'max:40'],
            'content.items.*.exercise_id' => ['nullable', 'integer', 'exists:training_exercises,id'],
            'content.items.*.sets' => ['nullable', 'integer', 'min:1', 'max:50'],
            'content.items.*.reps' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'content.items.*.duration' => ['nullable', 'integer', 'min:0', 'max:3600'],
            'content.items.*.rest_sets' => ['nullable', 'integer', 'min:0', 'max:1800'],
            'content.items.*.rest_after' => ['nullable', 'integer', 'min:0', 'max:1800'],
            'content.items.*.load' => ['nullable', 'string', 'max:100'],
            'content.items.*.note' => ['nullable', 'string', 'max:255'],
        ], [
            'title.required' => 'Donnez un titre à la séance.',
            'ends_at.after' => "L'heure de fin doit être après l'heure de début.",
        ]);

        $publier = ($data['action'] ?? 'draft') === 'publish';
        $swimmerIds = array_map('intval', $data['swimmers'] ?? []);
        if ($publier && ! $swimmerIds) {
            throw ValidationException::withMessages(['swimmers' => 'Choisissez au moins un nageur avant de publier la séance.']);
        }

        $session->fill([
            'type' => $type,
            'title' => $data['title'],
            'objective' => $data['objective'] ?? null,
            'session_date' => $data['session_date'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'planning_slot_id' => $data['planning_slot_id'] ?? null,
            'location' => $data['location'] ?? null,
            'equipment' => array_values(array_filter($data['equipment'] ?? [])),
            'instructions' => $data['instructions'] ?? null,
            'rounds' => $type === 'natation' ? 1 : (int) ($data['rounds'] ?? 1),
            'content' => $type === 'natation'
                ? ['blocks' => $this->cleanBlocks($data['content']['blocks'] ?? [])]
                : ['items' => $this->cleanItems($data['content']['items'] ?? [])],
            'status' => $publier ? 'published' : 'draft',
            'published_at' => $publier ? ($session->published_at ?? now()) : $session->published_at,
            'updated_by' => $request->user()->id,
        ]);
        $session->computeTotals();
        $session->save();

        $session->groups()->sync(array_map('intval', $data['groups'] ?? []));
        $session->swimmers()->sync($swimmerIds);
    }

    private function cleanBlocks(array $blocks): array
    {
        return collect($blocks)->map(function ($b) {
            $lines = collect($b['lines'] ?? [])
                ->filter(fn ($l) => ! empty($l['distance']) || ! empty($l['note']) || ! empty($l['stroke']))
                ->map(fn ($l) => [
                    'reps' => max(1, (int) ($l['reps'] ?? 1)),
                    'distance' => (int) ($l['distance'] ?? 0),
                    'stroke' => $l['stroke'] ?? null,
                    'zone' => $l['zone'] ?? null,
                    'departure' => $l['departure'] ?? null,
                    'rest' => $l['rest'] ?? null,
                    'equipment' => $l['equipment'] ?? null,
                    'note' => $l['note'] ?? null,
                ])->values()->all();

            return [
                'kind' => $b['kind'] ?? null,
                'title' => $b['title'] ?? null,
                'repeat' => max(1, (int) ($b['repeat'] ?? 1)),
                'note' => $b['note'] ?? null,
                'lines' => $lines,
            ];
        })->filter(fn ($b) => $b['lines'] || $b['note'] || $b['title'])->values()->all();
    }

    private function cleanItems(array $items): array
    {
        return collect($items)
            ->filter(fn ($i) => ! empty($i['exercise_id']))
            ->map(fn ($i) => [
                'exercise_id' => (int) $i['exercise_id'],
                'sets' => max(1, (int) ($i['sets'] ?? 1)),
                'reps' => ! empty($i['reps']) ? (int) $i['reps'] : null,
                'duration' => ! empty($i['duration']) ? (int) $i['duration'] : null,
                'rest_sets' => ! empty($i['rest_sets']) ? (int) $i['rest_sets'] : null,
                'rest_after' => ! empty($i['rest_after']) ? (int) $i['rest_after'] : null,
                'load' => $i['load'] ?? null,
                'note' => $i['note'] ?? null,
            ])->values()->all();
    }

    private function monday(?string $value): Carbon
    {
        return $this->dateOrToday($value)->startOfWeek(Carbon::MONDAY);
    }

    private function dateOrToday(?string $value): Carbon
    {
        try {
            return $value ? Carbon::createFromFormat('Y-m-d', $value)->startOfDay() : today();
        } catch (\Throwable $e) {
            return today();
        }
    }
}
