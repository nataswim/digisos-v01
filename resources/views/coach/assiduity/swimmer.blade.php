@extends('layouts.coach')

@section('title', 'Assiduité — ' . $swimmer->full_name)

@section('content')
<div class="container-lg">
    <a href="{{ route('coach.assiduity.index', ['periode' => $period]) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Assiduité
    </a>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            @if ($swimmer->photo)
                <img src="{{ $swimmer->photo }}" alt="" width="64" height="64" class="rounded-circle object-fit-cover">
            @endif
            <div>
                <h1 class="h3 fw-bold mb-1">{{ $swimmer->full_name }}</h1>
                <p class="text-muted mb-0">{{ $swimmer->groups->pluck('name')->join(', ') ?: 'Aucun groupe' }} · du {{ $from->format('d/m/Y') }} au {{ $to->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <form method="GET" class="d-flex gap-2">
                <label for="periode" class="visually-hidden">Période</label>
                <select id="periode" name="periode" class="form-select" onchange="this.form.submit()">
                    @foreach (\App\Services\PresenceStats::PERIODS as $key => $label)
                        <option value="{{ $key }}" @selected($period === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <noscript><button class="btn btn-outline-primary">Afficher</button></noscript>
            </form>
            <a href="{{ route('coach.notes.show', $swimmer) }}" class="btn btn-outline-primary">
                <i class="fas fa-clipboard-list me-1" aria-hidden="true"></i>Notes de suivi
            </a>
        </div>
    </div>

    {{-- Chiffres clés --}}
    <div class="row g-3 mb-4">
        @php
            $tuiles = [
                ['Taux de présence', isset($counts['rate']) ? $counts['rate'] . ' %' : '—', 'primary'],
                ['Présent', $counts['present'] ?? 0, 'success'],
                ['Absent', $counts['absent'] ?? 0, 'danger'],
                ['Excusé', $counts['excuse'] ?? 0, 'warning'],
            ];
        @endphp
        @foreach ($tuiles as [$label, $valeur, $couleur])
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body">
                        <div class="h2 fw-bold mb-0 text-{{ $couleur }}">{{ $valeur }}</div>
                        <div class="small text-muted">{{ $label }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <section class="col-lg-5" aria-labelledby="titre-creneaux">
            <h2 id="titre-creneaux" class="h5 fw-bold mb-3">Par créneau</h2>
            @if ($bySlot->isEmpty())
                <p class="text-muted">Aucun émargement sur la période.</p>
            @else
                <ul class="list-group shadow-sm">
                    @foreach ($bySlot as $row)
                        <li class="list-group-item">
                            <div class="fw-semibold mb-1">{{ $row['label'] }}</div>
                            <div class="small text-muted mb-1">{{ $row['present'] }} P · {{ $row['absent'] }} A · {{ $row['excuse'] }} E</div>
                            @include('coach.assiduity._rate', ['rate' => $row['rate']])
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section class="col-lg-7" aria-labelledby="titre-historique">
            <h2 id="titre-historique" class="h5 fw-bold mb-3">Historique</h2>
            @if ($history->isEmpty())
                <p class="text-muted">Aucun émargement sur la période.</p>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <caption class="visually-hidden">Historique des présences</caption>
                            <thead><tr><th scope="col">Date</th><th scope="col">Séance</th><th scope="col">Présence</th><th scope="col">Commentaire</th></tr></thead>
                            <tbody>
                                @foreach ($history as $record)
                                    @php [$lettre, $couleur] = \App\Models\PresenceRecord::BADGES[$record->status] ?? ['?', 'secondary']; @endphp
                                    <tr>
                                        <td class="text-nowrap">{{ $record->session->date->format('d/m/Y') }}</td>
                                        <td class="small">
                                            <a href="{{ route('coach.presence.show', $record->session) }}" class="text-decoration-none">{{ $record->session->label }}</a>
                                            <span class="text-muted">{{ $record->session->time_range }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $couleur }} {{ $couleur === 'warning' ? 'text-dark' : '' }}">{{ $record->status_label }}</span>
                                            @if ($record->arrival_label)<small class="text-muted ms-1">{{ $record->arrival_label }}</small>@endif
                                        </td>
                                        <td class="small">{{ $record->comment }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
