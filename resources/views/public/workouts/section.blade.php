@extends('layouts.public')

@section('title', 'Séances d\'Entraînement ' . $section->name)
@section('meta_description', $section->description ?? 'Découvrez tous les programmes d\'entraînement ' . $section->name . ' : séances structurées par niveau, du débutant au confirmé.')
@section('meta_keywords', 'entraînement ' . strtolower($section->name) . ', séances ' . strtolower($section->name) . ', programme ' . strtolower($section->name) . ', plan entraînement')

@section('content')

<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">
                     {{ $section->name }}
                </h1>
                
                @if($section->description)
                    <p class="lead mb-0">{{ $section->description }}</p>
                @else
                @endif
                
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mt-4">
                    <span class="badge bg-light text-dark fs-6">
                        <i class="fas fa-folder me-1"></i>{{ $categories->count() }} programme{{ $categories->count() > 1 ? 's' : '' }}
                    </span>
                    <span class="badge bg-light text-dark fs-6">
                        <i class="fas fa-running me-1"></i>{{ $categories->sum('workouts_count') }} séance{{ $categories->sum('workouts_count') > 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Liste des programmes -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-primary rounded px-3 py-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('public.workouts.index') }}" class="text-white">
                        <i class="fas fa-home me-1"></i>Séances d'Entraînement
                    </a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">
                    {{ $section->name }}
                </li>
            </ol>
        </nav>

        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">
                <i class="fas fa-folder-open me-2 text-primary"></i>
                 {{ $section->name }}
            </h2>
        </div>

        @if($categories->count() > 0)
            <!-- Boucle sur chaque catégorie -->
            @foreach($categories as $category)
                <div class="category-row mb-4">
                    <div class="card border-0 shadow-sm hover-category-section">
                        <div class="row g-0">
                            

                            <!-- Contenu central (titre, description) -->
                            <div>
                                <div class="card-body">
                                    <!-- Nom de la catégorie -->
                                    <h3 class="card-title h4 mb-3">
                                        <a href="{{ route('public.workouts.category', [$section, $category]) }}" 
                                           class="text-decoration-none text-dark category-link-section">
                                            {{ $category->name }}
                                        </a>
                                    </h3>

                                    <!-- Description -->
                                    @if($category->description)
                                        <p class="card-text text-muted mb-3">
                                            {!! Str::limit(strip_tags($category->description), 180) !!}
                                        </p>
                                    @else
                                        
                                    @endif

                                    <!-- Informations supplémentaires -->
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <div class="badge bg-danger shadow-sm fs-6">
                                            <i class="fas fa-check-circle me-1"></i>
                                            {{ $category->workouts_count }} Séance{{ $category->workouts_count > 1 ? 's' : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton à droite -->
                            <div>
                                <div class="p-3 w-100">
                                    <a href="{{ route('public.workouts.category', [$section, $category]) }}" 
                                       class="btn btn-outline-primary w-100 btn-category-section">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        <span class="d-none d-lg-inline">Voir</span>
                                        <span class="d-inline d-lg-none">Voir les séances</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="fas fa-folder fa-3x text-muted mb-3 opacity-25"></i>
                    <h3 class="text-muted mb-3 h5">Aucun programme disponible dans cette discipline</h3>
                    <p class="text-muted">Les programmes d'entraînement seront bientôt ajoutés</p>
                    <a href="{{ route('public.workouts.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux disciplines
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Navigation rapide -->
<section class="py-4 bg-white border-top">
    <div class="container-lg">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('public.workouts.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-th me-2"></i>Toutes les disciplines
            </a>
        </div>
    </div>
</section>

<!-- Section SEO -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-3">Programmes d'Entraînement {{ $section->name }}</h2>
                        <p class="text-muted">
                            Nos <strong>programmes {{ $section->name }}</strong> sont structurés 
                            pour vous faire progresser efficacement. Chaque <strong>séance d'entraînement</strong> 
                            est conçue avec des objectifs précis et une progression logique adaptée à votre niveau.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Espacement entre les lignes de catégories section */
.category-row {
    margin-bottom: 2rem;
}

/* Style de la carte catégorie section avec effet hover */
.hover-category-section {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.hover-category-section:hover {
    box-shadow: 0 0.5rem 2rem rgba(255, 215, 0, 0.3) !important;
    background-color: #fffdf0;
}

/* Image de la catégorie section */
.category-image-wrapper-section {
    position: relative;
    height: 100%;
    min-height: 250px;
}

.category-image-placeholder-section {
    width: 100%;
    height: 100%;
    min-height: 250px;
}

/* Liens avec effet hover section */
.category-link-section {
    transition: color 0.3s ease;
}

.hover-category-section:hover .category-link-section {
    color: #ffc107 !important;
}

/* Bouton avec effet hover section */
.btn-category-section {
    transition: all 0.3s ease;
}

.hover-category-section:hover .btn-category-section {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

/* Effet hover général */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}



/* Responsive pour mobile */
@media (max-width: 767px) {
    /* Image centrée en haut sur mobile */
    .category-image-wrapper-section {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .category-image-placeholder-section {
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
    .category-image-wrapper-section {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-placeholder-section {
        border-radius: 12px 0 0 12px;
    }
}
</style>
@endpush