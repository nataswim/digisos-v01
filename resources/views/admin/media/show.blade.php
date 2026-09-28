@extends('layouts.admin')

@section('title', 'Details du media')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
                <div>
                    <h1 class="h3 mb-0">{{ $media->name }}</h1>
                    <p class="text-muted mb-0">Details et edition du media</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux medias
                    </a>
                    <button type="button" 
                            class="btn btn-outline-danger"
                            onclick="deleteMediaConfirm({{ $media->id }})">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <!-- Aperçu de l'image -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-image text-primary me-2"></i>Aperçu
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $media->url }}" 
                                 alt="{{ $media->alt_text ?: $media->name }}"
                                 class="img-fluid rounded shadow-sm mb-3"
                                 style="max-height: 400px;">
                            
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ $media->url }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>Voir en taille reelle
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
                                <i class="fas fa-water text-info me-2"></i>Informations techniques
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
                                    <span class="badge bg-info">{{ $media->formatted_size }}</span>
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
                                    <strong>Uploade le :</strong><br>
                                    <span class="text-muted">{{ $media->created_at->format('d/m/Y A H:i') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Par :</strong><br>
                                    <span class="text-muted">{{ $media->uploader->name ?? 'Inconnu' }}</span>
                                </div>
                                @if($media->used_at)
                                    <div class="col-md-6">
                                        <strong>Derniere utilisation :</strong><br>
                                        <span class="text-muted">{{ $media->used_at->format('d/m/Y A H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire d'edition -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-edit text-success me-2"></i>edition
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.media.update', $media) }}">
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
                                               placeholder="Description pour l'accessibilite">
                                        @error('alt_text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="media_category_id" class="form-label fw-semibold">
                                            Categorie
                                        </label>
                                        <select name="media_category_id" 
                                                id="media_category_id" 
                                                class="form-select @error('media_category_id') is-invalid @enderror">
                                            <option value="">Aucune categorie</option>
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
                                                  placeholder="Description detaillee du media">{{ old('description', $media->description) }}</textarea>
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
                                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                <p>Êtes-vous sûr de vouloir supprimer ce media ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-warning me-2"></i>
                    <strong>Cette action est irreversible.</strong> Le fichier sera definitivement supprime du serveur.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('admin.media.destroy', $media) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer definitivement
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
        // Afficher une notification de succes
        const alert = document.createElement('div');
        alert.className = 'alert alert-success position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        alert.innerHTML = '<i class="fas fa-check me-2"></i>URL copiee !';
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