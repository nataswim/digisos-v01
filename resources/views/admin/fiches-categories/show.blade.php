@extends('layouts.admin')

@section('title', 'Détail de la catégorie')
@section('page-title', $fichesCategory->name)
@section('page-description', 'Détails de la catégorie')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white text-primary p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-folder me-2"></i>{{ $fichesCategory->name }}
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $fichesCategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug</small>
                                <strong>{{ $fichesCategory->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-success border-3 ps-3">
                                <small class="text-muted d-block">Fiches</small>
                                <strong>{{ $fichesCategory->fiches_count }} fiche(s)</strong>
                            </div>
                        </div>
                    </div>

                    @if($fichesCategory->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image d'illustration</h6>
                            <img src="{{ $fichesCategory->image }}" 
                                 alt="{{ $fichesCategory->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($fichesCategory->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $fichesCategory->description }}
                            </div>
                        </div>
                    @endif

                    <!-- Informations SEO -->
                    @if($fichesCategory->meta_title || $fichesCategory->meta_description || $fichesCategory->meta_keywords)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-search me-2"></i>Informations SEO
                            </h6>
                            <div class="row g-3">
                                @if($fichesCategory->meta_title)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Titre SEO</small>
                                        <strong>{{ $fichesCategory->meta_title }}</strong>
                                    </div>
                                @endif
                                
                                @if($fichesCategory->meta_description)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Description SEO</small>
                                        <div class="bg-light p-2 rounded">{{ $fichesCategory->meta_description }}</div>
                                    </div>
                                @endif
                                
                                @if($fichesCategory->meta_keywords)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Mots-clés</small>
                                        <div>
                                            @foreach(explode(',', $fichesCategory->meta_keywords) as $keyword)
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
                            <span class="badge bg-{{ $fichesCategory->is_active ? 'success' : 'warning' }}-subtle text-{{ $fichesCategory->is_active ? 'success' : 'warning' }} fs-6">
                                {{ $fichesCategory->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Ordre</small>
                            <strong class="text-primary">{{ $fichesCategory->sort_order }}</strong>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Fiches</small>
                            <strong>{{ $fichesCategory->fiches_count }}</strong>
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
                        @if($fichesCategory->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Créé par</small>
                                <strong>{{ $fichesCategory->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de création</small>
                            <strong>{{ $fichesCategory->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($fichesCategory->updated_at && $fichesCategory->updated_at != $fichesCategory->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Dernière modification</small>
                                <strong>{{ $fichesCategory->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.fiches-categories.edit', $fichesCategory) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($fichesCategory->is_active)
                            <a href="{{ route('public.fiches.category', $fichesCategory) }}" target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                            </a>
                        @endif
                        <a href="{{ route('admin.fiches-categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.fiches-categories.destroy', $fichesCategory) }}" 
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




.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush