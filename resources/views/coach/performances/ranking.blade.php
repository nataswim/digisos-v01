@extends('layouts.coach')

@section('title', 'Classement du club')

@section('content')
<div class="container-lg">
    <h1 class="h3 fw-bold mb-1">Classement du club</h1>
    <p class="text-muted">Meilleur temps de chaque nageur sur l'épreuve choisie.</p>
    @include('coach.performances._nav')

    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label for="epreuve" class="form-label small mb-1">Épreuve</label>
                <select id="epreuve" name="epreuve" class="form-select">
                    @foreach (\App\Models\SwimPerformance::EVENTS as $e)<option value="{{ $e }}" @selected($event === $e)>{{ $e }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="bassin" class="form-label small mb-1">Bassin</label>
                <select id="bassin" name="bassin" class="form-select">
                    <option value="25" @selected($pool === 25)>25 m</option>
                    <option value="50" @selected($pool === 50)>50 m</option>
                </select>
            </div>
            <div>
                <label for="sexe" class="form-label small mb-1">Sexe</label>
                <select id="sexe" name="sexe" class="form-select">
                    <option value="">Tous</option>
                    <option value="F" @selected($sex === 'F')>Filles / femmes</option>
                    <option value="M" @selected($sex === 'M')>Garçons / hommes</option>
                </select>
            </div>
            <div>
                <label for="groupe" class="form-label small mb-1">Groupe</label>
                <select id="groupe" name="groupe" class="form-select">
                    <option value="">Tous</option>
                    @foreach ($groups as $g)<option value="{{ $g->id }}" @selected($groupId === $g->id)>{{ $g->name }}</option>@endforeach
                </select>
            </div>
            <button class="btn btn-outline-primary">Afficher</button>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        @if ($rows->isEmpty())
            <div class="card-body text-center text-muted py-5">Aucun temps sur {{ $event }} en bassin de {{ $pool }} m.</div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <caption class="visually-hidden">Classement du club sur {{ $event }}, bassin de {{ $pool }} m</caption>
                    <thead><tr><th scope="col">#</th><th scope="col">Nageur</th><th scope="col">Année</th><th scope="col">Temps</th><th scope="col">Écart au 1er</th><th scope="col">Date</th><th scope="col">Réunion</th></tr></thead>
                    <tbody>
                        @foreach ($rows as $perf)
                            <tr>
                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                <td><a href="{{ route('coach.performances.swimmer', $perf->planning_swimmer_id) }}" class="fw-semibold text-decoration-none">{{ $perf->swimmer?->full_name }}</a></td>
                                <td class="small">{{ $perf->swimmer?->birth_year }}</td>
                                <td class="perf-time">{{ $perf->time_label }}</td>
                                <td class="small">{{ $loop->first ? '—' : \App\Models\SwimRecord::formatGap($perf->time_cs - $rows->first()->time_cs) }}</td>
                                <td class="small text-nowrap">{{ $perf->performed_on->format('d/m/Y') }}</td>
                                <td class="small">{{ $perf->meet_label }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>.perf-time { font-family: var(--bs-font-monospace); font-weight: 700; }</style>
@endpush
