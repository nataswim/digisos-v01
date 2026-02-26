@extends('layouts.admin')

@section('title', 'Galeries photos')



@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
            <i class="fas fa-images text-primary me-2"></i>
            Galeries photos
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('galleries.index') }}" 
               class="btn btn-outline-info"
               target="_blank">
                <i class="fas fa-eye me-2"></i>Voir le site
            </a>
            <a href="{{ route('admin.photo-galleries.create') }}" 
               class="btn btn-warning">
                <i class="fas fa-plus me-2"></i>Nouvelle galerie
            </a>
        </div>
    </div>



<div class="py-4">
    <div class="container-fluid">
        {{-- Statistiques --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                <i class="fas fa-images text-primary fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-4">{{ $galleries->total() }}</div>
                                <small class="text-muted">Galeries total</small>
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
                                <i class="fas fa-check text-success fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-4">{{ $galleries->where('is_published', true)->count() }}</div>
                                <small class="text-muted">Publiées</small>
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
                                <div class="fw-bold fs-4">{{ $galleries->where('is_published', false)->count() }}</div>
                                <small class="text-muted">Brouillons</small>
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
                                <i class="fas fa-star text-info fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-4">{{ $galleries->where('is_featured', true)->count() }}</div>
                                <small class="text-muted">En avant</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Barre d'actions en masse --}}
        <div id="bulkActionsBar" class="card border-0 shadow-sm mb-3 d-none">
            <div class="card-body py-2">
                <form id="bulkActionForm" method="POST" action="{{ route('admin.photo-galleries.bulk-action') }}">
                    @csrf
                    <div class="row align-items-center g-2">
                        <div class="col-auto">
                            <span class="fw-semibold">
                                <span id="selectedCount">0</span> galerie(s) sélectionnée(s)
                            </span>
                        </div>
                        <div class="col-auto">
                            <select name="action" id="bulkActionSelect" class="form-select form-select-sm" required>
                                <option value="">-- Choisir une action --</option>
                                <option value="publish">Publier</option>
                                <option value="unpublish">Dépublier</option>
                                <option value="feature">Mettre en avant</option>
                                <option value="unfeature">Retirer de la une</option>
                                <option value="delete">Supprimer</option>
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
                    <input type="hidden" name="galleries" id="selectedGalleryIds">
                </form>
            </div>
        </div>

        {{-- Filtres --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.photo-galleries.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search }}"
                                   class="form-control border-start-0" 
                                   placeholder="Rechercher...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="visibility" class="form-select">
                            <option value="">Toutes visibilités</option>
                            <option value="public" {{ $visibility === 'public' ? 'selected' : '' }}>Public</option>
                            <option value="authenticated" {{ $visibility === 'authenticated' ? 'selected' : '' }}>Authentifié</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Tous statuts</option>
                            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Publiée</option>
                            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Brouillon</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i>Filtrer
                            </button>
                            <a href="{{ route('admin.photo-galleries.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Liste des galeries --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if($galleries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th width="80">Image</th>
                                    <th>Titre</th>
                                    <th width="100" class="text-center">Photos</th>
                                    <th width="120">Visibilité</th>
                                    <th width="100">Statut</th>
                                    <th width="150">Date</th>
                                    <th width="200" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($galleries as $gallery)
                                    <tr>
                                        <td>
                                            <input type="checkbox" 
                                                   class="form-check-input gallery-checkbox" 
                                                   value="{{ $gallery->id }}">
                                        </td>
                                        <td>
                                            @if($gallery->coverImage)
                                                <img src="{{ $gallery->coverImage->url }}" 
                                                     alt="{{ $gallery->title }}"
                                                     class="img-thumbnail"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-images text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $gallery->title }}</div>
                                            <small class="text-muted">{{ $gallery->slug }}</small>
                                            @if($gallery->is_featured)
                                                <span class="badge bg-warning ms-1">
                                                    <i class="fas fa-star"></i> En avant
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                {{ $gallery->photos_count }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($gallery->visibility === 'public')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-globe me-1"></i>Public
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-lock me-1"></i>Authentifié
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($gallery->is_published)
                                                <span class="badge bg-success">Publiée</span>
                                            @else
                                                <span class="badge bg-secondary">Brouillon</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $gallery->created_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="{{ route('admin.photo-galleries.show', $gallery) }}" 
                                                   class="btn btn-sm btn-outline-info"
                                                   title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.photo-galleries.edit', $gallery) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Éditer">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" 
                                                      action="{{ route('admin.photo-galleries.duplicate', $gallery) }}"
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-secondary"
                                                            title="Dupliquer">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" 
                                                      action="{{ route('admin.photo-galleries.destroy', $gallery) }}"
                                                      onsubmit="return confirm('Êtes-vous sûr ?')"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Supprimer">
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

                    {{-- Pagination --}}
                    @if($galleries->hasPages())
                        <div class="border-top p-4">
                            {{ $galleries->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-images fa-4x text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted">Aucune galerie trouvée</h5>
                        <p class="text-muted mb-4">
                            @if($search || $visibility || $status)
                                Aucun résultat pour les critères sélectionnés.
                            @else
                                Commencez par créer votre première galerie.
                            @endif
                        </p>
                        <a href="{{ route('admin.photo-galleries.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Créer une galerie
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Gestion de la sélection multiple
let selectedGalleries = new Set();

document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.gallery-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
        updateGallerySelection(checkbox);
    });
});

document.querySelectorAll('.gallery-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        updateGallerySelection(this);
    });
});

function updateGallerySelection(checkbox) {
    const galleryId = checkbox.value;
    
    if (checkbox.checked) {
        selectedGalleries.add(galleryId);
    } else {
        selectedGalleries.delete(galleryId);
        document.getElementById('selectAll').checked = false;
    }
    
    updateBulkActionsBar();
}

function updateBulkActionsBar() {
    const bar = document.getElementById('bulkActionsBar');
    const count = document.getElementById('selectedCount');
    const galleryIds = document.getElementById('selectedGalleryIds');
    
    count.textContent = selectedGalleries.size;
    galleryIds.value = JSON.stringify([...selectedGalleries]);
    
    if (selectedGalleries.size > 0) {
        bar.classList.remove('d-none');
    } else {
        bar.classList.add('d-none');
    }
}

function clearSelection() {
    selectedGalleries.clear();
    document.querySelectorAll('.gallery-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActionsBar();
}

document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
    const action = document.getElementById('bulkActionSelect').value;
    
    if (action === 'delete') {
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedGalleries.size} galerie(s) ?`)) {
            e.preventDefault();
            return false;
        }
    }
});
</script>
@endpush
