<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CoachDocument;
use App\Models\Competition;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\SwimmerNote;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace coachs : tableau de bord (accès coachs et administrateurs).
 */
class CoachDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $safe = function (callable $fn, $default) {
            try {
                return $fn();
            } catch (\Throwable $e) {
                report($e);

                return $default;
            }
        };

        return view('coach.dashboard', [
            'user' => $request->user(),
            'counts' => [
                'groups' => $safe(fn () => PlanningGroup::active()->count(), 0),
                'swimmers' => $safe(fn () => PlanningSwimmer::active()->count(), 0),
                'competitions' => $safe(fn () => Competition::upcoming()->count(), 0),
                'documents' => $safe(fn () => CoachDocument::count(), 0),
            ],
            'competitions' => $safe(fn () => Competition::upcoming()->withCount('attendances')->limit(5)->get(), collect()),
            'notes' => $safe(fn () => SwimmerNote::with(['swimmer', 'author'])->latest('noted_on')->latest('id')->limit(5)->get(), collect()),
            'documents' => $safe(fn () => CoachDocument::latest()->limit(5)->get(), collect()),
        ]);
    }
}
