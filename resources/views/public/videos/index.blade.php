@extends('layouts.public')

@section('title', 'Vidéos Entraînement Natation & Triathlon - Conseils Hassan EL HAOUAT')
@section('meta_description', 'Banque de vidéos d\'exercices et analyses techniques pour entraîneurs et nageurs. Optimisez la planification et la performance en natation et triathlon.')

@section('content')

<section class="position-relative text-white py-5 nataswim-titre3 overflow-hidden" style="min-height: 600px;">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 1;">
        <source src="{{ asset('assets/images/team/nataswim-sport-training-2.mp4') }}" type="video/mp4">
    </video>

    <!-- Overlay sombre pour meilleure lisibilité -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;"></div>

    <!-- Contenu -->
    <div class="container-lg py-4 position-relative" style="z-index: 3;">
        <div class="row align-items-center">
            <div class="col-lg mb-2 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('posts.public.index') }}" style=" color: #fff; text-decoration: none; ">
                    
                    <h1 class="display-4 fw-bold mb-0 shadow-lg border-0" style="text-shadow: 2px 2px 4px rgb(3 64 71);background-color: #63d0c7;padding: 10px;border-radius: 10px;"> <i class="fas fa-eye me-3"></i>Vidéothèque</h1>
                    </a>
                </div>

                <p class="lead mb-4">
                    Banque de vidéos (infos, tutoriels, techniques et plus) pour entraîneurs et sportifs.
                </p>
            </div>
        </div>
    </div>
</section>




<!-- Navigation par Catégories -->
<section class="py-5 bg-light">
    <div class="container-lg">


        @if($categories->count() > 0)
            <!-- Boucle sur chaque catégorie -->
            @foreach($categories as $category)
                <div class="category-row mb-4">
                    <div class="card border-0 shadow-sm hover-category-video">
                        <div class="row g-0">
                            <!-- Image de la catégorie (gauche sur desktop, haut sur mobile) -->
                            <div class="col-12 col-md-3">
                                <div class="category-image-wrapper-video">
                                    @if($category->image)
                                        <img src="{{ $category->image }}" 
                                             alt="{{ $category->name }}"
                                             class="category-image-video">
                                    @else
                                        <div class="category-image-placeholder-video d-flex align-items-center justify-content-center text-white"
                                             style="background: linear-gradient(135deg, {{ $loop->index % 4 == 0 ? '#71287c' : ($loop->index % 4 == 1 ? '#198754' : ($loop->index % 4 == 2 ? '#0dcaf0' : '#ffc107')) }} 0%, {{ $loop->index % 4 == 0 ? '#4b0055' : ($loop->index % 4 == 1 ? '#0f5132' : ($loop->index % 4 == 2 ? '#087990' : '#cc9a06')) }} 100%);">
                                            <i class="fas fa-video" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    

                                </div>
                            </div>

                            <!-- Contenu central (titre, description) -->
                            <div class="col-12 col-md-7">
                                <div class="card-body">
                                    <!-- Nom de la catégorie -->
                                    <h3 class="card-title h4 mb-3">
                                        <a href="{{ route('public.videos.category', $category) }}" 
                                           class="text-decoration-none text-dark category-link-video">
                                            {{ $category->name }}
                                        </a>
                                    </h3>

                                    <!-- Description -->
                                    @if($category->description)
                                        <p class="card-text text-muted mb-3">
                                            {{ Str::limit(strip_tags($category->description), 200) }}
                                        </p>
                                    @else
                                        <p class="card-text text-muted mb-3">
                                            Découvrez nos vidéos dans la catégorie {{ $category->name }}.
                                        </p>
                                    @endif

                                    <!-- Informations supplémentaires -->
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <div class="badge bg-primary" style=" border-radius: 100%; font-size: 120%; font-weight: 300; ">
                                            
                                            {{ $category->published_videos_count }} 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton à droite -->
                            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                                <div class="p-3 w-100">
                                    <a href="{{ route('public.videos.category', $category) }}" 
                                       class="btn btn-outline-primary w-100 btn-category-video">
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
<section class="py-5 nataswim-titre3">
    <div class="container-lg">


        @if($videos->count() > 0)
        <div class="row g-4">
            @foreach($videos->take(6) as $video)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-lg hover-lift">
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
                        <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark">
                            <i class="fas fa-crown me-1"></i>Premium
                        </span>
                        @endif

                        {{-- Badge Nouveau --}}
                        @if($video->created_at->diffInDays(now()) < 7)
                        <span class="position-absolute top-0 end-0 m-2 badge bg-success">
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
                            <span class="badge bg-primary">
                                {{ $category->name }}
                            </span>
                            @endforeach

                            @if($video->visibility === 'authenticated')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-crown me-1"></i>Premium
                            </span>
                            @endif

                            @if($video->is_featured)
                            <span class="badge bg-success">
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
        @else
        <div class="text-center py-5">
            <i class="fas fa-video fa-3x text-muted mb-3 opacity-25"></i>
            <h5 class="text-muted">Aucune vidéo disponible pour le moment</h5>
        </div>
        @endif
    </div>
</section>

<!-- Top 3 des vidéos les plus vues -->
@if(isset($topVideos) && $topVideos->count() >= 3)
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">
                <i class="fas fa-trophy me-2 text-warning"></i>Top 3 des vidéos les plus vues
            </h2>
            <p class="lead text-muted">Les vidéos favorites de notre communauté</p>
        </div>

        <div class="row g-4">
            @foreach($topVideos->take(3) as $video)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-lg hover-lift position-relative">
                    {{-- Badge de classement --}}
                    <div class="position-absolute top-0 start-0" style="z-index: 10;">
                        <span class="badge {{ $loop->first ? 'bg-warning' : ($loop->iteration == 2 ? 'bg-secondary' : 'bg-bronze') }} fs-5 rounded-end-pill ps-3 pe-3 py-2">
                            @if($loop->first)
                                <i class="fas fa-crown me-1"></i>#1
                            @elseif($loop->iteration == 2)
                                <i class="fas fa-medal me-1"></i>#2
                            @else
                                <i class="fas fa-award me-1"></i>#3
                            @endif
                        </span>
                    </div>

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
                        <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>En vedette
                        </span>
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
                            <span class="badge bg-primary">
                                {{ $category->name }}
                            </span>
                            @endforeach

                            @if($video->visibility === 'authenticated')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-crown me-1"></i>Premium
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
    </div>
</section>
@endif

<!-- Toutes les vidéos avec pagination (si plus de 6) -->
@if($videos->count() > 6)
<section class="py-5 bg-white" id="all-videos-section">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">
                <i class="fas fa-th me-2 text-primary"></i>Toutes les vidéos
            </h2>
        </div>

        <div class="row g-4">
            @foreach($videos as $video)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow hover-lift">
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
                        <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark">
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
/* Espacement entre les lignes de catégories vidéo */
.category-row {
    margin-bottom: 2rem;
}

/* Style de la carte catégorie vidéo avec effet hover */
.hover-category-video {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.hover-category-video:hover {
    box-shadow: 0 0.5rem 2rem rgba(113, 40, 124, 0.3) !important;
    background-color: #fcf5fd;
}

/* Image de la catégorie vidéo */
.category-image-wrapper-video {
    position: relative;

}

.category-image-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.category-image-placeholder-video {
    width: 100%;
    height: 100%;
    min-height: 250px;
}

/* Liens avec effet hover vidéo */
.category-link-video {
    transition: color 0.3s ease;
}

.hover-category-video:hover .category-link-video {
    color: #71287c !important;
}

/* Bouton avec effet hover vidéo */
.btn-category-video {
    transition: all 0.3s ease;
}

.hover-category-video:hover .btn-category-video {
    background-color: #2d9707;
    border-color: #71287c;
    color: white;
}

/* Effet hover sur les cartes de vidéos */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}



/* Couleur bronze pour la 3ème place */
.bg-bronze {
    background-color: #cd7f32 !important;
    color: white !important;
}

/* Animation smooth scroll */
html {
    scroll-behavior: smooth;
}

/* Badge classement avec effet brillant */
.badge.fs-5 {
    font-weight: 700;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Responsive pour mobile */
@media (max-width: 767px) {
    /* Image centrée en haut sur mobile */
    .category-image-wrapper-video {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .category-image-video {
        border-radius: 12px 12px 0 0;
    }
    
    .category-image-placeholder-video {
        min-height: 200px;
        border-radius: 12px 12px 0 0;
    }
    
    /* Espacement réduit sur mobile */
    .category-row {
        margin-bottom: 1.5rem;
    }
}

/* Responsive pour desktop */
@media (min-width: 768px) {
    /* Image à gauche sur desktop */
    .category-image-wrapper-video {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-video {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-placeholder-video {
        border-radius: 12px 0 0 12px;
    }
}
</style>
@endpush