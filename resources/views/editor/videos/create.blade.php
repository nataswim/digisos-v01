@extends('layouts.editor')

@section('title', 'Créer une vidéo')

@section('content')
<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-success  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white ">  <i class="fas fa-file-medical text-white me-2"></i>
                Ajouter une nouvelle vidéo</h5>
            </div>
        </div>
</section>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('editor.videos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <form method="POST" action="{{ route('editor.videos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-video me-2"></i>Informations de la vidéo
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">Titre de la vidéo *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control form-control-lg @error('title') is-invalid @enderror" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">Slug URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ url('/videos') }}/</span>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-control @error('slug') is-invalid @enderror">
                            </div>
                            <div class="form-text">Laisser vide pour génération automatique</div>
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <div id="description-editor" style="height: 500px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                            <textarea name="description" id="description" class="d-none @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="form-label fw-semibold">Type de source *</label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Sélectionner un type</option>
                                <option value="upload" {{ old('type') === 'upload' ? 'selected' : '' }}>Upload fichier</option>
                                <option value="youtube" {{ old('type') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                                <option value="vimeo" {{ old('type') === 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                <option value="dailymotion" {{ old('type') === 'dailymotion' ? 'selected' : '' }}>Dailymotion</option>
                                <option value="url" {{ old('type') === 'url' ? 'selected' : '' }}>URL directe</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4" id="uploadSection" style="display: none;">
                            <label for="file" class="form-label fw-semibold">Fichier vidéo</label>
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-pane" type="button">
                                        <i class="fas fa-upload me-1"></i>Upload nouveau fichier
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="library-tab" data-bs-toggle="tab" data-bs-target="#library-pane" type="button">
                                        <i class="fas fa-folder-open me-1"></i>Bibliothèque
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="upload-pane" role="tabpanel">
                                    <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept="video/mp4,video/webm,video/quicktime,video/x-msvideo">
                                    <div class="form-text">Formats: mp4, webm, mov, avi - Max: 500MB</div>
                                    @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="tab-pane fade" id="library-pane" role="tabpanel">
                                    <button type="button" class="btn btn-primary" id="btnOpenVideoLibrary">
                                        <i class="fas fa-folder-open me-2"></i>Parcourir la bibliothèque vidéo
                                    </button>
                                    <div class="form-text">Sélectionner une vidéo depuis storage/media/video/</div>
                                    
                                    <div id="selectedLibraryVideo" class="d-none mt-3">
                                        <div class="alert alert-success d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-check-circle me-2"></i>
                                                <strong>Vidéo sélectionnée :</strong>
                                                <span id="selectedVideoName"></span>
                                                <br>
                                                <small class="text-muted">Taille : <span id="selectedVideoSize"></span></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearLibrarySelection()">
                                                <i class="fas fa-times"></i> Annuler
                                            </button>
                                        </div>
                                        <input type="hidden" name="library_file_path" id="library_file_path">
                                        <input type="hidden" name="library_file_size" id="library_file_size">
                                        <input type="hidden" name="library_mime_type" id="library_mime_type">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4" id="urlSection" style="display: none;">
                            <label for="external_url" class="form-label fw-semibold">URL de la vidéo</label>
                            <div class="input-group">
                                <input type="url" name="external_url" id="external_url" value="{{ old('external_url') }}" class="form-control @error('external_url') is-invalid @enderror" placeholder="https://www.youtube.com/watch?v=...">
                                <button type="button" class="btn btn-outline-primary" id="fetchMetadataBtn">
                                    <i class="fas fa-download me-2"></i>Récupérer infos
                                </button>
                            </div>
                            @error('external_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-info-circle me-2 text-white"></i>Métadonnées
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="duration" class="form-label">Durée (secondes)</label>
                                    <input type="number" name="duration" id="duration" value="{{ old('duration') }}" class="form-control" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="width" class="form-label">Largeur (px)</label>
                                    <input type="number" name="width" id="width" value="{{ old('width') }}" class="form-control" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="height" class="form-label">Hauteur (px)</label>
                                    <input type="number" name="height" id="height" value="{{ old('height') }}" class="form-control" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-4 mt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-search me-2 text-primary"></i>SEO
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="meta_title" class="form-label">Titre SEO</label>
                                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" class="form-control" maxlength="60">
                                </div>
                                <div class="col-md-6">
                                    <label for="meta_keywords" class="form-label">Mots-clés</label>
                                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="meta_description" class="form-label">Description SEO</label>
                                    <textarea name="meta_description" id="meta_description" rows="3" class="form-control" maxlength="160">{{ old('meta_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-calendar me-2"></i>Publication
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="is_published" class="form-label fw-semibold">Statut</label>
                            <select name="is_published" id="is_published" class="form-select">
                                <option value="0" {{ old('is_published', 0) == 0 ? 'selected' : '' }}>Brouillon</option>
                                <option value="1" {{ old('is_published', 0) == 1 ? 'selected' : '' }}>Publié</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="visibility" class="form-label fw-semibold">Visibilité</label>
                            <select name="visibility" id="visibility" class="form-select">
                                <option value="public" {{ old('visibility', 'public') === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="authenticated" {{ old('visibility') === 'authenticated' ? 'selected' : '' }}>Membres uniquement</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="published_at" class="form-label fw-semibold">Date de publication</label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label fw-semibold">Ordre</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="form-control">
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="form-check-input">
                            <label for="is_featured" class="form-check-label">
                                <i class="fas fa-star text-warning me-1"></i>Mise en vedette
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white text-primary p-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-folder me-2"></i>Catégories
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-body p-4">
                        @if($categories->count() > 0)
                        @foreach($categories as $category)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category_{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="category_{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                        @endforeach
                        @else
                        <p class="text-muted mb-0">Aucune catégorie disponible</p>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white text-primary p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2"></i>Miniature
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="input-group">
                            <input type="text" name="thumbnail" id="thumbnail" value="{{ old('thumbnail') }}" class="form-control">
                            <button type="button" class="btn btn-outline-primary" onclick="openMediaSelector('thumbnail', 'thumbnailPreview')">
                                <i class="fas fa-images"></i>
                            </button>
                        </div>

                        <div class="mt-3 d-none" id="thumbnailPreviewContainer">
                            <img id="thumbnailPreview" alt="Aperçu" class="img-fluid rounded" style="max-height: 150px;">
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="action" value="save" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer la vidéo
                    </button>
                    <button type="submit" name="action" value="save_and_continue" class="btn btn-outline-primary">
                        <i class="fas fa-save me-2"></i>Créer et continuer
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="newCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Catégorie Vidéo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="new_category_name" class="form-control" placeholder="Nom *">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="createCategory()">Créer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let quillDescription = initQuillEditor('#description-editor', 'description');

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('description').value = quillDescription.root.innerHTML;
    });

    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = '';
    });

    const typeSelect = document.getElementById('type');
    const uploadSection = document.getElementById('uploadSection');
    const urlSection = document.getElementById('urlSection');

    typeSelect.addEventListener('change', function() {
        uploadSection.style.display = this.value === 'upload' ? 'block' : 'none';
        urlSection.style.display = ['youtube', 'vimeo', 'dailymotion', 'url'].includes(this.value) ? 'block' : 'none';
    });

    if (typeSelect.value) typeSelect.dispatchEvent(new Event('change'));

    const fetchMetadataBtn = document.getElementById('fetchMetadataBtn');
    const externalUrlInput = document.getElementById('external_url');

    if (fetchMetadataBtn) {
        fetchMetadataBtn.addEventListener('click', async function() {
            const url = externalUrlInput.value;
            const type = typeSelect.value;

            if (!url || !['youtube', 'vimeo', 'dailymotion'].includes(type)) {
                alert('Veuillez sélectionner un type et saisir une URL valide');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Récupération...';

            try {
                const response = await fetch('{{ route("admin.videos.fetch-metadata") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ url, type })
                });

                const data = await response.json();

                if (data.success && data.data) {
                    if (data.data.title && !titleInput.value) {
                        titleInput.value = data.data.title;
                        titleInput.dispatchEvent(new Event('input'));
                    }
                    if (data.data.thumbnail) {
                        document.getElementById('thumbnail').value = data.data.thumbnail;
                        document.getElementById('thumbnailPreview').src = data.data.thumbnail;
                        document.getElementById('thumbnailPreviewContainer').classList.remove('d-none');
                    }
                    if (data.data.duration) document.getElementById('duration').value = data.data.duration;
                    if (data.data.width) document.getElementById('width').value = data.data.width;
                    if (data.data.height) document.getElementById('height').value = data.data.height;

                    alert('✓ Métadonnées récupérées');
                } else {
                    alert('Impossible de récupérer les métadonnées');
                }
            } catch (error) {
                alert('Une erreur est survenue');
            } finally {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-download me-2"></i>Récupérer infos';
            }
        });
    }

    document.getElementById('thumbnail').addEventListener('input', function() {
        if (this.value.trim()) {
            document.getElementById('thumbnailPreview').src = this.value;
            document.getElementById('thumbnailPreviewContainer').classList.remove('d-none');
        } else {
            document.getElementById('thumbnailPreviewContainer').classList.add('d-none');
        }
    });

    const btnOpenLibrary = document.getElementById('btnOpenVideoLibrary');
    if (btnOpenLibrary) {
        btnOpenLibrary.addEventListener('click', function() {
            videoLibrary.open(function(videoData) {
                document.getElementById('library_file_path').value = videoData.file_path || '';
                document.getElementById('library_file_size').value = videoData.file_size || '';
                document.getElementById('library_mime_type').value = videoData.mime_type || '';
                document.getElementById('selectedVideoName').textContent = videoData.original_name || 'Fichier sélectionné';
                document.getElementById('selectedVideoSize').textContent = videoData.file_size || 'N/A';
                document.getElementById('selectedLibraryVideo').classList.remove('d-none');
                document.getElementById('file').disabled = true;
                document.getElementById('file').value = '';
            });
        });
    }

    setTimeout(() => {
        if (typeof window.initQuillAI === 'function') window.initQuillAI();
    }, 1500);
});

window.clearLibrarySelection = function() {
    document.getElementById('library_file_path').value = '';
    document.getElementById('library_file_size').value = '';
    document.getElementById('library_mime_type').value = '';
    document.getElementById('selectedLibraryVideo').classList.add('d-none');
    document.getElementById('file').disabled = false;
    document.getElementById('file').value = '';
}

async function createCategory() {
    const name = document.getElementById('new_category_name').value;
    if (!name) return alert('Le nom est requis');
    
    try {
        const response = await fetch('{{ route("admin.video-categories.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name })
        });
        
        const data = await response.json();
        if (data.success) {
            const container = document.querySelector('.card-body');
            const checkbox = `<div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="categories[]" value="${data.category.id}" id="cat_${data.category.id}" checked><label class="form-check-label" for="cat_${data.category.id}">${data.category.name}</label></div>`;
            container.insertAdjacentHTML('beforeend', checkbox);
            bootstrap.Modal.getInstance(document.getElementById('newCategoryModal')).hide();
            document.getElementById('new_category_name').value = '';
        }
    } catch (error) {
        alert('Erreur lors de la création');
    }
}
</script>
@endpush