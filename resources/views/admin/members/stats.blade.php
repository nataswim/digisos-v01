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
        </div>
    </div>

    {{-- Graphiques de répartition (chaque graphique a ses chiffres juste en dessous) --}}
    @php
        $statuts = $byStatus->mapWithKeys(fn ($n, $k) => [M::STATUSES[$k][0] ?? $k => $n]);
        $graphiques = [
            ['cat', 'Par catégorie', $byCategory->map(fn ($c) => $c['count']), 'adhérents actifs'],
            ['statut', 'Par statut', $statuts, 'adhésions de la saison, abandons compris'],
            ['situation', 'Par situation', $bySituation, 'adhérents actifs'],
            ['sexe', 'Par sexe', $bySex, 'adhérents actifs'],
            ['age', 'Par âge', $byAge, 'âge au 1er septembre ' . substr($season, 0, 4)],
        ];
    @endphp

    {{-- Le plus jeune / le plus âgé, la plus jeune / la plus âgée --}}
    <h2 class="h5 fw-bold mt-2 mb-3">Âges extrêmes</h2>
    <div class="row g-3 mb-4">
        @foreach ($extremes as $e)
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100"><div class="card-body">
                    <div class="small text-muted">{{ $e['label'] }}</div>
                    @if ($e['member'])
                        <a href="{{ $r('show', $e['member']) }}" class="fw-bold d-block text-decoration-none">{{ $e['member']->full_name }}</a>
                        <div class="h5 fw-bold mb-0">{{ $e['age'] }} ans</div>
                        <div class="small text-muted">né{{ str_contains($e['label'], 'La ') ? 'e' : '' }} le {{ $e['member']->birth_date->format('d/m/Y') }}</div>
                    @else
                        <div class="text-muted">—</div>
                    @endif
                </div></div>
            </div>
        @endforeach
    </div>
    <h2 class="h5 fw-bold mt-2 mb-3">Répartition des adhérents</h2>
    <div class="row g-4 mb-4">
        @foreach ($graphiques as [$id, $titre, $donnees, $note])
            <div class="{{ $id === 'age' ? 'col-12' : 'col-lg-6' }}">
                <section class="card border-0 shadow-sm h-100" aria-labelledby="titre-{{ $id }}">
                    <div class="card-header bg-white d-flex justify-content-between align-items-baseline gap-2">
                        <h3 id="titre-{{ $id }}" class="h6 fw-bold mb-0">{{ $titre }}</h3>
                        <span class="small text-muted">{{ $note }}</span>
                    </div>
                    <div class="card-body">
                        @if ($donnees->sum() > 0)
                            <div class="mbr-chart" style="height: {{ $id === 'age' ? 340 : 40 + $donnees->count() * 34 }}px">
                                <canvas id="graph-{{ $id }}" role="img"
                                        aria-label="{{ $titre }} : {{ $donnees->map(fn ($n, $k) => $k . ' ' . $n)->implode(', ') }}"></canvas>
                            </div>
                        @else
                            <p class="text-muted mb-0">Aucune donnée pour cette saison.</p>
                        @endif
                        @if ($id === 'age')
                            <a href="{{ $r('lists.year', ['saison' => $season]) }}" class="small d-inline-block mt-2"><i class="fas fa-list me-1" aria-hidden="true"></i>Liste des adhérents par année de naissance</a>
                        @endif
                        <details class="mt-2">
                            <summary class="small text-muted">Voir les chiffres</summary>
                            <table class="table table-sm small mb-0 mt-2">
                                <caption class="visually-hidden">{{ $titre }}</caption>
                                <thead><tr><th scope="col">{{ ltrim(str_replace('Par ', '', $titre)) }}</th><th scope="col" class="text-end">Nombre</th>@if ($id === 'cat')<th scope="col" class="text-end">Montant</th>@endif</tr></thead>
                                <tbody>
                                    @foreach ($donnees as $k => $n)
                                        <tr><th scope="row" class="fw-normal">{{ $k }}</th><td class="text-end">{{ $n }}</td>@if ($id === 'cat')<td class="text-end">{{ M::money($byCategory[$k]['amount']) }}</td>@endif</tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </details>
                    </div>
                </section>
            </div>
        @endforeach
    </div>

    {{-- Carte des communes --}}
    <section class="card border-0 shadow-sm mb-4" aria-labelledby="titre-carte">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-baseline gap-2">
            <h2 id="titre-carte" class="h6 fw-bold mb-0"><i class="fas fa-map-location-dot text-primary me-2" aria-hidden="true"></i>Communes des adhérents</h2>
            <span class="small text-muted">{{ count($map['points']) + count($map['missing']) }} commune(s) · cercle proportionnel au nombre d'adhérents · <a href="{{ $r('lists.city', ['saison' => $season]) }}">liste des adhérents par commune</a></span>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-8">
                    @if ($map['points'])
                        <div id="carte-communes" class="mbr-map" role="region" aria-label="Carte des communes des adhérents (la liste à côté donne les mêmes informations)"></div>
                        <p class="small text-muted mt-2 mb-0">Fond de carte © contributeurs OpenStreetMap. Positions : geo.api.gouv.fr (seuls le code postal et le nom de la commune sont envoyés).</p>
                    @else
                        <p class="text-muted mb-0">Carte indisponible : aucune commune n'a pu être localisée (communes non renseignées, ou service de localisation injoignable pour le moment).</p>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="mbr-cities">
                        <table class="table table-sm small mb-0">
                            <caption class="visually-hidden">Adhérents par commune</caption>
                            <thead class="table-light sticky-top"><tr><th scope="col">Commune</th><th scope="col" class="text-end">Adhérents</th></tr></thead>
                            <tbody>
                                @foreach (collect($map['points'])->concat($map['missing'])->sortByDesc('count') as $c)
                                    <tr>
                                        <th scope="row" class="fw-normal">{{ $c['name'] }} <span class="text-muted">{{ $c['postal_code'] }}</span>@if (! isset($c['lat']))<span class="badge bg-warning-subtle text-warning-emphasis ms-1" title="Commune non localisée sur la carte">non placée</span>@endif</th>
                                        <td class="text-end">{{ $c['count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
    .mbr-chart { position: relative; width: 100%; }
    .mbr-map { height: 460px; border-radius: 12px; overflow: hidden; border: 1px solid #e6eaf1; z-index: 0; }
    .mbr-cities { max-height: 460px; overflow-y: auto; border: 1px solid #e6eaf1; border-radius: 12px; }
    .mbr-cities thead th { background: #f8fafc; }
    .leaflet-tooltip.mbr-tip { font-family: inherit; font-size: .85rem; border-radius: 8px; border: 1px solid #e6eaf1; box-shadow: 0 6px 16px rgba(20, 33, 92, .12); }
    @media (max-width: 575.98px) { .mbr-map { height: 340px; } }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
(() => {
    const BLEU = '#2f80d1', TEXTE = '#1f2a44', DISCRET = '#6b7a90', GRILLE = '#e6eaf1';
    const donnees = @json(collect($graphiques)->mapWithKeys(fn ($g) => [$g[0] => ['labels' => $g[2]->keys()->values(), 'values' => $g[2]->values()]]));

    // Valeur écrite au bout de chaque barre (une seule série : pas de légende, le titre la nomme)
    const valeurs = {
        id: 'valeurs',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            ctx.save();
            ctx.font = '600 12px Manrope, system-ui, sans-serif';
            ctx.fillStyle = TEXTE;
            const vertical = chart.options.indexAxis === 'x';
            ctx.textBaseline = vertical ? 'bottom' : 'middle';
            ctx.textAlign = vertical ? 'center' : 'left';
            chart.getDatasetMeta(0).data.forEach((barre, i) => {
                const v = chart.data.datasets[0].data[i];
                if (v) vertical ? ctx.fillText(v, barre.x, barre.y - 4) : ctx.fillText(v, barre.x + 6, barre.y);
            });
            ctx.restore();
        },
    };

    if (window.Chart) {
        Object.entries(donnees).forEach(([id, d]) => {
            const canvas = document.getElementById('graph-' + id);
            if (!canvas) return;
            const max = Math.max(...d.values, 1);
            const vertical = id === 'age';   // âge : colonnes (axe des âges horizontal)
            new Chart(canvas, {
                type: 'bar',
                data: { labels: d.labels, datasets: [{ data: d.values, backgroundColor: BLEU, hoverBackgroundColor: '#236bb5', borderRadius: 4, borderSkipped: 'start', maxBarThickness: 22 }] },
                options: {
                    indexAxis: vertical ? 'x' : 'y',
                    maintainAspectRatio: false,
                    animation: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? false : { duration: 400 },
                    layout: { padding: vertical ? { top: 20 } : { right: 28 } },
                    scales: vertical ? {
                        x: { ticks: { color: TEXTE, font: { size: 11 }, autoSkip: window.innerWidth < 576, maxRotation: 60, minRotation: 0 }, grid: { display: false }, border: { color: GRILLE } },
                        y: { beginAtZero: true, suggestedMax: max * 1.15, ticks: { precision: 0, color: DISCRET, font: { size: 11 } }, grid: { color: GRILLE }, border: { display: false } },
                    } : {
                        x: { beginAtZero: true, suggestedMax: max * 1.1, ticks: { precision: 0, color: DISCRET, font: { size: 11 } }, grid: { color: GRILLE }, border: { display: false } },
                        y: { ticks: { color: TEXTE, font: { size: 12 } }, grid: { display: false }, border: { color: GRILLE } },
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#fff', titleColor: TEXTE, bodyColor: TEXTE, borderColor: GRILLE, borderWidth: 1, padding: 10, displayColors: false,
                                   callbacks: { label: c => { const n = vertical ? c.parsed.y : c.parsed.x; return n + ' adhérent' + (n > 1 ? 's' : ''); } } },
                    },
                },
                plugins: [valeurs],
            });
        });
    }

    // Carte : un cercle par commune, surface proportionnelle au nombre d'adhérents
    const points = @json($map['points']);
    const el = document.getElementById('carte-communes');
    if (el && window.L && points.length) {
        const carte = L.map(el, { scrollWheelZoom: false });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(carte);
        const max = Math.max(...points.map(p => p.count));
        const cercles = points.map(p => L.circleMarker([p.lat, p.lng], {
            radius: 6 + 22 * Math.sqrt(p.count / max),
            color: '#ffffff', weight: 2, fillColor: BLEU, fillOpacity: 0.6,
        }).bindTooltip(`<strong>${p.name}</strong> ${p.postal_code}<br>${p.count} adhérent${p.count > 1 ? 's' : ''}`, { className: 'mbr-tip', direction: 'top' }).addTo(carte));
        const groupe = L.featureGroup(cercles);
        carte.fitBounds(groupe.getBounds().pad(0.25), { maxZoom: 12 });
    }
})();
</script>
@endpush
