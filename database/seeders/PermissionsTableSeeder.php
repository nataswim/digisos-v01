<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = [
            // ========== DASHBOARD ==========
            [
                'name' => 'Accéder au dashboard admin',
                'slug' => 'admin.dashboard',
                'group' => 'dashboard',
                'description' => 'Accéder au tableau de bord administrateur',
            ],
            [
                'name' => 'Accéder au dashboard éditeur',
                'slug' => 'editor.dashboard',
                'group' => 'dashboard',
                'description' => 'Accéder au tableau de bord éditeur',
            ],

            // ========== GESTION UTILISATEURS ==========
            [
                'name' => 'Voir les utilisateurs',
                'slug' => 'users.view',
                'group' => 'users',
                'description' => 'Consulter la liste des utilisateurs',
            ],
            [
                'name' => 'Créer des utilisateurs',
                'slug' => 'users.create',
                'group' => 'users',
                'description' => 'Créer de nouveaux utilisateurs',
            ],
            [
                'name' => 'Modifier les utilisateurs',
                'slug' => 'users.edit',
                'group' => 'users',
                'description' => 'Modifier les utilisateurs existants',
            ],
            [
                'name' => 'Supprimer les utilisateurs',
                'slug' => 'users.delete',
                'group' => 'users',
                'description' => 'Supprimer des utilisateurs',
            ],
            [
                'name' => 'Actions groupées utilisateurs',
                'slug' => 'users.bulk',
                'group' => 'users',
                'description' => 'Effectuer des actions groupées sur les utilisateurs',
            ],
            [
                'name' => 'Modifier les rôles utilisateurs',
                'slug' => 'users.update-role',
                'group' => 'users',
                'description' => 'Modifier le rôle des utilisateurs',
            ],

            // ========== GESTION RÔLES ==========
            [
                'name' => 'Voir les rôles',
                'slug' => 'roles.view',
                'group' => 'roles',
                'description' => 'Consulter la liste des rôles',
            ],
            [
                'name' => 'Créer des rôles',
                'slug' => 'roles.create',
                'group' => 'roles',
                'description' => 'Créer de nouveaux rôles',
            ],
            [
                'name' => 'Modifier les rôles',
                'slug' => 'roles.edit',
                'group' => 'roles',
                'description' => 'Modifier les rôles existants',
            ],
            [
                'name' => 'Supprimer les rôles',
                'slug' => 'roles.delete',
                'group' => 'roles',
                'description' => 'Supprimer des rôles',
            ],

            // ========== GESTION PERMISSIONS ==========
            [
                'name' => 'Voir les permissions',
                'slug' => 'permissions.view',
                'group' => 'permissions',
                'description' => 'Consulter la liste des permissions',
            ],
            [
                'name' => 'Créer des permissions',
                'slug' => 'permissions.create',
                'group' => 'permissions',
                'description' => 'Créer de nouvelles permissions',
            ],
            [
                'name' => 'Modifier les permissions',
                'slug' => 'permissions.edit',
                'group' => 'permissions',
                'description' => 'Modifier les permissions existantes',
            ],
            [
                'name' => 'Supprimer les permissions',
                'slug' => 'permissions.delete',
                'group' => 'permissions',
                'description' => 'Supprimer des permissions',
            ],

            // ========== GESTION POSTS (ARTICLES) ==========
            [
                'name' => 'Voir les articles',
                'slug' => 'posts.view',
                'group' => 'posts',
                'description' => 'Consulter la liste des articles',
            ],
            [
                'name' => 'Créer des articles',
                'slug' => 'posts.create',
                'group' => 'posts',
                'description' => 'Créer de nouveaux articles',
            ],
            [
                'name' => 'Modifier les articles',
                'slug' => 'posts.edit',
                'group' => 'posts',
                'description' => 'Modifier les articles existants',
            ],
            [
                'name' => 'Supprimer les articles',
                'slug' => 'posts.delete',
                'group' => 'posts',
                'description' => 'Supprimer des articles',
            ],
            [
                'name' => 'Actions groupées articles',
                'slug' => 'posts.bulk',
                'group' => 'posts',
                'description' => 'Effectuer des actions groupées sur les articles',
            ],

            // ========== GESTION CATÉGORIES (POSTS) ==========
            [
                'name' => 'Voir les catégories',
                'slug' => 'categories.view',
                'group' => 'categories',
                'description' => 'Consulter les catégories d\'articles',
            ],
            [
                'name' => 'Créer des catégories',
                'slug' => 'categories.create',
                'group' => 'categories',
                'description' => 'Créer de nouvelles catégories',
            ],
            [
                'name' => 'Modifier les catégories',
                'slug' => 'categories.edit',
                'group' => 'categories',
                'description' => 'Modifier les catégories existantes',
            ],
            [
                'name' => 'Supprimer les catégories',
                'slug' => 'categories.delete',
                'group' => 'categories',
                'description' => 'Supprimer des catégories',
            ],
            [
                'name' => 'Actions groupées catégories',
                'slug' => 'categories.bulk',
                'group' => 'categories',
                'description' => 'Effectuer des actions groupées sur les catégories',
            ],

            // ========== GESTION TAGS ==========
            [
                'name' => 'Voir les tags',
                'slug' => 'tags.view',
                'group' => 'tags',
                'description' => 'Consulter les tags',
            ],
            [
                'name' => 'Créer des tags',
                'slug' => 'tags.create',
                'group' => 'tags',
                'description' => 'Créer de nouveaux tags',
            ],
            [
                'name' => 'Modifier les tags',
                'slug' => 'tags.edit',
                'group' => 'tags',
                'description' => 'Modifier les tags existants',
            ],
            [
                'name' => 'Supprimer les tags',
                'slug' => 'tags.delete',
                'group' => 'tags',
                'description' => 'Supprimer des tags',
            ],
            [
                'name' => 'Actions groupées tags',
                'slug' => 'tags.bulk',
                'group' => 'tags',
                'description' => 'Effectuer des actions groupées sur les tags',
            ],

            // ========== GESTION PAGES STATIQUES ==========
            [
                'name' => 'Voir les pages',
                'slug' => 'pages.view',
                'group' => 'pages',
                'description' => 'Consulter les pages statiques',
            ],
            [
                'name' => 'Créer des pages',
                'slug' => 'pages.create',
                'group' => 'pages',
                'description' => 'Créer de nouvelles pages',
            ],
            [
                'name' => 'Modifier les pages',
                'slug' => 'pages.edit',
                'group' => 'pages',
                'description' => 'Modifier les pages existantes',
            ],
            [
                'name' => 'Supprimer les pages',
                'slug' => 'pages.delete',
                'group' => 'pages',
                'description' => 'Supprimer des pages',
            ],
            [
                'name' => 'Actions groupées pages',
                'slug' => 'pages.bulk',
                'group' => 'pages',
                'description' => 'Effectuer des actions groupées sur les pages',
            ],

            // ========== GESTION CATÉGORIES PAGES ==========
            [
                'name' => 'Voir les catégories de pages',
                'slug' => 'pages-categories.view',
                'group' => 'pages-categories',
                'description' => 'Consulter les catégories de pages',
            ],
            [
                'name' => 'Créer des catégories de pages',
                'slug' => 'pages-categories.create',
                'group' => 'pages-categories',
                'description' => 'Créer de nouvelles catégories de pages',
            ],
            [
                'name' => 'Modifier les catégories de pages',
                'slug' => 'pages-categories.edit',
                'group' => 'pages-categories',
                'description' => 'Modifier les catégories de pages',
            ],
            [
                'name' => 'Supprimer les catégories de pages',
                'slug' => 'pages-categories.delete',
                'group' => 'pages-categories',
                'description' => 'Supprimer des catégories de pages',
            ],

            // ========== GESTION FICHES ==========
            [
                'name' => 'Voir les fiches',
                'slug' => 'fiches.view',
                'group' => 'fiches',
                'description' => 'Consulter les fiches techniques',
            ],
            [
                'name' => 'Créer des fiches',
                'slug' => 'fiches.create',
                'group' => 'fiches',
                'description' => 'Créer de nouvelles fiches',
            ],
            [
                'name' => 'Modifier les fiches',
                'slug' => 'fiches.edit',
                'group' => 'fiches',
                'description' => 'Modifier les fiches existantes',
            ],
            [
                'name' => 'Supprimer les fiches',
                'slug' => 'fiches.delete',
                'group' => 'fiches',
                'description' => 'Supprimer des fiches',
            ],
            [
                'name' => 'Actions groupées fiches',
                'slug' => 'fiches.bulk',
                'group' => 'fiches',
                'description' => 'Effectuer des actions groupées sur les fiches',
            ],

            // ========== GESTION CATÉGORIES FICHES ==========
            [
                'name' => 'Voir les catégories de fiches',
                'slug' => 'fiches-categories.view',
                'group' => 'fiches-categories',
                'description' => 'Consulter les catégories de fiches',
            ],
            [
                'name' => 'Créer des catégories de fiches',
                'slug' => 'fiches-categories.create',
                'group' => 'fiches-categories',
                'description' => 'Créer de nouvelles catégories de fiches',
            ],
            [
                'name' => 'Modifier les catégories de fiches',
                'slug' => 'fiches-categories.edit',
                'group' => 'fiches-categories',
                'description' => 'Modifier les catégories de fiches',
            ],
            [
                'name' => 'Supprimer les catégories de fiches',
                'slug' => 'fiches-categories.delete',
                'group' => 'fiches-categories',
                'description' => 'Supprimer des catégories de fiches',
            ],

            // ========== GESTION SOUS-CATÉGORIES FICHES ==========
            [
                'name' => 'Voir les sous-catégories de fiches',
                'slug' => 'fiches-sous-categories.view',
                'group' => 'fiches-sous-categories',
                'description' => 'Consulter les sous-catégories de fiches',
            ],
            [
                'name' => 'Créer des sous-catégories de fiches',
                'slug' => 'fiches-sous-categories.create',
                'group' => 'fiches-sous-categories',
                'description' => 'Créer de nouvelles sous-catégories',
            ],
            [
                'name' => 'Modifier les sous-catégories de fiches',
                'slug' => 'fiches-sous-categories.edit',
                'group' => 'fiches-sous-categories',
                'description' => 'Modifier les sous-catégories',
            ],
            [
                'name' => 'Supprimer les sous-catégories de fiches',
                'slug' => 'fiches-sous-categories.delete',
                'group' => 'fiches-sous-categories',
                'description' => 'Supprimer des sous-catégories',
            ],

            // ========== GESTION VIDÉOS ==========
            [
                'name' => 'Voir les vidéos',
                'slug' => 'videos.view',
                'group' => 'videos',
                'description' => 'Consulter les vidéos',
            ],
            [
                'name' => 'Créer des vidéos',
                'slug' => 'videos.create',
                'group' => 'videos',
                'description' => 'Créer de nouvelles vidéos',
            ],
            [
                'name' => 'Modifier les vidéos',
                'slug' => 'videos.edit',
                'group' => 'videos',
                'description' => 'Modifier les vidéos existantes',
            ],
            [
                'name' => 'Supprimer les vidéos',
                'slug' => 'videos.delete',
                'group' => 'videos',
                'description' => 'Supprimer des vidéos',
            ],

            // ========== GESTION CATÉGORIES VIDÉOS ==========
            [
                'name' => 'Voir les catégories de vidéos',
                'slug' => 'video-categories.view',
                'group' => 'video-categories',
                'description' => 'Consulter les catégories de vidéos',
            ],
            [
                'name' => 'Créer des catégories de vidéos',
                'slug' => 'video-categories.create',
                'group' => 'video-categories',
                'description' => 'Créer de nouvelles catégories de vidéos',
            ],
            [
                'name' => 'Modifier les catégories de vidéos',
                'slug' => 'video-categories.edit',
                'group' => 'video-categories',
                'description' => 'Modifier les catégories de vidéos',
            ],
            [
                'name' => 'Supprimer les catégories de vidéos',
                'slug' => 'video-categories.delete',
                'group' => 'video-categories',
                'description' => 'Supprimer des catégories de vidéos',
            ],

            // ========== BIBLIOTHÈQUE VIDÉO ==========
            [
                'name' => 'Accéder à la bibliothèque vidéo',
                'slug' => 'video-library.access',
                'group' => 'video-library',
                'description' => 'Accéder à la bibliothèque de vidéos',
            ],
            [
                'name' => 'Uploader des vidéos',
                'slug' => 'video-library.upload',
                'group' => 'video-library',
                'description' => 'Uploader des fichiers vidéo',
            ],
            [
                'name' => 'Gérer la bibliothèque vidéo',
                'slug' => 'video-library.manage',
                'group' => 'video-library',
                'description' => 'Créer des dossiers et organiser',
            ],

            // ========== GESTION MÉDIATHÈQUE ==========
            [
                'name' => 'Voir les médias',
                'slug' => 'media.view',
                'group' => 'media',
                'description' => 'Consulter la médiathèque',
            ],
            [
                'name' => 'Uploader des médias',
                'slug' => 'media.upload',
                'group' => 'media',
                'description' => 'Uploader des fichiers médias',
            ],
            [
                'name' => 'Modifier les médias',
                'slug' => 'media.edit',
                'group' => 'media',
                'description' => 'Modifier les médias existants',
            ],
            [
                'name' => 'Supprimer les médias',
                'slug' => 'media.delete',
                'group' => 'media',
                'description' => 'Supprimer des médias',
            ],
            [
                'name' => 'Actions groupées médias',
                'slug' => 'media.bulk',
                'group' => 'media',
                'description' => 'Effectuer des actions groupées',
            ],

            // ========== GESTION CATÉGORIES MÉDIAS ==========
            [
                'name' => 'Voir les catégories de médias',
                'slug' => 'media-categories.view',
                'group' => 'media-categories',
                'description' => 'Consulter les catégories de médias',
            ],
            [
                'name' => 'Créer des catégories de médias',
                'slug' => 'media-categories.create',
                'group' => 'media-categories',
                'description' => 'Créer de nouvelles catégories',
            ],
            [
                'name' => 'Supprimer les catégories de médias',
                'slug' => 'media-categories.delete',
                'group' => 'media-categories',
                'description' => 'Supprimer des catégories',
            ],

            // ========== GESTION TÉLÉCHARGEMENTS ==========
            [
                'name' => 'Voir les téléchargements',
                'slug' => 'downloadables.view',
                'group' => 'downloadables',
                'description' => 'Consulter les fichiers téléchargeables',
            ],
            [
                'name' => 'Créer des téléchargements',
                'slug' => 'downloadables.create',
                'group' => 'downloadables',
                'description' => 'Créer de nouveaux fichiers téléchargeables',
            ],
            [
                'name' => 'Modifier les téléchargements',
                'slug' => 'downloadables.edit',
                'group' => 'downloadables',
                'description' => 'Modifier les fichiers existants',
            ],
            [
                'name' => 'Supprimer les téléchargements',
                'slug' => 'downloadables.delete',
                'group' => 'downloadables',
                'description' => 'Supprimer des fichiers',
            ],
            [
                'name' => 'Dupliquer les téléchargements',
                'slug' => 'downloadables.duplicate',
                'group' => 'downloadables',
                'description' => 'Dupliquer des fichiers',
            ],
            [
                'name' => 'Actions groupées téléchargements',
                'slug' => 'downloadables.bulk',
                'group' => 'downloadables',
                'description' => 'Effectuer des actions groupées',
            ],

            // ========== GESTION CATÉGORIES TÉLÉCHARGEMENTS ==========
            [
                'name' => 'Voir les catégories de téléchargements',
                'slug' => 'download-categories.view',
                'group' => 'download-categories',
                'description' => 'Consulter les catégories de téléchargements',
            ],
            [
                'name' => 'Créer des catégories de téléchargements',
                'slug' => 'download-categories.create',
                'group' => 'download-categories',
                'description' => 'Créer de nouvelles catégories',
            ],
            [
                'name' => 'Modifier les catégories de téléchargements',
                'slug' => 'download-categories.edit',
                'group' => 'download-categories',
                'description' => 'Modifier les catégories',
            ],
            [
                'name' => 'Supprimer les catégories de téléchargements',
                'slug' => 'download-categories.delete',
                'group' => 'download-categories',
                'description' => 'Supprimer des catégories',
            ],

            // ========== GESTION BANNIÈRES ==========
            [
                'name' => 'Voir les bannières',
                'slug' => 'banners.view',
                'group' => 'banners',
                'description' => 'Consulter les bannières',
            ],
            [
                'name' => 'Créer des bannières',
                'slug' => 'banners.create',
                'group' => 'banners',
                'description' => 'Créer de nouvelles bannières',
            ],
            [
                'name' => 'Modifier les bannières',
                'slug' => 'banners.edit',
                'group' => 'banners',
                'description' => 'Modifier les bannières existantes',
            ],
            [
                'name' => 'Supprimer les bannières',
                'slug' => 'banners.delete',
                'group' => 'banners',
                'description' => 'Supprimer des bannières',
            ],
            [
                'name' => 'Gérer les slides',
                'slug' => 'banners.slides',
                'group' => 'banners',
                'description' => 'Gérer les slides des bannières',
            ],

            // ========== GESTION GALERIES PHOTO ==========
            [
                'name' => 'Voir les galeries',
                'slug' => 'photo-galleries.view',
                'group' => 'photo-galleries',
                'description' => 'Consulter les galeries photo',
            ],
            [
                'name' => 'Créer des galeries',
                'slug' => 'photo-galleries.create',
                'group' => 'photo-galleries',
                'description' => 'Créer de nouvelles galeries',
            ],
            [
                'name' => 'Modifier les galeries',
                'slug' => 'photo-galleries.edit',
                'group' => 'photo-galleries',
                'description' => 'Modifier les galeries existantes',
            ],
            [
                'name' => 'Supprimer les galeries',
                'slug' => 'photo-galleries.delete',
                'group' => 'photo-galleries',
                'description' => 'Supprimer des galeries',
            ],
            [
                'name' => 'Dupliquer les galeries',
                'slug' => 'photo-galleries.duplicate',
                'group' => 'photo-galleries',
                'description' => 'Dupliquer des galeries',
            ],
            [
                'name' => 'Actions groupées galeries',
                'slug' => 'photo-galleries.bulk',
                'group' => 'photo-galleries',
                'description' => 'Effectuer des actions groupées',
            ],

            // ========== GESTION SITEMAP ==========
            [
                'name' => 'Voir le sitemap',
                'slug' => 'sitemap.view',
                'group' => 'sitemap',
                'description' => 'Consulter le sitemap',
            ],
            [
                'name' => 'Générer le sitemap',
                'slug' => 'sitemap.generate',
                'group' => 'sitemap',
                'description' => 'Générer le fichier sitemap.xml',
            ],
            [
                'name' => 'Gérer le sitemap',
                'slug' => 'sitemap.manage',
                'group' => 'sitemap',
                'description' => 'Découvrir, approuver et nettoyer les URLs',
            ],

            // ========== STATISTIQUES ==========
            [
                'name' => 'Voir les statistiques',
                'slug' => 'stats.view',
                'group' => 'stats',
                'description' => 'Consulter les statistiques du site',
            ],
        ];

        $groupCounts = [];
        
        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
            
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