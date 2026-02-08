@extends('layouts.public')

@section('title', 'Vidéos Entraînement Natation & Triathlon - Conseils Hassan EL HAOUAT')
@section('meta_description', 'Banque de vidéos d\'exercices et analyses techniques pour entraîneurs et nageurs. Optimisez la planification et la performance en natation et triathlon.')

@section('content')


<!-- Hero Section avec Video Background -->
<section class="position-relative text-white overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="hero-video">
        <source src="{{ asset('assets/images/team/nataswim-sport-training-2.mp4') }}" type="video/mp4">
    </video>
    <!-- Contenu -->
    <div class="container-lg py-5 position-relative hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-4 animate-slide-up">
                    <h1 class="text-white display-3 fw-bold mb-0">Vidéothèque Thématique</h1>
                </div>
                <p class="lead mb-4 animate-slide-up animation-delay-1">
                    (infos, tutoriels, techniques et plus).
                </p>
            </div>
        </div>
    </div>
</section>





<!-- Navigation par Catégories -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        @if($categories->count() > 0)
            <!-- Boucle sur chaque catégorie -->
            @foreach($categories as $category)
                <div class="mb-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="card-aqua hover-lift">
                        <div class="row g-0">
                            <!-- Image de la catégorie -->
                            <div class="col-12 col-md-3">
                                <div class="position-relative overflow-hidden" style="min-height: 250px;">
                                    @if($category->image)
                                        <img src="{{ $category->image }}" 
                                             alt="{{ $category->name }}"
                                             class="w-100 h-100 object-cover">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white"
                                             style="background: linear-gradient(135deg, 
                                                {{ $loop->index % 4 == 0 ? '#38859b' : ($loop->index % 4 == 1 ? '#4fa79c' : ($loop->index % 4 == 2 ? '#49aaca' : '#3d8993')) }} 0%, 
                                                {{ $loop->index % 4 == 0 ? '#3d8993' : ($loop->index % 4 == 1 ? '#3e8680' : ($loop->index % 4 == 2 ? '#3a92b0' : '#38859b')) }} 100%);">
                                            <i class="fas fa-video" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Contenu central -->
                            <div class="col-12 col-md-7">
                                <div class="p-4">
                                    <!-- Nom de la catégorie -->
                                    <h3 class="title-section mb-3">
                                        <a href="{{ route('public.videos.category', $category) }}" 
                                           class="text-decoration-none text-dark hover-aqua-glow">
                                            {{ $category->name }}
                                        </a>
                                    </h3>

                                    <!-- Description -->
                                    @if($category->description)
                                        <p class="text-muted mb-3">
                                            {{ Str::limit(strip_tags($category->description), 200) }}
                                        </p>
                                    @else
                                        <p class="text-muted mb-3">
                                            Découvrez nos vidéos dans la catégorie {{ $category->name }}.
                                        </p>
                                    @endif

                                    <!-- Badge nombre de vidéos -->
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <span class="badge badge-m2pc badge-materiel" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                            {{ $category->published_videos_count }} vidéo(s)
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton d'action -->
                            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                                <div class="p-3 w-100">
                                    <a href="{{ route('public.videos.category', $category) }}" 
                                       class="btn btn-outline-primary w-100">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        <span class="d-none d-lg-inline">Découvrir</span>
                                        <span class="d-inline d-lg-none">Voir les vidéos</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-video fa-3x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted">Aucune catégorie disponible pour le moment</h5>
            </div>
        @endif
    </div>
</section>

<!-- Les 6 dernières vidéos ajoutées -->
<section class="py-5 bg-secondary">
    <div class="container-lg">
        @if($videos->count() > 0)
        <div class="row g-4">
            @foreach($videos->take(6) as $video)
            <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                <div class="card-aqua h-100 hover-lift">
                    @if($video->thumbnail)
                    <div class="position-relative">
                        <img src="{{ $video->thumbnail }}"
                            class="card-img-top"
                            style="height: 220px; object-fit: cover;"
                            alt="{{ $video->title }}">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <div class="bg-danger bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-play text-white fs-4"></i>
                            </div>
                        </div>
                        @if($video->duration)
                        <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark">
                            {{ $video->getFormattedDuration() }}
                        </span>
                        @endif

                        {{-- Badge Premium --}}
                        @if($video->visibility === 'authenticated')
                        <span class="position-absolute top-0 start-0 m-2 badge badge-warning">
                            <i class="fas fa-crown me-1"></i>Premium
                        </span>
                        @endif

                        {{-- Badge Nouveau --}}
                        @if($video->created_at->diffInDays(now()) < 7)
                        <span class="position-absolute top-0 end-0 m-2 badge badge-success">
                            <i class="fas fa-sparkles me-1"></i>Nouveau
                        </span>
                        @endif
                    </div>
                    @else
                    <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                        style="height: 220px;">
                        <i class="fas fa-video fa-4x text-white opacity-50"></i>
                    </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach($video->categories->take(2) as $category)
                            <span class="badge badge-primary">
                                {{ $category->name }}
                            </span>
                            @endforeach

                            @if($video->visibility === 'authenticated')
                            <span class="badge badge-warning">
                                <i class="fas fa-crown me-1"></i>Premium
                            </span>
                            @endif

                            @if($video->is_featured)
                            <span class="badge badge-success">
                                <i class="fas fa-star me-1"></i>Vedette
                            </span>
                            @endif
                        </div>

                        <h5 class="card-title mb-3">{{ $video->title }}</h5>

                        @if($video->description)
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit(strip_tags($video->description), 120) }}
                        </p>
                        @endif

                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($video->views_count) }} vues
                            </small>
                            <a href="{{ route('public.videos.show', $video) }}"
                                class="btn btn-sm btn-primary">
                                Regarder <i class="fas fa-play ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($videos->count() > 6)
        <div class="text-center mt-5">
            <a href="#all-videos-section" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-chevron-down me-2"></i>Voir toutes les vidéos
            </a>
        </div>
        @endif
        @else
        <div class="card-aqua text-center py-5">
            <i class="fas fa-video fa-3x text-muted mb-3 opacity-25"></i>
            <h5 class="text-muted mb-0">Aucune vidéo disponible pour le moment</h5>
        </div>
        @endif
    </div>
</section>

<!-- Section Top 3 des vidéos les plus vues -->
@if($videos->count() >= 3)
@php
    $topVideos = $videos->sortByDesc('views_count')->take(3);
@endphp
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="title-aqua-secondary">
                <i class="fas fa-trophy me-2"></i>Top 3 des vidéos les plus vues
            </h2>
            <p class="text-muted">Les vidéos favorites de notre communauté</p>
        </div>

        <div class="row g-4">
            @foreach($topVideos as $topVideo)
            <div class="col-md-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.15 }}s;">
                <div class="card-aqua-icon h-100 hover-scale position-relative">
                    <!-- Badge position -->
                    <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                        <div class="badge fs-5 px-3 py-2 shadow-lg
                            @if($loop->first) bg-warning @elseif($loop->iteration === 2) bg-secondary @else @endif"
                            @if($loop->iteration === 3) style="background-color: #cd7f32 !important; color: white !important;" @endif>
                            <i class="fas fa-trophy me-1"></i>#{{ $loop->iteration }}
                        </div>
                    </div>

                    @if($topVideo->thumbnail)
                    <div class="position-relative">
                        <img src="{{ $topVideo->thumbnail }}"
                            class="card-img-top"
                            style="height: 250px; object-fit: cover;"
                            alt="{{ $topVideo->title }}">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <div class="bg-danger bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px;">
                                <i class="fas fa-play text-white fs-3"></i>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="card-content p-4">
                        <h5 class="card-title mb-3 fw-bold">{{ $topVideo->title }}</h5>

                        @if($topVideo->description)
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit(strip_tags($topVideo->description), 100) }}
                        </p>
                        @endif

                        <div class="d-flex align-items-center justify-content-between border-top pt-3">
                            <div class="badge badge-info px-3 py-2">
                                <i class="fas fa-eye me-1"></i>{{ number_format($topVideo->views_count) }} vues
                            </div>
                            <a href="{{ route('public.videos.show', $topVideo) }}"
                                class="btn btn-sm btn-primary">
                                Regarder
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Toutes les vidéos (si plus de 6) -->
@if($videos->count() > 6)
<section class="py-5 bg-white" id="all-videos-section">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="title-aqua-secondary">
                <i class="fas fa-th me-2"></i>Toutes les vidéos
            </h2>
        </div>

        <div class="row g-4">
            @foreach($videos as $video)
            <div class="col-md-6 col-lg-4">
                <div class="card-aqua h-100 hover-lift">
                    @if($video->thumbnail)
                    <div class="position-relative">
                        <img src="{{ $video->thumbnail }}"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover;"
                            alt="{{ $video->title }}">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <div class="bg-danger bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-play text-white fs-5"></i>
                            </div>
                        </div>
                        @if($video->duration)
                        <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark">
                            {{ $video->getFormattedDuration() }}
                        </span>
                        @endif

                        {{-- Badge Premium --}}
                        @if($video->visibility === 'authenticated')
                        <span class="position-absolute top-0 start-0 m-2 badge badge-warning">
                            <i class="fas fa-crown me-1"></i>Premium
                        </span>
                        @endif
                    </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($video->title, 60) }}</h5>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($video->views_count) }}
                            </small>
                            <a href="{{ route('public.videos.show', $video) }}"
                                class="btn btn-sm btn-primary">
                                Regarder
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($videos->hasPages())
            <div class="row mt-5">
                <div class="col-12">
                    <div class="mt-5">
                        {{ $videos->links('pagination.five-per-row') }}
                    </div>
                </div>
            </div>
        @endif
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