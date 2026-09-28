@extends('layouts.admin')

@section('title', 'Détails de la structure')
@section('page-title', $structure->name)
@section('page-description', 'Division fonctionnelle — Détails et espaces associés')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.services.show', $structure->service) }}">{{ $structure->service->name }}</a>
                </li>
                <li class="breadcrumb-item active">{{ $structure->name }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.structures.edit', $structure) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.structures.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Informations --}}
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
                            <p class="fw-semibold fs-5 mb-0">{{ $structure->name }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($structure->is_active)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Actif
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">Inactif</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Service parent</p>
                            <a href="{{ route('admin.services.show', $structure->service) }}"
                               class="badge bg-light text-dark border text-decoration-none fs-6">
                                <i class="fas fa-building me-1"></i>{{ $structure->service->name }}
                            </a>
                        </div>
                        @if($structure->capacity)
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Capacité</p>
                                <p class="mb-0">
                                    <i class="fas fa-users text-muted me-2"></i>{{ number_format($structure->capacity) }} personnes
                                </p>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Créé le</p>
                            <p class="mb-0">{{ $structure->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Modifié le</p>
                            <p class="mb-0">{{ $structure->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($structure->description)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-align-left me-2 text-secondary"></i>Description
                        </h5>
                    </div>
                    <div class="card-body p-4 ql-editor-content">
                        {!! $structure->description !!}
                    </div>
                </div>
            @endif

            {{-- Espaces associés --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-th-large me-2 text-success"></i>Espaces associés
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success rounded-pill">{{ $structure->espaces_count }}</span>
                        <a href="{{ route('admin.espaces.create') }}?structure_id={{ $structure->id }}"
                           class="btn btn-sm btn-outline-success">
                            <i class="fas fa-plus me-1"></i>Ajouter
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($structure->espaces as $espace)
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                @if($espace->photo)
                                    <img src="{{ $espace->photo }}"
                                         class="rounded" style="width:40px;height:40px;object-fit:cover;"
                                         alt="{{ $espace->name }}">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                         style="width:40px;height:40px;">
                                        <i class="fas fa-th-large text-muted small"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $espace->name }}</div>
                                    <div class="text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $espace->zones_count }} zone(s)
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($espace->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                                <a href="{{ route('admin.espaces.show', $espace) }}"
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.espaces.edit', $espace) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-th-large fa-2x mb-2 d-block opacity-25"></i>
                            Aucun espace associé.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            @if($structure->photo)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="mb-0"><i class="fas fa-image me-2 text-warning"></i>Photo</h6>
                    </div>
                    <div class="card-body p-3">
                        <img src="{{ $structure->photo }}"
                             alt="{{ $structure->name }}"
                             class="img-fluid rounded"
                             style="width:100%; height:200px; object-fit:cover;">
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-info"></i>Hiérarchie</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-building text-primary"></i>
                            <span>Service</span>
                        </div>
                        <a href="{{ route('admin.services.show', $structure->service) }}"
                           class="badge bg-primary text-decoration-none">{{ $structure->service->name }}</a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-th-large text-success"></i>
                            <span>Espaces</span>
                        </div>
                        <span class="badge bg-success rounded-pill">{{ $structure->espaces_count }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-map-marker-alt text-warning"></i>
                            <span>Zones</span>
                        </div>
                        <span class="badge bg-warning rounded-pill text-dark">
                            {{ $structure->espaces->sum('zones_count') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
