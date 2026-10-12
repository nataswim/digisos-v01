<?php

namespace Database\Seeders;

use App\Models\PlanningGroup;
use App\Models\PlanningSlot;
use App\Support\ClubGroupes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Données de départ du module Planning : les groupes et les créneaux de la période scolaire
 * 2026-2027 (source : App\Support\ClubGroupes, document d'organisation du club).
 *
 * - Aucun nageur n'est créé : ils se saisissent dans l'administration (Planning > Nageurs).
 * - Le planning « Vacances scolaires » est vide : les stages sont ajoutés au fil de la saison.
 * - Les 3 groupes Maîtres partagent les mêmes créneaux : un seul groupe « Maîtres » est créé.
 *
 * Attention : ce seeder vide les tables du planning (groupes, nageurs, créneaux).
 */
class PlanningSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('planning_slots')->truncate();
        DB::table('planning_group_swimmer')->truncate();
        DB::table('planning_swimmers')->truncate();
        DB::table('planning_groups')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $ordre = 0;
        $nbCreneaux = 0;

        foreach (ClubGroupes::GROUPES as $g) {
            $group = PlanningGroup::create([
                'name' => $g['titre'],
                'section' => $g['section'],
                'description' => $g['age'] . ' — ' . $g['resume'],
                'page_url' => '/pages/nos-groupes/' . $g['slug'],
                'sort_order' => ++$ordre * 10,
                'is_active' => true,
            ]);

            foreach ($g['creneaux'] as [$jour, $horaire, $espace]) {
                $this->creneau($group, $jour, $horaire, $espace);
                $nbCreneaux++;
            }
        }

        $maitres = PlanningGroup::create([
            'name' => 'Maîtres',
            'section' => 'Maîtres',
            'description' => '21 ans et plus — groupes Arc Atlantique, Aqua-Défis « 2000 » et Aqua-Masters « 1500 ».',
            'page_url' => '/pages/nos-groupes/section-maitres',
            'sort_order' => ++$ordre * 10,
            'is_active' => true,
        ]);

        foreach (ClubGroupes::MAITRES_CRENEAUX as [$jour, $horaire]) {
            $this->creneau($maitres, $jour, $horaire, '2 couloirs');
            $nbCreneaux++;
        }

        $this->command->info("🎉 PlanningSeeder : {$ordre} groupes, {$nbCreneaux} créneaux (période scolaire)");
        $this->command->warn('⚠️  Nageurs à saisir dans l\'administration : Planning > Nageurs.');
    }

    /** Crée un créneau scolaire à partir de « Mercredi », « 17 h 30 – 19 h », « 2 couloirs ». */
    private function creneau(PlanningGroup $group, string $jour, string $horaire, string $espace): void
    {
        [$debut, $fin] = array_map([$this, 'heure'], preg_split('/\s*–\s*/u', $horaire));

        PlanningSlot::create([
            'planning_group_id' => $group->id,
            'period' => 'scolaire',
            'day_of_week' => array_search($jour, PlanningSlot::DAYS, true),
            'starts_at' => $debut,
            'ends_at' => $fin,
            'location' => 'Centre aquatique Cœur d\'O',
            'lanes' => $espace,
            'is_active' => true,
        ]);
    }

    /** « 17 h 30 » → « 17:30 » */
    private function heure(string $texte): string
    {
        preg_match('/(\d{1,2}) h(?: (\d{2}))?/u', $texte, $m);

        return sprintf('%02d:%02d', (int) $m[1], (int) ($m[2] ?? 0));
    }
}
