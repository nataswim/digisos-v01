@extends('layouts.coach')

@section('title', 'Records')

@section('content')
@php use App\Models\SwimRecord as R; $official = $level !== 'club'; @endphp
<div class="container-lg">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Records</h1>
            <p class="text-muted mb-0">Records du club (saisis ici) et records officiels (importés depuis extraNat), affichés sur la page publique.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('records.index', ['niveau' => $level, 'bassin' => $pool, 'categorie' => $category]) }}" class="btn btn-outline-secondary" target="_blank" rel="noopener">
                <i class="fas fa-eye me-1" aria-hidden="true"></i>Page publique
            </a>
            <a href="{{ route('coach.records.import-form', ['niveau' => $official ? $level : 'france', 'bassin' => $pool, 'categorie' => $category]) }}" class="btn btn-outline-primary">
                <i class="fas fa-file-import me-1" aria-hidden="true"></i>Importer depuis extraNat
            </a>
            <a href="{{ route('coach.records.create', ['niveau' => $level, 'bassin' => $pool, 'categorie' => $category]) }}" class="btn btn-primary">
                <i class="fas fa-plus me-1" aria-hidden="true"></i>{{ $official ? 'Ajouter un record' : 'Nouveau record du club' }}
            </a>
        </div>
    </div>

    @include('coach.records._filters', ['route' => 'coach.records.index', 'levels' => array_keys(R::LEVELS)])

    <h2 class="h5 fw-bold mb-3">{{ R::levelLabel($level) }} · {{ R::POOLS[$pool] }} · {{ R::CATEGORIES[$category] }}
        @if ($updatedAt)<small class="text-muted fw-normal">— mis à jour le {{ \Illuminate\Support\Carbon::parse($updatedAt)->format('d/m/Y') }}</small>@endif
    </h2>

    @if ($records->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                Aucun record pour cette sélection.
                @if ($official)
                    <br><a href="{{ route('coach.records.import-form', ['niveau' => $level, 'bassin' => $pool, 'categorie' => $category]) }}">Importer le tableau depuis extraNat</a>
                @endif
            </div>
        </div>
    @endif

    @foreach (R::SEXES as $sex => $sexLabel)
        @continue(! $records->has($sex))
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between">
                <h3 class="h6 fw-bold mb-0">{{ $sexLabel }}</h3>
                <a href="{{ route('coach.records.create', ['niveau' => $level, 'bassin' => $pool, 'categorie' => $category, 'sexe' => $sex]) }}" class="small">Ajouter</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th scope="col">Épreuve</th><th scope="col">Temps</th><th scope="col">Nageur(s)</th><th scope="col">Club</th><th scope="col">Date</th><th scope="col">Lieu</th><th scope="col"><span class="visually-hidden">Actions</span></th></tr></thead>
                    <tbody>
                        @foreach ($records[$sex] as $record)
                            <tr>
                                <th scope="row">{{ $record->event }}</th>
                                <td class="fw-bold font-monospace">{{ $record->time_label }}</td>
                                <td class="small">{!! nl2br(e($record->swimmers)) !!}</td>
                                <td class="small">{{ $record->club }}</td>
                                <td class="small text-nowrap">{{ $record->record_date?->format('d/m/Y') }}</td>
                                <td class="small">{{ $record->location }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('coach.records.edit', $record) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier {{ $record->event }}</span></a>
                                    <form method="POST" action="{{ route('coach.records.destroy', $record) }}" class="d-inline" onsubmit="return confirm('Supprimer ce record ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer {{ $record->event }}</span></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
</div>
@endsection
