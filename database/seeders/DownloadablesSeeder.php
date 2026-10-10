<?php

namespace Database\Seeders;

use App\Models\DownloadCategory;
use App\Models\Downloadable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Documents à télécharger (rubrique « Ressources »).
 *
 * Un document n'est créé que si son fichier existe sur le disque « local »,
 * celui que lit le modèle Downloadable (Laravel 12 : storage/app/private/…).
 * Déposer les PDF du club aux chemins indiqués,
 * puis relancer : php artisan db:seed --class=DownloadablesSeeder
 */
class DownloadablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Downloadable::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $author = User::whereHas('role', fn ($q) => $q->where('slug', 'editor'))->first()
            ?? User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $documents = [
            [
                'category' => 'documents-inscription',
                'title' => 'Fiche d\'inscription 2026-2027',
                'slug' => 'fiche-inscription-2026-2027',
                'file_path' => 'downloads/fiche-inscription-2026-2027.pdf',
                'short_description' => 'Le formulaire d\'adhésion de la saison, à compléter et signer.',
                'long_description' => '<p>Imprimez la fiche, complétez-la et joignez-la au dossier avec le règlement, le certificat médical ou l\'attestation, la photo (nouveaux adhérents) et la caution de 5 € pour le badge.</p><p>Dossier à déposer dans la boîte aux lettres du club, derrière l\'abribus en bas de Cœur d\'O.</p>',
                'is_featured' => true,
            ],
            [
                'category' => 'documents-inscription',
                'title' => 'Fiche sanitaire (compétiteurs)',
                'slug' => 'fiche-sanitaire-competiteurs',
                'file_path' => 'downloads/fiche-sanitaire.pdf',
                'short_description' => 'À joindre au dossier d\'inscription des nageurs compétiteurs.',
                'long_description' => '<p>La fiche sanitaire est demandée pour les nageurs qui participent aux compétitions et aux déplacements.</p>',
                'is_featured' => false,
            ],
            [
                'category' => 'documents-inscription',
                'title' => 'Attestation de santé pour les mineurs (CERFA 15699-01)',
                'slug' => 'attestation-sante-mineurs-cerfa-15699',
                'file_path' => 'downloads/cerfa-15699-01.pdf',
                'short_description' => 'Remplace le certificat médical pour les nageurs mineurs.',
                'long_description' => '<p>Pour les nageurs mineurs, cette attestation remplie par les parents remplace le certificat médical.</p>',
                'is_featured' => false,
            ],
        ];

        $disk = Storage::disk('local');
        $created = 0;

        foreach ($documents as $i => $d) {
            if (! $disk->exists($d['file_path'])) {
                $this->command->warn("⚠️  Fichier absent, document ignoré : {$disk->path($d['file_path'])}");
                continue;
            }

            $category = DownloadCategory::where('slug', $d['category'])->first()
                ?? throw new \RuntimeException("Catégorie « {$d['category']} » absente : lancer DownloadCategoriesSeeder d'abord.");

            Downloadable::create([
                'title' => $d['title'],
                'slug' => $d['slug'],
                'format' => 'pdf',
                'short_description' => $d['short_description'],
                'long_description' => $d['long_description'],
                'file_path' => $d['file_path'],
                'file_size' => $disk->size($d['file_path']),
                'cover_image' => null,
                'download_category_id' => $category->id,
                'user_permission' => 'user',
                'download_count' => 0,
                'order' => $i + 1,
                'status' => 'active',
                'is_featured' => $d['is_featured'],
                'meta_title' => mb_strimwidth($d['title'], 0, 55, '…') . ' - CNBB',
                'meta_keywords' => 'CNBB, inscription, document, natation Bressuire',
                'meta_description' => mb_strimwidth($d['short_description'], 0, 160, '…'),
                'created_by' => $author?->id,
                'created_by_name' => $author?->name,
                'updated_by' => $author?->id,
            ]);
            $created++;
            $this->command->info("✅ Document : {$d['title']}");
        }

        $this->command->info("🎉 DownloadablesSeeder : {$created} document(s) sur " . count($documents) . ' créés');
    }
}
