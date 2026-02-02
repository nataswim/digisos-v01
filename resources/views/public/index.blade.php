@extends('layouts.public')


@section('title', 'Dossiers & Actualités Sport Natation Triathlon Santé')
@section('meta_description', 'Retrouvez nos derniers articles. Expertise, conseils techniques et actualités pour les passionnés de sport de la natation du triathlon et de la santé.')


@section('content')

<!-- Hero Section avec Video Background -->
<section class="hero-video-section position-relative text-white overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="hero-video">
        <source src="{{ asset('assets/images/team/nataswim-sport-training-1.mp4') }}" type="video/mp4">
    </video>

    <!-- Overlay avec dégradé aquatique -->
    <div class="hero-overlay"></div>

    <!-- Contenu -->
    <div class="container-lg py-5 position-relative hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-4 animate-slide-up">
                    <i class="fas fa-newspaper me-3 hero-icon"></i>
                    <h1 class="display-3 fw-bold mb-0">Articles & Dossiers</h1>
                </div>

                <p class="lead mb-4 animate-slide-up animation-delay-1">
                    Retrouvez nos derniers articles. Expertise, conseils techniques et actualités pour les passionnés de sport de la natation du triathlon et de la santé.
                </p>
            </div>
        </div>
    </div>
</section>



<!-- Filtres et recherche -->
<section class="py-4">
    <div class="container-lg">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Rechercher dans les articles...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Toutes les categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ $category === $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->posts_count ?? 0 }})
                    </option>
                    @endforeach
                </select>
            </div>
            @if(isset($tags) && $tags->count() > 0)
            <div class="col-md-2">
                <select name="tag" class="form-select">
                    <option value="">Tous les tags</option>
                    @foreach($tags as $tagItem)
                    <option value="{{ $tagItem->slug }}" {{ ($tag ?? '') === $tagItem->slug ? 'selected' : '' }}>
                        {{ $tagItem->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary text-white flex-fill">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                    @if($search || $category || ($tag ?? ''))
                    <a href="{{ route('posts.public.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Articles -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        @if($posts->count() > 0)
        <!-- Statistiques de recherche -->
        @if($search || $category || ($tag ?? ''))
        <div class="mb-4">
            <div class="alert alert-info border-0">
                <i class="fas fa-water me-2"></i>
                {{ $posts->total() }} resultat(s) trouve(s)
                @if($search)
                pour "<strong>{{ $search }}</strong>"
                @endif
                @if($category)
                dans la categorie "<strong>{{ $categories->where('slug', $category)->first()->name ?? $category }}</strong>"
                @endif
            </div>
        </div>
        @endif

        <div class="row g-4">
            @foreach($posts as $post)
            <div class="col-lg-4 col-md-6">
                <div class="card-aqua h-100">
                    <!-- Image et badges -->
                    <div class="card-image-wrapper mb-3 position-relative">
                        @if($post->image)
                        <img src="{{ $post->image }}" class="card-image" alt="{{ $post->name }}">
                        @else
                        <div class="card-image-placeholder">
                            <i class="fas fa-newspaper fa-3x text-primary opacity-25"></i>
                        </div>
                        @endif

                        <!-- Badges en overlay -->
                        <div class="position-absolute top-0 end-0 p-3">
                            @if($post->is_featured)
                            <span class="badge badge-warning mb-2 d-block">
                                <i class="fas fa-star me-1"></i>A la une
                            </span>
                            @endif

                            @if($post->visibility === 'authenticated')
                            <span class="badge badge-info d-block">
                                <i class="fas fa-lock me-1"></i>Membre
                            </span>
                            @endif
                        </div>

                        <!-- Indicateur de temps de lecture -->
                        <div class="position-absolute bottom-0 start-0 p-3">
                            <span class="badge badge-dark">
                                <i class="fas fa-clock me-1"></i>{{ $post->reading_time ?? 5 }} min
                            </span>
                        </div>
                    </div>

                    <!-- Metadonnees -->
                    <div class="card-meta mb-2">
                        <span class="badge badge-primary">
                            {{ $post->category->name ?? 'Non categorise' }}
                        </span>
                    </div>

                    <!-- Titre -->
                    <h6 class="card-title mb-2">
                        <a href="{{ route('posts.public.show', $post) }}"
                            class="text-decoration-none text-dark hover-primary">
                            {{ $post->name }}
                        </a>
                    </h6>

                    <!-- Intro (toujours visible) -->
                    @if($post->intro)
                    <p class="card-text text-muted small mb-3">
                        {!! Str::limit(strip_tags($post->intro), 120) !!}
                    </p>
                    @endif

                    <!-- Footer avec informations -->
                    <div class="card-footer-info mt-auto">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($post->hits) }}
                            </small>

                            @if($post->visibility === 'authenticated' && auth()->guest())
                            <small class="text-warning">
                                <i class="fas fa-lock me-1"></i>Connexion
                            </small>
                            @else
                            <small class="text-muted">
                                {{ $post->published_at?->format('d/m/Y') ?? $post->created_at?->format('d/m/Y') }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="mt-5">
                    {{ $posts->appends(request()->query())->links('pagination.five-per-row') }}
                </div>
            </div>
        </div>
        @endif
        @else
        <!-- etat vide -->
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                style="width: 120px; height: 120px;">
                <i class="fas fa-search text-muted fa-3x"></i>
            </div>
            <h3 class="fw-bold mb-3">Aucun article trouve</h3>
            <p class="text-muted mb-4">
                @if($search || $category || ($tag ?? ''))
                Aucun resultat ne correspond A vos criteres de recherche.
                @else
                Il n'y a pas encore d'articles publies.
                @endif
            </p>
            @if($search || $category || ($tag ?? ''))
            <a href="{{ route('posts.public.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Voir tous les articles
            </a>
            @endif
        </div>
        @endif
    </div>
</section>




<!-- Grille des catégories -->
<section class="py-5">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="title-aqua-secondary">
                <i class="fas fa-folder me-2"></i>Catégories
            </h2>
            <p class="text-muted">Explorez nos articles par thématique</p>
        </div>

        @if($categories->count() > 0)
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="card-aqua h-100">
                    <!-- Image de la catégorie -->
                    <div class="card-image-wrapper mb-3 position-relative">
                        @if($category->image)
                        <img src="{{ $category->image }}"
                            alt="{{ $category->name }}"
                            class="card-image">
                        @else
                        <div class="card-image-placeholder">
                            <i class="fas fa-folder fa-3x text-secondary opacity-25"></i>
                        </div>
                        @endif

                        <!-- Badge nombre d'articles -->
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge badge-danger">
                                <i class="fas fa-file-alt me-1"></i>
                                {{ $category->posts_count }} article{{ $category->posts_count > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>

                    <!-- Nom de la catégorie -->
                    <h6 class="card-title mb-2">
                        <a href="{{ route('posts.public.category', $category) }}"
                            class="text-decoration-none text-dark hover-primary">
                            {{ $category->name }}
                        </a>
                    </h6>

                    <!-- Description -->
                    @if($category->description)
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($category->description, 120) }}
                    </p>
                    @endif

                    <!-- Groupe (si existe) -->
                    @if($category->group_name)
                    <div class="card-meta">
                        <span class="badge badge-secondary">
                            <i class="fas fa-layer-group me-1"></i>{{ $category->group_name }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Message si aucune catégorie -->
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
            <h3 class="text-muted">Aucune catégorie disponible</h3>
            <p class="text-muted">Les catégories seront bientôt disponibles.</p>
        </div>
        @endif
    </div>
</section>




@endsection

@push('styles')
<style>
/* ============================================================================
   HERO VIDEO SECTION
   ============================================================================ */
.hero-video-section {
    min-height: 600px;
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
    color: #38859b !important;
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
}

/* ============================================================================
   SMOOTH SCROLL
   ============================================================================ */
html {
    scroll-behavior: smooth;
}
</style>
@endpush