@extends('layouts.admin')

@section('title', 'Gestion des Tags')
@section('page-title', 'Tags')
@section('page-description', 'Gestion des etiquettes d\'articles')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des tags -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white text-primary p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-tags me-2"></i>Liste des tags
                            </h5>
                            <small class="opacity-75">{{ $tags->total() ?? $tags->count() }} tag(s) au total</small>
                        </div>
                        <a href="{{ route('admin.tags.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouveau tag
                        </a>
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="card-body border-bottom p-4 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="form-control border-start-0"
                                       placeholder="Rechercher un tag...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="group" class="form-select">
                                <option value="">Tous les groupes</option>
                                @foreach(\App\Models\Tag::whereNotNull('group_name')->distinct()->pluck('group_name') as $group)
                                    <option value="{{ $group }}" {{ request('group') === $group ? 'selected' : '' }}>
                                        {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                @if(request('search') || request('group'))
                                    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tags avec layout en grille -->
                <div class="card-body p-4">
                    @if($tags->count() > 0)
                        <div class="row g-3">
                            @foreach($tags as $tag)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border h-100 hover-shadow">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="flex-fill">
                                                    <div class="d-flex align-items-center mb-2">
                                                        @if($tag->image)
                                                            <img src="{{ $tag->image }}" 
                                                                 class="rounded me-2" 
                                                                 style="width: 24px; height: 24px; object-fit: cover;" 
                                                                 alt="">
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning me-2">
                                                                <i class="fas fa-tag"></i>
                                                            </span>
                                                        @endif
                                                        <h6 class="mb-0">{{ $tag->name }}</h6>
                                                    </div>
                                                    
                                                    @if($tag->group_name)
                                                        <div class="mb-2">
                                                            <span class="badge bg-secondary-subtle text-secondary small">
                                                                {{ $tag->group_name }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if($tag->description)
                                                        <p class="text-muted small mb-2">{!! Str::limit($tag->description, 60) !!}</p>
                                                    @endif
                                                    
                                                    <div class="d-flex align-items-center justify-content-between text-muted small">
                                                        <span>
                                                            <i class="fas fa-file-alt me-1"></i>
                                                            {{ $tag->posts()->count() }} articles
                                                        </span>
                                                        <span class="badge bg-{{ $tag->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $tag->status === 'active' ? 'success' : 'secondary' }}">
                                                            {{ $tag->status === 'active' ? 'Actif' : 'Inactif' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="dropdown ms-2">
                                                    <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ route('admin.tags.show', $tag) }}">
                                                                <i class="fas fa-eye me-2 text-white"></i>Voir details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ route('admin.tags.edit', $tag) }}">
                                                                <i class="fas fa-edit me-2 text-primary"></i>Modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ route('posts.public.tag', $tag) }}" 
                                                               target="_blank">
                                                                <i class="fas fa-external-link-alt me-2 text-success"></i>Voir sur le site
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" 
                                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tag ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="dropdown-item d-flex align-items-center text-danger">
                                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
@if($tags->hasPages())
    <div class="mt-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted">
                Affichage de {{ $tags->firstItem() }} à {{ $tags->lastItem() }} 
                sur {{ $tags->total() }} résultat(s)
            </div>
            {{ $tags->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucun tag trouve</h5>
                            @if(request()->hasAny(['search', 'group']))
                                <p class="text-muted mb-3">Aucun resultat ne correspond A vos criteres de recherche.</p>
                                <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Voir tous les tags
                                </a>
                            @else
                                <p class="text-muted mb-3">Creez votre premier tag pour organiser vos articles</p>
                                <a href="{{ route('admin.tags.create') }}" class="btn btn-warning">
                                    <i class="fas fa-plus me-2"></i>Creer un tag
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques et actions -->
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $totalTags = \App\Models\Tag::count();
                        $usedTags = \App\Models\Tag::has('posts')->count();
                        $unusedTags = $totalTags - $usedTags;
                        $totalPosts = \App\Models\Post::count();
                        $avgPerPost = $totalPosts > 0 ? round(\App\Models\Post::withCount('tags')->get()->avg('tags_count'), 1) : 0;
                    @endphp
                    
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $totalTags }}</h4>
                                <small class="text-muted">Total tags</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $usedTags }}</h4>
                                <small class="text-muted">Utilises</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $unusedTags }}</h4>
                                <small class="text-muted">Non utilises</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $avgPerPost }}</h4>
                                <small class="text-muted">Moy./article</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tags populaires -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-fire me-2"></i>Tags populaires
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $popularTags = \App\Models\Tag::withCount('posts')
                            ->orderBy('posts_count', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($popularTags as $tag)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <a href="{{ route('admin.tags.show', $tag) }}" class="text-decoration-none">
                                    <span class="badge bg-warning-subtle text-warning">{{ $tag->name }}</span>
                                </a>
                            </div>
                            <small class="text-muted">{{ $tag->posts_count }} articles</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun tag utilise pour le moment</p>
                    @endforelse
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tags.create') }}" class="btn btn-warning">
                            <i class="fas fa-plus me-2"></i>Nouveau tag
                        </a>
                        @if($unusedTags > 0)
                            <button class="btn btn-outline-warning" onclick="cleanUnusedTags()">
                                <i class="fas fa-broom me-2"></i>Nettoyer les tags non utilises ({{ $unusedTags }})
                            </button>
                        @endif
                        <button class="btn btn-outline-info" onclick="exportTags()">
                            <i class="fas fa-water me-2"></i>Exporter la liste
                        </button>
                    </div>
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

.hover-shadow {
    transition: box-shadow 0.15s ease-in-out;
}

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.dropdown-menu {
    border: 0;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

@media (max-width: 768px) {
    .col-lg-4 {
        margin-top: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function cleanUnusedTags() {
    if (confirm('Êtes-vous sûr de vouloir supprimer tous les tags non utilises ? Cette action est irreversible.')) {
        // Simulation - il faudra creer la route correspondante
        alert('Fonctionnalite A implementer dans le contrôleur');
        // fetch('/admin/tags/clean-unused', {
        //     method: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        //         'Accept': 'application/json'
        //     }
        // })
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         location.reload();
        //     }
        // });
    }
}

function exportTags() {
    // Simulation - il faudra creer la route correspondante
    alert('Fonctionnalite A implementer dans le contrôleur');
    // window.open('/admin/tags/export', '_blank');
}
</script>
@endpush