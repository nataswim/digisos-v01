@extends('layouts.admin')

@section('title', 'Gestion des Rôles')
@section('page-title', 'Rôles et Permissions')
@section('page-description', 'Gestion des rôles utilisateurs et permissions')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Liste des rôles -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-user-shield me-2"></i>Rôles du systeme
                            </h5>
                            <small class="opacity-75">{{ $roles->count() }} rôle(s) configure(s)</small>
                        </div>
                        <a href="{{ route('admin.roles.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouveau rôle
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @forelse($roles as $role)
                        <div class="border-bottom p-4 hover-bg">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <div class="bg-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'user' ? 'primary' : 'info') }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 55px; height: 55px;">
                                                <i class="fas fa-{{ $role->name === 'admin' ? 'crown' : ($role->name === 'user' ? 'user' : 'user-shield') }} text-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'user' ? 'primary' : 'info') }} fs-5"></i>
                                            </div>
                                            @if($role->is_default ?? false)
                                                <span class="position-absolute top-0 start-100 translate-middle badge bg-warning">
                                                    <i class="fas fa-star" style="font-size: 8px;"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $role->display_name ?? $role->name }}</h6>
                                            <div class="d-flex align-items-center gap-2">
                                                <small class="text-muted">{{ $role->name }}</small>
                                                @if($role->level ?? false)
                                                    <span class="badge bg-secondary-subtle text-secondary small">
                                                        Niveau {{ $role->level }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($role->description)
                                                <small class="text-muted d-block mt-1">{!! Str::limit($role->description, 80) !!}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <h5 class="mb-1 text-primary">{{ $role->users()->count() }}</h5>
                                            <small class="text-muted">Utilisateurs</small>
                                            @if($role->users()->count() > 0)
                                                <a href="{{ route('admin.users.index', ['role' => $role->id]) }}" 
                                                   class="small text-decoration-none">
                                                    Voir la liste
                                                </a>
                                            @endif
                                        </div>
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
                                                   href="{{ route('admin.roles.show', $role) }}">
                                                    <i class="fas fa-eye me-2 text-info"></i>Voir details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" 
                                                   href="{{ route('admin.roles.edit', $role) }}">
                                                    <i class="fas fa-edit me-2 text-primary"></i>Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" 
                                                   href="{{ route('admin.users.index', ['role' => $role->id]) }}">
                                                    <i class="fas fa-users me-2 text-success"></i>Utilisateurs ({{ $role->users()->count() }})
                                                </a>
                                            </li>
                                            @if($role->name !== 'admin' && $role->name !== 'user')
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" 
                                                          action="{{ route('admin.roles.destroy', $role) }}" 
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Les utilisateurs assignes perdront leurs permissions.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="dropdown-item d-flex align-items-center text-danger">
                                                            <i class="fas fa-trash me-2"></i>Supprimer
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Permissions du rôle -->
                            @if($role->permissions()->count() > 0)
                                <div class="mt-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <small class="text-muted me-2">
                                            <i class="fas fa-key me-1"></i>Permissions :
                                        </small>
                                        <span class="badge bg-success-subtle text-success">
                                            {{ $role->permissions()->count() }} autorisations
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($role->permissions()->limit(6)->get() as $permission)
                                            <span class="badge bg-secondary-subtle text-secondary small">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                        @if($role->permissions()->count() > 6)
                                            <span class="badge bg-light text-dark small">
                                                +{{ $role->permissions()->count() - 6 }} autres
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="mt-3">
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Aucune permission assignee
                                    </small>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucun rôle configure</h5>
                            <p class="text-muted mb-3">Creez des rôles pour organiser les permissions utilisateurs</p>
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Creer un rôle
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Repartition des utilisateurs
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $usersByRole = \App\Models\User::selectRaw('role_id, count(*) as count')
                            ->whereNotNull('role_id')
                            ->groupBy('role_id')
                            ->with('role')
                            ->get();
                        $totalUsers = \App\Models\User::count();
                        $usersWithoutRole = \App\Models\User::whereNull('role_id')->count();
                    @endphp
                    
                    @if($totalUsers > 0)
                        @foreach($usersByRole as $stat)
                            @php $percentage = round(($stat->count / $totalUsers) * 100, 1); @endphp
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="fw-semibold">{{ $stat->role->display_name ?? $stat->role->name ?? 'Rôle inconnu' }}</span>
                                    <br><small class="text-muted">{{ $stat->count }} utilisateurs</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">{{ $percentage }}%</div>
                                    <div class="progress" style="width: 60px; height: 4px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($usersWithoutRole > 0)
                            @php $percentage = round(($usersWithoutRole / $totalUsers) * 100, 1); @endphp
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="fw-semibold text-warning">Sans rôle</span>
                                    <br><small class="text-muted">{{ $usersWithoutRole }} utilisateurs</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-warning">{{ $percentage }}%</div>
                                    <div class="progress" style="width: 60px; height: 4px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="text-muted mb-0">Aucun utilisateur dans le systeme</p>
                    @endif
                </div>
            </div>

            <!-- Permissions systeme -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-white p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">
                            <i class="fas fa-key me-2"></i>Permissions systeme
                        </h6>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-light">
                            Gerer
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    @php
                        $totalPermissions = \App\Models\Permission::count();
                        $permissionGroups = \App\Models\Permission::selectRaw('`group`, count(*) as count')
                            ->groupBy('group')
                            ->orderBy('count', 'desc')
                            ->get();
                    @endphp
                    
                    @if($permissionGroups->count() > 0)
                        @foreach($permissionGroups->take(5) as $group)
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-capitalize">{{ str_replace('_', ' ', $group->group ?: 'General') }}</span>
                                <span class="badge bg-warning-subtle text-warning">{{ $group->count }}</span>
                            </div>
                        @endforeach
                        
                        @if($permissionGroups->count() > 5)
                            <div class="text-center mt-2">
                                <small class="text-muted">{{ $permissionGroups->count() - 5 }} autres groupes...</small>
                            </div>
                        @endif
                    @else
                        <p class="text-muted mb-0">Aucune permission configuree</p>
                    @endif
                    
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-water me-1"></i>
                            Total: {{ $totalPermissions }} permissions
                        </small>
                    </div>
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
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouveau rôle
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>Gerer les permissions
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-users me-2"></i>Voir les utilisateurs
                        </a>
                        @if($usersWithoutRole > 0)
                            <button class="btn btn-outline-warning" onclick="showAssignRoleHelper()">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ $usersWithoutRole }} sans rôle
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aide pour assignation -->
<div class="modal fade" id="assignRoleHelperModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle me-2"></i>Utilisateurs sans rôle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Il y a <strong>{{ $usersWithoutRole }}</strong> utilisateur(s) sans rôle assigne.</p>
                <p>Ces utilisateurs ont un acces tres limite au systeme. Vous pouvez :</p>
                <ul>
                    <li>Assigner des rôles individuellement depuis la <a href="{{ route('admin.users.index') }}">liste des utilisateurs</a></li>
                    <li>Creer un <a href="{{ route('admin.roles.create') }}">nouveau rôle</a> si necessaire</li>
                    <li>Configurer un <a href="{{ route('admin.roles.index') }}">rôle par defaut</a> pour les nouveaux utilisateurs</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Gerer les utilisateurs
                </a>
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

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.progress {
    background-color: #e9ecef;
}

@media (max-width: 768px) {
    .col-md-3 {
        margin-top: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function showAssignRoleHelper() {
    new bootstrap.Modal(document.getElementById('assignRoleHelperModal')).show();
}

// Confirmation pour suppression des rôles critiques
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('button[type="submit"]');
    deleteButtons.forEach(button => {
        if (button.textContent.includes('Supprimer')) {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                if (form && form.action.includes('/destroy')) {
                    e.preventDefault();
                    const confirmed = confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irreversible et les utilisateurs assignes perdront leurs permissions.');
                    if (confirmed) {
                        form.submit();
                    }
                }
            });
        }
    });
});
</script>
@endpush