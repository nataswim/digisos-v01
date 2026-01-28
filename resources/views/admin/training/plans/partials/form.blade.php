@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Informations du plan
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Titre -->
                <div class="mb-4">
                    <label for="titre" class="form-label fw-semibold">Titre du plan *</label>
                    <input type="text" 
                           name="titre" 
                           id="titre" 
                           value="{{ old('titre', isset($plan) ? $plan->titre : '') }}"
                           class="form-control form-control-lg @error('titre') is-invalid @enderror"
                           placeholder="Ex: Programme Force 12 semaines..."
                           required>
                    @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description avec Quill -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    
                    <div id="description-editor" style="height: 400px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <textarea name="description" 
                              id="description" 
                              class="d-none @error('description') is-invalid @enderror">{{ old('description', isset($plan) ? $plan->description : '') }}</textarea>
                              
                    <div class="form-text">Décrivez les objectifs et le contenu du plan...</div>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Prérequis avec Quill -->
                <div class="mb-4">
                    <label for="prerequis" class="form-label fw-semibold">Prérequis</label>
                    
                    <div id="prerequis-editor" style="height: 120px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <textarea name="prerequis" 
                              id="prerequis" 
                              class="d-none @error('prerequis') is-invalid @enderror">{{ old('prerequis', isset($plan) ? $plan->prerequis : '') }}</textarea>
                              
                    <div class="form-text">Conditions requises pour suivre ce plan...</div>
                    @error('prerequis')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Conseils généraux avec Quill -->
                <div class="mb-4">
                    <label for="conseils_generaux" class="form-label fw-semibold">Conseils généraux</label>
                    
                    <div id="conseils-editor" style="height: 120px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                    
                    <textarea name="conseils_generaux" 
                              id="conseils_generaux" 
                              class="d-none @error('conseils_generaux') is-invalid @enderror">{{ old('conseils_generaux', isset($plan) ? $plan->conseils_generaux : '') }}</textarea>
                              
                    <div class="form-text">Conseils pour bien suivre ce plan...</div>
                    @error('conseils_generaux')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Cycles du plan -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Cycles du plan</h6>
                    <div id="cycles-container">
                        @if(isset($plan))
                            @foreach($plan->cycles as $index => $cycleItem)
                                <div class="card border mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Cycle {{ $index + 1 }}</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCycle(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Cycle</label>
                                                <select name="cycles[{{ $index }}][cycle_id]" class="form-select" required>
                                                    @foreach($cycles as $cycle)
                                                        <option value="{{ $cycle->id }}" {{ $cycle->id == $cycleItem->id ? 'selected' : '' }}>
                                                            {{ $cycle->titre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Ordre</label>
                                                <input type="number" name="cycles[{{ $index }}][ordre]" class="form-control" 
                                                       value="{{ $cycleItem->pivot->ordre }}" min="1" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Semaine début</label>
                                                <input type="number" name="cycles[{{ $index }}][semaine_debut]" class="form-control" 
                                                       value="{{ $cycleItem->pivot->semaine_debut }}" min="1" max="104" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Notes</label>
                                                <input type="text" name="cycles[{{ $index }}][notes]" class="form-control" 
                                                       value="{{ $cycleItem->pivot->notes }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline-primary" onclick="addCycle()">
                        <i class="fas fa-plus me-2"></i>Ajouter un cycle
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Paramètres -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Paramètres
                </h6>
            </div>
            <div class="card-body p-4">

<div class="mb-3">
    <label for="niveau" class="form-label fw-semibold">
        Niveau <small class="text-muted">(optionnel)</small>
    </label>
    <select name="niveau" id="niveau" class="form-select @error('niveau') is-invalid @enderror">
        <option value="">-- Aucun niveau --</option>
        <option value="debutant" {{ old('niveau', isset($plan) ? $plan->niveau : '') === 'debutant' ? 'selected' : '' }}>Débutant</option>
        <option value="intermediaire" {{ old('niveau', isset($plan) ? $plan->niveau : '') === 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
        <option value="avance" {{ old('niveau', isset($plan) ? $plan->niveau : '') === 'avance' ? 'selected' : '' }}>Avancé</option>
        <option value="special" {{ old('niveau', isset($plan) ? $plan->niveau : '') === 'special' ? 'selected' : '' }}>Spécial</option>
    </select>
    @error('niveau')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="objectif" class="form-label fw-semibold">
        Objectif <small class="text-muted">(optionnel)</small>
    </label>
    <select name="objectif" id="objectif" class="form-select @error('objectif') is-invalid @enderror">
        <option value="">-- Aucun objectif --</option>
        <option value="force" {{ old('objectif', isset($plan) ? $plan->objectif : '') === 'force' ? 'selected' : '' }}>Force</option>
        <option value="endurance" {{ old('objectif', isset($plan) ? $plan->objectif : '') === 'endurance' ? 'selected' : '' }}>Endurance</option>
        <option value="perte_poids" {{ old('objectif', isset($plan) ? $plan->objectif : '') === 'perte_poids' ? 'selected' : '' }}>Perte de poids</option>
        <option value="prise_masse" {{ old('objectif', isset($plan) ? $plan->objectif : '') === 'prise_masse' ? 'selected' : '' }}>Prise de masse</option>
        <option value="recuperation" {{ old('objectif', isset($plan) ? $plan->objectif : '') === 'recuperation' ? 'selected' : '' }}>Récupération</option>
        <option value="mixte" {{ old('objectif', isset($plan) ? $plan->objectif : '') === 'mixte' ? 'selected' : '' }}>Mixte</option>
    </select>
    @error('objectif')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>




                <div class="mb-3">
                    <label for="duree_semaines" class="form-label fw-semibold">Durée totale (semaines)</label>
                    <input type="number" 
                           name="duree_semaines" 
                           id="duree_semaines" 
                           value="{{ old('duree_semaines', isset($plan) ? $plan->duree_semaines : '') }}"
                           class="form-control @error('duree_semaines') is-invalid @enderror"
                           min="1" max="104">
                    @error('duree_semaines')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="ordre" class="form-label">Ordre</label>
                    <input type="number" 
                           name="ordre" 
                           id="ordre" 
                           value="{{ old('ordre', isset($plan) ? $plan->ordre : 0) }}"
                           class="form-control"
                           min="0">
                </div>

                <div class="row g-2">
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="is_public" 
                                   id="is_public" 
                                   value="1"
                                   {{ old('is_public', isset($plan) ? $plan->is_public : true) ? 'checked' : '' }}
                                   class="form-check-input">
                            <label for="is_public" class="form-check-label">
                                Plan public
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="is_featured" 
                                   id="is_featured" 
                                   value="1"
                                   {{ old('is_featured', isset($plan) ? $plan->is_featured : false) ? 'checked' : '' }}
                                   class="form-check-input">
                            <label for="is_featured" class="form-check-label">
                                Plan à la une
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', isset($plan) ? $plan->is_active : true) ? 'checked' : '' }}
                                   class="form-check-input">
                            <label for="is_active" class="form-check-label">
                                Plan actif
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2"></i>Image du plan
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">URL de l'image</label>
                    <div class="input-group">
                        <input type="text" 
                               name="image" 
                               id="image" 
                               value="{{ old('image', isset($plan) ? $plan->image : '') }}"
                               class="form-control @error('image') is-invalid @enderror"
                               placeholder="https://exemple.com/image.jpg ou /storage/media/image.jpg">
                        <button type="button" 
                                class="btn btn-outline-primary"
                                onclick="openMediaSelector('image', 'imagePreview')">
                            <i class="fas fa-images"></i>
                        </button>
                    </div>
                    <div class="form-text">Sélectionnez depuis la médiathèque ou saisissez une URL</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($plan) && $plan->image)
                    <div class="mt-3" id="currentImagePreview">
                        <small class="text-muted d-block mb-2">Aperçu actuel :</small>
                        <img src="{{ $plan->image }}" 
                             id="imagePreview"
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 100px; object-fit: cover;"
                             alt="Image actuelle">
                    </div>
                @else
                    <div class="mt-3 d-none" id="currentImagePreview">
                        <small class="text-muted d-block mb-2">Aperçu :</small>
                        <img id="imagePreview"
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 100px; object-fit: cover;"
                             alt="Aperçu">
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
                    <a href="{{ route('admin.training.plans.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@once
<script src="{{ asset('js/media-selector.js') }}"></script>
<script src="{{ asset('js/quill-advanced.js') }}"></script>
@endonce

@php
    // Pré-rendu des options de cycles pour le JS
    $cycleOptionsHtml = '<option value="">Choisir un cycle</option>';
    if (isset($cycles)) {
        foreach ($cycles as $cycle) {
            $cycleOptionsHtml .= '<option value="' . $cycle->id . '">' . htmlspecialchars($cycle->titre) . '</option>';
        }
    }
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // 1. INITIALISATION DES ÉDITEURS QUILL
    // ========================================
    let quillDescription = null;
    let quillPrerequis = null;
    let quillConseils = null;
    
    if (document.getElementById('description-editor')) {
        quillDescription = initQuillEditor('#description-editor', 'description');
    }
    
    if (document.getElementById('prerequis-editor')) {
        quillPrerequis = initQuillEditor('#prerequis-editor', 'prerequis');
    }
    
    if (document.getElementById('conseils-editor')) {
        quillConseils = initQuillEditor('#conseils-editor', 'conseils_generaux');
    }

    // ========================================
    // 2. SYNCHRONISATION À LA SOUMISSION
    // ========================================
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const descriptionTextarea = document.getElementById('description');
            if (descriptionTextarea && quillDescription) {
                descriptionTextarea.value = quillDescription.root.innerHTML;
            }
            
            const prerequisTextarea = document.getElementById('prerequis');
            if (prerequisTextarea && quillPrerequis) {
                prerequisTextarea.value = quillPrerequis.root.innerHTML;
            }
            
            const conseilsTextarea = document.getElementById('conseils_generaux');
            if (conseilsTextarea && quillConseils) {
                conseilsTextarea.value = quillConseils.root.innerHTML;
            }
        });
    }

    // ========================================
    // 3. APERÇU DE L'IMAGE
    // ========================================
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('currentImagePreview');
    
    if (imageInput && imagePreview && imagePreviewContainer) {
        imageInput.addEventListener('input', function() {
            const imageUrl = this.value.trim();
            if (imageUrl) {
                imagePreview.src = imageUrl;
                imagePreviewContainer.classList.remove('d-none');
            } else {
                imagePreviewContainer.classList.add('d-none');
            }
        });
    }
});

// ========================================
// 4. GESTION DES CYCLES
// ========================================
const cycleOptionsHtml = `{!! $cycleOptionsHtml !!}`;
let cycleIndex = {{ isset($plan) ? $plan->cycles->count() : 0 }};

function addCycle() {
    const container = document.getElementById('cycles-container');
    const div = document.createElement('div');
    div.className = 'card border mb-3';
    div.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Cycle ${cycleIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCycle(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cycle</label>
                    <select name="cycles[${cycleIndex}][cycle_id]" class="form-select" required>
                        ${cycleOptionsHtml}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ordre</label>
                    <input type="number" name="cycles[${cycleIndex}][ordre]" class="form-control" value="${cycleIndex + 1}" min="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Semaine début</label>
                    <input type="number" name="cycles[${cycleIndex}][semaine_debut]" class="form-control" value="1" min="1" max="104" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <input type="text" name="cycles[${cycleIndex}][notes]" class="form-control" placeholder="Notes spécifiques...">
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    cycleIndex++;
}

function removeCycle(button) {
    button.closest('.card').remove();
}

// Ajouter un premier cycle si nouveau plan
@if(!isset($plan) || (isset($plan) && $plan->cycles->count() == 0))
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('cycles-container').children.length === 0) {
            addCycle();
        }
    });
@endif
</script>
@endpush