@extends('layouts.admin')

@section('title', 'Profil utilisateur')
@section('page-title', $user->name)
@section('page-description', 'Profil et details du compte')

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
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                         style="width: 60px; height: 60px; font-size: 24px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="opacity-75">{{ $user->email }}</small>
                                    @if($user->username)
                                        <span class="badge bg-light text-dark">@{{ $user->username }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">
                                {{ $user->role->display_name ?? $user->role->name ?? 'Aucun rôle' }}
                            </span>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                {{ $user->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations personnelles -->
                    <div class="row mb-4">
                        @if($user->first_name || $user->last_name)
                            <div class="col-md-6">
                                <div class="border-start border-primary border-3 ps-3">
                                    <small class="text-muted d-block">Nom complet</small>
                                    <strong>{{ trim($user->first_name . ' ' . $user->last_name) }}</strong>
                                </div>
                            </div>
                        @endif
                        
                        @if($user->phone)
                            <div class="col-md-6">
                                <div class="border-start border-success border-3 ps-3">
                                    <small class="text-muted d-block">Telephone</small>
                                    <strong>{{ $user->phone }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($user->date_of_birth)
                            <div class="col-md-6 mt-3">
                                <div class="border-start border-info border-3 ps-3">
                                    <small class="text-muted d-block">Date de naissance</small>
                                    <strong>{{ $user->date_of_birth->format('d/m/Y') }} ({{ $user->date_of_birth->age }} ans)</strong>
                                </div>
                            </div>
                        @endif

                        @if($user->locale || $user->timezone)
                            <div class="col-md-6 mt-3">
                                <div class="border-start border-warning border-3 ps-3">
                                    <small class="text-muted d-block">Localisation</small>
                                    <strong>
                                        @if($user->locale)
                                            {{ strtoupper($user->locale) }}
                                        @endif
                                        @if($user->timezone)
                                            ({{ $user->timezone }})
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($user->bio)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Biographie</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $user->bio }}
                            </div>
                        </div>
                    @endif

                    <!-- Activite recente -->
                    <div class="border-top pt-4">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-chart-line me-2 text-primary"></i>Activite
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="fw-bold text-primary fs-4">{{ $user->posts()->count() }}</div>
                                    <small class="text-muted">Articles crees</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="fw-bold text-success fs-4">{{ $user->login_count ?? 0 }}</div>
                                    <small class="text-muted">Connexions</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="fw-bold text-info fs-4">
                                        {{ $user->created_at->diffInDays() }}
                                    </div>
                                    <small class="text-muted">Jours depuis l'inscription</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Articles recents -->
                    @if($user->posts()->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-file-alt me-2"></i>Articles recents
                            </h6>
                            <div class="list-group list-group-flush">
                                @foreach($user->posts()->latest()->take(5)->get() as $post)
                                    <div class="list-group-item border-0 px-0 py-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <strong>{{ $post->name }}</strong>
                                                <div class="small text-muted">
                                                    {{ $post->created_at->format('d/m/Y') }}
                                                    @if($post->category)
                                                        • {{ $post->category->name }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning' }}-subtle text-{{ $post->status === 'published' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                                <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($user->posts()->count() > 5)
                                <div class="mt-3">
                                    <a href="{{ route('admin.posts.index', ['user' => $user->id]) }}" class="btn btn-sm btn-outline-info">
                                        Voir tous les articles ({{ $user->posts()->count() }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Rôle et permissions -->
            @if($user->role)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white p-3">
                        <h6 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>Rôle et permissions
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-shield-alt text-success"></i>
                            </div>
                            <div>
                                <strong>{{ $user->role->display_name ?? $user->role->name }}</strong>
                                @if($user->role->description)
                                    <div class="text-muted small">{{ $user->role->description }}</div>
                                @endif
                            </div>
                        </div>

                        @if($user->role->permissions()->count() > 0)
                            <div>
                                <small class="text-muted d-block mb-2">Permissions ({{ $user->role->permissions()->count() }})</small>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->role->permissions()->limit(10)->get() as $permission)
                                        <span class="badge bg-success-subtle text-success small">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                    @if($user->role->permissions()->count() > 10)
                                        <span class="badge bg-light text-dark small">
                                            +{{ $user->role->permissions()->count() - 10 }} autres
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Statistiques du compte -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 small">
                        <div class="col-6">
                            <small class="text-muted d-block">Derniere connexion</small>
                            @if($user->last_login_at)
                                <strong>{{ $user->last_login_at->format('d/m/Y H:i') }}</strong>
                                <div class="text-muted">{{ $user->last_login_at->diffForHumans() }}</div>
                            @else
                                <em class="text-muted">Jamais connecte</em>
                            @endif
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">IP de connexion</small>
                            <strong>{{ $user->last_login_ip ?? 'N/A' }}</strong>
                        </div>

                        <div class="col-6">
                            <small class="text-muted d-block">Inscription</small>
                            <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                        </div>

                        <div class="col-6">
                            <small class="text-muted d-block">Derniere MAJ</small>
                            @if($user->updated_at && $user->updated_at != $user->created_at)
                                <strong>{{ $user->updated_at->format('d/m/Y') }}</strong>
                            @else
                                <em class="text-muted">Jamais modifie</em>
                            @endif
                        </div>
                    </div>
                </div>
            </div>




<!-- Actions -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-3">
        <div class="d-grid gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Modifier le profil
            </a>
            
            @if($user->id !== auth()->id())
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-outline-{{ $user->status === 'active' ? 'warning' : 'success' }}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#toggleStatusModal">
                        <i class="fas fa-{{ $user->status === 'active' ? 'pause' : 'play' }} me-2"></i>
                        {{ $user->status === 'active' ? 'Desactiver' : 'Activer' }} le compte
                    </button>
                </div>
            @endif

            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour A la liste
            </a>
        </div>
        
        @if($user->id !== auth()->id())
            <hr class="my-3">
            
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte utilisateur ? Cette action est irreversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="fas fa-trash me-2"></i>Supprimer le compte
                </button>
            </form>
        @endif
    </div>
</div>








        </div>
    </div>
</div>




<!-- Modal de confirmation toggle status -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Modifier le statut du compte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir <strong>{{ $user->status === 'active' ? 'desactiver' : 'activer' }}</strong> le compte de <strong>{{ $user->name }}</strong> ?</p>
                @if($user->status === 'active')
                    <div class="alert alert-warning">
                        <i class="fas fa-water me-2"></i>
                        L'utilisateur ne pourra plus se connecter une fois le compte desactive.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                    <input type="hidden" name="status" value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">
                    <button type="submit" class="btn btn-{{ $user->status === 'active' ? 'warning' : 'success' }}">
                        <i class="fas fa-{{ $user->status === 'active' ? 'pause' : 'play' }} me-2"></i>
                        {{ $user->status === 'active' ? 'Desactiver' : 'Activer' }}
                    </button>
                </form>
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
</style>
@endpush