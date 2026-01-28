@extends('layouts.admin')

@section('title', 'Gestion des Sous-Catégories de Fiches')
@section('page-title', 'Sous-Catégories de Fiches')
@section('page-description', 'Gestion des sous-catégories de fiches')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-info text-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-layer-group me-2"></i>Liste des sous-catégories
                    </h5>
                    <small class="opacity-75">{{ $sousCategories->total() }} sous-catégorie(s) au total</small>
                </div>
                <a href="{{ route('admin.fiches-sous-categories.create') }}" class="btn bg-warning text-white p-2">
                    <i class="fas fa-plus me-2"></i>Nouvelle sous-catégorie
                </a>
            </div>
        </div>
        
        <!-- Filtres et recherche -->
        <div class="card-body border-bottom p-4 bg-light">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}" 
                               class="form-control border-start-0"
                               placeholder="Rechercher une sous-catégorie...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">Toutes les catégories parentes</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary text-white flex-fill">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        @if(request('search') || request('category'))
                            <a href="{{ route('admin.fiches-sous-categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques rapides -->
        @if(!request('search') && !request('category'))
            <div class="card-body border-bottom p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-layer-group text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['active'] ?? 0 }}</h6>
                                <small class="text-muted">Sous-catégories actives</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-pause-circle text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['inactive'] ?? 0 }}</h6>
                                <small class="text-muted">Sous-catégories inactives</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-hashtag text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['total'] ?? 0 }}</h6>
                                <small class="text-muted">Total sous-catégories</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tableau -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 px-4 py-3">Sous-catégorie</th>
                        <th class="border-0 py-3">Catégorie parente</th>
                        <th class="border-0 py-3 text-center">Fiches</th>
                        <th class="border-0 py-3 text-center">Statut</th>
                        <th class="border-0 py-3 text-center">Ordre</th>
                        <th class="border-0 py-3">Créé le</th>
                        <th class="border-0 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sousCategories as $sousCategory)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        @if($sousCategory->image)
                                            <img src="{{ $sousCategory->image }}" 
                                                 class="rounded" 
                                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                                 alt="{{ $sousCategory->name }}">
                                        @else
                                            <div class="bg-info bg-opacity-10 rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-layer-group text-info fs-5"></i>
                                            </div>
                                        @endif
                                        @if(!$sousCategory->is_active)
                                            <span class="position-absolute top-0 start-100 translate-middle badge bg-warning">
                                                <i class="fas fa-pause" style="font-size: 8px;"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $sousCategory->name }}</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted">{{ $sousCategory->slug }}</small>
                                            @if($sousCategory->description)
                                                <span class="badge bg-light text-dark" title="{{ $sousCategory->description }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($sousCategory->category)
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="fas fa-folder me-1"></i>{{ $sousCategory->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold {{ $sousCategory->fiches_count > 0 ? 'text-primary' : 'text-muted' }}">
                                        {{ $sousCategory->fiches_count }}
                                    </span>
                                    @if($sousCategory->fiches_count > 0)
                                        <a href="{{ route('admin.fiches.index', ['sous_category' => $sousCategory->id]) }}" 
                                           class="small text-decoration-none">
                                            Voir fiches
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-{{ $sousCategory->is_active ? 'success' : 'warning' }}-subtle text-{{ $sousCategory->is_active ? 'success' : 'warning' }}">
                                    <i class="fas fa-{{ $sousCategory->is_active ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                    {{ $sousCategory->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                @if($sousCategory->sort_order)
                                    <span class="badge bg-light text-dark border">{{ $sousCategory->sort_order }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="text-muted small">
                                    @if($sousCategory->created_at)
                                        {{ $sousCategory->created_at->format('d/m/Y') }}
                                        <br>{{ $sousCategory->created_at->format('H:i') }}
                                    @else
                                        <em class="text-muted">Date inconnue</em>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary border-0" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" 
                                               href="{{ route('admin.fiches-sous-categories.show', $sousCategory) }}">
                                                <i class="fas fa-eye me-2 text-info"></i>Voir détails
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" 
                                               href="{{ route('admin.fiches-sous-categories.edit', $sousCategory) }}">
                                                <i class="fas fa-edit me-2 text-primary"></i>Modifier
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" 
                                                  action="{{ route('admin.fiches-sous-categories.destroy', $sousCategory) }}" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette sous-catégorie ? Cette action est irréversible.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="dropdown-item d-flex align-items-center text-danger">
                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-layer-group fa-3x mb-3 opacity-25"></i>
                                    <h5>Aucune sous-catégorie trouvée</h5>
                                    @if(request('search') || request('category'))
                                        <p class="mb-3">Aucun résultat ne correspond à vos critères de recherche.</p>
                                        <a href="{{ route('admin.fiches-sous-categories.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left me-2"></i>Voir toutes les sous-catégories
                                        </a>
                                    @else
                                        <p class="mb-3">Créez votre première sous-catégorie pour organiser vos fiches</p>
                                        <a href="{{ route('admin.fiches-sous-categories.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Créer une sous-catégorie
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sousCategories->hasPages())
            <div class="card-footer bg-white border-top p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-muted">
                        Affichage de {{ $sousCategories->firstItem() }} à {{ $sousCategories->lastItem() }} 
                        sur {{ $sousCategories->total() }} résultat(s)
                    </div>
                    <div>
                        {{ $sousCategories->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>


.dropdown-menu {
    border: 0;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}
</style>
@endpush