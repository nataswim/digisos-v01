@extends('layouts.public')

@section('title', 'Plateforme Digital\'SOS')
@section('meta_description', 'Decouvrez notre plateforme dediee A la natation et au triathlon avec articles, plans d\'entrainement, fiches techniques et videos. Rejoignez notre communaute de nageurs, triathletes et coachs.')

@section('content')
<!-- Hero Section avec Video Background -->
<section class="hero-video-section position-relative text-white overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="hero-video">
        <source src="{{ asset('assets/images/team/nataswim.mp4') }}" type="video/mp4">
    </video>


    <!-- Contenu -->
    <div class="container-lg py-5 position-relative hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-4 animate-slide-up">
                    <i class="fas fa-swimmer me-3 hero-icon"></i>
                    <h1 class="display-3 fw-bold mb-0 text-white">Digital'SOS</h1>
                </div>

                <p class="lead mb-4 animate-slide-up animation-delay-1">
                    Optimisez vos entraînements, développez vos connaissances et formez-vous en continu grâce à cette plateforme dédiée aux sportifs, techniciens, préparateurs physiques, entraîneurs et coachs — du débutant au professionnel.
                </p>

                <div class="d-flex gap-3 flex-wrap animate-slide-up animation-delay-2">
                    <a href="#content-sections" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-down me-2"></i>Découvrir
                    </a>
                    <a href="{{ route('posts.public.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-book-open me-2"></i>Nos contenus
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center animate-fade-in animation-delay-3">
                <div class="hero-logo-wrapper">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/team/nataswim_app_logo_2.png') }}"
                             alt="nataswim application pour tous"
                             class="hero-logo img-fluid">
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>






<!-- Section Articles -->
<section class="py-5 bg-aqua-light" id="content-sections">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="title-aqua-secondary">
                <i class="fas fa-newspaper me-2"></i>Articles Récents
            </h2>
            <p class="text-muted">Découvrez nos dernières publications sur la natation et le triathlon</p>
        </div>

        <div class="row g-4 mb-4">
            @php
            $recentArticles = App\Models\Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();
            @endphp

            @forelse($recentArticles as $article)
            <div class="col-md-6 col-lg-3">
                <div class="card-aqua h-100">
                    <div class="card-image-wrapper mb-3">
                        @if($article->image)
                        <img src="{{ $article->image }}"
                            class="card-image"
                            alt="{{ $article->name }}">
                        @else
                        <div class="card-image-placeholder">
                            <i class="fas fa-newspaper fa-3x text-primary opacity-25"></i>
                        </div>
                        @endif
                    </div>

                    <div class="card-meta mb-2">
                        <span class="badge badge-primary">
                            {{ $article->category->name ?? 'Non catégorisé' }}
                        </span>
                    </div>

                    <h6 class="card-title mb-2">
                        <a href="{{ route('posts.public.show', $article) }}"
                            class="text-decoration-none text-dark hover-primary">
                            {!! Str::limit($article->name, 50) !!}
                        </a>
                    </h6>

                    @if($article->intro)
                    <p class="card-text text-muted small mb-3">
                        {!! Str::limit(strip_tags($article->intro), 80) !!}
                    </p>
                    @endif

                    <div class="card-footer-info mt-auto">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>{{ $article->hits }}
                        </small>
                        <small class="text-muted">
                            {{ $article->published_at->format('d/m/Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-newspaper fa-3x mb-3 opacity-25"></i>
                    <p>Aucun article publié récemment</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="text-center">
            <a href="{{ route('posts.public.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-right me-2"></i>Voir tous les articles
            </a>
        </div>
    </div>
</section>




<!-- Section Vidéos -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="title-aqua-secondary">
                <i class="fas fa-video me-2"></i>Vidéos
            </h2>
            <p class="text-muted">Tutoriels vidéo et démonstrations techniques</p>
        </div>

        <div class="row g-4 mb-4">
            @php
            $recentVideos = App\Models\Video::query()
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
            @endphp

            @forelse($recentVideos as $video)
            <div class="col-md-6 col-lg-3">
                <div class="card-aqua h-100">
                    <div class="card-image-wrapper mb-3 position-relative">
                        @if($video->thumbnail)
                        <img src="{{ $video->thumbnail }}"
                            class="card-image"
                            alt="{{ $video->title }}">
                        @else
                        <div class="card-image-placeholder">
                            <i class="fas fa-video fa-3x text-info opacity-25"></i>
                        </div>
                        @endif
                        <div class="video-play-overlay">
                            <i class="fas fa-play-circle"></i>
                        </div>
                    </div>

                    <h6 class="card-title mb-2">
                        <a href="{{ route('public.videos.show', $video) }}"
                            class="text-decoration-none text-dark hover-primary">
                            {!! Str::limit($video->title, 50) !!}
                        </a>
                    </h6>

                    @if($video->description)
                    <p class="card-text text-muted small mb-3">
                        {!! Str::limit(strip_tags($video->description), 80) !!}
                    </p>
                    @endif

                    <div class="card-footer-info mt-auto">
                        <span class="badge badge-info">
                            <i class="fas fa-play me-1"></i>Vidéo
                        </span>
                        <a href="{{ route('public.videos.show', $video) }}"
                            class="btn btn-sm btn-outline-primary">
                            Voir
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-video fa-3x mb-3 opacity-25"></i>
                    <p>Aucune vidéo disponible</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="text-center">
            <a href="{{ route('public.videos.index') }}" class="btn btn-info btn-lg">
                <i class="fas fa-arrow-right me-2"></i>Voir toutes les vidéos
            </a>
        </div>
    </div>
</section>


<!-- Section eBooks -->
<section class="py-5">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="title-aqua-secondary">
                <i class="fas fa-book me-2"></i>eBooks & Téléchargements
            </h2>
            <p class="text-muted">Ressources téléchargeables pour approfondir vos connaissances</p>
        </div>

        <div class="row g-4 mb-4">
            @php
            $recentDownloads = App\Models\Downloadable::query()
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
            @endphp

            @forelse($recentDownloads as $download)
            <div class="col-md-6 col-lg-3">
                <div class="card-aqua h-100">
                    <div class="card-image-wrapper mb-3">
                        @if($download->image)
                        <img src="{{ $download->image }}"
                            class="card-image"
                            alt="{{ $download->title }}">
                        @else
                        <div class="card-image-placeholder">
                            <i class="fas fa-book fa-3x text-success opacity-25"></i>
                        </div>
                        @endif
                    </div>

                    <div class="card-meta mb-2">
                        <span class="badge badge-success">
                            {{ $download->category->name ?? 'Téléchargement' }}
                        </span>
                    </div>

                    <h6 class="card-title mb-2">
                        <a href="{{ route('ebook.show', [$download->category, $download]) }}"
                            class="text-decoration-none text-dark hover-primary">
                            {!! Str::limit($download->title, 50) !!}
                        </a>
                    </h6>

                    @if($download->description)
                    <p class="card-text text-muted small mb-3">
                        {!! Str::limit(strip_tags($download->description), 80) !!}
                    </p>
                    @endif

                    <div class="card-footer-info mt-auto">
                        <small class="text-muted">
                            <i class="fas fa-download me-1"></i>{{ $download->downloads_count ?? 0 }}
                        </small>
                        <a href="{{ route('ebook.show', [$download->category, $download]) }}"
                            class="btn btn-sm btn-outline-primary">
                            Télécharger
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-book fa-3x mb-3 opacity-25"></i>
                    <p>Aucun téléchargement disponible</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="text-center">
            <a href="{{ route('ebook.index') }}" class="btn btn-success btn-lg">
                <i class="fas fa-arrow-right me-2"></i>Voir tous les téléchargements
            </a>
        </div>
    </div>
</section>


@endsection

@push('styles')
<style>
/* ============================================================================
   HERO VIDEO SECTION
   ============================================================================ */
.hero-video-section {
    min-height: 400px;
    position: relative;
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

.hero-logo-wrapper {
    position: relative;
    display: inline-block;
    background: white;
    border-radius: 50%;
    padding: 1rem;
    box-shadow: 0 0 40px rgba(255, 255, 255, 0.8), 0 0 10px rgba(255, 255, 255, 1);
}

.hero-logo {
    max-width: 200px;
    height: auto;
    border-radius: 50%;
    transition: transform 0.3s ease;
}

.hero-logo:hover {
    transform: scale(1.05);
}

.btn-outline-light {
    background: transparent;
    color: white;
    border: 2px solid white;
    transition: all 0.3s ease;
}

.btn-outline-light:hover {
    background: white;
    color: #1db8c5;
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

/* Video play overlay */
.video-play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 3rem;
    color: white;
    opacity: 0.8;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.card-aqua:hover .video-play-overlay {
    opacity: 1;
}

/* ============================================================================
   RESPONSIVE
   ============================================================================ */
@media (max-width: 768px) {
    .hero-video-section {
        min-height: 500px;
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

    .hero-logo-wrapper {
        padding: 0.5rem;
    }

    .hero-logo {
        max-width: 150px;
    }

    .btn-lg {
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
    }
}

/* ============================================================================
   SMOOTH SCROLL
   ============================================================================ */
html {
    scroll-behavior: smooth;
}
</style>
@endpush