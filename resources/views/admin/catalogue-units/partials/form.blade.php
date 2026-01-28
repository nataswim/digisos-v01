@csrf

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Informations de l'Unité</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Titre de l'unité *</label>
                    <input type="text" name="title" id="title" 
                           value="{{ old('title', isset($unit) ? $unit->title : '') }}"
                           class="form-control form-control-lg @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug URL</label>
                    <input type="text" name="slug" id="slug" 
                           value="{{ old('slug', isset($unit) ? $unit->slug : '') }}"
                           class="form-control @error('slug') is-invalid @enderror">
                    <div class="form-text">Laisser vide pour génération automatique</div>
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', isset($unit) ? $unit->description : '') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section des contenus multiples -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-info text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Contenus de l'unité</h6>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addContentsModal">
                        <i class="fas fa-plus me-1"></i>Ajouter des contenus
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="contents-container">
                    @php
                        $existingContents = isset($unit) ? $unit->contents()->ordered()->get() : collect();
                        $oldContents = old('contents', []);
                    @endphp
                    
                    @if(count($oldContents) > 0)
                        {{-- Afficher les anciens contenus en cas d'erreur de validation --}}
                        @foreach($oldContents as $index => $oldContent)
                            @include('admin.catalogue-units.partials.content-item-display', [
                                'index' => $index,
                                'content' => (object)$oldContent,
                                'isNew' => true
                            ])
                        @endforeach
                    @elseif($existingContents->count() > 0)
                        {{-- Afficher les contenus existants en mode édition --}}
                        @foreach($existingContents as $index => $content)
                            @include('admin.catalogue-units.partials.content-item-display', [
                                'index' => $index,
                                'content' => $content,
                                'isNew' => false
                            ])
                        @endforeach
                    @else
                        {{-- Message si aucun contenu --}}
                        <div id="no-content-message" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">Aucun contenu ajouté. Cliquez sur "Ajouter des contenus" pour commencer.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Paramètres</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="catalogue_section_id" class="form-label fw-semibold">Section *</label>
                    <select name="catalogue_section_id" id="catalogue_section_id" 
                            class="form-select @error('catalogue_section_id') is-invalid @enderror">
                        <option value="">Sélectionner une section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" 
                                    data-section-id="{{ $section->id }}"
                                    {{ old('catalogue_section_id', isset($unit) && $unit->module ? $unit->module->catalogue_section_id : '') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('catalogue_section_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="catalogue_module_id" class="form-label fw-semibold">Module parent *</label>
                    <select name="catalogue_module_id" id="catalogue_module_id" 
                            class="form-select @error('catalogue_module_id') is-invalid @enderror" required>
                        <option value="">Sélectionner d'abord une section</option>
                        @if(isset($unit) && $unit->module)
                            <option value="{{ $unit->module->id }}" selected>{{ $unit->module->name }}</option>
                        @endif
                    </select>
                    @error('catalogue_module_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label fw-semibold">Ordre d'affichage</label>
                    <input type="number" name="order" id="order" 
                           value="{{ old('order', isset($unit) ? $unit->order : 0) }}"
                           class="form-control @error('order') is-invalid @enderror" min="0">
                    @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', isset($unit) ? $unit->is_active : true) ? 'checked' : '' }}
                           class="form-check-input">
                    <label for="is_active" class="form-check-label">
                        <i class="fas fa-check-circle text-success me-1"></i>Unité active
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.catalogue-units.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
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

<!-- Modal ajout de contenus avec sélection multiple -->
<div class="modal fade" id="addContentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter des contenus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Sélection du type -->
                <div class="mb-4">
                    <label for="modal_contentable_type" class="form-label fw-semibold">
                        <i class="fas fa-layer-group me-2"></i>Type de contenu *
                    </label>
                    <select id="modal_contentable_type" class="form-select form-select-lg">
                        <option value="">-- Sélectionner un type --</option>
                        <option value="App\Models\Post">Article (Post)</option>
                        <option value="App\Models\Video">Vidéo</option>
                        <option value="App\Models\Downloadable">Fichier téléchargeable</option>
                        <option value="App\Models\Fiche">Fiche</option>
                        <option value="App\Models\Exercice">Exercice</option>
                        <option value="App\Models\Workout">Entraînement</option>
                        <option value="App\Models\EbookFile">E-book</option>
                    </select>
                </div>
                
                <!-- Liste des contenus -->
                <div id="modal-contents-list" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-check-double me-2"></i>Sélectionner les contenus
                        </h6>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="modalSelectAll()">
                                <i class="fas fa-check-square me-1"></i>Tout
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="modalDeselectAll()">
                                <i class="fas fa-square me-1"></i>Aucun
                            </button>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Cochez les contenus à ajouter. Vous pourrez modifier leurs paramètres individuels après l'enregistrement.
                    </div>
                    
                    <!-- Recherche -->
                    <div class="mb-3">
                        <input type="text" 
                               id="modal-search" 
                               class="form-control" 
                               placeholder="Rechercher un contenu...">
                    </div>
                    
                    <!-- Contenus avec checkboxes -->
                    <div id="modal-contents-checkboxes" class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                            <p>Chargement...</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            <span id="modal-selected-count">0</span> contenu(s) sélectionné(s)
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-primary" id="modal-add-btn" onclick="addSelectedContents()" disabled>
                    <i class="fas fa-plus me-2"></i>Ajouter au formulaire
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>

.bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0891b2 100%);
}

.content-item-display {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}
.content-item-display:hover {
    background-color: #e9ecef;
}
.modal-content-checkbox-item {
    transition: background-color 0.2s;
}
.modal-content-checkbox-item:hover {
    background-color: #f8f9fa;
}
.modal-content-checkbox-item.selected {
    background-color: #e7f5ff;
    border-color: #0ea5e9;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let contentIndex = {{ $existingContents->count() ?? 0 }};
    const container = document.getElementById('contents-container');
    const noContentMsg = document.getElementById('no-content-message');
    
    // Variables pour la modale
    const modalTypeSelect = document.getElementById('modal_contentable_type');
    const modalContentsList = document.getElementById('modal-contents-list');
    const modalCheckboxesContainer = document.getElementById('modal-contents-checkboxes');
    const modalAddBtn = document.getElementById('modal-add-btn');
    const modalSelectedCount = document.getElementById('modal-selected-count');
    const modalSearch = document.getElementById('modal-search');
    const modal = document.getElementById('addContentsModal');
    
    let availableContents = [];
    let selectedContentType = '';
    
    // ========== MODAL: Changement de type ==========
    modalTypeSelect.addEventListener('change', function() {
        selectedContentType = this.value;
        
        if (!selectedContentType) {
            modalContentsList.style.display = 'none';
            modalAddBtn.disabled = true;
            return;
        }
        
        modalContentsList.style.display = 'block';
        modalCheckboxesContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin fa-2x mb-3"></i><p>Chargement...</p></div>';
        
        // Charger les contenus
        fetch(`{{ route('admin.catalogue-units.api.content-by-type') }}?content_type=${encodeURIComponent(selectedContentType)}`)
            .then(response => response.json())
            .then(data => {
                availableContents = data;
                renderModalContents(data);
            })
            .catch(error => {
                console.error('Erreur:', error);
                modalCheckboxesContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erreur de chargement.</div>';
            });
    });
    
    // ========== MODAL: Afficher les contenus ==========
    function renderModalContents(contents) {
        if (contents.length === 0) {
            modalCheckboxesContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-3"></i><p>Aucun contenu disponible.</p></div>';
            return;
        }
        
        let html = '<div class="list-group">';
        contents.forEach(content => {
            html += `
                <label class="list-group-item modal-content-checkbox-item" data-content-id="${content.id}" data-content-title="${content.title.toLowerCase()}">
                    <div class="d-flex align-items-center">
                        <input class="form-check-input me-3 modal-content-checkbox" 
                               type="checkbox" 
                               value="${content.id}"
                               data-title="${content.title}"
                               data-slug="${content.slug || ''}"
                               onchange="modalUpdateCount()">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">${content.title}</h6>
                            ${content.slug ? `<small class="text-muted">${content.slug}</small>` : ''}
                        </div>
                    </div>
                </label>
            `;
        });
        html += '</div>';
        
        modalCheckboxesContainer.innerHTML = html;
        modalUpdateCount();
    }
    
    // ========== MODAL: Recherche ==========
    modalSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = modalCheckboxesContainer.querySelectorAll('.modal-content-checkbox-item');
        
        items.forEach(item => {
            const title = item.dataset.contentTitle;
            item.style.display = title.includes(searchTerm) ? '' : 'none';
        });
    });
    
    // ========== MODAL: Compteur ==========
    window.modalUpdateCount = function() {
        const checked = modalCheckboxesContainer.querySelectorAll('.modal-content-checkbox:checked').length;
        modalSelectedCount.textContent = checked;
        modalAddBtn.disabled = checked === 0;
        
        // Mise en évidence
        modalCheckboxesContainer.querySelectorAll('.modal-content-checkbox-item').forEach(item => {
            const checkbox = item.querySelector('.modal-content-checkbox');
            item.classList.toggle('selected', checkbox.checked);
        });
    };
    
    // ========== MODAL: Sélectionner tout ==========
    window.modalSelectAll = function() {
        modalCheckboxesContainer.querySelectorAll('.modal-content-checkbox-item').forEach(item => {
            if (item.style.display !== 'none') {
                item.querySelector('.modal-content-checkbox').checked = true;
            }
        });
        modalUpdateCount();
    };
    
    // ========== MODAL: Désélectionner tout ==========
    window.modalDeselectAll = function() {
        modalCheckboxesContainer.querySelectorAll('.modal-content-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        modalUpdateCount();
    };
    
    // ========== MODAL: Ajouter les contenus sélectionnés ==========
    window.addSelectedContents = function() {
        if (noContentMsg) {
            noContentMsg.remove();
        }
        
        const checkedBoxes = modalCheckboxesContainer.querySelectorAll('.modal-content-checkbox:checked');
        
        checkedBoxes.forEach(checkbox => {
            const contentId = checkbox.value;
            const contentTitle = checkbox.dataset.title;
            const contentSlug = checkbox.dataset.slug;
            
            // Créer l'élément à afficher
            const contentHtml = createContentDisplay(contentIndex, selectedContentType, contentId, contentTitle, contentSlug);
            container.insertAdjacentHTML('beforeend', contentHtml);
            
            contentIndex++;
        });
        
        // Fermer la modale et réinitialiser
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        bootstrapModal.hide();
        
        modalTypeSelect.value = '';
        modalContentsList.style.display = 'none';
        modalCheckboxesContainer.innerHTML = '';
        modalSearch.value = '';
        availableContents = [];
        selectedContentType = '';
        
        updateAllOrders();
    };
    
    // ========== CRÉER L'AFFICHAGE D'UN CONTENU ==========
    function createContentDisplay(index, type, id, title, slug) {
        const typeLabels = {
            'App\\Models\\Post': 'Article',
            'App\\Models\\Video': 'Vidéo',
            'App\\Models\\Downloadable': 'Fichier',
            'App\\Models\\Fiche': 'Fiche',
            'App\\Models\\Exercice': 'Exercice',
            'App\\Models\\Workout': 'Entraînement',
            'App\\Models\\EbookFile': 'E-book'
        };
        
        return `
            <div class="content-item-display border rounded p-3 mb-3" data-index="${index}">
                <input type="hidden" name="contents[${index}][contentable_type]" value="${type}">
                <input type="hidden" name="contents[${index}][contentable_id]" value="${id}">
                <input type="hidden" name="contents[${index}][order]" value="${index + 1}" class="order-input">
                <input type="hidden" name="contents[${index}][is_required]" value="1">
                
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-secondary order-badge">${index + 1}</span>
                            <span class="badge bg-info-subtle text-info">${typeLabels[type] || 'Contenu'}</span>
                        </div>
                        <h6 class="mb-1">${title}</h6>
                        ${slug ? `<small class="text-muted">${slug}</small>` : ''}
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeContent(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
    }
    
    // ========== SUPPRIMER UN CONTENU ==========
    window.removeContent = function(btn) {
        if (confirm('Supprimer ce contenu ?')) {
            btn.closest('.content-item-display').remove();
            updateAllOrders();
            
            if (container.querySelectorAll('.content-item-display').length === 0) {
                container.innerHTML = `
                    <div id="no-content-message" class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle fa-2x mb-3 opacity-25"></i>
                        <p class="mb-0">Aucun contenu ajouté. Cliquez sur "Ajouter des contenus" pour commencer.</p>
                    </div>
                `;
            }
        }
    };
    
    // ========== METTRE À JOUR LES ORDRES ==========
    function updateAllOrders() {
        container.querySelectorAll('.content-item-display').forEach((item, index) => {
            item.querySelector('.order-badge').textContent = index + 1;
            item.querySelector('.order-input').value = index + 1;
        });
    }
    
    // ========== AUTO-GÉNÉRATION DU SLUG ==========
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.autoGenerated) {
                slugInput.value = this.value.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.dataset.autoGenerated = 'true';
            }
        });
        
        slugInput.addEventListener('input', function() {
            this.dataset.autoGenerated = '';
        });
    }
    
    // ========== CHARGEMENT MODULES PAR SECTION ==========
    const sectionSelect = document.getElementById('catalogue_section_id');
    const moduleSelect = document.getElementById('catalogue_module_id');
    
    if (sectionSelect && moduleSelect) {
        sectionSelect.addEventListener('change', function() {
            const sectionId = this.value;
            
            moduleSelect.innerHTML = '<option value="">Chargement...</option>';
            moduleSelect.disabled = true;
            
            if (!sectionId) {
                moduleSelect.innerHTML = '<option value="">Sélectionner d\'abord une section</option>';
                return;
            }
            
            fetch(`{{ route('admin.catalogue-units.api.modules-by-section') }}?section_id=${sectionId}`)
                .then(response => response.json())
                .then(data => {
                    moduleSelect.innerHTML = '<option value="">Sélectionner un module</option>';
                    
                    if (data.length > 0) {
                        data.forEach(module => {
                            const option = document.createElement('option');
                            option.value = module.id;
                            option.textContent = module.name;
                            moduleSelect.appendChild(option);
                        });
                        moduleSelect.disabled = false;
                    } else {
                        moduleSelect.innerHTML = '<option value="">Aucun module disponible</option>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    moduleSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        });
        
        // Déclencher le chargement initial si section déjà sélectionnée
        if (sectionSelect.value) {
            const currentModuleId = '{{ isset($unit) ? $unit->catalogue_module_id : "" }}';
            if (currentModuleId) {
                sectionSelect.addEventListener('change', function setCurrentModule() {
                    setTimeout(() => {
                        moduleSelect.value = currentModuleId;
                    }, 500);
                    sectionSelect.removeEventListener('change', setCurrentModule);
                });
            }
            sectionSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush