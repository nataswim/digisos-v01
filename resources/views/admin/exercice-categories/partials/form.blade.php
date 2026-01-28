@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-folder me-2"></i>Informations de la catégorie
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Nom -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom de la catégorie *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $exerciceCategory->name ?? '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: Membres supérieurs..."
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug <small class="text-muted">(généré automatiquement si vide)</small></label>
                    <input type="text" 
                           name="slug" 
                           id="slug" 
                           value="{{ old('slug', $exerciceCategory->slug ?? '') }}"
                           class="form-control @error('slug') is-invalid @enderror"
                           placeholder="membres-superieurs">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description avec Quill -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    
                    <div id="description-editor" style="height: 400px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <textarea name="description" 
                              id="description" 
                              class="d-none @error('description') is-invalid @enderror">{{ old('description', $exerciceCategory->description ?? '') }}</textarea>
                              
                    <div class="form-text">Décrivez cette catégorie d'exercices...</div>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Champs SEO -->
                <hr class="my-4">
                <h6 class="fw-semibold mb-3">Référencement (SEO)</h6>

                <div class="mb-3">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" 
                           name="meta_title" 
                           id="meta_title" 
                           value="{{ old('meta_title', $exerciceCategory->meta_title ?? '') }}"
                           class="form-control @error('meta_title') is-invalid @enderror"
                           maxlength="255"
                           placeholder="Titre pour les moteurs de recherche">
                    @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea name="meta_description" 
                              id="meta_description" 
                              rows="3"
                              class="form-control @error('meta_description') is-invalid @enderror"
                              placeholder="Description pour les moteurs de recherche">{{ old('meta_description', $exerciceCategory->meta_description ?? '') }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                    <input type="text" 
                           name="meta_keywords" 
                           id="meta_keywords" 
                           value="{{ old('meta_keywords', $exerciceCategory->meta_keywords ?? '') }}"
                           class="form-control @error('meta_keywords') is-invalid @enderror"
                           placeholder="mot-clé1, mot-clé2, mot-clé3">
                    @error('meta_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                    <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                    <input type="number" 
                           name="sort_order" 
                           id="sort_order" 
                           value="{{ old('sort_order', $exerciceCategory->sort_order ?? 0) }}"
                           class="form-control @error('sort_order') is-invalid @enderror"
                           min="0"
                           placeholder="0">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', $exerciceCategory->is_active ?? true) ? 'checked' : '' }}
                           class="form-check-input">
                    <label for="is_active" class="form-check-label">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Catégorie active
                    </label>
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2"></i>Image de la catégorie
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">URL de l'image</label>
                    <div class="input-group">
                        <input type="text" 
                               name="image" 
                               id="image" 
                               value="{{ old('image', $exerciceCategory->image ?? '') }}"
                               class="form-control @error('image') is-invalid @enderror"
                               placeholder="https://exemple.com/image.jpg">
                        <button type="button" 
                                class="btn btn-outline-primary"
                                onclick="openMediaSelector('image', 'imagePreview')">
                            <i class="fas fa-images"></i>
                        </button>
                    </div>
                    <div class="form-text">Sélectionnez depuis la médiathèque ou saisissez une URL</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($exerciceCategory) && $exerciceCategory->image)
                    <div class="mt-3" id="currentImagePreview">
                        <small class="text-muted d-block mb-2">Aperçu actuel :</small>
                        <img src="{{ $exerciceCategory->image }}" 
                             id="imagePreview"
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 150px; object-fit: cover;"
                             alt="Image actuelle">
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
                    <a href="{{ route('admin.exercice-categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@once
<script src="{{ asset('js/media-selector.js') }}"></script>
<script src="{{ asset('js/quill-advanced.js') }}"></script>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation Quill
    let quillDescription = null;
    
    if (document.getElementById('description-editor')) {
        quillDescription = initQuillEditor('#description-editor', 'description');
    }

    // Synchronisation à la soumission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            if (quillDescription) {
                document.getElementById('description').value = quillDescription.root.innerHTML;
            }
        });
    }

    // Aperçu image
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

    // Auto-génération du slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    if (nameInput && slugInput && !slugInput.value) {
        nameInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        });
    }
});
</script>
@endpush