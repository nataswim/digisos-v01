<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Concerns\LatestContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\CompetitionAttendance;
use App\Models\Member;
use App\Models\Membership;
use App\Models\PlanningSwimmer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace adhérent : en haut, une carte par nageur rattaché au compte (photo, groupe et créneaux
 * d'entraînement, licence, fiche adhérent de la saison : adhésion, dossier, règlements, coordonnées),
 * puis les convocations, les accès rapides et les derniers contenus publiés.
 * Fiche adhérent : celle liée au nageur, ou celle rattachée au compte avec le même prénom.
 * Une fiche adhérent rattachée au compte sans nageur (adulte, bénévole…) a sa propre carte.
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
        $swimmers->load(['groups.slots' => fn ($q) => $q->active()->chronological()]);

        // Fiches adhérent du compte (adhésion de la saison en cours)
        $season = Membership::currentSeason();
        $members = Member::with(['memberships' => fn ($q) => $q->where('season', $season)->with(['category', 'payments'])])
            ->where(fn ($q) => $q->where('user_id', $user->id)->orWhereIn('planning_swimmer_id', $swimmers->pluck('id')))
            ->get();
        $prenom = fn ($v) => Str::slug((string) $v);
        $memberFor = [];
        foreach ($swimmers as $n) {
            $m = $members->firstWhere('planning_swimmer_id', $n->id)
                ?? $members->first(fn ($m) => ! $m->planning_swimmer_id && $m->user_id === $user->id && $prenom($m->first_name) === $prenom($n->first_name));
            if ($m) {
                $memberFor[$n->id] = $m;
            }
        }
        $otherMembers = $members->reject(fn ($m) => in_array($m->id, collect($memberFor)->pluck('id')->all(), true))->values();

        $convocations = CompetitionAttendance::with(['competition', 'swimmer', 'entries.event'])
            ->whereIn('planning_swimmer_id', $swimmers->pluck('id'))
            ->whereHas('competition', fn ($q) => $q->where('is_published', true)->whereDate('ends_on', '>=', today()))
            ->get()
            ->sortBy(fn ($a) => $a->competition->starts_on->format('Y-m-d') . '-' . $a->swimmer->first_name)
            ->values();

        return view('user.dashboard', ['user' => $user, 'swimmers' => $swimmers, 'convocations' => $convocations,
            'season' => $season, 'memberFor' => $memberFor, 'otherMembers' => $otherMembers]
            + $this->latestContent(membres: true));
    }
}
