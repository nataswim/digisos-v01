<?php

namespace Database\Seeders;

use App\Models\PagesCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catégories des pages du menu « Le Club ».
 * Les pages légales (mentions, confidentialité, cookies, accessibilité) sont des vues fixes :
 * elles ne sont plus en base.
 */
class PagesCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PagesCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $categories = [
            ['Le club', 'le-club', 'Histoire, identité et fonctionnement du Cercle des Nageurs du Bocage Bressuirais.'],
            ['Vie associative', 'vie-associative', 'Bénévolat, officiels et comité directeur : comment s\'impliquer dans le club.'],
            ['Questions fréquentes', 'questions-frequentes', 'Les réponses aux questions les plus posées par les familles et les nageurs.'],
        ];

        foreach ($categories as $i => [$name, $slug, $description]) {
            PagesCategory::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'image' => null,
                'meta_title' => "{$name} - CNBB Natation Bressuire",
                'meta_description' => $description,
                'meta_keywords' => mb_strtolower($name) . ', CNBB, club de natation, Bressuire',
                'is_active' => true,
                'sort_order' => $i + 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]);
            $this->command->info("✅ Catégorie de pages : {$name}");
        }

        $this->command->info('🎉 PagesCategoriesSeeder : ' . count($categories) . ' catégories créées');
    }
}
