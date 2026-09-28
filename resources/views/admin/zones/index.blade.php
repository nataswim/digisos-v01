@extends('layouts.admin')

@section('title', 'Zones')
@section('page-title', 'Zones')
@section('page-description', 'Gestion des lieux physiques précis')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.structures.index') }}">Structures</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.espaces.index') }}">Espaces</a></li>
                <li class="breadcrumb-item active">Zones</li>
            </ol>
        </nav>
        <a href="{{ route('admin.zones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle zone
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-map-marker-alt me-2 text-primary"></i>Liste des zones
            </h5>
            <span class="badge bg-primary rounded-pill">{{ $zones->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:60px;">Photo</th>
                            <th>Nom</th>
                            <th>Hiérarchie complète</th>
                            <th class="text-center">Capacité</th>
                            <th class="text-center">Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($zones as $zone)
                            <tr>
                                <td class="ps-4">
                                    @if($zone->photo)
                                        <img src="{{ $zone->photo }}"
                                             alt="{{ $zone->name }}"
                                             class="rounded"
                                             style="width:48px;height:48px;object-fit:cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                             style="width:48px;height:48px;">
                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $zone->name }}</div>
                                    @if($zone->description)
                                        <div class="text-muted small">
                                            {{ Str::limit(strip_tags($zone->description), 80) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small lh-lg">
                                        <span class="text-muted">
                                            <i class="fas fa-building me-1"></i>{{ $zone->espace->structure->service->name }}
                                        </span><br>
                                        <span class="text-muted">
                                            <i class="fas fa-layer-group me-1"></i>{{ $zone->espace->structure->name }}
                                        </span><br>
                                        <a href="{{ route('admin.espaces.show', $zone->espace) }}"
                                           class="text-decoration-none fw-semibold">
                                            <i class="fas fa-th-large me-1"></i>{{ $zone->espace->name }}
                                        </a>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($zone->capacity)
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-users me-1"></i>{{ number_format($zone->capacity) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($zone->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.zones.show', $zone) }}"
                                           class="btn btn-sm btn-outline-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.zones.edit', $zone) }}"
                                           class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.zones.destroy', $zone) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer cette zone ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-map-marker-alt fa-2x mb-2 d-block opacity-25"></i>
                                    Aucune zone créée pour le moment.
                                    <div class="mt-2">
                                        <a href="{{ route('admin.zones.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i>Créer la première zone
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($zones->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $zones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
