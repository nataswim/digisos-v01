<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Concerns\LatestContent;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace éditeur : accueil dans la mise en page du site public (menu, bandeau boutique, pied de page),
 * statut « éditeur », outils de l'éditeur, brouillons en cours, accès rapides du club
 * et derniers contenus publiés (y compris ceux réservés aux adhérents).
 * Les pages de gestion (articles, fiches, médiathèque…) gardent la mise en page de l'espace éditeur.
 */
class EditorDashboardController extends Controller
{
    use LatestContent;

    public function index(Request $request): View
    {
        $user = $request->user();

        try {
            $drafts = Post::where('created_by', $user->id)->where('status', 'draft')
                ->latest('updated_at')->limit(5)->get();
        } catch (\Throwable $e) {
            report($e);
            $drafts = collect();
        }

        return view('editor.dashboard', ['user' => $user, 'drafts' => $drafts] + $this->latestContent(membres: true));
    }
}
