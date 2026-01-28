@extends('layouts.admin')

@section('title', 'Detail de l\'article')
@section('page-title', $post->name)
@section('page-description', 'Details de l\'article')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>{{ $post->name }}
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">
                                {{ ucfirst($post->status) }}
                            </span>
                            @if($post->is_featured)
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>Featured
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug</small>
                                <strong>{{ $post->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-info border-3 ps-3">
                                <small class="text-muted d-block">Type</small>
                                <strong>{{ ucfirst($post->type) ?: 'Standard' }}</strong>
                            </div>
                        </div>
                    </div>

                    @if($post->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image A la une</h6>
                            <img src="{{ $post->image }}" 
                                 alt="{{ $post->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($post->intro)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Introduction</h6>
                            <div class="bg-light p-3 rounded">
                                {!! $post->intro !!}
                            </div>
                        </div>
                    @endif

                    @if($post->content)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Contenu</h6>
                            <div class="content-display">
                                {!! $post->content !!}
                            </div>
                        </div>
                    @endif

                    <!-- Informations SEO -->
                    @if($post->meta_title || $post->meta_description || $post->meta_keywords)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-search me-2"></i>Informations SEO
                            </h6>
                            <div class="row g-3">
                                @if($post->meta_title)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Titre SEO</small>
                                        <strong>{{ $post->meta_title }}</strong>
                                    </div>
                                @endif
                                
                                @if($post->meta_description)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Description SEO</small>
                                        <div class="bg-light p-2 rounded">{{ $post->meta_description }}</div>
                                    </div>
                                @endif
                                
                                @if($post->meta_keywords)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Mots-cles</small>
                                        <div>
                                            @foreach(explode(',', $post->meta_keywords) as $keyword)
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
            <!-- Statut et publication -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-water me-2"></i>Informations de publication
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Statut</small>
                            <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning' }}-subtle text-{{ $post->status === 'published' ? 'success' : 'warning' }} fs-6">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                        
                        @if($post->published_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Date de publication</small>
                                <strong>{{ $post->published_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Vues</small>
                            <strong class="text-primary">{{ number_format($post->hits) }}</strong>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Ordre</small>
                            <strong>{{ $post->order }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categorie -->
            @if($post->category)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white p-3">
                        <h6 class="mb-0">
                            <i class="fas fa-folder me-2"></i>Categorie
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-folder text-info"></i>
                            </div>
                            <div>
                                <strong>{{ $post->category->name }}</strong>
                                @if($post->category->description)
                                    <div class="text-muted small">{!! Str::limit($post->category->description, 50) !!}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tags -->
            @if($post->tags->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-white p-3">
                        <h6 class="mb-0">
                            <i class="fas fa-tags me-2"></i>Tags ({{ $post->tags->count() }})
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <span class="badge bg-warning-subtle text-warning">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Auteur et dates -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Historique
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 small">
                        @if($post->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Cree par</small>
                                <strong>{{ $post->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de creation</small>
                            <strong>{{ $post->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($post->updated_at && $post->updated_at != $post->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Derniere modification</small>
                                <strong>{{ $post->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('posts.public.show', $post) }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                        </a>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
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

.content-display img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.content-display pre {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
    border-left: 4px solid #0ea5e9;
    overflow-x: auto;
    margin: 1rem 0;
}

.content-display {
    line-height: 1.6;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    background: #f9fafb;
}







.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush