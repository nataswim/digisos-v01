<?php

namespace Database\Seeders;

use App\Models\DownloadCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catégories de la rubrique « Ressources » (documents à télécharger).
 */
class DownloadCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DownloadCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $categories = [
            [
                'name' => 'Documents d\'inscription',
                'slug' => 'documents-inscription',
                'short_description' => 'Fiche d\'inscription, fiche sanitaire et attestation de santé.',
                'description' => 'Les formulaires à imprimer, compléter et joindre au dossier d\'inscription déposé dans la boîte aux lettres du club.',
                'icon' => 'fas fa-file-signature',
            ],
            [
                'name' => 'Vie du club',
                'slug' => 'vie-du-club',
                'description' => 'Statuts, règlement intérieur, comptes rendus d\'assemblée générale et documents de l\'association.',
                'short_description' => 'Statuts, règlement intérieur et comptes rendus.',
                'icon' => 'fas fa-users',
            ],
            [
                'name' => 'Compétitions',
                'slug' => 'competitions',
                'short_description' => 'Calendriers et documents liés aux compétitions.',
                'description' => 'Calendriers de la saison, convocations et documents pour les nageurs compétiteurs et les officiels.',
                'icon' => 'fas fa-stopwatch',
            ],
        ];

        foreach ($categories as $i => $data) {
            DownloadCategory::create($data + [
                'order' => $i + 1,
                'status' => 'active',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]);
            $this->command->info("✅ Catégorie de ressources : {$data['name']}");
        }

        $this->command->info('🎉 DownloadCategoriesSeeder : ' . count($categories) . ' catégories créées');
    }
}
