@extends('layouts.user')

@section('title', 'Fiche de ' . $user->name)

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-id-card me-2 text-primary"></i>Fiche de {{ $user->name }}
            </h4>
            <p class="text-muted mb-0">Détail de la fiche enrichie et des éléments de contenu.</p>
        </div>
        <a href="{{ route('admin.user-profiles.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <div class="row g-4">

        {{-- Colonne principale --}}
        <div class="col-lg-8">

            {{-- Informations de base utilisateur --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}"
                                     alt="{{ $user->name }}"
                                     class="rounded-circle"
                                     style="width:60px;height:60px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center fw-bold text-primary"
                                     style="width:60px;height:60px;font-size:24px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary">
                            {{ $user->role->display_name ?? $user->role->name ?? 'Aucun rôle' }}
                        </span>
                    </div>
                </div>

                @if($user->userProfile)
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @if($user->userProfile->job_title)
                                <div class="col-md-6">
                                    <div class="border-start border-primary border-3 ps-3">
                                        <small class="text-muted d-block">Poste / Fonction</small>
                                        <strong>{{ $user->userProfile->job_title }}</strong>
                                    </div>
                                </div>
                            @endif
                            @if($user->userProfile->address)
                                <div class="col-md-6">
                                    <div class="border-start border-success border-3 ps-3">
                                        <small class="text-muted d-block">Adresse</small>
                                        <strong>{{ $user->userProfile->address }}</strong>
                                    </div>
                                </div>
                            @endif
                            @if($user->userProfile->website)
                                <div class="col-md-6">
                                    <div class="border-start border-info border-3 ps-3">
                                        <small class="text-muted d-block">Site web</small>
                                        <a href="{{ $user->userProfile->website }}" target="_blank" rel="noopener">
                                            {{ $user->userProfile->website }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="border-start border-warning border-3 ps-3">
                                    <small class="text-muted d-block">Visibilité</small>
                                    @if($user->userProfile->is_visible)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-eye me-1"></i>Visible
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="fas fa-eye-slash me-1"></i>Masquée
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($user->userProfile->admin_notes)
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-2">
                                    <i class="fas fa-lock me-2 text-warning"></i>Notes internes (admin)
                                </h6>
                                <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded p-3">
                                    {{ $user->userProfile->admin_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="card-body p-4 text-center text-muted">
                        <i class="fas fa-id-card fa-2x mb-3 d-block opacity-25"></i>
                        Aucune fiche créée pour cet utilisateur.
                        <div class="mt-3">
                            <a href="{{ route('admin.user-profiles.create', ['user_id' => $user->id]) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-2"></i>Créer la fiche
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Éléments de contenu (ProfileItems) --}}
            @if($user->userProfile)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white p-4 d-flex align-items-center justify-content-between">
                        <h6 class="fw-semibold mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>
                            Éléments de contenu
                            <span class="badge bg-primary-subtle text-primary ms-2">
                                {{ $user->userProfile->items->count() }}
                            </span>
                        </h6>
                        <a href="{{ route('admin.user-profiles.items.create', $user) }}"
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Ajouter un élément
                        </a>
                    </div>

                    <div class="card-body p-0">
                        @forelse($user->userProfile->items as $item)
                            <div class="p-4 border-bottom">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-light text-muted border" style="font-size:.7rem;">
                                                #{{ $item->sort_order }}
                                            </span>
                                            <h6 class="fw-semibold mb-0">{{ $item->title }}</h6>
                                        </div>
                                        <p class="text-muted mb-0" style="white-space:pre-line;">{{ $item->description }}</p>
                                    </div>
                                    <div class="d-flex gap-2 flex-shrink-0">
                                        <a href="{{ route('admin.user-profiles.items.edit', [$user, $item]) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.user-profiles.items.destroy', [$user, $item]) }}"
                                              onsubmit="return confirm('Supprimer cet élément ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center text-muted">
                                <i class="fas fa-inbox fa-2x mb-3 d-block opacity-25"></i>
                                Aucun élément de contenu ajouté pour l'instant.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar actions --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="fas fa-cogs me-2 text-secondary"></i>Actions
                    </h6>
                </div>
                <div class="card-body p-3 d-grid gap-2">
                    @if($user->userProfile)
                        <a href="{{ route('admin.user-profiles.edit', $user) }}"
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier la fiche
                        </a>
                        <a href="{{ route('admin.user-profiles.items.create', $user) }}"
                           class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>Ajouter un élément
                        </a>
                        <hr class="my-1">
                        <form method="POST"
                              action="{{ route('admin.user-profiles.destroy', $user) }}"
                              onsubmit="return confirm('Supprimer la fiche et tous ses éléments ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Supprimer la fiche
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.user-profiles.create', ['user_id' => $user->id]) }}"
                           class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Créer la fiche
                        </a>
                    @endif
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="btn btn-outline-secondary">
                        <i class="fas fa-user me-2"></i>Voir le compte
                    </a>
                    <a href="{{ route('admin.user-profiles.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                </div>
            </div>

            {{-- Méta --}}
            @if($user->userProfile)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white p-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="fas fa-info-circle me-2 text-info"></i>Informations
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3 small">
                            <div class="col-6">
                                <small class="text-muted d-block">Fiche créée le</small>
                                <strong>{{ $user->userProfile->created_at->format('d/m/Y') }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Dernière mise à jour</small>
                                <strong>{{ $user->userProfile->updated_at->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
