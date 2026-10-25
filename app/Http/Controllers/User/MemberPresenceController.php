<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PlanningSwimmer;
use App\Models\PresenceRecord;
use App\Services\PresenceStats;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace adhérent → Assiduité : présences aux entraînements des nageurs rattachés au compte
 * (consultation seulement, saison en cours).
 */
class MemberPresenceController extends Controller
{
    public function __invoke(Request $request, PresenceStats $stats): View|RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('visitor')) {
            return redirect()->route('visitor.dashboard');
        }

        [$from, $to] = PresenceStats::range('saison');
        $swimmers = PlanningSwimmer::where('user_id', $user->id)->with('groups')->orderBy('first_name')->get();
        $counts = $stats->bySwimmer($from, $to, $swimmers->pluck('id'));

        $history = PresenceRecord::with('session.group')
            ->whereIn('planning_swimmer_id', $swimmers->pluck('id'))
            ->whereHas('session', fn ($q) => $q->whereBetween('date', [$from->toDateString(), $to->toDateString() . ' 23:59:59']))
            ->get()
            ->sortByDesc(fn ($r) => $r->session->date->toDateString() . ($r->session->starts_at ?? ''))
            ->groupBy('planning_swimmer_id');

        return view('user.presence.index', compact('swimmers', 'counts', 'history', 'from', 'to'));
    }
}
