@extends('layouts.public')

@section('title', $espace->name . ' — ' . $espace->structure->name)
@section('meta_description', strip_tags($espace->description ?? 'Découvrez les zones de ' . $espace->name))

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
                        <a href="{{ route('public.installations.service', $espace->structure->service) }}"
                           class="text-white opacity-75">{{ $espace->structure->service->name }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('public.installations.structure', $espace->structure) }}"
                           class="text-white opacity-75">{{ $espace->structure->name }}</a>
                    </li>
                    <li class="breadcrumb-item active text-white">{{ $espace->name }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3 mb-2">
                <i class="fas fa-th-large fa-2x text-white opacity-75"></i>
                <h1 class="text-white mb-0">{{ $espace->name }}</h1>
            </div>
            <p class="text-white opacity-75 mb-0">
                <i class="fas fa-layer-group me-1"></i>{{ $espace->structure->name }}
                <i class="fas fa-chevron-right mx-2 opacity-50"></i>
                <i class="fas fa-building me-1"></i>{{ $espace->structure->service->name }}
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
                @if($espace->photo)
                    <div class="mb-4">
                        <img src="{{ $espace->photo }}"
                             alt="{{ $espace->name }}"
                             class="img-fluid rounded shadow-aqua w-100"
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                {{-- Description --}}
                @if($espace->description)
                    <div class="card-aqua mb-4">
                        <div class="content-display">
                            {!! $espace->description !!}
                        </div>
                    </div>
                @endif

                {{-- Zones --}}
                <h2 class="h4 mb-4">
                    <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                    Zones disponibles
                    <span class="badge bg-warning text-dark ms-2">{{ $espace->zones->count() }}</span>
                </h2>

                @if($espace->zones->count() > 0)
                    <div class="row g-3">
                        @foreach($espace->zones as $zone)
                            <div class="col-12 col-md-6 fade-in-up" style="animation-delay: {{ $loop->index * 0.08 }}s;">
                                <div class="card-aqua hover-lift h-100">

                                    @if($zone->photo)
                                        <img src="{{ $zone->photo }}"
                                             alt="{{ $zone->name }}"
                                             class="img-fluid rounded-top w-100"
                                             style="height: 140px; object-fit: cover;">
                                    @else
                                        <div class="bg-warning-lighter rounded-top d-flex align-items-center justify-content-center"
                                             style="height: 140px;">
                                            <i class="fas fa-map-marker-alt fa-2x text-warning opacity-50"></i>
                                        </div>
                                    @endif

                                    <div class="p-3">
                                        <h3 class="h6 mb-2">
                                            <a href="{{ route('public.installations.zone', $zone) }}"
                                               class="text-decoration-none text-dark hover-primary">
                                                {{ $zone->name }}
                                            </a>
                                        </h3>

                                        @if($zone->capacity)
                                            <p class="text-muted small mb-2">
                                                <i class="fas fa-users me-1"></i>{{ number_format($zone->capacity) }} personnes
                                            </p>
                                        @endif

                                        @if($zone->description)
                                            <p class="text-muted small mb-3">
                                                {{ Str::limit(strip_tags($zone->description), 80) }}
                                            </p>
                                        @endif

                                        <a href="{{ route('public.installations.zone', $zone) }}"
                                           class="btn btn-sm btn-warning text-dark">
                                            <i class="fas fa-arrow-right me-1"></i>Détails
                                        </a>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card-aqua text-center py-4">
                        <i class="fas fa-map-marker-alt fa-2x text-muted mb-2 opacity-25"></i>
                        <p class="text-muted mb-0">Aucune zone disponible pour le moment.</p>
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
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="fas fa-layer-group text-success mt-1"></i>
                            <span class="text-muted">Structure :
                                <a href="{{ route('public.installations.structure', $espace->structure) }}"
                                   class="text-success fw-semibold text-decoration-none">
                                    {{ $espace->structure->name }}
                                </a>
                            </span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="fas fa-building text-primary mt-1"></i>
                            <span class="text-muted">Service :
                                <a href="{{ route('public.installations.service', $espace->structure->service) }}"
                                   class="text-primary fw-semibold text-decoration-none">
                                    {{ $espace->structure->service->name }}
                                </a>
                            </span>
                        </li>
                        @if($espace->capacity)
                            <li class="d-flex align-items-center gap-2 mb-3">
                                <i class="fas fa-users text-primary"></i>
                                <span class="text-muted">Capacité : <strong>{{ number_format($espace->capacity) }}</strong> personnes</span>
                            </li>
                        @endif
                        <li class="d-flex align-items-center gap-2">
                            <i class="fas fa-map-marker-alt text-warning"></i>
                            <span class="text-muted">
                                <strong>{{ $espace->zones->count() }}</strong>
                                zone{{ $espace->zones->count() > 1 ? 's' : '' }} disponible{{ $espace->zones->count() > 1 ? 's' : '' }}
                            </span>
                        </li>
                    </ul>
                </div>

                {{-- Navigation --}}
                <div class="card-aqua">
                    <div class="d-grid gap-2">
                        <a href="{{ route('public.installations.structure', $espace->structure) }}"
                           class="btn btn-outline-success">
                            <i class="fas fa-layer-group me-2"></i>{{ $espace->structure->name }}
                        </a>
                        <a href="{{ route('public.installations.service', $espace->structure->service) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-building me-2"></i>{{ $espace->structure->service->name }}
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
.bg-warning-lighter { background-color: rgba(255, 193, 7, 0.15); }
.content-display h1, .content-display h2, .content-display h3 { margin-top: 1.5rem; margin-bottom: 0.75rem; font-weight: 600; }
.content-display h1 { font-size: 1.7rem; color: #38859b; }
.content-display h2 { font-size: 1.5rem; color: #2f80b8; }
.content-display h3 { font-size: 1.3rem; color: #2f80b8; }
.content-display p  { margin-bottom: 1.25rem; line-height: 1.8; color: #4a5568; }
.content-display ul, .content-display ol { margin-bottom: 1.25rem; padding-left: 2rem; line-height: 1.7; }
.content-display li { margin-bottom: 0.4rem; }
</style>
@endpush
