<?php

namespace App\Services;

use App\Models\PlanningSwimmer;
use App\Models\QualificationGrid;
use App\Models\SwimPerformance;
use Illuminate\Support\Collection;

/**
 * Compare les meilleures performances des nageurs du club à une grille de qualification.
 *
 * Règle : qualifié si le meilleur temps est égal ou plus rapide que le temps de la grille (≤),
 * dans le même bassin, sur la période de la grille (si elle est renseignée).
 * Catégorie : celle de la grille qui contient l'année de naissance du nageur.
 */
class QualificationChecker
{
    /** Écart maximal (en %) pour être affiché « proche ». */
    public const NEAR_PCT = 3.0;

    /**
     * @return Collection<int, array{swimmer: PlanningSwimmer, category: \App\Models\QualificationCategory,
     *     results: array<int, array{event: string, pool: int, grid_cs: int, best_cs: ?int, gap: ?int, pct: ?float, qualified: bool, near: bool, date: ?string}>,
     *     qualified: int, near: int}>
     */
    public function check(QualificationGrid $grid, ?Collection $swimmers = null): Collection
    {
        $grid->loadMissing(['categories', 'times']);
        $map = $grid->timeMap();
        $events = $grid->events();

        $swimmers ??= PlanningSwimmer::where('is_active', true)
            ->whereIn('gender', ['F', 'M'])
            ->whereNotNull('birth_year')
            ->with('groups')
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        $perfs = SwimPerformance::query()
            ->whereIn('planning_swimmer_id', $swimmers->pluck('id'))
            ->whereIn('event', $events ?: ['—'])
            ->whereIn('pool', [25, 50])
            ->where('status', 'valid')
            ->whereNotNull('time_cs')
            ->when($grid->period_start, fn ($q) => $q->whereDate('performed_on', '>=', $grid->period_start))
            ->when($grid->period_end, fn ($q) => $q->whereDate('performed_on', '<=', $grid->period_end))
            ->get()
            ->groupBy('planning_swimmer_id');

        $lignes = collect();

        foreach ($swimmers as $nageur) {
            $categorie = $grid->categories->first(fn ($c) => $c->includesYear($nageur->birth_year));
            if (! $categorie || empty($map[$categorie->id])) {
                continue;
            }

            // Meilleur temps par épreuve et bassin
            $meilleurs = [];
            foreach ($perfs->get($nageur->id, collect()) as $p) {
                $cle = $p->event.'|'.$p->pool;
                if (! isset($meilleurs[$cle]) || $p->time_cs < $meilleurs[$cle]->time_cs) {
                    $meilleurs[$cle] = $p;
                }
            }

            $resultats = [];
            foreach ($events as $event) {
                foreach ([25, 50] as $pool) {
                    $temps = $map[$categorie->id][$event][$nageur->gender][$pool] ?? null;
                    if (! $temps) {
                        continue;
                    }
                    $best = $meilleurs[$event.'|'.$pool] ?? null;
                    $gap = $best ? $best->time_cs - $temps : null;
                    $pct = $best ? round($gap / $temps * 100, 2) : null;
                    $resultats[] = [
                        'event' => $event,
                        'pool' => $pool,
                        'grid_cs' => $temps,
                        'best_cs' => $best?->time_cs,
                        'gap' => $gap,
                        'pct' => $pct,
                        'qualified' => $best !== null && $gap <= 0,
                        'near' => $best !== null && $gap > 0 && $pct <= self::NEAR_PCT,
                        'date' => $best?->performed_on?->format('d/m/Y'),
                    ];
                }
            }

            $lignes->push([
                'swimmer' => $nageur,
                'category' => $categorie,
                'results' => $resultats,
                'qualified' => count(array_filter($resultats, fn ($r) => $r['qualified'])),
                'near' => count(array_filter($resultats, fn ($r) => $r['near'])),
                'swum' => count(array_filter($resultats, fn ($r) => $r['best_cs'] !== null)),
            ]);
        }

        return $lignes;
    }

    /**
     * Incohérences probables dans une grille (aide à la relecture après import ou saisie).
     *
     * @param  array<int, array{event: string, gender: string, cells: array<int, ?int>}>  $rows
     * @param  array<int, array{0: string, 1: int}>  $columns  [libellé de catégorie, bassin] par colonne
     * @return array<int, string>
     */
    public function anomalies(array $rows, array $columns): array
    {
        $alertes = [];
        $format = fn ($cs) => \App\Support\SwimTime::format($cs);
        $parCle = [];
        foreach ($rows as $r) {
            $parCle[$r['event'].'|'.$r['gender']] = $r;
        }

        // Deux épreuves avec exactement les mêmes temps
        $vues = [];
        foreach ($rows as $r) {
            $remplies = array_filter($r['cells'], fn ($c) => $c !== null);
            if (count($remplies) < 2) {
                continue;
            }
            $sig = $r['gender'].'|'.json_encode($r['cells']);
            if (isset($vues[$sig])) {
                $alertes[] = "{$r['event']} et {$vues[$sig]} ".($r['gender'] === 'F' ? 'Dames' : 'Messieurs').' ont exactement les mêmes temps.';
            } else {
                $vues[$sig] = $r['event'];
            }
        }

        foreach ($rows as $r) {
            $sexe = $r['gender'] === 'F' ? 'Dames' : 'Messieurs';
            foreach ($r['cells'] as $i => $cs) {
                if ($cs === null || ! isset($columns[$i])) {
                    continue;
                }
                [$cat, $pool] = $columns[$i];

                // Même temps Dames et Messieurs
                if ($r['gender'] === 'F' && ($autre = $parCle[$r['event'].'|M']['cells'][$i] ?? null) === $cs) {
                    $alertes[] = "{$r['event']} {$cat} {$pool} m : même temps Dames et Messieurs ({$format($cs)}).";
                }
                // Bassin de 50 m plus rapide que le bassin de 25 m (même catégorie)
                if ($pool === 50) {
                    foreach ($columns as $j => [$cat2, $pool2]) {
                        if ($cat2 === $cat && $pool2 === 25 && ($t25 = $r['cells'][$j] ?? null) && $cs < $t25) {
                            $alertes[] = "{$r['event']} {$sexe} {$cat} : le temps en 50 m ({$format($cs)}) est plus rapide qu'en 25 m ({$format($t25)}).";
                        }
                    }
                }
                // Catégorie suivante (plus âgée) plus lente, même bassin
                foreach ($columns as $j => [$cat2, $pool2]) {
                    if ($j > $i && $pool2 === $pool && $cat2 !== $cat && ($suiv = $r['cells'][$j] ?? null)) {
                        if ($suiv > $cs) {
                            $alertes[] = "{$r['event']} {$sexe} {$pool} m : {$cat2} ({$format($suiv)}) plus lent que {$cat} ({$format($cs)}).";
                        }
                        break;
                    }
                }
            }
        }

        return array_values(array_unique($alertes));
    }
}
