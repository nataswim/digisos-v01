@extends('layouts.coach')

@section('title', 'Bilan — ' . $title)

@section('content')
@php use App\Services\PerformanceTracker as PT; @endphp
<div class="container-lg">
    <a href="{{ route('coach.performances.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Performances</a>
    <h1 class="h3 fw-bold mb-1">Bilan : {{ $title }}</h1>
    @if ($competition)<p class="text-muted">{{ $competition->starts_on?->translatedFormat('j F Y') }} · {{ $competition->city }}</p>@endif

    <div class="row g-3 my-3">
        @foreach ([
            ['Nageurs', $stats['swimmers'], 'primary'],
            ['Courses', $stats['swims'], 'primary'],
            ['Records perso', $stats['pbs'], 'warning'],
            ['Progression moyenne', $stats['avg_pct'] !== null ? PT::pctLabel($stats['avg_pct']) : '—', $stats['avg_pct'] !== null && $stats['avg_pct'] < 0 ? 'success' : 'secondary'],
        ] as [$label, $valeur, $couleur])
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 text-center"><div class="card-body">
                    <div class="h3 fw-bold mb-0 text-{{ $couleur }}">{{ $valeur }}</div>
                    <div class="small text-muted">{{ $label }}</div>
                </div></div>
            </div>
        @endforeach
    </div>
    <p class="small text-muted">
        Progression moyenne : moyenne des écarts (en %) entre chaque temps et la MPP du nageur avant la course ; négatif = plus rapide.
        @if ($stats['firsts']) {{ $stats['firsts'] }} premier(s) temps sur une épreuve (sans comparaison). @endif
        @if ($stats['invalid']) {{ $stats['invalid'] }} disqualification(s), abandon(s) ou forfait(s). @endif
    </p>

    @forelse ($perfs as $swimmerId => $liste)
        @php $swimmer = $liste->first()->swimmer; @endphp
        <section class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between">
                <h2 class="h6 fw-bold mb-0">{{ $swimmer?->full_name }}</h2>
                <a href="{{ route('coach.performances.swimmer', $swimmerId) }}" class="small">Fiche du nageur</a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($liste as $perf)
                    <li class="list-group-item d-flex flex-wrap gap-2 align-items-center">
                        <span class="fw-semibold" style="min-width: 110px">{{ $perf->event }} <span class="small text-muted">{{ $perf->pool }} m</span></span>
                        <span class="small text-muted" style="min-width: 90px">{{ $perf->round_label }}</span>
                        <span class="perf-time {{ $perf->isValid() ? '' : 'text-danger' }}" style="min-width: 80px">{{ $perf->time_label }}</span>
                        @include('performances._gap', ['perf' => $perf, 'info' => $infos[$perf->id] ?? null])
                        <a href="{{ route('coach.performances.edit', $perf) }}" class="ms-auto small">Modifier</a>
                    </li>
                @endforeach
            </ul>
        </section>
    @empty
        <div class="card border-0 shadow-sm"><div class="card-body text-muted text-center py-5">Aucun temps pour cette réunion.</div></div>
    @endforelse
</div>
@endsection
