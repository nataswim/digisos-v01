{{--
    Fiche performances d'un nageur (coach et espace adhérent).
    Variables : $swimmer, $perfs, $infos, $bests, $chart, $chartOptions, $chartEvent, $chartPool,
                $chartRoute (nom de route + paramètres pour changer d'épreuve), $coach (bool)
--}}
@php use App\Models\SwimRecord; use App\Services\PerformanceTracker as PT; @endphp

{{-- Meilleures performances personnelles --}}
<div class="row g-4 mb-4">
    @foreach ([25, 50] as $pool)
        <section class="col-lg-6" aria-labelledby="mpp-{{ $pool }}">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white"><h2 id="mpp-{{ $pool }}" class="h6 fw-bold mb-0"><i class="fas fa-medal me-2 text-warning" aria-hidden="true"></i>Meilleures performances · bassin de {{ $pool }} m</h2></div>
                @if (empty($bests[$pool]))
                    <div class="card-body text-muted small">Aucun temps en bassin de {{ $pool }} m.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <caption class="visually-hidden">Meilleures performances personnelles en bassin de {{ $pool }} m</caption>
                            <thead><tr><th scope="col">Épreuve</th><th scope="col">MPP</th><th scope="col">Date</th><th scope="col">Réunion</th></tr></thead>
                            <tbody>
                                @foreach ($bests[$pool] as $event => $best)
                                    <tr>
                                        <th scope="row">
                                            <a href="{{ route($chartRoute[0], $chartRoute[1] + ['epreuve' => $event, 'bassin' => $pool]) }}#progression" class="text-decoration-none">{{ $event }}</a>
                                        </th>
                                        <td class="perf-time">{{ $best->time_label }}</td>
                                        <td class="small text-nowrap">{{ $best->performed_on->format('d/m/Y') }}</td>
                                        <td class="small">{{ $best->meet_label }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    @endforeach
</div>

{{-- Progression --}}
<section class="card border-0 shadow-sm mb-4" id="progression" aria-labelledby="titre-progression">
    <div class="card-header bg-white perf-head">
        <h2 id="titre-progression" class="h6 fw-bold mb-0"><i class="fas fa-chart-line me-2 text-primary" aria-hidden="true"></i>Progression</h2>
        @if ($chartOptions->isNotEmpty())
            <nav class="perf-chips" aria-label="Choisir l'épreuve">
                @foreach ($chartOptions as [$p, $e])
                    <a href="{{ route($chartRoute[0], $chartRoute[1] + ['epreuve' => $e, 'bassin' => $p]) }}#progression"
                       class="perf-chip {{ $e === $chartEvent && (int) $p === $chartPool ? 'is-active' : '' }}">{{ $e }} · {{ $p }} m</a>
                @endforeach
            </nav>
        @endif
    </div>
    <div class="card-body">
        @if ($chart)
            @php $pts = $chart['points']; @endphp
            <figure class="mb-0">
                <svg viewBox="0 0 {{ $chart['width'] }} {{ $chart['height'] }}" class="perf-chart" role="img"
                     aria-label="Progression sur {{ $chartEvent }} en bassin de {{ $chartPool }} m : de {{ $pts[0]['time'] }} le {{ $pts[0]['date'] }} à {{ end($pts)['time'] }} le {{ end($pts)['date'] }}">
                    <line x1="30" y1="{{ $chart['height'] - 18 }}" x2="{{ $chart['width'] - 20 }}" y2="{{ $chart['height'] - 18 }}" class="perf-axis" />
                    <text x="4" y="22" class="perf-axis-label">{{ SwimRecord::formatTime($chart['min']) }}</text>
                    <text x="4" y="{{ $chart['height'] - 24 }}" class="perf-axis-label">{{ SwimRecord::formatTime($chart['max']) }}</text>
                    <polyline points="{{ collect($pts)->map(fn ($p) => $p['x'] . ',' . $p['y'])->join(' ') }}" class="perf-line" />
                    @foreach ($pts as $p)
                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="{{ $p['pb'] ? 6 : 4 }}" class="{{ $p['pb'] ? 'perf-dot perf-dot--pb' : 'perf-dot' }}">
                            <title>{{ $p['date'] }} · {{ $p['time'] }} · {{ $p['label'] }}{{ $p['pb'] ? ' · record perso' : '' }}</title>
                        </circle>
                    @endforeach
                    <text x="{{ $pts[0]['x'] }}" y="{{ $chart['height'] - 4 }}" class="perf-axis-label" text-anchor="start">{{ $pts[0]['date'] }}</text>
                    <text x="{{ end($pts)['x'] }}" y="{{ $chart['height'] - 4 }}" class="perf-axis-label" text-anchor="end">{{ end($pts)['date'] }}</text>
                </svg>
                <figcaption class="small text-muted mt-2">
                    {{ $chartEvent }}, bassin de {{ $chartPool }} m. Plus le point est haut, plus le temps est rapide ; les gros points dorés sont les records personnels.
                </figcaption>
            </figure>
        @elseif ($chartEvent)
            <p class="text-muted mb-0">Il faut au moins deux temps sur {{ $chartEvent }} ({{ $chartPool }} m) pour tracer la progression.</p>
        @else
            <p class="text-muted mb-0">Pas encore de temps enregistré.</p>
        @endif
    </div>
</section>

{{-- Historique --}}
<section class="card border-0 shadow-sm" aria-labelledby="titre-historique">
    <div class="card-header bg-white"><h2 id="titre-historique" class="h6 fw-bold mb-0"><i class="fas fa-history me-2 text-primary" aria-hidden="true"></i>Historique ({{ $perfs->count() }})</h2></div>
    @if ($perfs->isEmpty())
        <div class="card-body text-muted small">Aucun temps enregistré.</div>
    @else
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <caption class="visually-hidden">Historique des temps</caption>
                <thead>
                    <tr><th scope="col">Date</th><th scope="col">Réunion</th><th scope="col">Épreuve</th><th scope="col">Tour</th><th scope="col">Temps</th><th scope="col">Écart à la MPP</th>@if ($coach)<th scope="col"><span class="visually-hidden">Actions</span></th>@endif</tr>
                </thead>
                <tbody>
                    @foreach ($perfs as $perf)
                        <tr>
                            <td class="small text-nowrap">{{ $perf->performed_on->format('d/m/Y') }}</td>
                            <td class="small">{{ $perf->meet_label }}@if ($perf->location)<span class="d-block text-muted">{{ $perf->location }}</span>@endif</td>
                            <td class="text-nowrap fw-semibold">{{ $perf->event }} <span class="text-muted small">{{ $perf->pool }} m</span></td>
                            <td class="small">{{ $perf->round_label }}</td>
                            <td class="perf-time {{ $perf->isValid() ? '' : 'text-danger' }}">{{ $perf->time_label }}</td>
                            <td>@include('performances._gap', ['perf' => $perf, 'info' => $infos[$perf->id] ?? null])</td>
                            @if ($coach)
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('coach.performances.edit', $perf) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span></a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>

@once
@push('styles')
<style>
    .perf-head { display: flex; flex-wrap: wrap; justify-content: space-between; gap: .5rem; }
    .perf-chips { display: flex; flex-wrap: wrap; gap: .3rem; }
    .perf-chip { padding: .15rem .6rem; border-radius: 999px; border: 1px solid #e2e8f0; font-size: .8rem; font-weight: 600; color: #314158; text-decoration: none; background: #fff; }
    .perf-chip:hover, .perf-chip:focus { border-color: #0090D4; color: #0090D4; }
    .perf-chip.is-active { background: #0090D4; border-color: #0090D4; color: #fff; }
    .perf-chart { width: 100%; height: auto; max-height: 280px; }
    .perf-axis { stroke: #cad5e2; stroke-width: 1; }
    .perf-axis-label { font-size: 11px; fill: #62748e; font-family: var(--bs-font-monospace); }
    .perf-line { fill: none; stroke: #0090D4; stroke-width: 2.5; stroke-linejoin: round; }
    .perf-dot { fill: #fff; stroke: #0090D4; stroke-width: 2.5; }
    .perf-dot--pb { fill: #D49F45; stroke: #fff; }
</style>
@endpush
@endonce
