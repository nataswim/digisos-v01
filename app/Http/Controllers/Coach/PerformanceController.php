<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\SwimPerformance;
use App\Models\SwimRecord;
use App\Services\PerformanceTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Espace coachs → Performances : saisie des temps (par compétition ou à l'unité), historique,
 * fiche nageur (MPP, progression), bilan d'une réunion, classement du club.
 */
class PerformanceController extends Controller
{
    public function __construct(private PerformanceTracker $tracker)
    {
    }

    /** Derniers temps saisis, filtrables. */
    public function index(Request $request): View
    {
        $swimmerId = $request->integer('nageur') ?: null;
        $event = in_array($request->query('epreuve'), SwimPerformance::EVENTS, true) ? $request->query('epreuve') : null;
        $pool = in_array((int) $request->query('bassin'), [25, 50], true) ? (int) $request->query('bassin') : null;

        $perfs = SwimPerformance::with(['swimmer', 'competition'])
            ->when($swimmerId, fn ($q) => $q->where('planning_swimmer_id', $swimmerId))
            ->when($event, fn ($q) => $q->where('event', $event))
            ->when($pool, fn ($q) => $q->where('pool', $pool))
            ->orderByDesc('performed_on')->orderByDesc('id')
            ->paginate(50)->withQueryString();

        // Réunions récentes (pour le bilan)
        $reunions = SwimPerformance::with('competition')
            ->select('competition_id', 'meet_name', 'performed_on', 'context', DB::raw('count(*) as total'))
            ->groupBy('competition_id', 'meet_name', 'performed_on', 'context')
            ->orderByDesc('performed_on')->limit(8)->get();

        return view('coach.performances.index', [
            'perfs' => $perfs,
            'infos' => $this->tracker->analyseSubset($perfs->getCollection()),
            'swimmers' => PlanningSwimmer::active()->orderBy('last_name')->orderBy('first_name')->get(),
            'swimmerId' => $swimmerId, 'event' => $event, 'pool' => $pool,
            'reunions' => $reunions,
            'competitions' => Competition::orderByDesc('starts_on')->limit(30)->get(),
        ]);
    }

    /** Grille de saisie : une compétition du calendrier (nageurs convoqués et leurs épreuves) ou une réunion libre. */
    public function create(Request $request): View
    {
        $competition = $request->integer('competition') ? Competition::with(['attendances.swimmer', 'attendances.entries.event'])->find($request->integer('competition')) : null;
        $context = $competition ? 'competition' : (array_key_exists($request->query('contexte'), SwimPerformance::CONTEXTS) ? $request->query('contexte') : 'meeting');

        $rows = [];
        if ($competition) {
            foreach ($competition->attendances->whereIn('status', ['confirme', 'convoque'])->sortBy(fn ($a) => $a->swimmer?->last_name) as $attendance) {
                $events = $attendance->entries->map(fn ($e) => SwimPerformance::eventFromSwimEvent($e->event))->filter()->unique();
                foreach ($events->isEmpty() ? [null] : $events as $ev) {
                    $rows[] = ['swimmer' => $attendance->planning_swimmer_id, 'event' => $ev, 'round' => 'serie'];
                }
            }
        }
        if (! $rows) {
            $rows = array_fill(0, 5, ['swimmer' => null, 'event' => null, 'round' => $context === 'training' ? 'clm' : 'serie']);
        }

        $swimmers = PlanningSwimmer::active()->with('groups:id,name')->orderBy('last_name')->orderBy('first_name')->get();

        return view('coach.performances.create', [
            'competition' => $competition,
            'context' => $context,
            'rows' => $rows,
            'swimmers' => $swimmers,
            'competitions' => Competition::orderByDesc('starts_on')->limit(40)->get(),
            'bests' => $this->bestsMap($swimmers->pluck('id')),
            'defaults' => [
                'date' => $competition?->starts_on?->toDateString() ?? today()->toDateString(),
                'pool' => $competition?->pool_length ?: 25,
                'location' => $competition ? trim(($competition->venue ? $competition->venue . ', ' : '') . ($competition->city ?? '')) : '',
                'meet_name' => $context === 'training' ? 'Contre-la-montre' : '',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'context' => ['required', Rule::in(array_keys(SwimPerformance::CONTEXTS))],
            'competition_id' => ['nullable', 'required_if:context,competition', 'integer', 'exists:competitions,id'],
            'meet_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'performed_on' => ['required', 'date', 'before_or_equal:today'],
            'pool' => ['required', Rule::in([25, 50])],
            'rows' => ['required', 'array'],
            'rows.*.swimmer' => ['nullable', 'integer', 'exists:planning_swimmers,id'],
            'rows.*.event' => ['nullable', Rule::in(SwimPerformance::EVENTS)],
            'rows.*.round' => ['nullable', Rule::in(array_keys(SwimPerformance::ROUNDS))],
            'rows.*.time' => ['nullable', 'string', 'max:12'],
            'rows.*.status' => ['nullable', Rule::in(array_keys(SwimPerformance::STATUSES))],
            'rows.*.note' => ['nullable', 'string', 'max:255'],
        ], [
            'competition_id.required_if' => 'Choisissez la compétition du calendrier.',
            'performed_on.before_or_equal' => 'La date ne peut pas être dans le futur.',
        ]);

        $lignes = [];
        $erreurs = [];
        foreach ($data['rows'] as $i => $row) {
            $status = $row['status'] ?? 'valid';
            $time = SwimRecord::parseTime($row['time'] ?? null);
            if (empty($row['swimmer']) || (blank($row['time'] ?? null) && $status === 'valid')) {
                continue;   // ligne vide
            }
            if (empty($row['event'])) {
                $erreurs["rows.$i.event"] = 'Ligne ' . ($i + 1) . ' : choisissez l\'épreuve.';
                continue;
            }
            if ($status === 'valid' && $time === null) {
                $erreurs["rows.$i.time"] = 'Ligne ' . ($i + 1) . " : temps illisible (« {$row['time']} »). Exemple : 1:05.32";
                continue;
            }
            if ((int) $data['pool'] === 50 && str_starts_with($row['event'], '25 ')) {
                $erreurs["rows.$i.event"] = 'Ligne ' . ($i + 1) . ' : pas de 25 m en bassin de 50 m.';
                continue;
            }
            $lignes[] = [
                'planning_swimmer_id' => (int) $row['swimmer'],
                'event' => $row['event'],
                'round' => $row['round'] ?? 'serie',
                'time_cs' => $status === 'valid' ? $time : null,
                'status' => $status,
                'note' => $row['note'] ?? null,
            ];
        }
        if ($erreurs) {
            throw ValidationException::withMessages($erreurs);
        }
        if (! $lignes) {
            throw ValidationException::withMessages(['rows' => 'Aucun temps saisi.']);
        }

        $competition = $data['context'] === 'competition' ? Competition::find($data['competition_id']) : null;
        $commun = [
            'competition_id' => $competition?->id,
            'context' => $data['context'],
            'meet_name' => $competition ? null : (($data['meet_name'] ?? null) ?: null),
            'location' => ($data['location'] ?? null) ?: null,
            'performed_on' => $data['performed_on'],
            'pool' => (int) $data['pool'],
            'created_by' => $request->user()->id,
        ];

        DB::transaction(function () use ($lignes, $commun) {
            foreach ($lignes as $ligne) {
                SwimPerformance::create($ligne + $commun);
            }
        });

        return redirect()->route('coach.performances.report', array_filter([
            'competition' => $competition?->id,
            'date' => $competition ? null : $data['performed_on'],
            'meeting' => $competition ? null : (($data['meet_name'] ?? null) ?: null),
        ]))->with('success', count($lignes) . ' temps enregistré(s).');
    }

    public function edit(SwimPerformance $performance): View
    {
        return view('coach.performances.edit', [
            'performance' => $performance->load('swimmer'),
            'competitions' => Competition::orderByDesc('starts_on')->limit(40)->get(),
        ]);
    }

    public function update(Request $request, SwimPerformance $performance): RedirectResponse
    {
        $data = $request->validate([
            'competition_id' => ['nullable', 'integer', 'exists:competitions,id'],
            'meet_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'performed_on' => ['required', 'date', 'before_or_equal:today'],
            'pool' => ['required', Rule::in([25, 50])],
            'event' => ['required', Rule::in(SwimPerformance::EVENTS)],
            'round' => ['required', Rule::in(array_keys(SwimPerformance::ROUNDS))],
            'time' => ['nullable', 'required_if:status,valid', 'string', 'max:12'],
            'status' => ['required', Rule::in(array_keys(SwimPerformance::STATUSES))],
            'note' => ['nullable', 'string', 'max:255'],
        ], ['time.required_if' => 'Saisissez le temps.']);

        $time = SwimRecord::parseTime($data['time'] ?? null);
        if ($data['status'] === 'valid' && $time === null) {
            throw ValidationException::withMessages(['time' => 'Temps illisible. Exemple : 1:05.32']);
        }

        $performance->update([
            'competition_id' => $data['competition_id'] ?? null,
            'context' => ! empty($data['competition_id']) ? 'competition' : ($performance->context === 'competition' ? 'meeting' : $performance->context),
            'meet_name' => ! empty($data['competition_id']) ? null : ($data['meet_name'] ?? null),
            'location' => $data['location'] ?? null,
            'performed_on' => $data['performed_on'],
            'pool' => (int) $data['pool'],
            'event' => $data['event'],
            'round' => $data['round'],
            'time_cs' => $data['status'] === 'valid' ? $time : null,
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
        ]);

        return redirect()->route('coach.performances.swimmer', $performance->planning_swimmer_id)->with('success', 'Performance modifiée.');
    }

    public function destroy(SwimPerformance $performance): RedirectResponse
    {
        $swimmerId = $performance->planning_swimmer_id;
        $performance->delete();

        return redirect()->route('coach.performances.swimmer', $swimmerId)->with('success', 'Performance supprimée.');
    }

    /** Fiche nageur : MPP, progression, historique. */
    public function swimmer(Request $request, PlanningSwimmer $swimmer): View
    {
        return view('coach.performances.swimmer', $this->swimmerData($request, $swimmer) + ['swimmer' => $swimmer->load('groups')]);
    }

    /** Bilan d'une réunion (compétition du calendrier, ou meeting / séance à une date). */
    public function report(Request $request): View
    {
        $competition = $request->integer('competition') ? Competition::find($request->integer('competition')) : null;
        $date = $request->query('date');
        $meeting = $request->query('meeting');

        $perfs = SwimPerformance::with('swimmer')
            ->when($competition, fn ($q) => $q->where('competition_id', $competition->id))
            ->when(! $competition && $date, fn ($q) => $q->whereNull('competition_id')->whereDate('performed_on', $date)
                ->when($meeting, fn ($m) => $m->where('meet_name', $meeting)))
            ->when(! $competition && ! $date, fn ($q) => $q->whereRaw('1 = 0'))
            ->get();

        $infos = $this->tracker->analyseSubset($perfs);
        $valides = $perfs->filter->isValid();
        $avecEcart = collect($infos)->filter(fn ($i) => $i['pct'] !== null);

        return view('coach.performances.report', [
            'competition' => $competition,
            'title' => $competition?->title ?? ($meeting ?: 'Réunion du ' . ($date ? Carbon::parse($date)->format('d/m/Y') : '')),
            'perfs' => $perfs->sortBy(fn ($p) => [mb_strtolower($p->swimmer?->last_name . ' ' . $p->swimmer?->first_name), SwimPerformance::eventOrder($p->event), SwimPerformance::roundOrder($p->round)])
                ->groupBy('planning_swimmer_id'),
            'infos' => $infos,
            'stats' => [
                'swims' => $perfs->count(),
                'swimmers' => $perfs->pluck('planning_swimmer_id')->unique()->count(),
                'pbs' => collect($infos)->where('pb', true)->count(),
                'firsts' => collect($infos)->where('first', true)->count(),
                'avg_pct' => $avecEcart->isNotEmpty() ? round($avecEcart->avg('pct'), 1) : null,
                'invalid' => $perfs->count() - $valides->count(),
            ],
        ]);
    }

    /** Classement du club : meilleur temps de chaque nageur sur une épreuve. */
    public function ranking(Request $request): View
    {
        $event = in_array($request->query('epreuve'), SwimPerformance::EVENTS, true) ? $request->query('epreuve') : '50 NL';
        $pool = in_array((int) $request->query('bassin'), [25, 50], true) ? (int) $request->query('bassin') : 25;
        $sex = in_array($request->query('sexe'), ['F', 'M'], true) ? $request->query('sexe') : null;
        $groupId = $request->integer('groupe') ?: null;

        $rows = SwimPerformance::with(['swimmer', 'competition'])
            ->where(['event' => $event, 'pool' => $pool, 'status' => 'valid'])->whereNotNull('time_cs')
            ->whereHas('swimmer', fn ($q) => $q
                ->when($sex, fn ($s) => $s->where('gender', $sex))
                ->when($groupId, fn ($g) => $g->whereHas('groups', fn ($gr) => $gr->where('planning_groups.id', $groupId))))
            ->get()
            ->groupBy('planning_swimmer_id')
            ->map(fn ($liste) => $liste->sortBy('time_cs')->first())
            ->sortBy('time_cs')
            ->values();

        return view('coach.performances.ranking', [
            'rows' => $rows, 'event' => $event, 'pool' => $pool, 'sex' => $sex, 'groupId' => $groupId,
            'groups' => PlanningGroup::ordered()->get(),
        ]);
    }

    // ------------------------------------------------------------------

    /** Données de la fiche nageur (partagées avec l'espace adhérent). */
    public function swimmerData(Request $request, PlanningSwimmer $swimmer): array
    {
        $perfs = SwimPerformance::with('competition')->where('planning_swimmer_id', $swimmer->id)->get();
        $bests = $this->tracker->bests($perfs);

        // Graphique : épreuve choisie, sinon celle qui a le plus de temps
        $choix = $perfs->filter->isValid()->groupBy(fn ($p) => $p->pool . '|' . $p->event)->sortByDesc(fn ($g) => $g->count());
        [$chartPool, $chartEvent] = array_pad(explode('|', (string) $choix->keys()->first()), 2, null);
        if ($request->query('epreuve') && in_array($request->query('epreuve'), SwimPerformance::EVENTS, true)) {
            $chartEvent = $request->query('epreuve');
            $chartPool = in_array((int) $request->query('bassin'), [25, 50], true) ? (int) $request->query('bassin') : (int) ($chartPool ?: 25);
        }

        return [
            'perfs' => PerformanceTracker::chronological($perfs)->reverse()->values(),
            'infos' => $this->tracker->analyse($perfs),
            'bests' => $bests,
            'chartOptions' => $choix->keys()->map(fn ($k) => explode('|', $k))->sortBy(fn ($k) => [(int) $k[0], SwimPerformance::eventOrder($k[1])])->values(),
            'chartEvent' => $chartEvent,
            'chartPool' => (int) $chartPool,
            'chart' => $chartEvent ? $this->tracker->chart($perfs, $chartEvent, (int) $chartPool) : null,
        ];
    }

    /** Meilleurs temps actuels « nageur|épreuve|bassin » => centièmes (calcul en direct dans la grille). */
    private function bestsMap($swimmerIds): array
    {
        return SwimPerformance::whereIn('planning_swimmer_id', $swimmerIds)->where('status', 'valid')->whereNotNull('time_cs')
            ->select('planning_swimmer_id', 'event', 'pool', DB::raw('min(time_cs) as best'))
            ->groupBy('planning_swimmer_id', 'event', 'pool')->get()
            ->mapWithKeys(fn ($r) => [$r->planning_swimmer_id . '|' . $r->event . '|' . $r->pool => (int) $r->best])
            ->all();
    }
}
