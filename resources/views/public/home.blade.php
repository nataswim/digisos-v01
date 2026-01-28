@extends('layouts.public')

@section('title', 'Plateforme Sportive pour tous - Natation & Triathlon')
@section('meta_description', 'Decouvrez notre plateforme dediee A la natation et au triathlon avec articles, plans d\'entrainement, fiches techniques et videos. Rejoignez notre communaute de nageurs, triathletes et coachs.')

@section('content')
<!--  Section avec Video Background -->

<section class="position-relative text-white py-5 nataswim-titre3  overflow-hidden" style="min-height: 600px;">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 1;">
        <source src="{{ asset('assets/images/team/nataswim.mp4') }}" type="video/mp4">
    </video>
    
    <!-- Overlay sombre pour meilleure lisibilité -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;"></div>
    
    <!-- Contenu -->
    <div class="container-lg py-4 position-relative" style="z-index: 3;">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-swimmer me-3 fs-1"></i>
                    <h1 class="display-4 fw-bold mb-0">nataswim</h1>
                </div>
                
                <p class="lead mb-4">
                    Optimisez vos entraînements, développez vos connaissances et formez-vous en continu grâce à cette plateforme  dédiée aux sportifs, techniciens, préparateurs physiques, entraîneurs et coachs — du débutant au professionnel.
                </p>
            </div>
            <div class="col-lg-5">
                
    <div class="text-center">
        <div class="position-relative d-inline-block bg-white rounded-circle">
           <a href="{{ route('home') }}"> <img src="{{ asset('assets/images/team/nataswim_app_logo_2.png') }}" 
                 alt="nataswim application pour tous" 
                 class="img-fluid" 
                 style="max-width: 200px;height: auto;box-shadow: 0 0 40px rgba(255,255,255,.8),0 0 10px #fff;border-radius: 100%;"></a>
        </div>
    </div>
</div>
        </div>
    </div>
</section>






<!-- Dernieres Publications -->
<section class="py-5">
    <div class="container-lg card shadow-lg border-0 bg-white">
        <div class="row mb-4 card-header bg-info text-white">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        Articles
                    </h5>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentArticles = App\Models\Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();
            @endphp

            @forelse($recentArticles as $article)
            <div class="col-md-6 col-lg-3">


            
                <div class="card border-0 h-100 hover-lift">


                    <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                        style="height: 180px; overflow: hidden;">
                        @if($article->image)
                        <img src="{{ $article->image }}"
                            class="w-100"
                            style="object-fit: cover;"
                            alt="{{ $article->name }}">
                        @else
                        <i class="fas fa-newspaper fa-3x text-primary opacity-25"></i>
                        @endif
                    </div>

                    <div class="card-body p-3">



                        <span class="badge bg-primary-subtle text-primary mb-2">
                            {{ $article->category->name ?? 'Non catégorisé' }}
                        </span>
                        <h6 class="card-title mb-2">
                            <a href="{{ route('posts.public.show', $article) }}"
                                class="text-decoration-none text-dark">
                                {!! Str::limit($article->name, 50) !!}
                            </a>
                        </h6>
                        @if($article->intro)
                        <p class="card-text text-muted small mb-3">
                            {!! Str::limit(strip_tags($article->intro), 80) !!}
                        </p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ $article->hits }}
                            </small>
                            <small class="text-muted">
                                {{ $article->published_at->format('d/m/Y') }}
                            </small>
                        </div>
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

        <div class="row mb-4">
            <div class="col-12">
                <div class="align-items-center justify-content-between">
                    <a href="{{ route('posts.public.index') }}" class="btn btn-lg btn-secondary d-flex align-items-center px-4 text-white bg-primary" style="border-radius: 0px;">
                        <i class="fas fa-eye me-1"></i> Plus d'articles
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</section>




<section class="py-5" >

    <div class="container-lg card shadow-lg border-0 bg-white">
        <div class="row mb-4 card-header bg-info text-white">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        Fiches Pratiques
                    </h5>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentFiches = App\Models\Fiche::where('is_published', true)
            ->where('visibility', 'public')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
                @endphp

                @forelse($recentFiches as $fiche)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 h-100 hover-lift">
                        @if($fiche->image)
                        <img src="{{ $fiche->image }}"
                            class="w-100"
                            style="object-fit: cover;"
                            alt="{{ $fiche->title }}">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                            style="height: 180px;">
                            <i class="fas fa-file-alt fa-3x text-muted opacity-25"></i>
                        </div>
                        @endif

                        <div class="card-body p-3">
                            @if($fiche->category)
                            <span class="badge bg-primary-subtle text-primary mb-2">
                                {{ $fiche->category->name }}
                            </span>
                            @endif
                            <h6 class="card-title mb-2">
                                <a href="{{ route('public.fiches.show', [$fiche->category, $fiche]) }}"
                                    class="text-decoration-none text-dark">
                                    {!! Str::limit($fiche->title, 50) !!}
                                </a>
                            </h6>
                            @if($fiche->short_description)
                            <p class="card-text text-muted small mb-3">
                                {!! Str::limit(strip_tags($fiche->short_description), 80) !!}
                            </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-eye me-1"></i>{{ $fiche->views_count ?? 0 }}
                                </small>
                                <a href="{{ route('public.fiches.show', [$fiche->category, $fiche]) }}"
                                    class="btn btn-light d-flex align-items-center px-4">
                                    Lire
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-file-alt fa-3x mb-3 opacity-25"></i>
                        <p>Aucune fiche publiée récemment</p>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <a href="{{ route('public.fiches.index') }}" class="btn btn-lg btn-secondary d-flex align-items-center px-4 text-white bg-primary" style="border-radius: 0px;">
                        <i class="fas fa-eye me-1"></i> Plus de fiches
                    </a>
                </div>
            </div>
        </div>
    </section>


<!-- Section Vidéos -->
<section class="py-5">
    <div class="container-lg card shadow-lg border-0 bg-white">
        <div class="row mb-4 card-header bg-info text-white">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        Vidéos
                    </h5>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentVideos = App\Models\Video::query()
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
            @endphp

            @forelse($recentVideos as $video)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100 hover-lift">
                    @if($video->thumbnail)
                    <img src="{{ $video->thumbnail }}"
                        class="card-img-top"
                        style="height: 180px; object-fit: cover;"
                        alt="{{ $video->title }}">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                        style="height: 180px;">
                        <i class="fas fa-video fa-3x text-muted opacity-25"></i>
                    </div>
                    @endif

                    <div class="card-body p-3">
                        <h6 class="card-title mb-2">
                            <a href="{{ route('public.videos.show', $video) }}"
                                class="text-decoration-none text-dark">
                                {!! Str::limit($video->title, 50) !!}
                            </a>
                        </h6>
                        @if($video->description)
                        <p class="card-text text-muted small mb-3">
                            {!! Str::limit(strip_tags($video->description), 80) !!}
                        </p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-play me-1"></i>Vidéo
                            </small>
                            <a href="{{ route('public.videos.show', $video) }}"
                                class="btn btn-light d-flex align-items-center px-4">
                                Voir
                            </a>
                        </div>
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

        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('public.videos.index') }}" class="btn btn-lg btn-secondary d-flex align-items-center px-4 text-white bg-primary" style="border-radius: 0px;">
                    <i class="fas fa-eye me-1"></i> Plus de vidéos
                </a>
            </div>
        </div>
    </div>
</section>


<!-- Section eBooks -->
<section class="py-5">
    <div class="container-lg card shadow-lg border-0 bg-white">
        <div class="row mb-4 card-header bg-info text-white">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        eBooks & Téléchargements
                    </h5>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentDownloads = App\Models\Downloadable::query()
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
            @endphp

            @forelse($recentDownloads as $download)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100 hover-lift">
                    @if($download->image)
                    <img src="{{ $download->image }}"
                        class="card-img-top"
                        style="height: 180px; object-fit: cover;"
                        alt="{{ $download->title }}">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                        style="height: 180px;">
                        <i class="fas fa-book fa-3x text-muted opacity-25"></i>
                    </div>
                    @endif

                    <div class="card-body p-3">
                        <span class="badge bg-success-subtle text-success mb-2">
                            {{ $download->category->name ?? 'Téléchargement' }}
                        </span>
                        <h6 class="card-title mb-2">
                            <a href="{{ route('ebook.show', [$download->category, $download]) }}"
                                class="text-decoration-none text-dark">
                                {!! Str::limit($download->title, 50) !!}
                            </a>
                        </h6>
                        @if($download->description)
                        <p class="card-text text-muted small mb-3">
                            {!! Str::limit(strip_tags($download->description), 80) !!}
                        </p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-download me-1"></i>{{ $download->downloads_count ?? 0 }}
                            </small>
                            <a href="{{ route('ebook.show', [$download->category, $download]) }}"
                                class="btn btn-light d-flex align-items-center px-4">
                                Voir
                            </a>
                        </div>
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

        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('ebook.index') }}" class="btn btn-lg btn-secondary d-flex align-items-center px-4 text-white bg-primary" style="border-radius: 0px;">
                    <i class="fas fa-eye me-1"></i> Plus de téléchargements
                </a>
            </div>
        </div>
    </div>
</section>


@endsection

@push('styles')
<style>





.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

/* Cards catégories - Design amélioré */
.category-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 12px;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important;
}

.category-card .card-header {
    padding: 1.25rem;
}

.category-card .card-header h4 {
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
}

/* Effets hover spécifiques par couleur */
.category-card:hover .bg-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
}

.category-card:hover .bg-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%) !important;
}

.category-card:hover .bg-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
}

.category-card:hover .bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #cc9a06 100%) !important;
}

.category-card:hover .bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #bb0a31 100%) !important;
}

.category-card:hover .bg-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #565e64 100%) !important;
}

/* Amélioration des badges */
.category-card .badge {
    font-size: 0.7rem;
    padding: 0.35rem 0.65rem;
    font-weight: 600;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1);
}
.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .display-4 {
        font-size: 1.75rem !important;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
        align-items: stretch !important;
    }
    
    .category-card .card-header h4 {
        font-size: 1.1rem;
    }
    
    .category-card .card-header i {
        font-size: 1.5rem !important;
    }
    
    .category-card .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Animation au chargement */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.category-card {
    animation: fadeInUp 0.6s ease-out;
}

.category-card:nth-child(1) { animation-delay: 0.1s; }
.category-card:nth-child(2) { animation-delay: 0.2s; }
.category-card:nth-child(3) { animation-delay: 0.3s; }
.category-card:nth-child(4) { animation-delay: 0.4s; }
.category-card:nth-child(5) { animation-delay: 0.5s; }
.category-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush