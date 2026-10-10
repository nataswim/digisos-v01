<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Site du Cercle des Nageurs du Bocage Bressuirais (CNBB).
 * Usage : php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🏊 CNBB — Cercle des Nageurs du Bocage Bressuirais : initialisation de la base');
        $this->command->info('');

        $this->command->info('— Phase 1 : rôles, permissions, comptes');
        $this->call([
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            RolePermissionTableSeeder::class,
            UsersTableSeeder::class,
            UserProfilesSeeder::class,
        ]);

        $this->command->info('— Phase 2 : catégories');
        $this->call([
            CategoriesTableSeeder::class,
            TagsTableSeeder::class,
            FichesCategoriesSeeder::class,
            FichesSousCategoriesSeeder::class,
            PagesCategoriesSeeder::class,
            VideoCategoriesSeeder::class,
            DownloadCategoriesSeeder::class,
        ]);

        $this->command->info('— Phase 3 : contenus');
        $this->call([
            PostsTableSeeder::class,
            TaggablesTableSeeder::class,
            FichesSeeder::class,
            PagesSeeder::class,
            VideosSeeder::class,
            DownloadablesSeeder::class,
        ]);

        $this->summary();
    }

    /** Récapitulatif calculé à partir de la base (aucun chiffre écrit en dur). */
    private function summary(): void
    {
        $tables = [
            'roles' => 'Rôles',
            'permissions' => 'Permissions',
            'users' => 'Comptes',
            'user_profiles' => 'Fiches personnelles',
            'profile_items' => 'Éléments de fiche',
            'categories' => 'Catégories d\'articles',
            'tags' => 'Tags',
            'posts' => 'Articles',
            'fiches_categories' => 'Catégories de fiches',
            'fiches_sous_categories' => 'Sous-catégories de fiches',
            'fiches' => 'Fiches',
            'pages_categories' => 'Catégories de pages',
            'pages' => 'Pages',
            'video_categories' => 'Catégories vidéo',
            'videos' => 'Vidéos',
            'download_categories' => 'Catégories de ressources',
            'downloadables' => 'Documents',
        ];

        $rows = [];
        foreach ($tables as $table => $label) {
            try {
                $rows[] = [$label, DB::table($table)->count()];
            } catch (\Throwable) {
                $rows[] = [$label, '— (table ' . $table . ' introuvable)'];
            }
        }

        $this->command->info('');
        $this->command->table(['Contenu', 'Nombre'], $rows);

        $accounts = User::with('role')->orderBy('id')->get()
            ->map(fn ($u) => [$u->role?->name ?? '—', $u->email])->all();

        if ($accounts) {
            $this->command->table(['Rôle', 'Compte'], $accounts);
            $this->command->warn('⚠️  Comptes de test : changer les mots de passe avant toute mise en ligne.');
        }
    }
}
