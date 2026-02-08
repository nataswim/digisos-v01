@extends('layouts.public')

@section('title', 'Fiches Pratiques')
@section('meta_description', 'Découvrez nos fiches pratiques organisées par thématique pour optimiser votre entraînement et performance sportive.')

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
                    <h1 class="text-white display-3 fw-bold mb-0">Fiches Thématique</h1>
                </div>
                <p class="lead mb-4 animate-slide-up animation-delay-1">
                    Retrouvez less dernières fiches.
                </p>
            </div>
        </div>
    </div>
</section>



<!-- Fiches en vedette -->
@if($featuredFiches->count() > 0)
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <h2 class="text-white-secondary mb-5 text-center">
            <i class="fas fa-star text-warning me-2"></i>Fiches en Vedette
        </h2>
        
        <div class="row g-4">
            @foreach($featuredFiches as $fiche)
            <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.15 }}s;">
                <div class="card-aqua h-100 hover-lift">
                    @if($fiche->image)
                    <img src="{{ $fiche->image }}" 
                         class="card-img-top rounded-top" 
                         style="height: 220px; object-fit: cover;"
                         alt="{{ $fiche->title }}">
                    @else
                    <div class="card-img-top bg-primary-lighter rounded-top d-flex align-items-center justify-content-center" 
                         style="height: 220px;">
                        <i class="fas fa-file-alt fa-4x text-primary opacity-50"></i>
                    </div>
                    @endif
                    
                    <div class="d-flex flex-column p-4">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($fiche->category)
                            <span class="badge badge-primary">
                                <i class="fas fa-folder me-1"></i>{{ $fiche->category->name }}
                            </span>
                            @endif
                            @if($fiche->visibility === 'authenticated')
                            <span class="badge badge-warning">
                                <i class="fas fa-lock me-1"></i>Membres
                            </span>
                            @endif
                            <span class="badge badge-success">
                                <i class="fas fa-star me-1"></i>En vedette
                            </span>
                        </div>
                        
                        <h5 class="mb-3">{{ $fiche->title }}</h5>
                        
                        <p class="text-muted flex-grow-1">
                            {!! Str::limit(strip_tags($fiche->short_description), 120) !!}
                        </p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($fiche->views_count ?? 0) }} lectures
                            </small>
                            @if($fiche->category && $fiche->sousCategory)
                            <a href="{{ route('public.fiches.show', [$fiche->category, $fiche->sousCategory, $fiche]) }}" 
                               class="btn btn-sm btn-primary">
                                Découvrir <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i>Indisponible
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Catégories -->
<section class="py-5 {{ $featuredFiches->count() > 0 ? 'bg-white' : 'bg-aqua-light' }}">
    <div class="container-lg">
        <h2 class="text-white-secondary mb-5 text-center">Catégories de Fiches</h2>
        
        @if($categories->count() > 0)
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-12 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                <div class="card-aqua hover-lift">
                    <div class="row g-0">
                        <!-- Image -->
                        <div class="col-12 col-md-3">
                            @if($category->image)
                            <img src="{{ $category->image }}" 
                                 alt="{{ $category->name }}"
                                 class="img-fluid rounded-start h-100"
                                 style="object-fit: cover; min-height: 200px;">
                            @else
                            <div class="bg-primary-lighter rounded-start h-100 d-flex align-items-center justify-content-center text-white"
                                 style="min-height: 200px;">
                                <i class="fas fa-folder" style="font-size: 3rem;"></i>
                            </div>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge badge-success shadow-sm fs-6">
                                    {{ $category->published_fiches_count }} fiche{{ $category->published_fiches_count > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>

                        <!-- Contenu -->
                        <div class="col-12 col-md-7">
                            <div class="p-4">
                                <h3 class="h4 mb-3">
                                    <a href="{{ route('public.fiches.category', $category) }}" 
                                       class="text-decoration-none text-dark hover-primary">
                                        {{ $category->name }}
                                    </a>
                                </h3>

                                @if($category->description)
                                <p class="text-muted mb-0">
                                    {!! Str::limit(strip_tags($category->description), 180) !!}
                                </p>
                                @else
                                <p class="text-muted mb-0">
                                    Découvrez nos fiches pratiques dans la catégorie {{ $category->name }}.
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Bouton -->
                        <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                            <div class="p-3 w-100">
                                <a href="{{ route('public.fiches.category', $category) }}" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    <span class="d-none d-lg-inline">Découvrir</span>
                                    <span class="d-inline d-lg-none">Découvrir les fiches</span>
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
            <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
            <h5 class="text-muted">Aucune catégorie disponible pour le moment</h5>
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