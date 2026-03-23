@extends('layouts.admin')

@section('title', 'Fiches utilisateurs')

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-id-card me-2 text-primary"></i>Fiches utilisateurs
            </h4>
            <p class="text-muted mb-0">Gérez les fiches de profil enrichi de vos utilisateurs.</p>
        </div>
        <a href="{{ route('admin.user-profiles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Créer une fiche
        </a>
    </div>

    {{-- Tableau --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Utilisateur</th>
                            <th class="py-3">Rôle</th>
                            <th class="py-3">Fiche</th>
                            <th class="py-3">Visibilité</th>
                            <th class="py-3">Éléments</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                {{-- Identité --}}
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}"
                                                 alt="{{ $user->name }}"
                                                 class="rounded-circle"
                                                 style="width:40px;height:40px;object-fit:cover;">
                                        @else
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center fw-bold text-primary"
                                                 style="width:40px;height:40px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Rôle --}}
                                <td class="py-3">
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        {{ $user->role->display_name ?? $user->role->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- État fiche --}}
                                <td class="py-3">
                                    @if($user->userProfile)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-check me-1"></i>Créée
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="fas fa-clock me-1"></i>Aucune fiche
                                        </span>
                                    @endif
                                </td>

                                {{-- Visibilité --}}
                                <td class="py-3">
                                    @if($user->userProfile)
                                        @if($user->userProfile->is_visible)
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="fas fa-eye me-1"></i>Visible
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="fas fa-eye-slash me-1"></i>Masquée
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Nombre d'items --}}
                                <td class="py-3">
                                    @if($user->userProfile)
                                        <span class="badge bg-info-subtle text-info">
                                            {{ $user->userProfile->items->count() }} élément(s)
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="py-3 px-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if($user->userProfile)
                                            <a href="{{ route('admin.user-profiles.show', $user) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Voir la fiche">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.user-profiles.edit', $user) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Modifier la fiche">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('admin.user-profiles.destroy', $user) }}"
                                                  onsubmit="return confirm('Supprimer la fiche de {{ addslashes($user->name) }} ? Les éléments associés seront également supprimés.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Supprimer la fiche">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.user-profiles.create', ['user_id' => $user->id]) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="Créer la fiche">
                                                <i class="fas fa-plus me-1"></i>Créer
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-id-card fa-2x mb-3 d-block opacity-25"></i>
                                    Aucun utilisateur trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="card-footer bg-white border-top px-4 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
