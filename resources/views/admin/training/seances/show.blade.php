@extends('layouts.admin')

@section('title', 'Détail séance')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-dumbbell me-2"></i>{{ $seance->titre }}
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">{{ $seance->type_seance_label }}</span>
                            <span class="badge bg-{{ $seance->is_active ? 'success' : 'secondary' }}">
                                {{ $seance->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($seance->image)
                        <div class="mb-4">
                            <img src="{{ $seance->image }}" 
                                 alt="{{ $seance->titre }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 200px;">
                        </div>
                    @endif

                    @if($seance->description)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">Description</h6>
        <div class="content-display">
            {!! $seance->description !!}
        </div>
    </div>
@endif

                    <!-- Informations pratiques -->
                    <div class="row g-3 mb-4">
                        @if($seance->duree_estimee_minutes)
                            <div class="col-md-3">
                                <div class="bg-info bg-opacity-10 rounded p-3 text-center">
                                    <h4 class="fw-bold text-primary mb-1">{{ $seance->duree_estimee_formattee }}</h4>
                                    <small class="text-muted">Durée estimée</small>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="bg-success bg-opacity-10 rounded p-3 text-center">
                                <h4 class="fw-bold text-success mb-1">{{ $seance->getTotalExercices() }}</h4>
                                <small class="text-muted">Séries</small>
                            </div>
                        </div>
                    </div>

                    @if($seance->materiel_requis)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="fas fa-tools me-2 text-warning"></i>Matériel requis
        </h6>
        <div class="content-display">
            {!! $seance->materiel_requis !!}
        </div>
    </div>
@endif

                    @if($seance->echauffement)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="fas fa-fire me-2 text-info"></i>Échauffement
        </h6>
        <div class="content-display">
            {!! $seance->echauffement !!}
        </div>
    </div>
@endif

                    <!-- Séries -->
                    @if($seance->series->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-list-ol me-2 text-primary"></i>Séries d'exercices
                            </h6>
                            <div class="row g-3">
                                @foreach($seance->series->sortBy('pivot.ordre') as $serie)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge bg-primary">{{ $serie->pivot->ordre }}</span>
                                                    <span class="badge bg-success">{{ $serie->pivot->nombre_series }}x</span>
                                                </div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.training.series.show', $serie) }}" 
                                                       class="text-decoration-none">
                                                        {{ $serie->exercice->titre }}
                                                    </a>
                                                </h6>
                                                <div class="small text-muted mb-2">
                                                    {{ $serie->nom_complet }}
                                                </div>
                                                @if($serie->pivot->notes)
                                                    <div class="small text-info">
                                                        <i class="fas fa-sticky-note me-1"></i>{{ $serie->pivot->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($seance->retour_calme)
    <div class="mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="fas fa-leaf me-2 text-success"></i>Retour au calme
        </h6>
        <div class="content-display">
            {!! $seance->retour_calme !!}
        </div>
    </div>
@endif

                    <!-- Cycles utilisant cette séance -->
                    @if($seance->cycles->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">Utilisée dans {{ $seance->cycles->count() }} cycle(s)</h6>
                            <div class="row g-2">
                                @foreach($seance->cycles as $cycle)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.training.cycles.show', $cycle) }}" 
                                                       class="text-decoration-none">
                                                        {{ $cycle->titre }}
                                                    </a>
                                                </h6>
                                                <div class="small text-muted">
                                                    Semaine {{ $cycle->pivot->semaine_cycle }} • 
                                                    Ordre: {{ $cycle->pivot->ordre }}
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
                            <small class="text-muted">Niveau</small>
                            <div>
                                <span class="badge bg-{{ $seance->niveau === 'debutant' ? 'success' : ($seance->niveau === 'avance' ? 'danger' : 'warning') }} fs-6">
                                    {{ $seance->niveau_label }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Type de séance</small>
                            <div><strong>{{ $seance->type_seance_label }}</strong></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Ordre</small>
                            <div><strong>{{ $seance->ordre }}</strong></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Créée le</small>
                            <div><small>{{ $seance->created_at->format('d/m/Y') }}</small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.training.seances.edit', $seance) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('admin.training.seances.index') }}" class="btn btn-outline-secondary">
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