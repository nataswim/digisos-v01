<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Vidéos « Au fil de l'eau ».
 *
 * Pour ajouter une vidéo, recopier le modèle commenté en fin de liste
 * (external_id = identifiant après « v= » sur YouTube, ou le nombre à la fin de l'adresse Vimeo).
 * Miniature laissée vide : la vue peut l'afficher depuis YouTube
 * (https://img.youtube.com/vi/{external_id}/hqdefault.jpg).
 */
class VideosSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Video::truncate();
        DB::table('category_video')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $author = User::whereHas('role', fn ($q) => $q->where('slug', 'editor'))->first()
            ?? User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $videos = [
            [
                'category' => 'entrainements',
                'title' => 'Préparation physique du nageur',
                'slug' => 'preparation-physique-du-nageur',
                'description' => 'Exercices de renforcement et de préparation physique hors de l\'eau pour les nageurs (vidéo de la chaîne SwimTube, en anglais).',
                'type' => 'youtube',
                'external_id' => 'yTUs_IlYQdc',
                'is_featured' => true,
            ],
            [
                // Titre et description à vérifier : la page YouTube n'a pas pu être lue.
                'category' => 'entrainements',
                'title' => 'Vidéo d\'entraînement natation',
                'slug' => 'video-entrainement-natation',
                'description' => 'Vidéo d\'entraînement sélectionnée par les entraîneurs du club.',
                'type' => 'youtube',
                'external_id' => '6AhY6oLf6PM',
                'is_featured' => false,
            ],
            // Modèle pour ajouter une vidéo :
            // [
            //     'category' => 'vie-du-club',   // entrainements | competitions | vie-du-club
            //     'title' => 'Titre de la vidéo',
            //     'slug' => 'titre-de-la-video',
            //     'description' => 'Une ou deux phrases de présentation.',
            //     'type' => 'youtube',             // youtube | vimeo | dailymotion
            //     'external_id' => 'XXXXXXXXXXX',  // ce qui suit « v= » dans l'adresse YouTube
            //     'is_featured' => false,
            // ],
        ];

        if ($videos === []) {
            $this->command->warn('⚠️  VideosSeeder : aucune vidéo du club renseignée, rubrique « Au fil de l\'eau » vide.');
            return;
        }

        foreach ($videos as $i => $v) {
            $url = match ($v['type']) {
                'vimeo' => "https://vimeo.com/{$v['external_id']}",
                'dailymotion' => "https://www.dailymotion.com/video/{$v['external_id']}",
                default => "https://www.youtube.com/watch?v={$v['external_id']}",
            };

            $video = Video::create([
                'title' => $v['title'],
                'slug' => $v['slug'],
                'description' => $v['description'],
                'type' => $v['type'],
                'external_url' => $url,
                'external_id' => $v['external_id'],
                'thumbnail' => null,
                'duration' => $v['duration'] ?? null,
                'width' => 1920,
                'height' => 1080,
                'visibility' => 'public',
                'is_published' => true,
                'is_featured' => $v['is_featured'] ?? false,
                'sort_order' => $i + 1,
                'views_count' => 0,
                'meta_title' => mb_strimwidth($v['title'], 0, 55, '…') . ' - CNBB',
                'meta_keywords' => 'vidéo, natation, CNBB, Bressuire',
                'meta_description' => mb_strimwidth($v['description'], 0, 160, '…'),
                'created_by' => $author?->id,
                'created_by_name' => $author?->name,
                'updated_by' => $author?->id,
                'published_at' => now(),
            ]);

            $category = VideoCategory::where('slug', $v['category'])->first();
            if ($category) {
                $video->categories()->attach($category->id);
            } else {
                $this->command->warn("⚠️  Catégorie vidéo introuvable : {$v['category']}");
            }

            $this->command->info("✅ Vidéo : {$video->title}");
        }

        $this->command->info('🎉 VideosSeeder : ' . count($videos) . ' vidéos créées');
    }
}
