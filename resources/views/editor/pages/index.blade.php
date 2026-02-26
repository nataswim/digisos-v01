@extends('layouts.editor')

@section('title', 'Gestion des Pages')
@section('page-title', 'Pages')
@section('page-description', 'Gestion des pages statiques')

@section('content')

<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-secondary  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white ">Pages ({{ $pages->total() }})</h5>
                <a href="{{ route('editor.pages.create') }}" class="btn btn-warning">
                    <i class="fas fa-plus me-2"></i>Nouvelle page
                </a>
            </div>
        </div>
</section>

<div class="container-fluid">
    




    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total site</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-primary">{{ $stats['my_total'] }}</h3>
                    <small class="text-primary">Mes pages</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $stats['my_published'] }}</h3>
                    <small class="text-success">Mes publiées</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-secondary">{{ $stats['my_draft'] }}</h3>
                    <small class="text-secondary">Mes brouillons</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('editor.pages.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Rechercher..."
                               value="{{ $search }}">
                    </div>
                    
                    <div class="col-md-2">
                        <select name="category" class="form-select">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <select name="visibility" class="form-select">
                            <option value="">Toutes visibilités</option>
                            <option value="public" {{ $visibility === 'public' ? 'selected' : '' }}>Public</option>
                            <option value="authenticated" {{ $visibility === 'authenticated' ? 'selected' : '' }}>Authentifié</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-check mt-2">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="my_pages" 
                                   id="myPagesFilter" 
                                   value="1"
                                   {{ $myPages ? 'checked' : '' }}>
                            <label class="form-check-label" for="myPagesFilter">
                                Mes pages uniquement
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('editor.pages.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions et liste -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pages ({{ $pages->total() }})</h5>
                <a href="{{ route('editor.pages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle page
                </a>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($pages->count() > 0)
                <!-- Actions groupées -->
                <div class="p-3 bg-light border-bottom">
                    <form method="POST" 
                          action="{{ route('editor.pages.bulk-assign-categories') }}"
                          id="bulkCategoryForm">
                        @csrf
                        <input type="hidden" name="page_ids" id="selectedPageIds">
                        
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small mb-1">Catégorie pour les pages sélectionnées :</label>
                                <select name="pages_category_id" class="form-select form-select-sm">
                                    <option value="">Aucune catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-sm btn-primary" id="bulkCategoryBtn" disabled>
                                    <i class="fas fa-tags me-1"></i>Assigner la catégorie
                                </button>
                            </div>
                            <div class="col-md-5 text-end">
                                <small class="text-muted">
                                    <span id="selectedCount">0</span> page(s) sélectionnée(s)
                                </small>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th width="120">Visibilité</th>
                                <th width="100">Statut</th>
                                <th width="120">Auteur</th>
                                <th width="120">Créée le</th>
                                <th width="150" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                                <tr>
                                    <td>
                                        <input type="checkbox" 
                                               class="page-checkbox" 
                                               name="page_ids[]" 
                                               value="{{ $page->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $page->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $page->slug }}</small>
                                    </td>
                                    <td>
                                        @if($page->category)
                                            <span class="badge bg-primary">{{ $page->category->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($page->visibility === 'public')
                                            <span class="badge bg-success">Public</span>
                                        @else
                                            <span class="badge bg-warning">Authentifié</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($page->is_published)
                                            <span class="badge bg-success">Publié</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $page->creator ? $page->creator->name : $page->created_by_name }}
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $page->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('editor.pages.show', $page) }}" 
                                               class="btn btn-outline-info"
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('editor.pages.edit', $page) }}" 
                                               class="btn btn-outline-primary"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('editor.pages.destroy', $page) }}" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Supprimer cette page ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger"
                                                        title="Supprimer">
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
                
                <div class="p-3">
                    {{ $pages->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune page trouvée</p>
                    <a href="{{ route('editor.pages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la première page
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.page-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const bulkCategoryBtn = document.getElementById('bulkCategoryBtn');
    const selectedPageIds = document.getElementById('selectedPageIds');
    const bulkCategoryForm = document.getElementById('bulkCategoryForm');

    // Sélectionner tout / Désélectionner tout
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }

    // Mise à jour du compteur
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        const count = selected.length;
        
        selectedCount.textContent = count;
        bulkCategoryBtn.disabled = count === 0;

        // Mettre à jour le champ caché avec les IDs sélectionnés
        const ids = selected.map(cb => cb.value);
        selectedPageIds.value = JSON.stringify(ids);
    }

    // Soumission du formulaire d'assignation de catégories
    if (bulkCategoryForm) {
        bulkCategoryForm.addEventListener('submit', function(e) {
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            if (selected.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins une page.');
                return false;
            }
            
            // Conversion du JSON en array pour le formulaire
            const pageIds = selected.map(cb => cb.value);
            
            // Supprimer le champ JSON caché
            selectedPageIds.remove();
            
            // Ajouter des champs individuels pour chaque ID
            pageIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'page_ids[]';
                input.value = id;
                bulkCategoryForm.appendChild(input);
            });
        });
    }
});
</script>
@endpush