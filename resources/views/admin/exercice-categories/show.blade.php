@extends('layouts.admin')

@section('title', 'Détail de la catégorie')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-folder me-2"></i>{{ $exerciceCategory->name }}
                        </h5>
                        <span class="badge bg-{{ $exerciceCategory->is_active ? 'success' : 'secondary' }}">
                            {{ $exerciceCategory->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($exerciceCategory->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image</h6>
                            <img src="{{ $exerciceCategory->image }}" 
                                 alt="{{ $exerciceCategory->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($exerciceCategory->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="content-display">
                                {!! $exerciceCategory->description !!}
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Slug</h6>
                        <code>{{ $exerciceCategory->slug }}</code>
                    </div>

                    <!-- Sous-catégories -->
                    @if($exerciceCategory->sousCategories && $exerciceCategory->sousCategories->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-layer-group me-2"></i>Sous-catégories ({{ $exerciceCategory->sous_categories_count }})
                            </h6>
                            <div class="row g-2">
                                @foreach($exerciceCategory->sousCategories as $sousCategory)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.exercice-sous-categories.show', $sousCategory) }}" 
                                                       class="text-decoration-none">
                                                        {{ $sousCategory->name }}
                                                    </a>
                                                    @if(!$sousCategory->is_active)
                                                        <span class="badge bg-secondary ms-2">Inactif</span>
                                                    @endif
                                                </h6>
                                                <div class="small text-muted">
                                                    <i class="fas fa-running me-1"></i>{{ $sousCategory->exercices_count ?? 0 }} exercice(s)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- SEO -->
                    @if($exerciceCategory->meta_title || $exerciceCategory->meta_description || $exerciceCategory->meta_keywords)
                        <div class="border-top pt-4 mt-4">
                            <h6 class="fw-semibold mb-3">Référencement (SEO)</h6>
                            
                            @if($exerciceCategory->meta_title)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Meta Title</small>
                                    <strong>{{ $exerciceCategory->meta_title }}</strong>
                                </div>
                            @endif

                            @if($exerciceCategory->meta_description)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Meta Description</small>
                                    <p class="mb-0">{{ $exerciceCategory->meta_description }}</p>
                                </div>
                            @endif

                            @if($exerciceCategory->meta_keywords)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Meta Keywords</small>
                                    <p class="mb-0">{{ $exerciceCategory->meta_keywords }}</p>
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
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $exerciceCategory->sous_categories_count ?? 0 }}</h4>
                                <small class="text-muted">Sous-catégories</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $exerciceCategory->exercices_count ?? 0 }}</h4>
                                <small class="text-muted">Exercices</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-secondary bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-secondary mb-1">{{ $exerciceCategory->sort_order }}</h4>
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
                        @if($exerciceCategory->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Créé par</small>
                                <strong>{{ $exerciceCategory->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de création</small>
                            <strong>{{ $exerciceCategory->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($exerciceCategory->updated_at && $exerciceCategory->updated_at != $exerciceCategory->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Dernière modification</small>
                                <strong>{{ $exerciceCategory->updated_at->format('d/m/Y H:i') }}</strong>
                                @if($exerciceCategory->updater)
                                    <small class="text-muted d-block">par {{ $exerciceCategory->updater->name }}</small>
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
                        <a href="{{ route('admin.exercice-categories.edit', $exerciceCategory) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        
                        <a href="{{ route('admin.exercice-sous-categories.create') }}?category={{ $exerciceCategory->id }}" 
                           class="btn btn-outline-info">
                            <i class="fas fa-plus me-2"></i>Ajouter sous-catégorie
                        </a>
                        
                        <a href="{{ route('admin.exercice-categories.index') }}" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" 
                          action="{{ route('admin.exercice-categories.destroy', $exerciceCategory) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
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
/* Styles pour le contenu HTML */
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





.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush