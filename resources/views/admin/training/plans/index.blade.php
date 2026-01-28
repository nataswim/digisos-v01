@extends('layouts.admin')

@section('title', 'Gestion des Plans')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-calendar-alt me-2"></i>Plans d'Entraînement
                            </h5>
                            <small class="opacity-75">{{ $plans->total() ?? $plans->count() }} plan(s) au total</small>
                        </div>
                        <a href="{{ route('admin.training.plans.create') }}" class="btn bg-warning text-white p-2">
                            <i class="fas fa-plus me-2"></i>Nouveau plan
                        </a>
                    </div>
                </div>
                
                <!-- Filtres -->
                <!-- Filtres -->
<div class="card-body border-bottom p-4 bg-light">
    <form method="GET" class="row g-3">
        <div class="col-md-8">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   class="form-control"
                   placeholder="Rechercher un plan...">
        </div>
        <div class="col-md-4">
            <div class="d-flex gap-1">
                <button type="submit" class="btn btn-primary text-white flex-fill">
                    <i class="fas fa-search me-2"></i>Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.training.plans.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

                <div class="card-body p-0">
                    @if($plans->count() > 0)
                        <div class="row g-0">
                            @foreach($plans as $plan)
                                <div class="col-12">
                                    <div class="border-bottom p-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start">
                                                    @if($plan->image)
                                                        <img src="{{ $plan->image }}" 
                                                             class="rounded me-3" 
                                                             style="width: 80px; height: 60px; object-fit: cover;" 
                                                             alt="">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 80px; height: 60px;">
                                                            <i class="fas fa-calendar-alt text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-fill">
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('admin.training.plans.show', $plan) }}" 
                                                               class="text-decoration-none text-dark">
                                                                {{ $plan->titre }}
                                                            </a>
                                                            @if($plan->is_featured)
                                                                <span class="badge bg-warning text-dark ms-2">
                                                                    <i class="fas fa-star me-1"></i>À la une
                                                                </span>
                                                            @endif
                                                        </h6>
                                                        @if($plan->description)
    <small class="text-muted d-block">{!! Str::limit(strip_tags($plan->description), 80) !!}</small>
@endif
                                                        <div class="d-flex gap-2 mt-2">
                                                            <span class="badge bg-{{ $plan->niveau === 'debutant' ? 'success' : ($plan->niveau === 'avance' ? 'danger' : 'warning') }}">
                                                                {{ $plan->niveau_label }}
                                                            </span>
                                                            <span class="badge bg-info">
                                                                {{ $plan->objectif_label }}
                                                            </span>
                                                            @if($plan->duree_semaines)
                                                                <span class="badge bg-secondary">
                                                                    {{ $plan->duree_semaines_formattee }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2 text-center">
                                                <div class="d-flex flex-column">
                                                    <h5 class="mb-1 text-primary">{{ $plan->cycles_count ?? 0 }}</h5>
                                                    <small class="text-muted">Cycles</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2 text-center">
                                                <div class="d-flex flex-column">
                                                    <h5 class="mb-1 text-success">{{ $plan->users_count ?? 0 }}</h5>
                                                    <small class="text-muted">Utilisateurs</small>
                                                    @if(($plan->users_count ?? 0) > 0)
                                                        <a href="{{ route('admin.training.plans.assignations', $plan) }}" 
                                                           class="small text-decoration-none">
                                                            Gérer
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2 text-end">
                                                <div class="d-flex flex-column gap-1 mb-2">
                                                    <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }}">
                                                        {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                    <span class="badge bg-{{ $plan->is_public ? 'info' : 'warning' }}">
                                                        {{ $plan->is_public ? 'Public' : 'Privé' }}
                                                    </span>
                                                </div>
                                                
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('admin.training.plans.show', $plan) }}">
                                                                <i class="fas fa-eye me-2"></i>Voir
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('admin.training.plans.edit', $plan) }}">
                                                                <i class="fas fa-edit me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('admin.training.plans.assignations', $plan) }}">
                                                                <i class="fas fa-users me-2"></i>Assignations ({{ $plan->users_count ?? 0 }})
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST" 
                                                                  action="{{ route('admin.training.plans.destroy', $plan) }}" 
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

                        @if($plans->hasPages())
                            <div class="card-footer">
                                {{ $plans->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <h5>Aucun plan trouvé</h5>
                            <a href="{{ route('admin.training.plans.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer un plan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar statistiques -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $totalPlans = \App\Models\Plan::count();
                        $activePlans = \App\Models\Plan::where('is_active', true)->count();
                        $publicPlans = \App\Models\Plan::where('is_public', true)->count();
                        $featuredPlans = \App\Models\Plan::where('is_featured', true)->count();
                    @endphp
                    
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $totalPlans }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $activePlans }}</h4>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $publicPlans }}</h4>
                                <small class="text-muted">Publics</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $featuredPlans }}</h4>
                                <small class="text-muted">À la une</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection