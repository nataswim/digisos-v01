@extends('layouts.admin')

@section('title', 'Modifier une Catégorie')
@section('page-title', 'Modifier la catégorie')
@section('page-description', $pagesCategory->name)

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.pages-categories.update', $pagesCategory) }}">
        @method('PUT')
        @csrf
        
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">Informations de la catégorie</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nom *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $pagesCategory->name) }}"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">Slug</label>
                            <input type="text" 
                                   name="slug" 
                                   id="slug" 
                                   value="{{ old('slug', $pagesCategory->slug) }}"
                                   class="form-control">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <div id="quill-editor-description" style="height: 250px;"></div>
                            <textarea name="description" 
                                      id="description" 
                                      class="d-none">{{ old('description', $pagesCategory->description) }}</textarea>
                        </div>

                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-search me-2 text-primary"></i>SEO
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="meta_title" class="form-label">Titre SEO</label>
                                    <input type="text" 
                                           name="meta_title" 
                                           id="meta_title" 
                                           value="{{ old('meta_title', $pagesCategory->meta_title) }}"
                                           class="form-control">
                                </div>
                                
                                <div class="col-12">
                                    <label for="meta_description" class="form-label">Description SEO</label>
                                    <textarea name="meta_description" 
                                              id="meta_description" 
                                              rows="3"
                                              class="form-control">{{ old('meta_description', $pagesCategory->meta_description) }}</textarea>
                                </div>
                                
                                <div class="col-12">
                                    <label for="meta_keywords" class="form-label">Mots-clés</label>
                                    <input type="text" 
                                           name="meta_keywords" 
                                           id="meta_keywords" 
                                           value="{{ old('meta_keywords', $pagesCategory->meta_keywords) }}"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-toggle-on me-2 text-success"></i>Statut
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
                                       {{ old('is_active', $pagesCategory->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active</strong>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="form-label fw-semibold">Ordre</label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order" 
                                   value="{{ old('sort_order', $pagesCategory->sort_order) }}"
                                   class="form-control"
                                   min="0">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2"></i>Image
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="input-group mb-3">
                            <input type="text" 
                                   name="image" 
                                   id="categoryImage"
                                   value="{{ old('image', $pagesCategory->image) }}"
                                   class="form-control"
                                   placeholder="https://...">
                            <button type="button" 
                                    class="btn btn-outline-primary"
                                    onclick="openMediaSelector('categoryImage', 'categoryImagePreview')">
                                <i class="fas fa-images"></i> Médias
                            </button>
                        </div>
                        
                        @if($pagesCategory->image)
                            <div id="categoryImagePreviewContainer">
                                <img src="{{ $pagesCategory->image }}" 
                                     id="categoryImagePreview"
                                     alt="Aperçu" 
                                     class="img-fluid rounded"
                                     style="max-height: 150px;">
                            </div>
                        @else
                            <div class="d-none" id="categoryImagePreviewContainer">
                                <img id="categoryImagePreview"
                                     alt="Aperçu" 
                                     class="img-fluid rounded"
                                     style="max-height: 150px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.pages-categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Quill Editor pour description
    if (typeof initQuillEditor === 'function') {
        initQuillEditor('#quill-editor-description', 'description');
    }

    // Auto-génération du slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (!slugInput.dataset.manuallyEdited) {
                const slug = this.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                slugInput.value = slug;
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.manuallyEdited = 'true';
        });
    }

    // Aperçu d'image
    const imageInput = document.getElementById('categoryImage');
    const imagePreview = document.getElementById('categoryImagePreview');
    const imagePreviewContainer = document.getElementById('categoryImagePreviewContainer');

    if (imageInput) {
        imageInput.addEventListener('input', function() {
            if (this.value) {
                imagePreview.src = this.value;
                imagePreviewContainer.classList.remove('d-none');
            } else {
                imagePreviewContainer.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush
