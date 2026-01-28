@extends('layouts.admin')

@section('title', 'Gestion des Unités du Catalogue')
@section('page-title', 'Unités du Catalogue')
@section('page-description', 'Gestion des unités pédagogiques')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des unités - Pleine largeur -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-file-alt me-2"></i>Unités Pédagogiques
                            </h5>
                            <small class="opacity-75">{{ $units->total() ?? $units->count() }} unité(s) au total</small>
                        </div>
                        <a href="{{ route('admin.catalogue-units.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouvelle unité
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
                        <div class="col-md-3">
                            <select name="section" id="filter_section" class="form-select">
                                <option value="">Toutes les sections</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ ($sectionId ?? '') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="module" id="filter_module" class="form-select" {{ !($sectionId ?? '') ? 'disabled' : '' }}>
                                <option value="">Tous les modules</option>
                                @if(isset($modules))
                                    @foreach($modules as $mod)
                                        <option value="{{ $mod->id }}" {{ ($moduleId ?? '') == $mod->id ? 'selected' : '' }}>
                                            {{ $mod->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'section', 'module', 'content_type']))
                                    <a href="{{ route('admin.catalogue-units.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Unités -->
                <div class="card-body p-0">
                    @if($units->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Unité</th>
                                        <th class="border-0 px-4 py-3">Module / Section</th>
                                        <th class="border-0 px-4 py-3">Type de contenu</th>
                                        <th class="border-0 px-4 py-3">Ordre</th>
                                        <th class="border-0 px-4 py-3">Statut</th>
                                        <th class="border-0 px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($units as $unit)
                                        <tr class="border-bottom hover-bg">
                                            <td class="px-4 py-3">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('admin.catalogue-units.show', $unit) }}" 
                                                           class="text-decoration-none text-dark">
                                                            {{ Str::limit($unit->title, 50) }}
                                                        </a>
                                                    </h6>
                                                    @if($unit->description)
                                                        <small class="text-muted">{{ Str::limit(strip_tags($unit->description), 70) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @if($unit->module)
                                                    <div class="d-flex flex-column gap-1">
                                                        <span class="badge bg-primary-subtle text-primary">
                                                            {{ $unit->module->name }}
                                                        </span>
                                                        @if($unit->module->section)
                                                            <small class="text-muted">
                                                                <i class="fas fa-layer-group me-1"></i>{{ $unit->module->section->name }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info-subtle text-info">
                                                    {{ $unit->content_type_label }}
                                                </span>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary">{{ $unit->order }}</span>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @if($unit->is_active)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="fas fa-check me-1"></i>Actif
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="fas fa-pause me-1"></i>Inactif
                                                    </span>
                                                @endif
                                            </td>
                                            
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-inline-flex gap-1">
                                                    <!-- Bouton Voir -->
                                                    <a href="{{ route('admin.catalogue-units.show', $unit) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Voir"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Modifier -->
                                                    <a href="{{ route('admin.catalogue-units.edit', $unit) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Modifier"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Gérer les contenus -->
                                                    <a href="{{ route('admin.catalogue-units.contents', $unit) }}" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       title="Gérer les contenus"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Supprimer -->
                                                    <form method="POST" 
                                                          action="{{ route('admin.catalogue-units.destroy', $unit) }}" 
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette unité ?')"
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
                        @if($units->hasPages())
                            <div class="card-footer bg-white border-top p-4">
                                {{ $units->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucune unité trouvée</h5>
                            <p class="text-muted mb-3">Commencez par créer votre première unité</p>
                            <a href="{{ route('admin.catalogue-units.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer une unité
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cards en bas : Statistiques et Actions rapides -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-12">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $stats['total'] }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $stats['active'] }}</h4>
                                <small class="text-muted">Actives</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $stats['with_content'] }}</h4>
                                <small class="text-muted">Avec contenu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.catalogue-units.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle unité
                        </a>
                        <a href="{{ route('admin.catalogue-sections.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-layer-group me-2"></i>Gérer les sections
                        </a>
                        <a href="{{ route('admin.catalogue-modules.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-th me-2"></i>Gérer les modules
                        </a>
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

    // Filtre dynamique section -> modules
    const filterSection = document.getElementById('filter_section');
    const filterModule = document.getElementById('filter_module');
    
    if (filterSection && filterModule) {
        filterSection.addEventListener('change', function() {
            const sectionId = this.value;
            
            filterModule.innerHTML = '<option value="">Tous les modules</option>';
            filterModule.disabled = true;
            
            if (!sectionId) return;
            
            fetch(`{{ route('admin.catalogue-units.api.modules-by-section') }}?section_id=${sectionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(module => {
                            const option = document.createElement('option');
                            option.value = module.id;
                            option.textContent = module.name;
                            filterModule.appendChild(option);
                        });
                        filterModule.disabled = false;
                    }
                })
                .catch(error => console.error('Erreur:', error));
        });
    }
});
</script>
@endpush