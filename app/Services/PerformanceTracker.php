<?php

namespace App\Services;

use App\Models\SwimPerformance;
use Illuminate\Support\Collection;

/**
 * Calculs du suivi des performances.
 *
 * Pour chaque temps validé, on le compare à la meilleure performance personnelle (MPP) du nageur
 * sur la même épreuve et le même bassin, établie AVANT cette course :
 *   écart = temps − MPP (négatif = plus rapide) ; % = écart ÷ MPP × 100 ; record perso si temps < MPP.
 * En finale, on compare aussi avec la série de la même réunion.
 */
class PerformanceTracker
{
    /** Tri chronologique : date, puis tour (série avant finale), puis saisie. */
    public static function chronological(Collection $perfs): Collection
    {
        return $perfs->sortBy(fn ($p) => [$p->performed_on->toDateString(), SwimPerformance::roundOrder($p->round), $p->id])->values();
    }

    /**
     * Analyse de toutes les performances d'un ou plusieurs nageurs.
     *
     * @return array<int, array{prev_best: ?int, gap: ?int, pct: ?float, pb: bool, first: bool, vs_serie: ?int, vs_serie_pct: ?float}>  clé = id de la performance
     */
    public function analyse(Collection $perfs): array
    {
        $result = [];
        $bests = [];       // swimmer|event|pool => meilleur temps jusqu'ici
        $series = [];      // swimmer|event|pool|réunion => temps de la série

        foreach (self::chronological($perfs) as $p) {
            $key = $p->planning_swimmer_id . '|' . $p->event . '|' . $p->pool;
            $info = ['prev_best' => $bests[$key] ?? null, 'gap' => null, 'pct' => null, 'pb' => false, 'first' => false, 'vs_serie' => null, 'vs_serie_pct' => null];

            if ($p->isValid()) {
                if ($info['prev_best'] === null) {
                    $info['first'] = true;
                } else {
                    $info['gap'] = $p->time_cs - $info['prev_best'];
                    $info['pct'] = round($info['gap'] * 100 / $info['prev_best'], 1);
                    $info['pb'] = $p->time_cs < $info['prev_best'];
                }

                $reunion = $key . '|' . $p->meet_key;
                if ($p->round === 'serie') {
                    $series[$reunion] = $p->time_cs;
                } elseif (isset($series[$reunion]) && $p->round !== 'clm') {
                    $info['vs_serie'] = $p->time_cs - $series[$reunion];
                    $info['vs_serie_pct'] = round($info['vs_serie'] * 100 / $series[$reunion], 1);
                }

                $bests[$key] = min($bests[$key] ?? PHP_INT_MAX, $p->time_cs);
            }

            $result[$p->id] = $info;
        }

        return $result;
    }

    /**
     * Analyse d'une sélection de performances (bilan, liste) : on charge tout l'historique
     * des nageurs concernés pour que les écarts tiennent compte des courses précédentes.
     */
    public function analyseSubset(Collection $subset): array
    {
        if ($subset->isEmpty()) {
            return [];
        }
        $all = SwimPerformance::whereIn('planning_swimmer_id', $subset->pluck('planning_swimmer_id')->unique())
            ->whereIn('event', $subset->pluck('event')->unique())
            ->get();

        return array_intersect_key($this->analyse($all), $subset->keyBy('id')->all());
    }

    /**
     * Meilleures performances personnelles : [bassin][épreuve] => performance.
     *
     * @return array<int, array<string, SwimPerformance>>
     */
    public function bests(Collection $perfs): array
    {
        $bests = [25 => [], 50 => []];
        foreach (self::chronological($perfs->filter->isValid()) as $p) {
            $actuel = $bests[$p->pool][$p->event] ?? null;
            if (! $actuel || $p->time_cs < $actuel->time_cs) {
                $bests[$p->pool][$p->event] = $p;
            }
        }
        foreach ($bests as $pool => $list) {
            uksort($list, fn ($a, $b) => SwimPerformance::eventOrder($a) <=> SwimPerformance::eventOrder($b));
            $bests[$pool] = $list;
        }

        return $bests;
    }

    /**
     * Points d'un graphique de progression (SVG) pour une épreuve et un bassin.
     *
     * @return array{points: array<int, array>, min: int, max: int}|null
     */
    public function chart(Collection $perfs, string $event, int $pool, int $width = 640, int $height = 220): ?array
    {
        $liste = self::chronological($perfs->filter(fn ($p) => $p->isValid() && $p->event === $event && $p->pool === $pool))->values();
        if ($liste->count() < 2) {
            return null;
        }

        $pad = 36;
        $min = $liste->min('time_cs');
        $max = $liste->max('time_cs');
        $ecart = max(1, $max - $min);
        $n = $liste->count();
        $best = PHP_INT_MAX;

        $points = $liste->map(function ($p, $i) use ($pad, $width, $height, $min, $ecart, $n, &$best) {
            $pb = $p->time_cs < $best;
            $best = min($best, $p->time_cs);

            return [
                'x' => round($pad + ($width - 2 * $pad) * ($n > 1 ? $i / ($n - 1) : 0.5), 1),
                // temps le plus rapide en haut
                'y' => round($pad / 2 + ($height - $pad * 1.5) * (($p->time_cs - $min) / $ecart), 1),
                'time' => $p->time_label,
                'date' => $p->performed_on->format('d/m/Y'),
                'label' => $p->meet_label . ' · ' . $p->round_label,
                'pb' => $pb,
            ];
        })->all();

        return ['points' => $points, 'min' => $min, 'max' => $max, 'width' => $width, 'height' => $height];
    }

    /** « −1.12 » / « +0.84 » */
    public static function gapLabel(?int $gap): string
    {
        return $gap === null ? '' : \App\Models\SwimRecord::formatGap($gap);
    }

    /** « −1,7 % » / « +1,3 % » */
    public static function pctLabel(?float $pct): string
    {
        if ($pct === null) {
            return '';
        }

        return ($pct > 0 ? '+' : ($pct < 0 ? '−' : '')) . number_format(abs($pct), 1, ',', ' ') . ' %';
    }
}
