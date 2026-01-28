@extends('layouts.user')

@section('title', 'Plans d\'Entraînement')

@section('content')
<div class="container-lg py-5">
    <!-- En-tête -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center">
                <h1 class="fw-bold mb-3">Programme de musculation</h1>
                <p class="text-muted mb-0">Découvrez nos programmes d'entraînement adaptés à vos objectifs</p>
            </div>
        </div>
    </div>

    <!-- Mes plans actuels -->
    @if($mesPlans->count() > 0)
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white p-4">
                        <h4 class="mb-0">
                            <i class="fas fa-user-check me-2"></i>Mes Plans en Cours
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($mesPlans as $monPlan)
                                <div class="col-lg-6">
                                    <div class="card border h-100">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="mb-0">{{ $monPlan->titre }}</h5>
                                                <span class="badge bg-{{ $monPlan->pivot->statut_color }}-subtle text-{{ $monPlan->pivot->statut_color }}">
                                                    {{ $monPlan->pivot->statut_label }}
                                                </span>
                                            </div>
                                            
                                            @if($monPlan->pivot->progression_pourcentage > 0)
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between small mb-1">
                                                        <span>Progression</span>
                                                        <span>{{ $monPlan->pivot->progression_pourcentage }}%</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" 
                                                             style="width: {{ $monPlan->pivot->progression_pourcentage }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-muted small">
                                                    @if($monPlan->pivot->date_debut)
                                                        Commencé le {{ \Carbon\Carbon::parse($monPlan->pivot->date_debut)->format('d/m/Y') }}
                                                    @endif
                                                </div>
                                                <a href="{{ route('user.training.show', $monPlan) }}" class="btn btn-primary btn-sm">
                                                    Continuer
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="{{ route('user.training.mes-plans') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Voir tous mes plans
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Plans en vedette -->
    @if($plansFeatured->count() > 0)
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="fw-semibold mb-4">
                    <i class="fas fa-star text-warning me-2"></i>Plans Recommandés
                </h3>
                <div class="row g-4">
                    @foreach($plansFeatured as $plan)
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                @if($plan->image)
                                    <img src="{{ $plan->image }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="">
                                @else
                                    <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-calendar-alt fa-3x text-white opacity-50"></i>
                                    </div>
                                @endif
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $plan->titre }}</h5>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-star me-1"></i>Premium
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="badge bg-{{ $plan->niveau === 'debutant' ? 'success' : ($plan->niveau === 'avance' ? 'danger' : 'warning') }}-subtle text-{{ $plan->niveau === 'debutant' ? 'success' : ($plan->niveau === 'avance' ? 'danger' : 'warning') }}">
                                            {{ $plan->niveau_label }}
                                        </span>
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $plan->objectif_label }}
                                        </span>
                                        @if($plan->duree_semaines)
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                {{ $plan->duree_semaines_formattee }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                   @if($plan->description)
    <p class="card-text text-muted small">{!! Str::limit(strip_tags($plan->description), 100) !!}</p>
@endif
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <small class="text-muted">{{ $plan->cycles->count() }} cycles</small>
                                        <a href="{{ route('user.training.show', $plan) }}" class="btn btn-primary btn-sm">
                                            Découvrir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="form-control"
                                   placeholder="Rechercher un plan...">
                        </div>
                        <div class="col-md-2">
                            <select name="niveau" class="form-select">
                                <option value="">Tous niveaux</option>
                                <option value="debutant" {{ request('niveau') === 'debutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermediaire" {{ request('niveau') === 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avance" {{ request('niveau') === 'avance' ? 'selected' : '' }}>Avancé</option>
                                <option value="special" {{ request('niveau') === 'special' ? 'selected' : '' }}>Spécial</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="objectif" class="form-select">
                                <option value="">Tous objectifs</option>
                                <option value="force" {{ request('objectif') === 'force' ? 'selected' : '' }}>Force</option>
                                <option value="endurance" {{ request('objectif') === 'endurance' ? 'selected' : '' }}>Endurance</option>
                                <option value="perte_poids" {{ request('objectif') === 'perte_poids' ? 'selected' : '' }}>Perte de poids</option>
                                <option value="prise_masse" {{ request('objectif') === 'prise_masse' ? 'selected' : '' }}>Prise de masse</option>
                                <option value="recuperation" {{ request('objectif') === 'recuperation' ? 'selected' : '' }}>Récupération</option>
                                <option value="mixte" {{ request('objectif') === 'mixte' ? 'selected' : '' }}>Mixte</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                                @if(request()->hasAny(['search', 'niveau', 'objectif']))
                                    <a href="{{ route('user.training.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des plans disponibles -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-semibold mb-4">Tous les Plans Disponibles</h3>
        </div>
    </div>

    @if($plans->count() > 0)
        <div class="row g-4">
            @foreach($plans as $plan)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        @if($plan->image)
                            <img src="{{ $plan->image }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-calendar-alt fa-3x text-muted opacity-25"></i>
                            </div>
                        @endif
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $plan->titre }}</h5>
                                @if($plan->is_featured)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>Premium
                                    </span>
                                @endif
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-{{ $plan->niveau === 'debutant' ? 'success' : ($plan->niveau === 'avance' ? 'danger' : 'warning') }}-subtle text-{{ $plan->niveau === 'debutant' ? 'success' : ($plan->niveau === 'avance' ? 'danger' : 'warning') }}">
                                    {{ $plan->niveau_label }}
                                </span>
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $plan->objectif_label }}
                                </span>
                                @if($plan->duree_semaines)
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        {{ $plan->duree_semaines_formattee }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($plan->description)
    <p class="card-text text-muted small flex-fill">{!! Str::limit(strip_tags($plan->description), 120) }}</p>
@endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <small class="text-muted">{{ $plan->cycles->count() }} cycles</small>
                                <a href="{{ route('user.training.show', $plan) }}" class="btn btn-outline-primary btn-sm">
                                    Voir le plan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
@if($plans->hasPages())
    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="mt-5 d-flex justify-content-center">
                {{ $plans->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
    @else
        <div class="text-center py-5">
            <i class="fas fa-calendar-alt fa-3x text-muted mb-4 opacity-25"></i>
            <h4>Aucun plan disponible</h4>
            @if(request()->hasAny(['search', 'niveau', 'objectif']))
                <p class="text-muted mb-4">Aucun plan ne correspond à vos critères de recherche.</p>
                <a href="{{ route('user.training.index') }}" class="btn btn-primary">
                    Voir tous les plans
                </a>
            @else
                <p class="text-muted mb-4">Les plans d'entraînement seront bientôt disponibles.</p>
            @endif
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>


.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}
</style>
@endpush