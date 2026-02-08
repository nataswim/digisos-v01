@extends('layouts.admin')

@section('title', 'Gestion des Telechargements')
@section('page-title', 'Telechargements')
@section('page-description', 'Gestion des fichiers telechargeables')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des telechargements - Pleine largeur -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-water me-2"></i>Telechargements
                            </h5>
                            <small class="opacity-75">{{ $downloadables->total() ?? $downloadables->count() }} fichier(s) au total</small>
                        </div>
                        <a href="{{ route('admin.downloadables.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouveau fichier
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
                                       value="{{ request('search') }}" 
                                       class="form-control border-start-0"
                                       placeholder="Rechercher...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    Actifs
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    Inactifs
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="permission" class="form-select">
                                <option value="">Toute permission</option>
                                <option value="public" {{ request('permission') === 'public' ? 'selected' : '' }}>
                                    Public
                                </option>
                                <option value="visitor" {{ request('permission') === 'visitor' ? 'selected' : '' }}>
                                    Visiteur
                                </option>
                                <option value="user" {{ request('permission') === 'user' ? 'selected' : '' }}>
                                    Utilisateur
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select">
                                <option value="">Toute categorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'status', 'permission', 'category', 'format']))
                                    <a href="{{ route('admin.downloadables.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Actions en lot -->
                @if($downloadables->count() > 0)
                    <div class="card-body border-bottom p-3 bg-light">
                        <form method="POST" action="{{ route('admin.downloadables.bulk-action') }}" id="bulk-form">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                        <label class="form-check-label fw-semibold" for="select-all">
                                            Selectionner tout
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-2">
                                        <select name="action" class="form-select form-select-sm">
                                            <option value="">Actions en lot</option>
                                            <option value="activate">Activer</option>
                                            <option value="deactivate">Desactiver</option>
                                            <option value="feature">Mettre en avant</option>
                                            <option value="unfeature">Retirer de la une</option>
                                            <option value="delete">Supprimer</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            Appliquer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Telechargements -->
                <div class="card-body p-0">
                    @if($downloadables->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3" style="width: 40px;">
                                            <input type="checkbox" class="form-check-input" id="master-checkbox">
                                        </th>
                                        <th class="border-0 px-4 py-3">Fichier</th>
                                        <th class="border-0 px-4 py-3">Categorie</th>
                                        <th class="border-0 px-4 py-3">Format</th>
                                        <th class="border-0 px-4 py-3">Permission</th>
                                        <th class="border-0 px-4 py-3">Statut</th>
                                        <th class="border-0 px-4 py-3">Stats</th>
                                        <th class="border-0 px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($downloadables as $downloadable)
                                        <tr class="border-bottom hover-bg">
                                            <td class="px-4 py-3">
                                                <input type="checkbox" 
                                                       class="form-check-input item-checkbox" 
                                                       name="downloadables[]" 
                                                       value="{{ $downloadable->id }}"
                                                       form="bulk-form">
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-start">
                                                    @if($downloadable->cover_image)
                                                        <img src="{{ $downloadable->cover_image }}" 
                                                             class="rounded me-3" 
                                                             style="width: 50px; height: 60px; object-fit: cover;" 
                                                             alt="">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 60px;">
                                                            <i class="fas fa-file-{{ $downloadable->format === 'pdf' ? 'pdf' : ($downloadable->format === 'mp4' ? 'video' : 'alt') }} text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-fill">
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('admin.downloadables.show', $downloadable) }}" 
                                                               class="text-decoration-none text-dark">
                                                                {!! Str::limit($downloadable->title, 50) !!}
                                                            </a>
                                                        </h6>
                                                        @if($downloadable->file_size)
                                                            <small class="text-muted d-block">{{ $downloadable->file_size }}</small>
                                                        @endif
                                                        @if($downloadable->is_featured)
                                                            <span class="badge bg-warning-subtle text-warning">
                                                                <i class="fas fa-star me-1"></i>Vedette
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @if($downloadable->category)
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        {{ $downloadable->category->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Non categorise</span>
                                                @endif
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    {{ strtoupper($downloadable->format) }}
                                                </span>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @php
                                                    $permissionColors = [
                                                        'public' => 'success',
                                                        'visitor' => 'info', 
                                                        'user' => 'warning'
                                                    ];
                                                    $color = $permissionColors[$downloadable->user_permission] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">
                                                    <i class="fas fa-{{ $downloadable->user_permission === 'public' ? 'globe' : ($downloadable->user_permission === 'user' ? 'user' : 'eye') }} me-1"></i>
                                                    {{ ucfirst($downloadable->user_permission) }}
                                                </span>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <span class="badge bg-{{ $downloadable->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $downloadable->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($downloadable->status) }}
                                                </span>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-water me-1 text-muted"></i>
                                                    <span class="fw-bold text-primary">{{ number_format($downloadable->download_count) }}</span>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-inline-flex gap-1">
                                                    <!-- Bouton Voir -->
                                                    <a href="{{ route('admin.downloadables.show', $downloadable) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Voir"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Modifier -->
                                                    <a href="{{ route('admin.downloadables.edit', $downloadable) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Modifier"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Bouton Voir en ligne -->
                                                    @if($downloadable->status === 'active' && $downloadable->category)
                                                        <a href="{{ route('ebook.show', [$downloadable->category->slug, $downloadable->slug]) }}" 
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-success" 
                                                           title="Voir en ligne"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    @else
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-secondary" 
                                                                title="Non disponible en ligne"
                                                                data-bs-toggle="tooltip"
                                                                disabled>
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <!-- Bouton Dupliquer -->
                                                    <form method="POST" 
                                                          action="{{ route('admin.downloadables.duplicate', $downloadable) }}"
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-info" 
                                                                title="Dupliquer"
                                                                data-bs-toggle="tooltip">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Bouton Supprimer -->
                                                    <form method="POST" 
                                                          action="{{ route('admin.downloadables.destroy', $downloadable) }}" 
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')"
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
@if($downloadables->hasPages())
    <div class="card-footer bg-white border-top p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted">
                Affichage de {{ $downloadables->firstItem() }} à {{ $downloadables->lastItem() }} 
                sur {{ $downloadables->total() }} résultat(s)
            </div>
            {{ $downloadables->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-water fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucun telechargement trouve</h5>
                            @if(request()->hasAny(['search', 'status', 'permission', 'category', 'format']))
                                <p class="text-muted mb-3">Aucun resultat ne correspond A vos criteres de recherche.</p>
                                <a href="{{ route('admin.downloadables.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Voir tous les telechargements
                                </a>
                            @else
                                <p class="text-muted mb-3">Commencez par creer votre premier fichier telechargeable</p>
                                <a href="{{ route('admin.downloadables.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Creer un telechargement
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cards en bas : Statistiques et Actions rapides -->
        <div class="col-lg-6">
            <!-- Statistiques generales -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $totalDownloadables = \App\Models\Downloadable::count();
                        $activeDownloadables = \App\Models\Downloadable::where('status', 'active')->count();
                        $inactiveDownloadables = \App\Models\Downloadable::where('status', 'inactive')->count();
                        $featuredDownloadables = \App\Models\Downloadable::where('is_featured', true)->count();
                    @endphp
                    
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $totalDownloadables }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $activeDownloadables }}</h4>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $inactiveDownloadables }}</h4>
                                <small class="text-muted">Inactifs</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $featuredDownloadables }}</h4>
                                <small class="text-muted">Vedettes</small>
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
                        <a href="{{ route('admin.downloadables.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouveau fichier
                        </a>
                        <a href="{{ route('admin.download-categories.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-folder me-2"></i>Gerer les categories
                        </a>
                        <a href="{{ route('admin.downloadables.stats') }}" class="btn btn-outline-success">
                            <i class="fas fa-chart-line me-2"></i>Statistiques detaillees
                        </a>
                        <a href="{{ route('ebook.index') }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-external-link-alt me-2"></i>Voir le site public
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

    // Gestion des checkboxes
    const masterCheckbox = document.getElementById('master-checkbox');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');
    
    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            if (masterCheckbox) {
                masterCheckbox.checked = this.checked;
            }
        });
    }
    
    // Gestion du formulaire en lot
    const bulkForm = document.getElementById('bulk-form');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const actionSelect = document.querySelector('select[name="action"]');
            
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Veuillez selectionner au moins un element.');
                return;
            }
            
            if (!actionSelect.value) {
                e.preventDefault();
                alert('Veuillez choisir une action.');
                return;
            }
            
            if (actionSelect.value === 'delete') {
                if (!confirm('Êtes-vous sûr de vouloir supprimer les elements selectionnes ?')) {
                    e.preventDefault();
                }
            }
        });
    }
});
</script>
@endpush