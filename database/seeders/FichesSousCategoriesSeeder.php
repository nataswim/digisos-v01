<?php

namespace Database\Seeders;

use App\Models\FichesCategory;
use App\Models\FichesSousCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FichesSousCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FichesSousCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $cat = fn (string $slug) => FichesCategory::where('slug', $slug)->first()
            ?? throw new \RuntimeException("Catégorie de fiches « {$slug} » absente : lancer FichesCategoriesSeeder d'abord.");

        // [catégorie parente, nom, slug, description]
        $sousCategories = [
            ['inscription-adhesion', 'Dossier d\'inscription', 'dossier-inscription', 'Les pièces à fournir, le certificat médical et le dépôt du dossier.'],
            ['inscription-adhesion', 'Tarifs & paiement', 'tarifs-paiement', 'Tarifs de la saison, réductions familles et moyens de paiement acceptés.'],
            ['vie-sportive', 'École de natation', 'ecole-de-natation', 'Apprentissage et tests de l\'École de Natation Française.'],
            ['vie-sportive', 'Compétition', 'competition', 'Natation course, compétitions FFN et officiels.'],
            ['piscine-fonctionnement', 'Centre aquatique Cœur d\'O', 'centre-aquatique', 'Accès au bassin, badge et calendrier des activités.'],
        ];

        $order = [];
        foreach ($sousCategories as [$parent, $name, $slug, $description]) {
            $order[$parent] = ($order[$parent] ?? 0) + 1;

            FichesSousCategory::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'image' => null,
                'fiches_category_id' => $cat($parent)->id,
                'meta_title' => "{$name} - Infos pratiques CNBB",
                'meta_description' => $description,
                'meta_keywords' => mb_strtolower($name) . ', CNBB, natation, Bressuire',
                'is_active' => true,
                'sort_order' => $order[$parent],
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]);
            $this->command->info("✅ Sous-catégorie : {$name}");
        }

        $this->command->info('🎉 FichesSousCategoriesSeeder : ' . count($sousCategories) . ' sous-catégories créées');
    }
}
