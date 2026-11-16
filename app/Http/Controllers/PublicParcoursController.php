<?php

namespace App\Http\Controllers;

use App\Models\PlanningGroup;
use App\Support\ParcoursNageur;
use Illuminate\Support\Str;

/**
 * Page publique interactive « Le parcours du nageur ».
 *
 * Le contenu vient de App\Support\ParcoursNageur ; les créneaux de la période scolaire
 * viennent du module Planning, en retrouvant chaque groupe par le début de son slug
 * (ex. « initiation-bleu » retrouve « Initiation Bleu (Primaire (6–10 ans)) »).
 */
class PublicParcoursController extends Controller
{
    public function __invoke()
    {
        $planning = PlanningGroup::active()
            ->with(['slots' => fn ($q) => $q->active()->period('scolaire')->chronological()])
            ->get();

        $groupes = array_map(function (array $g) use ($planning) {
            $trouve = $planning->first(fn ($p) => $p->slug === $g['cle'] || Str::startsWith($p->slug, $g['cle'].'-'));

            $g['creneaux'] = $trouve
                ? $trouve->slots->map(fn ($s) => $s->day_name.' '.$s->time_range)->unique()->values()->all()
                : [];
            $g['ancre'] = route('schedule').'#groupe-'.($trouve->slug ?? $g['cle']);
            $g['lien'] = $g['page'] ? url('/pages/nos-groupes/'.$g['page']) : $g['ancre'];
            $g['couleur'] = ParcoursNageur::COULEURS[$g['section']] ?? '#2f80b8';

            return $g;
        }, ParcoursNageur::GROUPES);

        return view('public.parcours', [
            'etapes' => ParcoursNageur::ETAPES,
            'themes' => ParcoursNageur::THEMES,
            'groupes' => $groupes,
            'parents' => ParcoursNageur::PARENTS,
            'sourceFfn' => ParcoursNageur::SOURCE_FFN,
        ]);
    }
}
