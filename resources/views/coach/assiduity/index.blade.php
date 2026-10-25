@extends('layouts.coach')

@section('title', 'Assiduité')

@section('content')
<div class="container-lg">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Assiduité</h1>
            <p class="text-muted mb-0">Du {{ $from->format('d/m/Y') }} au {{ $to->format('d/m/Y') }} · taux = présences / séances émargées</p>
        </div>
        <a href="{{ route('coach.presence.index') }}" class="btn btn-primary">
            <i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>Faire l'appel
        </a>
    </div>

    {{-- Vues --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $vue === 'nageurs' ? 'active' : '' }}" @if ($vue === 'nageurs') aria-current="page" @endif
               href="{{ route('coach.assiduity.index', array_filter(['periode' => $period, 'groupe' => $groupId])) }}">
                <i class="fas fa-person-swimming me-1" aria-hidden="true"></i>Par nageur
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $vue === 'creneaux' ? 'active' : '' }}" @if ($vue === 'creneaux') aria-current="page" @endif
               href="{{ route('coach.assiduity.index', array_filter(['vue' => 'creneaux', 'periode' => $period, 'groupe' => $groupId])) }}">
                <i class="fas fa-clock me-1" aria-hidden="true"></i>Par créneau
            </a>
        </li>
    </ul>

    {{-- Filtres --}}
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <input type="hidden" name="vue" value="{{ $vue }}">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label for="periode" class="form-label small mb-1">Période</label>
                <select id="periode" name="periode" class="form-select">
                    @foreach (\App\Services\PresenceStats::PERIODS as $key => $label)
                        <option value="{{ $key }}" @selected($period === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="groupe" class="form-label small mb-1">Groupe</label>
                <select id="groupe" name="groupe" class="form-select">
                    <option value="">Tous les groupes</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" @selected($groupId === $group->id)>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-outline-primary">Afficher</button>
            @if ($groupId)
                <a href="{{ route('coach.assiduity.group', $groupId) }}" class="btn btn-outline-secondary ms-auto">
                    <i class="fas fa-table me-1" aria-hidden="true"></i>Tableau mensuel du groupe
                </a>
            @endif
        </div>
    </form>

    @if ($vue === 'nageurs')
        @if ($swimmers->isEmpty())
            <div class="card border-0 shadow-sm"><div class="card-body text-center text-muted py-5">Aucun nageur actif.</div></div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <caption class="visually-hidden">Assiduité par nageur</caption>
                        <thead>
                            <tr>
                                <th scope="col">Nageur</th>
                                <th scope="col">Groupes</th>
                                <th scope="col" class="text-center" title="Présent">P</th>
                                <th scope="col" class="text-center" title="Absent">A</th>
                                <th scope="col" class="text-center" title="Excusé">E</th>
                                <th scope="col">Taux de présence</th>
                                <th scope="col">Dernière présence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($swimmers as $swimmer)
                                @php $c = $counts->get($swimmer->id); @endphp
                                <tr>
                                    <td><a href="{{ route('coach.assiduity.swimmer', [$swimmer, 'periode' => $period]) }}" class="fw-semibold text-decoration-none">{{ $swimmer->full_name }}</a></td>
                                    <td class="small">{{ $swimmer->groups->pluck('name')->join(', ') ?: '—' }}</td>
                                    <td class="text-center">{{ $c['present'] ?? 0 }}</td>
                                    <td class="text-center">{{ $c['absent'] ?? 0 }}</td>
                                    <td class="text-center">{{ $c['excuse'] ?? 0 }}</td>
                                    <td>@include('coach.assiduity._rate', ['rate' => $c['rate'] ?? null])</td>
                                    <td class="small">{{ ! empty($c['last']) ? \Illuminate\Support\Carbon::parse($c['last'])->format('d/m/Y') : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="small text-muted mt-2">P : présent · A : absent · E : excusé. Les séances non émargées ne sont pas comptées.</p>
        @endif
    @else
        @if ($slots->isEmpty())
            <div class="card border-0 shadow-sm"><div class="card-body text-center text-muted py-5">Aucune séance émargée sur cette période.</div></div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <caption class="visually-hidden">Fréquentation par créneau</caption>
                        <thead>
                            <tr>
                                <th scope="col">Créneau</th>
                                <th scope="col">Groupe</th>
                                <th scope="col" class="text-center">Séances</th>
                                <th scope="col" class="text-center">Présents en moyenne</th>
                                <th scope="col" class="text-center" title="Absences">A</th>
                                <th scope="col" class="text-center" title="Excusés">E</th>
                                <th scope="col">Taux de présence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($slots as $row)
                                <tr>
                                    <td class="fw-semibold">{{ $row['when'] ?: '—' }}</td>
                                    <td>{{ $row['label'] }}</td>
                                    <td class="text-center">{{ $row['sessions'] }}</td>
                                    <td class="text-center fw-semibold">{{ number_format($row['average'], 1, ',', ' ') }}</td>
                                    <td class="text-center">{{ $row['absent'] }}</td>
                                    <td class="text-center">{{ $row['excuse'] }}</td>
                                    <td>@include('coach.assiduity._rate', ['rate' => $row['rate']])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
