@extends('layouts.admin')

@section('title', 'Categories de medias')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
                <div>
                    <h1 class="h3 mb-0">Categories de medias</h1>
                    <p class="text-muted mb-0">Organisez vos medias en categories</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux medias
                    </a>
                    <button type="button" 
                            class="btn btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#categoryModal">
                        <i class="fas fa-plus me-2"></i>Nouvelle categorie
                    </button>
                </div>
            </div>

            @if($categories->count() > 0)
                <div class="row g-4">
                    @foreach($categories as $category)
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <!-- En-tête de la catégorie -->
                                <div class="card-header d-flex justify-content-between align-items-center" 
                                     style="background-color: {{ $category->color }}15; border-left: 4px solid {{ $category->color }}">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded me-3" 
                                             style="width: 32px; height: 32px; background-color: {{ $category->color }}"></div>
                                        <div>
                                            <h5 class="mb-0">{{ $category->name }}</h5>
                                            <small class="text-muted">{{ $category->media_count }} média(s)</small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <!-- Bouton upload dans cette catégorie -->
                                        <button type="button" 
                                                class="btn btn-sm btn-primary" 
                                                onclick="openUploadForCategory({{ $category->id }}, '{{ $category->name }}')"
                                                title="Uploader dans cette catégorie">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                        <a href="{{ route('admin.media.index', ['category' => $category->id]) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Voir tous les médias">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($category->media_count === 0)
                                            <form method="POST" 
                                                  action="{{ route('admin.media.categories.destroy', $category) }}"
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
                                        @endif
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($category->description)
                                    <div class="card-body py-2 border-bottom">
                                        <small class="text-muted">{{ $category->description }}</small>
                                    </div>
                                @endif

                                <!-- Miniatures des médias -->
                                <div class="card-body">
                                    @if($category->media_count > 0)
                                        <div class="row g-2">
                                            @foreach($category->media()->latest()->limit(6)->get() as $media)
                                                <div class="col-4">
                                                    <a href="{{ route('admin.media.show', $media) }}" 
                                                       class="d-block position-relative">
                                                        <img src="{{ $media->url }}" 
                                                             alt="{{ $media->name }}"
                                                             class="img-fluid rounded shadow-sm"
                                                             style="width: 100%; height: 100px; object-fit: cover;">
                                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-opacity-0 hover-overlay rounded d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-search-plus text-white d-none"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($category->media_count > 6)
                                            <div class="text-center mt-3">
                                                <a href="{{ route('admin.media.index', ['category' => $category->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    Voir les {{ $category->media_count }} médias
                                                    <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-images fa-2x mb-2 opacity-50"></i>
                                            <p class="mb-2">Aucun média dans cette catégorie</p>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary"
                                                    onclick="openUploadForCategory({{ $category->id }}, '{{ $category->name }}')">
                                                <i class="fas fa-upload me-1"></i>Uploader maintenant
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- Footer -->
                                <div class="card-footer bg-light">
                                    <div class="row text-center small text-muted">
                                        <div class="col-4">
                                            <i class="fas fa-sort-amount-down me-1"></i>
                                            Ordre: {{ $category->order }}
                                        </div>
                                        <div class="col-4">
                                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <div class="col-4">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $category->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-folder fa-4x text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted">Aucune categorie creee</h5>
                    <p class="text-muted mb-4">Creez votre premiere categorie pour organiser vos medias.</p>
                    <button type="button" 
                            class="btn btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#categoryModal">
                        <i class="fas fa-plus me-2"></i>Creer une categorie
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Categorie -->
@include('admin.media.modals.category')

<!-- Modal Upload avec catégorie pré-sélectionnée -->
@include('admin.media.modals.upload')
@endsection

@push('styles')
<style>
    .hover-overlay:hover {
        background-color: rgba(0, 0, 0, 0.3) !important;
    }
    .hover-overlay:hover i {
        display: block !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/media-manager.js') }}"></script>
<script>
function openUploadForCategory(categoryId, categoryName) {
    // Ouvrir le modal
    const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
    
    // Pré-sélectionner la catégorie
    const categorySelect = document.getElementById('media_category_id');
    if (categorySelect) {
        categorySelect.value = categoryId;
    }
    
    // Mettre à jour le titre du modal
    const modalTitle = document.querySelector('#uploadModal .modal-title');
    if (modalTitle) {
        modalTitle.innerHTML = `
            <i class="fas fa-cloud-upload-alt text-primary me-2"></i>
            Uploader dans : <span class="text-primary">${categoryName}</span>
        `;
    }
    
    uploadModal.show();
}

// Réinitialiser le titre du modal à la fermeture
document.getElementById('uploadModal')?.addEventListener('hidden.bs.modal', function() {
    const modalTitle = this.querySelector('.modal-title');
    if (modalTitle) {
        modalTitle.innerHTML = `
            <i class="fas fa-cloud-upload-alt text-primary me-2"></i>
            Uploader des fichiers
        `;
    }
});
</script>
@endpush