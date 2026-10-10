<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Fiches personnelles de TEST (tables user_profiles et profile_items).
 * Une fiche par compte de test éditeur et adhérent ; les éléments sont des exemples
 * de ce que l'administration peut y noter (groupe, dossier, certificat…).
 * À lancer après UsersTableSeeder.
 */
class UserProfilesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('profile_items')->truncate();
        DB::table('user_profiles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // courriel => [fonction, note interne, [titre => description]]
        $profiles = [
            'communication@example.com' => [
                'Bénévole — communication',
                'Compte de test éditeur.',
                [
                    'Mission' => 'Publication des actualités, des fiches pratiques, des vidéos « Au fil de l\'eau » et des galeries photo.',
                    'Contact' => 'Les demandes de publication passent par le formulaire de contact du site.',
                ],
            ],
            'adherent@example.com' => [
                null,
                'Compte de test adhérent (groupe jeunes).',
                [
                    'Groupe' => 'Natation jeunes et étudiants (exemple).',
                    'Dossier d\'inscription 2026-2027' => 'Complet : fiche d\'inscription, règlement, attestation de santé (exemple).',
                    'Tests ENF' => 'Sauv\'nage et Pass\'sports de l\'eau validés (exemple).',
                ],
            ],
            'competiteur@example.com' => [
                null,
                'Compte de test nageur compétiteur.',
                [
                    'Groupe' => 'Compétition (exemple).',
                    'Dossier d\'inscription 2026-2027' => 'Complet, fiche sanitaire fournie (exemple).',
                    'Tests ENF' => 'Pass\'compétition validé (exemple).',
                    'Officiel accompagnateur' => 'Parent à contacter pour les déplacements (exemple).',
                ],
            ],
        ];

        $now = now();
        $items = 0;

        foreach ($profiles as $email => [$jobTitle, $note, $entries]) {
            $user = User::where('email', $email)->first();
            if (! $user) {
                $this->command->warn("⚠️  Compte introuvable, fiche ignorée : {$email}");
                continue;
            }

            $profileId = DB::table('user_profiles')->insertGetId([
                'user_id' => $user->id,
                'job_title' => $jobTitle,
                'address' => null,
                'website' => null,
                'admin_notes' => $note,
                'is_visible' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $order = 0;
            foreach ($entries as $title => $description) {
                DB::table('profile_items')->insert([
                    'user_profile_id' => $profileId,
                    'title' => $title,
                    'description' => $description,
                    'sort_order' => ++$order,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $items++;
            }

            $this->command->info("✅ Fiche : {$user->name} ({$order} éléments)");
        }

        $this->command->info("🎉 UserProfilesSeeder : {$items} éléments de fiche créés");
    }
}
