@extends('layouts.admin')

@section('title', 'Détail plan')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>{{ $plan->titre }}
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">{{ $plan->objectif_label }}</span>
                            @if($plan->is_featured)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>À la une
                                </span>
                            @endif
                            <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }}">
                                {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($plan->image)
                        <div class="mb-4">
                            <img src="{{ $plan->image }}" 
                                 alt="{{ $plan->titre }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 200px;">
                        </div>
                    @endif

                    @if($plan->description)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">Description</h6>
        <div class="content-display">
            {!! $plan->description !!}
        </div>
    </div>
@endif

                    <!-- Informations pratiques -->
                    <div class="row g-3 mb-4">
                        @if($plan->duree_semaines)
                            <div class="col-md-3">
                                <div class="bg-info bg-opacity-10 rounded p-3 text-center">
                                    <h4 class="fw-bold text-primary mb-1">{{ $plan->duree_semaines }}</h4>
                                    <small class="text-muted">Semaines</small>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="bg-success bg-opacity-10 rounded p-3 text-center">
                                <h4 class="fw-bold text-success mb-1">{{ $plan->getTotalCycles() }}</h4>
                                <small class="text-muted">Cycles</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-info bg-opacity-10 rounded p-3 text-center">
                                <h4 class="fw-bold text-info mb-1">{{ $plan->getTotalSeances() }}</h4>
                                <small class="text-muted">Séances</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-success bg-opacity-10 rounded p-3 text-center">
                                <h4 class="fw-bold text-warning mb-1">{{ $plan->users->count() }}</h4>
                                <small class="text-muted">Utilisateurs</small>
                            </div>
                        </div>
                    </div>

                    @if($plan->prerequis)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="fas fa-water me-2 text-info"></i>Prérequis
        </h6>
        <div class="content-display">
            {!! $plan->prerequis !!}
        </div>
    </div>
@endif

                    @if($plan->conseils_generaux)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="fas fa-lightbulb me-2 text-warning"></i>Conseils généraux
        </h6>
        <div class="content-display">
            {!! $plan->conseils_generaux !!}
        </div>
    </div>
@endif

                    <!-- Cycles du plan -->
                    @if($plan->cycles->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-sync-alt me-2 text-primary"></i>Cycles du plan
                            </h6>
                            
                            @foreach($plan->cycles->sortBy('pivot.ordre') as $cycle)
                                <div class="card border mb-3">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-primary me-3">{{ $cycle->pivot->ordre }}</span>
                                                    <div>
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('admin.training.cycles.show', $cycle) }}" 
                                                               class="text-decoration-none">
                                                                {{ $cycle->titre }}
                                                            </a>
                                                        </h6>
                                                        <div class="small text-muted">
                                                            {{ $cycle->objectif_label }}
                                                            @if($cycle->duree_semaines)
                                                                • {{ $cycle->duree_semaines_formattee }}
                                                            @endif
                                                            • {{ $cycle->getTotalSeances() }} séances
                                                        </div>
                                                        @if($cycle->pivot->notes)
                                                            <div class="small text-info mt-1">
                                                                <i class="fas fa-sticky-note me-1"></i>{{ $cycle->pivot->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="small text-muted">
                                                    Commence semaine {{ $cycle->pivot->semaine_debut }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Utilisateurs assignés -->
                    @if($plan->users->count() > 0)
                        <div class="border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">
                                    <i class="fas fa-users me-2 text-success"></i>Utilisateurs assignés ({{ $plan->users->count() }})
                                </h6>
                                <a href="{{ route('admin.training.plans.assignations', $plan) }}" class="btn btn-sm btn-outline-primary">
                                    Gérer les assignations
                                </a>
                            </div>
                            <div class="row g-2">
                                @foreach($plan->users->take(6) as $user)
                                    <div class="col-md-4">
                                        <div class="card border">
                                            <div class="card-body p-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-fill">
                                                        <h6 class="mb-1 small">{{ $user->name }}</h6>
                                                        <div class="d-flex gap-1">
                                                            <span class="badge bg-{{ $user->pivot->statut_color }} small">
                                                                {{ $user->pivot->statut_label }}
                                                            </span>
                                                            @if($user->pivot->progression_pourcentage > 0)
                                                                <span class="badge bg-info small">
                                                                    {{ $user->pivot->progression_pourcentage }}%
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($plan->users->count() > 6)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.training.plans.assignations', $plan) }}" class="small text-decoration-none">
                                        Voir les {{ $plan->users->count() - 6 }} autres utilisateurs...
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">Informations</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted">Niveau</small>
                            <div>
                                <span class="badge bg-{{ $plan->niveau === 'debutant' ? 'success' : ($plan->niveau === 'avance' ? 'danger' : 'warning') }} fs-6">
                                    {{ $plan->niveau_label }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Objectif</small>
                            <div><strong>{{ $plan->objectif_label }}</strong></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Visibilité</small>
                            <div>
                                <span class="badge bg-{{ $plan->is_public ? 'info' : 'warning' }}">
                                    {{ $plan->is_public ? 'Public' : 'Privé' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Ordre</small>
                            <div><strong>{{ $plan->ordre }}</strong></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Cycles</small>
                            <div><strong>{{ $plan->getTotalCycles() }}</strong></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Créé le</small>
                            <div><small>{{ $plan->created_at->format('d/m/Y') }}</small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.training.plans.edit', $plan) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('admin.training.plans.assignations', $plan) }}" class="btn btn-success">
                            <i class="fas fa-users me-2"></i>Gérer les assignations
                        </a>
                        <a href="{{ route('admin.training.plans.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
/* Styles pour le contenu HTML de Quill */
.content-display {
    line-height: 1.6;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    background: #f9fafb;
}

.content-display h1,
.content-display h2,
.content-display h3 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.content-display p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.content-display ul,
.content-display ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.content-display blockquote {
    border-left: 4px solid var(--bs-primary);
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #6c757d;
}

.content-display img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.content-display pre {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
    border-left: 4px solid #0ea5e9;
    overflow-x: auto;
    margin: 1rem 0;
}
</style>
@endpush

@endsection