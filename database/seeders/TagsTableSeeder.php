<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * 🇬🇧 Tags Table Seeder - Creates post tags for Digital'SOS
 * 🇫🇷 Seeder de la table tags - Crée les tags de posts pour Digital'SOS
 * 
 * @file database/seeders/TagsTableSeeder.php
 */
class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🇬🇧 Disable foreign key checks / 🇫🇷 Désactiver les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 🇬🇧 Truncate table / 🇫🇷 Vider la table
        Tag::truncate();
        
        // 🇬🇧 Re-enable foreign key checks / 🇫🇷 Réactiver les vérifications
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🇬🇧 Get first admin user as creator / 🇫🇷 Récupérer le premier admin comme créateur
        $admin = User::whereHas('role', function ($query) {
            $query->where('slug', 'admin');
        })->first();

        if (!$admin) {
            $this->command->warn('⚠️  Aucun admin trouvé. Les tags seront créés sans créateur.');
        }

        // 🇬🇧 Define tags / 🇫🇷 Définir les tags
        $tags = [
            [
                'name' => 'Performance',
                'slug' => 'performance',
                'group_name' => 'blog',
                'description' => 'Articles axés sur l\'optimisation des performances sportives, l\'amélioration des résultats et l\'atteinte des objectifs.',
                'image' => 'tags/performance.jpg',
                'status' => 'active',
                'meta_title' => 'Performance sportive - Digital\'SOS',
                'meta_description' => 'Découvrez nos articles sur l\'optimisation des performances, les techniques d\'entraînement et l\'amélioration des résultats sportifs.',
                'meta_keywords' => 'performance sportive, optimisation, résultats, objectifs',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Gestion',
                'slug' => 'gestion',
                'group_name' => 'blog',
                'description' => 'Contenus dédiés à l\'organisation, au management des structures sportives, à la gestion du personnel et du matériel.',
                'image' => 'tags/gestion.jpg',
                'status' => 'active',
                'meta_title' => 'Gestion sportive - Digital\'SOS',
                'meta_description' => 'Articles sur la gestion et l\'organisation de structures sportives : management, personnel, matériel, plannings.',
                'meta_keywords' => 'gestion sportive, organisation, management, planification',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Technologie',
                'slug' => 'technologie',
                'group_name' => 'blog',
                'description' => 'Innovations digitales, outils technologiques et solutions numériques pour moderniser la gestion sportive et l\'entraînement.',
                'image' => 'tags/technologie.jpg',
                'status' => 'active',
                'meta_title' => 'Technologie sportive - Digital\'SOS',
                'meta_description' => 'Explorez les innovations technologiques et outils digitaux qui transforment la gestion sportive et l\'entraînement moderne.',
                'meta_keywords' => 'technologie sportive, innovation, digital, outils numériques',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
        ];

        // 🇬🇧 Create tags / 🇫🇷 Créer les tags
        foreach ($tags as $tagData) {
            $tag = Tag::create($tagData);
            
            $this->command->info("✅ Tag créé : {$tag->name} (slug: {$tag->slug})");
        }

        $this->command->info('');
        $this->command->info('🎉 TagsTableSeeder terminé avec succès !');
        $this->command->info('📊 3 tags créés pour enrichir les posts :');
        $this->command->info('');
        $this->command->table(
            ['Nom', 'Slug', 'Description courte', 'Statut'],
            [
                ['Performance', 'performance', 'Optimisation résultats sportifs', '✅ Active'],
                ['Gestion', 'gestion', 'Organisation et management', '✅ Active'],
                ['Technologie', 'technologie', 'Outils digitaux et innovations', '✅ Active'],
            ]
        );
    }
}
