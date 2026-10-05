<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FichesCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * 🇬🇧 Fiches Categories Seeder - Creates fiche categories for Mon Club de Natation
 * 🇫🇷 Seeder des catégories de fiches - Crée les catégories de fiches pour Mon Club de Natation
 * 
 * @file database/seeders/FichesCategoriesSeeder.php
 */
class FichesCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🇬🇧 Disable foreign key checks / 🇫🇷 Désactiver les vérifications de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 🇬🇧 Truncate table / 🇫🇷 Vider la table
        FichesCategory::truncate();
        
        // 🇬🇧 Re-enable foreign key checks / 🇫🇷 Réactiver les vérifications
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🇬🇧 Get first admin user as creator / 🇫🇷 Récupérer le premier admin comme créateur
        $admin = User::whereHas('role', function ($query) {
            $query->where('slug', 'admin');
        })->first();

        if (!$admin) {
            $this->command->warn('⚠️  Aucun admin trouvé. Les catégories seront créées sans créateur.');
        }

        // 🇬🇧 Define fiche categories / 🇫🇷 Définir les catégories de fiches
        $categories = [
            [
                'name' => 'Techniques d\'Entraînement',
                'slug' => 'techniques-entrainement',
                'description' => 'Fiches techniques détaillant les méthodes d\'entraînement, exercices spécifiques, programmes de développement des compétences et protocoles de préparation physique.',
                'image' => 'fiches-categories/techniques-entrainement.jpg',
                'meta_title' => 'Techniques d\'entraînement - Fiches Digital\'SOS',
                'meta_description' => 'Consultez nos fiches techniques sur les méthodes d\'entraînement, exercices et protocoles pour optimiser la préparation physique.',
                'meta_keywords' => 'techniques entraînement, exercices, préparation physique, protocoles sportifs',
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Gestion Administrative',
                'slug' => 'gestion-administrative',
                'description' => 'Documentation administrative complète : procédures internes, formulaires types, règlements, guides de conformité et outils de gestion quotidienne des structures sportives.',
                'image' => 'fiches-categories/gestion-administrative.jpg',
                'meta_title' => 'Gestion administrative - Fiches Digital\'SOS',
                'meta_description' => 'Accédez à nos fiches de gestion administrative : procédures, formulaires, règlements et outils pour gérer votre structure sportive.',
                'meta_keywords' => 'gestion administrative, procédures, formulaires, règlements, documentation',
                'is_active' => true,
                'sort_order' => 2,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Matériel & Équipement',
                'slug' => 'materiel-equipement',
                'description' => 'Guides d\'utilisation du matériel sportif, procédures de maintenance, inventaires types, conseils d\'entretien et fiches de sécurité pour tous les équipements.',
                'image' => 'fiches-categories/materiel-equipement.jpg',
                'meta_title' => 'Matériel et équipement - Fiches Digital\'SOS',
                'meta_description' => 'Découvrez nos fiches pratiques sur le matériel sportif : guides d\'utilisation, maintenance, inventaires et sécurité.',
                'meta_keywords' => 'matériel sportif, équipement, maintenance, inventaire, guides utilisation',
                'is_active' => true,
                'sort_order' => 3,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
        ];

        // 🇬🇧 Create categories / 🇫🇷 Créer les catégories
        foreach ($categories as $categoryData) {
            $category = FichesCategory::create($categoryData);
            
            $this->command->info("✅ Catégorie de fiches créée : {$category->name} (slug: {$category->slug})");
        }

        $this->command->info('');
        $this->command->info('🎉 FichesCategoriesSeeder terminé avec succès !');
        $this->command->info('📊 3 catégories de fiches créées :');
        $this->command->info('');
        $this->command->table(
            ['Nom', 'Slug', 'Ordre', 'Statut'],
            [
                ['Techniques d\'Entraînement', 'techniques-entrainement', '1', '✅ Active'],
                ['Gestion Administrative', 'gestion-administrative', '2', '✅ Active'],
                ['Matériel & Équipement', 'materiel-equipement', '3', '✅ Active'],
            ]
        );
    }
}
