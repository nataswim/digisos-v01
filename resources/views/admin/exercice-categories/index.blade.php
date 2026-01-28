@extends('layouts.admin')

@section('title', 'Catégories d\'Exercices')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-folder me-2"></i>Catégories d'Exercices
                            </h5>
                            <small class="opacity-75">{{ $categories->total() }} catégorie(s) au total</small>
                        </div>
                        <a href="{{ route('admin.exercice-categories.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouvelle catégorie
                        </a>
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="card-body border-bottom p-4 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search }}" 
                                   class="form-control"
                                   placeholder="Rechercher une catégorie...">
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                                @if($search)
                                    <a href="{{ route('admin.exercice-categories.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Catégorie</th>
                                        <th class="border-0 px-4 py-3 text-center">Sous-catégories</th>
                                        <th class="border-0 px-4 py-3 text-center">Exercices</th>
                                        <th class="border-0 px-4 py-3 text-center">Ordre</th>
                                        <th class="border-0 px-4 py-3 text-center">Statut</th>
                                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr class="border-bottom">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    @if($category->image)
                                                        <img src="{{ $category->image }}" 
                                                             class="rounded me-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover;" 
                                                             alt="">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-folder text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">
                                                            <a href="{{ route('admin.exercice-categories.show', $category) }}" 
                                                               class="text-decoration-none text-dark">
                                                                {{ $category->name }}
                                                            </a>
                                                        </h6>
                                                        <small class="text-muted">{{ $category->slug }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-info">{{ $category->sous_categories_count ?? 0 }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-primary">{{ $category->exercices_count ?? 0 }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-secondary">{{ $category->sort_order }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                                    {{ $category->is_active ? 'Actif' : 'Inactif' }}
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
                                                               href="{{ route('admin.exercice-categories.show', $category) }}">
                                                                <i class="fas fa-eye me-2"></i>Voir
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('admin.exercice-categories.edit', $category) }}">
                                                                <i class="fas fa-edit me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST" 
                                                                  action="{{ route('admin.exercice-categories.destroy', $category) }}" 
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

                        @if($categories->hasPages())
                            <div class="card-footer bg-white">
                                {{ $categories->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucune catégorie trouvée</h5>
                            <a href="{{ route('admin.exercice-categories.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Créer une catégorie
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
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.exercice-categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle catégorie
                        </a>
                        <a href="{{ route('admin.exercice-sous-categories.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-layer-group me-2"></i>Sous-catégories
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