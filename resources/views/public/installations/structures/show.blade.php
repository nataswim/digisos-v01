@extends('layouts.public')

@section('title', $structure->name . ' — ' . $structure->service->name)
@section('meta_description', strip_tags($structure->description ?? 'Découvrez les espaces de ' . $structure->name))

@section('content')

{{-- Hero --}}
<section>
    <div class="nataswim-titre1 position-relative text-white">
        <div class="container-lg">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb breadcrumb-public">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-white opacity-75">Accueil</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('public.installations.index') }}" class="text-white opacity-75">Installations</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('public.installations.service', $structure->service) }}"
                           class="text-white opacity-75">{{ $structure->service->name }}</a>
                    </li>
                    <li class="breadcrumb-item active text-white">{{ $structure->name }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3 mb-2">
                <i class="fas fa-layer-group fa-2x text-white opacity-75"></i>
                <h1 class="text-white mb-0">{{ $structure->name }}</h1>
            </div>
            <p class="text-white opacity-75 mb-0">
                <i class="fas fa-building me-2"></i>{{ $structure->service->name }}
            </p>
        </div>
    </div>
</section>

<article class="py-5 bg-white">
    <div class="container-lg">
        <div class="row g-5">

            {{-- Colonne principale --}}
            <div class="col-lg-8">

                {{-- Photo --}}
                @if($structure->photo)
                    <div class="mb-4">
                        <img src="{{ $structure->photo }}"
                             alt="{{ $structure->name }}"
                             class="img-fluid rounded shadow-aqua w-100"
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                {{-- Description --}}
                @if($structure->description)
                    <div class="card-aqua mb-4">
                        <div class="content-display">
                            {!! $structure->description !!}
                        </div>
                    </div>
                @endif

                {{-- Espaces --}}
                <h2 class="h4 mb-4">
                    <i class="fas fa-th-large me-2 text-success"></i>
                    Espaces disponibles
                    <span class="badge bg-success ms-2">{{ $structure->espaces->count() }}</span>
                </h2>

                @if($structure->espaces->count() > 0)
                    <div class="row g-4">
                        @foreach($structure->espaces as $espace)
                            <div class="col-12 col-md-6 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                <div class="card-aqua hover-lift h-100">

                                    @if($espace->photo)
                                        <img src="{{ $espace->photo }}"
                                             alt="{{ $espace->name }}"
                                             class="img-fluid rounded-top w-100"
                                             style="height: 160px; object-fit: cover;">
                                    @else
                                        <div class="bg-success-lighter rounded-top d-flex align-items-center justify-content-center"
                                             style="height: 160px;">
                                            <i class="fas fa-th-large fa-2x text-success opacity-50"></i>
                                        </div>
                                    @endif

                                    <div class="p-4">
                                        <h3 class="h5 mb-2">
                                            <a href="{{ route('public.installations.espace', $espace) }}"
                                               class="text-decoration-none text-dark hover-primary">
                                                {{ $espace->name }}
                                            </a>
                                        </h3>

                                        @if($espace->capacity)
                                            <p class="text-muted small mb-2">
                                                <i class="fas fa-users me-1"></i>{{ number_format($espace->capacity) }} personnes
                                            </p>
                                        @endif

                                        @if($espace->description)
                                            <p class="text-muted small mb-3">
                                                {{ Str::limit(strip_tags($espace->description), 100) }}
                                            </p>
                                        @endif

                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="badge bg-warning text-dark">
                                                {{ $espace->zones_count }} zone{{ $espace->zones_count > 1 ? 's' : '' }}
                                            </span>
                                            <a href="{{ route('public.installations.espace', $espace) }}"
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-arrow-right me-1"></i>Voir
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card-aqua text-center py-4">
                        <i class="fas fa-th-large fa-2x text-muted mb-2 opacity-25"></i>
                        <p class="text-muted mb-0">Aucun espace disponible pour le moment.</p>
                    </div>
                @endif

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">

                {{-- Infos --}}
                <div class="card-aqua mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informations
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center gap-2 mb-3">
                            <i class="fas fa-building text-primary"></i>
                            <span class="text-muted">Service :
                                <a href="{{ route('public.installations.service', $structure->service) }}"
                                   class="text-primary fw-semibold text-decoration-none">
                                    {{ $structure->service->name }}
                                </a>
                            </span>
                        </li>
                        @if($structure->capacity)
                            <li class="d-flex align-items-center gap-2 mb-3">
                                <i class="fas fa-users text-primary"></i>
                                <span class="text-muted">Capacité : <strong>{{ number_format($structure->capacity) }}</strong> personnes</span>
                            </li>
                        @endif
                        <li class="d-flex align-items-center gap-2">
                            <i class="fas fa-th-large text-success"></i>
                            <span class="text-muted">
                                <strong>{{ $structure->espaces->count() }}</strong>
                                espace{{ $structure->espaces->count() > 1 ? 's' : '' }} disponible{{ $structure->espaces->count() > 1 ? 's' : '' }}
                            </span>
                        </li>
                    </ul>
                </div>

                {{-- Navigation --}}
                <div class="card-aqua">
                    <div class="d-grid gap-2">
                        <a href="{{ route('public.installations.service', $structure->service) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-building me-2"></i>{{ $structure->service->name }}
                        </a>
                        <a href="{{ route('public.installations.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Toutes les installations
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</article>

@endsection

@push('styles')
<style>
.breadcrumb-public .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.5); }
.hover-primary { transition: color 0.2s ease; }
.hover-primary:hover { color: #38859b !important; }
.bg-success-lighter { background-color: rgba(25, 135, 84, 0.1); }
.content-display h1, .content-display h2, .content-display h3 { margin-top: 1.5rem; margin-bottom: 0.75rem; font-weight: 600; }
.content-display h1 { font-size: 1.7rem; color: #38859b; }
.content-display h2 { font-size: 1.5rem; color: #2f80b8; }
.content-display h3 { font-size: 1.3rem; color: #2f80b8; }
.content-display p  { margin-bottom: 1.25rem; line-height: 1.8; color: #4a5568; }
.content-display ul, .content-display ol { margin-bottom: 1.25rem; padding-left: 2rem; line-height: 1.7; }
.content-display li { margin-bottom: 0.4rem; }
</style>
@endpush
