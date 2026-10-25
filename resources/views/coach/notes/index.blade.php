@extends('layouts.coach')

@section('title', 'Suivi des nageurs')

@section('content')
<div class="container-lg">
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1">Suivi des nageurs</h1>
        <p class="text-muted mb-0">Notes d'entraînement et de compétition, visibles uniquement des coachs et des administrateurs.</p>
    </div>

    <form method="GET" class="card border-0 shadow-sm mb-4" role="search">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label for="q" class="form-label small mb-1">Nageur</label>
                <input type="search" id="q" name="q" value="{{ $search }}" class="form-control" placeholder="Nom ou prénom">
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
            <button class="btn btn-outline-primary">Filtrer</button>
            @if ($search !== '' || $groupId)
                <a href="{{ route('coach.notes.index') }}" class="btn btn-link">Effacer</a>
            @endif
        </div>
    </form>

    @if ($swimmers->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">Aucun nageur actif{{ $search !== '' || $groupId ? ' pour ce filtre' : '' }}.</div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <caption class="visually-hidden">Nageurs actifs et nombre de notes de suivi</caption>
                    <thead>
                        <tr>
                            <th scope="col">Nageur</th>
                            <th scope="col">Groupes</th>
                            <th scope="col" class="text-center">Notes</th>
                            <th scope="col">Dernière note</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($swimmers as $swimmer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($swimmer->photo)
                                            <img src="{{ $swimmer->photo }}" alt="" width="36" height="36" class="rounded-circle object-fit-cover">
                                        @else
                                            <span class="espace-avatar" aria-hidden="true">{{ $swimmer->initials }}</span>
                                        @endif
                                        <span class="fw-semibold">{{ $swimmer->full_name }}</span>
                                        @if ($swimmer->birth_year)<small class="text-muted">{{ $swimmer->birth_year }}</small>@endif
                                    </div>
                                </td>
                                <td class="small">{{ $swimmer->groups->pluck('name')->join(', ') ?: '—' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $swimmer->coach_notes_count ? 'bg-primary' : 'bg-light text-muted' }}">{{ $swimmer->coach_notes_count }}</span>
                                </td>
                                <td class="small">
                                    {{ $swimmer->coach_notes_max_noted_on ? \Illuminate\Support\Carbon::parse($swimmer->coach_notes_max_noted_on)->format('d/m/Y') : '—' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('coach.notes.show', $swimmer) }}" class="btn btn-sm btn-outline-primary">
                                        Ouvrir<span class="visually-hidden"> le suivi de {{ $swimmer->full_name }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $swimmers->links() }}</div>
    @endif
</div>
@endsection
