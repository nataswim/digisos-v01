<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DownloadCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DownloadCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DownloadCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->first();

        $categories = [
            [
                'name' => 'E-books & Guides',
                'slug' => 'ebooks-guides',
                'short_description' => 'Livres numériques et guides complets',
                'description' => 'E-books, manuels et guides complets sur la gestion sportive, l\'entraînement et l\'organisation de structures.',
                'icon' => 'fa-book',
                'order' => 1,
                'status' => 'active',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Formulaires & Modèles',
                'slug' => 'formulaires-modeles',
                'short_description' => 'Documents types prêts à l\'emploi',
                'description' => 'Formulaires administratifs, modèles de contrats, documents types pour simplifier la gestion quotidienne.',
                'icon' => 'fa-file-alt',
                'order' => 2,
                'status' => 'active',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Plans d\'Entraînement',
                'slug' => 'plans-entrainement',
                'short_description' => 'Programmes structurés pour tous niveaux',
                'description' => 'Plans d\'entraînement détaillés, programmes de préparation et cycles de développement pour différentes disciplines.',
                'icon' => 'fa-calendar-alt',
                'order' => 3,
                'status' => 'active',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
        ];

        foreach ($categories as $data) {
            $category = DownloadCategory::create($data);
            $this->command->info("✅ Catégorie download : {$category->name}");
        }

        $this->command->info('🎉 DownloadCategoriesSeeder terminé : 3 catégories créées');
    }
}
