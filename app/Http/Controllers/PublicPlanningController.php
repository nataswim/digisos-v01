<?php

namespace App\Http\Controllers;

use App\Models\PlanningGroup;
use App\Models\PlanningSlot;
use Illuminate\Http\Request;

/**
 * Page publique « Groupes et horaires » (module Planning, géré dans l'administration).
 *
 * - Tout le monde voit les créneaux de la période choisie (scolaire / vacances).
 * - La composition des groupes (prénom + initiale du nom) n'est envoyée à la vue
 *   que pour les adhérents, éditeurs et administrateurs connectés.
 */
class PublicPlanningController extends Controller
{
    public function __invoke(Request $request)
    {
        $period = array_key_exists($request->query('periode'), PlanningSlot::PERIODS)
            ? $request->query('periode')
            : 'scolaire';

        $slots = PlanningSlot::with('group')
            ->active()
            ->period($period)
            ->whereHas('group', fn ($q) => $q->where('is_active', true))
            ->chronological()
            ->get();

        // Créneaux par jour ; un même horaire partagé par plusieurs groupes reste sur une ligne par groupe
        $planning = $slots->groupBy('day_of_week')->sortKeys();

        $counts = PlanningSlot::active()
            ->whereHas('group', fn ($q) => $q->where('is_active', true))
            ->selectRaw('period, count(*) as total')
            ->groupBy('period')
            ->pluck('total', 'period');

        $user = $request->user();
        $canSeeSwimmers = $user && ! $user->hasRole('visitor');

        // Groupes présents sur ce planning, avec leurs nageurs actifs si autorisé
        $groups = PlanningGroup::active()
            ->ordered()
            ->whereIn('id', $slots->pluck('planning_group_id')->unique())
            ->when($canSeeSwimmers, fn ($q) => $q->with(['swimmers' => fn ($s) => $s->where('is_active', true)]))
            ->withCount(['swimmers' => fn ($s) => $s->where('is_active', true)])
            ->get();

        return view('public.schedule', [
            'period' => $period,
            'planning' => $planning,
            'counts' => $counts,
            'groups' => $groups,
            'sections' => PlanningGroup::SECTIONS,
            'canSeeSwimmers' => $canSeeSwimmers,
        ]);
    }
}
