@extends('layouts.admin')

@section('title', 'Detail de la permission')
@section('page-title', $permission->name)
@section('page-description', 'Details de la permission systeme')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white text-primary p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center text-white" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-key fs-3"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $permission->name }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="opacity-75">{{ $permission->slug }}</small>
                                    @if($permission->group)
                                        <span class="badge bg-light text-dark">{{ $permission->group }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-info">
                                {{ $permission->roles()->count() }} rôle(s)
                            </span>
                            @php
                                $totalUsers = $permission->roles()->withCount('users')->get()->sum('users_count');
                            @endphp
                            <span class="badge bg-success">
                                {{ $totalUsers }} utilisateur(s)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Identifiant technique</small>
                                <strong>{{ $permission->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-success border-3 ps-3">
                                <small class="text-muted d-block">Groupe</small>
                                <strong>{{ $permission->group ?: 'Aucun groupe' }}</strong>
                            </div>
                        </div>
                    </div>

                    @if($permission->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $permission->description }}
                            </div>
                        </div>
                    @endif

                    <!-- Rôles utilisant cette permission -->
                    @if($permission->roles()->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-user-shield me-2 text-primary"></i>
                                Rôles utilisant cette permission ({{ $permission->roles()->count() }})
                            </h6>

                            <div class="row g-3">
                                @foreach($permission->roles as $role)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary text-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user-shield text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $role->display_name ?? $role->name }}</strong>
                                                            <div class="small text-muted">
                                                                {{ $role->users()->count() }} utilisateur(s)
                                                                @if($role->level)
                                                                    • Niveau {{ $role->level }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Utilisateurs affectes indirectement -->
                        @php
                            $affectedUsers = collect();
                            foreach($permission->roles as $role) {
                                $affectedUsers = $affectedUsers->merge($role->users);
                            }
                            $affectedUsers = $affectedUsers->unique('id');
                        @endphp

                        @if($affectedUsers->count() > 0)
                            <div class="border-top pt-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-semibold mb-0">
                                        <i class="fas fa-users me-2"></i>Utilisateurs affectes
                                    </h6>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $affectedUsers->count() }} utilisateur(s)
                                    </span>
                                </div>

                                <div class="list-group list-group-flush">
                                    @foreach($affectedUsers->take(10) as $user)
                                        <div class="list-group-item border-0 px-0 py-2">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if($user->avatar)
                                                            <img src="{{ $user->avatar }}" 
                                                                 class="rounded-circle" 
                                                                 style="width: 32px; height: 32px; object-fit: cover;" 
                                                                 alt="">
                                                        @else
                                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" 
                                                                 style="width: 32px; height: 32px; font-size: 12px;">
                                                                {{ substr($user->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong>{{ $user->name }}</strong>
                                                        <div class="small text-muted">
                                                            {{ $user->email }} 
                                                            • via {{ $user->role->display_name ?? $user->role->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($affectedUsers->count() > 10)
                                    <div class="mt-3">
                                        <small class="text-muted">{{ $affectedUsers->count() - 10 }} utilisateur(s) supplementaire(s)...</small>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="border-top pt-4">
                            <div class="text-center py-4">
                                <i class="fas fa-user-shield fa-2x text-muted mb-3"></i>
                                <h6>Permission non utilisee</h6>
                                <p class="text-muted mb-3">Cette permission n'est assignee A aucun rôle.</p>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-shield me-2"></i>Assigner A un rôle
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Statistiques d'utilisation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Utilisation
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <div class="fw-bold text-primary fs-4">{{ $permission->roles()->count() }}</div>
                                <small class="text-muted">Rôles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <div class="fw-bold text-success fs-4">{{ $totalUsers }}</div>
                                <small class="text-muted">Utilisateurs</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($permission->group)
                        <div class="mt-3 pt-3 border-top">
                            @php
                                $sameGroupPermissions = \App\Models\Permission::where('group', $permission->group)
                                    ->where('id', '!=', $permission->id)
                                    ->count();
                            @endphp
                            <small class="text-muted">
                                <i class="fas fa-layer-group me-1"></i>
                                {{ $sameGroupPermissions }} autres permissions dans le groupe "{{ $permission->group }}"
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Permissions similaires -->
            @if($permission->group)
                @php
                    $similarPermissions = \App\Models\Permission::where('group', $permission->group)
                        ->where('id', '!=', $permission->id)
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($similarPermissions->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary text-white p-3">
                            <h6 class="mb-0">
                                <i class="fas fa-layer-group me-2"></i>Permissions similaires
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            @foreach($similarPermissions as $similar)
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <a href="{{ route('admin.permissions.show', $similar) }}" class="text-decoration-none">
                                            <strong>{{ $similar->name }}</strong>
                                        </a>
                                        <div class="small text-muted">{{ $similar->roles()->count() }} rôles</div>
                                    </div>
                                    <a href="{{ route('admin.permissions.show', $similar) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
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
                            <strong>{{ $permission->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</strong>
                        </div>
                        
                        @if($permission->updated_at && $permission->updated_at != $permission->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Derniere modification</small>
                                <strong>{{ $permission->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-shield me-2"></i>Gerer les rôles
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                        </a>
                    </div>
                    
                    @if($permission->roles()->count() == 0)
                        <hr class="my-3">
                        
                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette permission ? Cette action est irreversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    @else
                        <hr class="my-3">
                        <small class="text-muted d-block text-center">
                            <i class="fas fa-water me-1"></i>
                            Permission utilisee par {{ $permission->roles()->count() }} rôle(s)
                        </small>
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