@extends('layouts.editor')

@section('title', 'Créer une fiche')

@section('content')
<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-success  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white ">  <i class="fas fa-file-medical text-white me-2"></i>
                Créer une nouvelle fiche </h5>
            </div>
        </div>
</section>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('editor.fiches.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <form method="POST" action="{{ route('editor.fiches.store') }}">
        @csrf

        <div class="row g-4">
            <!-- Contenu principal -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white p-4">
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
                                   value="{{ old('title') }}"
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
                                       value="{{ old('slug') }}"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       placeholder="slug-automatique">
                            </div>
                            <div class="form-text">Laisser vide pour génération automatique</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description courte -->
                        <div class="mb-4">
                            <label for="short_description" class="form-label fw-semibold">
                                Description courte / Résumé *
                                <span class="badge bg-info-subtle text-info ms-2">Toujours visible</span>
                            </label>
                            
                            <div id="short-description-editor" style="height: 400px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                            
                            <textarea name="short_description" 
                                      id="short_description" 
                                      class="d-none @error('short_description') is-invalid @enderror"
                                      required>{{ old('short_description') }}</textarea>
                                      
                            @error('short_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description longue -->
                        <div class="mb-4">
                            <label for="long_description" class="form-label fw-semibold">
                                Description complète
                                <span class="badge bg-warning-subtle text-warning ms-2">Selon visibilité</span>
                            </label>
                            
                            <div id="long-description-editor" style="height: 500px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                            
                            <textarea name="long_description" 
                                      id="long_description" 
                                      class="d-none @error('long_description') is-invalid @enderror">{{ old('long_description') }}</textarea>
                                      
                            @error('long_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white text-primary p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-search me-2"></i>SEO et Métadonnées
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="meta_title" class="form-label fw-semibold">Titre SEO</label>
                                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" class="form-control" maxlength="60">
                            </div>
                            <div class="col-md-6">
                                <label for="meta_keywords" class="form-label fw-semibold">Mots-clés</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}" class="form-control">
                            </div>
                            <div class="col-12">
                                <label for="meta_description" class="form-label fw-semibold">Description SEO</label>
                                <textarea name="meta_description" id="meta_description" rows="3" class="form-control" maxlength="160">{{ old('meta_description') }}</textarea>
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
                                <option value="0" selected>Brouillon</option>
                                <option value="1">Publié</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="visibility" class="form-label fw-semibold">Visibilité</label>
                            <select name="visibility" id="visibility" class="form-select">
                                <option value="public" selected>Public</option>
                                <option value="authenticated">Membres uniquement</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="published_at" class="form-label fw-semibold">Date de publication</label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label fw-semibold">Ordre</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="form-control">
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" class="form-check-input">
                            <label for="is_featured" class="form-check-label">
                                <i class="fas fa-star text-warning me-1"></i>Mise en vedette
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Catégorie -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white text-primary p-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-folder me-2"></i>Catégorie
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="fiches_category_id" class="form-label fw-semibold">Catégorie *</label>
                            <select name="fiches_category_id" id="fiches_category_id" class="form-select" required>
                                <option value="">Sélectionner</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="fiches_sous_category_id" class="form-label fw-semibold mb-0">Sous-catégorie</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newSousCategoryModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <select name="fiches_sous_category_id" id="fiches_sous_category_id" class="form-select" disabled>
                                <option value="">Aucune</option>
                            </select>
                            <div class="form-text">Sélectionnez d'abord une catégorie</div>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white text-primary p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2"></i>Image
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" name="image" id="image" value="{{ old('image') }}" class="form-control" placeholder="URL de l'image">
                                <button type="button" class="btn btn-outline-primary" onclick="openMediaSelector('image', 'imagePreview')">
                                    <i class="fas fa-images"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-3 d-none" id="currentImagePreview">
                            <img id="imagePreview" class="img-fluid rounded shadow-sm" style="max-height: 150px;" alt="Aperçu">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button type="submit" name="action" value="save" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                    <button type="submit" name="action" value="save_and_continue" class="btn btn-outline-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer et continuer
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Nouvelle Catégorie -->
<div class="modal fade" id="newCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_category_name" class="form-label">Nom *</label>
                    <input type="text" id="new_category_name" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="createCategory()">Créer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Sous-Catégorie -->
<div class="modal fade" id="newSousCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Sous-Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_sous_category_name" class="form-label">Nom *</label>
                    <input type="text" id="new_sous_category_name" class="form-control" required>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>La sous-catégorie sera rattachée à la catégorie sélectionnée
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="createSousCategory()">Créer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let quillShortDescription = initQuillEditor('#short-description-editor', 'short_description');
    let quillLongDescription = initQuillEditor('#long-description-editor', 'long_description');

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('short_description').value = quillShortDescription.root.innerHTML;
        document.getElementById('long_description').value = quillLongDescription.root.innerHTML;
    });

    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = '';
    });

    document.getElementById('image').addEventListener('input', function() {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('currentImagePreview');
        if (this.value.trim()) {
            preview.src = this.value;
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    });

    // Chargement sous-catégories
    const categorySelect = document.getElementById('fiches_category_id');
    const sousCategorySelect = document.getElementById('fiches_sous_category_id');
    
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        sousCategorySelect.innerHTML = '<option value="">Aucune</option>';
        sousCategorySelect.disabled = true;
        
        if (!categoryId) return;
        
        fetch(`{{ route('admin.fiches-sous-categories.api.by-category') }}?category_id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(sc => {
                    sousCategorySelect.innerHTML += `<option value="${sc.id}">${sc.name}</option>`;
                });
                sousCategorySelect.disabled = false;
            });
    });

    setTimeout(() => {
        if (typeof window.initQuillAI === 'function') window.initQuillAI();
    }, 1500);
});

async function createCategory() {
    const name = document.getElementById('new_category_name').value;
    if (!name) return alert('Le nom est requis');
    
    try {
        const response = await fetch('{{ route("admin.fiches-categories.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name })
        });
        
        const data = await response.json();
        if (data.success) {
            const select = document.getElementById('fiches_category_id');
            const option = new Option(data.category.name, data.category.id, true, true);
            select.add(option);
            bootstrap.Modal.getInstance(document.getElementById('newCategoryModal')).hide();
            document.getElementById('new_category_name').value = '';
        }
    } catch (error) {
        alert('Erreur lors de la création');
    }
}

async function createSousCategory() {
    const name = document.getElementById('new_sous_category_name').value;
    const categoryId = document.getElementById('fiches_category_id').value;
    
    if (!name) return alert('Le nom est requis');
    if (!categoryId) return alert('Sélectionnez d\'abord une catégorie');
    
    try {
        const response = await fetch('{{ route("admin.fiches-sous-categories.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, fiches_category_id: categoryId })
        });
        
        const data = await response.json();
        if (data.success) {
            const select = document.getElementById('fiches_sous_category_id');
            const option = new Option(data.sous_category.name, data.sous_category.id, true, true);
            select.add(option);
            select.disabled = false;
            bootstrap.Modal.getInstance(document.getElementById('newSousCategoryModal')).hide();
            document.getElementById('new_sous_category_name').value = '';
        }
    } catch (error) {
        alert('Erreur lors de la création');
    }
}
</script>
@endpush