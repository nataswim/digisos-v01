@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Utilisateurs')
@section('page-description', 'Gestion des comptes utilisateurs')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Liste des utilisateurs</h5>
                <a href="{{ route('admin.users.create') }}" class="btn bg-warning text-white p-2">
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
                        <th class="border-0 py-3">Dernière connexion</th>
                        <th class="border-0 py-3">Inscription</th>
                        <th class="border-0 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $allRoles = \App\Models\Role::orderBy('level')->get();
                    @endphp
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
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
    @if($user->id === auth()->id())
        {{-- L'utilisateur connecté ne peut pas changer son propre rôle --}}
        @if($user->role)
            <span class="badge bg-info-subtle text-info">
                {{ $user->role->display_name }}
            </span>
            <small class="text-muted d-block mt-1">(Votre compte)</small>
        @else
            <span class="text-muted">Aucun rôle</span>
        @endif
    @else
        {{-- Formulaire pour changer le rôle --}}
        <form method="POST" 
              action="{{ route('admin.users.update-role', $user) }}" 
              class="role-update-form"
              id="role-form-{{ $user->id }}">
            @csrf
            @method('PATCH')
            
            <select name="role_id" 
                    class="form-select form-select-sm role-selector-form" 
                    data-user-name="{{ $user->name }}"
                    data-form-id="role-form-{{ $user->id }}"
                    style="width: auto; min-width: 150px;">
                <option value="">Aucun rôle</option>
                @foreach($allRoles as $role)
                    <option value="{{ $role->id }}" 
                            {{ $user->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->display_name }}
                    </option>
                @endforeach
            </select>
        </form>
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
                                    <span class="text-muted">Jamais connecté</span>
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
                                    <div>Aucun utilisateur trouvé</div>
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

{{-- Toast pour les notifications --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="roleUpdateToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>
@endsection

{{-- Script simplifié sans AJAX --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du changement de rôle avec formulaire
    const roleSelectors = document.querySelectorAll('.role-selector-form');
    
    roleSelectors.forEach(selector => {
        // Stocker la valeur initiale
        const originalValue = selector.value;
        selector.dataset.originalValue = originalValue;
        
        selector.addEventListener('change', function() {
            const userName = this.dataset.userName;
            const formId = this.dataset.formId;
            const form = document.getElementById(formId);
            const newValue = this.value;
            const originalValue = this.dataset.originalValue;
            
            // Demander confirmation
            const confirmMessage = newValue 
                ? `Êtes-vous sûr de vouloir changer le rôle de ${userName} ?`
                : `Êtes-vous sûr de vouloir retirer le rôle de ${userName} ?`;
            
            if (confirm(confirmMessage)) {
                // Soumettre le formulaire
                form.submit();
            } else {
                // Restaurer la valeur originale
                this.value = originalValue;
            }
        });
    });
    
    // Gestion de la confirmation de suppression
    const deleteButtons = document.querySelectorAll('[data-confirm="delete"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush

{{-- Messages de succès/erreur avec session flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3" 
         role="alert" 
         style="z-index: 1050; max-width: 350px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
    <script>
        // Auto-fermer après 3 secondes
        setTimeout(function() {
            document.querySelector('.alert-success')?.remove();
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed bottom-0 end-0 m-3" 
         role="alert" 
         style="z-index: 1050; max-width: 350px;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
    <script>
        // Auto-fermer après 5 secondes
        setTimeout(function() {
            document.querySelector('.alert-danger')?.remove();
        }, 5000);
    </script>
@endif

@push('styles')
<style>
/* Styles pour le dropdown de rôle */
.role-selector {
    cursor: pointer;
    transition: all 0.2s ease;
}

.role-selector:hover:not(:disabled) {
    background-color: #f8f9fa;
    border-color: #0ea5e9;
}

.role-selector:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

/* Animation du spinner */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.2em;
}

/* Styles pour les toasts */
.toast {
    min-width: 300px;
}

.bg-success-subtle {
    background-color: #d1f2eb !important;
}

.bg-danger-subtle {
    background-color: #f8d7da !important;
}

/* Amélioration visuelle du tableau */
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.table td {
    vertical-align: middle;
}
</style>
@endpush