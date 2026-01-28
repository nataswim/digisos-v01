@extends('layouts.admin')

@section('title', 'Créer une Vidéo')
@section('page-title', 'Nouvelle Vidéo')
@section('page-description', 'Ajout d\'une nouvelle vidéo')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Contenu principal -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-video me-2"></i>Informations de la vidéo
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Titre -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">Titre de la vidéo *</label>
                            <input type="text"
                                name="title"
                                id="title"
                                value="{{ old('title') }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                placeholder="Ex: Technique du crawl pour débutants"
                                required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">Slug URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ url('/videos') }}/</span>
                                <input type="text"
                                    name="slug"
                                    id="slug"
                                    value="{{ old('slug') }}"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    placeholder="technique-crawl-debutants">
                            </div>
                            <div class="form-text">Laisser vide pour génération automatique</div>
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description avec Quill Editor + IA -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>

                            <div id="description-editor" style="height: 500px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>

                            <textarea name="description"
                                id="description"
                                class="d-none @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                            @error('description')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror

                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Le bouton "IA" apparaîtra automatiquement dans la barre d'outils.
                            </div>
                        </div>






<!-- Type de source -->
<div class="mb-4">
    <label for="type" class="form-label fw-semibold">Type de source *</label>
    <select name="type"
        id="type"
        class="form-select @error('type') is-invalid @enderror"
        required>
        <option value="">Sélectionner un type</option>
        <option value="upload" {{ old('type') === 'upload' ? 'selected' : '' }}>
            Upload fichier
        </option>
        <option value="youtube" {{ old('type') === 'youtube' ? 'selected' : '' }}>
            YouTube
        </option>
        <option value="vimeo" {{ old('type') === 'vimeo' ? 'selected' : '' }}>
            Vimeo
        </option>
        <option value="dailymotion" {{ old('type') === 'dailymotion' ? 'selected' : '' }}>
            Dailymotion
        </option>
        <option value="url" {{ old('type') === 'url' ? 'selected' : '' }}>
            URL directe
        </option>
    </select>
    @error('type')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Upload fichier -->
<div class="mb-4" id="uploadSection" style="display: none;">
    <label for="file" class="form-label fw-semibold">Fichier vidéo</label>
    
    <!-- Tabs pour choisir entre Upload et Bibliothèque -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-pane" type="button">
                <i class="fas fa-upload me-1"></i>Upload nouveau fichier
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="library-tab" data-bs-toggle="tab" data-bs-target="#library-pane" type="button">
                <i class="fas fa-folder-open me-1"></i>Choisir dans la bibliothèque
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Tab Upload -->
        <div class="tab-pane fade show active" id="upload-pane" role="tabpanel">
            <input type="file"
                   name="file"
                   id="file"
                   class="form-control @error('file') is-invalid @enderror"
                   accept="video/mp4,video/webm,video/quicktime,video/x-msvideo">
            <div class="form-text">Formats acceptés: mp4, webm, mov, avi - Taille max: 500MB</div>
            @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tab Bibliothèque -->
        <div class="tab-pane fade" id="library-pane" role="tabpanel">
            <button type="button" class="btn btn-primary" id="btnOpenVideoLibrary">
                <i class="fas fa-folder-open me-2"></i>Parcourir la bibliothèque vidéo
            </button>
            <div class="form-text">Sélectionner une vidéo depuis storage/media/video/ (max 200 Mo)</div>
            
            <!-- Fichier sélectionné depuis la bibliothèque -->
            <div id="selectedLibraryVideo" class="d-none mt-3">
                <div class="alert alert-success d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Vidéo sélectionnée :</strong>
                        <span id="selectedVideoName"></span>
                        <br>
                        <small class="text-muted">
                            Taille : <span id="selectedVideoSize"></span>
                        </small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearLibrarySelection()">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
                <!-- Champs cachés pour stocker les infos -->
                <input type="hidden" name="library_file_path" id="library_file_path">
                <input type="hidden" name="library_file_size" id="library_file_size">
                <input type="hidden" name="library_mime_type" id="library_mime_type">
            </div>
        </div>
    </div>
</div>

















                        <!-- URL externe -->
                        <div class="mb-4" id="urlSection" style="display: none;">
                            <label for="external_url" class="form-label fw-semibold">URL de la vidéo</label>
                            <div class="input-group">
                                <input type="url"
                                    name="external_url"
                                    id="external_url"
                                    value="{{ old('external_url') }}"
                                    class="form-control @error('external_url') is-invalid @enderror"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                <button type="button"
                                    class="btn btn-outline-primary"
                                    id="fetchMetadataBtn">
                                    <i class="fas fa-download me-2"></i>Récupérer infos
                                </button>
                            </div>
                            <div class="form-text">Collez l'URL de la vidéo pour récupérer automatiquement les métadonnées</div>
                            @error('external_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Métadonnées -->
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-info-circle me-2 text-info"></i>Métadonnées
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="duration" class="form-label">Durée (secondes)</label>
                                    <input type="number"
                                        name="duration"
                                        id="duration"
                                        value="{{ old('duration') }}"
                                        class="form-control"
                                        min="0"
                                        placeholder="120">
                                </div>
                                <div class="col-md-4">
                                    <label for="width" class="form-label">Largeur (px)</label>
                                    <input type="number"
                                        name="width"
                                        id="width"
                                        value="{{ old('width') }}"
                                        class="form-control"
                                        min="0"
                                        placeholder="1920">
                                </div>
                                <div class="col-md-4">
                                    <label for="height" class="form-label">Hauteur (px)</label>
                                    <input type="number"
                                        name="height"
                                        id="height"
                                        value="{{ old('height') }}"
                                        class="form-control"
                                        min="0"
                                        placeholder="1080">
                                </div>
                            </div>
                        </div>

                        <!-- SEO -->
                        <div class="border-top pt-4 mt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-search me-2 text-primary"></i>SEO et Métadonnées
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="meta_title" class="form-label">Titre SEO</label>
                                    <input type="text"
                                        name="meta_title"
                                        id="meta_title"
                                        value="{{ old('meta_title') }}"
                                        class="form-control"
                                        maxlength="60">
                                </div>
                                <div class="col-md-6">
                                    <label for="meta_keywords" class="form-label">Mots-clés</label>
                                    <input type="text"
                                        name="meta_keywords"
                                        id="meta_keywords"
                                        value="{{ old('meta_keywords') }}"
                                        class="form-control"
                                        placeholder="natation, technique, crawl">
                                </div>
                                <div class="col-12">
                                    <label for="meta_description" class="form-label">Description SEO</label>
                                    <textarea name="meta_description"
                                        id="meta_description"
                                        rows="3"
                                        class="form-control"
                                        maxlength="160">{{ old('meta_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publication -->
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
                                <option value="0" {{ old('is_published', 0) == 0 ? 'selected' : '' }}>
                                    Brouillon
                                </option>
                                <option value="1" {{ old('is_published', 0) == 1 ? 'selected' : '' }}>
                                    Publié
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="visibility" class="form-label fw-semibold">
                                <i class="fas fa-eye me-1 text-info"></i>Visibilité
                            </label>
                            <select name="visibility" id="visibility" class="form-select">
                                <option value="public" {{ old('visibility', 'public') === 'public' ? 'selected' : '' }}>
                                    Public - Accessible à tous
                                </option>
                                <option value="authenticated" {{ old('visibility') === 'authenticated' ? 'selected' : '' }}>
                                    Membres uniquement
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="published_at" class="form-label fw-semibold">Date de publication</label>
                            <input type="datetime-local"
                                name="published_at"
                                id="published_at"
                                value="{{ old('published_at') }}"
                                class="form-control">
                            <div class="form-text">Laisser vide pour publication immédiate</div>
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                            <input type="number"
                                name="sort_order"
                                id="sort_order"
                                value="{{ old('sort_order', 0) }}"
                                class="form-control"
                                placeholder="0">
                        </div>

                        <div class="form-check">
                            <input type="checkbox"
                                name="is_featured"
                                id="is_featured"
                                value="1"
                                {{ old('is_featured', false) ? 'checked' : '' }}
                                class="form-check-input">
                            <label for="is_featured" class="form-check-label">
                                <i class="fas fa-star text-warning me-1"></i>
                                Vidéo mise en vedette
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Catégories -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-white p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-folder me-2"></i>Catégories
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($categories->count() > 0)
                        @foreach($categories as $category)
                        <div class="form-check mb-2">
                            <input class="form-check-input"
                                type="checkbox"
                                name="categories[]"
                                value="{{ $category->id }}"
                                id="category_{{ $category->id }}"
                                {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="category_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                        @endforeach
                        @else
                        <p class="text-muted mb-0">Aucune catégorie disponible</p>
                        <a href="{{ route('admin.video-categories.create') }}" class="btn btn-sm btn-outline-primary mt-2">
                            Créer une catégorie
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Thumbnail -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2"></i>Miniature
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="input-group">
                            <input type="text"
                                name="thumbnail"
                                id="thumbnail"
                                value="{{ old('thumbnail') }}"
                                class="form-control"
                                placeholder="https://exemple.com/thumbnail.jpg">
                            <button type="button"
                                class="btn btn-outline-primary"
                                onclick="openMediaSelector('thumbnail', 'thumbnailPreview')">
                                <i class="fas fa-images"></i>
                            </button>
                        </div>
                        <div class="form-text">Miniature de la vidéo</div>

                        <div class="mt-3 d-none" id="thumbnailPreviewContainer">
                            <img id="thumbnailPreview"
                                alt="Aperçu"
                                class="img-fluid rounded"
                                style="max-height: 150px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="save" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Créer la vidéo
                                </button>
                                <button type="submit" name="action" value="save_and_continue" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Créer et continuer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    

    

    

    

    /* Styles Quill personnalisés */
    #toolbar-description {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-bottom: none;
        border-radius: 0.375rem 0.375rem 0 0;
        padding: 0.5rem;
    }

    #quill-description {
        border: 1px solid #dee2e6;
        border-radius: 0 0 0.375rem 0.375rem;
        font-size: 1rem;
    }

    .ql-toolbar.ql-snow {
        border: none;
        padding: 0;
    }

    .ql-container.ql-snow {
        border: none;
    }

    .ql-editor {
        min-height: 400px;
    }

    /* Bouton IA */
    #optimizeDescriptionBtn {
        transition: all 0.3s ease;
    }

    #optimizeDescriptionBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(14, 165, 233, 0.3);
    }
</style>

<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
{{-- Scripts Quill + IA (chargés UNE FOIS) --}}
@once
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="{{ asset('js/media-selector.js') }}"></script>
<script src="{{ asset('js/quill-advanced.js') }}"></script>
<script src="{{ asset('js/quill-ai-optimizer.js') }}"></script>
<script src="{{ asset('js/video-library-selector.js') }}"></script>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎬 Initialisation éditeur vidéo...');

    // ========== 1. INITIALISER QUILL ==========
    let quillDescription = null;

    if (document.getElementById('description-editor')) {
        quillDescription = initQuillEditor('#description-editor', 'description');
        console.log('✅ Éditeur Quill initialisé');
    }

    // ========== 2. SYNCHRONISATION À LA SOUMISSION ==========
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const descriptionTextarea = document.getElementById('description');
            if (descriptionTextarea && quillDescription) {
                descriptionTextarea.value = quillDescription.root.innerHTML;
            }
        });
    }

    // ========== 3. AUTO-GÉNÉRATION DU SLUG ==========
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.autoGenerated) {
                const slug = this.value
                    .toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.autoGenerated = '';
        });
    }

    // ========== 4. GESTION DU TYPE DE SOURCE ==========
    const typeSelect = document.getElementById('type');
    const uploadSection = document.getElementById('uploadSection');
    const urlSection = document.getElementById('urlSection');

    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            uploadSection.style.display = this.value === 'upload' ? 'block' : 'none';
            urlSection.style.display = ['youtube', 'vimeo', 'dailymotion', 'url'].includes(this.value) ? 'block' : 'none';
        });

        if (typeSelect.value) {
            typeSelect.dispatchEvent(new Event('change'));
        }
    }

    // ========== 5. RÉCUPÉRATION DES MÉTADONNÉES ==========
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
                    body: JSON.stringify({
                        url,
                        type
                    })
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

                    alert('✓ Métadonnées récupérées avec succès !');
                } else {
                    alert('Impossible de récupérer les métadonnées');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            } finally {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-download me-2"></i>Récupérer infos';
            }
        });
    }

    // ========== 6. APERÇU THUMBNAIL ==========
    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const thumbnailPreviewContainer = document.getElementById('thumbnailPreviewContainer');

    if (thumbnailInput && thumbnailPreview) {
        thumbnailInput.addEventListener('input', function() {
            if (this.value.trim()) {
                thumbnailPreview.src = this.value;
                thumbnailPreviewContainer.classList.remove('d-none');
            } else {
                thumbnailPreviewContainer.classList.add('d-none');
            }
        });
    }

    // ========== 7. INITIALISER L'IA ==========
    setTimeout(function() {
        if (typeof window.initQuillAI === 'function') {
            window.initQuillAI();
            console.log('✅ Module IA initialisé');
        } else {
            console.warn('⚠️ Module IA non disponible');
        }
    }, 1500);

    // ========== 8. BIBLIOTHÈQUE VIDÉO ==========
    const btnOpenLibrary = document.getElementById('btnOpenVideoLibrary');
    if (btnOpenLibrary) {
        btnOpenLibrary.addEventListener('click', function() {
            videoLibrary.open(function(videoData) {
                console.log('✅ Vidéo sélectionnée:', videoData);
                
                // Remplir les champs cachés
                const pathInput = document.getElementById('library_file_path');
                const sizeInput = document.getElementById('library_file_size');
                const mimeInput = document.getElementById('library_mime_type');
                const nameSpan = document.getElementById('selectedVideoName');
                const sizeSpan = document.getElementById('selectedVideoSize');
                const container = document.getElementById('selectedLibraryVideo');
                
                // Vérifier que tous les éléments existent
                if (!pathInput || !sizeInput || !mimeInput || !nameSpan || !sizeSpan || !container) {
                    console.error('❌ Éléments HTML manquants');
                    alert('Erreur : Éléments HTML manquants. Veuillez recharger la page.');
                    return;
                }
                
                // Remplir les valeurs
                pathInput.value = videoData.file_path || '';
                sizeInput.value = videoData.file_size || '';
                mimeInput.value = videoData.mime_type || '';
                nameSpan.textContent = videoData.original_name || 'Fichier sélectionné';
                sizeSpan.textContent = videoData.file_size || 'N/A';
                
                // Afficher la confirmation
                container.classList.remove('d-none');
                
                // Désactiver l'upload classique
                const fileInput = document.getElementById('file');
                if (fileInput) {
                    fileInput.disabled = true;
                    fileInput.value = '';
                }
                
                console.log('✅ Sélection appliquée avec succès');
            });
        });
    }
});

// Fonction pour annuler la sélection de la bibliothèque
window.clearLibrarySelection = function() {
    const pathInput = document.getElementById('library_file_path');
    const sizeInput = document.getElementById('library_file_size');
    const mimeInput = document.getElementById('library_mime_type');
    const container = document.getElementById('selectedLibraryVideo');
    const fileInput = document.getElementById('file');
    
    if (pathInput) pathInput.value = '';
    if (sizeInput) sizeInput.value = '';
    if (mimeInput) mimeInput.value = '';
    if (container) container.classList.add('d-none');
    if (fileInput) {
        fileInput.disabled = false;
        fileInput.value = '';
    }
    
    console.log('✅ Sélection annulée');
}
</script>
@endpush