<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Concerns\LatestContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace visiteur : mise en page du site public (menu, bandeau boutique, pied de page),
 * mention du statut « visiteur », accès rapides et derniers contenus publics.
 * Seuls les contenus publics sont listés : les contenus réservés aux adhérents
 * deviennent visibles une fois le compte validé par un administrateur.
 */
class VisitorDashboardController extends Controller
{
    use LatestContent;

    public function __invoke(Request $request): View
    {
        return view('visitor.dashboard', ['user' => $request->user()] + $this->latestContent(membres: false));
    }
}
