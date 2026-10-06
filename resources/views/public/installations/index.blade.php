@extends('layouts.public')

@section('title', 'Nos Installations')
@section('meta_description', 'Découvrez l\'ensemble de nos installations sportives : services, structures, espaces et zones.')

@section('content')

{{-- Hero Section --}}
<section class="position-relative text-white overflow-hidden">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="{{ asset('assets/images/team/CNBB-natation-1.mp4') }}" type="video/mp4">
    </video>
    <div class="container-lg py-5 position-relative hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-4 animate-slide-up">
                    <i class="fas fa-building me-3" style="font-size: 2.5rem;"></i>
                    <h1 class="text-white display-3 fw-bold mb-0">Nos Installations</h1>
                </div>
                <p class="lead mb-4 animate-slide-up animation-delay-1">
                    Explorez l'ensemble de nos équipements et découvrez chaque espace.
                </p>
                {{-- Fil d'ariane hiérarchique --}}
                <div class="d-flex flex-wrap gap-2 animate-slide-up animation-delay-1">
                    <span class="badge bg-white text-primary px-3 py-2">
                        <i class="fas fa-building me-1"></i>Services
                    </span>
                    <i class="fas fa-chevron-right text-white opacity-50 align-self-center"></i>
                    <span class="badge bg-white bg-opacity-25 text-primary px-3 py-2">
                        <i class="fas fa-layer-group me-1"></i>Structures
                    </span>
                    <i class="fas fa-chevron-right text-white opacity-50 align-self-center"></i>
                    <span class="badge bg-white bg-opacity-25 text-primary px-3 py-2">
                        <i class="fas fa-th-large me-1"></i>Espaces
                    </span>
                    <i class="fas fa-chevron-right text-white opacity-50 align-self-center"></i>
                    <span class="badge bg-white bg-opacity-25 text-primary px-3 py-2">
                        <i class="fas fa-map-marker-alt me-1"></i>Zones
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Liste des services --}}
<section class="py-5 bg-white">
    <div class="container-lg">

        <h2 class="mb-2 text-center">Nos Services</h2>
        <p class="text-muted text-center mb-5">Sélectionnez une installation pour explorer ses espaces</p>

        @if($services->count() > 0)
            <div class="row g-4">
                @foreach($services as $service)
                    <div class="col-12 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <div class="card-aqua hover-lift">
                            <div class="row g-0">

                                {{-- Photo --}}
                                <div class="col-12 col-md-3 position-relative">
                                    @if($service->photo)
                                        <img src="{{ $service->photo }}"
                                             alt="{{ $service->name }}"
                                             class="img-fluid rounded-start h-100"
                                             style="object-fit: cover; min-height: 220px;">
                                    @else
                                        <div class="bg-primary-lighter rounded-start h-100 d-flex align-items-center justify-content-center text-white"
                                             style="min-height: 220px;">
                                            <i class="fas fa-building" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge badge-success shadow-sm fs-6">
                                            {{ $service->structures_count }}
                                            structure{{ $service->structures_count > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Contenu --}}
                                <div class="col-12 col-md-7">
                                    <div class="p-4">
                                        <h3 class="h4 mb-2">
                                            <a href="{{ route('public.installations.service', $service) }}"
                                               class="text-decoration-none text-dark hover-primary">
                                                {{ $service->name }}
                                            </a>
                                        </h3>

                                        @if($service->address)
                                            <p class="text-muted small mb-3">
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $service->address }}
                                            </p>
                                        @endif

                                        @if($service->capacity)
                                            <p class="text-muted small mb-3">
                                                <i class="fas fa-users me-1"></i>Capacité : {{ number_format($service->capacity) }} personnes
                                            </p>
                                        @endif

                                        @if($service->description)
                                            <div class="text-muted installation-description">
                                                {!! Str::limit(strip_tags($service->description), 200) !!}
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">
                                                Découvrez les structures de {{ $service->name }}.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Bouton --}}
                                <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                                    <div class="p-3 w-100">
                                        <a href="{{ route('public.installations.service', $service) }}"
                                           class="btn btn-primary w-100">
                                            <i class="fas fa-arrow-right me-2"></i>
                                            <span class=" d-lg-inline">Explorer</span>
                                            <span class="d-inline d-lg-none">Explorer les structures</span>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card-aqua text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted">Aucune installation disponible pour le moment</h5>
            </div>
        @endif

    </div>
</section>

@endsection

@push('styles')
<style>
.hero-video {
position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
    border-top: 20px solid #ffffff;
    border-bottom: 20px solid #ffffff;
    border-left: 20px solid #efa525;
    border-right: 20px solid #efa525;
}
.hero-content { z-index: 3; }
.min-vh-50 { min-height: 50vh; }

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-slide-up { animation: slideUp 0.8s ease-out; }
.animation-delay-1 { animation-delay: 0.2s; opacity: 0; animation-fill-mode: forwards; }

.hover-primary { transition: color 0.2s ease; }
.hover-primary:hover { color: #38859b !important; }

.installation-description p { margin-bottom: 0; }

@media (max-width: 768px) {
    .display-3 { font-size: 2rem !important; }
    .rounded-start { border-radius: 0.75rem 0.75rem 0 0 !important; }
}
@media (min-width: 768px) {
    .rounded-start { border-radius: 0.75rem 0 0 0.75rem !important; }
}
</style>
@endpush
