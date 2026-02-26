@extends('layouts.editor')

@section('title', 'Éditer l\'article')

@section('content')

<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-success  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white ">  <i class="fas fa-file-medical text-white me-2"></i>
                Éditer l'article -  {{ $post->name }} </h5>
            </div>
        </div>
</section>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-2">
            @if($post->status === 'published')
                <a href="{{ route('posts.public.show', $post->slug) }}" class="btn btn-outline-primary" target="_blank">
                    <i class="fas fa-eye me-2"></i>Voir
                </a>
            @endif
            <a href="{{ route('editor.posts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('editor.posts.update', $post) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Contenu principal -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Contenu de l'article
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Titre -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Titre de l'article *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $post->name) }}"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">Slug URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ url('/posts') }}/</span>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       value="{{ old('slug', $post->slug) }}"
                                       class="form-control @error('slug') is-invalid @enderror">
                            </div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Introduction -->
                        <div class="mb-4">
                            <label for="intro" class="form-label fw-semibold">
                                Introduction / Résumé
                                <span class="badge bg-info-subtle text-info ms-2">Toujours visible</span>
                            </label>
                            
                            <div id="intro-editor" style="height: 400px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                            
                            <textarea name="intro" id="intro" class="d-none @error('intro') is-invalid @enderror">{{ old('intro', $post->intro) }}</textarea>
                            @error('intro')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contenu principal -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                Contenu principal *
                                <span class="badge bg-warning-subtle text-warning ms-2">Selon visibilité</span>
                            </label>
                            
                            <div id="content-editor" style="height: 500px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                            
                            <textarea name="content" id="content" class="d-none @error('content') is-invalid @enderror" required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <label for="type" class="form-label fw-semibold">Type d'article</label>
                            <select name="type" id="type" class="form-select">
                                <option value="article" {{ old('type', $post->type) === 'article' ? 'selected' : '' }}>Article standard</option>
                                <option value="tutorial" {{ old('type', $post->type) === 'tutorial' ? 'selected' : '' }}>Tutoriel</option>
                                <option value="news" {{ old('type', $post->type) === 'news' ? 'selected' : '' }}>Actualité</option>
                                <option value="review" {{ old('type', $post->type) === 'review' ? 'selected' : '' }}>Test/Avis</option>
                            </select>
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
                                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $post->meta_title) }}" class="form-control" maxlength="60">
                            </div>
                            <div class="col-md-6">
                                <label for="meta_keywords" class="form-label fw-semibold">Mots-clés</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}" class="form-control">
                            </div>
                            <div class="col-12">
                                <label for="meta_description" class="form-label fw-semibold">Description SEO</label>
                                <textarea name="meta_description" id="meta_description" rows="3" class="form-control" maxlength="160">{{ old('meta_description', $post->meta_description) }}</textarea>
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
                            <label for="status" class="form-label fw-semibold">Statut</label>
                            <select name="status" id="status" class="form-select">
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Publié</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="visibility" class="form-label fw-semibold">Visibilité</label>
                            <select name="visibility" id="visibility" class="form-select">
                                <option value="public" {{ old('visibility', $post->visibility) === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="authenticated" {{ old('visibility', $post->visibility) === 'authenticated' ? 'selected' : '' }}>Membres uniquement</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="published_at" class="form-label fw-semibold">Date de publication</label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label fw-semibold">Ordre</label>
                            <input type="number" name="order" id="order" value="{{ old('order', $post->order) }}" class="form-control">
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }} class="form-check-input">
                            <label for="is_featured" class="form-check-label">
                                <i class="fas fa-star text-warning me-1"></i>Mis en avant
                            </label>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($post->hits ?? 0) }} vues
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Catégorie et Tags -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white text-primary p-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-folder me-2"></i>Catégorisation
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">Catégorie</label>
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">Aucune</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="tags" class="form-label fw-semibold mb-0">Tags</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newTagModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <select name="tags[]" id="tags" class="form-select" multiple size="5">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white text-primary p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2"></i>Image à la une
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($post->image)
                            <img src="{{ $post->image }}" alt="{{ $post->name }}" class="img-fluid mb-3 rounded">
                        @endif
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" name="image" id="image" value="{{ old('image', $post->image) }}" class="form-control">
                                <button type="button" class="btn btn-outline-primary" onclick="openMediaSelector('image', 'imagePreview')">
                                    <i class="fas fa-images"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-3 {{ $post->image ? '' : 'd-none' }}" id="currentImagePreview">
                            <img id="imagePreview" src="{{ $post->image }}" class="img-fluid rounded shadow-sm" style="max-height: 150px;" alt="Aperçu">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button type="submit" name="action" value="save" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                    <button type="submit" name="action" value="save_and_continue" class="btn btn-outline-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer et continuer
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modals (mêmes que create) -->
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
                <div class="mb-3">
                    <label for="new_category_description" class="form-label">Description</label>
                    <textarea id="new_category_description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="createCategory()">Créer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_tag_name" class="form-label">Nom *</label>
                    <input type="text" id="new_tag_name" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="createTag()">Créer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let quillIntro = initQuillEditor('#intro-editor', 'intro');
    let quillContent = initQuillEditor('#content-editor', 'content');

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('intro').value = quillIntro.root.innerHTML;
        document.getElementById('content').value = quillContent.root.innerHTML;
    });

    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
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

    setTimeout(() => {
        if (typeof window.initQuillAI === 'function') window.initQuillAI();
    }, 1500);
});

async function createCategory() {
    const name = document.getElementById('new_category_name').value;
    const description = document.getElementById('new_category_description').value;
    
    if (!name) return alert('Le nom est requis');
    
    try {
        const response = await fetch('{{ route("admin.categories.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, description, status: 'active' })
        });
        
        const data = await response.json();
        if (data.success) {
            const select = document.getElementById('category_id');
            const option = new Option(data.category.name, data.category.id, true, true);
            select.add(option);
            bootstrap.Modal.getInstance(document.getElementById('newCategoryModal')).hide();
            document.getElementById('new_category_name').value = '';
            document.getElementById('new_category_description').value = '';
        }
    } catch (error) {
        alert('Erreur lors de la création');
    }
}

async function createTag() {
    const name = document.getElementById('new_tag_name').value;
    
    if (!name) return alert('Le nom est requis');
    
    try {
        const response = await fetch('{{ route("admin.tags.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, status: 'active' })
        });
        
        const data = await response.json();
        if (data.success) {
            const select = document.getElementById('tags');
            const option = new Option(data.tag.name, data.tag.id, true, true);
            select.add(option);
            bootstrap.Modal.getInstance(document.getElementById('newTagModal')).hide();
            document.getElementById('new_tag_name').value = '';
        }
    } catch (error) {
        alert('Erreur lors de la création');
    }
}
</script>
@endpush