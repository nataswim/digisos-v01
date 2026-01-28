@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Utilisateurs')
@section('page-description', 'Gestion des comptes utilisateurs')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-4">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Liste des utilisateurs</h5>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Nouvel utilisateur
                </a>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="card-body border-bottom p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               class="form-control border-start-0"
                               placeholder="Rechercher un utilisateur...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Tous les rôles</option>
                        @foreach(\App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        @if($search || request('role'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Tableau -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 px-4 py-3">Utilisateur</th>
                        <th class="border-0 py-3">Rôle</th>
                        <th class="border-0 py-3">Statut</th>
                        <th class="border-0 py-3">Derniere connexion</th>
                        <th class="border-0 py-3">Inscription</th>
                        <th class="border-0 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 45px; height: 45px; font-size: 16px;">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;" alt="">
                                        @else
                                            {{ substr($user->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                        @if($user->username)
                                            <br><small class="text-info">@{{ $user->username }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($user->role)
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $user->role->display_name }}
                                    </span>
                                @else
                                    <span class="text-muted">Aucun rôle</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}-subtle text-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                    {{ $user->status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($user->last_login_at)
                                    <div class="text-muted">
                                        {{ $user->last_login_at->format('d/m/Y') }}
                                        <br><small>{{ $user->last_login_at->format('H:i') }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">Jamais connecte</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="text-muted">
                                    {{ $user->created_at->format('d/m/Y') }}
                                    <br><small>{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td class="py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                                <i class="fas fa-eye me-2"></i>Voir le profil
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                                <i class="fas fa-edit me-2"></i>Modifier
                                            </a>
                                        </li>
                                        @if($user->id !== auth()->id())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="dropdown-item text-danger"
                                                            data-confirm="delete">
                                                        <i class="fas fa-trash me-2"></i>Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-2x mb-3"></i>
                                    <div>Aucun utilisateur trouve</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
@if($users->hasPages())
    <div class="card-footer bg-white border-top p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted">
                Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} 
                sur {{ $users->total() }} résultat(s)
            </div>
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
    </div>
</div>
@endsection