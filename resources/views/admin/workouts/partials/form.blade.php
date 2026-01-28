@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Contenu du workout
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Titre -->
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Titre du workout *</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', isset($workout) ? $workout->title : '') }}"
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
                        <span class="input-group-text bg-light">{{ url('/workouts/{section}/{categorie}') }}/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($workout) ? $workout->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="slug-automatique">
                    </div>
                    <div class="form-text">Laisser vide pour génération automatique à partir du titre</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description courte avec Quill -->
                <div class="mb-4">
                    <label for="short_description" class="form-label fw-semibold">
                        Description courte / Résumé *
                    </label>
                    
                    <!-- Conteneur pour l'éditeur Quill -->
                    <div id="short-description-editor" style="height: 400px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <!-- Textarea cachée pour Laravel -->
                    <textarea name="short_description" 
                              id="short_description" 
                              class="d-none @error('short_description') is-invalid @enderror"
                              required>{{ old('short_description', isset($workout) ? $workout->short_description : '') }}</textarea>
                              
                    @error('short_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description longue avec Quill -->
                <div class="mb-4">
                    <label for="long_description" class="form-label fw-semibold">
                        Description complète
                    </label>
                    
                    <!-- Conteneur pour l'éditeur Quill -->
                    <div id="long-description-editor" style="height: 500px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <!-- Textarea cachée pour Laravel -->
                    <textarea name="long_description" 
                              id="long_description" 
                              class="d-none @error('long_description') is-invalid @enderror">{{ old('long_description', isset($workout) ? $workout->long_description : '') }}</textarea>
                              
                    @error('long_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total en mètres -->
                <div class="mb-4">
                    <label for="total" class="form-label fw-semibold">Distance totale (en mètres) *</label>
                    <div class="input-group">
                        <input type="number" 
                               name="total" 
                               id="total" 
                               value="{{ old('total', isset($workout) ? $workout->total : 0) }}"
                               class="form-control @error('total') is-invalid @enderror"
                               placeholder="2000"
                               min="0"
                               required>
                        <span class="input-group-text">m</span>
                    </div>
                    <div class="form-text">Distance totale du workout en mètres (ex: 2000 pour 2km)</div>
                    @error('total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Catégories et numéros d'ordre -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-folder me-2"></i>Catégories et ordre
                </h6>
            </div>
            <div class="card-body p-4">
                <label class="form-label fw-semibold">Catégories *</label>
                <p class="small text-muted mb-3">Sélectionnez une ou plusieurs catégories et définissez le numéro d'ordre pour chacune</p>
                
                @php
                    $selectedCategories = old('categories', isset($workout) ? $workout->categories->pluck('id')->toArray() : []);
                    $orderNumbers = old('order_numbers', isset($workout) ? $workout->categories->pluck('pivot.order_number', 'id')->toArray() : []);
                @endphp
                
                <div id="categories-container" class="mb-3">
                    @foreach($categories as $sectionName => $sectionCategories)
                        <div class="mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-layer-group me-1"></i>{{ $sectionName }}
                            </h6>
                            @foreach($sectionCategories as $category)
                                <div class="card mb-2 category-item" data-category-id="{{ $category->id }}">
                                    <div class="card-body p-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input category-checkbox" 
                                                   type="checkbox" 
                                                   name="categories[]" 
                                                   value="{{ $category->id }}"
                                                   id="category_{{ $category->id }}"
                                                   {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                                   onchange="toggleOrderNumber({{ $category->id }})">
                                            <label class="form-check-label" for="category_{{ $category->id }}">
                                                <strong>{{ $category->name }}</strong>
                                            </label>
                                        </div>
                                        
                                        <div class="order-number-container" 
                                             id="order_container_{{ $category->id }}"
                                             style="display: {{ in_array($category->id, $selectedCategories) ? 'block' : 'none' }};">
                                            <label for="order_{{ $category->id }}" class="form-label small text-muted">
                                                Numéro d'ordre dans cette catégorie
                                            </label>
                                            <input type="number" 
                                                   name="order_numbers[{{ $category->id }}]" 
                                                   id="order_{{ $category->id }}"
                                                   value="{{ $orderNumbers[$category->id] ?? 0 }}"
                                                   class="form-control form-control-sm"
                                                   min="0"
                                                   placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                
                @error('categories')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                
                <div class="alert alert-info border-0 small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Info :</strong> Le numéro d'ordre définit la position du workout dans chaque catégorie (0 = premier)
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
                    <a href="{{ route('admin.workouts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                        </button>
                        <button type="submit" name="action" value="save_and_continue" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Enregistrer et continuer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}



.category-item {
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
}

.category-item:has(.category-checkbox:checked) {
    border-left-color: var(--bs-primary);
    background-color: #f8f9fa;
}
</style>
@endpush



@push('scripts')
{{-- Charger Quill CSS ET JS --}}
@once
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="{{ asset('js/media-selector.js') }}"></script>
<script src="{{ asset('js/quill-advanced.js') }}"></script>
<script src="{{ asset('js/quill-ai-optimizer.js') }}"></script>
@endonce

<script>
function toggleOrderNumber(categoryId) {
    const checkbox = document.getElementById('category_' + categoryId);
    const orderContainer = document.getElementById('order_container_' + categoryId);
    
    if (checkbox.checked) {
        orderContainer.style.display = 'block';
    } else {
        orderContainer.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        let quillShortDescription = null;
        let quillLongDescription = null;
        
        if (document.getElementById('short-description-editor')) {
            quillShortDescription = initQuillEditor('#short-description-editor', 'short_description');
            console.log('✅ Short description editor initialized');
        }
        
        if (document.getElementById('long-description-editor')) {
            quillLongDescription = initQuillEditor('#long-description-editor', 'long_description');
            console.log('✅ Long description editor initialized');
        }

        // Synchronisation à la soumission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                if (quillShortDescription) {
                    document.getElementById('short_description').value = quillShortDescription.root.innerHTML;
                }
                if (quillLongDescription) {
                    document.getElementById('long_description').value = quillLongDescription.root.innerHTML;
                }
            });
        }

        // Auto-génération du slug
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                if (!slugInput.value || slugInput.dataset.autoGenerated) {
                    const slug = this.value
                        .toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    slugInput.value = slug;
                    slugInput.dataset.autoGenerated = 'true';
                }
            });
            
            slugInput.addEventListener('input', function() {
                this.dataset.autoGenerated = '';
            });
        }

        // Initialiser l'IA
        setTimeout(function() {
            if (typeof window.initQuillAI === 'function') {
                console.log('🤖 Initialisation IA...');
                window.initQuillAI();
            }
        }, 2000);
        
    }, 500);
});
</script>
@endpush