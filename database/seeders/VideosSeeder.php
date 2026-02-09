<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VideosSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Video::truncate();
        DB::table('category_video')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $editor = User::whereHas('role', fn($q) => $q->where('slug', 'editor'))->first();
        
        $categoryTutoriels = VideoCategory::where('slug', 'tutoriels-techniques')->first();
        $categoryFormations = VideoCategory::where('slug', 'formations-webinaires')->first();
        $categoryTemoignages = VideoCategory::where('slug', 'temoignages-video')->first();

        $videos = [
            [
                'title' => 'Technique du virage en natation : analyse complète',
                'slug' => 'technique-virage-natation-analyse-complete',
                'description' => 'Vidéo pédagogique détaillant les 4 phases du virage culbute en crawl avec ralentis et annotations pour optimiser vos transitions.',
                'type' => 'youtube',
                'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'external_id' => 'dQw4w9WgXcQ',
                'thumbnail' => 'videos/thumbnails/virage-natation.jpg',
                'duration' => 480,
                'width' => 1920,
                'height' => 1080,
                'visibility' => 'authenticated',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'views_count' => 567,
                'meta_title' => 'Technique du virage en natation - Tutoriel Digital\'SOS',
                'meta_keywords' => 'virage, natation, culbute, technique, tutoriel',
                'meta_description' => 'Apprenez la technique parfaite du virage culbute en crawl avec notre analyse vidéo complète.',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Webinaire : Digitaliser sa structure sportive en 2025',
                'slug' => 'webinaire-digitaliser-structure-sportive-2025',
                'description' => 'Enregistrement du webinaire du 15 janvier 2025 avec Hassan El Haouat : stratégies, outils et retours d\'expérience pour réussir sa transformation digitale.',
                'type' => 'vimeo',
                'external_url' => 'https://vimeo.com/123456789',
                'external_id' => '123456789',
                'thumbnail' => 'videos/thumbnails/webinaire-digitalisation.jpg',
                'duration' => 3600,
                'width' => 1920,
                'height' => 1080,
                'visibility' => 'public',
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'views_count' => 823,
                'meta_title' => 'Webinaire digitalisation sportive 2025 - Digital\'SOS',
                'meta_keywords' => 'webinaire, digitalisation, transformation, 2025',
                'meta_description' => 'Replay du webinaire sur la digitalisation des structures sportives : stratégies et outils 2025.',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Témoignage : Le Club Aqua Sport raconte sa transformation',
                'slug' => 'temoignage-club-aqua-sport-transformation',
                'description' => 'Interview de Marc Durand, président du Club Aqua Sport, qui partage son expérience avec Digital\'SOS : de 50 à 150 licenciés en 18 mois.',
                'type' => 'youtube',
                'external_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'external_id' => '9bZkp7q19f0',
                'thumbnail' => 'videos/thumbnails/temoignage-aquasport.jpg',
                'duration' => 720,
                'width' => 1920,
                'height' => 1080,
                'visibility' => 'public',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'views_count' => 1245,
                'meta_title' => 'Témoignage Aqua Sport - Success Story Digital\'SOS',
                'meta_keywords' => 'témoignage, aqua sport, success story, transformation',
                'meta_description' => 'Découvrez comment le Club Aqua Sport a triplé ses adhésions grâce à Digital\'SOS.',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(18),
            ],
        ];

        foreach ($videos as $data) {
            $video = Video::create($data);
            
            // Attacher catégories
            if ($data['slug'] === 'technique-virage-natation-analyse-complete') {
                $video->categories()->attach($categoryTutoriels->id);
            } elseif ($data['slug'] === 'webinaire-digitaliser-structure-sportive-2025') {
                $video->categories()->attach($categoryFormations->id);
            } elseif ($data['slug'] === 'temoignage-club-aqua-sport-transformation') {
                $video->categories()->attach($categoryTemoignages->id);
            }
            
            $this->command->info("✅ Vidéo créée : {$video->title}");
        }

        $this->command->info('🎉 VideosSeeder terminé : 3 vidéos créées');
    }
}
