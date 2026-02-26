@extends('layouts.admin')

@section('title', $photoGallery->title)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
            <i class="fas fa-images text-primary me-2"></i>
            {{ $photoGallery->title }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.photo-galleries.index') }}" 
               class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            @if($photoGallery->is_published)
                <a href="{{ route('galleries.show', $photoGallery) }}" 
                   class="btn btn-outline-info"
                   target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                </a>
            @endif
            <a href="{{ route('admin.photo-galleries.edit', $photoGallery) }}" 
               class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Éditer
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-4">
    <div class="container-fluid">
        <div class="row g-4">
            {{-- Colonne de gauche : Informations --}}
            <div class="col-lg-4">
                {{-- Informations générales --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informations
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <strong class="d-block mb-1">Titre :</strong>
                            <span>{{ $photoGallery->title }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong class="d-block mb-1">Slug :</strong>
                            <code class="small">{{ $photoGallery->slug }}</code>
                        </div>

                        @if($photoGallery->description)
                            <div class="mb-3">
                                <strong class="d-block mb-1">Description :</strong>
                                <p class="text-muted mb-0">{{ $photoGallery->description }}</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <strong class="d-block mb-1">Visibilité :</strong>
                            @if($photoGallery->visibility === 'public')
                                <span class="badge bg-success">
                                    <i class="fas fa-globe me-1"></i>Public
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-lock me-1"></i>Authentifié
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong class="d-block mb-1">Statut :</strong>
                            @if($photoGallery->is_published)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Publiée
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-clock me-1"></i>Brouillon
                                </span>
                            @endif
                        </div>

                        @if($photoGallery->is_featured)
                            <div class="mb-3">
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>Mise en avant
                                </span>
                            </div>
                        @endif

                        <div class="mb-3">
                            <strong class="d-block mb-1">Nombre de photos :</strong>
                            <span class="badge bg-info">{{ $photoGallery->photos->count() }}</span>
                        </div>

                        @if($photoGallery->published_at)
                            <div class="mb-3">
                                <strong class="d-block mb-1">Publiée le :</strong>
                                <small class="text-muted">{{ $photoGallery->published_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        @endif

                        <div class="mb-0">
                            <strong class="d-block mb-1">Créée le :</strong>
                            <small class="text-muted">{{ $photoGallery->created_at->format('d/m/Y à H:i') }}</small>
                        </div>
                    </div>
                </div>

                {{-- Image de couverture --}}
                @if($photoGallery->coverImage)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="mb-0">
                                <i class="fas fa-image text-primary me-2"></i>
                                Image de couverture
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <img src="{{ $photoGallery->coverImage->url }}" 
                                 alt="{{ $photoGallery->title }}"
                                 class="img-fluid rounded shadow-sm">
                        </div>
                    </div>
                @endif
            </div>

            {{-- Colonne de droite : Photos de la galerie --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-images text-primary me-2"></i>
                                Photos de la galerie ({{ $photoGallery->photos->count() }})
                            </h5>
                            <a href="{{ route('admin.photo-galleries.edit', $photoGallery) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if($photoGallery->photos->count() > 0)
                            <div class="row g-3">
                                @foreach($photoGallery->photos as $photo)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <img src="{{ $photo->url }}" 
                                                 alt="{{ $photo->name }}"
                                                 class="card-img-top"
                                                 style="height: 200px; object-fit: cover;">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-1 text-truncate" 
                                                    title="{{ $photo->name }}">
                                                    {{ $photo->name }}
                                                </h6>
                                                @if($photo->pivot->caption)
                                                    <p class="card-text small text-muted mb-2">
                                                        {{ $photo->pivot->caption }}
                                                    </p>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        Ordre: {{ $photo->pivot->sort_order + 1 }}
                                                    </small>
                                                    <a href="{{ $photo->url }}" 
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-images fa-4x text-muted opacity-50 mb-3"></i>
                                <h5 class="text-muted">Aucune photo dans cette galerie</h5>
                                <p class="text-muted mb-4">Éditez la galerie pour ajouter des photos.</p>
                                <a href="{{ route('admin.photo-galleries.edit', $photoGallery) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Ajouter des photos
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SEO --}}
                @if($photoGallery->meta_title || $photoGallery->meta_description || $photoGallery->meta_keywords)
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="mb-0">
                                <i class="fas fa-search text-primary me-2"></i>
                                SEO
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @if($photoGallery->meta_title)
                                <div class="mb-3">
                                    <strong class="d-block mb-1">Meta titre :</strong>
                                    <span class="text-muted">{{ $photoGallery->meta_title }}</span>
                                </div>
                            @endif

                            @if($photoGallery->meta_description)
                                <div class="mb-3">
                                    <strong class="d-block mb-1">Meta description :</strong>
                                    <p class="text-muted mb-0">{{ $photoGallery->meta_description }}</p>
                                </div>
                            @endif

                            @if($photoGallery->meta_keywords)
                                <div class="mb-0">
                                    <strong class="d-block mb-1">Mots-clés :</strong>
                                    <span class="text-muted">{{ $photoGallery->meta_keywords }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
