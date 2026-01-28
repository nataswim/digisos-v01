@extends('layouts.public')

@section('title', 'Fiches Pratiques')
@section('meta_description', 'Découvrez nos fiches pratiques organisées par thématique pour optimiser votre entraînement et performance sportive.')

@section('content')



<section class="position-relative text-white py-5 nataswim-titre3 overflow-hidden" style="min-height: 600px;">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 1;">
        <source src="{{ asset('assets/images/team/nataswim-sport-training-0.mp4') }}" type="video/mp4">
    </video>

    <!-- Overlay sombre pour meilleure lisibilité -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;"></div>

    <!-- Contenu -->
    <div class="container-lg py-4 position-relative" style="z-index: 3;">
        <div class="row align-items-center">
            <div class="col-lg mb-2 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('posts.public.index') }}" style=" color: #fff; text-decoration: none; ">
                    
                    <h1 class="display-4 fw-bold mb-0 shadow-lg border-0" style="text-shadow: 2px 2px 4px rgb(3 64 71);background-color: #63d0c7;padding: 10px;border-radius: 10px;"> <i class="fas fa-file-alt me-3"></i>Fiches Thématique</h1>
                    </a>
                </div>

                <p class="lead mb-4">
                    Retrouvez nos derniers fiches. Expertise, conseils techniques et actualités.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Fiches en vedette -->
@if($featuredFiches->count() > 0)
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="h3 mb-0">
                <i class="fas fa-star text-warning me-2"></i>Fiches en Vedette
            </h2>
        </div>
        
        <div class="row g-4">
            @foreach($featuredFiches as $fiche)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-lg hover-lift">
                        @if($fiche->image)
                            <img src="{{ $fiche->image }}" 
                                 class="card-img-top" 
                                 alt="{{ $fiche->title }}">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" 
                                 style="height: 220px;">
                                <i class="fas fa-file-alt fa-4x text-white opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($fiche->category)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-folder me-1"></i>{{ $fiche->category->name }}
                                    </span>
                                @endif
                                @if($fiche->visibility === 'authenticated')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-lock me-1"></i>Membres
                                    </span>
                                @endif
                                <span class="badge bg-success">
                                    <i class="fas fa-star me-1"></i>En vedette
                                </span>
                            </div>
                            
                            <h5 class="card-title mb-3">{{ $fiche->title }}</h5>
                            
                            <p class="card-text text-muted flex-grow-1">
                                {!! Str::limit(strip_tags($fiche->short_description), 120) !!}
                            </p>
                            
                            <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-eye me-1"></i>55{{ number_format($fiche->views_count) }} lectures
                                </small>
                                @if($fiche->category)
                                    <a href="{{ route('public.fiches.show', [$fiche->category, $fiche]) }}" 
                                       class="btn btn-sm btn-primary">
                                        Découvrir <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Navigation par Catégories -->
<section class="py-5 {{ $featuredFiches->count() > 0 ? 'bg-white' : 'bg-light' }}">
    <div class="container-lg">
        <!-- Catégories de fiches -->
        @if($categories->count() > 0)
            <!-- Boucle sur chaque catégorie -->
            @foreach($categories as $category)
                <div class="category-row mb-4">
                    <div class="card border-0 shadow-sm hover-category-fiche">
                        <div class="row g-0">
                            <!-- Image de la catégorie (gauche sur desktop, haut sur mobile) -->
                            <div class="col-12 col-md-3">
                                <div class="category-image-wrapper-fiche">
                                    @if($category->image)
                                        <img src="{{ $category->image }}" 
                                             alt="{{ $category->name }}"
                                             class="category-image-fiche">
                                    @else
                                        <div class="category-image-placeholder-fiche d-flex align-items-center justify-content-center text-white"
                                             style="background: linear-gradient(135deg, {{ $loop->index % 4 == 0 ? '#0d6efd' : ($loop->index % 4 == 1 ? '#198754' : ($loop->index % 4 == 2 ? '#0dcaf0' : '#ffc107')) }} 0%, {{ $loop->index % 4 == 0 ? '#084298' : ($loop->index % 4 == 1 ? '#0f5132' : ($loop->index % 4 == 2 ? '#087990' : '#cc9a06')) }} 100%);">
                                            <i class="fas fa-folder" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge nombre de fiches -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-success shadow-sm fs-6">
                                            
                                            {{ $category->published_fiches_count }} fiche{{ $category->published_fiches_count > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenu central (titre, description) -->
                            <div class="col-12 col-md-7">
                                <div class="card-body">
                                    <!-- Nom de la catégorie -->
                                    <h3 class="card-title h4 mb-3">
                                        <a href="{{ route('public.fiches.category', $category) }}" 
                                           class="text-decoration-none text-dark category-link-fiche">
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
                                            Découvrez nos fiches pratiques dans la catégorie {{ $category->name }}.
                                        </p>
                                    @endif

                                    <!-- Informations supplémentaires -->

                                </div>
                            </div>

                            <!-- Bouton à droite -->
                            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                                <div class="p-3 w-100">
                                    <a href="{{ route('public.fiches.category', $category) }}" 
                                       class="btn btn-outline-primary w-100 btn-category-fiche">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        <span class="d-none d-lg-inline">Découvrir</span>
                                        <span class="d-inline d-lg-none">Découvrir les fiches</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted">Aucune catégorie disponible pour le moment</h5>
            </div>
        @endif

        
    </div>
</section>

@endsection

@push('styles')
<style>
/* Espacement entre les lignes de catégories de fiches */
.category-row {
    margin-bottom: 2rem;
}

/* Style de la carte catégorie fiche avec effet hover */
.hover-category-fiche {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.hover-category-fiche:hover {
    box-shadow: 0 0.5rem 2rem rgba(4, 173, 185, 0.25) !important;
    background-color: #f0fbfc;
}

/* Image de la catégorie fiche */
.category-image-wrapper-fiche {
    position: relative;
    height: 100%;
}

.category-image-fiche {
    width: 100%;
    object-fit: cover;
}

.category-image-placeholder-fiche {
    width: 100%;
    height: 100%;
    min-height: 250px;
}

/* Liens avec effet hover fiche */
.category-link-fiche {
    transition: color 0.3s ease;
}

.hover-category-fiche:hover .category-link-fiche {
    color: #04adb9 !important;
}

/* Bouton avec effet hover fiche */
.btn-category-fiche {
    transition: all 0.3s ease;
}

.hover-category-fiche:hover .btn-category-fiche {
    background-color: #04adb9;
    border-color: #04adb9;
    color: white;
}

/* Effet hover sur les cartes de fiches en vedette */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}



.badge {
    font-size: 0.75rem;
}

/* Responsive pour mobile */
@media (max-width: 767px) {
    /* Image centrée en haut sur mobile */
    .category-image-wrapper-fiche {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .category-image-fiche {
        border-radius: 12px 12px 0 0;
    }
    
    .category-image-placeholder-fiche {
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
    .category-image-wrapper-fiche {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-fiche {
        border-radius: 12px 0 0 12px;
    }
    
    .category-image-placeholder-fiche {
        border-radius: 12px 0 0 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée pour les cards
    const cards = document.querySelectorAll('.hover-category-fiche, .hover-lift');
    
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