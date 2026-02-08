@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-folder me-2"></i>Informations de la categorie
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Nom -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom de la categorie *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', isset($category) ? $category->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: eBooks Fitness, Videos Nutrition..."
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug URL</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">{{ url('/ebook') }}/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($category) ? $category->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="slug-automatique">
                    </div>
                    <div class="form-text">Laisser vide pour generation automatique A partir du nom</div>
                    @error('slug')
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
                              maxlength="500"
                              placeholder="Description affichee dans les listes...">{{ old('short_description', isset($category) ? $category->short_description : '') }}</textarea>
                    <div class="form-text">Maximum 500 caracteres</div>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description complete -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description complete</label>
                    <textarea name="description" 
                              id="description" 
                              rows="6"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Description detaillee de la categorie...">{{ old('description', isset($category) ? $category->description : '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                    <label for="status" class="form-label fw-semibold">Statut</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', isset($category) ? $category->status : 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ old('status', isset($category) ? $category->status : '') === 'inactive' ? 'selected' : '' }}>
                            Inactive
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
                           value="{{ old('order', isset($category) ? $category->order : 0) }}"
                           class="form-control @error('order') is-invalid @enderror"
                           placeholder="0">
                    <div class="form-text">Plus le nombre est petit, plus la categorie apparaît en premier</div>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Icône -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white text-primary p-4">
                <h6 class="mb-0">
                    <i class="fas fa-icons me-2"></i>Icône
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="icon" class="form-label fw-semibold">Classe d'icône</label>
                    <input type="text" 
                           name="icon" 
                           id="icon" 
                           value="{{ old('icon', isset($category) ? $category->icon : '') }}"
                           class="form-control @error('icon') is-invalid @enderror"
                           placeholder="Ex: fas fa-book, fas fa-video">
                    <div class="form-text">Utilisez les classes FontAwesome (ex: fas fa-book)</div>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Aperçu de l'icône -->
                <div id="icon-preview" class="text-center p-3 bg-light rounded d-none">
                    <i id="preview-icon" class="fa-3x text-primary"></i>
                    <div class="mt-2">
                        <small class="text-muted">Aperçu de l'icône</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.download-categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')

@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generation du slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
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

    // Aperçu de l'icône
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const previewIcon = document.getElementById('preview-icon');
    
    iconInput.addEventListener('input', function() {
        const iconClass = this.value.trim();
        if (iconClass) {
            previewIcon.className = iconClass + ' fa-3x text-primary';
            iconPreview.classList.remove('d-none');
        } else {
            iconPreview.classList.add('d-none');
        }
    });

    // Initialiser l'aperçu si une icône existe dejÃ
    if (iconInput.value) {
        iconInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endpush