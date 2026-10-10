<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Permissions du site, générées par module.
 * Les slugs sont identiques à l'ancienne version (aucun n'a été renommé) ;
 * ajout des modules services, structures, espaces, zones et profils.
 */
class PermissionsTableSeeder extends Seeder
{
    /** Libellés des actions courantes. */
    private const ACTIONS = [
        'view' => ['Voir', 'Consulter'],
        'create' => ['Créer', 'Créer'],
        'edit' => ['Modifier', 'Modifier'],
        'delete' => ['Supprimer', 'Supprimer'],
        'bulk' => ['Actions groupées', 'Effectuer des actions groupées sur'],
        'duplicate' => ['Dupliquer', 'Dupliquer'],
        'upload' => ['Téléverser dans', 'Téléverser des fichiers dans'],
    ];

    /**
     * Groupe => [libellé, actions]. L'ordre définit l'ordre en base.
     * Les groupes de contenu sont listés dans RolePermissionTableSeeder::EDITOR_GROUPS.
     */
    public const GROUPS = [
        'users' => ['les comptes', ['view', 'create', 'edit', 'delete', 'bulk']],
        'roles' => ['les rôles', ['view', 'create', 'edit', 'delete']],
        'permissions' => ['les permissions', ['view', 'create', 'edit', 'delete']],
        'posts' => ['les articles', ['view', 'create', 'edit', 'delete', 'bulk']],
        'categories' => ['les catégories d\'articles', ['view', 'create', 'edit', 'delete', 'bulk']],
        'tags' => ['les tags', ['view', 'create', 'edit', 'delete', 'bulk']],
        'pages' => ['les pages', ['view', 'create', 'edit', 'delete', 'bulk']],
        'pages-categories' => ['les catégories de pages', ['view', 'create', 'edit', 'delete']],
        'fiches' => ['les fiches pratiques', ['view', 'create', 'edit', 'delete', 'bulk']],
        'fiches-categories' => ['les catégories de fiches', ['view', 'create', 'edit', 'delete']],
        'fiches-sous-categories' => ['les sous-catégories de fiches', ['view', 'create', 'edit', 'delete']],
        'videos' => ['les vidéos', ['view', 'create', 'edit', 'delete']],
        'video-categories' => ['les catégories de vidéos', ['view', 'create', 'edit', 'delete']],
        'media' => ['la médiathèque', ['view', 'upload', 'edit', 'delete', 'bulk']],
        'media-categories' => ['les catégories de médias', ['view', 'create', 'delete']],
        'downloadables' => ['les documents à télécharger', ['view', 'create', 'edit', 'delete', 'duplicate', 'bulk']],
        'download-categories' => ['les catégories de documents', ['view', 'create', 'edit', 'delete']],
        'banners' => ['les bannières', ['view', 'create', 'edit', 'delete']],
        'photo-galleries' => ['les galeries photo', ['view', 'create', 'edit', 'delete', 'duplicate', 'bulk']],
        // Nouveaux modules (routes existantes sans permission jusqu'ici)
        'services' => ['les services', ['view', 'create', 'edit', 'delete']],
        'structures' => ['les structures', ['view', 'create', 'edit', 'delete']],
        'espaces' => ['les espaces', ['view', 'create', 'edit', 'delete']],
        'zones' => ['les zones', ['view', 'create', 'edit', 'delete']],
        'user-profiles' => ['les profils utilisateurs', ['view', 'create', 'edit', 'delete']],
    ];

    /** Permissions qui ne suivent pas le schéma groupe.action standard. */
    private const SPECIAL = [
        ['admin.dashboard', 'dashboard', 'Accéder au tableau de bord administrateur'],
        ['editor.dashboard', 'dashboard', 'Accéder au tableau de bord éditeur'],
        ['users.update-role', 'users', 'Modifier le rôle des comptes'],
        ['video-library.access', 'video-library', 'Accéder à la bibliothèque vidéo'],
        ['video-library.upload', 'video-library', 'Téléverser des fichiers vidéo'],
        ['video-library.manage', 'video-library', 'Créer des dossiers et organiser la bibliothèque vidéo'],
        ['banners.slides', 'banners', 'Gérer les diapositives des bannières'],
        ['sitemap.view', 'sitemap', 'Consulter le plan du site (sitemap)'],
        ['sitemap.generate', 'sitemap', 'Générer le fichier sitemap.xml'],
        ['sitemap.manage', 'sitemap', 'Découvrir, approuver et nettoyer les URL du sitemap'],
        ['stats.view', 'stats', 'Consulter les statistiques du site'],
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rows = [];

        foreach (self::GROUPS as $group => [$label, $actions]) {
            foreach ($actions as $action) {
                [$short, $verb] = self::ACTIONS[$action];
                $rows[] = [
                    'name' => "{$short} {$label}",
                    'slug' => "{$group}.{$action}",
                    'group' => $group,
                    'description' => "{$verb} {$label}",
                ];
            }
        }

        foreach (self::SPECIAL as [$slug, $group, $description]) {
            $rows[] = ['name' => $description, 'slug' => $slug, 'group' => $group, 'description' => $description];
        }

        $counts = [];
        foreach ($rows as $row) {
            Permission::create($row);
            $counts[$row['group']] = ($counts[$row['group']] ?? 0) + 1;
        }

        $this->command->info('🎉 PermissionsTableSeeder : ' . count($rows) . ' permissions dans ' . count($counts) . ' groupes');
        $this->command->table(['Groupe', 'Permissions'], collect($counts)->map(fn ($n, $g) => [$g, $n])->values()->all());
    }
}
