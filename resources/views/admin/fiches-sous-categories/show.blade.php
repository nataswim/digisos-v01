@extends('layouts.admin')

@section('title', 'Détail de la sous-catégorie')
@section('page-title', $fichesSousCategory->name)
@section('page-description', 'Détails de la sous-catégorie')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-layer-group me-2"></i>{{ $fichesSousCategory->name }}
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $fichesSousCategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug</small>
                                <strong>{{ $fichesSousCategory->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-success border-3 ps-3">
                                <small class="text-muted d-block">Fiches</small>
                                <strong>{{ $fichesSousCategory->fiches_count }} fiche(s)</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Catégorie parente -->
                    @if($fichesSousCategory->category)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Catégorie parente</h6>
                            <div class="card border">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        @if($fichesSousCategory->category->image)
                                            <img src="{{ $fichesSousCategory->category->image }}" 
                                                 class="rounded me-3" 
                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                 alt="{{ $fichesSousCategory->category->name }}">
                                        @else
                                            <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-folder text-primary fs-4"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $fichesSousCategory->category->name }}</h6>
                                            <small class="text-muted">{{ $fichesSousCategory->category->slug }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($fichesSousCategory->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image d'illustration</h6>
                            <img src="{{ $fichesSousCategory->image }}" 
                                 alt="{{ $fichesSousCategory->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($fichesSousCategory->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $fichesSousCategory->description }}
                            </div>
                        </div>
                    @endif

                    <!-- Informations SEO -->
                    @if($fichesSousCategory->meta_title || $fichesSousCategory->meta_description || $fichesSousCategory->meta_keywords)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-search me-2"></i>Informations SEO
                            </h6>
                            <div class="row g-3">
                                @if($fichesSousCategory->meta_title)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Titre SEO</small>
                                        <strong>{{ $fichesSousCategory->meta_title }}</strong>
                                    </div>
                                @endif
                                
                                @if($fichesSousCategory->meta_description)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Description SEO</small>
                                        <div class="bg-light p-2 rounded">{{ $fichesSousCategory->meta_description }}</div>
                                    </div>
                                @endif
                                
                                @if($fichesSousCategory->meta_keywords)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Mots-clés</small>
                                        <div>
                                            @foreach(explode(',', $fichesSousCategory->meta_keywords) as $keyword)
                                                <span class="badge bg-secondary me-1">{{ trim($keyword) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Statut et organisation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Statut</small>
                            <span class="badge bg-{{ $fichesSousCategory->is_active ? 'success' : 'warning' }}-subtle text-{{ $fichesSousCategory->is_active ? 'success' : 'warning' }} fs-6">
                                {{ $fichesSousCategory->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Ordre</small>
                            <strong class="text-primary">{{ $fichesSousCategory->sort_order }}</strong>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Fiches</small>
                            <strong>{{ $fichesSousCategory->fiches_count }}</strong>
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
                        @if($fichesSousCategory->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Créé par</small>
                                <strong>{{ $fichesSousCategory->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de création</small>
                            <strong>{{ $fichesSousCategory->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($fichesSousCategory->updated_at && $fichesSousCategory->updated_at != $fichesSousCategory->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Dernière modification</small>
                                <strong>{{ $fichesSousCategory->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.fiches-sous-categories.edit', $fichesSousCategory) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($fichesSousCategory->fiches_count > 0)
                            <a href="{{ route('admin.fiches.index', ['sous_category' => $fichesSousCategory->id]) }}" class="btn btn-outline-info">
                                <i class="fas fa-file-alt me-2"></i>Voir les fiches ({{ $fichesSousCategory->fiches_count }})
                            </a>
                        @endif
                        <a href="{{ route('admin.fiches-sous-categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.fiches-sous-categories.destroy', $fichesSousCategory) }}" 
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




.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush