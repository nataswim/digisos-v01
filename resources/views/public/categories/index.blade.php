@extends('layouts.public')

@section('title', 'Dossiers sport et performance')
@section('meta_description', 'Plongez dans nos catégories dédiées au sport. Conseils d\'experts, méthodes d\'entraînement avancées et décryptage des dernières tendances sportives.')

@section('content')

<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold d-flex align-items-center justify-content-center gap-3 mb-3">
                    Dossiers Thématiques
                </h1>
                <p class="lead mb-0">
                    <strong>Dossiers structurées et accessibles</strong> pour vous accompagner 
                    dans votre progression avec des contenus organisés par domaine.
                </p>
            </div>
            <div class="col-lg-5 text-center">
                <a href="{{ route('contact') }}">
                    <img src="{{ asset('assets/images/team/nataswim-sport-net-systemes-10.jpg') }}"
                        alt="Guide Nataswim"
                        class="img-fluid rounded-4"
                        style="max-height: 300px; object-fit: cover;">
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Liste des catégories -->
<section class="py-5">
    <div class="container-lg">
        @if($categories->count() > 0)
            <!-- Boucle sur chaque catégorie -->
            @foreach($categories as $category)
                <div class="category-row mb-4">
                    <div class="card border-0 shadow-sm hover-category">
                        <div class="row g-0">
                            <!-- Image de la catégorie (gauche sur desktop, haut sur mobile) -->
                            <div class="col-12 col-md-3">
                                <div class="category-image-wrapper">
                                    @if($category->image)
                                        <img src="{{ $category->image }}" 
                                             alt="{{ $category->name }}"
                                             class="category-image">
                                    @else
                                        <div class="category-image-placeholder bg-info d-flex align-items-center justify-content-center text-white">
                                            <i class="fas fa-folder fs-1"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge nombre d'articles -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger shadow-sm">
                                            
                                            {{ $category->posts_count }} article{{ $category->posts_count > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenu central (titre, description, articles) -->
                            <div class="col-12 col-md-7">
                                <div class="card-body">
                                    <!-- Nom de la catégorie -->
                                    <h3 class="card-title h4 mb-3">
                                        <a href="{{ route('posts.public.category', $category) }}" 
                                           class="text-decoration-none text-dark category-link">
                                            {{ $category->name }}
                                        </a>
                                    </h3>

                                    <!-- Description -->
                                    @if($category->description)
                                        <p class="card-text text-muted mb-3">
                                            {{ Str::limit($category->description, 150) }}
                                        </p>
                                    @endif

                                    <!-- Groupe (si existe) -->
                                    @if($category->group_name)
                                        <div class="mb-3">
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <i class="fas fa-layer-group me-1"></i>{{ $category->group_name }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Derniers articles de cette catégorie -->
                                    @if($category->posts->count() > 0)
                                        <div class="mt-3 pt-3 border-top">
                                            <h6 class="small fw-bold text-muted mb-2">
                                                <i class="fas fa-clock me-1"></i>Articles récents
                                            </h6>
                                            <ul class="list-unstyled small mb-0 recent-posts-list">
                                                @foreach($category->posts->take(3) as $post)
                                                    <li class="mb-2">
                                                        <a href="{{ route('posts.public.show', $post) }}" 
                                                           class="text-decoration-none text-dark post-link">
                                                            <i class="fas fa-chevron-right text-primary me-1 small"></i>
                                                            {{ Str::limit($post->name, 45) }}
                                                        </a>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $post->published_at->format('d/m/Y') }}
                                                        </small>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Bouton à droite -->
                            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                                <div class="p-3 w-100">
                                    <a href="{{ route('posts.public.category', $category) }}" 
                                       class="btn btn-outline-primary w-100 btn-category">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        <span class="d-none d-lg-inline">Voir ce dossier</span>
                                        <span class="d-inline d-lg-none">Voir tous les articles</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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

<!-- CTA vers les articles -->
<section class="py-5 bg-light">
    <div class="container-lg text-center">
        <h2 class="h4 mb-4">Vous cherchez un sujet en particulier ?</h2>
        <p class="mb-4 text-muted">Que vous soyez un athlète cherchant à optimiser votre préparation physique ou un passionné souhaitant simplement progresser, l'entraînement est la clé de la réussite dans le sport. Notre plateforme se spécialise dans les programmes structurés pour atteindre vos objectifs. Découvrez nos stratégies spécifiques pour le Triathlon, où l'enchaînement de la natation, du vélo et de la course requiert une endurance et une musculation ciblées. Explorez nos dossiers détaillés sur les meilleures techniques de nage, les séances de renforcement musculaire pour prévenir les blessures, et les plans de préparation physique générale pour garantir des performances durables. Maîtrisez chaque discipline et transformez votre potentiel athlétique.</p>
        <a href="{{ route('posts.public.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-search me-2"></i>Parcourir 
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Espacement entre les lignes de catégories */
.category-row {
    margin-bottom: 2rem;
}

/* Style de la carte catégorie avec effet hover */
.hover-category {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.hover-category:hover {
    box-shadow: 0 0.5rem 2rem rgba(4, 173, 185, 0.25) !important;
    background-color: #f8fbff;
}

/* Image de la catégorie */
.category-image-wrapper {
    position: relative;
    height: 100%;
}

.category-image {
    width: 100%;
    object-fit: cover;
}

.category-image-placeholder {
    width: 100%;
    height: 100%;
    min-height: 250px;
}

/* Liens avec effet hover */
.category-link {
    transition: color 0.3s ease;
}

.hover-category:hover .category-link {
    color: #04adb9 !important;
}

.post-link {
    transition: color 0.3s ease;
}

.post-link:hover {
    color: #04adb9 !important;
}

/* Bouton avec effet hover */
.btn-category {
    transition: all 0.3s ease;
}

.hover-category:hover .btn-category {
    background-color: #04adb9;
    border-color: #04adb9;
    color: white;
}

/* Liste des articles récents */
.recent-posts-list {
    max-height: 200px;
    overflow-y: auto;
}

/* Responsive pour mobile */
@media (max-width: 767px) {
    /* Image centrée en haut sur mobile */
    .category-image-wrapper {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .category-image {
        border-radius: 12px 12px 0 0;
    }
    
    .category-image-placeholder {
        min-height: 200px;
        border-radius: 12px 12px 0 0;
    }
    
    /* Espacement réduit sur mobile */
    .category-row {
        margin-bottom: 1.5rem;
    }
    
    .display-4 {
        font-size: 2rem !important;
    }
}

/* Responsive pour desktop */
@media (min-width: 768px) {
    /* Image à gauche sur desktop */
    .category-image-wrapper {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-placeholder {
        border-radius: 12px 0 0 12px;
    }
}

/* Scrollbar personnalisée pour la liste des articles */
.recent-posts-list::-webkit-scrollbar {
    width: 6px;
}

.recent-posts-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.recent-posts-list::-webkit-scrollbar-thumb {
    background: #04adb9;
    border-radius: 10px;
}

.recent-posts-list::-webkit-scrollbar-thumb:hover {
    background: #038d97;
}
</style>
@endpush