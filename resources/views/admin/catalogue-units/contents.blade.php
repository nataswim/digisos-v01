@extends('layouts.admin')

@section('title', 'Contenus de l\'unité : ' . $catalogueUnit->title)
@section('page-title', 'Gestion des contenus')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des contenus -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-list me-2"></i>Contenus de l'unité
                            </h5>
                            <small class="opacity-75">{{ $catalogueUnit->title }}</small>
                        </div>
                        <button class="btn bg-warning text-white p-2" data-bs-toggle="modal" data-bs-target="#addMultipleContentsModal">
                            <i class="fas fa-plus me-2"></i>Ajouter des contenus
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($catalogueUnit->contents->count() > 0)
                        <div class="list-group list-group-flush" id="contents-list">
                            @foreach($catalogueUnit->contents()->ordered()->get() as $content)
                                <div class="list-group-item px-4 py-3" data-content-id="{{ $content->id }}">
                                    <div class="row align-items-center">
                                        <!-- Handle de tri -->
                                        <div class="col-auto">
                                            <i class="fas fa-grip-vertical text-muted handle" style="cursor: move;"></i>
                                            <span class="badge bg-secondary ms-2">{{ $content->order }}</span>
                                        </div>
                                        
                                        <!-- Info contenu -->
                                        <div class="col">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        {{ $content->display_title }}
                                                        @if($content->custom_title)
                                                            <small class="text-muted">(Titre personnalisé)</small>
                                                        @endif
                                                    </h6>
                                                    <div class="d-flex gap-2 mb-1">
                                                        <span class="badge bg-info-subtle text-info">
                                                            {{ $content->content_type_label }}
                                                        </span>
                                                        @if($content->is_required)
                                                            <span class="badge bg-success-subtle text-success">Obligatoire</span>
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning">Optionnel</span>
                                                        @endif
                                                        @if($content->duration_minutes)
                                                            <span class="badge bg-secondary-subtle text-secondary">
                                                                <i class="fas fa-clock me-1"></i>{{ $content->duration_minutes }} min
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($content->custom_description)
                                                        <small class="text-muted">{{ $content->custom_description }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="col-auto">
                                            <div class="btn-group">
                                                @if($content->content_url)
                                                    <a href="{{ $content->content_url }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-info"
                                                       title="Voir le contenu">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @endif
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="editContentModal({{ $content->id }})"
                                                        title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" 
                                                      action="{{ route('admin.catalogue-units.remove-content', [$catalogueUnit, $content]) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Retirer ce contenu de l\'unité ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucun contenu lié</h5>
                            <p class="text-muted">Ajoutez des contenus à cette unité</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMultipleContentsModal">
                                <i class="fas fa-plus me-2"></i>Ajouter des contenus
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar infos -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h6>
                </div>
                <div class="card-body p-3">
                    <dl class="row mb-0">
                        <dt class="col-12">Module</dt>
                        <dd class="col-12 mb-3">{{ $catalogueUnit->module->name }}</dd>
                        
                        <dt class="col-12">Nombre de contenus</dt>
                        <dd class="col-12 mb-3">
                            <span class="badge bg-primary fs-6">{{ $catalogueUnit->contents->count() }}</span>
                        </dd>
                        
                        <dt class="col-12">Types présents</dt>
                        <dd class="col-12">
                            @forelse($catalogueUnit->content_types as $type)
                                <span class="badge bg-secondary me-1 mb-1">{{ $type }}</span>
                            @empty
                                <span class="text-muted">Aucun</span>
                            @endforelse
                        </dd>
                    </dl>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.catalogue-units.edit', $catalogueUnit) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier l'unité
                        </a>
                        <a href="{{ route('admin.catalogue-units.index') }}" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ajout multiple de contenus -->
<div class="modal fade" id="addMultipleContentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.catalogue-units.add-multiple-contents', $catalogueUnit) }}" id="addMultipleContentsForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter des contenus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Étape 1 : Sélection du type -->
                    <div id="step-select-type">
                        <div class="mb-4">
                            <label for="contentable_type_multiple" class="form-label fw-semibold">
                                <i class="fas fa-layer-group me-2"></i>Type de contenu *
                            </label>
                            <select name="contentable_type" id="contentable_type_multiple" class="form-select form-select-lg" required>
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
                    </div>
                    
                    <!-- Étape 2 : Liste des contenus avec checkboxes -->
                    <div id="step-select-contents" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">
                                <i class="fas fa-check-double me-2"></i>Sélectionner les contenus
                            </h6>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" onclick="selectAllContents()">
                                    <i class="fas fa-check-square me-1"></i>Tout sélectionner
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="deselectAllContents()">
                                    <i class="fas fa-square me-1"></i>Tout désélectionner
                                </button>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Astuce :</strong> Cochez les contenus que vous souhaitez ajouter à cette unité. 
                            Vous pourrez ensuite modifier les paramètres individuels de chaque contenu.
                        </div>
                        
                        <!-- Barre de recherche -->
                        <div class="mb-3">
                            <input type="text" 
                                   id="search-contents" 
                                   class="form-control" 
                                   placeholder="Rechercher un contenu...">
                        </div>
                        
                        <!-- Liste des contenus avec checkboxes -->
                        <div id="contents-checkboxes-container" class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                <p>Chargement des contenus...</p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                <span id="selected-count">0</span> contenu(s) sélectionné(s)
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit-multiple-btn" disabled>
                        <i class="fas fa-plus me-2"></i>Ajouter les contenus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>

.bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0891b2 100%);
}
.content-checkbox-item {
    transition: background-color 0.2s;
}
.content-checkbox-item:hover {
    background-color: #f8f9fa;
}
.content-checkbox-item.selected {
    background-color: #e7f5ff;
    border-color: #0ea5e9;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== DRAG & DROP POUR RÉORDONNER ==========
    const contentsList = document.getElementById('contents-list');
    if (contentsList) {
        Sortable.create(contentsList, {
            handle: '.handle',
            animation: 150,
            onEnd: function(evt) {
                const contents = [];
                contentsList.querySelectorAll('[data-content-id]').forEach(item => {
                    contents.push(item.dataset.contentId);
                });
                
                // Envoyer le nouvel ordre
                fetch(`{{ route('admin.catalogue-units.reorder-contents', $catalogueUnit) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ contents: contents })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour les badges d'ordre
                        contentsList.querySelectorAll('.badge.bg-secondary').forEach((badge, index) => {
                            badge.textContent = index + 1;
                        });
                    }
                })
                .catch(error => console.error('Erreur:', error));
            }
        });
    }
    
    // ========== MODAL AJOUT MULTIPLE ==========
    const typeSelect = document.getElementById('contentable_type_multiple');
    const stepSelectType = document.getElementById('step-select-type');
    const stepSelectContents = document.getElementById('step-select-contents');
    const contentsContainer = document.getElementById('contents-checkboxes-container');
    const submitBtn = document.getElementById('submit-multiple-btn');
    const selectedCountSpan = document.getElementById('selected-count');
    const searchInput = document.getElementById('search-contents');
    const modal = document.getElementById('addMultipleContentsModal');
    
    let allContents = [];
    
    // Réinitialiser le formulaire à la fermeture de la modale
    modal.addEventListener('hidden.bs.modal', function () {
        typeSelect.value = '';
        stepSelectContents.style.display = 'none';
        contentsContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin fa-2x mb-3"></i><p>Chargement des contenus...</p></div>';
        submitBtn.disabled = true;
        selectedCountSpan.textContent = '0';
        searchInput.value = '';
        allContents = [];
    });
    
    // Changement de type de contenu
    typeSelect.addEventListener('change', function() {
        const contentType = this.value;
        
        if (!contentType) {
            stepSelectContents.style.display = 'none';
            submitBtn.disabled = true;
            return;
        }
        
        stepSelectContents.style.display = 'block';
        contentsContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin fa-2x mb-3"></i><p>Chargement des contenus...</p></div>';
        
        // Charger les contenus disponibles
        fetch(`{{ route('admin.catalogue-units.api.content-by-type') }}?content_type=${encodeURIComponent(contentType)}`)
            .then(response => response.json())
            .then(data => {
                allContents = data;
                renderContents(data);
            })
            .catch(error => {
                console.error('Erreur:', error);
                contentsContainer.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erreur lors du chargement des contenus.</div>';
            });
    });
    
    // Fonction pour afficher les contenus
    function renderContents(contents) {
        if (contents.length === 0) {
            contentsContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-3"></i><p>Aucun contenu disponible pour ce type.</p></div>';
            return;
        }
        
        let html = '<div class="list-group">';
        contents.forEach(content => {
            html += `
                <label class="list-group-item content-checkbox-item" data-content-id="${content.id}" data-content-title="${content.title.toLowerCase()}">
                    <div class="d-flex align-items-center">
                        <input class="form-check-input me-3 content-checkbox" 
                               type="checkbox" 
                               name="contentable_ids[]" 
                               value="${content.id}"
                               onchange="updateSelectedCount()">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">${content.title}</h6>
                            ${content.slug ? `<small class="text-muted">${content.slug}</small>` : ''}
                        </div>
                    </div>
                </label>
            `;
        });
        html += '</div>';
        
        contentsContainer.innerHTML = html;
        updateSelectedCount();
    }
    
    // Recherche dans les contenus
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = contentsContainer.querySelectorAll('.content-checkbox-item');
        
        items.forEach(item => {
            const title = item.dataset.contentTitle;
            if (title.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Mettre à jour le compteur de sélection
    window.updateSelectedCount = function() {
        const checkboxes = contentsContainer.querySelectorAll('.content-checkbox:checked');
        const count = checkboxes.length;
        selectedCountSpan.textContent = count;
        submitBtn.disabled = count === 0;
        
        // Mettre en évidence les éléments sélectionnés
        contentsContainer.querySelectorAll('.content-checkbox-item').forEach(item => {
            const checkbox = item.querySelector('.content-checkbox');
            if (checkbox.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    };
    
    // Sélectionner tous les contenus visibles
    window.selectAllContents = function() {
        contentsContainer.querySelectorAll('.content-checkbox-item').forEach(item => {
            if (item.style.display !== 'none') {
                item.querySelector('.content-checkbox').checked = true;
            }
        });
        updateSelectedCount();
    };
    
    // Désélectionner tous les contenus
    window.deselectAllContents = function() {
        contentsContainer.querySelectorAll('.content-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCount();
    };
});

// Fonction pour éditer un contenu (placeholder pour future implémentation)
function editContentModal(contentId) {
    alert('Fonctionnalité d\'édition à venir - ID: ' + contentId);
    // TODO: Implémenter une modale d'édition individuelle si nécessaire
}
</script>
@endpush