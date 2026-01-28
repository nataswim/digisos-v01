@extends('layouts.admin')

@section('title', 'Sous-catégories d\'Exercices')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-layer-group me-2"></i>Sous-catégories d'Exercices
                            </h5>
                            <small class="opacity-75">{{ $sousCategories->total() }} sous-catégorie(s) au total</small>
                        </div>
                        <a href="{{ route('admin.exercice-sous-categories.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouvelle sous-catégorie
                        </a>
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="card-body border-bottom p-4 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search }}" 
                                   class="form-control"
                                   placeholder="Rechercher une sous-catégorie...">
                        </div>
                        <div class="col-md-4">
                            <select name="category" class="form-select">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                                @if($search || $categoryId)
                                    <a href="{{ route('admin.exercice-sous-categories.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    @if($sousCategories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Sous-catégorie</th>
                                        <th class="border-0 px-4 py-3">Catégorie parente</th>
                                        <th class="border-0 px-4 py-3 text-center">Exercices</th>
                                        <th class="border-0 px-4 py-3 text-center">Ordre</th>
                                        <th class="border-0 px-4 py-3 text-center">Statut</th>
                                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sousCategories as $sousCategory)
                                        <tr class="border-bottom">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    @if($sousCategory->image)
                                                        <img src="{{ $sousCategory->image }}" 
                                                             class="rounded me-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover;" 
                                                             alt="">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-layer-group text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">
                                                            <a href="{{ route('admin.exercice-sous-categories.show', $sousCategory) }}" 
                                                               class="text-decoration-none text-dark">
                                                                {{ $sousCategory->name }}
                                                            </a>
                                                        </h6>
                                                        <small class="text-muted">{{ $sousCategory->slug }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($sousCategory->category)
                                                    <a href="{{ route('admin.exercice-categories.show', $sousCategory->category) }}" 
                                                       class="text-decoration-none">
                                                        {{ $sousCategory->category->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-primary">{{ $sousCategory->exercices_count ?? 0 }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-secondary">{{ $sousCategory->sort_order }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-{{ $sousCategory->is_active ? 'success' : 'secondary' }}">
                                                    {{ $sousCategory->is_active ? 'Actif' : 'Inactif' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary border-0" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('admin.exercice-sous-categories.show', $sousCategory) }}">
                                                                <i class="fas fa-eye me-2"></i>Voir
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('admin.exercice-sous-categories.edit', $sousCategory) }}">
                                                                <i class="fas fa-edit me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST" 
                                                                  action="{{ route('admin.exercice-sous-categories.destroy', $sousCategory) }}" 
                                                                  onsubmit="return confirm('Êtes-vous sûr ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($sousCategories->hasPages())
                            <div class="card-footer bg-white">
                                {{ $sousCategories->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-layer-group fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucune sous-catégorie trouvée</h5>
                            <a href="{{ route('admin.exercice-sous-categories.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Créer une sous-catégorie
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar statistiques -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm">
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
                                <h4 class="fw-bold text-success mb-1">{{ $stats['active'] }}</h4>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-secondary bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-secondary mb-1">{{ $stats['inactive'] }}</h4>
                                <small class="text-muted">Inactifs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-warning text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.exercice-sous-categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle sous-catégorie
                        </a>
                        <a href="{{ route('admin.exercice-categories.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-folder me-2"></i>Catégories
                        </a>
                        <a href="{{ route('admin.training.exercices.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-running me-2"></i>Exercices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection