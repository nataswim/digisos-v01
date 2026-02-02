@extends('layouts.admin')

@section('title', 'Gestion des Fiches')
@section('page-title', 'Fiches')
@section('page-description', 'Gestion des fiches professionnelles')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des fiches - Pleine largeur -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-file-alt me-2"></i>Fiches
                            </h5>
                            <small class="opacity-75">{{ $fiches->total() ?? $fiches->count() }} fiche(s) au total</small>
                        </div>
                        <a href="{{ route('admin.fiches.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouvelle fiche
                        </a>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="card-body border-bottom p-4 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text"
                                    name="search"
                                    value="{{ $search ?? '' }}"
                                    class="form-control border-start-0"
                                    placeholder="Rechercher...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="visibility" class="form-select">
                                <option value="">Toute visibilité</option>
                                <option value="public" {{ ($visibility ?? '') === 'public' ? 'selected' : '' }}>
                                    Public
                                </option>
                                <option value="authenticated" {{ ($visibility ?? '') === 'authenticated' ? 'selected' : '' }}>
                                    Membres
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category" id="filter_category" class="form-select">
                                <option value="">Toutes catégories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sous_category" id="filter_sous_category" class="form-select" {{ !($categoryId ?? '') ? 'disabled' : '' }}>
                                <option value="">Toutes sous-catégories</option>
                                @if(isset($sousCategories) && $sousCategories->count() > 0)
                                @foreach($sousCategories as $sousCategory)
                                <option value="{{ $sousCategory->id }}" {{ ($sousCategoryId ?? '') == $sousCategory->id ? 'selected' : '' }}>
                                    {{ $sousCategory->name }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select name="featured" class="form-select">
                                <option value="">Toutes</option>
                                <option value="1" {{ ($featured ?? '') == '1' ? 'selected' : '' }}>
                                    En vedette
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'visibility', 'category', 'sous_category', 'featured']))
                                <a href="{{ route('admin.fiches.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Actions groupées -->
                <div class="card-body border-bottom p-4 bg-white" id="bulk-actions-container" style="display: none;">
                    <form method="POST" action="{{ route('admin.fiches.bulk-assign-categories') }}" id="bulk-assign-form">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-primary me-2 fs-5"></i>
                                    <span class="fw-semibold">
                                        <span id="selected-count">0</span> fiche(s) sélectionnée(s)
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="bulk_category" class="form-label fw-semibold mb-1">Catégorie</label>
                                <select name="fiches_category_id" id="bulk_category" class="form-select">
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="bulk_sous_category" class="form-label fw-semibold mb-1">Sous-catégorie</label>
                                <select name="fiches_sous_category_id" id="bulk_sous_category" class="form-select" disabled>
                                    <option value="">-- Sélectionner une sous-catégorie --</option>
                                </select>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary" id="apply-bulk-action">
                                    <i class="fas fa-check me-2"></i>Appliquer les modifications
                                </button>
                            </div>

                            <div class="col-auto">
                                <button type="button" class="btn btn-outline-secondary" id="cancel-selection">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            </div>
                        </div>

                        <!-- Conteneur pour les IDs des fiches sélectionnées -->
                        <div id="selected-fiches-container"></div>
                    </form>
                </div>

                <!-- Fiches -->
                <div class="card-body p-0">
                    @if($fiches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3" style="width: 30px;">
                                        <input type="checkbox" id="select-all-fiches" class="form-check-input">
                                    </th>
                                    <th class="border-0 px-4 py-3">Fiche</th>
                                    <th class="border-0 px-4 py-3">Visibilité</th>
                                    <th class="border-0 px-4 py-3">Classification</th>
                                    <th class="border-0 px-4 py-3">Stats</th>
                                    <th class="border-0 px-4 py-3">Date</th>
                                    <th class="border-0 px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fiches as $fiche)
                                <tr class="border-bottom hover-bg">
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox"
                                            class="form-check-input fiche-checkbox"
                                            value="{{ $fiche->id }}"
                                            data-fiche-id="{{ $fiche->id }}">
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-start">
                                            @if($fiche->image)
                                            <img src="{{ $fiche->image }}"
                                                class="rounded me-3"
                                                style="width: 60px; height: 45px; object-fit: cover;"
                                                alt="">
                                            @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 45px;">
                                                <i class="fas fa-file-alt text-muted"></i>
                                            </div>
                                            @endif
                                            <div class="flex-fill">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.fiches.show', $fiche) }}"
                                                        class="text-decoration-none text-dark">
                                                        {{ Str::limit($fiche->title, 60) }}
                                                    </a>
                                                </h6>
                                                @if($fiche->short_description)
                                                <small class="text-muted">{{ Str::limit(strip_tags($fiche->short_description), 80) }}</small>
                                                @endif
                                                <div class="d-flex flex-wrap gap-1 mt-2">
                                                    @if($fiche->is_featured)
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="fas fa-star me-1"></i>En vedette
                                                    </span>
                                                    @endif
                                                    @if($fiche->is_published)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="fas fa-check me-1"></i>Publié
                                                    </span>
                                                    @else
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="fas fa-edit me-1"></i>Brouillon
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        @if($fiche->visibility === 'authenticated')
                                        <span class="badge bg-info-subtle text-info" title="Contenu réservé aux membres">
                                            <i class="fas fa-lock me-1"></i>Membres
                                        </span>
                                        @else
                                        <span class="badge bg-success-subtle text-success" title="Contenu public">
                                            <i class="fas fa-globe me-1"></i>Public
                                        </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        @if($fiche->category || $fiche->sousCategory)
                                        <div class="d-flex flex-column gap-1">
                                            @if($fiche->category)
                                            <span class="badge bg-primary-subtle text-primary">
                                                <i class="fas fa-folder me-1"></i>{{ $fiche->category->name }}
                                            </span>
                                            @endif
                                            @if($fiche->sousCategory)
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="fas fa-layer-group me-1"></i>{{ $fiche->sousCategory->name }}
                                            </span>
                                            @endif
                                        </div>
                                        @else
                                        <span class="text-muted">Non catégorisé</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center text-muted">
                                                <i class="fas fa-eye me-1"></i>
                                                <span>{{ number_format($fiche->views_count ?? 0) }}</span>
                                            </div>
                                            @if($fiche->sort_order > 0)
                                            <small class="text-muted">
                                                <i class="fas fa-sort me-1"></i>Ordre: {{ $fiche->sort_order }}
                                            </small>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column">
                                            <small class="fw-semibold">
                                                {{ $fiche->published_at?->format('d/m/Y') ?? $fiche->created_at?->format('d/m/Y') ?? 'N/A' }}
                                            </small>
                                            <small class="text-muted">
                                                {{ $fiche->published_at?->format('H:i') ?? $fiche->created_at?->format('H:i') ?? '' }}
                                            </small>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <div class="d-inline-flex gap-1">
                                            <!-- Bouton Voir -->
                                            <a href="{{ route('admin.fiches.show', $fiche) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Voir"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('admin.fiches.edit', $fiche) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Modifier"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Bouton Voir en ligne -->
@if($fiche->is_published && $fiche->category && $fiche->sousCategory)
    <a href="{{ route('public.fiches.show', [
            'category' => $fiche->category->slug, 
            'sousCategory' => $fiche->sousCategory->slug,
            'fiche' => $fiche->slug
        ]) }}" 
       target="_blank"
       class="btn btn-sm btn-outline-success" 
       title="Voir en ligne"
       data-bs-toggle="tooltip">
        <i class="fas fa-external-link-alt"></i>
    </a>
@else
    <button type="button"
            class="btn btn-sm btn-outline-secondary" 
            title="@if(!$fiche->is_published)Non publié @elseif(!$fiche->category || !$fiche->sousCategory)Catégorie/Sous-catégorie manquante @else Non disponible @endif"
            data-bs-toggle="tooltip"
            disabled>
        <i class="fas fa-eye-slash"></i>
    </button>
@endif
                                            
                                            <!-- Bouton Supprimer -->
                                            <form method="POST" 
                                                  action="{{ route('admin.fiches.destroy', $fiche) }}" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette fiche ?')"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Supprimer"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($fiches->hasPages())
                    <div class="card-footer bg-white border-top p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-muted">
                                Affichage de {{ $fiches->firstItem() }} à {{ $fiches->lastItem() }}
                                sur {{ $fiches->total() }} résultat(s)
                            </div>
                            {{ $fiches->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-25"></i>
                        <h5>Aucune fiche trouvée</h5>
                        @if(request()->hasAny(['search', 'visibility', 'category', 'sous_category', 'featured']))
                        <p class="text-muted mb-3">Aucun résultat ne correspond à vos critères de recherche.</p>
                        <a href="{{ route('admin.fiches.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Voir toutes les fiches
                        </a>
                        @else
                        <p class="text-muted mb-3">Commencez par créer votre première fiche</p>
                        <a href="{{ route('admin.fiches.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Créer une fiche
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cards en bas : Statistiques et Visibilité -->
        <div class="col-lg-6">
            <!-- Statistiques générales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $stats['total'] }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $stats['published'] }}</h4>
                                <small class="text-muted">Publiées</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $stats['draft'] }}</h4>
                                <small class="text-muted">Brouillons</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $stats['featured'] }}</h4>
                                <small class="text-muted">Vedettes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition visibilité -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Visibilité
                    </h6>
                </div>
                <div class="card-body p-3">
                    @if($stats['total'] > 0)
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-semibold text-success">
                                    <i class="fas fa-globe me-1"></i>Public
                                </span>
                                <span class="badge bg-success-subtle text-success">{{ $stats['public'] }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ ($stats['public'] / $stats['total']) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-semibold text-info">
                                    <i class="fas fa-lock me-1"></i>Membres
                                </span>
                                <span class="badge bg-info-subtle text-info">{{ $stats['authenticated'] }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" 
                                     style="width: {{ ($stats['authenticated'] / $stats['total']) * 100 }}%"></div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune fiche créée</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.fiches.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle fiche
                        </a>
                        <a href="{{ route('admin.fiches-categories.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-folder me-2"></i>Gérer les catégories
                        </a>
                        <a href="{{ route('admin.fiches-sous-categories.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-layer-group me-2"></i>Gérer les sous-catégories
                        </a>
                        @if($stats['featured'] > 0)
                            <a href="{{ route('admin.fiches.index', ['featured' => '1']) }}" class="btn btn-outline-warning">
                                <i class="fas fa-star me-2"></i>Fiches vedettes ({{ $stats['featured'] }})
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>






.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

.hover-bg:hover {
    background-color: #f8f9fa;
}

.dropdown-menu {
    border: 0;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Style pour les boutons d'actions */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ========================================
    // GESTION DE LA SÉLECTION DES FICHES
    // ========================================
    
    const selectAllCheckbox = document.getElementById('select-all-fiches');
    const ficheCheckboxes = document.querySelectorAll('.fiche-checkbox');
    const bulkActionsContainer = document.getElementById('bulk-actions-container');
    const selectedCountSpan = document.getElementById('selected-count');
    const selectedFichesContainer = document.getElementById('selected-fiches-container');
    const cancelSelectionBtn = document.getElementById('cancel-selection');
    const bulkForm = document.getElementById('bulk-assign-form');
    
    // Fonction pour mettre à jour l'UI de sélection
    function updateSelectionUI() {
        const selectedCheckboxes = document.querySelectorAll('.fiche-checkbox:checked');
        const count = selectedCheckboxes.length;
        
        // Afficher/masquer la barre d'actions groupées
        if (count > 0) {
            bulkActionsContainer.style.display = 'block';
            selectedCountSpan.textContent = count;
            
            // Générer les inputs cachés pour les IDs sélectionnés
            selectedFichesContainer.innerHTML = '';
            selectedCheckboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'fiche_ids[]';
                input.value = checkbox.value;
                selectedFichesContainer.appendChild(input);
            });
        } else {
            bulkActionsContainer.style.display = 'none';
            selectedFichesContainer.innerHTML = '';
        }
        
        // Mettre à jour l'état de "Tout sélectionner"
        if (selectAllCheckbox) {
            const allChecked = ficheCheckboxes.length > 0 && 
                             Array.from(ficheCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(ficheCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        }
    }
    
    // Tout sélectionner / Tout désélectionner
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            ficheCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionUI();
        });
    }
    
    // Sélection individuelle
    ficheCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionUI);
    });
    
    // Annuler la sélection
    if (cancelSelectionBtn) {
        cancelSelectionBtn.addEventListener('click', function() {
            ficheCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            updateSelectionUI();
            
            // Réinitialiser les selects
            document.getElementById('bulk_category').value = '';
            document.getElementById('bulk_sous_category').value = '';
            document.getElementById('bulk_sous_category').disabled = true;
        });
    }
    
    // ========================================
    // CHARGEMENT DYNAMIQUE DES SOUS-CATÉGORIES
    // ========================================
    
    const bulkCategorySelect = document.getElementById('bulk_category');
    const bulkSousCategorySelect = document.getElementById('bulk_sous_category');
    
    if (bulkCategorySelect && bulkSousCategorySelect) {
        bulkCategorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Réinitialiser
            bulkSousCategorySelect.innerHTML = '<option value="">-- Sélectionner une sous-catégorie --</option>';
            bulkSousCategorySelect.disabled = true;
            
            if (!categoryId) {
                return;
            }
            
            // Charger les sous-catégories
            fetch(`{{ route('admin.fiches-sous-categories.api.by-category') }}?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(sousCategory => {
                            const option = document.createElement('option');
                            option.value = sousCategory.id;
                            option.textContent = sousCategory.name;
                            bulkSousCategorySelect.appendChild(option);
                        });
                        bulkSousCategorySelect.disabled = false;
                    }
                })
                .catch(error => console.error('Erreur:', error));
        });
    }
    
    // ========================================
    // VALIDATION AVANT SOUMISSION
    // ========================================
    
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const categoryId = document.getElementById('bulk_category').value;
            const sousCategoryId = document.getElementById('bulk_sous_category').value;
            const selectedCount = document.querySelectorAll('.fiche-checkbox:checked').length;
            
            // Vérifier qu'au moins une fiche est sélectionnée
            if (selectedCount === 0) {
                e.preventDefault();
                alert('⚠️ Veuillez sélectionner au moins une fiche.');
                return false;
            }
            
            // Vérifier qu'au moins une catégorie ou sous-catégorie est sélectionnée
            if (!categoryId && !sousCategoryId) {
                e.preventDefault();
                alert('⚠️ Veuillez sélectionner au moins une catégorie ou une sous-catégorie.');
                return false;
            }
            
            // Confirmation
            const message = `Voulez-vous vraiment assigner ${selectedCount} fiche(s) à la catégorie/sous-catégorie sélectionnée ?\n\n⚠️ Cette action remplacera les catégories existantes.`;
            
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    }
    
    // ========================================
    // FILTRE DYNAMIQUE DES SOUS-CATÉGORIES (CODE EXISTANT)
    // ========================================
    
    // Filtre dynamique des sous-catégories dans l'index
    const filterCategory = document.getElementById('filter_category');
    const filterSousCategory = document.getElementById('filter_sous_category');
    
    if (filterCategory && filterSousCategory) {
        filterCategory.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Réinitialiser
            filterSousCategory.innerHTML = '<option value="">Toutes sous-catégories</option>';
            filterSousCategory.disabled = true;
            
            if (!categoryId) {
                return;
            }
            
            // Charger les sous-catégories
            fetch(`{{ route('admin.fiches-sous-categories.api.by-category') }}?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(sousCategory => {
                            const option = document.createElement('option');
                            option.value = sousCategory.id;
                            option.textContent = sousCategory.name;
                            filterSousCategory.appendChild(option);
                        });
                        filterSousCategory.disabled = false;
                    }
                })
                .catch(error => console.error('Erreur:', error));
        });
    }
});
</script>
@endpush