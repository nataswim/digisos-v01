<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Référentiel des épreuves de natation course : 18 épreuves individuelles et 5 relais.
 * Idempotent : peut être relancé sans créer de doublon (clé « code »).
 */
class SwimEventsSeeder extends Seeder
{
    /** [nage, libellé court, distances] dans l'ordre d'affichage */
    private const EPREUVES = [
        ['nl', 'NL', [50, 100, 200, 400, 800, 1500]],
        ['dos', 'Dos', [50, 100, 200]],
        ['brasse', 'Brasse', [50, 100, 200]],
        ['pap', 'Pap', [50, 100, 200]],
        ['4n', '4 N', [100, 200, 400]],
    ];

    /** Relais : [code, libellé, distance par relayeur, nage] */
    private const RELAIS = [
        ['4x50-nl', '4×50 NL', 50, 'nl'],
        ['4x100-nl', '4×100 NL', 100, 'nl'],
        ['4x200-nl', '4×200 NL', 200, 'nl'],
        ['4x50-4n', '4×50 4 N', 50, '4n'],
        ['4x100-4n', '4×100 4 N', 100, '4n'],
    ];

    public function run(): void
    {
        $now = now();
        $ordre = 0;

        foreach (self::EPREUVES as [$nage, $libelle, $distances]) {
            foreach ($distances as $distance) {
                DB::table('swim_events')->updateOrInsert(
                    ['code' => "{$distance}-{$nage}"],
                    [
                        'stroke' => $nage,
                        'distance' => $distance,
                        'is_relay' => false,
                        'label' => "{$distance} {$libelle}",
                        'sort_order' => ++$ordre,
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }

        foreach (self::RELAIS as [$code, $libelle, $distance, $nage]) {
            DB::table('swim_events')->updateOrInsert(
                ['code' => $code],
                [
                    'stroke' => $nage,
                    'distance' => $distance,
                    'is_relay' => true,
                    'label' => $libelle,
                    'sort_order' => 100 + (++$ordre),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $this->command?->info('Épreuves : ' . DB::table('swim_events')->count());
    }
}
