@extends('layouts.admin')

@section('title', 'Gestion des Séries')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-list-ol me-2"></i>Séries d'Exercices
                    </h5>
                    <small class="opacity-75">{{ $series->total() ?? $series->count() }} série(s) au total</small>
                </div>
                <a href="{{ route('admin.training.series.create') }}" class="btn bg-warning text-white p-2">
                    <i class="fas fa-plus me-2"></i>Nouvelle série
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
                <div class="col-md-3">
                    <select name="exercice_id" class="form-select">
                        <option value="">Tous les exercices</option>
                        @foreach($exercices as $exercice)
                            <option value="{{ $exercice->id }}" {{ request('exercice_id') == $exercice->id ? 'selected' : '' }}>
                                {{ $exercice->titre }}
                            </option>
                        @endforeach
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
            @if($series->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Série</th>
                                <th class="px-4 py-3">Exercice</th>
                                <th class="px-4 py-3">Configuration</th>
                                <th class="px-4 py-3">Repos</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($series as $serie)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('admin.training.series.show', $serie) }}" 
                                                   class="text-decoration-none">
                                                    {{ $serie->nom ?: 'Série #' . $serie->id }}
                                                </a>
                                            </h6>
                                            @if($serie->consignes)
    <small class="text-muted">{!! Str::limit(strip_tags($serie->consignes), 50) !!}</small>
@endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.training.exercices.show', $serie->exercice) }}" 
                                           class="text-decoration-none">
                                            {{ $serie->exercice->titre }}
                                        </a>
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        <div class="small">
                                            @if($serie->repetitions)
                                                <div>{{ $serie->repetitions }} répétitions</div>
                                            @endif
                                            @if($serie->duree_secondes)
                                                <div>{{ gmdate('i:s', $serie->duree_secondes) }}</div>
                                            @endif
                                            @if($serie->distance_metres)
                                                <div>{{ $serie->distance_metres }}m</div>
                                            @endif
                                            @if($serie->poids_kg)
                                                <div>{{ $serie->poids_kg }}kg</div>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        <span class="badge bg-info">{{ $serie->repos_formate }}</span>
                                    </td>
                                    
                                    <td class="px-4 py-3">
                                        <span class="badge bg-{{ $serie->is_active ? 'success' : 'secondary' }}">
                                            {{ $serie->is_active ? 'Active' : 'Inactive' }}
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
                                                       href="{{ route('admin.training.series.show', $serie) }}">
                                                        <i class="fas fa-eye me-2"></i>Voir
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.training.series.edit', $serie) }}">
                                                        <i class="fas fa-edit me-2"></i>Modifier
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" 
                                                          action="{{ route('admin.training.series.destroy', $serie) }}" 
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

                @if($series->hasPages())
                    <div class="card-footer">
                        {{ $series->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-list-ol fa-3x text-muted mb-3"></i>
                    <h5>Aucune série trouvée</h5>
                    <a href="{{ route('admin.training.series.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer une série
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection