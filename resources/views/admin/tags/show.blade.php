@extends('layouts.admin')

@section('title', 'Detail du tag')
@section('page-title', $tag->name)
@section('page-description', 'Details du tag')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white text-primary p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if($tag->image)
                                    <img src="{{ $tag->image }}" 
                                         alt="{{ $tag->name }}" 
                                         class="rounded"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-white bg-opacity-20 rounded d-flex align-items-center justify-content-center text-white" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-tag fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $tag->name }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="opacity-75">{{ $tag->slug }}</small>
                                    @if($tag->group_name)
                                        <span class="badge bg-light text-dark">{{ $tag->group_name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">
                                {{ $tag->posts()->count() }} article(s)
                            </span>
                            <span class="badge bg-{{ $tag->status === 'active' ? 'success' : 'secondary' }}">
                                {{ $tag->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug URL</small>
                                <strong>{{ $tag->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-success border-3 ps-3">
                                <small class="text-muted d-block">Articles associes</small>
                                <strong>{{ $tag->posts()->count() }} articles</strong>
                            </div>
                        </div>
                    </div>

                    @if($tag->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $tag->description }}
                            </div>
                        </div>
                    @endif

                    @if($tag->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image d'illustration</h6>
                            <img src="{{ $tag->image }}" 
                                 alt="{{ $tag->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    <!-- Informations SEO -->
                    @if($tag->meta_title || $tag->meta_description || $tag->meta_keywords)
                        <div class="border-top pt-4 mb-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-search me-2"></i>Informations SEO
                            </h6>
                            <div class="row g-3">
                                @if($tag->meta_title)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Titre SEO</small>
                                        <strong>{{ $tag->meta_title }}</strong>
                                    </div>
                                @endif
                                
                                @if($tag->meta_description)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Description SEO</small>
                                        <div class="bg-light p-2 rounded">{{ $tag->meta_description }}</div>
                                    </div>
                                @endif
                                
                                @if($tag->meta_keywords)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Mots-cles</small>
                                        <div>
                                            @foreach(explode(',', $tag->meta_keywords) as $keyword)
                                                <span class="badge bg-secondary me-1">{{ trim($keyword) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Articles utilisant ce tag -->
                    @if($tag->posts()->count() > 0)
                        <div class="border-top pt-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-semibold mb-0">
                                    <i class="fas fa-file-alt me-2"></i>Articles utilisant ce tag
                                </h6>
                                <span class="badge bg-info-subtle text-info">
                                    {{ $tag->posts()->count() }} article(s)
                                </span>
                            </div>

                            <!-- Statistiques par statut -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                        <div class="fw-bold text-success fs-4">{{ $tag->posts()->where('status', 'published')->count() }}</div>
                                        <small class="text-muted">Publies</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                        <div class="fw-bold text-warning fs-4">{{ $tag->posts()->where('status', 'draft')->count() }}</div>
                                        <small class="text-muted">Brouillons</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                        <div class="fw-bold text-info fs-4">{{ $tag->posts()->sum('hits') }}</div>
                                        <small class="text-muted">Vues totales</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Liste des articles recents -->
                            <div class="list-group list-group-flush">
                                @foreach($tag->posts()->latest()->take(10)->get() as $post)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center flex-fill">
                                                <div class="me-3">
                                                    @if($post->image)
                                                        <img src="{{ $post->image }}" 
                                                             class="rounded" 
                                                             style="width: 50px; height: 50px; object-fit: cover;" 
                                                             alt="">
                                                    @else
                                                        <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-file-alt text-white"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-fill">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <strong class="me-2">{{ $post->name }}</strong>
                                                        @if($post->is_featured)
                                                            <span class="badge bg-warning-subtle text-warning small">
                                                                <i class="fas fa-star me-1"></i>Featured
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="small text-muted d-flex align-items-center gap-3">
                                                        <span>
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $post->created_at?->format('d/m/Y') ?? 'N/A' }}
                                                        </span>
                                                        @if($post->category)
                                                            <span>
                                                                <i class="fas fa-folder me-1"></i>
                                                                {{ $post->category->name }}
                                                            </span>
                                                        @endif
                                                        <span>
                                                            <i class="fas fa-eye me-1"></i>
                                                            {{ number_format($post->hits) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning' }}-subtle text-{{ $post->status === 'published' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                                <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($tag->posts()->count() > 10)
                                <div class="mt-3">
                                    <a href="{{ route('admin.posts.index', ['tag' => $tag->id]) }}" class="btn btn-sm btn-outline-info">
                                        Voir tous les articles ({{ $tag->posts()->count() }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="border-top pt-4">
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt fa-2x text-muted mb-3"></i>
                                <h6>Aucun article</h6>
                                <p class="text-muted mb-0">Ce tag n'est utilise par aucun article pour le moment.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Informations generales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-water me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Statut</small>
                            <span class="badge bg-{{ $tag->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $tag->status === 'active' ? 'success' : 'secondary' }} fs-6">
                                {{ $tag->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        
                        @if($tag->group_name)
                            <div class="col-12">
                                <small class="text-muted d-block">Groupe</small>
                                <strong>{{ $tag->group_name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Articles</small>
                            <strong class="text-primary">{{ $tag->posts()->count() }}</strong>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Vues totales</small>
                            <strong class="text-success">{{ number_format($tag->posts()->sum('hits')) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tags similaires (même groupe) -->
            @if($tag->group_name)
                @php
                    $similarTags = \App\Models\Tag::where('group_name', $tag->group_name)
                        ->where('id', '!=', $tag->id)
                        ->take(5)
                        ->get();
                @endphp
                
                @if($similarTags->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary text-white p-3">
                            <h6 class="mb-0">
                                <i class="fas fa-tags me-2"></i>Tags similaires
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($similarTags as $similarTag)
                                    <a href="{{ route('admin.tags.show', $similarTag) }}" 
                                       class="badge bg-warning-subtle text-warning text-decoration-none">
                                        {{ $similarTag->name }}
                                        <small>({{ $similarTag->posts()->count() }})</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Historique -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Historique
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 small">
                        @if($tag->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Cree par</small>
                                <strong>{{ $tag->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de creation</small>
                            <strong>{{ $tag->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</strong>
                        </div>
                        
                        @if($tag->updated_at && $tag->updated_at != $tag->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Derniere modification</small>
                                <strong>{{ $tag->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('posts.public.tag', $tag) }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                        </a>
                        <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tag ? Il sera retire de tous les articles associes.')">
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






.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}

.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>
@endpush