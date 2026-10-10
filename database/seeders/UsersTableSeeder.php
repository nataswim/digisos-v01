<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Comptes de TEST du site du CNBB (un par rôle, plus un adhérent compétiteur).
 *
 * - Adresses en @example.com (domaine réservé : aucun courriel réel ne peut partir).
 * - Le compte administrateur et le mot de passe commun peuvent être définis dans .env :
 *     SEED_ADMIN_EMAIL=...   SEED_ADMIN_NAME="..."   SEED_PASSWORD=...
 * - Avant la mise en ligne : supprimer ces comptes ou changer leurs mots de passe.
 */
class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $roles = Role::pluck('id', 'slug');
        foreach (['admin', 'editor', 'user', 'visitor'] as $slug) {
            if (! isset($roles[$slug])) {
                $this->command->error("❌ Rôle « {$slug} » absent : lancer RolesTableSeeder avant ce seeder.");
                return;
            }
        }

        $plainPassword = env('SEED_PASSWORD', 'password');
        $password = Hash::make($plainPassword);

        $adminName = env('SEED_ADMIN_NAME', 'Administrateur CNBB');
        [$adminFirst, $adminLast] = array_pad(explode(' ', $adminName, 2), 2, '');

        // [rôle, prénom, nom, identifiant, courriel, bio, courriel vérifié]
        $accounts = [
            ['admin', $adminFirst, $adminLast, 'admin.cnbb', env('SEED_ADMIN_EMAIL', 'admin@example.com'),
                'Compte d\'administration du site du Cercle des Nageurs du Bocage Bressuirais.', true],
            ['editor', 'Camille', 'Rédaction', 'communication.cnbb', 'communication@example.com',
                'Bénévole chargé·e de la communication : actualités, fiches pratiques, vidéos et galeries.', true],
            ['user', 'Léa', 'Nageuse', 'lea.nageuse', 'adherent@example.com',
                'Adhérente du groupe jeunes, compte de test pour les contenus réservés aux adhérents.', true],
            ['user', 'Tom', 'Compétiteur', 'tom.competiteur', 'competiteur@example.com',
                'Nageur compétiteur, compte de test.', true],
            ['visitor', 'Paul', 'Visiteur', 'paul.visiteur', 'visiteur@example.com',
                'Compte créé sur le site, adhésion pas encore validée.', true],
            ['visitor', 'Inès', 'Nouvelle', 'ines.nouvelle', 'nouveau@example.com',
                'Compte dont l\'adresse n\'est pas encore confirmée (test de la vérification par courriel).', false],
        ];

        $rows = [];
        foreach ($accounts as [$role, $first, $last, $username, $email, $bio, $verified]) {
            User::create([
                'name' => trim("{$first} {$last}"),
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'first_name' => $first,
                'last_name' => $last,
                'role_id' => $roles[$role],
                'bio' => $bio,
                'phone' => null,
                'date_of_birth' => null,
                'status' => 'active',
                'email_verified_at' => $verified ? now() : null,
                'avatar' => null,
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => null,
                'last_login_ip' => null,
                'login_count' => 0,
            ]);
            $rows[] = [$role, $email, $verified ? '✅' : '❌ à confirmer'];
        }

        $this->command->info('🎉 UsersTableSeeder : ' . count($rows) . ' comptes de test créés');
        $this->command->table(['Rôle', 'Courriel', 'Vérifié'], $rows);

        if ($plainPassword === 'password') {
            $this->command->warn('⚠️  Mot de passe commun : « password ». Définir SEED_PASSWORD dans .env pour un serveur en ligne.');
        }
    }
}
