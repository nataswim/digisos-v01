@extends('layouts.admin')

@section('title', 'Gestion des Categories de Telechargement')
@section('page-title', 'Categories de Telechargement')
@section('page-description', 'Gestion des categories d\'eBooks et ressources')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des categories -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-folder me-2"></i>Categories de Telechargement
                            </h5>
                            <small class="opacity-75">{{ $categories->total() ?? $categories->count() }} categorie(s) au total</small>
                        </div>
                        <a href="{{ route('admin.download-categories.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouvelle categorie
                        </a>
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="card-body border-bottom p-4 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
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
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    Actives
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    Inactives
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'status']))
                                    <a href="{{ route('admin.download-categories.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Liste des categories -->
                <div class="card-body p-0">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Categorie</th>
                                        <th class="border-0 px-4 py-3">Statut</th>
                                        <th class="border-0 px-4 py-3">Telechargements</th>
                                        <th class="border-0 px-4 py-3">Createur</th>
                                        <th class="border-0 px-4 py-3">Date</th>
                                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr class="border-bottom hover-bg">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-start">
                                                    @if($category->icon)
                                                        <div class="me-3">
                                                            <i class="{{ $category->icon }} fa-2x text-primary"></i>
                                                        </div>
                                                    @else
                                                        <div class="bg-primary bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 48px; height: 48px;">
                                                            <i class="fas fa-folder text-primary"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-fill">
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('admin.download-categories.show', $category) }}" 
                                                               class="text-decoration-none text-dark">
                                                                {{ $category->name }}
                                                            </a>
                                                        </h6>
                                                        @if($category->short_description)
                                                            <small class="text-muted">{!! Str::limit($category->short_description, 80) !!}</small>
                                                        @endif
                                                        <div class="mt-1">
                                                            <small class="text-muted">
                                                                <i class="fas fa-link me-1"></i>{{ $category->slug }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $category->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($category->status) }}
                                                </span>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-info-subtle text-info me-2">
                                                        {{ $category->downloadables_count ?? $category->downloadables->count() }}
                                                    </span>
                                                    <small class="text-muted">fichier(s)</small>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                @if($category->creator)
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px;">
                                                            <span class="small fw-bold text-primary">
                                                                {{ substr($category->creator->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $category->creator->name }}</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Createur inconnu</span>
                                                @endif
                                            </td>
                                            
                                            <td class="px-4 py-3">
                                                <div class="d-flex flex-column">
                                                    <small class="fw-semibold">
                                                        {{ $category->created_at->format('d/m/Y') }}
                                                    </small>
                                                    <small class="text-muted">
                                                        {{ $category->created_at->format('H:i') }}
                                                    </small>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary border-0" 
                                                            data-bs-toggle="dropdown" 
                                                            aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ route('admin.download-categories.show', $category) }}">
                                                                <i class="fas fa-eye me-2 text-white"></i>Voir
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ route('admin.download-categories.edit', $category) }}">
                                                                <i class="fas fa-edit me-2 text-primary"></i>Modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ route('ebook.category', $category->slug) }}" 
                                                               target="_blank">
                                                                <i class="fas fa-external-link-alt me-2 text-success"></i>Voir sur le site
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST" 
                                                                  action="{{ route('admin.download-categories.destroy', $category) }}" 
                                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette categorie ?')">
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
                                    @endforeach
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
            {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucune categorie trouvee</h5>
                            @if(request()->hasAny(['search', 'status']))
                                <p class="text-muted mb-3">Aucun resultat ne correspond A vos criteres de recherche.</p>
                                <a href="{{ route('admin.download-categories.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Voir toutes les categories
                                </a>
                            @else
                                <p class="text-muted mb-3">Commencez par creer votre premiere categorie</p>
                                <a href="{{ route('admin.download-categories.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Creer une categorie
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar statistiques -->
        <div class="col-lg-3">
            <!-- Statistiques generales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $totalCategories = \App\Models\DownloadCategory::count();
                        $activeCategories = \App\Models\DownloadCategory::where('status', 'active')->count();
                        $inactiveCategories = \App\Models\DownloadCategory::where('status', 'inactive')->count();
                        $totalDownloads = \App\Models\Downloadable::count();
                    @endphp
                    
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $totalCategories }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $activeCategories }}</h4>
                                <small class="text-muted">Actives</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $inactiveCategories }}</h4>
                                <small class="text-muted">Inactives</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $totalDownloads }}</h4>
                                <small class="text-muted">Fichiers</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.download-categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle categorie
                        </a>
                        <a href="{{ route('admin.downloadables.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-water me-2"></i>Gerer les fichiers
                        </a>
                        <a href="{{ route('admin.downloadables.stats') }}" class="btn btn-outline-success">
                            <i class="fas fa-chart-line me-2"></i>Statistiques
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
</style>
@endpush