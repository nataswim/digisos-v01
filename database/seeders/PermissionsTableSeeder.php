<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

/**
 * 🇬🇧 Permissions Table Seeder - Creates CRUD permissions for Digital'SOS modules
 * 🇫🇷 Seeder de la table permissions - Crée les permissions CRUD pour les modules Digital'SOS
 * 
 * @file database/seeders/PermissionsTableSeeder.php
 */
class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🇬🇧 Disable foreign key checks / 🇫🇷 Désactiver les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 🇬🇧 Truncate table / 🇫🇷 Vider la table
        Permission::truncate();
        
        // 🇬🇧 Re-enable foreign key checks / 🇫🇷 Réactiver les vérifications
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🇬🇧 Define permissions by module / 🇫🇷 Définir les permissions par module
        $permissions = [
            // ========== GESTION UTILISATEURS ==========
            [
                'name' => 'Voir les utilisateurs',
                'slug' => 'users.view',
                'group' => 'users',
                'description' => 'Consulter la liste des utilisateurs et leurs profils',
            ],
            [
                'name' => 'Gérer les utilisateurs',
                'slug' => 'users.manage',
                'group' => 'users',
                'description' => 'Créer, modifier et activer/désactiver les utilisateurs',
            ],
            [
                'name' => 'Supprimer les utilisateurs',
                'slug' => 'users.delete',
                'group' => 'users',
                'description' => 'Supprimer définitivement les utilisateurs',
            ],

            // ========== GESTION RÔLES ==========
            [
                'name' => 'Voir les rôles',
                'slug' => 'roles.view',
                'group' => 'roles',
                'description' => 'Consulter les rôles et leurs permissions',
            ],
            [
                'name' => 'Gérer les rôles',
                'slug' => 'roles.manage',
                'group' => 'roles',
                'description' => 'Créer, modifier les rôles et attribuer des permissions',
            ],
            [
                'name' => 'Supprimer les rôles',
                'slug' => 'roles.delete',
                'group' => 'roles',
                'description' => 'Supprimer les rôles personnalisés',
            ],

            // ========== GESTION PERMISSIONS ==========
            [
                'name' => 'Voir les permissions',
                'slug' => 'permissions.view',
                'group' => 'permissions',
                'description' => 'Consulter la liste des permissions système',
            ],
            [
                'name' => 'Gérer les permissions',
                'slug' => 'permissions.manage',
                'group' => 'permissions',
                'description' => 'Créer et modifier les permissions',
            ],
            [
                'name' => 'Supprimer les permissions',
                'slug' => 'permissions.delete',
                'group' => 'permissions',
                'description' => 'Supprimer les permissions personnalisées',
            ],

            // ========== GESTION POSTS ==========
            [
                'name' => 'Voir les posts',
                'slug' => 'posts.view',
                'group' => 'posts',
                'description' => 'Consulter tous les posts (brouillons et publiés)',
            ],
            [
                'name' => 'Gérer les posts',
                'slug' => 'posts.manage',
                'group' => 'posts',
                'description' => 'Créer, modifier et publier des posts',
            ],
            [
                'name' => 'Supprimer les posts',
                'slug' => 'posts.delete',
                'group' => 'posts',
                'description' => 'Supprimer définitivement les posts',
            ],

            // ========== GESTION CATÉGORIES (Posts) ==========
            [
                'name' => 'Voir les catégories',
                'slug' => 'categories.view',
                'group' => 'categories',
                'description' => 'Consulter les catégories de posts',
            ],
            [
                'name' => 'Gérer les catégories',
                'slug' => 'categories.manage',
                'group' => 'categories',
                'description' => 'Créer et modifier les catégories',
            ],
            [
                'name' => 'Supprimer les catégories',
                'slug' => 'categories.delete',
                'group' => 'categories',
                'description' => 'Supprimer les catégories',
            ],

            // ========== GESTION TAGS ==========
            [
                'name' => 'Voir les tags',
                'slug' => 'tags.view',
                'group' => 'tags',
                'description' => 'Consulter les tags',
            ],
            [
                'name' => 'Gérer les tags',
                'slug' => 'tags.manage',
                'group' => 'tags',
                'description' => 'Créer et modifier les tags',
            ],
            [
                'name' => 'Supprimer les tags',
                'slug' => 'tags.delete',
                'group' => 'tags',
                'description' => 'Supprimer les tags',
            ],

            // ========== GESTION FICHES ==========
            [
                'name' => 'Voir les fiches',
                'slug' => 'fiches.view',
                'group' => 'fiches',
                'description' => 'Consulter toutes les fiches techniques',
            ],
            [
                'name' => 'Gérer les fiches',
                'slug' => 'fiches.manage',
                'group' => 'fiches',
                'description' => 'Créer, modifier et publier des fiches',
            ],
            [
                'name' => 'Supprimer les fiches',
                'slug' => 'fiches.delete',
                'group' => 'fiches',
                'description' => 'Supprimer définitivement les fiches',
            ],

            // ========== GESTION PAGES ==========
            [
                'name' => 'Voir les pages',
                'slug' => 'pages.view',
                'group' => 'pages',
                'description' => 'Consulter toutes les pages statiques',
            ],
            [
                'name' => 'Gérer les pages',
                'slug' => 'pages.manage',
                'group' => 'pages',
                'description' => 'Créer, modifier et publier des pages',
            ],
            [
                'name' => 'Supprimer les pages',
                'slug' => 'pages.delete',
                'group' => 'pages',
                'description' => 'Supprimer définitivement les pages',
            ],

            // ========== GESTION VIDÉOS ==========
            [
                'name' => 'Voir les vidéos',
                'slug' => 'videos.view',
                'group' => 'videos',
                'description' => 'Consulter toutes les vidéos',
            ],
            [
                'name' => 'Gérer les vidéos',
                'slug' => 'videos.manage',
                'group' => 'videos',
                'description' => 'Créer, modifier et publier des vidéos',
            ],
            [
                'name' => 'Supprimer les vidéos',
                'slug' => 'videos.delete',
                'group' => 'videos',
                'description' => 'Supprimer définitivement les vidéos',
            ],

            // ========== GESTION TÉLÉCHARGEMENTS ==========
            [
                'name' => 'Voir les téléchargements',
                'slug' => 'downloads.view',
                'group' => 'downloads',
                'description' => 'Consulter tous les fichiers téléchargeables',
            ],
            [
                'name' => 'Gérer les téléchargements',
                'slug' => 'downloads.manage',
                'group' => 'downloads',
                'description' => 'Créer, modifier et uploader des fichiers',
            ],
            [
                'name' => 'Supprimer les téléchargements',
                'slug' => 'downloads.delete',
                'group' => 'downloads',
                'description' => 'Supprimer définitivement les fichiers',
            ],
        ];

        // 🇬🇧 Create permissions / 🇫🇷 Créer les permissions
        $groupCounts = [];
        
        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
            
            // 🇬🇧 Count by group / 🇫🇷 Compter par groupe
            $group = $permissionData['group'];
            $groupCounts[$group] = ($groupCounts[$group] ?? 0) + 1;
            
            $this->command->info("✅ Permission créée : {$permissionData['slug']}");
        }

        $this->command->info('');
        $this->command->info('🎉 PermissionsTableSeeder terminé avec succès !');
        $this->command->info('📊 ' . count($permissions) . ' permissions créées dans ' . count($groupCounts) . ' groupes :');
        
        foreach ($groupCounts as $group => $count) {
            $this->command->info("   → {$group}: {$count} permissions");
        }
    }
}
