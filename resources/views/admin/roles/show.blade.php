@extends('layouts.admin')

@section('title', 'Detail du rôle')
@section('page-title', $role->display_name ?? $role->name)
@section('page-description', 'Details du rôle et permissions')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center text-white" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user-shield fs-3"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $role->display_name ?? $role->name }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="opacity-75">{{ $role->name }}</small>
                                    @if($role->is_default)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-star me-1"></i>Par defaut
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">
                                Niveau {{ $role->level }}
                            </span>
                            <span class="badge bg-info">
                                {{ $role->users()->count() }} utilisateur(s)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Nom technique</small>
                                <strong>{{ $role->name }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-success border-3 ps-3">
                                <small class="text-muted d-block">Niveau d'autorite</small>
                                <strong>{{ $role->level }}/100</strong>
                            </div>
                        </div>
                    </div>

                    @if($role->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $role->description }}
                            </div>
                        </div>
                    @endif

                    <!-- Permissions par groupe -->
                    @if($role->permissions()->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-key me-2 text-primary"></i>
                                Permissions ({{ $role->permissions()->count() }})
                            </h6>

                            @php
                                $permissionGroups = $role->permissions->groupBy('group');
                            @endphp

                            <div class="row g-3">
                                @foreach($permissionGroups as $group => $permissions)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-header bg-light p-3">
                                                <h6 class="mb-0 text-capitalize">
                                                    <i class="fas fa-folder me-2 text-info"></i>
                                                    {{ str_replace('_', ' ', $group ?: 'General') }}
                                                    <span class="badge bg-info-subtle text-info ms-2">{{ $permissions->count() }}</span>
                                                </h6>
                                            </div>
                                            <div class="card-body p-3">
                                                @foreach($permissions as $permission)
                                                    <div class="d-flex align-items-start mb-2">
                                                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                                        <div>
                                                            <strong>{{ $permission->name }}</strong>
                                                            @if($permission->description)
                                                                <br><small class="text-muted">{{ $permission->description }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Aucune permission assignee</strong><br>
                                Ce rôle n'a aucune permission. Les utilisateurs avec ce rôle auront un acces tres limite.
                            </div>
                        </div>
                    @endif

                    <!-- Utilisateurs avec ce rôle -->
                    @if($role->users()->count() > 0)
                        <div class="border-top pt-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-semibold mb-0">
                                    <i class="fas fa-users me-2"></i>Utilisateurs avec ce rôle
                                </h6>
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $role->users()->count() }} utilisateur(s)
                                </span>
                            </div>

                            <div class="list-group list-group-flush">
                                @foreach($role->users()->latest()->take(10)->get() as $user)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if($user->avatar)
                                                        <img src="{{ $user->avatar }}" 
                                                             class="rounded-circle" 
                                                             style="width: 40px; height: 40px; object-fit: cover;" 
                                                             alt="">
                                                    @else
                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" 
                                                             style="width: 40px; height: 40px;">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    <div class="small text-muted">
                                                        {{ $user->email }}
                                                        @if($user->username)
                                                            • @{{ $user->username }}
                                                        @endif
                                                    </div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Inscrit {{ $user->created_at?->diffForHumans() ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ $user->status === 'active' ? 'Actif' : 'Inactif' }}
                                                </span>
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($role->users()->count() > 10)
                                <div class="mt-3">
                                    <a href="{{ route('admin.users.index', ['role' => $role->id]) }}" class="btn btn-sm btn-outline-info">
                                        Voir tous les utilisateurs ({{ $role->users()->count() }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="border-top pt-4">
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                <h6>Aucun utilisateur</h6>
                                <p class="text-muted mb-0">Ce rôle n'est assigne A aucun utilisateur.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Statistiques detaillees -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                <div class="fw-bold text-primary fs-4">{{ $role->users()->count() }}</div>
                                <small class="text-muted">Utilisateurs</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <div class="fw-bold text-success fs-4">{{ $role->permissions()->count() }}</div>
                                <small class="text-muted">Permissions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                <div class="fw-bold text-warning fs-4">{{ $role->level }}</div>
                                <small class="text-muted">Niveau</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                <div class="fw-bold text-info fs-4">{{ $role->users()->where('status', 'active')->count() }}</div>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparaison avec autres rôles -->
            @php
                $otherRoles = \App\Models\Role::where('id', '!=', $role->id)->withCount(['users', 'permissions'])->get();
            @endphp
            
            @if($otherRoles->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white p-3">
                        <h6 class="mb-0">
                            <i class="fas fa-balance-scale me-2"></i>Autres rôles
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        @foreach($otherRoles->take(5) as $otherRole)
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <a href="{{ route('admin.roles.show', $otherRole) }}" class="text-decoration-none">
                                        <strong>{{ $otherRole->display_name ?? $otherRole->name }}</strong>
                                    </a>
                                    <div class="small text-muted">
                                        {{ $otherRole->users_count }} utilisateurs • {{ $otherRole->permissions_count }} permissions
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark">Niv. {{ $otherRole->level }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Historique -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Historique
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 small">
                        <div class="col-12">
                            <small class="text-muted d-block">Date de creation</small>
                            <strong>{{ $role->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</strong>
                        </div>
                        
                        @if($role->updated_at && $role->updated_at != $role->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Derniere modification</small>
                                <strong>{{ $role->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('admin.users.index', ['role' => $role->id]) }}" class="btn btn-outline-info">
                            <i class="fas fa-users me-2"></i>Voir les utilisateurs
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                        </a>
                    </div>
                    
                    @if($role->name !== 'admin' && $role->name !== 'user')
                        <hr class="my-3">
                        
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Les utilisateurs assignes perdront leurs permissions.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    @endif
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

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}

.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>
@endpush