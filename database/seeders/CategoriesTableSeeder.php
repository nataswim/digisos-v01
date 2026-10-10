<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catégories des articles (menu « Vie du club » et « Médias > Revue de presse »).
 * Le slug « presse » est utilisé par PublicController::PRESS_CATEGORY_SLUG : ne pas le modifier.
 */
class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $categories = [
            [
                'name' => 'Vie du club',
                'slug' => 'vie-du-club',
                'description' => 'Les nouvelles du Cercle des Nageurs du Bocage Bressuirais : inscriptions, assemblée générale, bénévolat, informations aux familles.',
                'meta_title' => 'Vie du club - CNBB Natation Bressuire',
                'meta_description' => 'Actualités du Cercle des Nageurs du Bocage Bressuirais : inscriptions, vie associative et informations aux familles.',
                'meta_keywords' => 'CNBB, club de natation, Bressuire, actualités, vie associative',
            ],
            [
                'name' => 'Compétitions',
                'slug' => 'competitions',
                'description' => 'Calendrier, comptes rendus et résultats des nageurs du CNBB en compétitions FFN départementales, régionales et nationales.',
                'meta_title' => 'Compétitions et résultats - CNBB Natation',
                'meta_description' => 'Résultats et comptes rendus des compétitions de natation course des nageurs du CNBB.',
                'meta_keywords' => 'compétition natation, résultats, FFN, natation course, Deux-Sèvres',
            ],
            [
                'name' => 'École de natation',
                'slug' => 'ecole-de-natation',
                'description' => 'Apprentissage et perfectionnement des jeunes nageurs : groupes, tests fédéraux (Sauv\'nage, Pass\'sports de l\'eau, Pass\'compétition) et progression.',
                'meta_title' => 'École de natation - CNBB Bressuire',
                'meta_description' => 'L\'école de natation du CNBB : apprentissage, tests fédéraux et progression des jeunes nageurs.',
                'meta_keywords' => 'école de natation, Sauv\'nage, Pass\'sports de l\'eau, Pass\'compétition, enfants',
            ],
            [
                'name' => 'Événements',
                'slug' => 'evenements',
                'description' => 'Les rendez-vous organisés ou soutenus par le club : Aquathlon du Bocage, journées portes ouvertes, fêtes du club.',
                'meta_title' => 'Événements - CNBB Natation',
                'meta_description' => 'Les événements du Cercle des Nageurs du Bocage Bressuirais.',
                'meta_keywords' => 'événement, Aquathlon du Bocage, fête du club, Bressuire',
            ],
            [
                'name' => 'Revue de presse',
                'slug' => 'presse',
                'description' => 'Les articles de presse et publications officielles consacrés au club depuis sa création en 1954.',
                'meta_title' => 'Revue de presse - CNBB Natation',
                'meta_description' => 'Le CNBB dans la presse : articles et publications consacrés au club de natation de Bressuire.',
                'meta_keywords' => 'revue de presse, CNBB, natation Bressuire, article',
            ],
        ];

        foreach ($categories as $i => $data) {
            Category::create($data + [
                'group_name' => 'blog',
                'image' => null,
                'order' => $i + 1,
                'status' => 'active',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]);
            $this->command->info("✅ Catégorie : {$data['name']}");
        }

        $this->command->info('🎉 CategoriesTableSeeder : ' . count($categories) . ' catégories créées');
    }
}
