@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-water me-2"></i>Informations du telechargement
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Titre -->
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Titre du telechargement *</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', isset($downloadable) ? $downloadable->title : '') }}"
                           class="form-control form-control-lg @error('title') is-invalid @enderror"
                           placeholder="Ex: Guide complet de la nutrition sportive"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug URL</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">.../</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($downloadable) ? $downloadable->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="slug-automatique">
                    </div>
                    <div class="form-text">Laisser vide pour generation automatique A partir du titre</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>





                <!-- Source du fichier -->
<div class="mb-4">
    <label class="form-label fw-semibold">
        Source du fichier *
        @if(isset($downloadable) && ($downloadable->file_path || $downloadable->ebook_file_id))
            <span class="text-muted">(laisser tel quel pour conserver le fichier actuel)</span>
        @endif
    </label>
    
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 source-card {{ old('file_source', 'upload') === 'upload' ? 'border-primary selected' : '' }}" 
                 onclick="selectFileSource('upload')">
                <div class="card-body">
                    <div class="form-check">
                        <input type="radio" 
                               name="file_source" 
                               id="file_source_upload" 
                               value="upload"
                               class="form-check-input"
                               {{ old('file_source', isset($downloadable) ? '' : 'upload') === 'upload' ? 'checked' : '' }}>
                        <label for="file_source_upload" class="form-check-label">
                            <i class="fas fa-upload text-primary me-2"></i>
                            <strong>Télécharger un nouveau fichier</strong>
                        </label>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Uploadez un fichier depuis votre ordinateur
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card h-100 source-card {{ old('file_source') === 'existing' ? 'border-primary selected' : '' }}"
                 onclick="selectFileSource('existing')">
                <div class="card-body">
                    <div class="form-check">
                        <input type="radio" 
                               name="file_source" 
                               id="file_source_existing" 
                               value="existing"
                               class="form-check-input"
                               {{ old('file_source') === 'existing' ? 'checked' : '' }}>
                        <label for="file_source_existing" class="form-check-label">
                            <i class="fas fa-folder-open text-success me-2"></i>
                            <strong>Choisir un fichier existant</strong>
                        </label>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Sélectionnez un fichier déjà uploadé
                    </small>
                </div>
            </div>
        </div>
    </div>
    @error('file_source')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<!-- Zone upload classique -->
<div class="mb-4" id="upload_section" style="display: {{ old('file_source', isset($downloadable) ? '' : 'upload') === 'upload' ? 'block' : 'none' }};">
    <label for="file" class="form-label fw-semibold">
        Fichier à télécharger
        @if(isset($downloadable))
            <span class="text-muted">(laisser vide pour conserver le fichier actuel)</span>
        @endif
    </label>
    <input type="file" 
           name="file" 
           id="file" 
           class="form-control @error('file') is-invalid @enderror"
           accept=".pdf,.epub,.mp4,.zip,.doc,.docx">
    <div class="form-text">
        Formats acceptés : PDF, EPUB, MP4, ZIP, DOC, DOCX (max 200MB)
    </div>
    
    @if(isset($downloadable))
        @if($downloadable->file_path && !$downloadable->ebook_file_id)
            <div class="mt-2 p-2 bg-light rounded">
                <small class="text-muted">
                    <i class="fas fa-file me-1"></i>
                    Fichier actuel : {{ basename($downloadable->file_path) }}
                    @if($downloadable->file_size)
                        ({{ $downloadable->file_size }})
                    @endif
                </small>
            </div>
        @elseif($downloadable->ebook_file_id && $downloadable->ebookFile)
            <div class="mt-2 p-2 bg-light rounded">
                <small class="text-muted">
                    <i class="fas fa-link me-1"></i>
                    Fichier lié : {{ $downloadable->ebookFile->name }}
                    @if($downloadable->file_size)
                        ({{ $downloadable->file_size }})
                    @endif
                </small>
            </div>
        @endif
    @endif
    
    @error('file')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Zone sélection fichier existant -->
<div class="mb-4" id="existing_section" style="display: {{ old('file_source') === 'existing' ? 'block' : 'none' }};">
    <label for="ebook_file_id" class="form-label fw-semibold">
        Fichier existant
    </label>
    
    <input type="hidden" 
           name="ebook_file_id" 
           id="ebook_file_id" 
           value="{{ old('ebook_file_id', isset($downloadable) ? $downloadable->ebook_file_id : '') }}">
    
    <div class="border rounded p-3 bg-light">
        <div id="selected_ebook_preview" class="{{ old('ebook_file_id', isset($downloadable) ? $downloadable->ebook_file_id : '') ? '' : 'd-none' }}">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file fa-2x text-primary me-3" id="ebook_icon"></i>
                    <div>
                        <div class="fw-semibold" id="ebook_name">
                            @if(isset($downloadable) && $downloadable->ebookFile)
                                {{ $downloadable->ebookFile->name }}
                            @endif
                        </div>
                        <small class="text-muted" id="ebook_info">
                            @if(isset($downloadable) && $downloadable->ebookFile)
                                {{ $downloadable->ebookFile->formatted_size }} • {{ $downloadable->ebookFile->format_label }}
                            @endif
                        </small>
                    </div>
                </div>
                <button type="button" 
                        class="btn btn-sm btn-outline-danger"
                        onclick="clearSelectedEbookFile()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div id="no_ebook_selected" class="{{ old('ebook_file_id', isset($downloadable) ? $downloadable->ebook_file_id : '') ? 'd-none' : 'text-center py-3' }}">
            <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
            <p class="text-muted mb-2">Aucun fichier sélectionné</p>
        </div>
        
        <button type="button" 
                class="btn btn-primary w-100 mt-2"
                onclick="openEbookFileSelector()">
            <i class="fas fa-folder-open me-2"></i>Parcourir les fichiers eBook
        </button>
    </div>
    
    @error('ebook_file_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    
    <div class="mt-2">
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Vous pouvez aussi <a href="{{ route('admin.ebook-files.index') }}" target="_blank">gérer vos fichiers eBook</a>
        </small>
    </div>
</div>




                <!-- Format -->
                <div class="mb-4">
                    <label for="format" class="form-label fw-semibold">Format *</label>
                    <select name="format" id="format" class="form-select @error('format') is-invalid @enderror" required>
                        <option value="">Selectionner un format</option>
                        <option value="pdf" {{ old('format', isset($downloadable) ? $downloadable->format : '') === 'pdf' ? 'selected' : '' }}>
                            PDF - Document portable
                        </option>
                        <option value="epub" {{ old('format', isset($downloadable) ? $downloadable->format : '') === 'epub' ? 'selected' : '' }}>
                            EPUB - Livre electronique
                        </option>
                        <option value="mp4" {{ old('format', isset($downloadable) ? $downloadable->format : '') === 'mp4' ? 'selected' : '' }}>
                            MP4 - Video
                        </option>
                        <option value="zip" {{ old('format', isset($downloadable) ? $downloadable->format : '') === 'zip' ? 'selected' : '' }}>
                            ZIP - Archive compressee
                        </option>
                        <option value="doc" {{ old('format', isset($downloadable) ? $downloadable->format : '') === 'doc' ? 'selected' : '' }}>
                            DOC - Word (ancien format)
                        </option>
                        <option value="docx" {{ old('format', isset($downloadable) ? $downloadable->format : '') === 'docx' ? 'selected' : '' }}>
                            DOCX - Word (nouveau format)
                        </option>
                    </select>
                    @error('format')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description courte -->
                <div class="mb-4">
                    <label for="short_description" class="form-label fw-semibold">Description courte</label>
                    <textarea name="short_description" 
                              id="short_description" 
                              rows="3"
                              class="form-control @error('short_description') is-invalid @enderror"
                              maxlength="1000"
                              placeholder="Resume du contenu...">{{ old('short_description', isset($downloadable) ? $downloadable->short_description : '') }}</textarea>
                    <div class="form-text">Description affichee dans les listes (max 1000 caracteres)</div>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description complete avec Quill -->
                <div class="mb-4">
                    <label for="long_description" class="form-label fw-semibold">Description complete</label>
                    
                    <!-- Conteneur pour l'editeur Quill -->
                    <div id="description-editor" style="height: 200px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <!-- Textarea cachee pour Laravel -->
                    <textarea name="long_description" 
                              id="long_description" 
                              class="d-none @error('long_description') is-invalid @enderror">{{ old('long_description', isset($downloadable) ? $downloadable->long_description : '') }}</textarea>
                              
                    <div class="form-text">Description detaillee affichee sur la page du telechargement</div>
                    @error('long_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SEO et metadonnees -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-search me-2"></i>SEO et Metadonnees
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="meta_title" class="form-label fw-semibold">Titre SEO</label>
                        <input type="text" 
                               name="meta_title" 
                               id="meta_title" 
                               value="{{ old('meta_title', isset($downloadable) ? $downloadable->meta_title : '') }}"
                               class="form-control @error('meta_title') is-invalid @enderror"
                               maxlength="255"
                               placeholder="Titre optimise pour les moteurs de recherche">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="meta_keywords" class="form-label fw-semibold">Mots-cles</label>
                        <input type="text" 
                               name="meta_keywords" 
                               id="meta_keywords" 
                               value="{{ old('meta_keywords', isset($downloadable) ? $downloadable->meta_keywords : '') }}"
                               class="form-control @error('meta_keywords') is-invalid @enderror"
                               placeholder="mot1, mot2, mot3">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="meta_description" class="form-label fw-semibold">Description SEO</label>
                        <textarea name="meta_description" 
                                  id="meta_description" 
                                  rows="3"
                                  class="form-control @error('meta_description') is-invalid @enderror"
                                  maxlength="500"
                                  placeholder="Description qui apparaîtra dans les resultats de recherche...">{{ old('meta_description', isset($downloadable) ? $downloadable->meta_description : '') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Configuration -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Configuration
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="download_category_id" class="form-label fw-semibold">Categorie *</label>
                    <select name="download_category_id" id="download_category_id" class="form-select @error('download_category_id') is-invalid @enderror" required>
                        <option value="">Selectionner une categorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('download_category_id', isset($downloadable) ? $downloadable->download_category_id : request('category')) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('download_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Statut</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', isset($downloadable) ? $downloadable->status : 'active') === 'active' ? 'selected' : '' }}>
                            Actif
                        </option>
                        <option value="inactive" {{ old('status', isset($downloadable) ? $downloadable->status : '') === 'inactive' ? 'selected' : '' }}>
                            Inactif
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label fw-semibold">Ordre d'affichage</label>
                    <input type="number" 
                           name="order" 
                           id="order" 
                           value="{{ old('order', isset($downloadable) ? $downloadable->order : 0) }}"
                           class="form-control @error('order') is-invalid @enderror"
                           placeholder="0">
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" 
                           name="is_featured" 
                           id="is_featured" 
                           value="1"
                           {{ old('is_featured', isset($downloadable) ? $downloadable->is_featured : false) ? 'checked' : '' }}
                           class="form-check-input">
                    <label for="is_featured" class="form-check-label">
                        <i class="fas fa-star text-warning me-1"></i>
                        Telechargement mis en avant
                    </label>
                </div>
            </div>
        </div>

        <!-- Permissions d'acces -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Permissions d'acces
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="user_permission" class="form-label fw-semibold">Niveau d'acces requis</label>
                    <select name="user_permission" id="user_permission" class="form-select @error('user_permission') is-invalid @enderror" required>
                        <option value="public" {{ old('user_permission', isset($downloadable) ? $downloadable->user_permission : 'public') === 'public' ? 'selected' : '' }}>
                            <i class="fas fa-water"></i> Public - Accessible A tous
                        </option>
                        <option value="visitor" {{ old('user_permission', isset($downloadable) ? $downloadable->user_permission : '') === 'visitor' ? 'selected' : '' }}>
                            <i class="fas fa-eye"></i> Visiteur - Non-connectes uniquement
                        </option>
                        <option value="user" {{ old('user_permission', isset($downloadable) ? $downloadable->user_permission : '') === 'user' ? 'selected' : '' }}>
                            <i class="fas fa-user"></i> Utilisateur - Membres valides
                        </option>
                    </select>
                    
                    <!-- Aide contextuelle -->
                    <div class="form-text">
                        <div id="permission-help" class="mt-2 p-3 rounded" style="background-color: #f8f9fa;">
                            <div id="public-help" style="display: none;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-water text-success me-2 mt-1"></i>
                                    <div>
                                        <strong class="text-success">Acces public</strong>
                                        <div class="small text-muted mt-1">
                                            • Telechargeable par tous les visiteurs<br>
                                            • Aucune connexion requise<br>
                                            • Ideal pour le contenu promotionnel
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="visitor-help" style="display: none;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-eye text-info me-2 mt-1"></i>
                                    <div>
                                        <strong class="text-info">Acces visiteur</strong>
                                        <div class="small text-muted mt-1">
                                            • Reserve aux non-connectes<br>
                                            • Incite A decouvrir sans inscription<br>
                                            • Utile pour du contenu d'accroche
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="user-help" style="display: none;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-user text-warning me-2 mt-1"></i>
                                    <div>
                                        <strong class="text-warning">Acces membre</strong>
                                        <div class="small text-muted mt-1">
                                            • Reserve aux utilisateurs connectes et valides<br>
                                            • Exclut les comptes "visitor"<br>
                                            • Contenu premium et exclusif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @error('user_permission')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Image de couverture -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2"></i>Image de couverture
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="cover_image" class="form-label fw-semibold">URL de l'image</label>
                    <div class="input-group">
                        <input type="text" 
                               name="cover_image" 
                               id="cover_image" 
                               value="{{ old('cover_image', isset($downloadable) ? $downloadable->cover_image : '') }}"
                               class="form-control @error('cover_image') is-invalid @enderror"
                               placeholder="https://exemple.com/cover.jpg">
                        <button type="button" 
                                class="btn btn-outline-primary"
                                onclick="openMediaSelector('cover_image', 'coverPreview')">
                            <i class="fas fa-images"></i>
                        </button>
                    </div>
                    <div class="form-text">Selectionnez depuis la mediatheque ou saisissez une URL</div>
                    @error('cover_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($downloadable) && $downloadable->cover_image)
                    <div class="mt-3" id="currentCoverPreview">
                        <small class="text-muted d-block mb-2">Aperçu actuel :</small>
                        <img src="{{ $downloadable->cover_image }}" 
                             id="coverPreview"
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 200px; object-fit: cover;"
                             alt="Couverture actuelle">
                    </div>
                @else
                    <div class="mt-3 d-none" id="currentCoverPreview">
                        <small class="text-muted d-block mb-2">Aperçu :</small>
                        <img id="coverPreview"
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 200px; object-fit: cover;"
                             alt="Aperçu">
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.downloadables.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                        </button>
                        <button type="submit" name="action" value="save_and_continue" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Enregistrer et continuer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>






.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}
/* Styles pour la sélection de source */
.source-card {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid #e5e7eb;
}

.source-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.source-card.selected {
    border-color: #0ea5e9 !important;
    background-color: #f0f9ff;
}

.source-card .form-check-input:checked ~ .form-check-label {
    color: #0ea5e9;
    font-weight: 600;
}

/* Styles pour les fichiers eBook dans la modal */
.ebook-file-item {
    transition: all 0.2s ease;
    border: 2px solid #e5e7eb;
}

.ebook-file-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.ebook-file-item.selected {
    border-color: #0ea5e9 !important;
    background-color: #f0f9ff;
    box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.25);
}

/* Badge de format */
.badge.bg-secondary {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endpush



@push('scripts')
{{-- Charger les scripts UNE SEULE FOIS --}}
@once
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="{{ asset('js/media-selector.js') }}"></script>
<script src="{{ asset('js/quill-advanced.js') }}"></script>
<script src="{{ asset('js/quill-ai-optimizer.js') }}"></script>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        // Auto-génération du slug
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

        // ⚡ UTILISER initQuillEditor AU LIEU DE new Quill
        let quillDescription = null;
        if (document.getElementById('description-editor')) {
            quillDescription = initQuillEditor('#description-editor', 'long_description');
            console.log('✅ Description editor initialized');
        }

        // Aide contextuelle permissions
        const permissionSelect = document.getElementById('user_permission');
        const helpDivs = {
            'public': document.getElementById('public-help'),
            'visitor': document.getElementById('visitor-help'),
            'user': document.getElementById('user-help')
        };

        function updatePermissionHelp() {
            Object.values(helpDivs).forEach(div => {
                if (div) div.style.display = 'none';
            });
            const selectedValue = permissionSelect.value;
            if (helpDivs[selectedValue]) {
                helpDivs[selectedValue].style.display = 'block';
            }
        }

        if (permissionSelect) {
            permissionSelect.addEventListener('change', updatePermissionHelp);
            updatePermissionHelp();
        }

        // Aperçu image de couverture
        const coverInput = document.getElementById('cover_image');
        const coverPreview = document.getElementById('coverPreview');
        const coverPreviewContainer = document.getElementById('currentCoverPreview');
        
        if (coverInput && coverPreview && coverPreviewContainer) {
            coverInput.addEventListener('input', function() {
                const imageUrl = this.value.trim();
                if (imageUrl) {
                    coverPreview.src = imageUrl;
                    coverPreviewContainer.classList.remove('d-none');
                } else {
                    coverPreviewContainer.classList.add('d-none');
                }
            });
        }

        // Validation format de fichier
        const fileInput = document.getElementById('file');
        const formatSelect = document.getElementById('format');
        
        if (fileInput && formatSelect) {
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const fileName = this.files[0].name;
                    const extension = fileName.split('.').pop().toLowerCase();
                    if (['pdf', 'epub', 'mp4', 'zip', 'doc', 'docx'].includes(extension)) {
                        formatSelect.value = extension;
                    }
                }
            });
        }

        // Initialiser l'IA
        setTimeout(function() {
            if (typeof window.initQuillAI === 'function') {
                console.log('🤖 Initialisation IA...');
                window.initQuillAI();
            }
        }, 2000);
        
    }, 500);
});
</script>
<!-- Inclure la modal sélecteur -->
@include('admin.downloadables.partials.ebook-file-selector-modal')

@push('scripts')
<!-- Inclure le JS du sélecteur -->
<script src="{{ asset('js/ebook-file-selector.js') }}"></script>

<!-- Script existant Quill etc... -->
<!-- ... garder le script existant ... -->
@endpush