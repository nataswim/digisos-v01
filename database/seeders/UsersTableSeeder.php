<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * 🇬🇧 Users Table Seeder - Creates 8 test users (2 per role)
 * 🇫🇷 Seeder de la table users - Crée 8 utilisateurs de test (2 par rôle)
 * 
 * @file database/seeders/UsersTableSeeder.php
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🇬🇧 Disable foreign key checks / 🇫🇷 Désactiver les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 🇬🇧 Truncate table / 🇫🇷 Vider la table
        User::truncate();
        
        // 🇬🇧 Re-enable foreign key checks / 🇫🇷 Réactiver les vérifications
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🇬🇧 Get roles / 🇫🇷 Récupérer les rôles
        $adminRole = Role::where('slug', 'admin')->first();
        $editorRole = Role::where('slug', 'editor')->first();
        $userRole = Role::where('slug', 'user')->first();
        $visitorRole = Role::where('slug', 'visitor')->first();

        if (!$adminRole || !$editorRole || !$userRole || !$visitorRole) {
            $this->command->error('❌ Erreur : Les rôles doivent être créés avant d\'exécuter ce seeder !');
            $this->command->error('➡️  Exécutez d\'abord : php artisan db:seed --class=RolesTableSeeder');
            return;
        }

        // 🇬🇧 Default password for all test users / 🇫🇷 Mot de passe par défaut pour tous les utilisateurs
        $defaultPassword = Hash::make('password');

        // 🇬🇧 Define users / 🇫🇷 Définir les utilisateurs
        $users = [
            // ========== ADMINISTRATEURS ==========
            [
                'name' => 'Hassan El Haouat',
                'username' => 'hassan.elhaouat',
                'email' => 'hassan@digitalsos.fr',
                'password' => $defaultPassword,
                'first_name' => 'Hassan',
                'last_name' => 'El Haouat',
                'role_id' => $adminRole->id,
                'bio' => 'Directeur et fondateur de Digital\'SOS. Expert en gestion de structures sportives avec 15 ans d\'expérience dans l\'organisation d\'événements et la formation d\'athlètes.',
                'phone' => '+33 6 12 34 56 78',
                'date_of_birth' => '1985-03-15',
                'status' => 'active',
                'email_verified_at' => now(),
                'avatar' => 'avatars/hassan-elhaouat.jpg',
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => now()->subHours(2),
                'last_login_ip' => '192.168.1.100',
                'login_count' => 156,
            ],
            [
                'name' => 'Sophie Martin',
                'username' => 'sophie.martin',
                'email' => 'sophie.martin@digitalsos.fr',
                'password' => $defaultPassword,
                'first_name' => 'Sophie',
                'last_name' => 'Martin',
                'role_id' => $adminRole->id,
                'bio' => 'Administratrice système et responsable technique. Spécialisée dans la digitalisation des processus de gestion sportive et l\'optimisation des workflows.',
                'phone' => '+33 6 23 45 67 89',
                'date_of_birth' => '1990-07-22',
                'status' => 'active',
                'email_verified_at' => now(),
                'avatar' => 'avatars/sophie-martin.jpg',
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => now()->subHours(5),
                'last_login_ip' => '192.168.1.101',
                'login_count' => 89,
            ],

            // ========== ÉDITEURS ==========
            [
                'name' => 'Jean Dupont',
                'username' => 'jean.dupont',
                'email' => 'jean.dupont@digitalsos.fr',
                'password' => $defaultPassword,
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'role_id' => $editorRole->id,
                'bio' => 'Rédacteur en chef du contenu Digital\'SOS. Ancien journaliste sportif, je crée des ressources pédagogiques pour optimiser l\'entraînement et la performance.',
                'phone' => '+33 6 34 56 78 90',
                'date_of_birth' => '1988-11-05',
                'status' => 'active',
                'email_verified_at' => now(),
                'avatar' => 'avatars/jean-dupont.jpg',
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => now()->subHours(1),
                'last_login_ip' => '192.168.1.102',
                'login_count' => 234,
            ],
            [
                'name' => 'Marie Laurent',
                'username' => 'marie.laurent',
                'email' => 'marie.laurent@digitalsos.fr',
                'password' => $defaultPassword,
                'first_name' => 'Marie',
                'last_name' => 'Laurent',
                'role_id' => $editorRole->id,
                'bio' => 'Coordinatrice de contenu et spécialiste en documentation technique. Je développe les fiches pratiques et vidéos pour les coachs et athlètes.',
                'phone' => '+33 6 45 67 89 01',
                'date_of_birth' => '1992-04-18',
                'status' => 'active',
                'email_verified_at' => now(),
                'avatar' => 'avatars/marie-laurent.jpg',
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => now()->subHours(3),
                'last_login_ip' => '192.168.1.103',
                'login_count' => 187,
            ],

            // ========== UTILISATEURS PREMIUM ==========
            [
                'name' => 'Pierre Dubois',
                'username' => 'pierre.dubois',
                'email' => 'pierre.dubois@example.com',
                'password' => $defaultPassword,
                'first_name' => 'Pierre',
                'last_name' => 'Dubois',
                'role_id' => $userRole->id,
                'bio' => 'Coach sportif indépendant utilisant Digital\'SOS pour gérer mes 3 structures et 45 athlètes. Passionné par l\'optimisation des plannings d\'entraînement.',
                'phone' => '+33 6 56 78 90 12',
                'date_of_birth' => '1987-09-30',
                'status' => 'active',
                'email_verified_at' => now(),
                'avatar' => 'avatars/pierre-dubois.jpg',
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => now()->subDays(1),
                'last_login_ip' => '192.168.1.104',
                'login_count' => 67,
            ],
            [
                'name' => 'Claire Bernard',
                'username' => 'claire.bernard',
                'email' => 'claire.bernard@example.com',
                'password' => $defaultPassword,
                'first_name' => 'Claire',
                'last_name' => 'Bernard',
                'role_id' => $userRole->id,
                'bio' => 'Athlète de haut niveau en natation. J\'utilise Digital\'SOS pour suivre mes séances, gérer mon matériel et consulter les ressources techniques.',
                'phone' => '+33 6 67 89 01 23',
                'date_of_birth' => '1995-01-12',
                'status' => 'active',
                'email_verified_at' => now(),
                'avatar' => 'avatars/claire-bernard.jpg',
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => now()->subHours(8),
                'last_login_ip' => '192.168.1.105',
                'login_count' => 142,
            ],

            // ========== VISITEURS ==========
            [
                'name' => 'Lucas Petit',
                'username' => 'lucas.petit',
                'email' => 'lucas.petit@example.com',
                'password' => $defaultPassword,
                'first_name' => 'Lucas',
                'last_name' => 'Petit',
                'role_id' => $visitorRole->id,
                'bio' => 'Nouveau membre en cours de validation. Intéressé par la gestion de mon club de football amateur.',
                'phone' => '+33 6 78 90 12 34',
                'date_of_birth' => '1998-06-25',
                'status' => 'active',
                'email_verified_at' => null, // 🇬🇧 Not verified yet / 🇫🇷 Pas encore vérifié
                'avatar' => null,
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => now()->subHours(12),
                'last_login_ip' => '192.168.1.106',
                'login_count' => 3,
            ],
            [
                'name' => 'Emma Rousseau',
                'username' => 'emma.rousseau',
                'email' => 'emma.rousseau@example.com',
                'password' => $defaultPassword,
                'first_name' => 'Emma',
                'last_name' => 'Rousseau',
                'role_id' => $visitorRole->id,
                'bio' => 'Découverte de la plateforme pour explorer les ressources gratuites en gestion sportive.',
                'phone' => '+33 6 89 01 23 45',
                'date_of_birth' => '2000-12-08',
                'status' => 'active',
                'email_verified_at' => null, // 🇬🇧 Not verified yet / 🇫🇷 Pas encore vérifié
                'avatar' => null,
                'locale' => 'fr',
                'timezone' => 'Europe/Paris',
                'last_login_at' => now()->subDays(2),
                'last_login_ip' => '192.168.1.107',
                'login_count' => 1,
            ],
        ];

        // 🇬🇧 Create users / 🇫🇷 Créer les utilisateurs
        $roleCounts = [
            'admin' => 0,
            'editor' => 0,
            'user' => 0,
            'visitor' => 0,
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            $roleSlug = Role::find($userData['role_id'])->slug;
            $roleCounts[$roleSlug]++;
            
            $this->command->info("✅ Utilisateur créé : {$user->name} ({$user->email}) - Rôle: {$roleSlug}");
        }

        $this->command->info('');
        $this->command->info('🎉 UsersTableSeeder terminé avec succès !');
        $this->command->info('📊 8 utilisateurs créés :');
        $this->command->info("   → Admins: {$roleCounts['admin']}");
        $this->command->info("   → Editors: {$roleCounts['editor']}");
        $this->command->info("   → Users: {$roleCounts['user']}");
        $this->command->info("   → Visitors: {$roleCounts['visitor']}");
        $this->command->info('');
        $this->command->info('🔑 Mot de passe par défaut pour TOUS les comptes : password');
        $this->command->info('');
        $this->command->table(
            ['Email', 'Rôle', 'Statut Email'],
            [
                ['hassan@digitalsos.fr', 'Admin', '✅ Vérifié'],
                ['sophie.martin@digitalsos.fr', 'Admin', '✅ Vérifié'],
                ['jean.dupont@digitalsos.fr', 'Editor', '✅ Vérifié'],
                ['marie.laurent@digitalsos.fr', 'Editor', '✅ Vérifié'],
                ['pierre.dubois@example.com', 'User', '✅ Vérifié'],
                ['claire.bernard@example.com', 'User', '✅ Vérifié'],
                ['lucas.petit@example.com', 'Visitor', '❌ Non vérifié'],
                ['emma.rousseau@example.com', 'Visitor', '❌ Non vérifié'],
            ]
        );
    }
}
