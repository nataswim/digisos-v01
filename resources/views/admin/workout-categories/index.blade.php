@extends('layouts.admin')

@section('title', 'Gestion des Catégories Workout')
@section('page-title', 'Catégories Workout')
@section('page-description', 'Gestion des catégories de workout')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-info text-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-folder me-2"></i>Liste des catégories
                    </h5>
                    <small class="opacity-75">{{ $categories->total() }} catégorie(s) au total</small>
                </div>
                <a href="{{ route('admin.workout-categories.create') }}" class="btn bg-warning text-white p-2">
                    <i class="fas fa-plus me-2"></i>Nouvelle catégorie
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
                               value="{{ request('search') }}" 
                               class="form-control border-start-0"
                               placeholder="Rechercher une catégorie...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="section" class="form-select">
                        <option value="">Toutes les sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary text-white flex-fill">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        @if(request()->hasAny(['search', 'section']))
                            <a href="{{ route('admin.workout-categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques rapides -->
        @if(!request()->hasAny(['search', 'section']))
            <div class="card-body border-bottom p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['active'] }}</h6>
                                <small class="text-muted">Catégories actives</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-pause-circle text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['inactive'] }}</h6>
                                <small class="text-muted">Catégories inactives</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-folder text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['total'] }}</h6>
                                <small class="text-muted">Total catégories</small>
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
                        <th class="border-0 px-4 py-3">Catégorie</th>
                        <th class="border-0 py-3">Section</th>
                        <th class="border-0 py-3 text-center">Workouts</th>
                        <th class="border-0 py-3 text-center">Statut</th>
                        <th class="border-0 py-3 text-center">Ordre</th>
                        <th class="border-0 py-3">Créé le</th>
                        <th class="border-0 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-folder text-info fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $category->slug }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($category->section)
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $category->section->name }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold {{ $category->workouts_count > 0 ? 'text-primary' : 'text-muted' }}">
                                        {{ $category->workouts_count }}
                                    </span>
                                    @if($category->workouts_count > 0)
                                        <a href="{{ route('admin.workouts.index', ['category' => $category->id]) }}" 
                                           class="small text-decoration-none">
                                            Voir workouts
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'warning' }}-subtle text-{{ $category->is_active ? 'success' : 'warning' }}">
                                    <i class="fas fa-{{ $category->is_active ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-light text-dark border">{{ $category->sort_order }}</span>
                            </td>
                            <td class="py-3">
                                <div class="text-muted small">
                                    {{ $category->created_at->format('d/m/Y') }}<br>{{ $category->created_at->format('H:i') }}
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
                                               href="{{ route('admin.workout-categories.show', $category) }}">
                                                <i class="fas fa-eye me-2 text-info"></i>Voir détails
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" 
                                               href="{{ route('admin.workout-categories.edit', $category) }}">
                                                <i class="fas fa-edit me-2 text-primary"></i>Modifier
                                            </a>
                                        </li>
                                        @if($category->is_active && $category->section)
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" 
                                                   href="{{ route('public.workouts.category', [$category->section, $category]) }}" 
                                                   target="_blank">
                                                    <i class="fas fa-external-link-alt me-2 text-success"></i>Voir sur le site
                                                </a>
                                            </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" 
                                                  action="{{ route('admin.workout-categories.destroy', $category) }}" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
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
                                    <i class="fas fa-folder fa-3x mb-3 opacity-25"></i>
                                    <h5>Aucune catégorie trouvée</h5>
                                    @if(request()->hasAny(['search', 'section']))
                                        <p class="mb-3">Aucun résultat ne correspond à vos critères de recherche.</p>
                                        <a href="{{ route('admin.workout-categories.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left me-2"></i>Voir toutes les catégories
                                        </a>
                                    @else
                                        <p class="mb-3">Créez votre première catégorie pour organiser vos workouts</p>
                                        <a href="{{ route('admin.workout-categories.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Créer une catégorie
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
        @if($categories->hasPages())
            <div class="card-footer bg-white border-top p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-muted">
                        Affichage de {{ $categories->firstItem() }} à {{ $categories->lastItem() }} 
                        sur {{ $categories->total() }} résultat(s)
                    </div>
                    <div>
                        {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')

@endpush