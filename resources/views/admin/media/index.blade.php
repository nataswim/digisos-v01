@extends('layouts.admin')

@section('title', 'Gestion des medias')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12 card-header bg-primary text-white p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Gestion des medias</h1>
                    <p class="text-muted mb-0">Gerez vos images et fichiers multimedias</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.media.categories') }}" class="btn bg-warning text-white p-2">
                        <i class="fas fa-folder me-2"></i>Gérer les catégories
                    </a>
                    <button type="button" 
                            class="btn bg-warning text-white p-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#uploadModal">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Uploader des fichiers
                    </button>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row g-3 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-images text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-4">{{ number_format($stats['total_media']) }}</div>
                                    <small class="text-muted">Fichiers total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-hdd text-info fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-4">{{ $stats['total_size_formatted'] }}</div>
                                    <small class="text-muted">Espace utilise</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-folder text-success fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-4">{{ $stats['categories_count'] }}</div>
                                    <small class="text-muted">Categories</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-clock text-warning fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-4">{{ $stats['recent_uploads'] }}</div>
                                    <small class="text-muted">Cette semaine</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre d'actions en masse -->
    <div id="bulkActionsBar" class="card border-0 shadow-sm mb-3 d-none">
        <div class="card-body py-2">
            <form id="bulkActionForm" method="POST" action="{{ route('admin.media.bulk-action') }}">
                @csrf
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <span class="fw-semibold">
                            <span id="selectedCount">0</span> média(s) sélectionné(s)
                        </span>
                    </div>
                    <div class="col-auto">
                        <select name="action" id="bulkActionSelect" class="form-select form-select-sm" required>
                            <option value="">-- Choisir une action --</option>
                            <option value="change_category">Changer de catégorie</option>
                            <option value="delete">Supprimer</option>
                        </select>
                    </div>
                    <div class="col-auto" id="categorySelectContainer" style="display: none;">
                        <select name="category_id" id="bulkCategorySelect" class="form-select form-select-sm">
                            <option value="">Aucune catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-check me-1"></i>Appliquer
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearSelection()">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                    </div>
                </div>
                <input type="hidden" name="media_ids" id="selectedMediaIds">
            </form>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.media.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}"
                               class="form-control border-start-0" 
                               placeholder="Rechercher par nom...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Toutes les categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="per_page" class="form-select">
                        <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12 par page</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 par page</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 par page</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                        <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Grille des medias -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($media->count() > 0)
                <div class="media-grid p-4">
                    <!-- Case à cocher pour tout sélectionner -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label fw-semibold" for="selectAll">
                                Tout sélectionner
                            </label>
                        </div>
                    </div>

                    <div class="row g-3">
                        @foreach($media as $item)
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="media-item card border-0 shadow-sm h-100" 
                                     data-media-id="{{ $item->id }}">
                                    
                                    <!-- Case à cocher -->
                                    <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                        <input type="checkbox" 
                                               class="form-check-input media-checkbox" 
                                               value="{{ $item->id }}"
                                               style="width: 20px; height: 20px; cursor: pointer;">
                                    </div>

                                    <!-- Image -->
                                    <div class="media-preview position-relative">
                                        <img src="{{ $item->url }}" 
                                             alt="{{ $item->alt_text ?: $item->name }}"
                                             class="card-img-top"
                                             style="height: 150px; object-fit: cover;"
                                             loading="lazy">
                                        
                                        <!-- Overlay avec actions -->
                                        <div class="media-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.media.show', $item) }}" 
                                                   class="btn btn-sm btn-light rounded-circle"
                                                   title="Voir les details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger rounded-circle" 
                                                        onclick="deleteMedia({{ $item->id }})"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Badge categorie -->
                                        @if($item->category)
                                            <span class="position-absolute top-0 end-0 m-2 badge" 
                                                  style="background-color: {{ $item->category->color }}">
                                                {{ $item->category->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Informations -->
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-1 text-truncate" title="{{ $item->name }}">
                                            {{ $item->name }}
                                        </h6>
                                        <div class="small text-muted">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span>{{ $item->dimensions ?: 'N/A' }}</span>
                                                <span>{{ $item->formatted_size }}</span>
                                            </div>
                                            <div class="text-truncate">
                                                {{ $item->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if($media->hasPages())
                    <div class="border-top p-4">
                        {{ $media->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-images fa-4x text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted">Aucun media trouve</h5>
                    <p class="text-muted mb-4">
                        @if($search || $categoryId)
                            Aucun resultat pour les criteres selectionnes.
                        @else
                            Commencez par uploader vos premiers fichiers.
                        @endif
                    </p>
                    <button type="button" 
                            class="btn btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#uploadModal">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Uploader des fichiers
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Upload -->
@include('admin.media.modals.upload')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/media-manager.css') }}">
<style>
    .media-item.selected {
        border: 3px solid #0d6efd !important;
        transform: scale(0.98);
    }
    
    .media-checkbox:checked ~ .media-preview {
        opacity: 0.8;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/media-manager.js') }}"></script>
<script>
// Gestion de la sélection multiple
let selectedMedia = new Set();

// Tout sélectionner
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.media-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
        updateMediaSelection(checkbox);
    });
});

// Sélection individuelle
document.querySelectorAll('.media-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        updateMediaSelection(this);
    });
});

function updateMediaSelection(checkbox) {
    const mediaId = checkbox.value;
    const mediaItem = checkbox.closest('.media-item');
    
    if (checkbox.checked) {
        selectedMedia.add(mediaId);
        mediaItem.classList.add('selected');
    } else {
        selectedMedia.delete(mediaId);
        mediaItem.classList.remove('selected');
        document.getElementById('selectAll').checked = false;
    }
    
    updateBulkActionsBar();
}

function updateBulkActionsBar() {
    const bar = document.getElementById('bulkActionsBar');
    const count = document.getElementById('selectedCount');
    const mediaIds = document.getElementById('selectedMediaIds');
    
    count.textContent = selectedMedia.size;
    mediaIds.value = JSON.stringify([...selectedMedia]);
    
    if (selectedMedia.size > 0) {
        bar.classList.remove('d-none');
    } else {
        bar.classList.add('d-none');
    }
}

function clearSelection() {
    selectedMedia.clear();
    document.querySelectorAll('.media-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.querySelectorAll('.media-item').forEach(item => {
        item.classList.remove('selected');
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActionsBar();
}

// Afficher/masquer le sélecteur de catégorie
document.getElementById('bulkActionSelect')?.addEventListener('change', function() {
    const categoryContainer = document.getElementById('categorySelectContainer');
    const categorySelect = document.getElementById('bulkCategorySelect');
    
    if (this.value === 'change_category') {
        categoryContainer.style.display = 'block';
        categorySelect.required = true;
    } else {
        categoryContainer.style.display = 'none';
        categorySelect.required = false;
    }
});

// Validation du formulaire
document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
    const action = document.getElementById('bulkActionSelect').value;
    
    if (action === 'delete') {
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedMedia.size} média(s) ? Cette action est irréversible.`)) {
            e.preventDefault();
            return false;
        }
    }
    
    // Convertir le Set en array JSON
    const mediaIds = document.getElementById('selectedMediaIds');
    mediaIds.value = JSON.stringify([...selectedMedia]);
});

function deleteMedia(mediaId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce media ?')) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/media/${mediaId}`;
    form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
        <input type="hidden" name="_method" value="DELETE">
    `;
    
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush