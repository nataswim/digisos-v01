<?php

namespace App\Services;

use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\PresenceRecord;
use App\Models\PresenceSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Calculs d'assiduité (émargement des séances d'entraînement).
 *
 * Taux de présence = séances « présent » / séances émargées (présent + absent + excusé).
 * Une séance où le nageur n'a pas été émargé n'est pas comptée.
 */
class PresenceStats
{
    public const PERIODS = [
        'mois' => 'Ce mois-ci',
        'trimestre' => '3 derniers mois',
        'saison' => 'Saison en cours',
    ];

    /** @return array{0: Carbon, 1: Carbon} */
    public static function range(?string $period): array
    {
        $today = today();

        return match ($period) {
            'mois' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
            'trimestre' => [$today->copy()->subMonthsNoOverflow(3)->addDay(), $today->copy()],
            default => [self::seasonStart($today), $today->copy()],
        };
    }

    /** La saison commence le 1er septembre. */
    public static function seasonStart(Carbon $date): Carbon
    {
        $year = $date->month >= 9 ? $date->year : $date->year - 1;

        return Carbon::create($year, 9, 1)->startOfDay();
    }

    /** @param array{present?: int, absent?: int, excuse?: int} $c */
    public static function rate(array $c): ?int
    {
        $total = ($c['present'] ?? 0) + ($c['absent'] ?? 0) + ($c['excuse'] ?? 0);

        return $total ? (int) round(($c['present'] ?? 0) * 100 / $total) : null;
    }

    /**
     * Compteurs par nageur sur la période.
     *
     * @return Collection<int, array{present: int, absent: int, excuse: int, total: int, rate: ?int, last: ?string}>  clé = id du nageur
     */
    public function bySwimmer(Carbon $from, Carbon $to, ?Collection $swimmerIds = null): Collection
    {
        $rows = PresenceRecord::query()
            ->join('presence_sessions', 'presence_sessions.id', '=', 'presence_records.presence_session_id')
            ->whereBetween('presence_sessions.date', [$from->toDateString(), $to->toDateString() . ' 23:59:59'])
            ->when($swimmerIds, fn ($q) => $q->whereIn('presence_records.planning_swimmer_id', $swimmerIds))
            ->groupBy('presence_records.planning_swimmer_id', 'presence_records.status')
            ->select('presence_records.planning_swimmer_id as swimmer_id', 'presence_records.status', DB::raw('count(*) as total'),
                DB::raw('max(presence_sessions.date) as last_date'))
            ->get();

        return $rows->groupBy('swimmer_id')->map(function ($lignes) {
            $c = ['present' => 0, 'absent' => 0, 'excuse' => 0];
            foreach ($lignes as $l) {
                $c[$l->status] = (int) $l->total;
            }
            $present = $lignes->firstWhere('status', 'present');

            return $c + [
                'total' => array_sum($c),
                'rate' => self::rate($c),
                'last' => $present?->last_date,
            ];
        });
    }

    /**
     * Fréquentation par créneau : nombre de séances, présents en moyenne, taux.
     *
     * @return Collection<int, array>
     */
    public function bySlot(Carbon $from, Carbon $to, ?int $groupId = null): Collection
    {
        $sessions = PresenceSession::with(['slot.group', 'group'])
            ->withCount([
                'records',
                'records as present_count' => fn ($q) => $q->where('status', 'present'),
                'records as absent_count' => fn ($q) => $q->where('status', 'absent'),
                'records as excuse_count' => fn ($q) => $q->where('status', 'excuse'),
            ])
            ->whereBetween('date', [$from->toDateString(), $to->toDateString() . ' 23:59:59'])
            ->when($groupId, fn ($q) => $q->where('planning_group_id', $groupId))
            ->get();

        return $sessions
            ->groupBy(fn ($s) => $s->planning_slot_id ?? 'extra')
            ->map(function (Collection $seances, $key) {
                $first = $seances->first();
                $c = [
                    'present' => (int) $seances->sum('present_count'),
                    'absent' => (int) $seances->sum('absent_count'),
                    'excuse' => (int) $seances->sum('excuse_count'),
                ];

                return [
                    'key' => $key,
                    'slot' => $key === 'extra' ? null : $first->slot,
                    'label' => $key === 'extra' ? 'Séances supplémentaires' : ($first->group?->name ?? 'Groupe supprimé'),
                    'when' => $key === 'extra' ? '' : (($first->slot?->day_name ?? '') . ' ' . $first->time_range),
                    'sessions' => $seances->count(),
                    'average' => $seances->count() ? round($c['present'] / $seances->count(), 1) : 0,
                    'rate' => self::rate($c),
                ] + $c;
            })
            ->sortBy(fn ($r) => [$r['key'] === 'extra' ? 9 : ($r['slot']?->day_of_week ?? 8), $r['slot']?->starts_at ?? ''])
            ->values();
    }

    /**
     * Tableau mensuel d'un groupe : séances du mois × nageurs.
     *
     * @return array{sessions: Collection, swimmers: Collection, cells: array, totals: array}
     */
    public function monthGrid(PlanningGroup $group, Carbon $month): array
    {
        $sessions = PresenceSession::with('records')
            ->where('planning_group_id', $group->id)
            ->whereBetween('date', [$month->copy()->startOfMonth()->toDateString(), $month->copy()->endOfMonth()->toDateString() . ' 23:59:59'])
            ->orderBy('date')->orderBy('starts_at')
            ->get();

        $cells = [];
        foreach ($sessions as $session) {
            foreach ($session->records as $record) {
                $cells[$record->planning_swimmer_id][$session->id] = $record->status;
            }
        }

        $swimmers = $group->swimmers()->where('is_active', true)->get()
            ->concat(PlanningSwimmer::whereIn('id', array_keys($cells))->get())
            ->unique('id')
            ->sortBy(fn ($s) => mb_strtolower($s->last_name . ' ' . $s->first_name))
            ->values();

        $totals = [];
        foreach ($swimmers as $swimmer) {
            $c = array_count_values($cells[$swimmer->id] ?? []);
            $totals[$swimmer->id] = ['present' => $c['present'] ?? 0, 'total' => array_sum($c), 'rate' => self::rate($c)];
        }

        return compact('sessions', 'swimmers', 'cells', 'totals');
    }

    /**
     * Fréquentation d'un nageur par créneau sur la période.
     *
     * @return Collection<int, array>
     */
    public function swimmerBySlot(PlanningSwimmer $swimmer, Carbon $from, Carbon $to): Collection
    {
        return PresenceRecord::with('session.slot', 'session.group')
            ->where('planning_swimmer_id', $swimmer->id)
            ->whereHas('session', fn ($q) => $q->whereBetween('date', [$from->toDateString(), $to->toDateString() . ' 23:59:59']))
            ->get()
            ->groupBy(fn ($r) => $r->session->planning_slot_id ?? 'extra')
            ->map(function (Collection $records, $key) {
                $session = $records->first()->session;
                $c = $records->countBy('status')->all();

                return [
                    'label' => $key === 'extra' ? 'Séances supplémentaires' : (($session->slot?->day_name ?? '') . ' ' . $session->time_range . ' · ' . ($session->group?->name ?? '')),
                    'order' => $key === 'extra' ? 99 : ($session->slot?->day_of_week ?? 98),
                    'present' => $c['present'] ?? 0,
                    'absent' => $c['absent'] ?? 0,
                    'excuse' => $c['excuse'] ?? 0,
                    'rate' => self::rate($c),
                ];
            })
            ->sortBy('order')
            ->values();
    }
}
