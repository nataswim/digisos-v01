@extends('layouts.admin')

@section('title', 'Détail de la catégorie')
@section('page-title', $workoutCategory->name)
@section('page-description', 'Détails de la catégorie')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-folder me-2"></i>{{ $workoutCategory->name }}
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $workoutCategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug</small>
                                <strong>{{ $workoutCategory->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start border-success border-3 ps-3">
                                <small class="text-muted d-block">Section</small>
                                <strong>{{ $workoutCategory->section->name ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start border-info border-3 ps-3">
                                <small class="text-muted d-block">Workouts</small>
                                <strong>{{ $workoutCategory->workouts_count }} workout(s)</strong>
                            </div>
                        </div>
                    </div>

                    @if($workoutCategory->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $workoutCategory->description }}
                            </div>
                        </div>
                    @endif

                    <!-- Liste des workouts -->
                    @if($workoutCategory->workouts->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">Workouts de cette catégorie ({{ $workoutCategory->workouts->count() }})</h6>
                            <div class="list-group">
                                @foreach($workoutCategory->workouts as $workout)
                                    <a href="{{ route('admin.workouts.show', $workout) }}" 
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="badge bg-primary me-2">#{{ $workout->pivot->order_number }}</span>
                                                <strong>{{ $workout->title }}</strong>
                                            </div>
                                            <span class="badge bg-info">{{ $workout->formatted_total }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Statut et organisation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Statut</small>
                            <span class="badge bg-{{ $workoutCategory->is_active ? 'success' : 'warning' }}-subtle text-{{ $workoutCategory->is_active ? 'success' : 'warning' }} fs-6">
                                {{ $workoutCategory->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Ordre</small>
                            <strong class="text-primary">{{ $workoutCategory->sort_order }}</strong>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Workouts</small>
                            <strong>{{ $workoutCategory->workouts_count }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Historique
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 small">
                        <div class="col-12">
                            <small class="text-muted d-block">Date de création</small>
                            <strong>{{ $workoutCategory->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($workoutCategory->updated_at && $workoutCategory->updated_at != $workoutCategory->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Dernière modification</small>
                                <strong>{{ $workoutCategory->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.workout-categories.edit', $workoutCategory) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($workoutCategory->is_active && $workoutCategory->section)
                            <a href="{{ route('public.workouts.category', [$workoutCategory->section, $workoutCategory]) }}" 
                               target="_blank" 
                               class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                            </a>
                        @endif
                        <a href="{{ route('admin.workout-categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" 
                          action="{{ route('admin.workout-categories.destroy', $workoutCategory) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>


.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush