@extends('layouts.admin')

@section('title', 'Détails de la fiche')
@section('page-title', $fiche->title)
@section('page-description', 'Détails de la fiche')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Actions -->
            <div class="d-flex justify-content-between mb-4">
                <a href="{{ route('admin.fiches.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <div class="d-flex gap-2">
                    
                    
                    @if($fiche->is_published && $fiche->category && $fiche->sousCategory)
                        <a href="{{ route('public.fiches.show', [
                                'category' => $fiche->category->slug,
                                'sousCategory' => $fiche->sousCategory->slug,
                                'fiche' => $fiche->slug
                            ]) }}" 
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
                            <p>{{ $fiche->title }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Slug :</strong>
                            <p><code>{{ $fiche->slug }}</code></p>
                        </div>
                        
                        <div class="col-md-4">
                            <strong>Catégorie :</strong>
                            <p>
                                @if($fiche->category)
                                    <span class="badge bg-primary">{{ $fiche->category->name }}</span>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-4">
                            <strong>Sous-catégorie :</strong>
                            <p>
                                @if($fiche->sousCategory)
                                    <span class="badge bg-info">{{ $fiche->sousCategory->name }}</span>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-4">
                            <strong>Visibilité :</strong>
                            <p>
                                @if($fiche->visibility === 'public')
                                    <span class="badge bg-success">Public</span>
                                @else
                                    <span class="badge bg-warning">Authentifié</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-4">
                            <strong>Statut :</strong>
                            <p>
                                @if($fiche->is_published)
                                    <span class="badge bg-success">Publié</span>
                                @else
                                    <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-4">
                            <strong>En vedette :</strong>
                            <p>
                                @if($fiche->is_featured)
                                    <span class="badge bg-warning">Oui</span>
                                @else
                                    <span class="text-muted">Non</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-4">
                            <strong>Vues :</strong>
                            <p>{{ number_format($fiche->views_count ?? 0) }}</p>
                        </div>
                        
                        <div class="col-12">
                            <strong>Description courte :</strong>
                            <p>{!! $fiche->short_description ?? '<span class="text-muted">Aucune</span>' !!}</p>
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
                    {!! $fiche->long_description ?? '<p class="text-muted">Aucun contenu</p>' !!}
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
                            <p>{{ $fiche->created_by_name ?? $fiche->creator?->name ?? 'Inconnu' }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Date de création :</strong>
                            <p>{{ $fiche->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Dernière modification :</strong>
                            <p>{{ $fiche->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <strong>Date de publication :</strong>
                            <p>{{ $fiche->published_at?->format('d/m/Y H:i') ?? 'Non publié' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($fiche->image)
                <!-- Image -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-image me-2"></i>Image</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $fiche->image }}" 
                             alt="{{ $fiche->title }}" 
                             class="img-fluid rounded shadow"
                             style="max-height: 400px;">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection