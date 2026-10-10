<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Tag::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $tags = [
            ['Inscriptions', 'inscriptions', 'Adhésion, tarifs, dossier et calendrier d\'inscription au club.'],
            ['École de natation', 'ecole-de-natation', 'Apprentissage, tests fédéraux et progression des jeunes nageurs.'],
            ['Natation course', 'natation-course', 'Entraînement et compétition en natation course.'],
            ['Bénévolat', 'benevolat', 'Officiels, encadrants et membres du comité directeur : le club vit grâce à ses bénévoles.'],
            ['Histoire du club', 'histoire-du-club', 'Les grandes dates du club depuis sa fondation en 1954.'],
            ['Cœur d\'O', 'coeur-d-o', 'Le centre aquatique Cœur d\'O de l\'Agglo 2B, piscine du club.'],
            ['Aquathlon', 'aquathlon', 'L\'Aquathlon du Bocage et les épreuves enchaînant natation et course à pied.'],
        ];

        foreach ($tags as [$name, $slug, $description]) {
            Tag::create([
                'name' => $name,
                'slug' => $slug,
                'group_name' => 'blog',
                'description' => $description,
                'image' => null,
                'status' => 'active',
                'meta_title' => "{$name} - CNBB Natation",
                'meta_description' => $description,
                'meta_keywords' => mb_strtolower($name) . ', CNBB, natation, Bressuire',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]);
            $this->command->info("✅ Tag : {$name}");
        }

        $this->command->info('🎉 TagsTableSeeder : ' . count($tags) . ' tags créés');
    }
}
