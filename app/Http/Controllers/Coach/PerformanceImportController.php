<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\PlanningSwimmer;
use App\Models\SwimPerformance;
use App\Services\ExtranatPerformanceParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Espace coachs → Performances → « Importer depuis extraNat » (administrateurs uniquement).
 * Copier-coller de la page « Performances » d'un ou plusieurs nageurs → aperçu → import.
 * Tout l'historique est importé ; tour « Série » ; points FFN et niveau ignorés ;
 * une performance déjà présente (même nageur, épreuve, bassin, date et temps) n'est jamais ajoutée deux fois.
 */
class PerformanceImportController extends Controller
{
    public function __construct(private ExtranatPerformanceParser $parser)
    {
    }

    public function create(Request $request): View
    {
        $this->onlyAdmin($request);

        return view('coach.performances.import', ['text' => '', 'blocks' => null, 'ignored' => 0, 'swimmers' => $this->swimmerList()]);
    }

    public function store(Request $request): View|RedirectResponse
    {
        $this->onlyAdmin($request);
        $data = $request->validate([
            'text' => ['required', 'string', 'max:2000000'],
            'assign' => ['nullable', 'array'],
            'assign.*' => ['nullable', 'integer', 'exists:planning_swimmers,id'],
        ], ['text.required' => 'Collez la page « Performances » copiée depuis extraNat.']);

        $parsed = $this->parser->parse($data['text']);
        if (! $parsed['swimmers']) {
            return back()->withInput()->withErrors(['text' => 'Aucun nageur reconnu. Copiez toute la page extraNat, à partir de la ligne « NOM Prénom (année) FRA [IUF] ».']);
        }

        $blocks = [];
        foreach ($parsed['swimmers'] as $i => $s) {
            $choisi = array_key_exists($i, $data['assign'] ?? []) ? ($data['assign'][$i] ? PlanningSwimmer::find($data['assign'][$i]) : null) : $this->match($s);
            $blocks[] = $this->analyse($s, $choisi);
        }

        if (! $request->boolean('confirm')) {
            return view('coach.performances.import', [
                'text' => $data['text'], 'blocks' => $blocks, 'ignored' => $parsed['ignored'], 'swimmers' => $this->swimmerList(),
            ]);
        }

        $total = 0;
        DB::transaction(function () use ($blocks, $request, &$total) {
            foreach ($blocks as $b) {
                if (! $b['swimmer']) {
                    continue;
                }
                foreach ($b['new'] as $p) {
                    SwimPerformance::create([
                        'planning_swimmer_id' => $b['swimmer']->id,
                        'competition_id' => $p['competition_id'],
                        'context' => $p['competition_id'] ? 'competition' : 'meeting',
                        'meet_name' => $p['competition_id'] ? null : ($p['location'] ? 'Compétition à ' . $p['location'] : 'Compétition (extraNat)'),
                        'location' => $p['location'],
                        'performed_on' => $p['date'],
                        'pool' => $p['pool'],
                        'event' => $p['event'],
                        'round' => 'serie',
                        'time_cs' => $p['time_cs'],
                        'status' => 'valid',
                        'note' => 'Import extraNat',
                        'created_by' => $request->user()->id,
                    ]);
                    $total++;
                }
            }
        });

        $nageurs = collect($blocks)->filter(fn ($b) => $b['swimmer'] && $b['new'])->map(fn ($b) => $b['swimmer']->full_name)->implode(', ');

        return redirect()->route('coach.performances.index')
            ->with('success', $total ? "{$total} performance(s) importée(s) depuis extraNat ({$nageurs})." : 'Aucune nouvelle performance : tout était déjà enregistré.');
    }

    /** Nageur du planning correspondant : IUF (fiche ou licence FFN), sinon nom + prénom + année de naissance. */
    private function match(array $s): ?PlanningSwimmer
    {
        if ($s['iuf']) {
            $parIuf = PlanningSwimmer::where('license_number', $s['iuf'])->first()
                ?? PlanningSwimmer::whereHas('ffnLicenses', fn ($q) => $q->where('iuf', $s['iuf']))->first();
            if ($parIuf) {
                return $parIuf;
            }
        }
        $cle = fn ($v) => Str::slug((string) $v);

        return PlanningSwimmer::where('birth_year', $s['birth_year'])->get()
            ->first(fn ($n) => $cle($n->last_name) === $cle($s['last_name']) && $cle($n->first_name) === $cle($s['first_name']));
    }

    /** Sépare les performances nouvelles des doublons et cherche la compétition du calendrier (même date, même ville). */
    private function analyse(array $s, ?PlanningSwimmer $swimmer): array
    {
        $existantes = $swimmer
            ? SwimPerformance::where('planning_swimmer_id', $swimmer->id)->get()
                ->map(fn ($p) => $p->event . '|' . $p->pool . '|' . $p->performed_on?->toDateString() . '|' . $p->time_cs)->flip()
            : collect();

        $competitions = Competition::query()
            ->whereDate('starts_on', '<=', collect($s['performances'])->max('date') ?? today())
            ->whereDate('ends_on', '>=', collect($s['performances'])->min('date') ?? today())
            ->get(['id', 'title', 'city', 'starts_on', 'ends_on']);

        $nouvelles = [];
        $doublons = 0;
        $vues = [];
        foreach ($s['performances'] as $p) {
            $cle = $p['event'] . '|' . $p['pool'] . '|' . $p['date'] . '|' . $p['time_cs'];
            if (isset($existantes[$cle]) || isset($vues[$cle])) {
                $doublons++;
                continue;
            }
            $vues[$cle] = true;
            $compet = $competitions->first(fn ($c) => $c->starts_on->toDateString() <= $p['date'] && $c->ends_on->toDateString() >= $p['date']
                && Str::slug((string) $c->city) === Str::slug((string) $p['location']));
            $nouvelles[] = $p + ['competition_id' => $compet?->id, 'competition' => $compet?->title];
        }

        return [
            'name' => trim($s['last_name'] . ' ' . $s['first_name']),
            'birth_year' => $s['birth_year'],
            'iuf' => $s['iuf'],
            'swimmer' => $swimmer,
            // Nageur trouvé dont le nom ou l'année de naissance diffère (IUF mal saisi sur une fiche ?)
            'mismatch' => $swimmer && (Str::slug((string) $swimmer->last_name) !== Str::slug($s['last_name'])
                || ($swimmer->birth_year && (int) $swimmer->birth_year !== (int) $s['birth_year'])),
            'total' => count($s['performances']),
            'new' => $nouvelles,
            'duplicates' => $doublons,
            'by_event' => collect($nouvelles)->countBy(fn ($p) => $p['event'] . ' (' . $p['pool'] . ' m)'),
            'linked' => collect($nouvelles)->whereNotNull('competition_id')->count(),
            'first' => collect($nouvelles)->min('date'),
            'last' => collect($nouvelles)->max('date'),
        ];
    }

    private function swimmerList()
    {
        return PlanningSwimmer::orderBy('last_name')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'birth_year', 'is_active']);
    }

    private function onlyAdmin(Request $request): void
    {
        abort_unless($request->user()?->hasRole('admin'), 403, "L'import des performances est réservé aux administrateurs.");
    }
}
