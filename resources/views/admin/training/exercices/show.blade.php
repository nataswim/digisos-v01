@extends('layouts.admin')

@section('title', 'Détail de l\'exercice')
@section('page-title', $exercice->titre)
@section('page-description', 'Détails de l\'exercice')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-running me-2"></i>{{ $exercice->titre }}
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">
                                {{ $exercice->type_exercice_label }}
                            </span>
                            <span class="badge bg-{{ $exercice->is_active ? 'success' : 'secondary' }}">
                                {{ $exercice->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($exercice->image)
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Image de l'exercice</h6>
                        <img src="{{ $exercice->image }}"
                            alt="{{ $exercice->titre }}"
                            class="img-fluid rounded shadow-sm"
                            style="max-height: 300px;">
                    </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Description</h6>
                        <div class="content-display">
                            {!! $exercice->description !!}
                        </div>
                    </div>

                    @if($exercice->consignes_securite)
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3 text-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Consignes de sécurité
                        </h6>
                        <div class="content-display">
                            {!! $exercice->consignes_securite !!}
                        </div>
                    </div>
                    @endif

                    @if($exercice->muscles_cibles && count($exercice->muscles_cibles) > 0)
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Muscles ciblés</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($exercice->muscles_cibles as $muscle)
                            <span class="badge bg-primary-subtle text-primary">{{ ucfirst($muscle) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($exercice->video_url)
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Vidéo explicative</h6>
                        <a href="{{ $exercice->video_url }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-play me-2"></i>Voir la vidéo
                        </a>
                    </div>
                    @endif

                    <!-- Séries utilisant cet exercice -->
                    @if($exercice->series->count() > 0)
                    <div class="border-top pt-4">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="fas fa-list-ol me-2"></i>Séries utilisant cet exercice ({{ $exercice->series->count() }})
                        </h6>
                        <div class="row g-2">
                            @foreach($exercice->series as $serie)
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">
                                            <a href="{{ route('admin.training.series.show', $serie) }}" class="text-decoration-none">
                                                {{ $serie->nom ?: 'Série #' . $serie->id }}
                                            </a>
                                        </h6>
                                        <div class="small text-muted">
                                            {{ $serie->nom_complet }}
                                        </div>
                                        <div class="small text-muted">
                                            Repos: {{ $serie->repos_formate }}
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

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Informations générales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-water me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Niveau</small>
                            @if($exercice->niveau)
                            @php
                            $badgeColor = match($exercice->niveau) {
                            'debutant' => 'success',
                            'avance' => 'danger',
                            default => 'warning'
                            };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} fs-6">
                                {{ ucfirst($exercice->niveau) }}
                            </span>
                            @else
                            <span class="badge bg-secondary fs-6">Non défini</span>
                            @endif
                        </div>

                        <div class="col-12">
                            <small class="text-muted d-block">Type d'exercice</small>
                            @if($exercice->type_exercice)
                            <strong>{{ ucfirst($exercice->type_exercice) }}</strong>
                            @else
                            <span class="text-muted">Non défini</span>
                            @endif
                        </div>

                        <div class="col-6">
                            <small class="text-muted d-block">Ordre</small>
                            <strong>{{ $exercice->ordre }}</strong>
                        </div>

                        <div class="col-6">
                            <small class="text-muted d-block">Utilisations</small>
                            <strong class="text-primary">{{ $exercice->series->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
           @if($exercice->categories->isNotEmpty() || $exercice->sousCategories->isNotEmpty())
<div class="col-12">
    <hr class="my-3">
    <small class="text-muted d-block mb-2">Catégorisation</small>
    
    @if($exercice->categories->isNotEmpty())
        <div class="mb-2">
            <strong class="d-block mb-1">Catégories :</strong>
            <div class="d-flex gap-2 flex-wrap">
                @foreach($exercice->categories as $cat)
                    <a href="{{ route('admin.exercice-categories.show', $cat) }}" 
                       class="badge bg-primary-subtle text-primary text-decoration-none">
                        <i class="fas fa-folder me-1"></i>{{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    
    @if($exercice->sousCategories->isNotEmpty())
        <div>
            <strong class="d-block mb-1">Sous-catégories :</strong>
            <div class="d-flex gap-2 flex-wrap">
                @foreach($exercice->sousCategories as $sousCat)
                    <a href="{{ route('admin.exercice-sous-categories.show', $sousCat) }}" 
                       class="badge bg-info-subtle text-info text-decoration-none">
                        <i class="fas fa-layer-group me-1"></i>{{ $sousCat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endif
            <!-- Historique -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Historique
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 small">
                        @if($exercice->creator)
                        <div class="col-12">
                            <small class="text-muted d-block">Créé par</small>
                            <strong>{{ $exercice->creator->name }}</strong>
                        </div>
                        @endif

                        <div class="col-12">
                            <small class="text-muted d-block">Date de création</small>
                            <strong>{{ $exercice->created_at->format('d/m/Y H:i') }}</strong>
                        </div>

                        @if($exercice->updated_at && $exercice->updated_at != $exercice->created_at)
                        <div class="col-12">
                            <small class="text-muted d-block">Dernière modification</small>
                            <strong>{{ $exercice->updated_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.training.exercices.edit', $exercice) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('admin.training.exercices.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>

                    <hr class="my-3">

                    <form method="POST" action="{{ route('admin.training.exercices.destroy', $exercice) }}"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

@push('styles')
<style>
    

    

    

    

    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    }
</style>
@endpush