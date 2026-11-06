<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

/**
 * Référentiel des épreuves de natation course : épreuves individuelles (dont les 25 m et les
 * « Divers » en 2 nages) et relais.
 * Idempotent (clé « code ») : une épreuve existante garde le libellé, l'ordre et l'état
 * (active / désactivée) choisis dans Compétitions → Épreuves ; seules les manquantes sont ajoutées.
 */
class SwimEventsSeeder extends Seeder
{
    /** [nage, distances] dans l'ordre d'affichage */
    public const EPREUVES = [
        ['nl', [25, 50, 100, 200, 400, 800, 1500]],
        ['dos', [25, 50, 100, 200]],
        ['brasse', [25, 50, 100, 200]],
        ['pap', [25, 50, 100, 200]],
        ['4n', [100, 200, 400]],
        ['divers', [50, 100]],
    ];

    /** Relais : [relayeurs, distance par relayeur, nage] */
    public const RELAIS = [
        [4, 50, 'nl'],
        [4, 100, 'nl'],
        [4, 200, 'nl'],
        [10, 50, 'nl'],
        [4, 50, '4n'],
        [4, 100, '4n'],
    ];

    public function run(): void
    {
        $now = now();
        $ordre = 0;
        $lignes = [];

        foreach (self::EPREUVES as [$nage, $distances]) {
            foreach ($distances as $distance) {
                [$code, $label] = \App\Models\SwimEvent::suggest($nage, $distance);
                $lignes[] = [$code, $nage, $distance, false, $label, (++$ordre) * 10];
            }
        }
        foreach (self::RELAIS as [$relayeurs, $distance, $nage]) {
            [$code, $label] = \App\Models\SwimEvent::suggest($nage, $distance, $relayeurs);
            $lignes[] = [$code, $nage, $distance, true, $label, 1000 + (++$ordre) * 10];
        }

        foreach ($lignes as [$code, $nage, $distance, $relais, $label, $tri]) {
            if (DB::table('swim_events')->where('code', $code)->exists()) {
                continue;
            }
            DB::table('swim_events')->insert([
                'code' => $code, 'stroke' => $nage, 'distance' => $distance, 'is_relay' => $relais,
                'label' => $label, 'sort_order' => $tri, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
}
