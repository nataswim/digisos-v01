<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VideoCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catégories de la rubrique « Médias > Au fil de l'eau ».
 */
class VideoCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        VideoCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $categories = [
            ['Entraînements', 'entrainements', 'Séances, éducatifs et conseils techniques filmés au bord du bassin.'],
            ['Compétitions', 'competitions', 'Les nageurs du club en course.'],
            ['Vie du club', 'vie-du-club', 'Événements, fêtes et moments partagés du CNBB.'],
        ];

        foreach ($categories as $i => [$name, $slug, $description]) {
            VideoCategory::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'image' => null,
                'meta_title' => "{$name} - Au fil de l'eau - CNBB",
                'meta_description' => $description,
                'meta_keywords' => mb_strtolower($name) . ', vidéo, natation, CNBB',
                'is_active' => true,
                'sort_order' => $i + 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]);
            $this->command->info("✅ Catégorie vidéo : {$name}");
        }

        $this->command->info('🎉 VideoCategoriesSeeder : ' . count($categories) . ' catégories créées');
    }
}
