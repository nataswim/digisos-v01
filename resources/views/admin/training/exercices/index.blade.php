@extends('layouts.admin')

@section('title', 'Gestion des Exercices')
@section('page-title', 'Exercices')
@section('page-description', 'Gestion des exercices d\'entraînement')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des exercices - Pleine largeur -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-running me-2"></i>Exercices
                            </h5>
                            <small class="opacity-75">{{ $exercices->total() ?? $exercices->count() }} exercice(s) au total</small>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Bouton Actions en masse -->
                            <button type="button" 
                                    class="btn btn-warning d-none" 
                                    id="bulk-action-btn"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#bulkCategoriesModal">
                                <i class="fas fa-layer-group me-2"></i>
                                <span id="selected-count">0</span> sélectionné(s)
                            </button>
                            
                            <a href="{{ route('admin.training.exercices.create') }}" class="btn bg-warning text-white p-2">
                                <i class="fas fa-plus me-2"></i>Nouvel exercice
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="card-body border-bottom p-4 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control border-start-0"
                                    placeholder="Rechercher un exercice...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                                @if(request('search'))
                                <a href="{{ route('admin.training.exercices.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Exercices -->
                <div class="card-body p-0">
                    @if($exercices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3" style="width: 50px;">
                                        <input type="checkbox" 
                                               id="select-all" 
                                               class="form-check-input"
                                               title="Tout sélectionner">
                                    </th>
                                    <th class="border-0 px-4 py-3">Exercice</th>
                                    <th class="border-0 px-4 py-3">Catégories</th>
                                    <th class="border-0 px-4 py-3">Type & Niveau</th>
                                    <th class="border-0 px-4 py-3">Muscles</th>
                                    <th class="border-0 px-4 py-3">Statut</th>
                                    <th class="border-0 px-4 py-3">Utilisation</th>
                                    <th class="border-0 px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exercices as $exercice)
                                <tr class="border-bottom hover-bg">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" 
                                               class="form-check-input exercice-checkbox" 
                                               value="{{ $exercice->id }}"
                                               data-titre="{{ $exercice->titre }}">
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-start">
                                            @if($exercice->image)
                                            <img src="{{ $exercice->image }}"
                                                class="rounded me-3"
                                                style="width: 60px; height: 45px; object-fit: cover;"
                                                alt="">
                                            @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 45px;">
                                                <i class="fas fa-running text-muted"></i>
                                            </div>
                                            @endif
                                            <div class="flex-fill">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.training.exercices.show', $exercice) }}"
                                                        class="text-decoration-none text-dark">
                                                        {!! Str::limit($exercice->titre, 50) !!}
                                                    </a>
                                                </h6>
                                                @if($exercice->description)
                                                <small class="text-muted">{!! Str::limit(strip_tags($exercice->description), 60) !!}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column gap-1">
                                            @if($exercice->categories->isNotEmpty())
                                                @foreach($exercice->categories->take(2) as $cat)
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        <i class="fas fa-folder me-1"></i>{{ $cat->name }}
                                                    </span>
                                                @endforeach
                                                @if($exercice->categories->count() > 2)
                                                    <span class="badge bg-secondary">
                                                        +{{ $exercice->categories->count() - 2 }} autre(s)
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Non catégorisé</span>
                                            @endif
                                            
                                            @if($exercice->sousCategories->isNotEmpty())
                                                @foreach($exercice->sousCategories->take(1) as $sousCat)
                                                    <span class="badge bg-info-subtle text-info">
                                                        <i class="fas fa-layer-group me-1"></i>{{ $sousCat->name }}
                                                    </span>
                                                @endforeach
                                                @if($exercice->sousCategories->count() > 1)
                                                    <span class="badge bg-secondary">
                                                        +{{ $exercice->sousCategories->count() - 1 }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column gap-1">
                                            @if($exercice->type_exercice)
                                            <span class="badge bg-primary-subtle text-primary">
                                                {{ ucfirst($exercice->type_exercice) }}
                                            </span>
                                            @else
                                            <span class="badge bg-secondary">Non défini</span>
                                            @endif

                                            @if($exercice->niveau)
                                            @php
                                            $badgeColor = match($exercice->niveau) {
                                            'debutant' => 'success',
                                            'avance' => 'danger',
                                            default => 'warning'
                                            };
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }}">
                                                {{ ucfirst($exercice->niveau) }}
                                            </span>
                                            @else
                                            <span class="badge bg-secondary">Non défini</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <small class="text-muted">
                                            {{ $exercice->muscles_cibles_formatted }}
                                        </small>
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="badge bg-{{ $exercice->is_active ? 'success' : 'secondary' }}-subtle text-{{ $exercice->is_active ? 'success' : 'secondary' }}">
                                            {{ $exercice->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-list me-1"></i>
                                            <span>{{ $exercice->series()->count() }} série(s)</span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <div class="d-inline-flex gap-1">
                                            <!-- Bouton Voir -->
                                            <a href="{{ route('admin.training.exercices.show', $exercice) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Voir"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('admin.training.exercices.edit', $exercice) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Modifier"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Bouton Supprimer -->
                                            <form method="POST"
                                                action="{{ route('admin.training.exercices.destroy', $exercice) }}"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?')"
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
                    @if($exercices->hasPages())
                    <div class="card-footer bg-white border-top p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-muted">
                                Affichage de {{ $exercices->firstItem() }} à {{ $exercices->lastItem() }}
                                sur {{ $exercices->total() }} résultat(s)
                            </div>
                            {{ $exercices->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-running fa-3x text-muted mb-3 opacity-25"></i>
                        <h5>Aucun exercice trouvé</h5>
                        @if(request()->hasAny(['search', 'niveau', 'type']))
                        <p class="text-muted mb-3">Aucun résultat ne correspond à vos critères de recherche.</p>
                        <a href="{{ route('admin.training.exercices.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Voir tous les exercices
                        </a>
                        @else
                        <p class="text-muted mb-3">Commencez par créer votre premier exercice</p>
                        <a href="{{ route('admin.training.exercices.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Créer un exercice
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cards en bas : Statistiques et Actions rapides -->
        <div class="col-lg-6">
            <!-- Statistiques générales -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                    $totalExercices = \App\Models\Exercice::count();
                    $activeExercices = \App\Models\Exercice::where('is_active', true)->count();
                    $forceExercices = \App\Models\Exercice::where('type_exercice', 'force')->count();
                    $cardioExercices = \App\Models\Exercice::where('type_exercice', 'cardio')->count();
                    @endphp

                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $totalExercices }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $activeExercices }}</h4>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $forceExercices }}</h4>
                                <small class="text-muted">Force</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $cardioExercices }}</h4>
                                <small class="text-muted">Cardio</small>
                            </div>
                        </div>
                    </div>
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
                        <a href="{{ route('admin.training.exercices.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvel exercice
                        </a>
                        <a href="{{ route('admin.training.series.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-list-ol me-2"></i>Gérer les séries
                        </a>
                        <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-images me-2"></i>Médiathèque
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Actions en masse -->
<div class="modal fade" id="bulkCategoriesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="bulk-categories-form" method="POST" action="{{ route('admin.training.exercices.bulk-assign-categories') }}">
                @csrf
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-layer-group me-2"></i>Assigner des catégories en masse
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Exercices sélectionnés -->
                    <div class="alert alert-info mb-4">
                        <strong><span id="modal-selected-count">0</span> exercice(s) sélectionné(s) :</strong>
                        <div id="selected-exercices-list" class="mt-2 small"></div>
                    </div>
                    
                    <!-- Onglets d'action -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" 
                                    id="add-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#add-pane" 
                                    type="button">
                                <i class="fas fa-plus-circle me-1"></i>Ajouter
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" 
                                    id="replace-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#replace-pane" 
                                    type="button">
                                <i class="fas fa-sync me-1"></i>Remplacer
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" 
                                    id="remove-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#remove-pane" 
                                    type="button">
                                <i class="fas fa-minus-circle me-1"></i>Supprimer
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Contenu des onglets -->
                    <div class="tab-content">
                        <!-- Onglet Ajouter -->
                        <div class="tab-pane fade show active" id="add-pane">
                            <input type="hidden" name="action" value="add" id="action-input">
                            
                            <p class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Les catégories sélectionnées seront <strong>ajoutées</strong> aux catégories existantes des exercices.
                            </p>
                            
                            @include('admin.training.exercices.partials.bulk-categories-form')
                        </div>
                        
                        <!-- Onglet Remplacer -->
                        <div class="tab-pane fade" id="replace-pane">
                            <p class="text-muted">
                                <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                                Les catégories actuelles seront <strong>supprimées</strong> et remplacées par les nouvelles sélectionnées.
                            </p>
                            
                            @include('admin.training.exercices.partials.bulk-categories-form')
                        </div>
                        
                        <!-- Onglet Supprimer -->
                        <div class="tab-pane fade" id="remove-pane">
                            <p class="text-muted">
                                <i class="fas fa-trash me-1 text-danger"></i>
                                Les catégories sélectionnées seront <strong>supprimées</strong> des exercices.
                            </p>
                            
                            @include('admin.training.exercices.partials.bulk-categories-form')
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Appliquer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    

    

    

    

    

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

    const selectAllCheckbox = document.getElementById('select-all');
    const exerciceCheckboxes = document.querySelectorAll('.exercice-checkbox');
    const bulkActionBtn = document.getElementById('bulk-action-btn');
    const selectedCountSpan = document.getElementById('selected-count');
    const modalSelectedCountSpan = document.getElementById('modal-selected-count');
    const selectedExercicesList = document.getElementById('selected-exercices-list');
    const bulkForm = document.getElementById('bulk-categories-form');
    const actionInput = document.getElementById('action-input');
    
    // Sélectionner/désélectionner tous
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            exerciceCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkButton();
        });
    }
    
    // Écouter les changements sur chaque checkbox
    exerciceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButton);
    });
    
    // Mettre à jour le bouton et le compteur
    function updateBulkButton() {
        const selectedCheckboxes = document.querySelectorAll('.exercice-checkbox:checked');
        const count = selectedCheckboxes.length;
        
        if (count > 0) {
            bulkActionBtn.classList.remove('d-none');
            selectedCountSpan.textContent = count;
        } else {
            bulkActionBtn.classList.add('d-none');
        }
        
        // Mettre à jour "Sélectionner tout"
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = count === exerciceCheckboxes.length && count > 0;
        }
    }
    
    // Au clic sur le bouton Actions en masse
    bulkActionBtn.addEventListener('click', function() {
        const selectedCheckboxes = document.querySelectorAll('.exercice-checkbox:checked');
        const count = selectedCheckboxes.length;
        
        // Mettre à jour le compteur dans la modal
        modalSelectedCountSpan.textContent = count;
        
        // Afficher la liste des exercices sélectionnés
        let listHtml = '<ul class="mb-0">';
        selectedCheckboxes.forEach(checkbox => {
            listHtml += `<li>${checkbox.dataset.titre}</li>`;
        });
        listHtml += '</ul>';
        selectedExercicesList.innerHTML = listHtml;
    });
    
    // Changer l'action selon l'onglet
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            const targetId = e.target.getAttribute('data-bs-target');
            
            if (targetId === '#add-pane') {
                actionInput.value = 'add';
            } else if (targetId === '#replace-pane') {
                actionInput.value = 'replace';
            } else if (targetId === '#remove-pane') {
                actionInput.value = 'remove';
            }
        });
    });
    
    // Ajouter les IDs des exercices sélectionnés au formulaire
    bulkForm.addEventListener('submit', function(e) {
        // Supprimer les anciens inputs cachés
        bulkForm.querySelectorAll('input[name="exercices[]"]').forEach(input => input.remove());
        
        // Ajouter les nouveaux
        const selectedCheckboxes = document.querySelectorAll('.exercice-checkbox:checked');
        selectedCheckboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'exercices[]';
            input.value = checkbox.value;
            bulkForm.appendChild(input);
        });
    });
});
</script>
@endpush