@extends('layouts.admin')

@section('title', 'Détails de la page')
@section('page-title', $page->title)
@section('page-description', 'Détails de la page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Actions -->
            <div class="d-flex justify-content-between mb-4">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    
                    @if($page->is_published)
                        <a href="{{ $page->url }}" 
                           target="_blank"
                           class="btn btn-success">
                            <i class="fas fa-external-link-alt me-2"></i>Voir en ligne
                        </a>
                    @endif
                </div>
            </div>

            <!-- Informations principales -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations principales</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Titre :</strong>
                            <p>{{ $page->title }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Slug :</strong>
                            <p><code>{{ $page->slug }}</code></p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Catégorie :</strong>
                            <p>
                                @if($page->category)
                                    <span class="badge bg-primary">{{ $page->category->name }}</span>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-3">
                            <strong>Visibilité :</strong>
                            <p>
                                @if($page->visibility === 'public')
                                    <span class="badge bg-success">Public</span>
                                @else
                                    <span class="badge bg-warning">Authentifié</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-3">
                            <strong>Statut :</strong>
                            <p>
                                @if($page->is_published)
                                    <span class="badge bg-success">Publié</span>
                                @else
                                    <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-12">
                            <strong>Description courte :</strong>
                            <p>{!! $page->short_description ?? '<span class="text-muted">Aucune</span>' !!}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Contenu</h5>
                </div>
                <div class="card-body">
                    {!! $page->long_description ?? '<p class="text-muted">Aucun contenu</p>' !!}
                </div>
            </div>

            <!-- Métadonnées -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Métadonnées</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Créé par :</strong>
                            <p>{{ $page->created_by_name ?? $page->creator?->name ?? 'Inconnu' }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Date de création :</strong>
                            <p>{{ $page->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Dernière modification :</strong>
                            <p>{{ $page->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Date de publication :</strong>
                            <p>{{ $page->published_at?->format('d/m/Y H:i') ?? 'Non publié' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($page->image)
                <!-- Image -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-image me-2"></i>Image</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $page->image }}" 
                             alt="{{ $page->title }}" 
                             class="img-fluid rounded shadow"
                             style="max-height: 400px;">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
