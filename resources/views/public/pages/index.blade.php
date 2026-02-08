@extends('layouts.public')

@section('title', 'Pages Informatives')
@section('meta_description', 'Découvrez nos pages informatives organisées par thématique.')

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
                    <h1 class="text-white display-3 fw-bold mb-0">Pages Informatives</h1>
                </div>
                <p class="lead mb-4 animate-slide-up animation-delay-1">
                    Retrouvez toutes nos pages thématiques.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Catégories -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <h2 class="text-white-secondary mb-5 text-center">Catégories de Pages</h2>
        
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
                                    {{ $category->published_pages_count }} page{{ $category->published_pages_count > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-7">
                            <div class="p-4">
                                <h3 class="h4 mb-3">
                                    <a href="{{ route('public.pages.category', $category) }}" 
                                       class="text-decoration-none text-dark hover-primary">
                                        {{ $category->name }}
                                    </a>
                                </h3>

                                @if($category->description)
                                <div class="category-description text-muted mb-0">
                                    {!! Str::limit($category->description, 250) !!}
                                </div>
                                @else
                                <p class="text-muted mb-0">
                                    Découvrez nos pages dans la catégorie {{ $category->name }}.
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Bouton -->
                        <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                            <div class="p-3 w-100">
                                <a href="{{ route('public.pages.category', $category) }}" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    <span class="d-none d-lg-inline">Découvrir</span>
                                    <span class="d-inline d-lg-none">Découvrir les pages</span>
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

.hero-content {
    z-index: 3;
}

.min-vh-50 {
    min-height: 50vh;
}

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

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.animation-delay-1 {
    animation-delay: 0.2s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.hover-primary {
    transition: color 0.2s ease;
}

.hover-primary:hover {
    color: #38859b !important;
}

@media (max-width: 768px) {
    .display-3 {
        font-size: 2rem !important;
    }
}

@media (min-width: 768px) {
    .rounded-start {
        border-radius: 0.75rem 0 0 0.75rem !important;
    }
}

/* Styles pour la description des catégories */
.category-description {
    font-size: 0.95rem;
    line-height: 1.6;
}

.category-description p {
    margin-bottom: 0.5rem;
}

.category-description strong {
    font-weight: 600;
}

.category-description em {
    font-style: italic;
}

.category-description ul,
.category-description ol {
    margin: 0.5rem 0;
    padding-left: 1.5rem;
}

.category-description li {
    margin-bottom: 0.25rem;
}
</style>
@endpush
</document_content>
