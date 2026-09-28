@extends('layouts.admin')

@section('title', 'Détails du service')
@section('page-title', $service->name)
@section('page-description', 'Installation principale — Détails et structures associées')

@section('content')
<div class="container-fluid">

    {{-- Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                <li class="breadcrumb-item active">{{ $service->name }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Colonne principale --}}
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
                            <p class="fw-semibold fs-5 mb-0">{{ $service->name }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($service->is_active)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Actif
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-times-circle me-1"></i>Inactif
                                </span>
                            @endif
                        </div>

                        @if($service->address)
                            <div class="col-12">
                                <p class="text-muted small mb-1">Adresse</p>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>{{ $service->address }}
                                </p>
                            </div>
                        @endif

                        @if($service->capacity)
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Capacité</p>
                                <p class="mb-0">
                                    <i class="fas fa-users text-muted me-2"></i>{{ number_format($service->capacity) }} personnes
                                </p>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Créé le</p>
                            <p class="mb-0">{{ $service->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Modifié le</p>
                            <p class="mb-0">{{ $service->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($service->description)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-align-left me-2 text-secondary"></i>Description
                        </h5>
                    </div>
                    <div class="card-body p-4 ql-editor-content">
                        {!! $service->description !!}
                    </div>
                </div>
            @endif

            {{-- Structures associées --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-sitemap me-2 text-primary"></i>Structures associées
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill">{{ $service->structures_count }}</span>
                        <a href="{{ route('admin.structures.create') }}?service_id={{ $service->id }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Ajouter
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($service->structures as $structure)
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                @if($structure->photo)
                                    <img src="{{ $structure->photo }}"
                                         class="rounded" style="width:40px;height:40px;object-fit:cover;"
                                         alt="{{ $structure->name }}">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                         style="width:40px;height:40px;">
                                        <i class="fas fa-layer-group text-muted small"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $structure->name }}</div>
                                    <div class="text-muted small">
                                        <i class="fas fa-th-large me-1"></i>{{ $structure->espaces_count }} espace(s)
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($structure->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                                <a href="{{ route('admin.structures.show', $structure) }}"
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.structures.edit', $structure) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-layer-group fa-2x mb-2 d-block opacity-25"></i>
                            Aucune structure associée.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">

            {{-- Photo --}}
            @if($service->photo)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="mb-0"><i class="fas fa-image me-2 text-warning"></i>Photo</h6>
                    </div>
                    <div class="card-body p-3">
                        <img src="{{ $service->photo }}"
                             alt="{{ $service->name }}"
                             class="img-fluid rounded"
                             style="width:100%; height:200px; object-fit:cover;">
                    </div>
                </div>
            @endif

            {{-- Statistiques hiérarchie --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-info"></i>Hiérarchie</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-layer-group text-primary"></i>
                            <span>Structures</span>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $service->structures_count }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-th-large text-success"></i>
                            <span>Espaces</span>
                        </div>
                        <span class="badge bg-success rounded-pill">
                            {{ $service->structures->sum('espaces_count') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-map-marker-alt text-warning"></i>
                            <span>Zones</span>
                        </div>
                        <span class="badge bg-warning rounded-pill text-dark">—</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
