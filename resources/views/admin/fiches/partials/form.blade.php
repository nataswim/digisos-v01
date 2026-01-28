@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Contenu de la fiche
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Titre -->
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Titre de la fiche *</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', isset($fiche) ? $fiche->title : '') }}"
                           class="form-control form-control-lg @error('title') is-invalid @enderror"
                           placeholder="Saisissez un titre..."
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug URL</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">{{ url('/fiches/{categorie}') }}/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($fiche) ? $fiche->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="slug-automatique">
                    </div>
                    <div class="form-text">Laisser vide pour génération automatique à partir du titre</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description courte avec Quill -->
                <div class="mb-4">
                    <label for="short_description" class="form-label fw-semibold">
                        Description courte / Résumé *
                        <span class="badge bg-info-subtle text-info ms-2">Toujours visible</span>
                    </label>
                    
                    <!-- Conteneur pour l'éditeur Quill -->
                    <div id="short-description-editor" style="height: 400px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <!-- Textarea cachée pour Laravel -->
                    <textarea name="short_description" 
                              id="short_description" 
                              class="d-none @error('short_description') is-invalid @enderror"
                              required>{{ old('short_description', isset($fiche) ? $fiche->short_description : '') }}</textarea>
                              
                    <div class="form-text">Cette description sera visible par tous les visiteurs, même pour les fiches restreintes</div>
                    @error('short_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description longue avec Quill -->
                <div class="mb-4">
                    <label for="long_description" class="form-label fw-semibold">
                        Description complète
                        <span class="badge bg-warning-subtle text-warning ms-2">Selon visibilité</span>
                    </label>
                    
                    <!-- Conteneur pour l'éditeur Quill -->
                    <div id="long-description-editor" style="height: 500px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <!-- Textarea cachée pour Laravel -->
                    <textarea name="long_description" 
                              id="long_description" 
                              class="d-none @error('long_description') is-invalid @enderror">{{ old('long_description', isset($fiche) ? $fiche->long_description : '') }}</textarea>
                              
                    <div class="form-text">Ce contenu sera visible selon les paramètres de visibilité choisis</div>
                    @error('long_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SEO et métadonnées -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-search me-2"></i>SEO et Métadonnées
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="meta_title" class="form-label fw-semibold">Titre SEO</label>
                        <input type="text" 
                               name="meta_title" 
                               id="meta_title" 
                               value="{{ old('meta_title', isset($fiche) ? $fiche->meta_title : '') }}"
                               class="form-control @error('meta_title') is-invalid @enderror"
                               maxlength="60"
                               placeholder="Titre optimisé pour les moteurs de recherche">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="meta_keywords" class="form-label fw-semibold">Mots-clés</label>
                        <input type="text" 
                               name="meta_keywords" 
                               id="meta_keywords" 
                               value="{{ old('meta_keywords', isset($fiche) ? $fiche->meta_keywords : '') }}"
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
                                  maxlength="160"
                                  placeholder="Description qui apparaîtra dans les résultats de recherche...">{{ old('meta_description', isset($fiche) ? $fiche->meta_description : '') }}</textarea>
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
                    <select name="is_published" id="is_published" class="form-select @error('is_published') is-invalid @enderror">
                        <option value="0" {{ old('is_published', isset($fiche) ? $fiche->is_published : 0) == 0 ? 'selected' : '' }}>
                            <i class="fas fa-edit"></i> Brouillon
                        </option>
                        <option value="1" {{ old('is_published', isset($fiche) ? $fiche->is_published : 0) == 1 ? 'selected' : '' }}>
                            <i class="fas fa-check"></i> Publié
                        </option>
                    </select>
                    @error('is_published')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- VISIBILITÉ -->
                <div class="mb-3">
                    <label for="visibility" class="form-label fw-semibold">
                        <i class="fas fa-eye me-1 text-info"></i>Visibilité du contenu
                    </label>
                    <select name="visibility" id="visibility" class="form-select @error('visibility') is-invalid @enderror">
                        <option value="public" {{ old('visibility', isset($fiche) ? $fiche->visibility : 'public') === 'public' ? 'selected' : '' }}>
                            <i class="fas fa-globe"></i> Public - Accessible à tous
                        </option>
                        <option value="authenticated" {{ old('visibility', isset($fiche) ? $fiche->visibility : '') === 'authenticated' ? 'selected' : '' }}>
                            <i class="fas fa-lock"></i> Membres uniquement
                        </option>
                    </select>
                    
                    <!-- Aide contextuelle -->
                    <div class="form-text">
                        <div id="visibility-help" class="mt-2 p-3 rounded" style="background-color: #f8f9fa;">
                            <div id="public-help" style="display: none;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-globe text-success me-2 mt-1"></i>
                                    <div>
                                        <strong class="text-success">Accès public</strong>
                                        <div class="small text-muted mt-1">
                                            • Visible par tous les visiteurs<br>
                                            • Indexé par les moteurs de recherche<br>
                                            • Partageable sur les réseaux sociaux
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="authenticated-help" style="display: none;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-lock text-warning me-2 mt-1"></i>
                                    <div>
                                        <strong class="text-warning">Accès restreint</strong>
                                        <div class="small text-muted mt-1">
                                            • Titre et résumé visibles par tous<br>
                                            • Contenu complet réservé aux membres<br>
                                            • Incite à l'inscription
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @error('visibility')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="published_at" class="form-label fw-semibold">Date de publication</label>
                    <input type="datetime-local" 
                           name="published_at" 
                           id="published_at" 
                           value="{{ old('published_at', isset($fiche) ? $fiche->published_at?->format('Y-m-d\TH:i') : '') }}"
                           class="form-control @error('published_at') is-invalid @enderror">
                    <div class="form-text">Laisser vide pour publication immédiate</div>
                    @error('published_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                    <input type="number" 
                           name="sort_order" 
                           id="sort_order" 
                           value="{{ old('sort_order', isset($fiche) ? $fiche->sort_order : 0) }}"
                           class="form-control @error('sort_order') is-invalid @enderror"
                           placeholder="0">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" 
                           name="is_featured" 
                           id="is_featured" 
                           value="1"
                           {{ old('is_featured', isset($fiche) ? $fiche->is_featured : false) ? 'checked' : '' }}
                           class="form-check-input">
                    <label for="is_featured" class="form-check-label">
                        <i class="fas fa-star text-warning me-1"></i>
                        Fiche mise en vedette
                    </label>
                </div>
            </div>
        </div>

        <!-- Catégorie et Sous-Catégorie -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-warning text-white p-4">
        <h6 class="mb-0">
            <i class="fas fa-folder me-2"></i>Catégorie
        </h6>
    </div>
    <div class="card-body p-4">
        <!-- Catégorie principale -->
        <div class="mb-3">
            <label for="fiches_category_id" class="form-label fw-semibold">Catégorie *</label>
            <select name="fiches_category_id" 
                    id="fiches_category_id" 
                    class="form-select @error('fiches_category_id') is-invalid @enderror">
                <option value="">Sélectionner une catégorie</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                            {{ old('fiches_category_id', isset($fiche) ? $fiche->fiches_category_id : '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('fiches_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Sous-catégorie (dynamique) -->
        <div class="mb-3">
            <label for="fiches_sous_category_id" class="form-label fw-semibold">
                Sous-catégorie 
                <span class="badge bg-info-subtle text-info ms-1">Optionnel</span>
            </label>
            <select name="fiches_sous_category_id" 
                    id="fiches_sous_category_id" 
                    class="form-select @error('fiches_sous_category_id') is-invalid @enderror"
                    disabled>
                <option value="">Aucune sous-catégorie</option>
                @if(isset($fiche) && isset($sousCategories))
                    @foreach($sousCategories as $sousCategory)
                        <option value="{{ $sousCategory->id }}" 
                                {{ old('fiches_sous_category_id', $fiche->fiches_sous_category_id) == $sousCategory->id ? 'selected' : '' }}>
                            {{ $sousCategory->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <div class="form-text">Sélectionnez d'abord une catégorie</div>
            @error('fiches_sous_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Loader pour le chargement des sous-catégories -->
        <div id="sous-category-loader" class="d-none">
            <div class="d-flex align-items-center text-muted">
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <span>Chargement des sous-catégories...</span>
            </div>
        </div>
    </div>
</div>
        <!-- Image -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2"></i>Image principale
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">URL de l'image</label>
                    <div class="input-group">
                        <input type="text" 
                               name="image" 
                               id="image" 
                               value="{{ old('image', isset($fiche) ? $fiche->image : '') }}"
                               class="form-control @error('image') is-invalid @enderror"
                               placeholder="https://exemple.com/image.jpg ou /storage/media/image.jpg">
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

                @if(isset($fiche) && $fiche->image)
                    <div class="mt-3" id="currentImagePreview">
                        <small class="text-muted d-block mb-2">Aperçu actuel :</small>
                        <img src="{{ $fiche->image }}" 
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
                    <a href="{{ route('admin.fiches.index') }}" class="btn btn-outline-secondary">
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
<style>






.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

.tiny {
    font-size: 0.7rem;
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

    // ========================================
    // 4. AIDE CONTEXTUELLE VISIBILITÉ
    // ========================================
    const visibilitySelect = document.getElementById('visibility');
    const helpDivs = {
        'public': document.getElementById('public-help'),
        'authenticated': document.getElementById('authenticated-help')
    };

    function updateVisibilityHelp() {
        Object.values(helpDivs).forEach(div => {
            if (div) div.style.display = 'none';
        });
        
        const selectedValue = visibilitySelect.value;
        if (helpDivs[selectedValue]) {
            helpDivs[selectedValue].style.display = 'block';
        }
    }

    if (visibilitySelect) {
        visibilitySelect.addEventListener('change', updateVisibilityHelp);
        updateVisibilityHelp();
    }

    // ========================================
    // 5. APERÇU IMAGE
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
    // 6. INITIALISER L'IA APRÈS QUILL
    // ========================================
    setTimeout(function() {
        if (typeof window.initQuillAI === 'function') {
            window.initQuillAI();
        }
    }, 1500);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // CHARGEMENT DYNAMIQUE DES SOUS-CATÉGORIES
    // ========================================
    const categorySelect = document.getElementById('fiches_category_id');
    const sousCategorySelect = document.getElementById('fiches_sous_category_id');
    const sousCategoryLoader = document.getElementById('sous-category-loader');
    
    if (categorySelect && sousCategorySelect) {
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Réinitialiser le select des sous-catégories
            sousCategorySelect.innerHTML = '<option value="">Aucune sous-catégorie</option>';
            sousCategorySelect.disabled = true;
            
            if (!categoryId) {
                return;
            }
            
            // Afficher le loader
            if (sousCategoryLoader) {
                sousCategoryLoader.classList.remove('d-none');
            }
            
            // Appel AJAX pour récupérer les sous-catégories
            fetch(`{{ route('admin.fiches-sous-categories.api.by-category') }}?category_id=${categoryId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Masquer le loader
                if (sousCategoryLoader) {
                    sousCategoryLoader.classList.add('d-none');
                }
                
                if (data.length > 0) {
                    data.forEach(sousCategory => {
                        const option = document.createElement('option');
                        option.value = sousCategory.id;
                        option.textContent = sousCategory.name;
                        sousCategorySelect.appendChild(option);
                    });
                    sousCategorySelect.disabled = false;
                } else {
                    sousCategorySelect.innerHTML = '<option value="">Aucune sous-catégorie disponible</option>';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des sous-catégories:', error);
                if (sousCategoryLoader) {
                    sousCategoryLoader.classList.add('d-none');
                }
                sousCategorySelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
        });
        
        // Déclencher le chargement initial si une catégorie est déjà sélectionnée
        @if(isset($fiche) && $fiche->fiches_category_id)
            categorySelect.dispatchEvent(new Event('change'));
        @endif
    }
});
</script>
@endpush