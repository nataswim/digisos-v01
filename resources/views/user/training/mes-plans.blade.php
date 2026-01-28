@extends('layouts.user')

@section('title', 'Mes Plans d\'Entraînement')

@section('content')
<div class="container-lg py-5">
    <!-- En-tête -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-2">Mes Plans d'Entraînement</h1>
                    <p class="text-muted mb-0">Suivez vos progrès et continuez vos entraînements</p>
                </div>
                <a href="{{ route('user.training.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Découvrir d'autres plans
                </a>
            </div>
        </div>
    </div>

    @if($mesPlans->count() > 0)
        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-3">
                        <h3 class="text-primary fw-bold">{{ $mesPlans->count() }}</h3>
                        <small class="text-muted">Plan(s) assigné(s)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-3">
                        <h3 class="text-success fw-bold">{{ $mesPlans->where('pivot.statut', 'en_cours')->count() }}</h3>
                        <small class="text-muted">En cours</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-3">
                        <h3 class="text-info fw-bold">{{ $mesPlans->where('pivot.statut', 'termine')->count() }}</h3>
                        <small class="text-muted">Terminé(s)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-3">
                        @php
                            $moyenneProgression = $mesPlans->avg('pivot.progression_pourcentage') ?? 0;
                        @endphp
                        <h3 class="text-warning fw-bold">{{ round($moyenneProgression) }}%</h3>
                        <small class="text-muted">Progression moyenne</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des plans -->
        <div class="row g-4">
            @foreach($mesPlans as $plan)
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        @if($plan->image)
                            <img src="{{ $plan->image }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;" 
                                 alt="{{ $plan->titre }}">
                        @else
                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                                 style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-calendar-alt fa-3x text-white opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="card-body p-4">
                            <!-- En-tête avec statut -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold mb-0">{{ $plan->titre }}</h5>
                                <span class="badge bg-{{ 
                                    $plan->pivot->statut === 'en_cours' ? 'success' : 
                                    ($plan->pivot->statut === 'termine' ? 'info' : 
                                    ($plan->pivot->statut === 'pause' ? 'warning' : 'secondary'))
                                }}">
                                    {{ $plan->pivot->statut_label ?? 'Non commencé' }}
                                </span>
                            </div>

                            <!-- Progression -->
                            @if($plan->pivot->progression_pourcentage > 0)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Progression</small>
                                        <small class="fw-bold">{{ $plan->pivot->progression_pourcentage }}%</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-{{ 
                                            $plan->pivot->statut === 'en_cours' ? 'success' : 
                                            ($plan->pivot->statut === 'termine' ? 'info' : 'warning')
                                        }}" 
                                             style="width: {{ $plan->pivot->progression_pourcentage }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <!-- Informations du plan -->
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary">{{ $plan->niveau_label }}</span>
                                <span class="badge bg-success">{{ $plan->objectif_label }}</span>
                                @if($plan->duree_semaines)
                                    <span class="badge bg-info">{{ $plan->duree_semaines }} sem.</span>
                                @endif
                                <span class="badge bg-warning text-dark">{{ $plan->cycles->count() }} cycle(s)</span>
                            </div>

                            <!-- Description -->
                            @if($plan->description)
                                <p class="card-text text-muted small">
                                    {!! Str::limit(strip_tags($plan->description), 120) !!}
                                </p>
                            @endif

                            <!-- Dates -->
                            <div class="small text-muted mb-3">
                                @if($plan->pivot->date_debut)
                                    <div><i class="fas fa-play me-1"></i>Commencé le {{ \Carbon\Carbon::parse($plan->pivot->date_debut)->format('d/m/Y') }}</div>
                                @endif
                                @if($plan->pivot->date_fin_prevue)
                                    <div><i class="fas fa-flag-checkered me-1"></i>Fin prévue le {{ \Carbon\Carbon::parse($plan->pivot->date_fin_prevue)->format('d/m/Y') }}</div>
                                @endif
                            </div>

                            <!-- Notes utilisateur -->
                            @if($plan->pivot->notes_utilisateur)
                                <div class="alert alert-info alert-sm py-2 mb-3">
                                    <small>
                                        <i class="fas fa-sticky-note me-1"></i>
                                        {{ $plan->pivot->notes_utilisateur }}
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-white border-top-0 p-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('user.training.show', $plan) }}" 
                                   class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-eye me-2"></i>Voir le plan
                                </a>
                                @if($plan->pivot->statut === 'en_cours' || $plan->pivot->statut === 'non_commence')
                                    <!-- Lien vers la prochaine séance ou le premier cycle -->
                                    @php
                                        $premierCycle = $plan->cycles->sortBy('pivot.ordre')->first();
                                    @endphp
                                    @if($premierCycle)
                                        <a href="{{ route('user.training.cycle', [$plan, $premierCycle]) }}" 
                                           class="btn btn-success">
                                            <i class="fas fa-play me-2"></i>Continuer
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Aucun plan -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-alt fa-4x text-muted mb-4 opacity-25"></i>
                        <h4 class="fw-bold mb-3">Aucun plan d'entraînement</h4>
                        <p class="text-muted mb-4">
                            Vous n'avez pas encore de plan d'entraînement assigné.<br>
                            Découvrez nos plans personnalisés pour commencer votre parcours fitness.
                        </p>
                        <a href="{{ route('user.training.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>Découvrir les plans
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.alert-sm {
    padding: 0.375rem 0.75rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush