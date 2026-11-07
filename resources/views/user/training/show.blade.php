@extends('layouts.member')

@section('title', $session->title)

@section('content')
<section class="py-4 py-md-5 bg-light">
    <div class="container-lg" style="max-width: 980px">
        <div class="tr-inline mb-3 d-print-none">
            <a href="{{ route('user.training.index', ['semaine' => $session->session_date->copy()->startOfWeek()->toDateString()]) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Mes entraînements
            </a>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="fas fa-print me-1" aria-hidden="true"></i>Imprimer</button>
            @if ($session->swimmers->isNotEmpty())
                <span class="small text-muted ms-auto">Pour : {{ $session->swimmers->map->full_name->join(', ') }}</span>
            @endif
        </div>

        @include('training._detail', ['session' => $session, 'exercises' => $exercises])
    </div>
</section>
@endsection
