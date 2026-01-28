@extends('layouts.admin')

@section('title', 'Gestion des Categories')
@section('page-title', 'Categories')
@section('page-description', 'Gestion des categories d\'articles')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-info text-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-folder me-2"></i>Liste des categories
                    </h5>
                    <small class="opacity-75">{{ $categories->total() }} categorie(s) au total</small>
                </div>
                <a href="{{ route('admin.categories.create') }}" class="btn bg-warning text-white p-2">
                    <i class="fas fa-plus me-2"></i>Nouvelle categorie
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
                               placeholder="Rechercher une categorie...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="group" class="form-select">
                        <option value="">Tous les groupes</option>
                        @foreach($groups ?? [] as $group)
                            <option value="{{ $group }}" {{ request('group') === $group ? 'selected' : '' }}>
                                {{ $group }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary text-white flex-fill">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        @if(request('search') || request('status') || request('group'))
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques rapides -->
        @if(!request()->hasAny(['search', 'status', 'group']))
            <div class="card-body border-bottom p-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-folder text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['active'] ?? 0 }}</h6>
                                <small class="text-muted">Categories actives</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-folder-open text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['inactive'] ?? 0 }}</h6>
                                <small class="text-muted">Categories inactives</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-file-alt text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['total_posts'] ?? 0 }}</h6>
                                <small class="text-muted">Articles totaux</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-layer-group text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['groups'] ?? 0 }}</h6>
                                <small class="text-muted">Groupes</small>
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
                        <th class="border-0 px-4 py-3">
                            <a href="{{ route('admin.categories.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-decoration-none text-dark d-flex align-items-center">
                                Categorie
                                <i class="fas fa-sort ms-2 text-muted"></i>
                            </a>
                        </th>
                        <th class="border-0 py-3">Groupe</th>
                        <th class="border-0 py-3 text-center">Articles</th>
                        <th class="border-0 py-3 text-center">Statut</th>
                        <th class="border-0 py-3 text-center">Ordre</th>
                        <th class="border-0 py-3">Cree le</th>
                        <th class="border-0 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        @if($category->image)
                                            <img src="{{ $category->image }}" 
                                                 class="rounded" 
                                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                                 alt="{{ $category->name }}">
                                        @else
                                            <div class="bg-info bg-opacity-10 rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-folder text-info fs-5"></i>
                                            </div>
                                        @endif
                                        @if($category->status === 'inactive')
                                            <span class="position-absolute top-0 start-100 translate-middle badge bg-warning">
                                                <i class="fas fa-pause" style="font-size: 8px;"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $category->name }}</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted">{{ $category->slug }}</small>
                                            @if($category->description)
                                                <span class="badge bg-light text-dark" title="{{ $category->description }}">
                                                    <i class="fas fa-water"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($category->group_name)
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="fas fa-layer-group me-1"></i>{{ $category->group_name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold {{ $category->posts_count > 0 ? 'text-primary' : 'text-muted' }}">
                                        {{ $category->posts_count ?? $category->posts()->count() }}
                                    </span>
                                    @if(($category->posts_count ?? $category->posts()->count()) > 0)
                                        <a href="{{ route('admin.posts.index', ['category' => $category->id]) }}" 
                                           class="small text-decoration-none">
                                            Voir articles
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $category->status === 'active' ? 'success' : 'warning' }}">
                                    <i class="fas fa-{{ $category->status === 'active' ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                    {{ $category->status === 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                @if($category->order)
                                    <span class="badge bg-light text-dark border">{{ $category->order }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3">
   <td class="py-3">
    <div class="text-muted small">
        @if($category->created_at)
            {{ $category->created_at->format('d/m/Y') }}
            <br>{{ $category->created_at->format('H:i') }}
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
                                               href="{{ route('admin.categories.show', $category) }}">
                                                <i class="fas fa-eye me-2 text-info"></i>Voir details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" 
                                               href="{{ route('admin.categories.edit', $category) }}">
                                                <i class="fas fa-edit me-2 text-primary"></i>Modifier
                                            </a>
                                        </li>
                                        <li>
                                           <a class="dropdown-item d-flex align-items-center" 
   href="{{ route('posts.public.category', $category) }}" 
   target="_blank">
    <i class="fas fa-external-link-alt me-2 text-success"></i>Voir sur le site
</a>

                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" 
                                                  action="{{ route('admin.categories.destroy', $category) }}" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette categorie ? Cette action supprimera egalement tous les articles associes.')">
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
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                    <h5>Aucune categorie trouvee</h5>
                                    @if(request()->hasAny(['search', 'status', 'group']))
                                        <p class="mb-3">Aucun resultat ne correspond A vos criteres de recherche.</p>
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left me-2"></i>Voir toutes les categories
                                        </a>
                                    @else
                                        <p class="mb-3">Creez votre premiere categorie pour organiser vos articles</p>
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Creer une categorie
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
<style>


.table th a:hover {
    color: #0ea5e9 !important;
}

.dropdown-menu {
    border: 0;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .d-flex.align-items-center .me-3 {
        width: 40px !important;
        height: 40px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltip pour les descriptions
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush