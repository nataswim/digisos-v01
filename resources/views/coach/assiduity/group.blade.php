@extends('layouts.coach')

@section('title', 'Tableau mensuel — ' . $group->name)

@section('content')
<div class="container-fluid">
    <a href="{{ route('coach.assiduity.index', ['groupe' => $group->id]) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Assiduité
    </a>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ $group->name }}</h1>
            <p class="text-muted mb-0">Tableau mensuel des présences · {{ ucfirst($month->translatedFormat('F Y')) }}</p>
        </div>
        <nav class="d-flex flex-wrap gap-2 align-items-center" aria-label="Changer de mois ou de groupe">
            <a href="{{ route('coach.assiduity.group', [$group, 'mois' => $month->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-chevron-left" aria-hidden="true"></i><span class="visually-hidden">Mois précédent</span>
            </a>
            <a href="{{ route('coach.assiduity.group', [$group, 'mois' => $month->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-chevron-right" aria-hidden="true"></i><span class="visually-hidden">Mois suivant</span>
            </a>
            <form method="GET" action="{{ route('coach.assiduity.index') }}" class="d-flex gap-2">
                <label for="autre-groupe" class="visually-hidden">Autre groupe</label>
                <select id="autre-groupe" class="form-select" onchange="if (this.value) window.location = this.value">
                    @foreach ($groups as $g)
                        <option value="{{ route('coach.assiduity.group', [$g, 'mois' => $month->format('Y-m')]) }}" @selected($g->id === $group->id)>{{ $g->name }}</option>
                    @endforeach
                </select>
            </form>
        </nav>
    </div>

    @if ($sessions->isEmpty())
        <div class="card border-0 shadow-sm"><div class="card-body text-center text-muted py-5">Aucune séance émargée ce mois-ci pour ce groupe.</div></div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle text-center mb-0 presence-grid">
                    <caption class="visually-hidden">Présences du groupe {{ $group->name }}, {{ $month->translatedFormat('F Y') }}</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-start">Nageur</th>
                            @foreach ($sessions as $session)
                                <th scope="col" class="small">
                                    <a href="{{ route('coach.presence.show', $session) }}" class="text-decoration-none">
                                        {{ $session->date->translatedFormat('D') }}<br>{{ $session->date->format('d') }}
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col">Présences</th>
                            <th scope="col">Taux</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($swimmers as $swimmer)
                            <tr>
                                <th scope="row" class="text-start text-nowrap fw-semibold">
                                    <a href="{{ route('coach.assiduity.swimmer', $swimmer) }}" class="text-decoration-none">{{ $swimmer->full_name }}</a>
                                </th>
                                @foreach ($sessions as $session)
                                    @php $status = $cells[$swimmer->id][$session->id] ?? null; @endphp
                                    @if ($status)
                                        @php [$lettre, $couleur] = \App\Models\PresenceRecord::BADGES[$status]; @endphp
                                        <td class="presence-cell presence-cell--{{ $status }}" title="{{ \App\Models\PresenceRecord::STATUSES[$status] }}">
                                            {{ $lettre }}<span class="visually-hidden"> ({{ \App\Models\PresenceRecord::STATUSES[$status] }})</span>
                                        </td>
                                    @else
                                        <td class="text-muted" title="Non émargé">·<span class="visually-hidden">non émargé</span></td>
                                    @endif
                                @endforeach
                                <td class="fw-semibold">{{ $totals[$swimmer->id]['present'] }} / {{ $totals[$swimmer->id]['total'] }}</td>
                                <td class="fw-semibold">{{ $totals[$swimmer->id]['rate'] !== null ? $totals[$swimmer->id]['rate'] . ' %' : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th scope="row" class="text-start">Présents</th>
                            @foreach ($sessions as $session)
                                <td class="fw-semibold">{{ collect($cells)->filter(fn ($c) => ($c[$session->id] ?? null) === 'present')->count() }}</td>
                            @endforeach
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <p class="small text-muted mt-2">P : présent · A : absent · E : excusé · « · » : non émargé. Présences = séances « présent » / séances émargées. Cliquez sur une date pour ouvrir la séance.</p>
    @endif
</div>
@endsection

@push('styles')
<style>
    .presence-grid td, .presence-grid th { min-width: 42px; }
    .presence-cell { font-weight: 700; }
    .presence-cell--present { background: #d1fae5 !important; color: #065f46; }
    .presence-cell--absent { background: #fee2e2 !important; color: #991b1b; }
    .presence-cell--excuse { background: #FBF6EC !important; color: #7a5518; }
</style>
@endpush
