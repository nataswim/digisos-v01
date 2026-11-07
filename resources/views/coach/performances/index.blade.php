@extends('layouts.coach')

@section('title', 'Performances')

@section('content')
<div class="container-lg">
    <h1 class="h3 fw-bold mb-1">Suivi des performances</h1>
    <p class="text-muted">Temps réalisés en compétition, en meeting extérieur ou en contre-la-montre. Les écarts sont calculés par rapport à la meilleure performance personnelle (MPP) du nageur à la date de la course.</p>

    @include('coach.performances._nav')

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h6 fw-bold">Saisir les temps d'une compétition du calendrier</h2>
                    <form method="GET" action="{{ route('coach.performances.create') }}" class="d-flex flex-wrap gap-2">
                        <label for="competition" class="visually-hidden">Compétition</label>
                        <select id="competition" name="competition" class="form-select" style="flex: 1 1 240px" required>
                            <option value="">Choisir une compétition…</option>
                            @foreach ($competitions as $c)
                                <option value="{{ $c->id }}">{{ $c->starts_on?->format('d/m/Y') }} · {{ $c->title }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary">Saisir</button>
                    </form>
                    <p class="small text-muted mt-2 mb-0">Les nageurs convoqués et leurs épreuves engagées sont proposés automatiquement.</p>
                    <hr>
                    <a href="{{ route('coach.performances.create', ['contexte' => 'meeting']) }}" class="btn btn-sm btn-outline-primary me-1 mb-1"><i class="fas fa-flag-checkered me-1" aria-hidden="true"></i>Meeting extérieur</a>
                    <a href="{{ route('coach.performances.create', ['contexte' => 'training']) }}" class="btn btn-sm btn-outline-primary mb-1"><i class="fas fa-stopwatch me-1" aria-hidden="true"></i>Contre-la-montre à l'entraînement</a>
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('coach.performances.import') }}" class="btn btn-sm btn-outline-secondary mb-1 ms-1"><i class="fas fa-file-import me-1" aria-hidden="true"></i>Importer depuis extraNat</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h6 fw-bold">Bilans des dernières réunions</h2>
                    @forelse ($reunions as $r)
                        <a class="d-block small mb-1" href="{{ route('coach.performances.report', array_filter(['competition' => $r->competition_id, 'date' => $r->competition_id ? null : $r->performed_on?->toDateString(), 'meeting' => $r->competition_id ? null : $r->meet_name])) }}">
                            {{ $r->performed_on?->format('d/m/Y') }} · {{ $r->competition?->title ?? ($r->meet_name ?: \App\Models\SwimPerformance::CONTEXTS[$r->context]) }}
                            <span class="text-muted">({{ $r->total }} temps)</span>
                        </a>
                    @empty
                        <p class="small text-muted mb-0">Aucun temps saisi pour l'instant.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="card border-0 shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label for="nageur" class="form-label small mb-1">Nageur</label>
                <select id="nageur" name="nageur" class="form-select">
                    <option value="">Tous</option>
                    @foreach ($swimmers as $s)<option value="{{ $s->id }}" @selected($swimmerId === $s->id)>{{ $s->full_name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="epreuve" class="form-label small mb-1">Épreuve</label>
                <select id="epreuve" name="epreuve" class="form-select">
                    <option value="">Toutes</option>
                    @foreach (\App\Models\SwimPerformance::EVENTS as $e)<option value="{{ $e }}" @selected($event === $e)>{{ $e }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="bassin" class="form-label small mb-1">Bassin</label>
                <select id="bassin" name="bassin" class="form-select">
                    <option value="">25 et 50 m</option>
                    <option value="25" @selected($pool === 25)>25 m</option>
                    <option value="50" @selected($pool === 50)>50 m</option>
                </select>
            </div>
            <button class="btn btn-outline-primary">Filtrer</button>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        @if ($perfs->isEmpty())
            <div class="card-body text-center text-muted py-5">Aucun temps.</div>
        @else
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <caption class="visually-hidden">Derniers temps saisis</caption>
                    <thead><tr><th scope="col">Date</th><th scope="col">Nageur</th><th scope="col">Épreuve</th><th scope="col">Tour</th><th scope="col">Temps</th><th scope="col">Écart à la MPP</th><th scope="col">Réunion</th></tr></thead>
                    <tbody>
                        @foreach ($perfs as $perf)
                            <tr>
                                <td class="small text-nowrap">{{ $perf->performed_on->format('d/m/Y') }}</td>
                                <td><a href="{{ route('coach.performances.swimmer', $perf->planning_swimmer_id) }}" class="fw-semibold text-decoration-none">{{ $perf->swimmer?->full_name }}</a></td>
                                <td class="text-nowrap">{{ $perf->event }} <span class="small text-muted">{{ $perf->pool }} m</span></td>
                                <td class="small">{{ $perf->round_label }}</td>
                                <td class="perf-time {{ $perf->isValid() ? '' : 'text-danger' }}">{{ $perf->time_label }}</td>
                                <td>@include('performances._gap', ['perf' => $perf, 'info' => $infos[$perf->id] ?? null])</td>
                                <td class="small">{{ $perf->meet_label }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <div class="mt-3">{{ $perfs->links() }}</div>
</div>
@endsection
