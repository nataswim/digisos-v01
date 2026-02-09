<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VideoCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VideoCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        VideoCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->first();

        $categories = [
            [
                'name' => 'Tutoriels Techniques',
                'slug' => 'tutoriels-techniques',
                'description' => 'Vidéos pédagogiques démontrant des exercices, mouvements et techniques sportives avec analyses détaillées.',
                'image' => 'video-categories/tutoriels-techniques.jpg',
                'meta_title' => 'Tutoriels techniques - Vidéos Digital\'SOS',
                'meta_description' => 'Apprenez avec nos tutoriels vidéo : exercices, techniques et mouvements sportifs analysés.',
                'meta_keywords' => 'tutoriels, techniques, vidéos, exercices',
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Formations & Webinaires',
                'slug' => 'formations-webinaires',
                'description' => 'Enregistrements de formations, webinaires et conférences pour développer vos compétences en gestion sportive.',
                'image' => 'video-categories/formations-webinaires.jpg',
                'meta_title' => 'Formations et webinaires - Vidéos Digital\'SOS',
                'meta_description' => 'Accédez à nos formations vidéo et webinaires pour développer vos compétences sportives.',
                'meta_keywords' => 'formations, webinaires, conférences, apprentissage',
                'is_active' => true,
                'sort_order' => 2,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Témoignages Vidéo',
                'slug' => 'temoignages-video',
                'description' => 'Retours d\'expérience filmés de coachs, clubs et athlètes utilisant Digital\'SOS au quotidien.',
                'image' => 'video-categories/temoignages-video.jpg',
                'meta_title' => 'Témoignages vidéo - Digital\'SOS',
                'meta_description' => 'Visionnez les témoignages de nos utilisateurs : coachs, clubs et athlètes partagent leur expérience.',
                'meta_keywords' => 'témoignages, retours expérience, vidéos, avis',
                'is_active' => true,
                'sort_order' => 3,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
        ];

        foreach ($categories as $data) {
            $category = VideoCategory::create($data);
            $this->command->info("✅ Catégorie vidéo : {$category->name}");
        }

        $this->command->info('🎉 VideoCategoriesSeeder terminé : 3 catégories créées');
    }
}
