@extends($layout)

@section('title', 'Adhérents — tableau de bord ' . $season)

@section('content')
@php use App\Models\Membership as M; $r = fn ($n, $p = []) => route($area . '.members.' . $n, $p); @endphp
<div class="container-lg">
    <h1 class="h3 fw-bold mb-3">Tableau de bord — saison {{ $season }}</h1>
    @include('admin.members._nav')

    <div class="row g-3 mb-4">
        @foreach ([
            ['Adhérents', $total, 'primary', 'fa-users'],
            ['Montant attendu', M::money($money['expected']), 'primary', 'fa-euro-sign'],
            ['Reçu (dont à encaisser)', M::money($money['received']), 'success', 'fa-hand-holding-usd'],
            ['Reste à payer', M::money($money['due']), $money['due'] ? 'danger' : 'success', 'fa-hourglass-half'],
        ] as [$label, $valeur, $couleur, $icon])
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100"><div class="card-body">
                    <div class="small text-muted"><i class="fas {{ $icon }} me-1" aria-hidden="true"></i>{{ $label }}</div>
                    <div class="h4 fw-bold mb-0 text-{{ $couleur }}">{{ $valeur }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Par catégorie</h2></div>
                <table class="table table-sm mb-0">
                    <caption class="visually-hidden">Adhérents par catégorie</caption>
                    <thead><tr><th scope="col">Catégorie</th><th scope="col" class="text-end">Adhérents</th><th scope="col" class="text-end">Montant</th></tr></thead>
                    <tbody>@foreach ($byCategory as $code => $c)<tr><th scope="row">{{ $code }}</th><td class="text-end">{{ $c['count'] }}</td><td class="text-end">{{ M::money($c['amount']) }}</td></tr>@endforeach</tbody>
                </table>
            </section>
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Finances</h2></div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between"><span>Remises accordées</span><strong>{{ M::money($money['discounts']) }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Pass'Sport ({{ $counts['pass_sport'] }})</span><strong>{{ M::money($money['pass_sport']) }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Règlements reçus</span><strong>{{ M::money($money['received']) }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>dont déjà encaissés</span><strong>{{ M::money($money['cashed']) }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>dont à encaisser (chèques…)</span><strong>{{ M::money($money['received'] - $money['cashed']) }}</strong></li>
                </ul>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">À surveiller</h2></div>
                <ul class="list-group list-group-flush small">
                    @foreach ([
                        ['dossier', 'Dossiers incomplets', $counts['file_incomplete']],
                        ['certificat', 'Certificats à renouveler (adultes)', $counts['certificate']],
                        ['attestation', 'Attestations non remises', $counts['no_attestation']],
                        ['image', "Droit à l'image refusé", $counts['image_refused']],
                        ['minicoach', 'Mini-coachs', $counts['mini_coach']],
                    ] as [$filtre, $label, $n])
                        <li class="list-group-item d-flex justify-content-between"><a href="{{ $r('index', ['saison' => $season, 'filtre' => $filtre]) }}">{{ $label }}</a><strong>{{ $n }}</strong></li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between"><span>Abandons</span><strong>{{ $abandons }}</strong></li>
                </ul>
            </section>
            <div class="row g-4">
                @foreach ([['Par statut', $byStatus->mapWithKeys(fn ($n, $k) => [M::STATUSES[$k][0] ?? $k => $n])], ['Par situation', $bySituation], ['Par sexe', $bySex], ['Par âge', $byAge], ['Communes (10 premières)', $byCity]] as [$titre, $liste])
                    <div class="col-md-6">
                        <section class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">{{ $titre }}</h2></div>
                            <ul class="list-group list-group-flush small">
                                @forelse ($liste as $k => $n)<li class="list-group-item d-flex justify-content-between"><span>{{ $k }}</span><strong>{{ $n }}</strong></li>@empty<li class="list-group-item text-muted">—</li>@endforelse
                            </ul>
                        </section>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
