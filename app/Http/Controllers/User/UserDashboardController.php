<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Concerns\LatestContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\CompetitionAttendance;
use App\Models\PlanningSwimmer;
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

        // Nageurs rattachés au compte (photo, groupes, licence) et leurs convocations à venir.
        $swimmers = PlanningSwimmer::ofUser($user->id)->get();

        $convocations = CompetitionAttendance::with(['competition', 'swimmer', 'entries.event'])
            ->whereIn('planning_swimmer_id', $swimmers->pluck('id'))
            ->whereHas('competition', fn ($q) => $q->where('is_published', true)->whereDate('ends_on', '>=', today()))
            ->get()
            ->sortBy(fn ($a) => $a->competition->starts_on->format('Y-m-d') . '-' . $a->swimmer->first_name)
            ->values();

        return view('user.dashboard', ['user' => $user, 'swimmers' => $swimmers, 'convocations' => $convocations]
            + $this->latestContent(membres: true));
    }
}
