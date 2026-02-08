@extends('layouts.public')

@section('title', 'Espace Telechargement - Ressources et eBooks')
@section('meta_description', 'Decouvrez notre collection de ressources telechargeables : eBooks, guides, videos et documents pour votre developpement personnel et professionnel.')

@section('content')



<!-- Hero Section avec Video Background -->
<section class="position-relative text-white overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="hero-video">
        <source src="{{ asset('assets/images/team/ebooks.mp4') }}" type="video/mp4">
    </video>
    <!-- Contenu -->
    <div class="container-lg py-5 position-relative hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-4 animate-slide-up">
                    <h1 class="text-white display-3 fw-bold mb-0">Ressources Thématique</h1>
                </div>
                <p class="lead mb-4 animate-slide-up animation-delay-1">
                    guides pratiques et documents.
                </p>
            </div>
        </div>
    </div>
</section>


{{-- Section Telechargements Vedettes --}}
@if($featuredDownloads->count() > 0)
<section id="featured" class="section bg-aqua-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="title-aqua-secondary">A la une</h2>
            </div>
        </div>

        <div class="row g-4">
            @foreach($featuredDownloads as $download)
                <div class="col-lg-4 col-md-6 fade-in-up">
                    <div class="card-aqua h-100">
                        <div class="position-relative">
                            @if($download->cover_image)
                                <img src="{{ $download->cover_image }}" 
                                     class="w-100 rounded-t-lg" 
                                     style="height: 200px; object-fit: cover;"
                                     alt="{{ $download->title }}">
                            @else
                                <div class="bg-primary-subtle d-flex align-items-center justify-content-center rounded-t-lg" 
                                     style="height: 200px;">
                                    <i class="fas fa-file-{{ $download->format === 'pdf' ? 'pdf' : ($download->format === 'mp4' ? 'video' : 'alt') }} fa-3x text-primary"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge-m2pc badge-materiel">{{ strtoupper($download->format) }}</span>
                            </div>
                            
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 text-white px-2 py-1 rounded">
                                    <i class="fas fa-download me-1"></i>{{ number_format($download->download_count) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <div class="mb-2">
                                <span class="badge badge-primary">
                                    {{ $download->category->name }}
                                </span>
                            </div>
                            <h5 class="fw-bold mb-3">{{ $download->title }}</h5>
                            @if($download->short_description)
                                <p class="text-muted flex-grow-1 text-sm">
                                    {!! Str::limit($download->short_description, 120) !!}
                                </p>
                            @endif
                            
                            <div class="mt-auto">
                                @if($download->canBeDownloadedBy(auth()->user()))
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}" 
                                           class="btn btn-outline-primary hover-lift">
                                            <i class="fas fa-eye me-2"></i>Voir les details
                                        </a>
                                    </div>
                                @else
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}" 
                                           class="btn btn-outline-primary hover-lift">
                                            <i class="fas fa-eye me-2"></i>Voir les details
                                        </a>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-lock me-1"></i>
                                                {{ $download->getAccessMessage(auth()->user()) }}
                                            </small>
                                        </div>
                                    </div>
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

{{-- Section Categories --}}
<section id="categories" class="section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="title-aqua-secondary">Explorez nos categories</h2>
            </div>
        </div>

        @if($categories->count() > 0)
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-lg-4 col-md-6 fade-in-up">
                        <div class="card-aqua-icon h-100 text-center">
                            @if($category->icon)
                                <div class="card-icon">
                                    <i class="{{ $category->icon }}"></i>
                                </div>
                            @endif
                            <h5 class="card-title">{{ $category->name }}</h5>
                            @if($category->short_description)
                                <p class="card-description">{{ $category->short_description }}</p>
                            @endif
                            <div class="mb-3">
                                <span class="badge badge-primary">
                                    {{ $category->downloadables_count }} ressource(s)
                                </span>
                            </div>
                            <a href="{{ route('ebook.category', $category->slug) }}" 
                               class="btn btn-outline-primary hover-lift">
                                Explorer ce rayon
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                <h4>Aucune categorie disponible</h4>
                <p class="text-muted">Les categories seront bientot disponibles.</p>
            </div>
        @endif
    </div>
</section>

{{-- Section Telechargements Recents --}}
@if($recentDownloads->count() > 0)
<section class="section bg-aqua-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="title-aqua-secondary">Dernieres ressources</h2>
            </div>
        </div>

        <div class="row g-4">
            @foreach($recentDownloads as $download)
                <div class="col-lg-3 col-md-6 fade-in-up">
                    <div class="card-aqua h-100">
                        <div class="position-relative">
                            @if($download->cover_image)
                                <img src="{{ $download->cover_image }}" 
                                     class="w-100 rounded-t-lg" 
                                     style="height: 150px; object-fit: cover;"
                                     alt="{{ $download->title }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded-t-lg" 
                                     style="height: 150px;">
                                    <i class="fas fa-file-{{ $download->format === 'pdf' ? 'pdf' : ($download->format === 'mp4' ? 'video' : 'alt') }} fa-2x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge badge-info">{{ strtoupper($download->format) }}</span>
                            </div>
                        </div>
                        
                        <div class="p-3">
                            <div class="mb-2">
                                <span class="badge badge-primary text-xs">
                                    {{ $download->category->name }}
                                </span>
                            </div>
                            <h6 class="fw-bold mb-3">{!! Str::limit($download->title, 50) !!}</h6>
                            
                            <div class="d-grid">
                                <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}" 
                                   class="btn btn-sm btn-outline-primary hover-lift">
                                    <i class="fas fa-eye me-2"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('ebook.search') }}" class="btn btn-primary btn-lg hover-lift">
                <i class="fas fa-search me-2"></i>Voir toutes les ressources
            </a>
        </div>
    </div>
</section>
@endif

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