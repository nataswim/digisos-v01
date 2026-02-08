@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white text-primary p-4">
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
                           value="{{ old('name', $category->name ?? '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: Developpement Web"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">URL personnalisee (Slug)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">{{ url('/categories') }}/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', $category->slug ?? '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="developpement-web">
                    </div>
                    <div class="form-text">Laisser vide pour generation automatique</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Description detaillee de la categorie...">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Section SEO -->
                <div class="border-top pt-4">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="fas fa-search me-2"></i>Referencement (SEO)
                    </h6>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="meta_title" class="form-label">Titre SEO</label>
                            <input type="text" 
                                   name="meta_title" 
                                   id="meta_title" 
                                   value="{{ old('meta_title', $category->meta_title ?? '') }}"
                                   class="form-control @error('meta_title') is-invalid @enderror"
                                   placeholder="Titre optimise pour les moteurs de recherche">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="meta_description" class="form-label">Description SEO</label>
                            <textarea name="meta_description" 
                                      id="meta_description" 
                                      rows="3"
                                      class="form-control @error('meta_description') is-invalid @enderror"
                                      placeholder="Description courte pour les resultats de recherche">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="meta_keywords" class="form-label">Mots-cles</label>
                            <input type="text" 
                                   name="meta_keywords" 
                                   id="meta_keywords" 
                                   value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}"
                                   class="form-control @error('meta_keywords') is-invalid @enderror"
                                   placeholder="mot1, mot2, mot3">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Statut et publication -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-toggle-on me-2"></i>Statut et visibilite
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Statut</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', $category->status ?? 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ old('status', $category->status ?? '') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="order" class="form-label fw-semibold">Ordre d'affichage</label>
                    <input type="number" 
                           name="order" 
                           id="order" 
                           value="{{ old('order', $category->order ?? 0) }}"
                           class="form-control @error('order') is-invalid @enderror"
                           placeholder="0"
                           min="0">
                    <div class="form-text">Plus le nombre est petit, plus la categorie sera affichee en premier</div>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Organisation -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white text-primary p-4">
                <h6 class="mb-0">
                    <i class="fas fa-layer-group me-2"></i>Organisation
                </h6>
            </div>
            <div class="card-body p-4">
                <label for="group_name" class="form-label fw-semibold">Groupe</label>
                <input type="text" 
                       name="group_name" 
                       id="group_name" 
                       value="{{ old('group_name', $category->group_name ?? '') }}"
                       class="form-control @error('group_name') is-invalid @enderror"
                       placeholder="Ex: Technologies">
                <div class="form-text">Grouper les categories similaires ensemble</div>
                @error('group_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>








        <!-- Image -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white p-4">
        <h6 class="mb-0">
            <i class="fas fa-image me-2"></i>Image d'illustration
        </h6>
    </div>
    <div class="card-body p-4">
        <div class="input-group">
            <input type="text" 
       name="image" 
       id="categoryImage"
       value="{{ old('image', $category->image ?? '') }}"
       class="form-control @error('image') is-invalid @enderror"
       placeholder="https://example.com/image.jpg ou /storage/media/image.jpg">
            <button type="button" 
                    class="btn btn-outline-primary"
                    onclick="openMediaSelector('categoryImage', 'categoryImagePreview')">
                <i class="fas fa-images"></i>
            </button>
        </div>
        <div class="form-text">Selectionnez depuis la mediatheque ou saisissez une URL</div>
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        
        @if(isset($category) && $category->image)
            <div class="mt-3">
                <img src="{{ $category->image }}" 
                     id="categoryImagePreview"
                     alt="Aperçu" 
                     class="img-fluid rounded" 
                     style="max-height: 150px;">
            </div>
        @else
            <div class="mt-3 d-none">
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







<!-- Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>