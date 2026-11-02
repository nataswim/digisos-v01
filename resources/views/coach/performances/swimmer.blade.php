@extends('layouts.coach')

@section('title', 'Performances — ' . $swimmer->full_name)

@section('content')
<div class="container-lg">
    <a href="{{ route('coach.performances.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Performances</a>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            @if ($swimmer->photo)<img src="{{ $swimmer->photo }}" alt="" width="64" height="64" class="rounded-circle object-fit-cover" style="width:64px;height:64px">@endif
            <div>
                <h1 class="h3 fw-bold mb-1">{{ $swimmer->full_name }}</h1>
                <p class="text-muted mb-0">@if ($swimmer->birth_year)Né(e) en {{ $swimmer->birth_year }} · @endif{{ $swimmer->groups->pluck('name')->join(', ') ?: 'Aucun groupe' }}</p>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('coach.notes.show', $swimmer) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-clipboard-list me-1" aria-hidden="true"></i>Notes de suivi</a>
            <a href="{{ route('coach.assiduity.swimmer', $swimmer) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-chart-column me-1" aria-hidden="true"></i>Assiduité</a>
        </div>
    </div>

    @include('performances._swimmer', ['chartRoute' => ['coach.performances.swimmer', ['swimmer' => $swimmer->id]], 'coach' => true])
</div>
@endsection
