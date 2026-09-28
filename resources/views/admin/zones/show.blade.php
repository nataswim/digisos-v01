@extends('layouts.admin')

@section('title', 'Détails de la zone')
@section('page-title', $zone->name)
@section('page-description', 'Lieu physique précis — Niveau terminal de la hiérarchie')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.services.show', $zone->espace->structure->service) }}">
                        {{ $zone->espace->structure->service->name }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.structures.show', $zone->espace->structure) }}">
                        {{ $zone->espace->structure->name }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.espaces.show', $zone->espace) }}">
                        {{ $zone->espace->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $zone->name }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.zones.edit', $zone) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.zones.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-8">

            {{-- Informations principales --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informations principales
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <p class="text-muted small mb-1">Nom</p>
                            <p class="fw-semibold fs-5 mb-0">{{ $zone->name }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($zone->is_active)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Actif
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-times-circle me-1"></i>Inactif
                                </span>
                            @endif
                        </div>
                        @if($zone->capacity)
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Capacité</p>
                                <p class="mb-0">
                                    <i class="fas fa-users text-muted me-2"></i>{{ number_format($zone->capacity) }} personnes
                                </p>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Créé le</p>
                            <p class="mb-0">{{ $zone->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Modifié le</p>
                            <p class="mb-0">{{ $zone->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($zone->description)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-align-left me-2 text-secondary"></i>Description
                        </h5>
                    </div>
                    <div class="card-body p-4 ql-editor-content">
                        {!! $zone->description !!}
                    </div>
                </div>
            @endif

            {{-- Chemin hiérarchique --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0">
                        <i class="fas fa-sitemap me-2 text-primary"></i>Chemin hiérarchique complet
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center flex-wrap gap-2">

                        <a href="{{ route('admin.services.show', $zone->espace->structure->service) }}"
                           class="text-decoration-none">
                            <div class="card border-0 bg-primary text-white px-3 py-2 rounded-3">
                                <div class="small opacity-75">Service</div>
                                <div class="fw-semibold">{{ $zone->espace->structure->service->name }}</div>
                            </div>
                        </a>

                        <i class="fas fa-chevron-right text-muted"></i>

                        <a href="{{ route('admin.structures.show', $zone->espace->structure) }}"
                           class="text-decoration-none">
                            <div class="card border-0 bg-success text-white px-3 py-2 rounded-3">
                                <div class="small opacity-75">Structure</div>
                                <div class="fw-semibold">{{ $zone->espace->structure->name }}</div>
                            </div>
                        </a>

                        <i class="fas fa-chevron-right text-muted"></i>

                        <a href="{{ route('admin.espaces.show', $zone->espace) }}"
                           class="text-decoration-none">
                            <div class="card border-0 bg-warning text-dark px-3 py-2 rounded-3">
                                <div class="small opacity-75">Espace</div>
                                <div class="fw-semibold">{{ $zone->espace->name }}</div>
                            </div>
                        </a>

                        <i class="fas fa-chevron-right text-muted"></i>

                        <div class="card border-2 border-danger text-danger px-3 py-2 rounded-3">
                            <div class="small opacity-75">Zone</div>
                            <div class="fw-semibold">{{ $zone->name }}</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-4">

            @if($zone->photo)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2 text-warning"></i>Photo
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <img src="{{ $zone->photo }}"
                             alt="{{ $zone->name }}"
                             class="img-fluid rounded"
                             style="width:100%; height:200px; object-fit:cover;">
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-info"></i>Résumé hiérarchique
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-building text-primary"></i>
                            <span class="small">Service</span>
                        </div>
                        <a href="{{ route('admin.services.show', $zone->espace->structure->service) }}"
                           class="badge bg-primary text-decoration-none small text-truncate" style="max-width:140px;">
                            {{ $zone->espace->structure->service->name }}
                        </a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-layer-group text-success"></i>
                            <span class="small">Structure</span>
                        </div>
                        <a href="{{ route('admin.structures.show', $zone->espace->structure) }}"
                           class="badge bg-success text-decoration-none small text-truncate" style="max-width:140px;">
                            {{ $zone->espace->structure->name }}
                        </a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-th-large text-warning"></i>
                            <span class="small">Espace</span>
                        </div>
                        <a href="{{ route('admin.espaces.show', $zone->espace) }}"
                           class="badge bg-warning text-dark text-decoration-none small text-truncate" style="max-width:140px;">
                            {{ $zone->espace->name }}
                        </a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                            <span class="small">Zone (actuelle)</span>
                        </div>
                        <span class="badge bg-danger small text-truncate" style="max-width:140px;">
                            {{ $zone->name }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.zones.edit', $zone) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Modifier cette zone
                    </a>
                    <a href="{{ route('admin.espaces.show', $zone->espace) }}" class="btn btn-outline-warning">
                        <i class="fas fa-th-large me-2"></i>Voir l'espace parent
                    </a>
                    <a href="{{ route('admin.zones.create') }}?espace_id={{ $zone->espace_id }}"
                       class="btn btn-outline-secondary">
                        <i class="fas fa-plus me-2"></i>Nouvelle zone dans cet espace
                    </a>
                    <form action="{{ route('admin.zones.destroy', $zone) }}"
                          method="POST"
                          onsubmit="return confirm('Supprimer définitivement cette zone ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer cette zone
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
