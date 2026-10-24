<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Fiche;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Collection;

/**
 * Derniers contenus publiés pour les pages d'accueil des espaces (visiteur, adhérent).
 * $membres = true ajoute les contenus réservés aux adhérents (visibilité « authenticated »).
 */
trait LatestContent
{
    /** @return array{posts: Collection, fiches: Collection, pages: Collection} */
    protected function latestContent(bool $membres, int $limit = 4): array
    {
        $visibilites = $membres ? ['public', 'authenticated'] : ['public'];

        return [
            'posts' => $this->latestOf(fn () => Post::with('category')->published()->whereIn('visibility', $visibilites), $limit),
            'fiches' => $this->latestOf(fn () => Fiche::with(['category', 'sousCategory'])->published()->whereIn('visibility', $visibilites), $limit),
            'pages' => $this->latestOf(fn () => Page::with('category')->published()->whereIn('visibility', $visibilites), $limit),
        ];
    }

    /** Liste vide si le module n'est pas installé. */
    private function latestOf(callable $query, int $limit): Collection
    {
        try {
            return $query()->latest('published_at')->limit($limit)->get();
        } catch (\Throwable $e) {
            report($e);

            return collect();
        }
    }
}
