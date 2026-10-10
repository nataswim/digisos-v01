<?php

namespace Database\Seeders;

use App\Models\FichesCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catégories des fiches (menu « Infos pratiques »).
 */
class FichesCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FichesCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $categories = [
            [
                'name' => 'Inscription & adhésion',
                'slug' => 'inscription-adhesion',
                'description' => 'Tout pour rejoindre le club : conditions, tarifs, réductions, pièces du dossier et moyens de paiement.',
                'meta_keywords' => 'inscription natation Bressuire, adhésion, tarifs, dossier, certificat médical',
            ],
            [
                'name' => 'Vie sportive',
                'slug' => 'vie-sportive',
                'description' => 'École de natation, tests fédéraux, compétitions et rôle des officiels.',
                'meta_keywords' => 'école de natation, tests ENF, compétition, officiels, FFN',
            ],
            [
                'name' => 'Piscine & fonctionnement',
                'slug' => 'piscine-fonctionnement',
                'description' => 'Le centre aquatique Cœur d\'O, le badge d\'accès et le fonctionnement des activités pendant l\'année.',
                'meta_keywords' => 'Cœur d\'O, piscine Bressuire, badge, vacances scolaires',
            ],
        ];

        foreach ($categories as $i => $data) {
            FichesCategory::create($data + [
                'image' => null,
                'meta_title' => "{$data['name']} - Infos pratiques CNBB",
                'meta_description' => $data['description'],
                'is_active' => true,
                'sort_order' => $i + 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]);
            $this->command->info("✅ Catégorie de fiches : {$data['name']}");
        }

        $this->command->info('🎉 FichesCategoriesSeeder : ' . count($categories) . ' catégories créées');
    }
}
