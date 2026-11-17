<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\PlanningGroup;
use App\Models\QualificationGrid;
use App\Services\QualificationChecker;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace coachs → Qualifiés : nageurs du club qui ont réalisé les temps d'une grille de qualification.
 * Réservé aux coachs et administrateurs (noms et temps des nageurs).
 */
class QualificationController extends Controller
{
    public function __construct(private QualificationChecker $checker)
    {
    }

    public function index(): View
    {
        $grids = QualificationGrid::ordered()->withCount(['categories', 'times'])->get();

        return view('coach.qualifications.index', compact('grids'));
    }

    public function show(Request $request, QualificationGrid $grid): View
    {
        $filtres = $request->validate([
            'groupe' => ['nullable', 'integer'],
            'sexe' => ['nullable', 'in:F,M'],
            'categorie' => ['nullable', 'integer'],
            'bassin' => ['nullable', 'in:25,50'],
            'affichage' => ['nullable', 'in:qualifies,proches,tous'],
        ]);
        $affichage = $filtres['affichage'] ?? 'qualifies';

        $lignes = $this->checker->check($grid)
            ->when($filtres['groupe'] ?? null, fn ($c, $g) => $c->filter(fn ($l) => $l['swimmer']->groups->contains('id', (int) $g)))
            ->when($filtres['sexe'] ?? null, fn ($c, $s) => $c->filter(fn ($l) => $l['swimmer']->gender === $s))
            ->when($filtres['categorie'] ?? null, fn ($c, $cat) => $c->filter(fn ($l) => $l['category']->id === (int) $cat))
            ->map(function ($l) use ($filtres) {
                if ($b = $filtres['bassin'] ?? null) {
                    $l['results'] = array_values(array_filter($l['results'], fn ($r) => $r['pool'] === (int) $b));
                    $l['qualified'] = count(array_filter($l['results'], fn ($r) => $r['qualified']));
                    $l['near'] = count(array_filter($l['results'], fn ($r) => $r['near']));
                }

                return $l;
            })
            ->filter(fn ($l) => match ($affichage) {
                'qualifies' => $l['qualified'] > 0,
                'proches' => $l['qualified'] > 0 || $l['near'] > 0,
                default => true,
            })
            ->sortBy([['category.sort_order', 'asc'], fn ($a, $b) => $b['qualified'] <=> $a['qualified']])
            ->values();

        return view('coach.qualifications.show', [
            'grid' => $grid,
            'lignes' => $lignes,
            'filtres' => $filtres + ['affichage' => $affichage],
            'groupes' => PlanningGroup::active()->ordered()->get(['id', 'name']),
            'totalQualifies' => $lignes->filter(fn ($l) => $l['qualified'] > 0)->count(),
            'totalPerfs' => $lignes->sum('qualified'),
        ]);
    }
}
