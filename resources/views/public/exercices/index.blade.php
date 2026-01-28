@extends('layouts.public')

@section('title', 'Exercices de Musculation Natation Triathlon - Hassan EL HAOUAT')
@section('meta_description', 'Découvrez notre bibliothèque complète d\'exercices d\'entraînement pour la musculation la natation et le triathlon tous niveaux. Cardio, force, flexibilité et équilibre avec instructions détaillées.')

@section('content')

<!-- Section titre -->

<section class="position-relative text-white py-5 nataswim-titre3 overflow-hidden" style="min-height: 600px;">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 1;">
        <source src="{{ asset('assets/images/team/MUSCULATION_NATASWIM.mp4') }}" type="video/mp4">
    </video>

    <!-- Overlay sombre pour meilleure lisibilité -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;"></div>

    <!-- Contenu -->
    <div class="container-lg py-4 position-relative" style="z-index: 3;">
        <div class="row align-items-center">
            <div class="col-lg mb-2 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('posts.public.index') }}" style=" color: #fff; text-decoration: none; ">
                    
                    <h1 class="display-4 fw-bold mb-0 shadow-lg border-0" style="text-shadow: 2px 2px 4px rgb(3 64 71);background-color: #63d0c7;padding: 10px;border-radius: 10px;"> <i class="fas fa-file-alt me-3"></i>Bibliothèque d'exercices</h1>
                    </a>
                </div>

                <p class="lead mb-4">
                    Découvrez notre réserve d'exercices pour tous niveaux, avec instructions et conseils.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Navigation par Catégories d'exercices -->
@if(isset($categories) && $categories->count() > 0)
<section class="py-5 bg-light">
    <div class="container-lg">
        <h2 class="h3 fw-bold mb-4 text-center">
            <i class="fas fa-dumbbell text-primary me-2"></i>
            Catégories d'exercices
        </h2>

        <!-- Boucle sur chaque catégorie -->
        @foreach($categories as $category)
            <div class="category-row mb-4">
                <div class="card border-0 shadow-sm hover-category-exercice">
                    <div class="row g-0">
                        <!-- Image de la catégorie (gauche sur desktop, haut sur mobile) -->
                        <div class="col-12 col-md-3">
                            <div class="category-image-wrapper-exercice">
                                @if($category->image)
                                    <img src="{{ $category->image }}" 
                                         alt="{{ $category->name }}"
                                         class="category-image-exercice">
                                @else
                                    <div class="category-image-placeholder-exercice d-flex align-items-center justify-content-center text-white"
                                         style="background: linear-gradient(135deg, {{ $loop->index % 4 == 0 ? '#0d6efd' : ($loop->index % 4 == 1 ? '#198754' : ($loop->index % 4 == 2 ? '#0dcaf0' : '#ffc107')) }} 0%, {{ $loop->index % 4 == 0 ? '#084298' : ($loop->index % 4 == 1 ? '#0f5132' : ($loop->index % 4 == 2 ? '#087990' : '#cc9a06')) }} 100%);">
                                        <i class="fas fa-dumbbell" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <!-- Badge nombre d'exercices -->

                            </div>
                        </div>

                        <!-- Contenu central (titre, description) -->
                        <div class="col-12 col-md-7">
                            <div class="card-body">
                                <!-- Nom de la catégorie -->
                                <h3 class="card-title h4 mb-3">
                                    <a href="{{ route('exercices.category', $category) }}" 
                                       class="text-decoration-none text-dark category-link-exercice">
                                        {{ $category->name }}
                                    </a>
                                </h3>

                                <!-- Description -->
                                @if($category->description)
                                    <p class="card-text text-muted mb-3">
                                        {!! Str::limit(strip_tags($category->description), 180) !!}
                                    </p>
                                @else
                                    <p class="card-text text-muted mb-3">
                                        Découvrez nos exercices dans la catégorie {{ $category->name }}.
                                    </p>
                                @endif

                                <!-- Informations supplémentaires -->
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <div class="badge bg-danger px-3 py-2" style=" border-radius: 0%; ">
                                        <i class="fas fa-layer-group me-1"></i>
                                        {{ $category->exercices_count ?? 0 }} exercice{{ ($category->exercices_count ?? 0) > 1 ? 's' : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton à droite -->
                        <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                            <div class="p-3 w-100">
                                <a href="{{ route('exercices.category', $category) }}" 
                                   class="btn btn-outline-primary w-100 btn-category-exercice">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    <span class="d-none d-lg-inline">Découvrir</span>
                                    <span class="d-inline d-lg-none">Découvrir les exercices</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- Filtres et recherche -->
<section class="py-5">
    <div class="container-lg">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md">
                        <label class="fw-bold mb-2"><i class="fas fa-search text-primary me-2"></i>Recherche</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               class="form-control form-control-lg border-primary"
                               placeholder="Nom de l'exercice...">
                    </div>
                   
                    <div class="col-md">
                        <label class="fw-bold mb-2">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                        </div>
                    </div>
                </form>

                @if(request()->hasAny(['search']))
                    <div class="text-center mt-3">
                        <a href="{{ route('exercices.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Réinitialiser
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Liste des exercices -->
<section class="py-5">
    <div class="container-lg">
        @if($exercices->count() > 0)
            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0">
                            <i class="fas fa-running text-primary me-2"></i>
                            {{ $exercices->total() }} Exercices Trouvés
                        </h2>
                        @if(request()->hasAny(['search']))
                            <span class="badge bg-info fs-6">Filtres appliqués</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Grille des exercices -->
            <div class="row g-4">
                @foreach($exercices as $exercice)
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            @if($exercice->image)
                                <img src="{{ $exercice->image }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;" 
                                     alt="{{ $exercice->titre }}">
                            @else
                                <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                                     style="height: 200px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <i class="fas fa-running fa-3x text-muted opacity-25"></i>
                                </div>
                            @endif
                            
                            <div class="card-body p-4 d-flex flex-column">
                                <!-- Badges catégories -->
                                @if($exercice->categories->isNotEmpty() || $exercice->sousCategories->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach($exercice->categories as $cat)
                                            <span class="badge bg-primary">
                                                <i class="fas fa-folder me-1"></i>{{ $cat->name }}
                                            </span>
                                        @endforeach
                                        @foreach($exercice->sousCategories as $sousCat)
                                            <span class="badge bg-info">
                                                <i class="fas fa-layer-group me-1"></i>{{ $sousCat->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <h5 class="card-title fw-bold mb-2">{{ $exercice->titre }}</h5>
                                
                                @if($exercice->description)
                                    <p class="card-text text-muted small flex-fill">
                                        {!! Str::limit(strip_tags($exercice->description), 100) !!}
                                    </p>
                                @endif
                                
                                <div class="mt-auto">
                                    <a href="{{ route('exercices.show', $exercice) }}" 
                                       class="btn btn-outline-primary w-100 fw-bold">
                                        <i class="fas fa-eye me-2"></i>Voir l'exercice
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
@if($exercices->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <div class="mt-5">
                {{ $exercices->appends(request()->query())->links('pagination.five-per-row') }}
            </div>
        </div>
    </div>
@endif
        @else
            <!-- Aucun résultat -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-running fa-4x text-muted mb-4 opacity-25"></i>
                    <h4 class="fw-bold mb-3">Aucun exercice trouvé</h4>
                    @if(request()->hasAny(['search']))
                        <p class="text-muted mb-4">
                            Aucun exercice ne correspond à vos critères de recherche.<br>
                            Essayez de modifier votre terme de recherche.
                        </p>
                        <a href="{{ route('exercices.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-dumbbell me-2"></i>Voir tous les exercices
                        </a>
                    @else
                        <p class="text-muted mb-4">
                            La bibliothèque d'exercices sera bientôt disponible.<br>
                            Revenez prochainement pour découvrir nos exercices.
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Guide d'Utilisation -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-search text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h6 class="fw-bold">1. Explorez les Catégories</h6>
                            <p class="small text-muted">
                                Parcourez nos catégories pour trouver 
                                les exercices adaptés à vos objectifs.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-book-reader text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h6 class="fw-bold">2. Consultez les Instructions</h6>
                            <p class="small text-muted">
                                Accédez aux descriptions détaillées avec 
                                conseils de sécurité et vidéos.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-rocket text-warning" style="font-size: 2rem;"></i>
                            </div>
                            <h6 class="fw-bold">3. Pratiquez en Sécurité</h6>
                            <p class="small text-muted">
                                Suivez les recommandations pour 
                                optimiser votre entraînement.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg text-center mt-4">
                    <a href="{{ route('posts.public.index') }}">
                        <img src="{{ asset('assets/images/team/nataswim-sport-net-systemes-6.jpg') }}"
                            alt="Guide Nataswim"
                            class="img-fluid rounded-4"
                            style="max-height: 600px;">
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Espacement entre les lignes de catégories d'exercices */
.category-row {
    margin-bottom: 2rem;
}

/* Style de la carte catégorie exercice avec effet hover */
.hover-category-exercice {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.hover-category-exercice:hover {
    box-shadow: 0 0.5rem 2rem rgba(255, 136, 0, 0.25) !important;
    background-color: #fff8f0;
}

/* Image de la catégorie exercice */
.category-image-wrapper-exercice {
    position: relative;
    height: 100%;
}

.category-image-exercice {
    width: 100%;
    object-fit: cover;
}

.category-image-placeholder-exercice {
    width: 100%;
    height: 100%;
    min-height: 250px;
}

/* Liens avec effet hover exercice */
.category-link-exercice {
    transition: color 0.3s ease;
}

.hover-category-exercice:hover .category-link-exercice {
    color: #FF8800 !important;
}

/* Bouton avec effet hover exercice */
.btn-category-exercice {
    transition: all 0.3s ease;
}

.hover-category-exercice:hover .btn-category-exercice {
    background-color: #FF8800;
    border-color: #FF8800;
    color: white;
}

/* Effet hover sur les cartes d'exercices individuels */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.bg-gradient {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Responsive pour mobile */
@media (max-width: 767px) {
    /* Image centrée en haut sur mobile */
    .category-image-wrapper-exercice {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .category-image-exercice {
        border-radius: 12px 12px 0 0;
    }
    
    .category-image-placeholder-exercice {
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
    .category-image-wrapper-exercice {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-exercice {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-placeholder-exercice {
        border-radius: 12px 0 0 12px;
    }
}
</style>
@endpush