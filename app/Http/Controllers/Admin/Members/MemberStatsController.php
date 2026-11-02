<?php

namespace App\Http\Controllers\Admin\Members;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Tableau de bord des adhésions d'une saison : effectifs, finances, dossiers. */
class MemberStatsController extends MembersArea
{
    public function __invoke(Request $request): View
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
            'bySex' => $actives->countBy(fn ($m) => $m->member->gender ?: '?'),
            'byAge' => $actives->countBy($tranche)->sortKeys(),
            'byCity' => $actives->countBy(fn ($m) => mb_strtoupper(trim((string) $m->member->city)) ?: 'Inconnue')->sortDesc()->take(10),
            'bySituation' => $actives->countBy(fn ($m) => Membership::SITUATIONS[$m->situation] ?? 'Non précisée'),
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
}
