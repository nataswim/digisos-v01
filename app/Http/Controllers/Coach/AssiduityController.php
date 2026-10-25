<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\PresenceRecord;
use App\Services\PresenceStats;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Espace coachs → Assiduité : taux de présence par nageur, fréquentation par créneau,
 * tableau mensuel d'un groupe, fiche d'un nageur.
 */
class AssiduityController extends Controller
{
    public function __construct(private PresenceStats $stats)
    {
    }

    /** Vue « nageurs » (par défaut) ou « créneaux ». */
    public function index(Request $request): View
    {
        $period = array_key_exists($request->query('periode'), PresenceStats::PERIODS) ? $request->query('periode') : 'saison';
        [$from, $to] = PresenceStats::range($period);
        $groupId = $request->integer('groupe') ?: null;
        $view = $request->query('vue') === 'creneaux' ? 'creneaux' : 'nageurs';

        $data = [
            'period' => $period,
            'from' => $from,
            'to' => $to,
            'groupId' => $groupId,
            'groups' => PlanningGroup::ordered()->get(),
            'vue' => $view,
        ];

        if ($view === 'creneaux') {
            $data['slots'] = $this->stats->bySlot($from, $to, $groupId);
        } else {
            $swimmers = PlanningSwimmer::active()->with('groups')
                ->when($groupId, fn ($q) => $q->whereHas('groups', fn ($g) => $g->where('planning_groups.id', $groupId)))
                ->orderBy('last_name')->orderBy('first_name')
                ->get();
            $data['swimmers'] = $swimmers;
            $data['counts'] = $this->stats->bySwimmer($from, $to, $swimmers->pluck('id'));
        }

        return view('coach.assiduity.index', $data);
    }

    /** Fiche d'assiduité d'un nageur. */
    public function swimmer(Request $request, PlanningSwimmer $swimmer): View
    {
        $period = array_key_exists($request->query('periode'), PresenceStats::PERIODS) ? $request->query('periode') : 'saison';
        [$from, $to] = PresenceStats::range($period);

        return view('coach.assiduity.swimmer', [
            'swimmer' => $swimmer->load('groups'),
            'period' => $period,
            'from' => $from,
            'to' => $to,
            'counts' => $this->stats->bySwimmer($from, $to, collect([$swimmer->id]))->get($swimmer->id),
            'bySlot' => $this->stats->swimmerBySlot($swimmer, $from, $to),
            'history' => PresenceRecord::with('session.group')
                ->where('planning_swimmer_id', $swimmer->id)
                ->whereHas('session', fn ($q) => $q->whereBetween('date', [$from->toDateString(), $to->toDateString() . ' 23:59:59']))
                ->get()
                ->sortByDesc(fn ($r) => $r->session->date->toDateString() . ($r->session->starts_at ?? ''))
                ->values(),
        ]);
    }

    /** Tableau mensuel d'un groupe : nageurs × séances. */
    public function group(Request $request, PlanningGroup $group): View
    {
        try {
            $month = Carbon::createFromFormat('Y-m', (string) $request->query('mois'))->startOfMonth();
        } catch (\Throwable $e) {
            $month = today()->startOfMonth();
        }

        return view('coach.assiduity.group', ['group' => $group, 'month' => $month, 'groups' => PlanningGroup::ordered()->get()]
            + $this->stats->monthGrid($group, $month));
    }
}
