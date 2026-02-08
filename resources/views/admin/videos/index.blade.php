@extends('layouts.admin')

@section('title', 'Gestion des Vidéos')
@section('page-title', 'Vidéos')
@section('page-description', 'Gestion des vidéos du site')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des vidéos - Pleine largeur -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-video me-2"></i>Vidéos
                            </h5>
                            <small class="opacity-75">{{ $videos->total() }} vidéo(s) au total</small>
                        </div>
                        <a href="{{ route('admin.videos.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouvelle vidéo
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
                            <select name="type" class="form-select">
                                <option value="">Tous les types</option>
                                <option value="upload" {{ request('type') === 'upload' ? 'selected' : '' }}>Upload</option>
                                <option value="youtube" {{ request('type') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                                <option value="vimeo" {{ request('type') === 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                <option value="dailymotion" {{ request('type') === 'dailymotion' ? 'selected' : '' }}>Dailymotion</option>
                                <option value="url" {{ request('type') === 'url' ? 'selected' : '' }}>URL directe</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="visibility" class="form-select">
                                <option value="">Toute visibilité</option>
                                <option value="public" {{ request('visibility') === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="authenticated" {{ request('visibility') === 'authenticated' ? 'selected' : '' }}>Membres</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Toutes catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'type', 'visibility', 'category', 'featured']))
                                    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Vidéos -->
                <div class="card-body p-0">
                    @if($videos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Vidéo</th>
                                        <th class="border-0 px-4 py-3">Type</th>
                                        <th class="border-0 px-4 py-3">Catégories</th>
                                        <th class="border-0 px-4 py-3">Stats</th>
                                        <th class="border-0 px-4 py-3">Date</th>
                                        <th class="border-0 px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($videos as $video)
                                        <tr class="border-bottom hover-bg">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-start">
                                                    @if($video->thumbnail)
                                                        <img src="{{ $video->thumbnail }}" 
                                                             class="rounded me-3" 
                                                             style="width: 120px; height: 68px; object-fit: cover;" 
                                                             alt="">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 120px; height: 68px;">
                                                            <i class="fas fa-video text-muted fs-4"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-fill">
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('admin.videos.show', $video) }}" 
                                                               class="text-decoration-none text-dark">
                                                                {{ Str::limit($video->title, 60) }}
                                                            </a>
                                                        </h6>
                                                        @if($video->description)
                                                            <small class="text-muted">{{ Str::limit($video->description, 80) }}</small>
                                                        @endif
                                                        <div class="mt-1">
                                                            @if($video->is_featured)
                                                                <span class="badge bg-warning-subtle text-warning">
                                                                    <i class="fas fa-star me-1"></i>Vedette
                                                                </span>
                                                            @endif
                                                            @if($video->is_published)
                                                                <span class="badge bg-success-subtle text-success">
                                                                    <i class="fas fa-check me-1"></i>Publié
                                                                </span>
                                                            @else
                                                                <span class="badge bg-warning-subtle text-warning">
                                                                    <i class="fas fa-edit me-1"></i>Brouillon
                                                                </span>
                                                            @endif
                                                            @if($video->visibility === 'authenticated')
                                                                <span class="badge bg-info-subtle text-info">
                                                                    <i class="fas fa-lock me-1"></i>Membres
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @php
                                                    $typeIcons = [
                                                        'upload' => 'fas fa-upload text-primary',
                                                        'youtube' => 'fab fa-youtube text-danger',
                                                        'vimeo' => 'fab fa-vimeo text-info',
                                                        'dailymotion' => 'fas fa-video text-warning',
                                                        'url' => 'fas fa-link text-secondary'
                                                    ];
                                                    $typeLabels = [
                                                        'upload' => 'Upload',
                                                        'youtube' => 'YouTube',
                                                        'vimeo' => 'Vimeo',
                                                        'dailymotion' => 'Dailymotion',
                                                        'url' => 'URL'
                                                    ];
                                                @endphp
                                                <span class="badge bg-light text-dark border">
                                                    <i class="{{ $typeIcons[$video->type] }} me-1"></i>
                                                    {{ $typeLabels[$video->type] }}
                                                </span>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @if($video->categories->count() > 0)
                                                    @foreach($video->categories->take(2) as $category)
                                                        <span class="badge bg-primary-subtle text-primary mb-1">
                                                            {{ $category->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($video->categories->count() > 2)
                                                        <span class="badge bg-secondary-subtle text-secondary">
                                                            +{{ $video->categories->count() - 2 }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">Aucune</span>
                                                @endif
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex align-items-center text-muted small">
                                                        <i class="fas fa-eye me-1"></i>
                                                        <span>{{ number_format($video->views_count) }}</span>
                                                    </div>
                                                    @if($video->duration)
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>{{ $video->getFormattedDuration() }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="d-flex flex-column">
                                                    <small class="fw-semibold">
                                                        {{ $video->published_at?->format('d/m/Y') ?? $video->created_at->format('d/m/Y') }}
                                                    </small>
                                                    <small class="text-muted">
                                                        {{ $video->published_at?->format('H:i') ?? $video->created_at->format('H:i') }}
                                                    </small>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-inline-flex gap-1">
                                                    <!-- Bouton Voir -->
                                                    <a href="{{ route('admin.videos.show', $video) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Voir"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Modifier -->
                                                    <a href="{{ route('admin.videos.edit', $video) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Modifier"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Voir en ligne -->
                                                    @if($video->is_published)
                                                        <a href="{{ route('public.videos.show', $video) }}" 
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
                                                          action="{{ route('admin.videos.destroy', $video) }}" 
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?')"
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
                        @if($videos->hasPages())
                            <div class="card-footer bg-white border-top p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-muted">
                                        Affichage de {{ $videos->firstItem() }} à {{ $videos->lastItem() }} 
                                        sur {{ $videos->total() }} résultat(s)
                                    </div>
                                    {{ $videos->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-video fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucune vidéo trouvée</h5>
                            @if(request()->hasAny(['search', 'type', 'visibility', 'category', 'featured']))
                                <p class="text-muted mb-3">Aucun résultat ne correspond à vos critères.</p>
                                <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Voir toutes les vidéos
                                </a>
                            @else
                                <p class="text-muted mb-3">Commencez par créer votre première vidéo</p>
                                <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Créer une vidéo
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cards en bas : Statistiques et Par type -->
        <div class="col-lg-6">
            <!-- Statistiques générales -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $stats['total'] }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $stats['published'] }}</h4>
                                <small class="text-muted">Publiées</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $stats['draft'] }}</h4>
                                <small class="text-muted">Brouillons</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $stats['featured'] }}</h4>
                                <small class="text-muted">Vedettes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Répartition par type -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Par type
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-semibold">
                                <i class="fas fa-upload me-1 text-primary"></i>Upload
                            </span>
                            <span class="badge bg-primary-subtle text-primary">{{ $stats['upload'] }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['upload'] / $stats['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-semibold">
                                <i class="fas fa-link me-1 text-white"></i>Externes
                            </span>
                            <span class="badge bg-info-subtle text-info">{{ $stats['external'] }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['external'] / $stats['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle vidéo
                        </a>
                        <a href="{{ route('admin.video-categories.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-folder me-2"></i>Gérer les catégories
                        </a>
                        @if($stats['featured'] > 0)
                            <a href="{{ route('admin.videos.index', ['featured' => '1']) }}" class="btn btn-outline-info">
                                <i class="fas fa-star me-2"></i>Vidéos vedettes ({{ $stats['featured'] }})
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