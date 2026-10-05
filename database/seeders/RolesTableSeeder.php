<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

/**
 * 🇬🇧 Roles Table Seeder - Creates the 4 base roles for Mon Club de Natation
 * 🇫🇷 Seeder de la table roles - Crée les 4 rôles de base pour Mon Club de Natation
 * 
 * @file database/seeders/RolesTableSeeder.php
 */
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🇬🇧 Disable foreign key checks / 🇫🇷 Désactiver les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 🇬🇧 Truncate table / 🇫🇷 Vider la table
        Role::truncate();
        
        // 🇬🇧 Re-enable foreign key checks / 🇫🇷 Réactiver les vérifications
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🇬🇧 Define base roles / 🇫🇷 Définir les rôles de base
        $roles = [
            [
                'name' => 'Administrateur',
                'slug' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Accès complet au système avec gestion des utilisateurs, permissions et configuration globale.',
                'level' => 100,
                'is_default' => false,
            ],
            [
                'name' => 'Éditeur',
                'slug' => 'editor',
                'display_name' => 'Editor',
                'description' => 'Rédacteur pouvant créer, modifier et publier du contenu (posts, fiches, pages, vidéos).',
                'level' => 50,
                'is_default' => false,
            ],
            [
                'name' => 'Utilisateur',
                'slug' => 'user',
                'display_name' => 'User',
                'description' => 'Utilisateur vérifié avec accès au contenu premium et aux fonctionnalités avancées.',
                'level' => 10,
                'is_default' => false,
            ],
            [
                'name' => 'Visiteur',
                'slug' => 'visitor',
                'display_name' => 'Visitor',
                'description' => 'Visiteur non-vérifié avec accès limité au contenu public uniquement.',
                'level' => 0,
                'is_default' => true, // 🇬🇧 Default role for new registrations / 🇫🇷 Rôle par défaut à l'inscription
            ],
        ];

        // 🇬🇧 Create roles / 🇫🇷 Créer les rôles
        foreach ($roles as $roleData) {
            Role::create($roleData);
            
            $this->command->info("✅ Rôle créé : {$roleData['display_name']} (level {$roleData['level']})");
        }

        $this->command->info('');
        $this->command->info('🎉 RolesTableSeeder terminé avec succès !');
        $this->command->info('📊 4 rôles créés : Admin, Editor, User, Visitor');
    }
}
