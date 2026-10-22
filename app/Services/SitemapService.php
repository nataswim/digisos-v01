<?php

namespace App\Services;

use App\Models;
use App\Models\SitemapUrl;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/**
 * Plan du site (sitemap.xml) du CNBB.
 *
 * Fonctionnement « avec validation » :
 *   1. discoverUrls() recense les pages publiques du site (pages fixes + contenus publiés) ;
 *      les pages fixes sont approuvées d'office, les contenus attendent une approbation ;
 *      les approbations déjà données sont conservées d'une découverte à l'autre ;
 *      les adresses qui n'existent plus (contenu supprimé ou dépublié, ancien domaine) sont retirées.
 *   2. L'administrateur approuve ou écarte les adresses.
 *   3. generateXml() écrit storage/app/sitemap.xml, servi par la route /sitemap.xml.
 *
 * Contenus réservés aux membres (visibilité ≠ public) : jamais inclus.
 */
class SitemapService
{
    /** Fichier généré (hors de public/ : pas de conflit avec git sur le serveur). */
    public const FILE = 'app/sitemap.xml';

    /** Sources reconnues : clé => libellé (filtres et statistiques de la page d'administration). */
    public const SOURCES = [
        'pages_fixes'         => 'Pages fixes',
        'posts'               => 'Articles',
        'post_categories'     => 'Catégories d\'articles',
        'fiches'              => 'Fiches',
        'fiches_categories'   => 'Catégories de fiches',
        'fiches_sous_categories' => 'Sous-catégories de fiches',
        'pages'               => 'Pages',
        'pages_categories'    => 'Catégories de pages',
        'videos'              => 'Vidéos',
        'video_categories'    => 'Catégories de vidéos',
        'galleries'           => 'Galeries photo',
        'downloadables'       => 'Documents',
        'download_categories' => 'Catégories de documents',
        'competitions'        => 'Compétitions',
        'partners'            => 'Partenaires',
        'shop_categories'     => 'Catégories de la boutique',
        'shop_products'       => 'Produits de la boutique',
        'installations'       => 'Installations',
    ];

    /** Pages fixes : nom de route => [priorité, fréquence]. Les routes absentes sont ignorées. */
    private const STATIC_ROUTES = [
        'home'                       => [1.0, 'daily'],
        'posts.public.index'         => [0.9, 'daily'],
        'competitions.index'         => [0.9, 'weekly'],
        'schedule'                   => [0.9, 'monthly'],
        'pricing'                    => [0.9, 'monthly'],
        'about'                      => [0.8, 'monthly'],
        'team.index'                 => [0.8, 'monthly'],
        'public.fiches.index'        => [0.8, 'weekly'],
        'public.pages.index'         => [0.8, 'monthly'],
        'public.installations.index' => [0.7, 'monthly'],
        'partners.index'             => [0.7, 'monthly'],
        'support.mecenat'            => [0.7, 'yearly'],
        'support.donation'           => [0.7, 'yearly'],
        'shop.index'                 => [0.7, 'weekly'],
        'galleries.index'            => [0.7, 'weekly'],
        'public.videos.index'        => [0.7, 'weekly'],
        'ebook.index'                => [0.6, 'monthly'],
        'links.index'                => [0.5, 'monthly'],
        'press'                      => [0.6, 'monthly'],
        'contact'                    => [0.6, 'yearly'],
        'legal'                      => [0.2, 'yearly'],
        'privacy'                    => [0.2, 'yearly'],
        'cookies'                    => [0.2, 'yearly'],
        'accessibility'              => [0.2, 'yearly'],
    ];

    /** Adresses vues pendant la découverte en cours. */
    private array $seen = [];

    private array $stats = ['static' => 0, 'dynamic' => 0, 'new' => 0, 'removed' => 0, 'errors' => []];

    /**
     * Recenser les pages publiques du site.
     *
     * @return array{static: int, dynamic: int, new: int, removed: int, total: int, errors: array<string>}
     */
    public function discoverUrls(): array
    {
        $this->seen = [];
        $this->stats = ['static' => 0, 'dynamic' => 0, 'new' => 0, 'removed' => 0, 'errors' => []];

        // Pages fixes
        foreach (self::STATIC_ROUTES as $name => [$priority, $changefreq]) {
            if (Route::has($name)) {
                $this->put(route($name), 'static', 'pages_fixes', null, $priority, $changefreq, null);
            }
        }

        // Contenus : chaque module est traité à part, une erreur dans l'un n'arrête pas les autres
        foreach ([
            'posts' => fn () => $this->posts(),
            'fiches' => fn () => $this->fiches(),
            'pages' => fn () => $this->pages(),
            'videos' => fn () => $this->videos(),
            'galleries' => fn () => $this->galleries(),
            'downloadables' => fn () => $this->downloadables(),
            'competitions' => fn () => $this->competitions(),
            'partners' => fn () => $this->partners(),
            'shop' => fn () => $this->shop(),
            'installations' => fn () => $this->installations(),
        ] as $module => $discover) {
            try {
                $discover();
            } catch (\Throwable $e) {
                Log::warning("Sitemap : module {$module} ignoré : " . $e->getMessage());
                $this->stats['errors'][] = $module;
            }
        }

        // Retirer les adresses découvertes auparavant qui n'existent plus (contenu supprimé ou
        // dépublié, ancien domaine, anciennes pages) ; les adresses ajoutées à la main sont gardées.
        // Si un module a échoué, on ne retire rien, pour ne pas perdre ses adresses par erreur.
        if (empty($this->stats['errors'])) {
            $stale = SitemapUrl::where('type', '!=', 'manual')->get(['id', 'url'])
                ->reject(fn ($row) => isset($this->seen[$row->url]));
            if ($stale->isNotEmpty()) {
                $this->stats['removed'] = SitemapUrl::whereIn('id', $stale->pluck('id'))->delete();
            }
        }

        $this->stats['total'] = $this->stats['static'] + $this->stats['dynamic'];
        Log::info('Sitemap : découverte terminée', $this->stats);

        return $this->stats;
    }

    // ------------------------------------------------------------------ modules

    private function posts(): void
    {
        Models\Post::published()->where('visibility', 'public')->with('category')
            ->each(function ($post) {
                $this->put(route('posts.public.show', $post), 'dynamic', 'posts', $post->id,
                    $post->is_featured ? 0.8 : 0.7, 'monthly', $post->updated_at ?? $post->published_at);
            });

        Models\Category::where('status', 'active')
            ->whereHas('posts', fn (Builder $q) => $q->published()->where('visibility', 'public'))
            ->each(function ($category) {
                $this->put(route('posts.public.category', $category), 'dynamic', 'post_categories', $category->id,
                    0.5, 'weekly', $category->updated_at);
            });
    }

    private function fiches(): void
    {
        Models\FichesCategory::active()->each(function ($category) {
            $this->put(route('public.fiches.category', $category), 'dynamic', 'fiches_categories', $category->id,
                0.6, 'weekly', $category->updated_at);
        });

        Models\FichesSousCategory::active()->with('category')->each(function ($sous) {
            if ($sous->category && $sous->category->is_active) {
                $this->put(route('public.fiches.sous-category', [$sous->category, $sous]), 'dynamic',
                    'fiches_sous_categories', $sous->id, 0.6, 'weekly', $sous->updated_at);
            }
        });

        Models\Fiche::published()->where('visibility', 'public')->with(['category', 'sousCategory'])
            ->each(function ($fiche) {
                // Seules les fiches rangées dans une catégorie et une sous-catégorie ont une page
                if ($fiche->category && $fiche->sousCategory) {
                    $this->put(route('public.fiches.show', [$fiche->category, $fiche->sousCategory, $fiche]), 'dynamic',
                        'fiches', $fiche->id, $fiche->is_featured ? 0.8 : 0.7, 'monthly', $fiche->updated_at ?? $fiche->published_at);
                }
            });
    }

    private function pages(): void
    {
        Models\PagesCategory::active()->each(function ($category) {
            $this->put(route('public.pages.category', $category), 'dynamic', 'pages_categories', $category->id,
                0.6, 'monthly', $category->updated_at);
        });

        Models\Page::where('is_published', true)->where('visibility', 'public')->with('category')
            ->each(function ($page) {
                // Les pages sont publiées à l'adresse /pages/{catégorie}/{page} : une page sans catégorie n'a pas d'adresse
                if ($page->category) {
                    $this->put(route('public.pages.show', [$page->category->slug, $page->slug]), 'dynamic', 'pages', $page->id,
                        0.7, 'monthly', $page->updated_at);
                }
            });
    }

    private function videos(): void
    {
        Models\VideoCategory::active()->each(function ($category) {
            $this->put(route('public.videos.category', $category), 'dynamic', 'video_categories', $category->id,
                0.5, 'weekly', $category->updated_at);
        });

        Models\Video::published()->where('visibility', 'public')->each(function ($video) {
            $this->put(route('public.videos.show', $video), 'dynamic', 'videos', $video->id,
                0.6, 'monthly', $video->updated_at ?? $video->published_at);
        });
    }

    private function galleries(): void
    {
        Models\PhotoGallery::published()->where('visibility', 'public')->each(function ($gallery) {
            $this->put(route('galleries.show', $gallery), 'dynamic', 'galleries', $gallery->id,
                0.6, 'monthly', $gallery->updated_at ?? $gallery->published_at);
        });
    }

    private function downloadables(): void
    {
        Models\DownloadCategory::active()->each(function ($category) {
            $this->put(route('ebook.category', $category->slug), 'dynamic', 'download_categories', $category->id,
                0.5, 'monthly', $category->updated_at);
        });

        Models\Downloadable::active()->with('category')->each(function ($doc) {
            if ($doc->category && $doc->category->status === 'active') {
                $this->put(route('ebook.show', [$doc->category->slug, $doc->slug]), 'dynamic', 'downloadables', $doc->id,
                    0.5, 'monthly', $doc->updated_at);
            }
        });
    }

    private function competitions(): void
    {
        if (! Route::has('competitions.show')) {
            return;
        }

        Models\Competition::published()->each(function ($competition) {
            $this->put(route('competitions.show', $competition), 'dynamic', 'competitions', $competition->id,
                0.7, 'weekly', $competition->updated_at);
        });
    }

    private function partners(): void
    {
        if (! Route::has('partners.show')) {
            return;
        }

        Models\Partner::where('is_active', true)->each(function ($partner) {
            $this->put(route('partners.show', $partner), 'dynamic', 'partners', $partner->id,
                0.5, 'monthly', $partner->updated_at);
        });
    }

    private function shop(): void
    {
        if (! Route::has('shop.show')) {
            return;
        }

        Models\ShopCategory::active()->whereHas('products', fn (Builder $q) => $q->where('is_active', true))
            ->each(function ($category) {
                $this->put(route('shop.category', $category), 'dynamic', 'shop_categories', $category->id,
                    0.6, 'weekly', $category->updated_at);
            });

        Models\ShopProduct::visible()->with('category')->each(function ($product) {
            $this->put(route('shop.show', [$product->category, $product]), 'dynamic', 'shop_products', $product->id,
                0.6, 'monthly', $product->updated_at);
        });
    }

    private function installations(): void
    {
        foreach ([
            [Models\Service::class,   'public.installations.service'],
            [Models\Structure::class, 'public.installations.structure'],
            [Models\Espace::class,    'public.installations.espace'],
            [Models\Zone::class,      'public.installations.zone'],
        ] as [$model, $route]) {
            if (! Route::has($route)) {
                continue;
            }
            $model::where('is_active', true)->each(function ($item) use ($route) {
                $this->put(route($route, $item), 'dynamic', 'installations', $item->id, 0.5, 'monthly', $item->updated_at);
            });
        }
    }

    // ------------------------------------------------------------------ enregistrement

    /**
     * Enregistrer une adresse. Nouvelle adresse : pages fixes approuvées, contenus en attente.
     * Adresse connue : l'approbation, la priorité et la fréquence choisies par l'administrateur sont gardées.
     */
    private function put(string $url, string $type, string $source, ?int $sourceId, float $priority, string $changefreq, $lastModified): void
    {
        if ($url === '' || $url === '#' || isset($this->seen[$url])) {
            return;
        }
        $this->seen[$url] = true;
        $this->stats[$type]++;

        $row = SitemapUrl::where('url', $url)->first();

        if (! $row) {
            SitemapUrl::create([
                'url' => $url,
                'type' => $type,
                'source' => $source,
                'source_id' => $sourceId,
                'priority' => $priority,
                'changefreq' => $changefreq,
                'is_approved' => $type === 'static',
                'is_active' => true,
                'last_modified' => $lastModified ?? now(),
            ]);
            $this->stats['new']++;

            return;
        }

        if ($row->type === 'manual') {
            return;   // ajoutée à la main : on n'y touche pas
        }

        $row->update([
            'type' => $type,
            'source' => $source,
            'source_id' => $sourceId,
            'last_modified' => $lastModified ?? $row->last_modified,
        ]);
    }

    // ------------------------------------------------------------------ fichier XML

    public static function filePath(): string
    {
        return storage_path(self::FILE);
    }

    /** Écrire le fichier sitemap.xml avec les adresses approuvées et actives. */
    public function generateXml(): array
    {
        $urls = SitemapUrl::forSitemap()->orderByDesc('priority')->orderBy('url')->get();

        if ($urls->isEmpty()) {
            throw new \RuntimeException('Aucune adresse approuvée : lancez d\'abord la découverte, puis approuvez des adresses.');
        }

        $path = self::filePath();
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $this->buildXml($urls), LOCK_EX);

        return [
            'urls_count' => $urls->count(),
            'file_path' => $path,
            'file_size' => filesize($path),
            'generated_at' => now(),
        ];
    }

    /** Contenu XML (utilisé aussi par la route /sitemap.xml tant que le fichier n'a pas été généré). */
    public function buildXml($urls = null): string
    {
        $urls ??= SitemapUrl::forSitemap()->orderByDesc('priority')->orderBy('url')->get();

        $esc = fn ($v) => htmlspecialchars((string) $v, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $row) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . $esc($row->url) . "</loc>\n";
            $xml .= '    <lastmod>' . ($row->last_modified ?? $row->updated_at)->toAtomString() . "</lastmod>\n";
            $xml .= '    <changefreq>' . $esc($row->changefreq) . "</changefreq>\n";
            $xml .= '    <priority>' . number_format((float) $row->priority, 1, '.', '') . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>\n";

        return $xml;
    }

    // ------------------------------------------------------------------ statistiques

    public function getStatistics(): array
    {
        $path = self::filePath();
        $bySource = SitemapUrl::selectRaw('source, count(*) as total')->groupBy('source')->pluck('total', 'source');

        return [
            'total_urls' => SitemapUrl::count(),
            'approved_urls' => SitemapUrl::approved()->count(),
            'pending_approval' => SitemapUrl::where('is_approved', false)->count(),
            'active_urls' => SitemapUrl::active()->count(),
            'static_urls' => SitemapUrl::where('type', 'static')->count(),
            'dynamic_urls' => SitemapUrl::where('type', 'dynamic')->count(),
            'manual_urls' => SitemapUrl::where('type', 'manual')->count(),
            'by_source' => collect(self::SOURCES)
                ->map(fn ($label, $key) => ['label' => $label, 'count' => (int) ($bySource[$key] ?? 0)])
                ->filter(fn ($s) => $s['count'] > 0)
                ->all(),
            'sitemap_exists' => file_exists($path),
            'last_generated' => file_exists($path) ? Carbon::createFromTimestamp(filemtime($path)) : null,
        ];
    }
}
