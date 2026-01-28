@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Informations de la Section
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Nom -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom de la section *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', isset($section) ? $section->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Saisissez un nom..."
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug URL</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">{{ url('/catalogue') }}/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($section) ? $section->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="slug-automatique">
                    </div>
                    <div class="form-text">Laisser vide pour génération automatique</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description courte avec Quill et IA -->
                <div class="mb-4">
                    <label for="short_description" class="form-label fw-semibold">Description courte</label>
                    
                    <!-- Conteneur pour l'éditeur Quill -->
                    <div id="short-description-editor" style="height: 200px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <!-- Textarea cachée pour Laravel -->
                    <textarea name="short_description" 
                              id="short_description" 
                              class="d-none @error('short_description') is-invalid @enderror">{{ old('short_description', isset($section) ? $section->short_description : '') }}</textarea>
                              
                    <div class="form-text">Résumé de la section</div>
                    @error('short_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description longue avec Quill et IA -->
                <div class="mb-4">
                    <label for="long_description" class="form-label fw-semibold">Description complète</label>
                    
                    <!-- Conteneur pour l'éditeur Quill -->
                    <div id="long-description-editor" style="height: 350px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <!-- Textarea cachée pour Laravel -->
                    <textarea name="long_description" 
                              id="long_description" 
                              class="d-none @error('long_description') is-invalid @enderror">{{ old('long_description', isset($section) ? $section->long_description : '') }}</textarea>
                              
                    <div class="form-text">Description détaillée</div>
                    @error('long_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-search me-2"></i>SEO et Métadonnées
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="meta_title" class="form-label fw-semibold">Titre SEO</label>
                        <input type="text" 
                               name="meta_title" 
                               id="meta_title" 
                               value="{{ old('meta_title', isset($section) ? $section->meta_title : '') }}"
                               class="form-control @error('meta_title') is-invalid @enderror"
                               maxlength="60">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="meta_description" class="form-label fw-semibold">Description SEO</label>
                        <textarea name="meta_description" 
                                  id="meta_description" 
                                  rows="3"
                                  class="form-control @error('meta_description') is-invalid @enderror"
                                  maxlength="160">{{ old('meta_description', isset($section) ? $section->meta_description : '') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="meta_keywords" class="form-label fw-semibold">Mots-clés</label>
                        <input type="text" 
                               name="meta_keywords" 
                               id="meta_keywords" 
                               value="{{ old('meta_keywords', isset($section) ? $section->meta_keywords : '') }}"
                               class="form-control @error('meta_keywords') is-invalid @enderror"
                               placeholder="mot1, mot2, mot3">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Paramètres -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Paramètres
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="order" class="form-label fw-semibold">Ordre d'affichage</label>
                    <input type="number" 
                           name="order" 
                           id="order" 
                           value="{{ old('order', isset($section) ? $section->order : 0) }}"
                           class="form-control @error('order') is-invalid @enderror"
                           min="0">
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', isset($section) ? $section->is_active : true) ? 'checked' : '' }}
                           class="form-check-input">
                    <label for="is_active" class="form-check-label">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Section active
                    </label>
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2"></i>Image
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">URL de l'image</label>
                    <div class="input-group">
                        <input type="text" 
                               name="image" 
                               id="image" 
                               value="{{ old('image', isset($section) ? $section->image : '') }}"
                               class="form-control @error('image') is-invalid @enderror">
                        <button type="button" 
                                class="btn btn-outline-primary"
                                onclick="openMediaSelector('image', 'imagePreview')">
                            <i class="fas fa-images"></i>
                        </button>
                    </div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($section) && $section->image)
                    <div class="mt-3" id="currentImagePreview">
                        <small class="text-muted d-block mb-2">Aperçu :</small>
                        <img src="{{ $section->image }}" 
                             id="imagePreview"
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 150px; object-fit: cover;"
                             alt="Image">
                    </div>
                @else
                    <div class="mt-3 d-none" id="currentImagePreview">
                        <small class="text-muted d-block mb-2">Aperçu :</small>
                        <img id="imagePreview"
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 150px; object-fit: cover;"
                             alt="Aperçu">
                    </div>
                @endif
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
                    <a href="{{ route('admin.catalogue-sections.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
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

{{-- Scripts d'initialisation --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // 1. INITIALISATION DES ÉDITEURS QUILL
    // ========================================
    let quillShortDescription = null;
    let quillLongDescription = null;
    
    if (document.getElementById('short-description-editor')) {
        quillShortDescription = initQuillEditor('#short-description-editor', 'short_description');
    }
    
    if (document.getElementById('long-description-editor')) {
        quillLongDescription = initQuillEditor('#long-description-editor', 'long_description');
    }

    // ========================================
    // 2. SYNCHRONISATION À LA SOUMISSION
    // ========================================
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const shortDescriptionTextarea = document.getElementById('short_description');
            if (shortDescriptionTextarea && quillShortDescription) {
                shortDescriptionTextarea.value = quillShortDescription.root.innerHTML;
            }
            
            const longDescriptionTextarea = document.getElementById('long_description');
            if (longDescriptionTextarea && quillLongDescription) {
                longDescriptionTextarea.value = quillLongDescription.root.innerHTML;
            }
        });
    }

    // ========================================
    // 3. AUTO-GÉNÉRATION DU SLUG
    // ========================================
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
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

    // ========================================
    // 4. APERÇU IMAGE
    // ========================================
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('currentImagePreview');
    
    if (imageInput && imagePreview && imagePreviewContainer) {
        imageInput.addEventListener('input', function() {
            const imageUrl = this.value.trim();
            if (imageUrl) {
                imagePreview.src = imageUrl;
                imagePreviewContainer.classList.remove('d-none');
            } else {
                imagePreviewContainer.classList.add('d-none');
            }
        });
    }

    // ========================================
    // 5. INITIALISER L'IA APRÈS QUILL
    // ========================================
    setTimeout(function() {
        if (typeof window.initQuillAI === 'function') {
            window.initQuillAI();
        }
    }, 1500);
});
</script>
@endpush