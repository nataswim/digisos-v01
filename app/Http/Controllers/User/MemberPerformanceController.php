<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Coach\PerformanceController;
use App\Http\Controllers\Controller;
use App\Models\PlanningSwimmer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace adhérent → Mes performances : MPP, historique et progression des nageurs rattachés au compte.
 */
class MemberPerformanceController extends Controller
{
    public function __invoke(Request $request, PerformanceController $performances): View|RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('visitor')) {
            return redirect()->route('visitor.dashboard');
        }

        $swimmers = PlanningSwimmer::where('user_id', $user->id)->orderBy('first_name')->get();
        $swimmer = $swimmers->firstWhere('id', $request->integer('nageur')) ?? $swimmers->first();

        return view('user.performances.index', [
            'swimmers' => $swimmers,
            'swimmer' => $swimmer,
        ] + ($swimmer ? $performances->swimmerData($request, $swimmer) : []));
    }
}
