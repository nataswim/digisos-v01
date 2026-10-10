<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaggablesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('taggables')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tags = Tag::pluck('id', 'slug');
        $posts = Post::pluck('id', 'slug');
        $rows = [];

        foreach (PostsTableSeeder::tagMap() as $postSlug => $tagSlugs) {
            if (! isset($posts[$postSlug])) {
                $this->command->warn("⚠️  Article introuvable : {$postSlug}");
                continue;
            }
            foreach ($tagSlugs as $tagSlug) {
                if (! isset($tags[$tagSlug])) {
                    $this->command->warn("⚠️  Tag introuvable : {$tagSlug}");
                    continue;
                }
                $rows[] = [
                    'tag_id' => $tags[$tagSlug],
                    'taggable_id' => $posts[$postSlug],
                    'taggable_type' => Post::class,
                ];
            }
        }

        DB::table('taggables')->insert($rows);

        $this->command->info('🎉 TaggablesTableSeeder : ' . count($rows) . ' associations tags-articles créées');
    }
}
