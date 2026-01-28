@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-dumbbell me-2"></i>Informations de la séance
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Titre -->
                <div class="mb-4">
                    <label for="titre" class="form-label fw-semibold">Titre de la séance *</label>
                    <input type="text" 
                           name="titre" 
                           id="titre" 
                           value="{{ old('titre', isset($seance) ? $seance->titre : '') }}"
                           class="form-control form-control-lg @error('titre') is-invalid @enderror"
                           placeholder="Ex: Séance pectoraux + triceps..."
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
                              class="d-none @error('description') is-invalid @enderror">{{ old('description', isset($seance) ? $seance->description : '') }}</textarea>
                              
                    <div class="form-text">Décrivez les objectifs et le contenu de la séance...</div>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Matériel requis et Durée -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="materiel_requis" class="form-label fw-semibold">Matériel requis</label>
                        
                        <div id="materiel-editor" style="height: 120px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                        
                        <textarea name="materiel_requis" 
                                  id="materiel_requis" 
                                  class="d-none @error('materiel_requis') is-invalid @enderror">{{ old('materiel_requis', isset($seance) ? $seance->materiel_requis : '') }}</textarea>
                                  
                        <div class="form-text">Liste du matériel nécessaire...</div>
                        @error('materiel_requis')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="duree_estimee_minutes" class="form-label fw-semibold">Durée estimée (minutes)</label>
                        <input type="number" 
                               name="duree_estimee_minutes" 
                               id="duree_estimee_minutes" 
                               value="{{ old('duree_estimee_minutes', isset($seance) ? $seance->duree_estimee_minutes : '') }}"
                               class="form-control @error('duree_estimee_minutes') is-invalid @enderror"
                               min="1" max="480"
                               placeholder="45">
                        @error('duree_estimee_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Échauffement et Retour au calme avec Quill -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="echauffement" class="form-label fw-semibold">Échauffement</label>
                        
                        <div id="echauffement-editor" style="height: 120px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                        
                        <textarea name="echauffement" 
                                  id="echauffement" 
                                  class="d-none @error('echauffement') is-invalid @enderror">{{ old('echauffement', isset($seance) ? $seance->echauffement : '') }}</textarea>
                                  
                        <div class="form-text">Échauffement spécifique...</div>
                        @error('echauffement')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="retour_calme" class="form-label fw-semibold">Retour au calme</label>
                        
                        <div id="retour_calme-editor" style="height: 120px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>
                        
                        <textarea name="retour_calme" 
                                  id="retour_calme" 
                                  class="d-none @error('retour_calme') is-invalid @enderror">{{ old('retour_calme', isset($seance) ? $seance->retour_calme : '') }}</textarea>
                                  
                        <div class="form-text">Étirements et récupération...</div>
                        @error('retour_calme')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Séries d'exercices -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Séries d'exercices</h6>
                    <div id="series-container">
                        @if(isset($seance))
                            @foreach($seance->series as $index => $serieItem)
                                <div class="card border mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Série {{ $index + 1 }}</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSerie(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Série</label>
                                                <select name="series[{{ $index }}][serie_id]" class="form-select" required>
                                                    @foreach($series as $serie)
                                                        <option value="{{ $serie->id }}" {{ $serie->id == $serieItem->id ? 'selected' : '' }}>
                                                            {{ $serie->nom_complet }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Ordre</label>
                                                <input type="number" name="series[{{ $index }}][ordre]" class="form-control" 
                                                       value="{{ $serieItem->pivot->ordre }}" min="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Nombre</label>
                                                <input type="number" name="series[{{ $index }}][nombre_series]" class="form-control" 
                                                       value="{{ $serieItem->pivot->nombre_series }}" min="1" max="10" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Notes</label>
                                                <input type="text" name="series[{{ $index }}][notes]" class="form-control" 
                                                       value="{{ $serieItem->pivot->notes }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline-primary" onclick="addSerie()">
                        <i class="fas fa-plus me-2"></i>Ajouter une série
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
        <option value="debutant" {{ old('niveau', isset($seance) ? $seance->niveau : '') === 'debutant' ? 'selected' : '' }}>Débutant</option>
        <option value="intermediaire" {{ old('niveau', isset($seance) ? $seance->niveau : '') === 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
        <option value="avance" {{ old('niveau', isset($seance) ? $seance->niveau : '') === 'avance' ? 'selected' : '' }}>Avancé</option>
        <option value="special" {{ old('niveau', isset($seance) ? $seance->niveau : '') === 'special' ? 'selected' : '' }}>Spécial</option>
    </select>
    @error('niveau')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="type_seance" class="form-label fw-semibold">
        Type de séance <small class="text-muted">(optionnel)</small>
    </label>
    <select name="type_seance" id="type_seance" class="form-select @error('type_seance') is-invalid @enderror">
        <option value="">-- Aucun type --</option>
        <option value="force" {{ old('type_seance', isset($seance) ? $seance->type_seance : '') === 'force' ? 'selected' : '' }}>Force</option>
        <option value="cardio" {{ old('type_seance', isset($seance) ? $seance->type_seance : '') === 'cardio' ? 'selected' : '' }}>Cardio</option>
        <option value="mixte" {{ old('type_seance', isset($seance) ? $seance->type_seance : '') === 'mixte' ? 'selected' : '' }}>Mixte</option>
        <option value="recuperation" {{ old('type_seance', isset($seance) ? $seance->type_seance : '') === 'recuperation' ? 'selected' : '' }}>Récupération</option>
    </select>
    @error('type_seance')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>





                <div class="mb-3">
                    <label for="ordre" class="form-label">Ordre</label>
                    <input type="number" 
                           name="ordre" 
                           id="ordre" 
                           value="{{ old('ordre', isset($seance) ? $seance->ordre : 0) }}"
                           class="form-control"
                           min="0">
                </div>

                <div class="form-check">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', isset($seance) ? $seance->is_active : true) ? 'checked' : '' }}
                           class="form-check-input">
                    <label for="is_active" class="form-check-label">
                        Séance active
                    </label>
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2"></i>Image de la séance
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">URL de l'image</label>
                    <div class="input-group">
                        <input type="text" 
                               name="image" 
                               id="image" 
                               value="{{ old('image', isset($seance) ? $seance->image : '') }}"
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

                @if(isset($seance) && $seance->image)
                    <div class="mt-3" id="currentImagePreview">
                        <small class="text-muted d-block mb-2">Aperçu actuel :</small>
                        <img src="{{ $seance->image }}" 
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
                    <a href="{{ route('admin.training.seances.index') }}" class="btn btn-outline-secondary">
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
    // Pré-rendu des options de séries pour le JS
    $serieOptionsHtml = '<option value="">Choisir une série</option>';
    if (isset($series)) {
        foreach ($series as $serie) {
            $serieOptionsHtml .= '<option value="' . $serie->id . '">' . htmlspecialchars($serie->nom_complet) . '</option>';
        }
    }
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // 1. INITIALISATION DES ÉDITEURS QUILL
    // ========================================
    let quillDescription = null;
    let quillMateriel = null;
    let quillEchauffement = null;
    let quillRetourCalme = null;
    
    if (document.getElementById('description-editor')) {
        quillDescription = initQuillEditor('#description-editor', 'description');
    }
    
    if (document.getElementById('materiel-editor')) {
        quillMateriel = initQuillEditor('#materiel-editor', 'materiel_requis');
    }
    
    if (document.getElementById('echauffement-editor')) {
        quillEchauffement = initQuillEditor('#echauffement-editor', 'echauffement');
    }
    
    if (document.getElementById('retour_calme-editor')) {
        quillRetourCalme = initQuillEditor('#retour_calme-editor', 'retour_calme');
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
            
            const materielTextarea = document.getElementById('materiel_requis');
            if (materielTextarea && quillMateriel) {
                materielTextarea.value = quillMateriel.root.innerHTML;
            }
            
            const echauffementTextarea = document.getElementById('echauffement');
            if (echauffementTextarea && quillEchauffement) {
                echauffementTextarea.value = quillEchauffement.root.innerHTML;
            }
            
            const retourCalmeTextarea = document.getElementById('retour_calme');
            if (retourCalmeTextarea && quillRetourCalme) {
                retourCalmeTextarea.value = quillRetourCalme.root.innerHTML;
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
// 4. GESTION DES SÉRIES
// ========================================
const serieOptionsHtml = `{!! $serieOptionsHtml !!}`;
let serieIndex = {{ isset($seance) ? $seance->series->count() : 0 }};

function addSerie() {
    const container = document.getElementById('series-container');
    const div = document.createElement('div');
    div.className = 'card border mb-3';
    div.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Série ${serieIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSerie(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Série</label>
                    <select name="series[${serieIndex}][serie_id]" class="form-select" required>
                        ${serieOptionsHtml}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Ordre</label>
                    <input type="number" name="series[${serieIndex}][ordre]" class="form-control" value="${serieIndex + 1}" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nombre</label>
                    <input type="number" name="series[${serieIndex}][nombre_series]" class="form-control" value="1" min="1" max="10" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <input type="text" name="series[${serieIndex}][notes]" class="form-control" placeholder="Notes spécifiques...">
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    serieIndex++;
}

function removeSerie(button) {
    button.closest('.card').remove();
}

// Ajouter une première série si nouvelle séance
@if(!isset($seance) || (isset($seance) && $seance->series->count() == 0))
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('series-container').children.length === 0) {
            addSerie();
        }
    });
@endif
</script>
@endpush