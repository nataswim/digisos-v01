@extends('layouts.coach')

@section('title', 'Séances')

@section('content')
@php $types = \App\Models\TrainingSession::TYPES; @endphp
<div class="container-lg">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Séances & entraînements</h1>
            <p class="text-muted mb-0">Semaine du {{ $monday->translatedFormat('j F') }} au {{ $monday->copy()->addDays(6)->translatedFormat('j F Y') }}</p>
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus me-2" aria-hidden="true"></i>Nouvelle séance
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach ($types as $key => [$label, $icon, $color])
                    <li><a class="dropdown-item" href="{{ route('coach.training.create', ['type' => $key, 'date' => $monday->isCurrentWeek() ? today()->toDateString() : $monday->toDateString()]) }}">
                        <i class="fas {{ $icon }} fa-fw me-2" style="color: {{ $color }}" aria-hidden="true"></i>{{ $label }}
                    </a></li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Semaine et filtres --}}
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div class="btn-group" role="group" aria-label="Changer de semaine">
                <a href="{{ route('coach.training.index', array_filter(['semaine' => $monday->copy()->subWeek()->toDateString(), 'type' => $type, 'groupe' => $groupId, 'statut' => $status])) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i><span class="visually-hidden">Semaine précédente</span>
                </a>
                <a href="{{ route('coach.training.index', array_filter(['type' => $type, 'groupe' => $groupId, 'statut' => $status])) }}" class="btn btn-outline-secondary">Cette semaine</a>
                <a href="{{ route('coach.training.index', array_filter(['semaine' => $monday->copy()->addWeek()->toDateString(), 'type' => $type, 'groupe' => $groupId, 'statut' => $status])) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i><span class="visually-hidden">Semaine suivante</span>
                </a>
            </div>
            <input type="hidden" name="semaine" value="{{ $monday->toDateString() }}">
            <div>
                <label for="type" class="form-label small mb-1">Type</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Tous</option>
                    @foreach ($types as $key => [$label])<option value="{{ $key }}" @selected($type === $key)>{{ $label }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="groupe" class="form-label small mb-1">Groupe</label>
                <select id="groupe" name="groupe" class="form-select">
                    <option value="">Tous</option>
                    @foreach ($groups as $group)<option value="{{ $group->id }}" @selected($groupId === $group->id)>{{ $group->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="statut" class="form-label small mb-1">Statut</label>
                <select id="statut" name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach (\App\Models\TrainingSession::STATUSES as $key => $label)<option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>@endforeach
                </select>
            </div>
            <button class="btn btn-outline-primary">Filtrer</button>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('coach.exercises.index') }}" class="btn btn-outline-secondary"><i class="fas fa-book-medical me-1" aria-hidden="true"></i>Exercices</a>
                <a href="{{ route('coach.training-lists.edit') }}" class="btn btn-outline-secondary"><i class="fas fa-list me-1" aria-hidden="true"></i>Listes</a>
            </div>
        </div>
    </form>

    @if ($sessions->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-calendar-plus fa-2x mb-3" aria-hidden="true"></i>
                <p class="mb-0">Aucune séance cette semaine{{ $type || $groupId || $status ? ' pour ces filtres' : '' }}.</p>
            </div>
        </div>
    @endif

    @foreach ($sessions as $date => $jour)
        <h2 class="h6 fw-bold text-uppercase text-muted mt-4 mb-2">{{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l j F') }}</h2>
        @foreach ($jour as $session)
            <div class="card border-0 shadow-sm mb-2 tr-row" style="border-left: 5px solid {{ $session->type_color }} !important">
                <div class="card-body py-3 d-flex flex-wrap gap-3 align-items-center">
                    <div class="flex-grow-1" style="min-width: 240px">
                        <div class="d-flex flex-wrap gap-2 align-items-center mb-1">
                            <span class="badge" style="background: {{ $session->type_color }}"><i class="fas {{ $session->type_icon }} me-1" aria-hidden="true"></i>{{ $session->type_label }}</span>
                            <span class="badge {{ $session->isPublished() ? 'bg-success' : 'bg-secondary' }}">{{ \App\Models\TrainingSession::STATUSES[$session->status] }}</span>
                        </div>
                        <a href="{{ route('coach.training.edit', $session) }}" class="fw-bold fs-5 text-decoration-none">{{ $session->title }}</a>
                        <div class="small text-muted">
                            @if ($session->time_range){{ $session->time_range }} · @endif
                            {{ $session->groups->pluck('name')->join(', ') ?: 'Sans groupe' }} ·
                            {{ $session->swimmers_count }} nageur{{ $session->swimmers_count > 1 ? 's' : '' }}
                            @if ($session->volume_label) · <strong>{{ $session->volume_label }}</strong>@endif
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        <a href="{{ route('coach.training.show', $session) }}" class="btn btn-sm btn-outline-primary" title="Aperçu"><i class="fas fa-eye" aria-hidden="true"></i><span class="visually-hidden">Aperçu de {{ $session->title }}</span></a>
                        <a href="{{ route('coach.training.edit', $session) }}" class="btn btn-sm btn-outline-secondary" title="Modifier"><i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier {{ $session->title }}</span></a>
                        <form method="POST" action="{{ route('coach.training.duplicate', $session) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary" title="Dupliquer (semaine suivante)"><i class="fas fa-copy" aria-hidden="true"></i><span class="visually-hidden">Dupliquer {{ $session->title }}</span></button>
                        </form>
                        <form method="POST" action="{{ route('coach.training.toggle', $session) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm {{ $session->isPublished() ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                {{ $session->isPublished() ? 'Dépublier' : 'Publier' }}<span class="visually-hidden"> : {{ $session->title }}</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('coach.training.destroy', $session) }}" onsubmit="return confirm('Supprimer cette séance ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer {{ $session->title }}</span></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>
@endsection
