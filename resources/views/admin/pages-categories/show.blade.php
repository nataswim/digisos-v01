@extends('layouts.admin')

@section('title', 'Détail de la catégorie')
@section('page-title', $pagesCategory->name)
@section('page-description', 'Détails de la catégorie')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">{{ $pagesCategory->name }}</h5>
                        <span class="badge bg-{{ $pagesCategory->is_active ? 'success' : 'secondary' }}">
                            {{ $pagesCategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Slug</small>
                            <strong>{{ $pagesCategory->slug }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Pages</small>
                            <strong>{{ $pagesCategory->pages_count }} page(s)</strong>
                        </div>
                    </div>

                    @if($pagesCategory->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image</h6>
                            <img src="{{ $pagesCategory->image }}" 
                                 alt="{{ $pagesCategory->name }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($pagesCategory->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="content-display bg-light p-3 rounded">
                                {!! $pagesCategory->description !!}
                            </div>
                        </div>
                    @endif

                    @if($pagesCategory->meta_title || $pagesCategory->meta_description || $pagesCategory->meta_keywords)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">Informations SEO</h6>
                            <div class="row g-3">
                                @if($pagesCategory->meta_title)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Titre SEO</small>
                                        <strong>{{ $pagesCategory->meta_title }}</strong>
                                    </div>
                                @endif
                                
                                @if($pagesCategory->meta_description)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Description SEO</small>
                                        <div class="bg-light p-2 rounded">{{ $pagesCategory->meta_description }}</div>
                                    </div>
                                @endif
                                
                                @if($pagesCategory->meta_keywords)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Mots-clés</small>
                                        <div>
                                            @foreach(explode(',', $pagesCategory->meta_keywords) as $keyword)
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

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white p-3">
                    <h6 class="mb-0">Informations</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Statut</small>
                            <span class="badge bg-{{ $pagesCategory->is_active ? 'success' : 'warning' }}">
                                {{ $pagesCategory->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Ordre</small>
                            <strong>{{ $pagesCategory->sort_order }}</strong>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Pages</small>
                            <strong>{{ $pagesCategory->pages_count }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white p-3">
                    <h6 class="mb-0">Historique</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 small">
                        <div class="col-12">
                            <small class="text-muted d-block">Créée le</small>
                            <strong>{{ $pagesCategory->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($pagesCategory->updated_at && $pagesCategory->updated_at != $pagesCategory->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Modifiée le</small>
                                <strong>{{ $pagesCategory->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pages-categories.edit', $pagesCategory) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($pagesCategory->pages_count > 0)
                            <a href="{{ route('admin.pages.index', ['category' => $pagesCategory->id]) }}" class="btn btn-outline-info">
                                <i class="fas fa-file-alt me-2"></i>Voir les pages ({{ $pagesCategory->pages_count }})
                            </a>
                        @endif
                        <a href="{{ route('admin.pages-categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.pages-categories.destroy', $pagesCategory) }}" 
                          onsubmit="return confirm('Supprimer cette catégorie ?')">
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
/* Styles pour le contenu WYSIWYG */
.content-display h1,
.content-display h2,
.content-display h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
    line-height: 1.3;
}

.content-display h1 { font-size: 1.5rem; color: #38859b; }
.content-display h2 { font-size: 1.3rem; color: #49aaca; }
.content-display h3 { font-size: 1.1rem; color: #4fa79c; }

.content-display p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: #4a5568;
}

.content-display ul,
.content-display ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
    line-height: 1.6;
}

.content-display li {
    margin-bottom: 0.25rem;
}

.content-display blockquote {
    border-left: 4px solid #38859b;
    padding: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    background: #e8f4f7;
    border-radius: 0.25rem;
    color: #2d3748;
}

.content-display img {
    max-width: 100%;
    height: auto;
    margin: 1rem 0;
    display: block;
    border-radius: 0.5rem;
}

.content-display strong {
    font-weight: 600;
}

.content-display em {
    font-style: italic;
}
</style>
@endpush
