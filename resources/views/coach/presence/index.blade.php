@extends('layouts.coach')

@section('title', 'Émargement')

@section('content')
<div class="container-lg">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Émargement</h1>
            <p class="text-muted mb-0">
                Séances du {{ $date->translatedFormat('l j F Y') }}@if ($date->isToday()) <span class="badge bg-primary ms-1">Aujourd'hui</span>@endif
            </p>
        </div>
        <a href="{{ route('coach.presence.create', ['date' => $date->toDateString()]) }}" class="btn btn-outline-primary">
            <i class="fas fa-plus me-2" aria-hidden="true"></i>Séance supplémentaire
        </a>
    </div>

    {{-- Changer de jour --}}
    <nav class="d-flex flex-wrap align-items-center gap-2 mb-4" aria-label="Changer de jour">
        <a href="{{ route('coach.presence.index', ['date' => $date->copy()->subDay()->toDateString()]) }}" class="btn btn-outline-secondary">
            <i class="fas fa-chevron-left" aria-hidden="true"></i><span class="visually-hidden">Jour précédent</span>
        </a>
        <form method="GET" class="d-flex gap-2">
            <label for="date" class="visually-hidden">Date</label>
            <input type="date" id="date" name="date" value="{{ $date->toDateString() }}" max="{{ today()->addDays(7)->toDateString() }}" class="form-control">
            <button class="btn btn-outline-secondary">Afficher</button>
        </form>
        @if ($date->lt(today()->addDays(7)))
            <a href="{{ route('coach.presence.index', ['date' => $date->copy()->addDay()->toDateString()]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-chevron-right" aria-hidden="true"></i><span class="visually-hidden">Jour suivant</span>
            </a>
        @endif
        @unless ($date->isToday())
            <a href="{{ route('coach.presence.index') }}" class="btn btn-link">Aujourd'hui</a>
        @endunless
    </nav>

    @if ($slots->isEmpty() && $extras->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-calendar-times fa-2x mb-3" aria-hidden="true"></i>
                <p class="mb-0">Aucun créneau prévu ce jour-là dans le planning.</p>
            </div>
        </div>
    @endif

    <div class="row g-3">
        @foreach ($slots as $slot)
            @php $session = $bySlot->get($slot->id); @endphp
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100 presence-card" style="border-left: 5px solid {{ $slot->group->background_color }} !important">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <h2 class="h5 fw-bold mb-0">{{ $slot->group->name }}</h2>
                            <span class="badge bg-light text-dark border">{{ $periods[$slot->period] ?? $slot->period }}</span>
                        </div>
                        <p class="text-muted small mb-3">
                            <i class="far fa-clock me-1" aria-hidden="true"></i>{{ $slot->time_range }}
                            @if ($slot->location) · {{ $slot->location }}@endif
                        </p>

                        @if ($session)
                            <p class="mb-3">
                                <span class="badge bg-success">Émargée</span>
                                <span class="small ms-1">{{ $session->present_count }} présent{{ $session->present_count > 1 ? 's' : '' }} sur {{ $session->records_count }} émargé{{ $session->records_count > 1 ? 's' : '' }}</span>
                            </p>
                            <a href="{{ route('coach.presence.show', $session) }}" class="btn btn-outline-primary mt-auto">
                                <i class="fas fa-edit me-2" aria-hidden="true"></i>Voir / modifier<span class="visually-hidden"> : {{ $slot->group->name }} {{ $slot->time_range }}</span>
                            </a>
                        @else
                            <p class="small text-muted mb-3">{{ $slot->group->swimmers_count }} nageur{{ $slot->group->swimmers_count > 1 ? 's' : '' }} dans le groupe</p>
                            <form method="POST" action="{{ route('coach.presence.open') }}" class="mt-auto">
                                @csrf
                                <input type="hidden" name="slot" value="{{ $slot->id }}">
                                <input type="hidden" name="date" value="{{ $date->toDateString() }}">
                                <button class="btn btn-primary w-100">
                                    <i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>Faire l'appel<span class="visually-hidden"> : {{ $slot->group->name }} {{ $slot->time_range }}</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($extras as $session)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100 presence-card" style="border-left: 5px solid #D49F45 !important">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <h2 class="h5 fw-bold mb-0">{{ $session->label }}</h2>
                            <span class="badge bg-warning text-dark">{{ $session->isExtra() ? 'Supplémentaire' : 'Créneau retiré' }}</span>
                        </div>
                        <p class="text-muted small mb-3">
                            @if ($session->time_range)<i class="far fa-clock me-1" aria-hidden="true"></i>{{ $session->time_range }}@endif
                            @if ($session->title && $session->group) · {{ $session->group->name }}@endif
                        </p>
                        <p class="small mb-3">{{ $session->present_count }} présent{{ $session->present_count > 1 ? 's' : '' }} sur {{ $session->records_count }} émargé{{ $session->records_count > 1 ? 's' : '' }}</p>
                        <a href="{{ route('coach.presence.show', $session) }}" class="btn btn-outline-primary mt-auto">
                            <i class="fas fa-edit me-2" aria-hidden="true"></i>Voir / modifier<span class="visually-hidden"> : {{ $session->label }}</span>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <p class="small text-muted mt-4 mb-0">
        <i class="fas fa-info-circle me-1" aria-hidden="true"></i>
        Les créneaux affichés sont ceux du planning pour ce jour de la semaine (période scolaire et vacances).
        Faites l'appel uniquement pour les séances qui ont lieu.
    </p>
</div>
@endsection
