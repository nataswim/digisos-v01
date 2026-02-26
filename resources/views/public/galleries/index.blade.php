@extends('layouts.public')

@section('title', 'Galeries photos')

@section('meta_description', 'Découvrez nos galeries photos')

@section('content')
<!-- Hero Section avec Video Background -->
<section class="position-relative text-white overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="hero-video">
        <source src="{{ asset('assets/images/team/nataswim-sport-training-0.mp4') }}" type="video/mp4">
    </video>
    <!-- Contenu -->
    <div class="container-lg py-5 position-relative hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-4 animate-slide-up">
                    <h1 class="text-white display-3 fw-bold mb-0">Galeries</h1>
                </div>
                <p class="lead mb-4 animate-slide-up animation-delay-1">
                    Découvrez nos collections de photos
                </p>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">

    {{-- Barre de recherche --}}
    <div class="row justify-content-center mb-5">
        <div class="col-lg-6">
            <form method="GET" action="{{ route('galleries.index') }}">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}"
                           class="form-control border-start-0" 
                           placeholder="Rechercher une galerie...">
                    <button class="btn btn-primary" type="submit">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Galeries en avant --}}
    @if($featuredGalleries->count() > 0 && !$search)
        <div class="mb-5">
            <h2 class="h3 fw-bold mb-4">
                <i class="fas fa-star text-warning me-2"></i>
                Galeries en vedette
            </h2>
            
            <div class="row g-4">
                @foreach($featuredGalleries as $gallery)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('galleries.show', $gallery) }}" 
                           class="card border-0 shadow-sm h-100 text-decoration-none hover-lift">
                            @if($gallery->coverImage)
                                <img src="{{ $gallery->coverImage->url }}" 
                                     alt="{{ $gallery->title }}"
                                     class="card-img-top"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height: 250px;">
                                    <i class="fas fa-images fa-4x text-muted opacity-50"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>En vedette
                                </span>
                            </div>
                            
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-dark bg-opacity-75">
                                    <i class="fas fa-images me-1"></i>{{ $gallery->photos_count }} photos
                                </span>
                            </div>
                            
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-2">{{ $gallery->title }}</h5>
                                @if($gallery->description)
                                    <p class="card-text text-muted">
                                        {{ Str::limit($gallery->description, 100) }}
                                    </p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $gallery->published_at->format('d/m/Y') }}
                                    </small>
                                    <span class="text-primary">
                                        Voir la galerie
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Toutes les galeries --}}
    <div>


        @if($galleries->count() > 0)
            <div class="row g-4">
                @foreach($galleries as $gallery)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('galleries.show', $gallery) }}" 
                           class="card border-0 shadow-sm h-100 text-decoration-none hover-lift">
                            @if($gallery->coverImage)
                                <img src="{{ $gallery->coverImage->url }}" 
                                     alt="{{ $gallery->title }}"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="fas fa-images fa-3x text-muted opacity-50"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-dark bg-opacity-75">
                                    <i class="fas fa-images me-1"></i>{{ $gallery->photos_count }}
                                </span>
                            </div>
                            
                            @if($gallery->visibility === 'authenticated')
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-lock me-1"></i>Membres
                                    </span>
                                </div>
                            @endif
                            
                            <div class="card-body p-3">
                                <h6 class="card-title fw-bold mb-1">{{ $gallery->title }}</h6>
                                @if($gallery->description)
                                    <p class="card-text text-muted small mb-2">
                                        {{ Str::limit($gallery->description, 60) }}
                                    </p>
                                @endif
                                <small class="text-muted">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $gallery->published_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($galleries->hasPages())
                <div class="mt-5">
                    {{ $galleries->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-images fa-4x text-muted opacity-50 mb-4"></i>
                <h5 class="text-muted">Aucune galerie trouvée</h5>
                @if($search)
                    <p class="text-muted mb-4">Aucun résultat pour "{{ $search }}"</p>
                    <a href="{{ route('galleries.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Voir toutes les galeries
                    </a>
                @else
                    <p class="text-muted">Aucune galerie n'est disponible pour le moment.</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
}


.hero-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
    border-top: 20px solid #4097b5;
    border-bottom: 20px solid #4097b5;
    border-left: 20px solid #f9f5f4;
    border-right: 20px solid #f9f5f4;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(56, 133, 155, 0.85) 0%, rgba(73, 170, 202, 0.75) 100%);
    z-index: 2;
}

.hero-content {
    z-index: 3;
}

.min-vh-50 {
    min-height: 50vh;
}

.hero-icon {
    font-size: 3rem;
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.2));
}

/* ============================================================================
   ANIMATIONS
   ============================================================================ */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animation-delay-1 {
    animation-delay: 0.2s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animation-delay-2 {
    animation-delay: 0.4s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animation-delay-3 {
    animation-delay: 0.6s;
    opacity: 0;
    animation-fill-mode: forwards;
}

/* ============================================================================
   CARD COMPONENTS
   ============================================================================ */
.card-image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 0.75rem;
    height: 180px;
    background: linear-gradient(135deg, rgba(56, 133, 155, 0.05) 0%, rgba(73, 170, 202, 0.05) 100%);
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card-aqua:hover .card-image {
    transform: scale(1.05);
}

.card-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(56, 133, 155, 0.05) 0%, rgba(73, 170, 202, 0.05) 100%);
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.card-footer-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid rgba(56, 133, 155, 0.1);
}

.hover-primary {
    transition: color 0.2s ease;
}

.hover-primary:hover {
    color: #1db8c5 !important;
}

/* ============================================================================
   RESPONSIVE
   ============================================================================ */
@media (max-width: 768px) {
    .hero-video-section {
        min-height: 400px;
    }

    .hero-icon {
        font-size: 2rem;
    }

    .display-3 {
        font-size: 2rem !important;
    }

    .lead {
        font-size: 1rem;
    }
}

/* ============================================================================
   SMOOTH SCROLL
   ============================================================================ */
html {
    scroll-behavior: smooth;
}
.hover-primary:hover {
    color: #38859b !important;
}

.position-relative {
    position: relative;
}

@media (min-width: 768px) {
    .rounded-start {
        border-radius: 0.75rem 0 0 0.75rem !important;
    }
}


</style>
@endpush
