@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white text-primary p-4">
                <h5 class="mb-0">
                    <i class="fas fa-tag me-2"></i>Informations du tag
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Nom du tag -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom du tag *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', isset($tag) ? $tag->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: Laravel, PHP, JavaScript"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">URL personnalisee (Slug)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">{{ url('/tags') }}/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($tag) ? $tag->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="laravel-php">
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
                              placeholder="Description du tag et de son utilisation...">{{ old('description', isset($tag) ? $tag->description : '') }}</textarea>
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
                                   value="{{ old('meta_title', isset($tag) ? $tag->meta_title : '') }}"
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
                                      placeholder="Description courte pour les resultats de recherche">{{ old('meta_description', isset($tag) ? $tag->meta_description : '') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="meta_keywords" class="form-label">Mots-cles</label>
                            <input type="text" 
                                   name="meta_keywords" 
                                   id="meta_keywords" 
                                   value="{{ old('meta_keywords', isset($tag) ? $tag->meta_keywords : '') }}"
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
        <!-- Statut et visibilite -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-toggle-on me-2"></i>Statut et visibilite
                </h6>
            </div>
            <div class="card-body p-4">
                <label for="status" class="form-label fw-semibold">Statut</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="active" {{ old('status', isset($tag) ? $tag->status : 'active') === 'active' ? 'selected' : '' }}>
                        Actif
                    </option>
                    <option value="inactive" {{ old('status', isset($tag) ? $tag->status : '') === 'inactive' ? 'selected' : '' }}>
                        Inactif
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="form-text">
                    <small class="text-muted">
                        <i class="fas fa-water me-1"></i>
                        Les tags inactifs n'apparaissent pas dans les selections
                    </small>
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
                       value="{{ old('group_name', isset($tag) ? $tag->group_name : '') }}"
                       class="form-control @error('group_name') is-invalid @enderror"
                       placeholder="Ex: Technologies, Categories">
                <div class="form-text">Grouper les tags similaires ensemble</div>
                @error('group_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Suggestions de groupes existants -->
                @php
                    $existingGroups = \App\Models\Tag::whereNotNull('group_name')
                        ->distinct()
                        ->pluck('group_name')
                        ->take(5);
                @endphp
                
                @if($existingGroups->count() > 0)
                    <div class="mt-2">
                        <small class="text-muted d-block mb-1">Groupes existants :</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($existingGroups as $group)
                                <button type="button" 
                                        class="badge bg-light text-dark border-0" 
                                        onclick="document.getElementById('group_name').value='{{ $group }}'">
                                    {{ $group }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>









<!-- Image d'illustration -->
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
       id="tagImage"
       value="{{ old('image', isset($tag) ? $tag->image : '') }}"
       class="form-control @error('image') is-invalid @enderror"
       placeholder="https://example.com/tag-image.jpg ou /storage/media/image.jpg">
            <button type="button" 
                    class="btn btn-outline-primary"
                    onclick="openMediaSelector('tagImage', 'tagImagePreview')">
                <i class="fas fa-images"></i>
            </button>
        </div>
        <div class="form-text">Selectionnez depuis la mediatheque ou saisissez une URL</div>
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        
        @if(isset($tag) && $tag->image)
            <div class="mt-3 text-center">
                <img src="{{ $tag->image }}" 
                     id="tagImagePreview"
                     alt="Aperçu" 
                     class="img-fluid rounded" 
                     style="max-height: 120px;">
                <div class="small text-muted mt-1">Image actuelle</div>
            </div>
        @else
            <div class="mt-3 text-center d-none">
                <img id="tagImagePreview"
                     alt="Aperçu" 
                     class="img-fluid rounded" 
                     style="max-height: 120px;">
                <div class="small text-muted mt-1">Aperçu</div>
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
                    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>