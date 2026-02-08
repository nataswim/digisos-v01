@extends('layouts.admin')

@section('title', 'Creer une Categorie')
@section('page-title', 'Nouvelle Categorie')
@section('page-description', 'Creation d\'une nouvelle categorie')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        
        <div class="row g-4">
            <!-- Contenu principal -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">Informations de la categorie</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nom de la categorie *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   placeholder="Ex: Developpement Web"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">URL personnalisee (Slug)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ url('/articles?category=') }}</span>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       value="{{ old('slug') }}"
                                       class="form-control"
                                       placeholder="developpement-web">
                            </div>
                            <div class="form-text">Laisser vide pour generation automatique</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="form-control"
                                      placeholder="Description detaillee de la categorie...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Section SEO -->
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-search me-2 text-primary"></i>Referencement (SEO)
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
                                    <label for="meta_keywords" class="form-label">Mots-cles</label>
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
                            <i class="fas fa-toggle-on me-2 text-success"></i>Statut et visibilite
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Statut</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                    ✅ Active
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    ⏸️ Inactive
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="order" class="form-label fw-semibold">Ordre d'affichage</label>
                            <input type="number" 
                                   name="order" 
                                   id="order" 
                                   value="{{ old('order') }}"
                                   class="form-control"
                                   placeholder="0"
                                   min="0">
                            <div class="form-text">Plus le nombre est petit, plus la categorie sera affichee en premier</div>
                        </div>
                    </div>
                </div>

                <!-- Organisation -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-layer-group me-2 text-white"></i>Organisation
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <label for="group_name" class="form-label fw-semibold">Groupe</label>
                        <input type="text" 
                               name="group_name" 
                               id="group_name" 
                               value="{{ old('group_name') }}"
                               class="form-control"
                               placeholder="Ex: Technologies">
                        <div class="form-text">Grouper les categories similaires ensemble</div>
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
                        <input type="text" 
                               name="image" 
                               value="{{ old('image') }}"
                               class="form-control"
                               placeholder="https://example.com/image.jpg">
                        <div class="form-text">URL de l'image d'illustration de la categorie</div>
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
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Creer la categorie
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection