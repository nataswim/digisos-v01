@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', 'Plans d\'Entraînement - Programmes Personnalisés')
@section('meta_description', 'Découvrez nos plans d\'entraînement personnalisés pour tous niveaux. Programmes structurés avec exercices détaillés et progression adaptée.')

{{-- Open Graph / Facebook --}}
@section('og_type', 'website')
@section('og_title', 'Plans d\'Entraînement Personnalisés - Sport Net Systèmes')
@section('og_description', 'Programmes de musculation et entraînement structurés pour tous niveaux avec suivi de progression')
@section('og_url', route('plans.index'))
@section('og_image', asset('assets/images/team/nataswim-application-banner-4.jpg'))

@section('content')
<!-- En-tête de section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">Programme de musculation</h1>
                <p class="lead mb-0">Découvrez nos programmes d'entraînement structurés et personnalisés pour atteindre vos objectifs fitness.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                     style="width: 120px; height: 120px;">
                    <i class="fas fa-dumbbell" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filtres et recherche -->
<section class="py-4 bg-white border-bottom">
    <div class="container-lg">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           class="form-control"
                           placeholder="Rechercher un plan...">
                </div>
            </div>
            <div class="col-md-2">
                <select name="niveau" class="form-select">
                    <option value="">Tous niveaux</option>
                    <option value="debutant" {{ $niveau === 'debutant' ? 'selected' : '' }}>Débutant</option>
                    <option value="intermediaire" {{ $niveau === 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="avance" {{ $niveau === 'avance' ? 'selected' : '' }}>Avancé</option>
                    <option value="special" {{ $niveau === 'special' ? 'selected' : '' }}>Spécial</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="objectif" class="form-select">
                    <option value="">Tous objectifs</option>
                    <option value="force" {{ $objectif === 'force' ? 'selected' : '' }}>Force</option>
                    <option value="endurance" {{ $objectif === 'endurance' ? 'selected' : '' }}>Endurance</option>
                    <option value="perte_poids" {{ $objectif === 'perte_poids' ? 'selected' : '' }}>Perte de poids</option>
                    <option value="prise_masse" {{ $objectif === 'prise_masse' ? 'selected' : '' }}>Prise de masse</option>
                    <option value="recuperation" {{ $objectif === 'recuperation' ? 'selected' : '' }}>Récupération</option>
                    <option value="mixte" {{ $objectif === 'mixte' ? 'selected' : '' }}>Mixte</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="duree" class="form-select">
                    <option value="">Toutes durées</option>
                    <option value="courte" {{ $duree === 'courte' ? 'selected' : '' }}>Courte (≤4 sem.)</option>
                    <option value="moyenne" {{ $duree === 'moyenne' ? 'selected' : '' }}>Moyenne (5-12 sem.)</option>
                    <option value="longue" {{ $duree === 'longue' ? 'selected' : '' }}>Longue (>12 sem.)</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary text-white flex-fill">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                    @if($search || $niveau || $objectif || $duree)
                        <a href="{{ route('plans.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Plans d'entraînement -->
<section class="py-5 bg-light">
    <div class="container-lg">
        @if($plans->count() > 0)
            <!-- Statistiques de recherche -->
            @if($search || $niveau || $objectif || $duree)
                <div class="mb-4">
                    <div class="alert alert-info border-0">
                        <i class="fas fa-dumbbell me-2"></i>
                        {{ $plans->total() }} plan(s) trouvé(s)
                        @if($search)
                            pour "<strong>{{ $search }}</strong>"
                        @endif
                        @if($niveau)
                            niveau "<strong>{{ ucfirst($niveau) }}</strong>"
                        @endif
                        @if($objectif)
                            objectif "<strong>{{ ucfirst(str_replace('_', ' ', $objectif)) }}</strong>"
                        @endif
                    </div>
                </div>
            @endif

            <div class="row g-4">
                @foreach($plans as $plan)
                    <div class="col-lg-4 col-md-6">
                        <article class="card border-0 shadow-sm h-100 hover-lift">
                            <!-- Image et badges -->
                            <div class="position-relative">
                                @if($plan->image)
                                    <img src="{{ $plan->image }}" class="card-img-top" alt="{{ $plan->titre }}" 
                                         style="height: 220px; object-fit: cover;">
                                @else
                                    <div class="bg-gradient-secondary d-flex align-items-center justify-content-center text-white" 
                                         style="height: 220px;">
                                        <div class="text-center">
                                            <i class="fas fa-dumbbell fa-3x opacity-50 mb-3"></i>
                                            <div>{{ $plan->objectif_label }}</div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Badges en overlay -->
                                <div class="position-absolute top-0 end-0 p-3">
                                    @if($plan->is_featured)
                                        <span class="badge bg-warning text-dark mb-2 d-block">
                                            <i class="fas fa-star me-1"></i>À la une
                                        </span>
                                    @endif
                                    
                                    <span class="badge bg-{{ $plan->niveau === 'debutant' ? 'success' : ($plan->niveau === 'avance' ? 'danger' : 'warning') }} d-block">
                                        {{ $plan->niveau_label }}
                                    </span>
                                </div>

                                <!-- Indicateur de durée -->
                                <div class="position-absolute bottom-0 start-0 p-3">
                                    @if($plan->duree_semaines)
                                        <span class="badge bg-dark bg-opacity-75">
                                            <i class="fas fa-dumbbell me-1"></i>{{ $plan->duree_semaines }} semaines
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-body d-flex flex-column p-4">
                                <!-- Métadonnées -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $plan->objectif_label }}
                                        </span>
                                        <small class="text-muted d-flex align-items-center">
                                            <i class="fas fa-sync-alt me-1"></i>
                                            {{ $plan->cycles->count() }} cycle(s)
                                        </small>
                                    </div>
                                </div>
                                
                                <!-- Titre -->
<h5 class="card-title mb-3">
    @auth
        @if(auth()->user()->hasRole('user') || auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin'))
            <a href="{{ route('plans.show', $plan) }}" 
               class="text-decoration-none text-dark stretched-link">
                {{ $plan->titre }}
            </a>
        @else
            <span class="text-dark">{{ $plan->titre }}</span>
        @endif
    @else
        <a href="{{ route('plans.show', $plan) }}" 
           class="text-decoration-none text-dark stretched-link">
            {{ $plan->titre }}
        </a>
    @endauth
</h5>
                                
                                <!-- Description -->
                                @if($plan->description)
                                    <p class="card-text text-muted flex-grow-1 mb-3">
                                        {!! Str::limit(strip_tags($plan->description), 120) !!}
                                    </p>
                                @endif

                                <!-- Prérequis -->
                                @if($plan->prerequis)
                                    <div class="mb-3">
                                        <small class="text-warning fw-semibold">
                                            <i class="fas fa-info-circle me-1"></i>Prérequis
                                        </small>
                                        <p class="small text-muted mb-0">
                                            {!! Str::limit(strip_tags($plan->prerequis), 80) !!}
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Footer avec informations d'accès -->
                                <div class="mt-auto">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="fas fa-dumbbell me-1"></i>
                                                {{ $plan->getTotalSeances() }} séances
                                            </span>
                                        </div>
                                        
                                        <div class="d-flex align-items-center gap-2">
                                            @auth
                                                @if(auth()->user()->hasRole('user') || auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin'))
                                                    <div class="text-primary fw-medium">
                                                        Voir le plan
                                                        <i class="fas fa-arrow-right ms-1"></i>
                                                    </div>
                                                @else
                                                    <small class="text-warning">
                                                        <i class="fas fa-lock me-1"></i>Accès membre
                                                    </small>
                                                @endif
                                            @else
                                                <small class="text-warning">
                                                    <i class="fas fa-lock me-1"></i>Connexion requise
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($plans->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $plans->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- État vide -->
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                     style="width: 120px; height: 120px;">
                    <i class="fas fa-search text-muted fa-3x"></i>
                </div>
                <h3 class="fw-bold mb-3">Aucun plan trouvé</h3>
                <p class="text-muted mb-4">
                    @if($search || $niveau || $objectif || $duree)
                        Aucun plan ne correspond à vos critères de recherche.
                    @else
                        Il n'y a pas encore de plans d'entraînement disponibles.
                    @endif
                </p>
                @if($search || $niveau || $objectif || $duree)
                    <a href="{{ route('plans.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Voir tous les plans
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- Plans en vedette sidebar -->
@if($plansFeatured->count() > 0)
    <section class="py-5 bg-white">
        <div class="container-lg">
            <h2 class="fw-bold mb-4 text-center">
                <i class="fas fa-star text-warning me-2"></i>Plans
            </h2>
            <div class="row g-4">
                @foreach($plansFeatured as $plan)
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm hover-lift">
                            @if($plan->image)
                                <img src="{{ $plan->image }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $plan->titre }}">
                            @endif
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2">
                                    <a href="{{ route('plans.show', $plan) }}" class="text-decoration-none">
                                        {{ $plan->titre }}
                                    </a>
                                </h6>
                                <div class="d-flex gap-1 mb-2">
                                    <span class="badge bg-primary small">{{ $plan->objectif_label }}</span>
                                    <span class="badge bg-secondary small">{{ $plan->niveau_label }}</span>
                                </div>
                                @if($plan->duree_semaines)
                                    <small class="text-muted">{{ $plan->duree_semaines }} semaines</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Call-to-action pour les visiteurs -->
@guest
    <section class="py-5 bg-primary text-white">
        <div class="container-lg text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">Accédez à nos plans d'entraînement</h2>
                    <p class="lead mb-4">
                        Rejoignez notre communauté pour débloquer tous nos plans d'entraînement personnalisés et commencer votre transformation.
                    </p>
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Créer un compte gratuit
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    @if(!auth()->user()->hasRole('user') && !auth()->user()->hasRole('editor') && !auth()->user()->hasRole('admin'))
        <section class="py-5 bg-primary text-white">
            <div class="container-lg text-center">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-3">Upgrade votre compte</h2>
                        <p class="lead mb-4">
                            Pour accéder aux plans d'entraînement, vous devez avoir un rôle utilisateur approprié.
                        </p>
                        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-envelope me-2"></i>Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endguest

<!-- Navigation vers autres sections -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <h5 class="fw-bold mb-3">Découvrez aussi</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('exercices.index') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-dumbbell me-2"></i>Exercices
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('tools.index') }}" class="btn btn-outline-success btn-lg w-100">
                            <i class="fas fa-calculator me-2"></i>Outils de calcul
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('posts.public.index') }}" class="btn btn-outline-info btn-lg w-100">
                            <i class="fas fa-newspaper me-2"></i>Articles & conseils
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>


.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}

.hover-lift {
    transition: transform 0.2s ease-in-out;
}

.hover-lift:hover {
    transform: translateY(-5px);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.1);
}
</style>
@endpush
