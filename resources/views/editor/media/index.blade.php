@extends('layouts.editor')

@section('title', 'Médiathèque')

@section('content')
<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-secondary  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white ">  <i class="fas fa-images text-white me-2"></i>
                Médiathèque</h5>
         <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload me-2"></i>Uploader
        </button>
            </div>
        </div>
</section>


<div class="container-fluid">

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-primary">{{ $stats['total'] ?? 0 }}</h3>
                    <small class="text-muted">Total médias</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-success">{{ $stats['images'] ?? 0 }}</h3>
                    <small class="text-muted">Images</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-info">{{ $stats['documents'] ?? 0 }}</h3>
                    <small class="text-muted">Documents</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-secondary">{{ number_format(($stats['size'] ?? 0) / 1048576, 2) }} MB</h3>
                    <small class="text-muted">Espace utilisé</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('editor.media.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="image" {{ ($type ?? '') === 'image' ? 'selected' : '' }}>Images</option>
                        <option value="document" {{ ($type ?? '') === 'document' ? 'selected' : '' }}>Documents</option>
                        <option value="video" {{ ($type ?? '') === 'video' ? 'selected' : '' }}>Vidéos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category_id" class="form-select">
                        <option value="">Toutes catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ ($sortBy ?? 'created_at') === 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="name" {{ ($sortBy ?? '') === 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="file_size" {{ ($sortBy ?? '') === 'file_size' ? 'selected' : '' }}>Taille</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <select name="sort_order" class="form-select">
                        <option value="desc" {{ ($sortOrder ?? 'desc') === 'desc' ? 'selected' : '' }}>↓</option>
                        <option value="asc" {{ ($sortOrder ?? '') === 'asc' ? 'selected' : '' }}>↑</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse($media as $item)
            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="position-relative">
                        @if(str_starts_with($item->mime_type ?? '', 'image/'))
                            <img src="{{ $item->url }}" alt="{{ $item->name }}" 
                                 class="card-img-top" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                <i class="fas fa-file fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-2">
                        <h6 class="card-title small mb-1 text-truncate" title="{{ $item->name }}">
                            {{ $item->name }}
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ number_format($item->size / 1024, 1) }} KB</small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('editor.media.show', $item) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="deleteMedia({{ $item->id }})" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <p class="text-muted mb-0">Aucun média trouvé</p>
            </div>
        @endforelse
    </div>

    @if($media->hasPages())
        <div class="mt-4">
            {{ $media->links() }}
        </div>
    @endif
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Uploader un média</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('editor.media.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fichier</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Texte alternatif</label>
                        <input type="text" name="alt_text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select name="media_category_id" class="form-select">
                            <option value="">Aucune</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Uploader</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function deleteMedia(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce média ?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/editor/media/${id}`;
        form.submit();
    }
}
</script>
@endpush