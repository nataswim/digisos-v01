<?php

namespace App\Http\Controllers\Admin\Qualifications;

use App\Http\Controllers\Controller;
use App\Models\QualificationCategory;
use App\Models\QualificationGrid;
use App\Models\QualificationTime;
use App\Models\SwimPerformance;
use App\Services\QualificationChecker;
use App\Services\QualificationGridParser;
use App\Support\SwimTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Administration → Compétitions → Grilles de qualification (administrateurs uniquement).
 * Une grille = nom, description, période, catégories d'âge, temps Dames / Messieurs en 25 m et 50 m.
 */
class QualificationGridController extends Controller
{
    /** Ordre des colonnes d'une catégorie dans un tableau collé. */
    public const POOL_ORDERS = [
        '25-50' => '25 m puis 50 m',
        '50-25' => '50 m puis 25 m',
        '25' => '25 m seulement',
        '50' => '50 m seulement',
    ];

    public function __construct(private QualificationGridParser $parser, private QualificationChecker $checker)
    {
    }

    public function index(): View
    {
        $grids = QualificationGrid::ordered()->withCount(['categories', 'times'])->get();

        return view('admin.qualifications.index', compact('grids'));
    }

    public function create(): View
    {
        return view('admin.qualifications.form', ['grid' => new QualificationGrid(['is_published' => false])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $grid = DB::transaction(function () use ($request) {
            $grid = QualificationGrid::create($this->validated($request));
            $this->syncCategories($grid, $request);

            return $grid;
        });

        return redirect()->route('admin.qualifications.times', $grid)
            ->with('success', 'Grille créée. Saisissez les temps ou importez un tableau.');
    }

    public function edit(QualificationGrid $grid): View
    {
        return view('admin.qualifications.form', ['grid' => $grid->load('categories')]);
    }

    public function update(Request $request, QualificationGrid $grid): RedirectResponse
    {
        DB::transaction(function () use ($request, $grid) {
            $grid->update($this->validated($request));
            $this->syncCategories($grid, $request);
        });

        return redirect()->route('admin.qualifications.edit', $grid)->with('success', 'Grille enregistrée.');
    }

    public function destroy(QualificationGrid $grid): RedirectResponse
    {
        $grid->delete();

        return redirect()->route('admin.qualifications.index')->with('success', 'Grille supprimée.');
    }

    /** Copie de la grille (catégories et temps), non publiée : pratique pour la saison suivante. */
    public function duplicate(QualificationGrid $grid): RedirectResponse
    {
        $copie = DB::transaction(function () use ($grid) {
            $copie = QualificationGrid::create([
                'name' => $grid->name.' (copie)',
                'description' => $grid->description,
                'period_start' => $grid->period_start,
                'period_end' => $grid->period_end,
                'is_published' => false,
                'sort_order' => $grid->sort_order,
            ]);
            foreach ($grid->categories as $cat) {
                $nouvelle = $copie->categories()->create($cat->only(['label', 'birth_year_from', 'birth_year_to', 'sort_order']));
                foreach ($grid->times()->where('qualification_category_id', $cat->id)->get() as $t) {
                    $copie->times()->create(['qualification_category_id' => $nouvelle->id] + $t->only(['event', 'gender', 'pool', 'time_cs']));
                }
            }

            return $copie;
        });

        return redirect()->route('admin.qualifications.edit', $copie)
            ->with('success', 'Grille dupliquée. Pensez à modifier le nom, la période et les années de naissance.');
    }

    // ===================== TEMPS =====================

    public function times(QualificationGrid $grid): View
    {
        $grid->load(['categories', 'times']);

        return view('admin.qualifications.times', [
            'grid' => $grid,
            'map' => $grid->timeMap(),
            'events' => SwimPerformance::EVENTS,
            'anomalies' => $this->checker->anomalies(...$this->rowsFromGrid($grid)),
        ]);
    }

    public function updateTimes(Request $request, QualificationGrid $grid): RedirectResponse
    {
        $grid->load('categories');
        $saisis = $request->input('times', []);
        $erreurs = [];
        $lignes = [];

        foreach ($grid->categories as $cat) {
            foreach (SwimPerformance::EVENTS as $event) {
                foreach (['F', 'M'] as $g) {
                    foreach ([25, 50] as $pool) {
                        $valeur = trim((string) ($saisis[$cat->id][$event][$g][$pool] ?? ''));
                        if ($valeur === '') {
                            continue;
                        }
                        $cs = $this->parser->time($valeur) ?? SwimTime::parse($valeur);
                        if (! $cs) {
                            $erreurs[] = "{$event} ".($g === 'F' ? 'Dames' : 'Messieurs')." {$cat->label} {$pool} m : « {$valeur} » n'est pas un temps valide.";
                            continue;
                        }
                        $lignes[] = ['qualification_category_id' => $cat->id, 'event' => $event, 'gender' => $g, 'pool' => $pool, 'time_cs' => $cs];
                    }
                }
            }
        }

        if ($erreurs) {
            return back()->withInput()->withErrors($erreurs);
        }

        DB::transaction(function () use ($grid, $lignes) {
            $grid->times()->delete();
            foreach ($lignes as $l) {
                $grid->times()->create($l);
            }
        });

        return redirect()->route('admin.qualifications.times', $grid)->with('success', count($lignes).' temps enregistrés.');
    }

    // ===================== IMPORT =====================

    public function import(QualificationGrid $grid): View
    {
        return view('admin.qualifications.import', ['grid' => $grid->load('categories'), 'preview' => null]);
    }

    public function importStore(Request $request, QualificationGrid $grid): View|RedirectResponse
    {
        $grid->load('categories');
        $data = $request->validate([
            'text' => ['required', 'string', 'max:500000'],
            'pools' => ['required', 'in:'.implode(',', array_keys(self::POOL_ORDERS))],
            'mode' => ['required', 'in:merge,replace'],
        ], ['text.required' => 'Collez le tableau de la grille.']);

        $bassins = match ($data['pools']) {
            '25-50' => [25, 50], '50-25' => [50, 25], '25' => [25], '50' => [50],
        };
        $parsed = $this->parser->parse($data['text'], $grid->categories->count(), count($bassins));

        // Catégories : celles du texte (en-têtes) ou, à défaut, celles de la grille
        $cats = [];
        if ($parsed['categories']) {
            foreach ($parsed['categories'] as $c) {
                $existante = $grid->categories->first(fn ($e) => mb_strtolower(trim($e->label)) === mb_strtolower($c['label']));
                $cats[] = ['label' => $c['label'], 'from' => $c['from'], 'to' => $c['to'], 'existing' => $existante];
            }
        } else {
            foreach ($grid->categories as $e) {
                $cats[] = ['label' => $e->label, 'from' => $e->birth_year_from, 'to' => $e->birth_year_to, 'existing' => $e];
            }
        }

        if (! $cats) {
            return back()->withInput()->withErrors(['text' => 'Aucune catégorie : ajoutez les catégories d\'âge à la grille, ou collez aussi la ligne d\'en-tête (ex. « U12 (2014)  U13 (2013) »).']);
        }
        if (! $parsed['rows']) {
            return back()->withInput()->withErrors(['text' => 'Aucune épreuve reconnue. Chaque ligne de temps doit commencer par « Dames » ou « Messieurs », avec l\'épreuve (ex. « 50 nage libre ») sur la même ligne ou juste à côté.']);
        }

        // Colonne → [index de catégorie, bassin]
        $colonnes = [];
        foreach ($cats as $i => $c) {
            foreach ($bassins as $b) {
                $colonnes[] = [$i, $b];
            }
        }

        if (! $request->boolean('confirm')) {
            return view('admin.qualifications.import', [
                'grid' => $grid,
                'preview' => [
                    'cats' => $cats,
                    'columns' => $colonnes,
                    'rows' => $parsed['rows'],
                    'warnings' => $parsed['warnings'],
                    'anomalies' => $this->checker->anomalies($parsed['rows'], array_map(fn ($c) => [$cats[$c[0]]['label'], $c[1]], $colonnes)),
                    'count' => collect($parsed['rows'])->sum(fn ($r) => count(array_filter(array_slice($r['cells'], 0, count($colonnes)), fn ($c) => $c !== null))),
                ],
            ]);
        }

        $total = DB::transaction(function () use ($grid, $cats, $colonnes, $parsed, $data) {
            $ordre = (int) $grid->categories()->max('sort_order');
            $ids = [];
            foreach ($cats as $i => $c) {
                $ids[$i] = $c['existing']?->id ?? $grid->categories()->create([
                    'label' => $c['label'], 'birth_year_from' => $c['from'], 'birth_year_to' => $c['to'], 'sort_order' => ++$ordre,
                ])->id;
            }
            if ($data['mode'] === 'replace') {
                $grid->times()->delete();
            }
            $n = 0;
            foreach ($parsed['rows'] as $r) {
                foreach ($colonnes as $col => [$ci, $pool]) {
                    $cs = $r['cells'][$col] ?? null;
                    if ($cs === null) {
                        continue;
                    }
                    QualificationTime::updateOrCreate(
                        ['qualification_category_id' => $ids[$ci], 'event' => $r['event'], 'gender' => $r['gender'], 'pool' => $pool],
                        ['qualification_grid_id' => $grid->id, 'time_cs' => $cs],
                    );
                    $n++;
                }
            }

            return $n;
        });

        return redirect()->route('admin.qualifications.times', $grid)->with('success', "{$total} temps importés. Relisez la grille avant de la publier.");
    }

    // ===================== OUTILS =====================

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'period_start' => ['nullable', 'date'],
            'period_end' => ['nullable', 'date', 'after_or_equal:period_start'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'categories' => ['nullable', 'array', 'max:40'],
            'categories.*.id' => ['nullable', 'integer'],
            'categories.*.label' => ['nullable', 'string', 'max:60'],
            'categories.*.from' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'categories.*.to' => ['nullable', 'integer', 'min:1900', 'max:2100'],
        ], [
            'period_end.after_or_equal' => 'La fin de la période doit être après son début.',
        ]);

        return [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'period_start' => $data['period_start'] ?? null,
            'period_end' => $data['period_end'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ];
    }

    /** Catégories du formulaire : lignes existantes modifiées ou supprimées, lignes nouvelles ajoutées. */
    private function syncCategories(QualificationGrid $grid, Request $request): void
    {
        if (! $request->has('categories')) {
            return;
        }
        $gardees = [];
        foreach (array_values($request->input('categories', [])) as $ordre => $c) {
            $label = trim((string) ($c['label'] ?? ''));
            $existante = ! empty($c['id']) ? $grid->categories()->find($c['id']) : null;

            if ($label === '' || ! empty($c['delete'])) {
                continue;
            }
            $valeurs = [
                'label' => $label,
                'birth_year_from' => ($c['from'] ?? '') !== '' ? (int) $c['from'] : null,
                'birth_year_to' => ($c['to'] ?? '') !== '' ? (int) $c['to'] : null,
                'sort_order' => $ordre + 1,
            ];
            if ($existante) {
                $existante->update($valeurs);
                $gardees[] = $existante->id;
            } else {
                $gardees[] = $grid->categories()->create($valeurs)->id;
            }
        }

        // Catégories retirées du formulaire : supprimées avec leurs temps
        $grid->categories()->whereNotIn('id', $gardees ?: [0])->get()->each->delete();
    }

    /** Lignes et colonnes de la grille enregistrée, au format de QualificationChecker::anomalies(). */
    private function rowsFromGrid(QualificationGrid $grid): array
    {
        $map = $grid->timeMap();
        $colonnes = [];
        foreach ($grid->categories as $cat) {
            foreach ([25, 50] as $pool) {
                $colonnes[] = [$cat, $pool];
            }
        }
        $rows = [];
        foreach ($grid->events() as $event) {
            foreach (['F', 'M'] as $g) {
                $rows[] = [
                    'event' => $event,
                    'gender' => $g,
                    'cells' => array_map(fn ($c) => $map[$c[0]->id][$event][$g][$c[1]] ?? null, $colonnes),
                ];
            }
        }

        return [$rows, array_map(fn ($c) => [$c[0]->label, $c[1]], $colonnes)];
    }
}
