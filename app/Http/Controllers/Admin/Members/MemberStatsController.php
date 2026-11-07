<?php

namespace App\Http\Controllers\Admin\Members;

use App\Models\Membership;
use App\Services\CommuneLocator;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Tableau de bord des adhésions d'une saison : effectifs, finances, dossiers,
 * graphiques (catégorie, statut, situation, sexe, âge) et carte des communes.
 */
class MemberStatsController extends MembersArea
{
    /** Tranches d'âge, dans l'ordre d'affichage */
    public const AGE_BRACKETS = ['Moins de 10 ans', '10 à 14 ans', '15 à 17 ans', '18 à 25 ans', '26 à 59 ans', '60 ans et plus', 'Inconnu'];

    public function __invoke(Request $request, CommuneLocator $locator): View
    {
        $season = preg_match('/^\d{4}-\d{4}$/', (string) $request->query('saison')) ? $request->query('saison') : Membership::currentSeason();
        $all = Membership::with(['member', 'category', 'payments'])->where('season', $season)->get();
        $actives = $all->where('status', '!=', 'abandon');

        $tranche = function ($m) use ($season) {
            $age = $m->member->ageAt($season);

            return match (true) {
                $age === null => 'Inconnu',
                $age < 10 => 'Moins de 10 ans',
                $age < 15 => '10 à 14 ans',
                $age < 18 => '15 à 17 ans',
                $age < 26 => '18 à 25 ans',
                $age < 60 => '26 à 59 ans',
                default => '60 ans et plus',
            };
        };

        return $this->membersView('stats', [
            'season' => $season,
            'total' => $actives->count(),
            'abandons' => $all->count() - $actives->count(),
            'byStatus' => $all->countBy('status'),
            'byCategory' => $actives->groupBy(fn ($m) => $m->category?->code ?? 'Sans catégorie')->map(fn ($g) => ['count' => $g->count(), 'amount' => $g->sum('amount_cents')])->sortKeys(),
            'bySex' => $actives->countBy(fn ($m) => ['F' => 'Féminin', 'M' => 'Masculin'][$m->member->gender] ?? 'Non précisé')
                ->sortKeys(),
            'byAge' => collect(self::AGE_BRACKETS)->mapWithKeys(fn ($t) => [$t => $actives->filter(fn ($m) => $tranche($m) === $t)->count()])
                ->filter(fn ($n, $t) => $n > 0 || $t !== 'Inconnu'),
            'byCity' => $actives->countBy(fn ($m) => mb_strtoupper(trim((string) $m->member->city)) ?: 'Inconnue')->sortDesc()->take(10),
            'bySituation' => $actives->countBy(fn ($m) => Membership::SITUATIONS[$m->situation] ?? 'Non précisée'),
            'map' => $this->communes($actives, $locator),
            'money' => [
                'expected' => $actives->sum('amount_cents'),
                'discounts' => $actives->sum('discount_cents'),
                'pass_sport' => $actives->where('pass_sport', true)->sum('pass_sport_cents'),
                'received' => $actives->sum(fn ($m) => $m->paid_cents),
                'cashed' => $actives->sum(fn ($m) => $m->cashed_cents),
                'due' => $actives->sum(fn ($m) => $m->due_cents),
            ],
            'counts' => [
                'file_incomplete' => $actives->where('file_complete', false)->count(),
                'pass_sport' => $actives->where('pass_sport', true)->count(),
                'mini_coach' => $actives->where('mini_coach', true)->count(),
                'image_refused' => $actives->filter(fn ($m) => $m->member->image_rights === false)->count(),
                'certificate' => $actives->filter(fn ($m) => $m->certificateAlert() !== null)->count(),
                'no_attestation' => $actives->where('payment_certificate', false)->count(),
            ],
        ]);
    }

    /**
     * Communes des adhérents actifs pour la carte : nom, code postal, nombre d'adhérents, position.
     * Aucune adresse ni aucun nom de personne n'est transmis.
     *
     * @return array{points: array<int, array>, missing: array<int, array>}
     */
    private function communes($actives, CommuneLocator $locator): array
    {
        $groupes = $actives->groupBy(fn ($m) => preg_replace('/\D/', '', (string) $m->member->postal_code) . '|' . mb_strtoupper(trim((string) $m->member->city)))
            ->map(fn ($g, $k) => ['postal_code' => explode('|', $k)[0], 'city' => explode('|', $k)[1], 'count' => $g->count()])
            ->sortByDesc('count')->values();

        $points = [];
        $missing = [];
        foreach ($groupes as $g) {
            $pos = ($g['city'] !== '' || $g['postal_code'] !== '') ? $locator->locate($g['postal_code'], $g['city']) : null;
            $libelle = $g['city'] !== '' ? $g['city'] : 'Commune non renseignée';
            if ($pos) {
                $points[] = ['name' => $libelle, 'postal_code' => $g['postal_code'], 'count' => $g['count'], 'lat' => $pos['lat'], 'lng' => $pos['lng']];
            } else {
                $missing[] = ['name' => $libelle, 'postal_code' => $g['postal_code'], 'count' => $g['count']];
            }
        }

        return compact('points', 'missing');
    }
}
