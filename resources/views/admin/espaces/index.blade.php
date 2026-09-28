@extends('layouts.admin')

@section('title', 'Espaces')
@section('page-title', 'Espaces')
@section('page-description', 'Gestion des subdivisions thématiques')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.structures.index') }}">Structures</a></li>
                <li class="breadcrumb-item active">Espaces</li>
            </ol>
        </nav>
        <a href="{{ route('admin.espaces.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvel espace
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-th-large me-2 text-primary"></i>Liste des espaces
            </h5>
            <span class="badge bg-primary rounded-pill">{{ $espaces->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:60px;">Photo</th>
                            <th>Nom</th>
                            <th>Structure › Service</th>
                            <th class="text-center">Capacité</th>
                            <th class="text-center">Zones</th>
                            <th class="text-center">Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($espaces as $espace)
                            <tr>
                                <td class="ps-4">
                                    @if($espace->photo)
                                        <img src="{{ $espace->photo }}"
                                             alt="{{ $espace->name }}"
                                             class="rounded"
                                             style="width:48px;height:48px;object-fit:cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                             style="width:48px;height:48px;">
                                            <i class="fas fa-th-large text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $espace->name }}</div>
                                    @if($espace->description)
                                        <div class="text-muted small">
                                            {{ Str::limit(strip_tags($espace->description), 80) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        <a href="{{ route('admin.structures.show', $espace->structure) }}"
                                           class="text-decoration-none fw-semibold">
                                            {{ $espace->structure->name }}
                                        </a>
                                        <div class="text-muted">
                                            <i class="fas fa-building me-1"></i>{{ $espace->structure->service->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($espace->capacity)
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-users me-1"></i>{{ number_format($espace->capacity) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning rounded-pill text-dark">{{ $espace->zones_count }}</span>
                                </td>
                                <td class="text-center">
                                    @if($espace->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.espaces.show', $espace) }}"
                                           class="btn btn-sm btn-outline-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.espaces.edit', $espace) }}"
                                           class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.espaces.destroy', $espace) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer cet espace et toutes ses zones ?')">
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
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-th-large fa-2x mb-2 d-block opacity-25"></i>
                                    Aucun espace créé pour le moment.
                                    <div class="mt-2">
                                        <a href="{{ route('admin.espaces.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i>Créer le premier espace
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($espaces->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $espaces->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
