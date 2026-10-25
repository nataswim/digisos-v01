@extends('layouts.coach')

@section('title', 'Aperçu — ' . $session->title)

@section('content')
<div class="container-lg" style="max-width: 980px">
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3 d-print-none">
        <a href="{{ route('coach.training.index', ['semaine' => $session->session_date->copy()->startOfWeek()->toDateString()]) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Séances
        </a>
        <a href="{{ route('coach.training.edit', $session) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1" aria-hidden="true"></i>Modifier</a>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="fas fa-print me-1" aria-hidden="true"></i>Imprimer</button>
        <span class="badge fs-6 ms-auto {{ $session->isInternal() ? 'bg-dark' : ($session->isPublished() ? 'bg-success' : 'bg-secondary') }}">{{ $session->isInternal() ? 'Fiche coachs' : \App\Models\TrainingSession::STATUSES[$session->status] }}</span>
    </div>

    @if ($session->isInternal())
    <div class="alert alert-light border d-print-none">
        <i class="fas fa-lock me-1" aria-hidden="true"></i>Fiche réservée aux coachs @if ($session->groups->isNotEmpty()) · {{ $session->groups->pluck('name')->join(', ') }}@endif.
    </div>
    @else
    <div class="alert alert-light border d-print-none">
        <i class="fas fa-users me-1" aria-hidden="true"></i>
        <strong>{{ $session->swimmers->count() }} nageur{{ $session->swimmers->count() > 1 ? 's' : '' }}</strong>
        @if ($session->groups->isNotEmpty())({{ $session->groups->pluck('name')->join(', ') }})@endif :
        {{ $session->swimmers->sortBy('last_name')->map->full_name->join(', ') ?: 'aucun nageur choisi' }}.
        @unless ($session->isPublished())<br><em>Brouillon : les nageurs ne voient pas encore la séance.</em>@endunless
    </div>
    @endif

    @include('training._detail', ['session' => $session, 'exercises' => $exercises])
</div>
@endsection
