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
    /**
     * Tranches d'âge (âge au 1er septembre de la saison), dans l'ordre d'affichage :
     * 8 ans et moins, puis chaque âge de 9 à 24 ans, puis par groupes jusqu'à 71 ans et plus.
     * [libellé, âge minimum, âge maximum]
     */
    public const AGE_BRACKETS = [
        ['8 ans et moins', 0, 8],
        ['9 ans', 9, 9], ['10 ans', 10, 10], ['11 ans', 11, 11], ['12 ans', 12, 12], ['13 ans', 13, 13],
        ['14 ans', 14, 14], ['15 ans', 15, 15], ['16 ans', 16, 16], ['17 ans', 17, 17], ['18 ans', 18, 18],
        ['19 ans', 19, 19], ['20 ans', 20, 20], ['21 ans', 21, 21], ['22 ans', 22, 22], ['23 ans', 23, 23], ['24 ans', 24, 24],
        ['25 à 30 ans', 25, 30], ['31 à 35 ans', 31, 35], ['36 à 40 ans', 36, 40], ['41 à 45 ans', 41, 45],
        ['46 à 50 ans', 46, 50], ['51 à 60 ans', 51, 60], ['61 à 65 ans', 61, 65], ['66 à 70 ans', 66, 70],
        ['71 ans et plus', 71, 999],
    ];

    public static function ageBracket(?int $age): string
    {
        if ($age === null) {
            return 'Âge inconnu';
        }
        foreach (self::AGE_BRACKETS as [$libelle, $min, $max]) {
            if ($age >= $min && $age <= $max) {
                return $libelle;
            }
        }

        return 'Âge inconnu';
    }

    public function __invoke(Request $request, CommuneLocator $locator): View
    {
        $season = preg_match('/^\d{4}-\d{4}$/', (string) $request->query('saison')) ? $request->query('saison') : Membership::currentSeason();
        $all = Membership::with(['member', 'category', 'payments'])->where('season', $season)->get();
        $actives = $all->where('status', '!=', 'abandon');

        $tranche = fn ($m) => self::ageBracket($m->member->ageAt($season));

        return $this->membersView('stats', [
            'season' => $season,
            'total' => $actives->count(),
            'abandons' => $all->count() - $actives->count(),
            'byStatus' => $all->countBy('status'),
            'byCategory' => $actives->groupBy(fn ($m) => $m->category?->code ?? 'Sans catégorie')->map(fn ($g) => ['count' => $g->count(), 'amount' => $g->sum('amount_cents')])->sortKeys(),
            'bySex' => $actives->countBy(fn ($m) => ['F' => 'Féminin', 'M' => 'Masculin'][$m->member->gender] ?? 'Non précisé')
                ->sortKeys(),
            'byAge' => collect(self::AGE_BRACKETS)->pluck(0)->push('Âge inconnu')
                ->mapWithKeys(fn ($t) => [$t => $actives->filter(fn ($m) => $tranche($m) === $t)->count()])
                ->filter(fn ($n, $t) => $n > 0 || $t !== 'Âge inconnu'),
            'extremes' => $this->extremes($actives, $season),
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

    /**
     * Le plus jeune / le plus âgé (garçons et hommes) et la plus jeune / la plus âgée (filles et femmes)
     * parmi les adhérents actifs dont la date de naissance est connue.
     *
     * @return array<int, array{label: string, member: \App\Models\Member|null, age: int|null}>
     */
    private function extremes($actives, string $season): array
    {
        $avecDate = $actives->map->member->filter(fn ($m) => $m && $m->birth_date)->unique('id');
        $sexe = fn ($g) => $avecDate->where('gender', $g);

        return collect([
            ['Le plus jeune', $sexe('M')->sortByDesc('birth_date')->first()],
            ['Le plus âgé', $sexe('M')->sortBy('birth_date')->first()],
            ['La plus jeune', $sexe('F')->sortByDesc('birth_date')->first()],
            ['La plus âgée', $sexe('F')->sortBy('birth_date')->first()],
        ])->map(fn ($e) => ['label' => $e[0], 'member' => $e[1], 'age' => $e[1]?->ageAt($season)])->all();
    }
}
