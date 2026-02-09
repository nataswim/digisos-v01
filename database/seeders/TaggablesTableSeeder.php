<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TaggablesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('taggables')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tagPerformance = Tag::where('slug', 'performance')->first();
        $tagGestion = Tag::where('slug', 'gestion')->first();
        $tagTechnologie = Tag::where('slug', 'technologie')->first();

        $post1 = Post::where('slug', 'digitalsos-revolutionne-gestion-sportive')->first();
        $post2 = Post::where('slug', '5-astuces-optimiser-plannings-entrainement')->first();
        $post3 = Post::where('slug', 'club-aquasport-triple-adhesions')->first();

        $taggables = [
            // Post 1 : Gestion + Technologie
            ['tag_id' => $tagGestion->id, 'taggable_id' => $post1->id, 'taggable_type' => 'App\Models\Post'],
            ['tag_id' => $tagTechnologie->id, 'taggable_id' => $post1->id, 'taggable_type' => 'App\Models\Post'],
            
            // Post 2 : Performance + Gestion
            ['tag_id' => $tagPerformance->id, 'taggable_id' => $post2->id, 'taggable_type' => 'App\Models\Post'],
            ['tag_id' => $tagGestion->id, 'taggable_id' => $post2->id, 'taggable_type' => 'App\Models\Post'],
            
            // Post 3 : Gestion + Technologie
            ['tag_id' => $tagGestion->id, 'taggable_id' => $post3->id, 'taggable_type' => 'App\Models\Post'],
            ['tag_id' => $tagTechnologie->id, 'taggable_id' => $post3->id, 'taggable_type' => 'App\Models\Post'],
        ];

        foreach ($taggables as $data) {
            DB::table('taggables')->insert($data);
        }

        $this->command->info('🎉 TaggablesTableSeeder terminé : 6 associations tags-posts créées');
    }
}
