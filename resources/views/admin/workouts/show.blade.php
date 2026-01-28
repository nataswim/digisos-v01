@extends('layouts.admin')

@section('title', 'Détail du workout')
@section('page-title', $workout->title)
@section('page-description', 'Détails du workout')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-dumbbell me-2"></i>{{ $workout->title }}
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $workout->formatted_total }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug</small>
                                <strong>{{ $workout->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-info border-3 ps-3">
                                <small class="text-muted d-block">Distance totale</small>
                                <strong>{{ $workout->formatted_total }}</strong>
                            </div>
                        </div>
                    </div>

                    @if($workout->short_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description courte</h6>
                            <div class="bg-light p-3 rounded content-display">
                                {!! $workout->short_description !!}
                            </div>
                        </div>
                    @endif

                    @if($workout->long_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description complète</h6>
                            <div class="content-display">
                                {!! $workout->long_description !!}
                            </div>
                        </div>
                    @endif

                    <!-- Catégories et numéros d'ordre -->
                    @if($workout->categories->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-folder me-2"></i>Catégories ({{ $workout->categories->count() }})
                            </h6>
                            <div class="row g-3">
                                @foreach($workout->categories as $category)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <span class="badge bg-primary mb-2">
                                                            #{{ $category->pivot->order_number }}
                                                        </span>
                                                        <h6 class="mb-1">{{ $category->name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-layer-group me-1"></i>
                                                            {{ $category->section->name ?? 'N/A' }}
                                                        </small>
                                                    </div>
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
            <!-- Informations -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Distance totale</small>
                            <strong class="text-primary fs-5">{{ $workout->formatted_total }}</strong>
                        </div>
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Catégories</small>
                            <strong>{{ $workout->categories->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Historique
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 small">
                        <div class="col-12">
                            <small class="text-muted d-block">Date de création</small>
                            <strong>{{ $workout->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($workout->updated_at && $workout->updated_at != $workout->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Dernière modification</small>
                                <strong>{{ $workout->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.workouts.edit', $workout) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($workout->categories->first())
                            <a href="{{ route('public.workouts.show', [$workout->categories->first()->section, $workout->categories->first(), $workout]) }}" 
                               target="_blank" 
                               class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                            </a>
                        @endif
                        <a href="{{ route('admin.workouts.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.workouts.destroy', $workout) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce workout ?')">
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

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}



.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush