@extends('layouts.admin')

@section('title', 'Détail de la sous-catégorie')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-layer-group me-2"></i>{{ $exerciceSousCategory->name }}
                        </h5>
                        <span class="badge bg-{{ $exerciceSousCategory->is_active ? 'success' : 'secondary' }}">
                            {{ $exerciceSousCategory->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Catégorie parente -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Catégorie parente</h6>
                        @if($exerciceSousCategory->category)
                            <a href="{{ route('admin.exercice-categories.show', $exerciceSousCategory->category) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-folder me-2"></i>{{ $exerciceSousCategory->category->name }}
                            </a>
                        @else
                            <span class="text-muted">Aucune catégorie</span>
                        @endif
                    </div>

                    @if($exerciceSousCategory->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image</h6>
                            <img src="{{ $exerciceSousCategory->image }}" 
                                 alt="{{ $exerciceSousCategory->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($exerciceSousCategory->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="content-display">
                                {!! $exerciceSousCategory->description !!}
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Slug</h6>
                        <code>{{ $exerciceSousCategory->slug }}</code>
                    </div>

                    <!-- Exercices -->
                    @if($exerciceSousCategory->exercices && $exerciceSousCategory->exercices->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-running me-2"></i>Exercices ({{ $exerciceSousCategory->exercices_count }})
                            </h6>
                            <div class="row g-2">
                                @foreach($exerciceSousCategory->exercices as $exercice)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.training.exercices.show', $exercice) }}" 
                                                       class="text-decoration-none">
                                                        {{ $exercice->titre }}
                                                    </a>
                                                    @if(!$exercice->is_active)
                                                        <span class="badge bg-secondary ms-2">Inactif</span>
                                                    @endif
                                                </h6>
                                                @if($exercice->type_exercice)
                                                    <small class="text-muted">{{ ucfirst($exercice->type_exercice) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- SEO -->
                    @if($exerciceSousCategory->meta_title || $exerciceSousCategory->meta_description || $exerciceSousCategory->meta_keywords)
                        <div class="border-top pt-4 mt-4">
                            <h6 class="fw-semibold mb-3">Référencement (SEO)</h6>
                            
                            @if($exerciceSousCategory->meta_title)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Meta Title</small>
                                    <strong>{{ $exerciceSousCategory->meta_title }}</strong>
                                </div>
                            @endif

                            @if($exerciceSousCategory->meta_description)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Meta Description</small>
                                    <p class="mb-0">{{ $exerciceSousCategory->meta_description }}</p>
                                </div>
                            @endif

                            @if($exerciceSousCategory->meta_keywords)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Meta Keywords</small>
                                    <p class="mb-0">{{ $exerciceSousCategory->meta_keywords }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-12">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $exerciceSousCategory->exercices_count ?? 0 }}</h4>
                                <small class="text-muted">Exercices</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-secondary bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-secondary mb-1">{{ $exerciceSousCategory->sort_order }}</h4>
                                <small class="text-muted">Ordre d'affichage</small>
                            </div>
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
                        @if($exerciceSousCategory->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Créé par</small>
                                <strong>{{ $exerciceSousCategory->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de création</small>
                            <strong>{{ $exerciceSousCategory->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($exerciceSousCategory->updated_at && $exerciceSousCategory->updated_at != $exerciceSousCategory->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Dernière modification</small>
                                <strong>{{ $exerciceSousCategory->updated_at->format('d/m/Y H:i') }}</strong>
                                @if($exerciceSousCategory->updater)
                                    <small class="text-muted d-block">par {{ $exerciceSousCategory->updater->name }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.exercice-sous-categories.edit', $exerciceSousCategory) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        
                        @if($exerciceSousCategory->category)
                            <a href="{{ route('admin.exercice-categories.show', $exerciceSousCategory->category) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-folder me-2"></i>Voir la catégorie
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.exercice-sous-categories.index') }}" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" 
                          action="{{ route('admin.exercice-sous-categories.destroy', $exerciceSousCategory) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette sous-catégorie ?')">
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
.content-display {
    line-height: 1.6;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    background: #f9fafb;
}





.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush