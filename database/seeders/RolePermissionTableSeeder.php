<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

/**
 * 🇬🇧 Role-Permission Table Seeder - Assigns permissions to roles
 * 🇫🇷 Seeder de la table role_permission - Attribue les permissions aux rôles
 * 
 * @file database/seeders/RolePermissionTableSeeder.php
 */
class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🇬🇧 Get all roles / 🇫🇷 Récupérer tous les rôles
        $admin = Role::where('slug', 'admin')->first();
        $editor = Role::where('slug', 'editor')->first();
        $user = Role::where('slug', 'user')->first();
        $visitor = Role::where('slug', 'visitor')->first();

        if (!$admin || !$editor || !$user || !$visitor) {
            $this->command->error('❌ Erreur : Les rôles doivent être créés avant d\'exécuter ce seeder !');
            $this->command->error('➡️  Exécutez d\'abord : php artisan db:seed --class=RolesTableSeeder');
            return;
        }

        // ========== ADMIN : TOUTES LES PERMISSIONS ==========
        $this->command->info('🔧 Configuration du rôle Admin...');
        
        $allPermissions = Permission::all()->pluck('id');
        $admin->permissions()->sync($allPermissions);
        
        $this->command->info("✅ Admin : {$allPermissions->count()} permissions attribuées (TOUTES)");

        // ========== EDITOR : PERMISSIONS CONTENU UNIQUEMENT ==========
        $this->command->info('🔧 Configuration du rôle Editor...');
        
        $editorPermissionSlugs = [
            // Posts
            'posts.view',
            'posts.manage',
            'posts.delete',
            
            // Catégories (Posts)
            'categories.view',
            'categories.manage',
            'categories.delete',
            
            // Tags
            'tags.view',
            'tags.manage',
            'tags.delete',
            
            // Fiches
            'fiches.view',
            'fiches.manage',
            'fiches.delete',
            
            // Pages
            'pages.view',
            'pages.manage',
            'pages.delete',
            
            // Vidéos
            'videos.view',
            'videos.manage',
            'videos.delete',
            
            // Téléchargements
            'downloads.view',
            'downloads.manage',
            'downloads.delete',
        ];
        
        $editorPermissions = Permission::whereIn('slug', $editorPermissionSlugs)->pluck('id');
        $editor->permissions()->sync($editorPermissions);
        
        $this->command->info("✅ Editor : {$editorPermissions->count()} permissions attribuées (Contenu uniquement)");

        // ========== USER : AUCUNE PERMISSION ==========
        $this->command->info('🔧 Configuration du rôle User...');
        
        $user->permissions()->sync([]);
        
        $this->command->info("✅ User : 0 permission (Accès contenu via logique métier)");

        // ========== VISITOR : AUCUNE PERMISSION ==========
        $this->command->info('🔧 Configuration du rôle Visitor...');
        
        $visitor->permissions()->sync([]);
        
        $this->command->info("✅ Visitor : 0 permission (Accès public uniquement)");

        // ========== RÉSUMÉ ==========
        $this->command->info('');
        $this->command->info('🎉 RolePermissionTableSeeder terminé avec succès !');
        $this->command->info('📊 Matrice des permissions :');
        $this->command->info('');
        $this->command->table(
            ['Rôle', 'Level', 'Permissions', 'Détails'],
            [
                ['Admin', '100', $allPermissions->count(), 'Accès total système'],
                ['Editor', '50', $editorPermissions->count(), 'Gestion contenu (posts, fiches, pages, vidéos, downloads)'],
                ['User', '10', '0', 'Accès contenu premium via model policies'],
                ['Visitor', '0', '0', 'Accès public uniquement'],
            ]
        );
    }
}
