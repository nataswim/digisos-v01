@extends('layouts.public')

@section('title', 'Séances et Plans d\'Entraînement Natation & Triathlon - Hassan EL HAOUAT')
@section('meta_description', 'Découvrez nos séances d\'entraînement et plans natation et triathlon organisés par discipline : natation, course à pied, musculation. Programmes structurés pour optimiser votre progression.')
@section('meta_keywords', 'séances entraînement, plans entraînement sportif, programme natation, workout français, entraînement course à pied, programme musculation')

@section('content')



<section class="position-relative text-white py-5 nataswim-titre3 overflow-hidden" style="min-height: 600px;">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 1;">
        <source src="{{ asset('assets/images/team/nataswim-sport-training-4.mp4') }}" type="video/mp4">
    </video>

    <!-- Overlay sombre pour meilleure lisibilité -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;"></div>

    <!-- Contenu -->
    <div class="container-lg py-4 position-relative" style="z-index: 3;">
        <div class="row align-items-center">
            <div class="col-lg mb-2 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('posts.public.index') }}" style=" color: #fff; text-decoration: none; ">
                    
                    <h1 class="display-4 fw-bold mb-0 shadow-lg border-0" style="text-shadow: 2px 2px 4px rgb(3 64 71);background-color: #63d0c7;padding: 10px;border-radius: 10px;"> <i class="fas fa-star me-3"></i>Séances et Plans</h1>
                    </a>
                </div>

                <p class="lead mb-4">
                    <strong>Programmes et Plans d'Entraînement</strong> pour vous accompagner
                    dans votre progression avec des séances organisées par niveau et par discipline.
                </p>
            </div>
        </div>
    </div>
</section>






<!-- Navigation par Disciplines Sportives -->
<section class="py-5 bg-light">
    <div class="container-lg">

        <!-- Sections de séances -->
        @if($sections->count() > 0)
            <!-- Boucle sur chaque section -->
            @foreach($sections as $section)
                <div class="category-row mb-4">
                    <div class="card border-0 shadow-sm hover-category-workout">
                        <div class="row g-0">

                            <!-- Contenu central (titre, description, programmes) -->
                            <div>
                                <div class="card-body">
                                    <!-- Nom de la section -->
                                    <h3 class="card-title h4 mb-3">
                                       <i class="fas fa-layer-group me-1"></i> <a href="{{ route('public.workouts.section', $section) }}" 
                                           class="text-decoration-none text-dark category-link-workout">
                                            {{ $section->name }}
                                        </a>
                                    </h3>

                                    <!-- Description -->
                                    @if($section->description)
                                        <p class="card-text text-muted mb-3">
                                            {!! Str::limit(strip_tags($section->description), 180) !!}
                                        </p>
                                    @else

                                    @endif

<!-- Badge nombre total de séances -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-info shadow-sm fs-6">
                                            
                                            {{ $section->categories->sum('workouts_count') }} séance{{ $section->categories->sum('workouts_count') > 1 ? 's' : '' }}
                                        </span>
                                    </div>



                                    <!-- Liste des programmes disponibles -->
                                    @if($section->categories->count() > 0)
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($section->categories->take(5) as $category)
                                                    <span class="badge bg-primary shadow-sm fs-6"><i class="fas fa-list me-1"></i>
                                                        {{ $category->name }} 
                                                        <span class="badge bg-danger ms-1">{{ $category->workouts_count }}</span>
                                                    </span>
                                                @endforeach
                                                @if($section->categories->count() > 5)
                                                    <span class="badge bg-light text-dark border">
                                                        +{{ $section->categories->count() - 5 }} autres
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Bouton à droite -->
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="p-3 w-100">
                                    <a href="{{ route('public.workouts.section', $section) }}" 
                                       class="btn btn-outline-primary w-100 btn-category-workout">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        <span class="d-none d-lg-inline">Découvrir</span>
                                        <span class="d-inline d-lg-none">Découvrir les séances</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-layer-group fa-3x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted">Aucune discipline disponible pour le moment</h5>
                <p class="text-muted">Revenez bientôt pour découvrir nos programmes d'entraînement</p>
            </div>
        @endif

        <!-- Avantages des programmes -->
        <div class="row g-4 mb-5 mt-5">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-chart-line text-primary fs-4"></i>
                        </div>
                        <h4 class="h6 fw-bold">Progression Structurée</h4>
                        <p class="small text-muted mb-0">
                            Plans évolutifs adaptés à votre niveau
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-bullseye text-success fs-4"></i>
                        </div>
                        <h4 class="h6 fw-bold">Objectifs Ciblés</h4>
                        <p class="small text-muted mb-0">
                            Séances spécifiques pour chaque objectif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-clipboard-check text-info fs-4"></i>
                        </div>
                        <h4 class="h6 fw-bold">Instructions Détaillées</h4>
                        <p class="small text-muted mb-0">
                            Descriptions complètes et faciles à suivre
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-users text-warning fs-4"></i>
                        </div>
                        <h4 class="h6 fw-bold">Tous Niveaux</h4>
                        <p class="small text-muted mb-0">
                            Du débutant au compétiteur confirmé
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container-lg text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">


                @if($sections->count() > 0)
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @foreach($sections->take(3) as $section)
                    <a href="{{ route('public.workouts.section', $section) }}"
                        class="btn {{ $loop->first ? 'btn-light' : 'btn-outline-light' }} btn-lg">
                        <i class="fas fa-layer-group me-2"></i>{{ $section->name }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Section SEO - Contenu additionnel -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="col-lg text-center">
                            <a href="{{ route('posts.public.index') }}">
                                <img src="{{ asset('assets/images/team/nataswim-sport-net-systemes-5.jpg') }}"
                                    alt="Guide Nataswim"
                                    class="img-fluid rounded-4"
                                    style="max-height: 600px;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Espacement entre les lignes de sections workout */
.category-row {
    margin-bottom: 2rem;
}

/* Style de la carte section workout avec effet hover */
.hover-category-workout {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.hover-category-workout:hover {
    box-shadow: 0 0.5rem 2rem rgba(255, 215, 0, 0.3) !important;
    background-color: #fffdf0;
}

/* Image de la section workout */
.category-image-wrapper-workout {
    position: relative;
    height: 100%;
    min-height: 250px;
}

.category-image-placeholder-workout {
    width: 100%;
    height: 100%;
    min-height: 250px;
}

/* Liens avec effet hover workout */
.category-link-workout {
    transition: color 0.3s ease;
}

.hover-category-workout:hover .category-link-workout {
    color: #ffc107 !important;
}

/* Bouton avec effet hover workout */
.btn-category-workout {
    transition: all 0.3s ease;
}

.hover-category-workout:hover .btn-category-workout {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

/* Effet hover sur les cartes d'avantages */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

/* Responsive pour mobile */
@media (max-width: 767px) {
    /* Image centrée en haut sur mobile */
    .category-image-wrapper-workout {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .category-image-placeholder-workout {
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
    .category-image-wrapper-workout {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-placeholder-workout {
        border-radius: 12px 0 0 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation d'entrée pour les cards
        const cards = document.querySelectorAll('.hover-category-workout, .hover-lift');

        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endpush