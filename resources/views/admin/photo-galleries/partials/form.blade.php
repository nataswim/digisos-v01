{{-- Formulaire partagé pour créer/éditer une galerie --}}

<div class="row g-4">
    {{-- Colonne principale --}}
    <div class="col-lg-8">
        {{-- Informations de base --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Informations de base
                </h5>
            </div>
            <div class="card-body p-4">
                {{-- Titre --}}
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold required">
                        Titre de la galerie
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $gallery->title ?? '') }}"
                           required
                           autofocus>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Slug --}}
                <div class="mb-3">
                    <label for="slug" class="form-label fw-semibold">
                        Slug (URL)
                        <small class="text-muted">(Auto-généré si vide)</small>
                    </label>
                    <input type="text" 
                           name="slug" 
                           id="slug" 
                           class="form-control @error('slug') is-invalid @enderror"
                           value="{{ old('slug', $gallery->slug ?? '') }}"
                           placeholder="ex: ma-belle-galerie">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              class="form-control @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="Description de la galerie...">{{ old('description', $gallery->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ordre d'affichage --}}
                <div class="mb-0">
                    <label for="sort_order" class="form-label fw-semibold">
                        Ordre d'affichage
                        <small class="text-muted">(Plus le chiffre est petit, plus la galerie apparaît en premier)</small>
                    </label>
                    <input type="number" 
                           name="sort_order" 
                           id="sort_order" 
                           class="form-control @error('sort_order') is-invalid @enderror"
                           value="{{ old('sort_order', $gallery->sort_order ?? 0) }}"
                           min="0">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Photos de la galerie --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-images text-primary me-2"></i>
                        Photos de la galerie
                    </h5>
                    <button type="button" 
                            class="btn btn-sm btn-primary"
                            onclick="openGallerySelector()">
                        <i class="fas fa-plus me-1"></i>Ajouter des photos
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                {{-- Zone d'affichage des photos sélectionnées --}}
                <div id="selectedPhotosContainer" class="row g-3 mb-3">
                    <div class="col-12 text-center text-muted py-4" id="emptyPhotosMessage">
                        <i class="fas fa-images fa-3x opacity-50 mb-2"></i>
                        <p class="mb-0">Aucune photo sélectionnée</p>
                        <small>Cliquez sur "Ajouter des photos" pour commencer</small>
                    </div>
                </div>

                {{-- Instructions drag & drop --}}
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Astuce :</strong> Glissez-déposez les photos pour réorganiser leur ordre d'affichage.
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">
                    <i class="fas fa-search text-primary me-2"></i>
                    Référencement (SEO)
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="meta_title" class="form-label fw-semibold">
                        Meta titre
                    </label>
                    <input type="text" 
                           name="meta_title" 
                           id="meta_title" 
                           class="form-control @error('meta_title') is-invalid @enderror"
                           value="{{ old('meta_title', $gallery->meta_title ?? '') }}"
                           maxlength="255"
                           placeholder="Titre pour les moteurs de recherche">
                    @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="meta_description" class="form-label fw-semibold">
                        Meta description
                    </label>
                    <textarea name="meta_description" 
                              id="meta_description" 
                              class="form-control @error('meta_description') is-invalid @enderror"
                              rows="3"
                              placeholder="Description pour les moteurs de recherche">{{ old('meta_description', $gallery->meta_description ?? '') }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-0">
                    <label for="meta_keywords" class="form-label fw-semibold">
                        Mots-clés
                        <small class="text-muted">(séparés par des virgules)</small>
                    </label>
                    <input type="text" 
                           name="meta_keywords" 
                           id="meta_keywords" 
                           class="form-control @error('meta_keywords') is-invalid @enderror"
                           value="{{ old('meta_keywords', $gallery->meta_keywords ?? '') }}"
                           placeholder="photo, galerie, événement">
                    @error('meta_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Publication --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">
                    <i class="fas fa-cog text-primary me-2"></i>
                    Publication
                </h5>
            </div>
            <div class="card-body p-4">
                {{-- Statut --}}
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="is_published" 
                               id="is_published"
                               value="1"
                               {{ old('is_published', $gallery->is_published ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_published">
                            Publier la galerie
                        </label>
                    </div>
                    <small class="text-muted">
                        La galerie sera visible sur le site public
                    </small>
                </div>

                {{-- Mise en avant --}}
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="is_featured" 
                               id="is_featured"
                               value="1"
                               {{ old('is_featured', $gallery->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_featured">
                            Mettre en avant
                        </label>
                    </div>
                    <small class="text-muted">
                        Afficher dans les galeries en vedette
                    </small>
                </div>

                {{-- Visibilité --}}
                <div class="mb-3">
                    <label for="visibility" class="form-label fw-semibold required">
                        Visibilité
                    </label>
                    <select name="visibility" 
                            id="visibility" 
                            class="form-select @error('visibility') is-invalid @enderror"
                            required>
                        <option value="public" 
                                {{ old('visibility', $gallery->visibility ?? 'public') === 'public' ? 'selected' : '' }}>
                            <i class="fas fa-globe"></i> Public
                        </option>
                        <option value="authenticated" 
                                {{ old('visibility', $gallery->visibility ?? '') === 'authenticated' ? 'selected' : '' }}>
                            <i class="fas fa-lock"></i> Membres uniquement
                        </option>
                    </select>
                    @error('visibility')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Date de publication --}}
                <div class="mb-0">
                    <label for="published_at" class="form-label fw-semibold">
                        Date de publication
                    </label>
                    <input type="datetime-local" 
                           name="published_at" 
                           id="published_at" 
                           class="form-control @error('published_at') is-invalid @enderror"
                           value="{{ old('published_at', $gallery && $gallery->published_at ? $gallery->published_at->format('Y-m-d\TH:i') : '') }}">
                    @error('published_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        Laissez vide pour publier immédiatement
                    </small>
                </div>
            </div>
        </div>

        {{-- Image de couverture --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">
                    <i class="fas fa-image text-primary me-2"></i>
                    Image de couverture
                </h5>
            </div>
            <div class="card-body p-4">
                <div id="coverImagePreview" class="mb-3">
                    @if($gallery && $gallery->coverImage)
                        <img src="{{ $gallery->coverImage->url }}" 
                             alt="Couverture"
                             class="img-fluid rounded shadow-sm mb-2"
                             id="coverImageImg">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded mb-2"
                             style="height: 200px;"
                             id="coverImagePlaceholder">
                            <i class="fas fa-image fa-3x text-muted opacity-50"></i>
                        </div>
                    @endif
                </div>
                
                <input type="hidden" 
                       name="cover_image_id" 
                       id="cover_image_id"
                       value="{{ old('cover_image_id', $gallery->cover_image_id ?? '') }}">
                
                <button type="button" 
                        class="btn btn-outline-primary btn-sm w-100 mb-2"
                        onclick="openCoverImageSelector()">
                    <i class="fas fa-image me-1"></i>Choisir une image
                </button>
                
                @if($gallery && $gallery->coverImage)
                    <button type="button" 
                            class="btn btn-outline-danger btn-sm w-100"
                            onclick="removeCoverImage()">
                        <i class="fas fa-times me-1"></i>Retirer
                    </button>
                @endif
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <button type="submit" 
                        name="action" 
                        value="save"
                        class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-save me-2"></i>{{ $submitText }}
                </button>
                
                <button type="submit" 
                        name="action" 
                        value="save_and_continue"
                        class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-save me-2"></i>{{ $submitText }} et continuer
                </button>
                
                <a href="{{ route('admin.photo-galleries.index') }}" 
                   class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-génération du slug
document.getElementById('title')?.addEventListener('input', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.dataset.manual) {
        slugField.value = this.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
});

document.getElementById('slug')?.addEventListener('input', function() {
    this.dataset.manual = 'true';
});

// removeCoverImage reste nécessaire (bouton "Retirer")
function removeCoverImage() {
    document.getElementById('cover_image_id').value = '';
    document.getElementById('coverImagePreview').innerHTML = `
        <div class="bg-light d-flex align-items-center justify-content-center rounded mb-2"
             style="height:200px;" id="coverImagePlaceholder">
            <i class="fas fa-image fa-3x text-muted opacity-50"></i>
        </div>`;
}
// openGallerySelector() et openCoverImageSelector() → définis dans gallery-media-selector.js
</script>
@endpush