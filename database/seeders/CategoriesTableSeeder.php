<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * 🇬🇧 Categories Table Seeder - Creates post categories for Digital'SOS
 * 🇫🇷 Seeder de la table categories - Crée les catégories de posts pour Digital'SOS
 * 
 * @file database/seeders/CategoriesTableSeeder.php
 */
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🇬🇧 Disable foreign key checks / 🇫🇷 Désactiver les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 🇬🇧 Truncate table / 🇫🇷 Vider la table
        Category::truncate();
        
        // 🇬🇧 Re-enable foreign key checks / 🇫🇷 Réactiver les vérifications
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🇬🇧 Get first admin user as creator / 🇫🇷 Récupérer le premier admin comme créateur
        $admin = User::whereHas('role', function ($query) {
            $query->where('slug', 'admin');
        })->first();

        if (!$admin) {
            $this->command->warn('⚠️  Aucun admin trouvé. Les catégories seront créées sans créateur.');
        }

        // 🇬🇧 Define categories / 🇫🇷 Définir les catégories
        $categories = [
            [
                'name' => 'Actualités',
                'slug' => 'actualites',
                'description' => 'Dernières nouvelles et événements du monde sportif. Suivez les actualités des clubs, compétitions et innovations dans le secteur.',
                'group_name' => 'blog',
                'image' => 'categories/actualites.jpg',
                'meta_title' => 'Actualités sportives - Digital\'SOS',
                'meta_description' => 'Restez informé des dernières actualités sportives, événements et nouveautés dans la gestion de structures sportives.',
                'meta_keywords' => 'actualités sportives, événements, compétitions, news sport',
                'order' => 1,
                'status' => 'active',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Conseils & Méthodologie',
                'slug' => 'conseils-methodologie',
                'description' => 'Guides pratiques, méthodes d\'entraînement et conseils d\'experts pour optimiser la gestion de votre structure sportive et améliorer les performances.',
                'group_name' => 'blog',
                'image' => 'categories/conseils-methodologie.jpg',
                'meta_title' => 'Conseils et méthodologie sportive - Digital\'SOS',
                'meta_description' => 'Découvrez nos conseils d\'experts et méthodologies éprouvées pour optimiser votre organisation sportive et vos entraînements.',
                'meta_keywords' => 'conseils sportifs, méthodologie, gestion sportive, entraînement, performance',
                'order' => 2,
                'status' => 'active',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Témoignages & Success Stories',
                'slug' => 'temoignages-success-stories',
                'description' => 'Retours d\'expérience de coachs, clubs et athlètes utilisant Digital\'SOS. Découvrez comment nos solutions transforment la gestion sportive au quotidien.',
                'group_name' => 'blog',
                'image' => 'categories/temoignages.jpg',
                'meta_title' => 'Témoignages clients - Digital\'SOS',
                'meta_description' => 'Lisez les témoignages de nos utilisateurs : coachs, clubs et athlètes qui ont révolutionné leur gestion avec Digital\'SOS.',
                'meta_keywords' => 'témoignages, success stories, retour expérience, avis clients',
                'order' => 3,
                'status' => 'active',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
        ];

        // 🇬🇧 Create categories / 🇫🇷 Créer les catégories
        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);
            
            $this->command->info("✅ Catégorie créée : {$category->name} (slug: {$category->slug})");
        }

        $this->command->info('');
        $this->command->info('🎉 CategoriesTableSeeder terminé avec succès !');
        $this->command->info('📊 3 catégories de posts créées :');
        $this->command->info('');
        $this->command->table(
            ['Nom', 'Slug', 'Ordre', 'Statut'],
            [
                ['Actualités', 'actualites', '1', '✅ Active'],
                ['Conseils & Méthodologie', 'conseils-methodologie', '2', '✅ Active'],
                ['Témoignages & Success Stories', 'temoignages-success-stories', '3', '✅ Active'],
            ]
        );
    }
}
