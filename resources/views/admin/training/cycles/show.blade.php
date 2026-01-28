@extends('layouts.admin')

@section('title', 'Détail cycle')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-sync-alt me-2"></i>{{ $cycle->titre }}
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">{{ $cycle->objectif_label }}</span>
                            <span class="badge bg-{{ $cycle->is_active ? 'success' : 'secondary' }}">
                                {{ $cycle->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($cycle->image)
                        <div class="mb-4">
                            <img src="{{ $cycle->image }}" 
                                 alt="{{ $cycle->titre }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 200px;">
                        </div>
                    @endif

                    @if($cycle->description)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">Description</h6>
        <div class="content-display">
            {!! $cycle->description !!}
        </div>
    </div>
@endif

                    <!-- Informations pratiques -->
                    <div class="row g-3 mb-4">
                        @if($cycle->duree_semaines)
                            <div class="col-md-3">
                                <div class="bg-info bg-opacity-10 rounded p-3 text-center">
                                    <h4 class="fw-bold text-primary mb-1">{{ $cycle->duree_semaines }}</h4>
                                    <small class="text-muted">Semaines</small>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="bg-success bg-opacity-10 rounded p-3 text-center">
                                <h4 class="fw-bold text-success mb-1">{{ $cycle->getTotalSeances() }}</h4>
                                <small class="text-muted">Séances</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-info bg-opacity-10 rounded p-3 text-center">
                                <h4 class="fw-bold text-info mb-1">{{ $cycle->objectif_label }}</h4>
                                <small class="text-muted">Objectif</small>
                            </div>
                        </div>
                    </div>

                    @if($cycle->conseils)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="fas fa-lightbulb me-2 text-warning"></i>Conseils
        </h6>
        <div class="content-display">
            {!! $cycle->conseils !!}
        </div>
    </div>
@endif

                    <!-- Séances par semaine -->
                    @if($cycle->seances->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-calendar me-2 text-primary"></i>Planning des séances
                            </h6>
                            
                            @php
                                $seancesByWeek = $cycle->seances->groupBy('pivot.semaine_cycle');
                            @endphp
                            
                            @foreach($seancesByWeek as $semaine => $seances)
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">Semaine {{ $semaine }}</h6>
                                    <div class="row g-3">
                                        @foreach($seances->sortBy('pivot.ordre') as $seance)
                                            <div class="col-md-6">
                                                <div class="card border">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <span class="badge bg-primary">Jour {{ $seance->pivot->ordre }}</span>
                                                            @if($seance->pivot->jour_semaine)
                                                                <span class="badge bg-info">
                                                                    {{ ['', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'][$seance->pivot->jour_semaine] }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('admin.training.seances.show', $seance) }}" 
                                                               class="text-decoration-none">
                                                                {{ $seance->titre }}
                                                            </a>
                                                        </h6>
                                                        <div class="small text-muted mb-2">
                                                            <span class="badge bg-secondary">{{ $seance->type_seance_label }}</span>
                                                            @if($seance->duree_estimee_minutes)
                                                                <span class="badge bg-warning text-dark">{{ $seance->duree_estimee_formattee }}</span>
                                                            @endif
                                                        </div>
                                                        @if($seance->pivot->notes)
                                                            <div class="small text-info">
                                                                <i class="fas fa-sticky-note me-1"></i>{{ $seance->pivot->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Plans utilisant ce cycle -->
                    @if($cycle->plans->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">Utilisé dans {{ $cycle->plans->count() }} plan(s)</h6>
                            <div class="row g-2">
                                @foreach($cycle->plans as $plan)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.training.plans.show', $plan) }}" 
                                                       class="text-decoration-none">
                                                        {{ $plan->titre }}
                                                    </a>
                                                </h6>
                                                <div class="small text-muted">
                                                    Commence semaine {{ $plan->pivot->semaine_debut }} • 
                                                    Ordre: {{ $plan->pivot->ordre }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
                            <small class="text-muted">Objectif</small>
                            <div>
                                <span class="badge bg-primary fs-6">{{ $cycle->objectif_label }}</span>
                            </div>
                        </div>
                        @if($cycle->duree_semaines)
                            <div class="col-6">
                                <small class="text-muted">Durée</small>
                                <div><strong>{{ $cycle->duree_semaines_formattee }}</strong></div>
                            </div>
                        @endif
                        <div class="col-6">
                            <small class="text-muted">Ordre</small>
                            <div><strong>{{ $cycle->ordre }}</strong></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Séances</small>
                            <div><strong>{{ $cycle->getTotalSeances() }}</strong></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Créé le</small>
                            <div><small>{{ $cycle->created_at->format('d/m/Y') }}</small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.training.cycles.edit', $cycle) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('admin.training.cycles.index') }}" class="btn btn-outline-secondary">
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