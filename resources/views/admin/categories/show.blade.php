@extends('layouts.admin')

@section('title', 'Detail de la categorie')
@section('page-title', $category->name)
@section('page-description', 'Details de la categorie')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white text-primary p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-folder me-2"></i>{{ $category->name }}
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">
                                {{ ucfirst($category->status) }}
                            </span>
                            @if($category->group_name)
                                <span class="badge bg-warning">
                                    {{ $category->group_name }}
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
                                <strong>{{ $category->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-success border-3 ps-3">
                                <small class="text-muted d-block">Articles</small>
                                <strong>{{ $category->posts()->count() }} articles</strong>
                            </div>
                        </div>
                    </div>

                    @if($category->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image d'illustration</h6>
                            <img src="{{ $category->image }}" 
                                 alt="{{ $category->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($category->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $category->description }}
                            </div>
                        </div>
                    @endif

                    <!-- Informations SEO -->
                    @if($category->meta_title || $category->meta_description || $category->meta_keywords)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-search me-2"></i>Informations SEO
                            </h6>
                            <div class="row g-3">
                                @if($category->meta_title)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Titre SEO</small>
                                        <strong>{{ $category->meta_title }}</strong>
                                    </div>
                                @endif
                                
                                @if($category->meta_description)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Description SEO</small>
                                        <div class="bg-light p-2 rounded">{{ $category->meta_description }}</div>
                                    </div>
                                @endif
                                
                                @if($category->meta_keywords)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Mots-cles</small>
                                        <div>
                                            @foreach(explode(',', $category->meta_keywords) as $keyword)
                                                <span class="badge bg-secondary me-1">{{ trim($keyword) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Articles recents de cette categorie -->
                    @if($category->posts()->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-file-alt me-2"></i>Articles recents
                            </h6>
                            <div class="list-group list-group-flush">
                                @foreach($category->posts()->latest()->take(5)->get() as $post)
                                    <div class="list-group-item border-0 px-0 py-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <strong>{{ $post->name }}</strong>
                                                <div class="small text-muted">{{ $post->created_at->format('d/m/Y') }}</div>
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
                            
                            @if($category->posts()->count() > 5)
                                <div class="mt-3">
                                    <a href="{{ route('admin.posts.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-info">
                                        Voir tous les articles ({{ $category->posts()->count() }})
                                    </a>
                                </div>
                            @endif
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
                        <i class="fas fa-water me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Statut</small>
                            <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $category->status === 'active' ? 'success' : 'warning' }} fs-6">
                                {{ $category->status === 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        @if($category->group_name)
                            <div class="col-12">
                                <small class="text-muted d-block">Groupe</small>
                                <strong>{{ $category->group_name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Ordre</small>
                            <strong class="text-primary">{{ $category->order }}</strong>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Articles</small>
                            <strong>{{ $category->posts()->count() }}</strong>
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
                        @if($category->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Cree par</small>
                                <strong>{{ $category->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de creation</small>
                            <strong>{{ $category->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($category->updated_at && $category->updated_at != $category->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Derniere modification</small>
                                <strong>{{ $category->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('posts.public.category', $category) }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette categorie ?')">
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
</style>
@endpush