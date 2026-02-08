@extends('layouts.admin')

@section('title', 'Détail de la vidéo')
@section('page-title', $video->title)
@section('page-description', 'Détails de la vidéo')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">{{ $video->title }}</h5>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                @if($video->is_published)
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-check me-1"></i>Publié
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-edit me-1"></i>Brouillon
                                    </span>
                                @endif
                                @if($video->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star me-1"></i>Vedette
                                    </span>
                                @endif
                                @if($video->visibility === 'authenticated')
                                    <span class="badge bg-info">
                                        <i class="fas fa-lock me-1"></i>Membres
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Player vidéo -->
                    <div class="mb-4">
                        @if($video->type === 'upload' && $video->file_path)
                            <video controls class="w-100 rounded" style="max-height: 500px;">
                                <source src="{{ asset('storage/' . $video->file_path) }}" type="{{ $video->mime_type }}">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        @elseif($video->getEmbedUrl())
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $video->getEmbedUrl() }}" 
                                        allowfullscreen 
                                        class="rounded">
                                </iframe>
                            </div>
                        @elseif($video->external_url)
                            <div class="alert alert-info">
                                <i class="fas fa-link me-2"></i>
                                <a href="{{ $video->external_url }}" target="_blank">{{ $video->external_url }}</a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Aucune source vidéo disponible
                            </div>
                        @endif
                    </div>

                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug</small>
                                <strong>{{ $video->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-success border-3 ps-3">
                                <small class="text-muted d-block">Type</small>
                                <strong class="text-capitalize">{{ $video->type }}</strong>
                            </div>
                        </div>
                    </div>

                    @if($video->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $video->description }}
                            </div>
                        </div>
                    @endif

                    <!-- Métadonnées -->
                    @if($video->duration || $video->width || $video->height)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-info-circle me-2 text-white"></i>Métadonnées
                            </h6>
                            <div class="row g-3">
                                @if($video->duration)
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Durée</small>
                                        <strong>{{ $video->getFormattedDuration() }}</strong>
                                    </div>
                                @endif
                                @if($video->width && $video->height)
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Résolution</small>
                                        <strong>{{ $video->width }}x{{ $video->height }}</strong>
                                    </div>
                                @endif
                                @if($video->file_size)
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Taille fichier</small>
                                        <strong>{{ $video->file_size }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- SEO -->
                    @if($video->meta_title || $video->meta_description || $video->meta_keywords)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-search me-2"></i>Informations SEO
                            </h6>
                            <div class="row g-3">
                                @if($video->meta_title)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Titre SEO</small>
                                        <strong>{{ $video->meta_title }}</strong>
                                    </div>
                                @endif
                                
                                @if($video->meta_description)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Description SEO</small>
                                        <div class="bg-light p-2 rounded">{{ $video->meta_description }}</div>
                                    </div>
                                @endif
                                
                                @if($video->meta_keywords)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Mots-clés</small>
                                        <div>
                                            @foreach(explode(',', $video->meta_keywords) as $keyword)
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
            <!-- Catégories -->
            @if($video->categories->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-white p-3">
                        <h6 class="mb-0">
                            <i class="fas fa-folder me-2"></i>Catégories
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        @foreach($video->categories as $category)
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-warning bg-opacity-10 rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-folder text-warning"></i>
                                </div>
                                <div>
                                    <strong>{{ $category->name }}</strong>
                                    <div class="text-muted small">{{ $category->slug }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Statistiques -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                <div class="fw-bold text-primary fs-4">{{ number_format($video->views_count) }}</div>
                                <small class="text-muted">Vues</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <div class="fw-bold text-success fs-4">{{ $video->categories->count() }}</div>
                                <small class="text-muted">Catégories</small>
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
                        @if($video->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Créé par</small>
                                <strong>{{ $video->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de création</small>
                            <strong>{{ $video->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($video->updated_at && $video->updated_at != $video->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Dernière modification</small>
                                <strong>{{ $video->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif

                        @if($video->published_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Publié le</small>
                                <strong>{{ $video->published_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($video->is_published)
                            <a href="{{ route('public.videos.show', $video) }}" target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                            </a>
                        @endif
                        <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?')">
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
.bg-primary { background: linear-gradient(135deg, #0ea5e9 0%, #0f172a 100%); }
.bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%); }
.bg-gradient-secondary { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
</style>
@endpush