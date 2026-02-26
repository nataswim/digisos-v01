@extends('layouts.editor')

@section('title', 'Détails du média')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $media->name }}</h2>
            <p class="text-muted mb-0">Détails et édition du média</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('editor.media.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <button type="button" 
                    class="btn btn-outline-danger"
                    onclick="deleteMediaConfirm({{ $media->id }})">
                <i class="fas fa-trash me-2"></i>Supprimer
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Aperçu -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-image text-primary me-2"></i>Aperçu
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if(str_starts_with($media->mime_type ?? '', 'image/'))
                        <img src="{{ $media->url }}" 
                             alt="{{ $media->alt_text ?: $media->name }}"
                             class="img-fluid rounded shadow-sm mb-3"
                             style="max-height: 400px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 400px;">
                            <i class="fas fa-file fa-5x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ $media->url }}" 
                           target="_blank" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>Voir en taille réelle
                        </a>
                        <button type="button" 
                                class="btn btn-outline-success btn-sm"
                                onclick="copyToClipboard('{{ $media->url }}')">
                            <i class="fas fa-copy me-1"></i>Copier l'URL
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>Informations techniques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Nom du fichier :</strong><br>
                            <code class="small">{{ $media->file_name }}</code>
                        </div>
                        <div class="col-md-6">
                            <strong>Nom original :</strong><br>
                            <span class="text-muted">{{ $media->original_name }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Taille :</strong><br>
                            <span class="badge bg-info">{{ number_format($media->size / 1024, 1) }} KB</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Type MIME :</strong><br>
                            <span class="text-muted">{{ $media->mime_type }}</span>
                        </div>
                        @if($media->dimensions)
                            <div class="col-md-6">
                                <strong>Dimensions :</strong><br>
                                <span class="text-muted">{{ $media->dimensions }}</span>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <strong>Uploadé le :</strong><br>
                            <span class="text-muted">{{ $media->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire d'édition -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-edit text-success me-2"></i>Édition
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('editor.media.update', $media) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label fw-semibold">
                                    Nom d'affichage
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $media->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="alt_text" class="form-label fw-semibold">
                                    Texte alternatif (Alt)
                                </label>
                                <input type="text" 
                                       name="alt_text" 
                                       id="alt_text" 
                                       class="form-control @error('alt_text') is-invalid @enderror"
                                       value="{{ old('alt_text', $media->alt_text) }}"
                                       placeholder="Description pour l'accessibilité">
                                @error('alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="media_category_id" class="form-label fw-semibold">
                                    Catégorie
                                </label>
                                <select name="media_category_id" 
                                        id="media_category_id" 
                                        class="form-select @error('media_category_id') is-invalid @enderror">
                                    <option value="">Aucune catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('media_category_id', $media->media_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('media_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">
                                    Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="4"
                                          placeholder="Description détaillée du média">{{ old('description', $media->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="url_copy" class="form-label fw-semibold">
                                    URL du fichier
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           id="url_copy" 
                                           class="form-control font-monospace small" 
                                           value="{{ $media->url }}"
                                           readonly>
                                    <button type="button" 
                                            class="btn btn-outline-secondary"
                                            onclick="copyToClipboard('{{ $media->url }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce média ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Cette action est irréversible.</strong> Le fichier sera définitivement supprimé.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('editor.media.destroy', $media) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        alert.innerHTML = '<i class="fas fa-check me-2"></i>URL copiée !';
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 2000);
    });
}

function deleteMediaConfirm(mediaId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush