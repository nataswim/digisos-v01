@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">Informations de la page</h5>
            </div>
            <div class="card-body p-4">
                <!-- Titre -->
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Titre de la page *</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', isset($page) ? $page->title : '') }}"
                           class="form-control form-control-lg @error('title') is-invalid @enderror"
                           placeholder="Ex: À propos de nous"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">URL personnalisée (Slug)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">{{ url('/pages') }}/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($page) ? $page->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="a-propos">
                    </div>
                    <div class="form-text">Laisser vide pour génération automatique</div>
                    @error('slug')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description courte -->
                <div class="mb-4">
                    <label for="short_description" class="form-label fw-semibold">Description courte *</label>
                    <textarea name="short_description" 
                              id="short_description" 
                              rows="3"
                              class="form-control @error('short_description') is-invalid @enderror"
                              placeholder="Résumé court de la page..."
                              required>{{ old('short_description', isset($page) ? $page->short_description : '') }}</textarea>
                    <div class="form-text">Résumé affiché dans les listes et le SEO</div>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contenu long -->
                <div class="mb-4">
                    <label for="long_description" class="form-label fw-semibold">Contenu complet</label>
                    <div id="quill-editor-long-description" style="height: 400px;"></div>
                    <textarea name="long_description" 
                              id="long_description" 
                              class="d-none">{{ old('long_description', isset($page) ? $page->long_description : '') }}</textarea>
                    @error('long_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Section SEO -->
                <div class="border-top pt-4 mt-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-search me-2 text-primary"></i>Référencement (SEO)
                    </h6>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="meta_title" class="form-label">Titre SEO</label>
                            <input type="text" 
                                   name="meta_title" 
                                   id="meta_title" 
                                   value="{{ old('meta_title', isset($page) ? $page->meta_title : '') }}"
                                   class="form-control"
                                   maxlength="255">
                        </div>
                        
                        <div class="col-12">
                            <label for="meta_description" class="form-label">Description SEO</label>
                            <textarea name="meta_description" 
                                      id="meta_description" 
                                      rows="3"
                                      class="form-control"
                                      maxlength="500">{{ old('meta_description', isset($page) ? $page->meta_description : '') }}</textarea>
                        </div>
                        
                        <div class="col-12">
                            <label for="meta_keywords" class="form-label">Mots-clés</label>
                            <input type="text" 
                                   name="meta_keywords" 
                                   id="meta_keywords" 
                                   value="{{ old('meta_keywords', isset($page) ? $page->meta_keywords : '') }}"
                                   class="form-control"
                                   placeholder="mot1, mot2, mot3">
                        </div>

                        <div class="col-12">
                            <label for="meta_og_image" class="form-label">Image Open Graph (URL)</label>
                            <input type="text" 
                                   name="meta_og_image" 
                                   id="meta_og_image" 
                                   value="{{ old('meta_og_image', isset($page) ? $page->meta_og_image : '') }}"
                                   class="form-control"
                                   placeholder="https://...">
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
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-calendar me-2 text-success"></i>Publication
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="is_published" 
                               id="is_published" 
                               value="1"
                               {{ old('is_published', isset($page) ? $page->is_published : false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            <strong>Page publiée</strong>
                            <div class="text-muted small">Visible sur le site public</div>
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="published_at" class="form-label fw-semibold">Date de publication</label>
                    <input type="datetime-local" 
                           name="published_at" 
                           id="published_at" 
                           value="{{ old('published_at', isset($page) && $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}"
                           class="form-control">
                    <div class="form-text">Laisser vide pour publication immédiate</div>
                </div>

                <div>
                    <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                    <input type="number" 
                           name="sort_order" 
                           id="sort_order" 
                           value="{{ old('sort_order', isset($page) ? $page->sort_order : 0) }}"
                           class="form-control"
                           min="0">
                    <div class="form-text">Plus petit = affiché en premier</div>
                </div>
            </div>
        </div>

        <!-- Catégorie -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-folder me-2 text-primary"></i>Catégorie
                </h6>
            </div>
            <div class="card-body p-4">
                <select name="pages_category_id" 
                        id="pages_category_id" 
                        class="form-select @error('pages_category_id') is-invalid @enderror">
                    <option value="">Sans catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ old('pages_category_id', isset($page) ? $page->pages_category_id : '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('pages_category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Visibilité -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-eye me-2 text-warning"></i>Visibilité
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="radio" 
                               name="visibility" 
                               id="visibility_public" 
                               value="public"
                               {{ old('visibility', isset($page) ? $page->visibility : 'public') === 'public' ? 'checked' : '' }}>
                        <label class="form-check-label" for="visibility_public">
                            <strong>Public</strong>
                            <div class="text-muted small">Accessible à tous</div>
                        </label>
                    </div>
                </div>
                
                <div>
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="radio" 
                               name="visibility" 
                               id="visibility_authenticated" 
                               value="authenticated"
                               {{ old('visibility', isset($page) ? $page->visibility : 'public') === 'authenticated' ? 'checked' : '' }}>
                        <label class="form-check-label" for="visibility_authenticated">
                            <strong>Authentifié</strong>
                            <div class="text-muted small">Membres connectés uniquement</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2 text-info"></i>Image
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="input-group mb-3">
                    <input type="text" 
                           name="image" 
                           id="pageImage"
                           value="{{ old('image', isset($page) ? $page->image : '') }}"
                           class="form-control"
                           placeholder="https://...">
                    <button type="button" 
                            class="btn btn-outline-primary"
                            onclick="openMediaSelector('pageImage', 'pageImagePreview')">
                        <i class="fas fa-images"></i>
                    </button>
                </div>
                
                @if(isset($page) && $page->image)
                    <div id="pageImagePreviewContainer">
                        <img src="{{ $page->image }}" 
                             id="pageImagePreview"
                             alt="Aperçu" 
                             class="img-fluid rounded" 
                             style="max-height: 200px;">
                    </div>
                @else
                    <div class="d-none" id="pageImagePreviewContainer">
                        <img id="pageImagePreview"
                             alt="Aperçu" 
                             class="img-fluid rounded" 
                             style="max-height: 200px;">
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
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel }}
                        </button>
                        <button type="submit" name="action" value="save_and_continue" class="btn btn-outline-primary">
                            <i class="fas fa-save me-2"></i>Sauvegarder et continuer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-génération du slug
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = '';
    });

    // Aperçu image
    const imageInput = document.getElementById('pageImage');
    const imagePreview = document.getElementById('pageImagePreview');
    const previewContainer = document.getElementById('pageImagePreviewContainer');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('input', function() {
            if (this.value.trim()) {
                imagePreview.src = this.value;
                previewContainer.classList.remove('d-none');
            } else {
                previewContainer.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush
