<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Nouvelles épreuves : 25 m de chaque nage, 50 et 100 Divers (2 nages), relais 10×50 NL.
 * Remet aussi l'ordre d'affichage par pas de 10 (25, 50, 100… dans chaque nage, relais à la fin)
 * pour laisser de la place aux épreuves ajoutées depuis Compétitions → Épreuves.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new \Database\Seeders\SwimEventsSeeder())->run();

        // Ordre : celui du référentiel pour les épreuves connues (les autres gardent le leur).
        $ordre = 0;
        foreach (\Database\Seeders\SwimEventsSeeder::EPREUVES as [$nage, $distances]) {
            foreach ($distances as $distance) {
                DB::table('swim_events')->where('code', "{$distance}-{$nage}")->update(['sort_order' => (++$ordre) * 10]);
            }
        }
        foreach (\Database\Seeders\SwimEventsSeeder::RELAIS as [$relayeurs, $distance, $nage]) {
            DB::table('swim_events')->where('code', "{$relayeurs}x{$distance}-{$nage}")->update(['sort_order' => 1000 + (++$ordre) * 10]);
        }
    }

    public function down(): void
    {
        foreach (['25-nl', '25-dos', '25-brasse', '25-pap', '50-divers', '100-divers', '10x50-nl'] as $code) {
            $id = DB::table('swim_events')->where('code', $code)->value('id');
            if ($id && ! DB::table('competition_events')->where('swim_event_id', $id)->exists()
                && ! DB::table('competition_entries')->where('swim_event_id', $id)->exists()) {
                DB::table('swim_events')->where('id', $id)->delete();
            }
        }
    }
};
