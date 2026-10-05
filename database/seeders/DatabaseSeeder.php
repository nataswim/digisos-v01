<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * 🇬🇧 Database Seeder - Main orchestrator for all Mon Club de Natation seeders
 * 🇫🇷 Seeder principal - Orchestrateur de tous les seeders Mon Club de Natation
 * 
 * @file database/seeders/DatabaseSeeder.php
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔════════════════════════════════════════════════════════════╗');
        $this->command->info('║         DIGITAL\'SOS - DATABASE SEEDING                     ║');
        $this->command->info('║         Système de gestion sportive M2PC                   ║');
        $this->command->info('╚════════════════════════════════════════════════════════════╝');
        $this->command->info('');

        // ========== PHASE 1 : FONDATIONS ==========
        $this->command->info('🔷 PHASE 1 : FONDATIONS (Rôles, Permissions, Utilisateurs)');
        $this->command->info('');
        
        $this->call([
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            RolePermissionTableSeeder::class,
            UsersTableSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Phase 1 terminée !');
        $this->command->info('');

        // ========== PHASE 2 : TAXONOMIE ==========
        $this->command->info('🔷 PHASE 2 : TAXONOMIE (Catégories & Sous-catégories)');
        $this->command->info('');
        
        $this->call([
            CategoriesTableSeeder::class,
            TagsTableSeeder::class,
            FichesCategoriesSeeder::class,
            FichesSousCategoriesSeeder::class,
            PagesCategoriesSeeder::class,
            VideoCategoriesSeeder::class,
            DownloadCategoriesSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Phase 2 terminée !');
        $this->command->info('');

        // ========== PHASE 3 : CONTENU ==========
        $this->command->info('🔷 PHASE 3 : CONTENU (Posts, Fiches, Pages, Vidéos, Downloads)');
        $this->command->info('');
        
        $this->call([
            PostsTableSeeder::class,
            TaggablesTableSeeder::class,
            FichesSeeder::class,
            PagesSeeder::class,
            VideosSeeder::class,
            DownloadablesSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Phase 3 terminée !');
        $this->command->info('');

        // ========== RÉSUMÉ FINAL ==========
        $this->command->info('╔════════════════════════════════════════════════════════════╗');
        $this->command->info('║                  SEEDING TERMINÉ AVEC SUCCÈS               ║');
        $this->command->info('╚════════════════════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->table(
            ['Module', 'Éléments créés'],
            [
                ['👥 Rôles', '4 (admin, editor, user, visitor)'],
                ['🔐 Permissions', '30 (CRUD par module)'],
                ['👤 Utilisateurs', '8 (2 par rôle)'],
                ['📁 Catégories Posts', '3'],
                ['🏷️  Tags', '3'],
                ['📂 Catégories Fiches', '3'],
                ['📂 Sous-catégories Fiches', '3'],
                ['📂 Catégories Pages', '3'],
                ['📂 Catégories Vidéos', '3'],
                ['📂 Catégories Downloads', '3'],
                ['📰 Posts', '3'],
                ['📚 Fiches', '3'],
                ['📄 Pages', '3'],
                ['🎬 Vidéos', '3'],
                ['📥 Téléchargements', '3'],
            ]
        );
        $this->command->info('');
        $this->command->info('🎉 Base de données peuplée avec succès pour Digital\'SOS !');
        $this->command->info('🔑 Mot de passe par défaut : password');
        $this->command->info('📧 Comptes de test disponibles :');
        $this->command->info('   → hassan@digitalsos.fr (Admin)');
        $this->command->info('   → jean.dupont@digitalsos.fr (Editor)');
        $this->command->info('   → pierre.dubois@example.com (User)');
        $this->command->info('   → lucas.petit@example.com (Visitor)');
        $this->command->info('');
    }
}
