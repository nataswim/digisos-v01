@extends('layouts.admin')

@section('title', 'Gestion des Articles')
@section('page-title', 'Articles')
@section('page-description', 'Gestion des articles et contenus')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des articles - Pleine largeur -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-water me-2"></i>Articles
                            </h5>
                            <small class="opacity-75">{{ $posts->total() ?? $posts->count() }} article(s) au total</small>
                        </div>
                        <a href="{{ route('admin.posts.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouvel article
                        </a>
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="card-body border-bottom p-4 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="form-control border-start-0"
                                       placeholder="Rechercher...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>
                                    <i class="fas fa-check"></i> Publies
                                </option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>
                                    <i class="fas fa-edit"></i> Brouillons
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="visibility" class="form-select">
                                <option value="">Toute visibilite</option>
                                <option value="public" {{ request('visibility') === 'public' ? 'selected' : '' }}>
                                    <i class="fas fa-water"></i> Public
                                </option>
                                <option value="authenticated" {{ request('visibility') === 'authenticated' ? 'selected' : '' }}>
                                    <i class="fas fa-lock"></i> Membres
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select">
                                <option value="">Toutes categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'status', 'visibility', 'category']))
                                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Articles -->
                <div class="card-body p-0">
                    @if($posts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Article</th>
                                        <th class="border-0 px-4 py-3">Statut & Visibilite</th>
                                        <th class="border-0 px-4 py-3">Categorie</th>
                                        <th class="border-0 px-4 py-3">Auteur</th>
                                        <th class="border-0 px-4 py-3">Stats</th>
                                        <th class="border-0 px-4 py-3">Date</th>
                                        <th class="border-0 px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                        <tr class="border-bottom hover-bg">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-start">
                                                    @if($post->image)
                                                        <img src="{{ $post->image }}" 
                                                             class="rounded me-3" 
                                                             style="width: 60px; height: 45px; object-fit: cover;" 
                                                             alt="">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 60px; height: 45px;">
                                                            <i class="fas fa-file-alt text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-fill">
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('admin.posts.show', $post) }}" 
                                                               class="text-decoration-none text-dark">
                                                                {!! Str::limit($post->name, 60) !!}
                                                            </a>
                                                        </h6>
                                                        @if($post->intro)
                                                            <small class="text-muted">{!! Str::limit(strip_tags($post->intro), 80) !!}</small>
                                                        @endif
                                                        @if($post->is_featured)
                                                            <span class="badge bg-warning-subtle text-warning ms-2">
                                                                <i class="fas fa-star me-1"></i>A la une
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="d-flex flex-column gap-1">
                                                    <!-- Statut -->
                                                    <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning' }}-subtle text-{{ $post->status === 'published' ? 'success' : 'warning' }}">
                                                        <i class="fas fa-{{ $post->status === 'published' ? 'check' : 'edit' }} me-1"></i>
                                                        {{ ucfirst($post->status) }}
                                                    </span>
                                                    
                                                    <!-- Visibilite -->
                                                    @if($post->visibility === 'authenticated')
                                                        <span class="badge bg-info-subtle text-info" title="Contenu reserve aux membres connectes">
                                                            <i class="fas fa-lock me-1"></i>Membres
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success-subtle text-success" title="Contenu accessible A tous">
                                                            <i class="fas fa-water me-1"></i>Public
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @if($post->category)
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        {{ $post->category->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Non categorise</span>
                                                @endif
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @if($post->creator)
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px;">
                                                            <span class="small fw-bold text-primary">
                                                                {{ substr($post->creator->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $post->creator->name }}</div>
                                                            <small class="text-muted">{{ $post->creator->role->display_name ?? $post->creator->role->name ?? 'Utilisateur' }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Auteur inconnu</span>
                                                @endif
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex align-items-center text-muted">
                                                        <i class="fas fa-eye me-1"></i>
                                                        <span>{{ number_format($post->hits) }}</span>
                                                    </div>
                                                    @if($post->tags->count() > 0)
                                                        <small class="text-muted">
                                                            <i class="fas fa-tags me-1"></i>{{ $post->tags->count() }} tag(s)
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="d-flex flex-column">
                                                    <small class="fw-semibold">
                                                        {{ $post->published_at?->format('d/m/Y') ?? $post->created_at?->format('d/m/Y') ?? 'N/A' }}
                                                    </small>
                                                    <small class="text-muted">
                                                        {{ $post->published_at?->format('H:i') ?? $post->created_at?->format('H:i') ?? '' }}
                                                    </small>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-inline-flex gap-1">
                                                    <!-- Bouton Voir -->
                                                    <a href="{{ route('admin.posts.show', $post) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Voir"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Modifier -->
                                                    <a href="{{ route('admin.posts.edit', $post) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Modifier"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Voir en ligne -->
                                                    @if($post->status === 'published')
                                                        <a href="{{ route('posts.public.show', $post) }}" 
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-success" 
                                                           title="Voir en ligne"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    @else
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-secondary" 
                                                                title="Non publié"
                                                                data-bs-toggle="tooltip"
                                                                disabled>
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <!-- Bouton Supprimer -->
                                                    <form method="POST" 
                                                          action="{{ route('admin.posts.destroy', $post) }}" 
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Supprimer"
                                                                data-bs-toggle="tooltip">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
@if($posts->hasPages())
    <div class="card-footer bg-white border-top p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted">
                Affichage de {{ $posts->firstItem() }} à {{ $posts->lastItem() }} 
                sur {{ $posts->total() }} résultat(s)
            </div>
            {{ $posts->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-water fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucun article trouve</h5>
                            @if(request()->hasAny(['search', 'status', 'visibility', 'category']))
                                <p class="text-muted mb-3">Aucun resultat ne correspond A vos criteres de recherche.</p>
                                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Voir tous les articles
                                </a>
                            @else
                                <p class="text-muted mb-3">Commencez par creer votre premier article</p>
                                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Creer un article
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cards en bas : Statistiques et Visibilité -->
        <div class="col-lg-6">
            <!-- Statistiques generales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $totalPosts = \App\Models\Post::count();
                        $publishedPosts = \App\Models\Post::where('status', 'published')->count();
                        $draftPosts = \App\Models\Post::where('status', 'draft')->count();
                        $publicPosts = \App\Models\Post::where('visibility', 'public')->count();
                        $memberPosts = \App\Models\Post::where('visibility', 'authenticated')->count();
                    @endphp
                    
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $totalPosts }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $publishedPosts }}</h4>
                                <small class="text-muted">Publies</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $draftPosts }}</h4>
                                <small class="text-muted">Brouillons</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $memberPosts }}</h4>
                                <small class="text-muted">Membres</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Repartition visibilite -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Visibilite
                    </h6>
                </div>
                <div class="card-body p-3">
                    @if($totalPosts > 0)
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-semibold text-success">
                                    <i class="fas fa-water me-1"></i>Public
                                </span>
                                <span class="badge bg-success-subtle text-success">{{ $publicPosts }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ ($publicPosts / $totalPosts) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-semibold text-info">
                                    <i class="fas fa-lock me-1"></i>Membres
                                </span>
                                <span class="badge bg-info-subtle text-info">{{ $memberPosts }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" 
                                     style="width: {{ ($memberPosts / $totalPosts) * 100 }}%"></div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucun article cree</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvel article
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-folder me-2"></i>Gerer les categories
                        </a>
                        <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="btn btn-outline-info">
                            <i class="fas fa-edit me-2"></i>Voir les brouillons ({{ $draftPosts }})
                        </a>
                        @if($memberPosts > 0)
                            <a href="{{ route('admin.posts.index', ['visibility' => 'authenticated']) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-lock me-2"></i>Articles membres ({{ $memberPosts }})
                            </a>
                        @endif
                    </div>
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







.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

.hover-bg:hover {
    background-color: #f8f9fa;
}

.dropdown-menu {
    border: 0;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Style pour les boutons d'actions */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush