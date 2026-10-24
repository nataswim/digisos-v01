<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Fiche;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Espace visiteur : mise en page du site public (menu, bandeau boutique, pied de page),
 * mention du statut « visiteur », accès rapides et derniers contenus publics.
 * Seuls les contenus publics sont listés : les contenus réservés aux adhérents
 * deviennent visibles une fois le compte validé par un administrateur.
 */
class VisitorDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('visitor.dashboard', [
            'user' => $request->user(),
            'posts' => $this->latest(fn () => Post::with('category')->published()->where('visibility', 'public')),
            'fiches' => $this->latest(fn () => Fiche::with(['category', 'sousCategory'])->published()->where('visibility', 'public')),
            'pages' => $this->latest(fn () => Page::with('category')->published()->where('visibility', 'public')),
        ]);
    }

    /** Les 4 derniers éléments publiés ; liste vide si le module n'est pas installé. */
    private function latest(callable $query): Collection
    {
        try {
            return $query()->latest('published_at')->limit(4)->get();
        } catch (\Throwable $e) {
            report($e);

            return collect();
        }
    }
}
