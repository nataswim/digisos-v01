<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Concerns\LatestContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace adhérent : mise en page du site public (menu, bandeau boutique, pied de page),
 * accès à « mon espace » (fiche, compétitions, profil, documents), accès rapides du club
 * et derniers contenus publiés, y compris ceux réservés aux adhérents.
 * Un compte encore « visiteur » est renvoyé vers l'espace visiteur.
 */
class UserDashboardController extends Controller
{
    use LatestContent;

    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasRole('visitor')) {
            return redirect()->route('visitor.dashboard');
        }

        return view('user.dashboard', ['user' => $user] + $this->latestContent(membres: true));
    }
}
