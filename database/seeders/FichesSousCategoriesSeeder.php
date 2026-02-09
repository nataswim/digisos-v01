<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FichesSousCategory;
use App\Models\FichesCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FichesSousCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FichesSousCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->first();

        $categoriesTechniques = FichesCategory::where('slug', 'techniques-entrainement')->first();
        $categoriesGestion = FichesCategory::where('slug', 'gestion-administrative')->first();
        $categoriesMateriel = FichesCategory::where('slug', 'materiel-equipement')->first();

        $sousCategories = [
            [
                'name' => 'Natation',
                'slug' => 'natation',
                'description' => 'Exercices techniques, protocoles d\'entraînement et programmes spécifiques pour la natation sportive et de loisir.',
                'image' => 'fiches-sous-categories/natation.jpg',
                'fiches_category_id' => $categoriesTechniques->id,
                'meta_title' => 'Techniques natation - Digital\'SOS',
                'meta_description' => 'Fiches techniques natation : exercices, protocoles et programmes d\'entraînement.',
                'meta_keywords' => 'natation, techniques, exercices, entraînement',
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Inscriptions',
                'slug' => 'inscriptions',
                'description' => 'Formulaires types, procédures d\'inscription, documents obligatoires et processus de validation des adhésions.',
                'image' => 'fiches-sous-categories/inscriptions.jpg',
                'fiches_category_id' => $categoriesGestion->id,
                'meta_title' => 'Gestion inscriptions - Digital\'SOS',
                'meta_description' => 'Fiches pratiques : formulaires, procédures et documents pour gérer les inscriptions.',
                'meta_keywords' => 'inscriptions, formulaires, adhésions, procédures',
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Bassins',
                'slug' => 'bassins',
                'description' => 'Maintenance des équipements aquatiques, normes de sécurité, entretien et gestion des installations de baignade.',
                'image' => 'fiches-sous-categories/bassins.jpg',
                'fiches_category_id' => $categoriesMateriel->id,
                'meta_title' => 'Gestion bassins - Digital\'SOS',
                'meta_description' => 'Fiches maintenance et sécurité des bassins et équipements aquatiques.',
                'meta_keywords' => 'bassins, maintenance, sécurité, équipements aquatiques',
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
        ];

        foreach ($sousCategories as $data) {
            $sousCategory = FichesSousCategory::create($data);
            $this->command->info("✅ Sous-catégorie : {$sousCategory->name}");
        }

        $this->command->info('🎉 FichesSousCategoriesSeeder terminé : 3 sous-catégories créées');
    }
}
