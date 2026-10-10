<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Attribution des permissions aux rôles.
 * Correction : l'ancienne version donnait à l'éditeur des slugs inexistants
 * (posts.manage, downloads.view…), il ne recevait donc qu'une partie de ses droits.
 */
class RolePermissionTableSeeder extends Seeder
{
    /** Groupes de contenu gérés par l'éditeur (toutes les actions du groupe). */
    public const EDITOR_GROUPS = [
        'posts', 'categories', 'tags',
        'pages', 'pages-categories',
        'fiches', 'fiches-categories', 'fiches-sous-categories',
        'videos', 'video-categories', 'video-library',
        'media', 'media-categories',
        'downloadables', 'download-categories',
        'photo-galleries', 'banners',
    ];

    public function run(): void
    {
        $roles = Role::whereIn('slug', ['admin', 'editor', 'user', 'visitor'])->get()->keyBy('slug');

        if ($roles->count() < 4) {
            $this->command->error('❌ Rôles manquants : lancer RolesTableSeeder avant ce seeder.');
            return;
        }

        $all = Permission::pluck('id');
        $roles['admin']->permissions()->sync($all);

        $editor = Permission::whereIn('group', self::EDITOR_GROUPS)
            ->orWhere('slug', 'editor.dashboard')
            ->pluck('id');
        $roles['editor']->permissions()->sync($editor);

        // Adhérents et visiteurs : aucun droit d'administration,
        // l'accès aux contenus réservés passe par la visibilité des contenus.
        $roles['user']->permissions()->sync([]);
        $roles['visitor']->permissions()->sync([]);

        $this->command->info('🎉 RolePermissionTableSeeder terminé');
        $this->command->table(['Rôle', 'Permissions'], [
            ['Administrateur', $all->count()],
            ['Éditeur', $editor->count()],
            ['Adhérent', 0],
            ['Visiteur', 0],
        ]);
    }
}
