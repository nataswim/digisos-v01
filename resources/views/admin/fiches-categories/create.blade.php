@extends('layouts.admin')

@section('title', 'Créer une Catégorie de Fiche')
@section('page-title', 'Nouvelle Catégorie')
@section('page-description', 'Création d\'une nouvelle catégorie de fiche')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.fiches-categories.store') }}">
        @csrf
        
        <div class="row g-4">
            <!-- Contenu principal -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">Informations de la catégorie</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nom de la catégorie *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   placeholder="Ex: Exercices de natation"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">URL personnalisée (Slug)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ url('/fiches') }}/</span>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       value="{{ old('slug') }}"
                                       class="form-control"
                                       placeholder="exercices-natation">
                            </div>
                            <div class="form-text">Laisser vide pour génération automatique</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="form-control"
                                      placeholder="Description détaillée de la catégorie...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Section SEO -->
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-search me-2 text-primary"></i>Référencement (SEO)
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="meta_title" class="form-label">Titre SEO</label>
                                    <input type="text" 
                                           name="meta_title" 
                                           id="meta_title" 
                                           value="{{ old('meta_title') }}"
                                           class="form-control">
                                </div>
                                
                                <div class="col-12">
                                    <label for="meta_description" class="form-label">Description SEO</label>
                                    <textarea name="meta_description" 
                                              id="meta_description" 
                                              rows="3"
                                              class="form-control">{{ old('meta_description') }}</textarea>
                                </div>
                                
                                <div class="col-12">
                                    <label for="meta_keywords" class="form-label">Mots-clés</label>
                                    <input type="text" 
                                           name="meta_keywords" 
                                           id="meta_keywords" 
                                           value="{{ old('meta_keywords') }}"
                                           class="form-control"
                                           placeholder="mot1, mot2, mot3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statut -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-toggle-on me-2 text-success"></i>Statut et visibilité
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Catégorie active</strong>
                                    <div class="text-muted small">Visible sur le site public</div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order" 
                                   value="{{ old('sort_order', 0) }}"
                                   class="form-control"
                                   placeholder="0"
                                   min="0">
                            <div class="form-text">Plus le nombre est petit, plus la catégorie sera affichée en premier</div>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2 text-warning"></i>Image d'illustration
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="input-group">
                            <input type="text" 
                                   name="image" 
                                   id="categoryImage"
                                   value="{{ old('image') }}"
                                   class="form-control"
                                   placeholder="https://example.com/image.jpg">
                            <button type="button" 
                                    class="btn btn-outline-primary"
                                    onclick="openMediaSelector('categoryImage', 'categoryImagePreview')">
                                <i class="fas fa-images"></i>
                            </button>
                        </div>
                        <div class="form-text">URL de l'image d'illustration</div>
                        
                        <div class="mt-3 d-none" id="categoryImagePreviewContainer">
                            <img id="categoryImagePreview"
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
                            <a href="{{ route('admin.fiches-categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer la catégorie
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/media-selector.js').'?v='.filemtime(public_path('js/media-selector.js')) }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-génération du slug
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

    // Aperçu image
    const imageInput = document.getElementById('categoryImage');
    const imagePreview = document.getElementById('categoryImagePreview');
    const previewContainer = document.getElementById('categoryImagePreviewContainer');
    
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