@extends('layouts.admin')

@section('title', 'Gestion des Permissions')
@section('page-title', 'Permissions')
@section('page-description', 'Gestion des permissions systeme')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des permissions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-key me-2"></i>Permissions systeme
                            </h5>
                            <small class="opacity-75">{{ $permissions->total() ?? $permissions->count() }} permission(s) configuree(s)</small>
                        </div>
                        <a href="{{ route('admin.permissions.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouvelle permission
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
                                       placeholder="Rechercher une permission...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="group" class="form-select">
                                <option value="">Tous les groupes</option>
                                @foreach(\App\Models\Permission::whereNotNull('group')->distinct()->pluck('group') as $group)
                                    <option value="{{ $group }}" {{ request('group') === $group ? 'selected' : '' }}>
                                        {{ ucfirst($group) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="usage" class="form-select">
                                <option value="">Toutes</option>
                                <option value="used" {{ request('usage') === 'used' ? 'selected' : '' }}>Utilisees</option>
                                <option value="unused" {{ request('usage') === 'unused' ? 'selected' : '' }}>Non utilisees</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary text-white flex-fill">
                                    <i class="fas fa-filter"></i>
                                </button>
                                @if(request()->hasAny(['search', 'group', 'usage']))
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Permissions groupees -->
                <div class="card-body p-0">
                    @if($permissions->count() > 0)
                        @php
                            $groupedPermissions = $permissions->groupBy(function($permission) {
                                return $permission->group ?: 'general';
                            });
                        @endphp

                        @foreach($groupedPermissions as $groupName => $groupPermissions)
                            <div class="border-bottom">
                                <!-- Header du groupe -->
                                <div class="bg-light p-3 border-bottom">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 text-capitalize fw-semibold">
                                            <i class="fas fa-folder me-2 text-warning"></i>
                                            {{ str_replace('_', ' ', $groupName) }}
                                        </h6>
                                        <span class="badge bg-warning-subtle text-warning">
                                            {{ $groupPermissions->count() }} permissions
                                        </span>
                                    </div>
                                </div>

                                <!-- Permissions du groupe -->
                                @foreach($groupPermissions as $permission)
                                    <div class="p-4 border-bottom hover-bg">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <div class="bg-warning bg-opacity-10 rounded d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-key text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $permission->name }}</h6>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <small class="text-muted">{{ $permission->slug }}</small>
                                                            @if($permission->roles()->count() > 0)
                                                                <span class="badge bg-success-subtle text-success small">
                                                                    {{ $permission->roles()->count() }} rôles
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary small">
                                                                    Non utilisee
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if($permission->description)
                                                            <small class="text-muted d-block mt-1">{!! Str::limit($permission->description, 80) !!}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    @if($permission->roles()->count() > 0)
                                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                            @foreach($permission->roles()->limit(3)->get() as $role)
                                                                <span class="badge bg-primary-subtle text-primary small">
                                                                    {{ $role->display_name ?? $role->name }}
                                                                </span>
                                                            @endforeach
                                                            @if($permission->roles()->count() > 3)
                                                                <span class="badge bg-light text-dark small">
                                                                    +{{ $permission->roles()->count() - 3 }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <small class="text-muted">Aucun rôle</small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3 text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary border-0" 
                                                            data-bs-toggle="dropdown" 
                                                            aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ route('admin.permissions.show', $permission) }}">
                                                                <i class="fas fa-eye me-2 text-info"></i>Voir details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ route('admin.permissions.edit', $permission) }}">
                                                                <i class="fas fa-edit me-2 text-primary"></i>Modifier
                                                            </a>
                                                        </li>
                                                        @if($permission->roles()->count() == 0)
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form method="POST" 
                                                                      action="{{ route('admin.permissions.destroy', $permission) }}" 
                                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette permission ?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="dropdown-item d-flex align-items-center text-danger">
                                                                        <i class="fas fa-trash me-2"></i>Supprimer
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @else
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <span class="dropdown-item text-muted">
                                                                    <i class="fas fa-water me-2"></i>Utilisee par {{ $permission->roles()->count() }} rôle(s)
                                                                </span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <!-- Pagination -->
@if($permissions->hasPages())
    <div class="card-footer bg-white border-top p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted">
                Affichage de {{ $permissions->firstItem() }} à {{ $permissions->lastItem() }} 
                sur {{ $permissions->total() }} résultat(s)
            </div>
            {{ $permissions->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-key fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucune permission trouvee</h5>
                            @if(request()->hasAny(['search', 'group', 'usage']))
                                <p class="text-muted mb-3">Aucun resultat ne correspond A vos criteres de recherche.</p>
                                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Voir toutes les permissions
                                </a>
                            @else
                                <p class="text-muted mb-3">Creez vos premieres permissions pour contrôler les acces</p>
                                <a href="{{ route('admin.permissions.create') }}" class="btn btn-warning">
                                    <i class="fas fa-plus me-2"></i>Creer une permission
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar statistiques -->
        <div class="col-lg-4">
            <!-- Statistiques generales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $totalPermissions = \App\Models\Permission::count();
                        $usedPermissions = \App\Models\Permission::has('roles')->count();
                        $unusedPermissions = $totalPermissions - $usedPermissions;
                        $totalGroups = \App\Models\Permission::whereNotNull('group')->distinct('group')->count();
                    @endphp
                    
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $totalPermissions }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $usedPermissions }}</h4>
                                <small class="text-muted">Utilisees</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $unusedPermissions }}</h4>
                                <small class="text-muted">Non utilisees</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $totalGroups }}</h4>
                                <small class="text-muted">Groupes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Groupes les plus utilises -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-layer-group me-2"></i>Groupes populaires
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $popularGroups = \App\Models\Permission::selectRaw('`group`, count(*) as count')
                            ->whereNotNull('group')
                            ->groupBy('group')
                            ->orderBy('count', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($popularGroups as $group)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $group->group) }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success">{{ $group->count }}</span>
                                <a href="{{ route('admin.permissions.index', ['group' => $group->group]) }}" 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun groupe configure</p>
                    @endforelse
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-warning">
                            <i class="fas fa-plus me-2"></i>Nouvelle permission
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-shield me-2"></i>Gerer les rôles
                        </a>
                        @if($unusedPermissions > 0)
                            <button class="btn btn-outline-warning" onclick="showUnusedPermissions()">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ $unusedPermissions }} non utilisees
                            </button>
                        @endif
                        <button class="btn btn-outline-info" onclick="exportPermissions()">
                            <i class="fas fa-water me-2"></i>Exporter la liste
                        </button>
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

@push('scripts')
<script>
function showUnusedPermissions() {
    window.location.href = '{{ route("admin.permissions.index", ["usage" => "unused"]) }}';
}

function exportPermissions() {
    // Simuler l'export - A implementer dans le contrôleur
    alert('Fonctionnalite A implementer dans le contrôleur');
}
</script>
@endpush