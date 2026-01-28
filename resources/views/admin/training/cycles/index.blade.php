@extends('layouts.admin')

@section('title', 'Gestion des Cycles')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-sync-alt me-2"></i>Cycles d'Entraînement
                    </h5>
                    <small class="opacity-75">{{ $cycles->total() ?? $cycles->count() }} cycle(s) au total</small>
                </div>
                <a href="{{ route('admin.training.cycles.create') }}" class="btn bg-warning text-white p-2">
                    <i class="fas fa-plus me-2"></i>Nouveau cycle
                </a>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="card-body border-bottom p-4 bg-light">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="form-control"
                           placeholder="Rechercher...">
                </div>
                <div class="col-md-3">
                    <select name="objectif" class="form-select">
                        <option value="">Tous objectifs</option>
                        <option value="force" {{ request('objectif') === 'force' ? 'selected' : '' }}>Force</option>
                        <option value="endurance" {{ request('objectif') === 'endurance' ? 'selected' : '' }}>Endurance</option>
                        <option value="perte_poids" {{ request('objectif') === 'perte_poids' ? 'selected' : '' }}>Perte de poids</option>
                        <option value="prise_masse" {{ request('objectif') === 'prise_masse' ? 'selected' : '' }}>Prise de masse</option>
                        <option value="recuperation" {{ request('objectif') === 'recuperation' ? 'selected' : '' }}>Récupération</option>
                        <option value="mixte" {{ request('objectif') === 'mixte' ? 'selected' : '' }}>Mixte</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            @if($cycles->count() > 0)
                <div class="row g-0">
                    @foreach($cycles as $cycle)
                        <div class="col-12">
                            <div class="border-bottom p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            @if($cycle->image)
                                                <img src="{{ $cycle->image }}" 
                                                     class="rounded me-3" 
                                                     style="width: 60px; height: 45px; object-fit: cover;" 
                                                     alt="">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 45px;">
                                                    <i class="fas fa-sync-alt text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.training.cycles.show', $cycle) }}" 
                                                       class="text-decoration-none">
                                                        {{ $cycle->titre }}
                                                    </a>
                                                </h6>
                                                @if($cycle->description)
    <small class="text-muted">{!! Str::limit(strip_tags($cycle->description), 80)  !!}</small>
@endif
                                                <div class="mt-1">
                                                    <span class="badge bg-primary">{{ $cycle->objectif_label }}</span>
                                                    @if($cycle->duree_semaines)
                                                        <span class="badge bg-info">{{ $cycle->duree_semaines_formattee }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 text-center">
                                        <h5 class="mb-1 text-primary">{{ $cycle->getTotalSeances() }}</h5>
                                        <small class="text-muted">Séances</small>
                                    </div>
                                    
                                    <div class="col-md-2 text-center">
                                        <span class="badge bg-{{ $cycle->is_active ? 'success' : 'secondary' }}">
                                            {{ $cycle->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-2 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.training.cycles.show', $cycle) }}">
                                                        <i class="fas fa-eye me-2"></i>Voir
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.training.cycles.edit', $cycle) }}">
                                                        <i class="fas fa-edit me-2"></i>Modifier
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" 
                                                          action="{{ route('admin.training.cycles.destroy', $cycle) }}" 
                                                          onsubmit="return confirm('Êtes-vous sûr ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash me-2"></i>Supprimer
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($cycles->hasPages())
                    <div class="card-footer">
                        {{ $cycles->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-sync-alt fa-3x text-muted mb-3"></i>
                    <h5>Aucun cycle trouvé</h5>
                    <a href="{{ route('admin.training.cycles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer un cycle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection