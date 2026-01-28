@extends('layouts.admin')

@section('title', 'Gestion des Séances')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-dumbbell me-2"></i>Séances d'Entraînement
                    </h5>
                    <small class="opacity-75">{{ $seances->total() ?? $seances->count() }} séance(s) au total</small>
                </div>
                <a href="{{ route('admin.training.seances.create') }}" class="btn bg-warning text-white p-2">
                    <i class="fas fa-plus me-2"></i>Nouvelle séance
                </a>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="card-body border-bottom p-4 bg-light">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="form-control"
                           placeholder="Rechercher...">
                </div>
                <div class="col-md-2">
                    <select name="niveau" class="form-select">
                        <option value="">Tous niveaux</option>
                        <option value="debutant" {{ request('niveau') === 'debutant' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediaire" {{ request('niveau') === 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="avance" {{ request('niveau') === 'avance' ? 'selected' : '' }}>Avancé</option>
                        <option value="special" {{ request('niveau') === 'special' ? 'selected' : '' }}>Spécial</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Tous types</option>
                        <option value="force" {{ request('type') === 'force' ? 'selected' : '' }}>Force</option>
                        <option value="cardio" {{ request('type') === 'cardio' ? 'selected' : '' }}>Cardio</option>
                        <option value="mixte" {{ request('type') === 'mixte' ? 'selected' : '' }}>Mixte</option>
                        <option value="recuperation" {{ request('type') === 'recuperation' ? 'selected' : '' }}>Récupération</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            @if($seances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Séance</th>
                                <th class="px-4 py-3">Type & Niveau</th>
                                <th class="px-4 py-3">Durée</th>
                                <th class="px-4 py-3">Séries</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seances as $seance)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-start">
                                            @if($seance->image)
                                                <img src="{{ $seance->image }}" 
                                                     class="rounded me-3" 
                                                     style="width: 50px; height: 40px; object-fit: cover;" 
                                                     alt="">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 40px;">
                                                    <i class="fas fa-dumbbell text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.training.seances.show', $seance) }}" 
                                                       class="text-decoration-none">
                                                        {{ $seance->titre }}
                                                    </a>
                                                </h6>
                                                @if($seance->description)
    <small class="text-muted">{!! Str::limit(strip_tags($seance->description), 60) !!}</small>
@endif
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-primary">{{ $seance->type_seance_label }}</span>
                                            <span class="badge bg-{{ $seance->niveau === 'debutant' ? 'success' : ($seance->niveau === 'avance' ? 'danger' : 'warning') }}">
                                                {{ $seance->niveau_label }}
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        @if($seance->duree_estimee_minutes)
                                            <span class="badge bg-info">{{ $seance->duree_estimee_formattee }}</span>
                                        @else
                                            <span class="text-muted">Non définie</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        <span class="badge bg-secondary">{{ $seance->getTotalExercices() }} séries</span>
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        <span class="badge bg-{{ $seance->is_active ? 'success' : 'secondary' }}">
                                            {{ $seance->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.training.seances.show', $seance) }}">
                                                        <i class="fas fa-eye me-2"></i>Voir
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.training.seances.edit', $seance) }}">
                                                        <i class="fas fa-edit me-2"></i>Modifier
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" 
                                                          action="{{ route('admin.training.seances.destroy', $seance) }}" 
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($seances->hasPages())
                    <div class="card-footer">
                        {{ $seances->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-dumbbell fa-3x text-muted mb-3"></i>
                    <h5>Aucune séance trouvée</h5>
                    <a href="{{ route('admin.training.seances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer une séance
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection