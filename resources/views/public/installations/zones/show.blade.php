@extends('layouts.public')

@section('title', $zone->name . ' — ' . $zone->espace->name)
@section('meta_description', strip_tags($zone->description ?? 'Découvrez la zone ' . $zone->name))

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
                        <a href="{{ route('public.installations.service', $zone->espace->structure->service) }}"
                           class="text-white opacity-75">{{ $zone->espace->structure->service->name }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('public.installations.structure', $zone->espace->structure) }}"
                           class="text-white opacity-75">{{ $zone->espace->structure->name }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('public.installations.espace', $zone->espace) }}"
                           class="text-white opacity-75">{{ $zone->espace->name }}</a>
                    </li>
                    <li class="breadcrumb-item active text-white">{{ $zone->name }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3 mb-2">
                <i class="fas fa-map-marker-alt fa-2x text-white opacity-75"></i>
                <h1 class="text-white mb-0">{{ $zone->name }}</h1>
            </div>
            <p class="text-white opacity-75 mb-0 small">
                <i class="fas fa-th-large me-1"></i>{{ $zone->espace->name }}
                <i class="fas fa-chevron-right mx-2 opacity-50"></i>
                <i class="fas fa-layer-group me-1"></i>{{ $zone->espace->structure->name }}
                <i class="fas fa-chevron-right mx-2 opacity-50"></i>
                <i class="fas fa-building me-1"></i>{{ $zone->espace->structure->service->name }}
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
                @if($zone->photo)
                    <div class="mb-4">
                        <img src="{{ $zone->photo }}"
                             alt="{{ $zone->name }}"
                             class="img-fluid rounded shadow-aqua w-100"
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                {{-- Description --}}
                @if($zone->description)
                    <div class="card-aqua mb-4">
                        <div class="content-display">
                            {!! $zone->description !!}
                        </div>
                    </div>
                @else
                    <div class="card-aqua mb-4 text-center py-4">
                        <i class="fas fa-map-marker-alt fa-2x text-warning mb-2 opacity-50"></i>
                        <p class="text-muted mb-0">Aucune description disponible pour cette zone.</p>
                    </div>
                @endif

                {{-- Chemin hiérarchique visuel --}}
                <div class="card-aqua">
                    <h5 class="mb-4">
                        <i class="fas fa-sitemap me-2 text-primary"></i>Situation dans l'installation
                    </h5>
                    <div class="d-flex align-items-center flex-wrap gap-2">

                        <a href="{{ route('public.installations.service', $zone->espace->structure->service) }}"
                           class="text-decoration-none">
                            <div class="hierarchy-badge bg-primary text-white px-3 py-2 rounded-3">
                                <div class="small opacity-75">Service</div>
                                <div class="fw-semibold">{{ $zone->espace->structure->service->name }}</div>
                            </div>
                        </a>

                        <i class="fas fa-chevron-right text-muted"></i>

                        <a href="{{ route('public.installations.structure', $zone->espace->structure) }}"
                           class="text-decoration-none">
                            <div class="hierarchy-badge bg-success text-white px-3 py-2 rounded-3">
                                <div class="small opacity-75">Structure</div>
                                <div class="fw-semibold">{{ $zone->espace->structure->name }}</div>
                            </div>
                        </a>

                        <i class="fas fa-chevron-right text-muted"></i>

                        <a href="{{ route('public.installations.espace', $zone->espace) }}"
                           class="text-decoration-none">
                            <div class="hierarchy-badge bg-warning text-dark px-3 py-2 rounded-3">
                                <div class="small opacity-75">Espace</div>
                                <div class="fw-semibold">{{ $zone->espace->name }}</div>
                            </div>
                        </a>

                        <i class="fas fa-chevron-right text-muted"></i>

                        <div class="hierarchy-badge border-2 border-danger text-danger px-3 py-2 rounded-3">
                            <div class="small opacity-75">Zone</div>
                            <div class="fw-semibold">{{ $zone->name }}</div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">

                {{-- Infos --}}
                <div class="card-aqua mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informations
                    </h5>
                    <ul class="list-unstyled mb-0">
                        @if($zone->capacity)
                            <li class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                                <i class="fas fa-users text-primary"></i>
                                <div>
                                    <div class="text-muted small">Capacité</div>
                                    <div class="fw-semibold">{{ number_format($zone->capacity) }} personnes</div>
                                </div>
                            </li>
                        @endif
                        <li class="d-flex align-items-start gap-2 mb-3 pb-3 border-bottom">
                            <i class="fas fa-th-large text-warning mt-1"></i>
                            <div>
                                <div class="text-muted small">Espace</div>
                                <a href="{{ route('public.installations.espace', $zone->espace) }}"
                                   class="text-warning fw-semibold text-decoration-none">
                                    {{ $zone->espace->name }}
                                </a>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3 pb-3 border-bottom">
                            <i class="fas fa-layer-group text-success mt-1"></i>
                            <div>
                                <div class="text-muted small">Structure</div>
                                <a href="{{ route('public.installations.structure', $zone->espace->structure) }}"
                                   class="text-success fw-semibold text-decoration-none">
                                    {{ $zone->espace->structure->name }}
                                </a>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="fas fa-building text-primary mt-1"></i>
                            <div>
                                <div class="text-muted small">Service</div>
                                <a href="{{ route('public.installations.service', $zone->espace->structure->service) }}"
                                   class="text-primary fw-semibold text-decoration-none">
                                    {{ $zone->espace->structure->service->name }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Navigation --}}
                <div class="card-aqua">
                    <div class="d-grid gap-2">
                        <a href="{{ route('public.installations.espace', $zone->espace) }}"
                           class="btn btn-warning text-dark">
                            <i class="fas fa-th-large me-2"></i>{{ $zone->espace->name }}
                        </a>
                        <a href="{{ route('public.installations.structure', $zone->espace->structure) }}"
                           class="btn btn-outline-success">
                            <i class="fas fa-layer-group me-2"></i>{{ $zone->espace->structure->name }}
                        </a>
                        <a href="{{ route('public.installations.service', $zone->espace->structure->service) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-building me-2"></i>{{ $zone->espace->structure->service->name }}
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
.hierarchy-badge { display: inline-block; min-width: 120px; }
.content-display h1, .content-display h2, .content-display h3 { margin-top: 1.5rem; margin-bottom: 0.75rem; font-weight: 600; }
.content-display h1 { font-size: 1.7rem; color: #38859b; }
.content-display h2 { font-size: 1.5rem; color: #2f80b8; }
.content-display h3 { font-size: 1.3rem; color: #2f80b8; }
.content-display p  { margin-bottom: 1.25rem; line-height: 1.8; color: #4a5568; }
.content-display ul, .content-display ol { margin-bottom: 1.25rem; padding-left: 2rem; line-height: 1.7; }
.content-display li { margin-bottom: 0.4rem; }
</style>
@endpush
