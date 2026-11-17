<?php

namespace App\Http\Controllers;

use App\Models\QualificationGrid;
use App\Models\SwimPerformance;
use Illuminate\View\View;

/**
 * Pages publiques des grilles de qualification : uniquement les temps, jamais de nom de nageur.
 * Seules les grilles publiées par l'administrateur sont visibles.
 */
class PublicQualificationController extends Controller
{
    public function index(): View
    {
        $grids = QualificationGrid::published()->ordered()->withCount('categories')->get();

        return view('public.qualifications.index', compact('grids'));
    }

    public function show(string $slug): View
    {
        $grid = QualificationGrid::published()->where('slug', $slug)->with(['categories', 'times'])->firstOrFail();

        return view('public.qualifications.show', [
            'grid' => $grid,
            'map' => $grid->timeMap(),
            'events' => $grid->events(),
            'others' => QualificationGrid::published()->ordered()->where('id', '!=', $grid->id)->get(['name', 'slug']),
            'strokes' => ['NL' => 'Nage libre', 'Dos' => 'Dos', 'Brasse' => 'Brasse', 'Pap' => 'Papillon', '4N' => '4 nages'],
        ]);
    }
}
