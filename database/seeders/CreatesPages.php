<?php

namespace Database\Seeders\Concerns;

use App\Models\Page;
use App\Models\PagesCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Création de pages du club à partir d'un tableau simple.
 * Utilisé par PagesOrganisationSeeder et GroupesPagesSeeder.
 *
 * Chaque page : category (slug), title, slug, short_description, long_description (HTML).
 * L'ordre d'affichage continue après les pages déjà présentes dans la catégorie.
 */
trait CreatesPages
{
    protected function createPages(array $pages): int
    {
        $author = User::whereHas('role', fn ($q) => $q->where('slug', 'editor'))->first()
            ?? User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $count = 0;
        foreach ($pages as $p) {
            $category = PagesCategory::where('slug', $p['category'])->first()
                ?? throw new \RuntimeException("Catégorie de pages « {$p['category']} » absente : lancer PagesCategoriesSeeder d'abord.");

            // Relancer le seeder seul ne crée pas de doublon
            DB::table('pages')->where('slug', $p['slug'])->delete();

            Page::create([
                'title' => $p['title'],
                'slug' => $p['slug'],
                'short_description' => $p['short_description'],
                'long_description' => trim($p['long_description']),
                'image' => null,
                'visibility' => 'public',
                'is_published' => true,
                'sort_order' => (int) Page::where('pages_category_id', $category->id)->max('sort_order') + 1,
                'pages_category_id' => $category->id,
                'meta_title' => mb_strimwidth($p['title'], 0, 55, '…') . ' - CNBB',
                'meta_keywords' => 'CNBB, club de natation, Bressuire, ' . mb_strtolower($p['title']),
                'meta_description' => mb_strimwidth($p['short_description'], 0, 160, '…'),
                'created_by' => $author?->id,
                'created_by_name' => $author?->name,
                'updated_by' => $author?->id,
                'published_at' => now(),
            ]);

            $count++;
            $this->command->info("✅ Page : {$p['title']}");
        }

        return $count;
    }

    /** Liste HTML à puces à partir d'un tableau de lignes (HTML autorisé dans les lignes). */
    protected function ul(array $items): string
    {
        return "<ul>\n" . implode("\n", array_map(fn ($i) => "<li>{$i}</li>", $items)) . "\n</ul>";
    }
}
