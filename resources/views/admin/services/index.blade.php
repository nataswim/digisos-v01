@extends('layouts.admin')

@section('title', 'Services')
@section('page-title', 'Services')
@section('page-description', 'Gestion des installations principales')

@section('content')
<div class="container-fluid">

    {{-- Header actions --}}
    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Services</li>
            </ol>
        </nav>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau service
        </a>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-building me-2 text-primary"></i>Liste des services
            </h5>
            <span class="badge bg-primary rounded-pill">{{ $services->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 60px;">Photo</th>
                            <th>Nom</th>
                            <th>Adresse</th>
                            <th class="text-center">Capacité</th>
                            <th class="text-center">Structures</th>
                            <th class="text-center">Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td class="ps-4">
                                    @if($service->photo)
                                        <img src="{{ $service->photo }}"
                                             alt="{{ $service->name }}"
                                             class="rounded"
                                             style="width:48px; height:48px; object-fit:cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                             style="width:48px; height:48px;">
                                            <i class="fas fa-building text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $service->name }}</div>
                                    @if($service->description)
                                        <div class="text-muted small">
                                            {{ Str::limit(strip_tags($service->description), 80) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $service->address ?? '—' }}</td>
                                <td class="text-center">
                                    @if($service->capacity)
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-users me-1"></i>{{ number_format($service->capacity) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">{{ $service->structures_count }}</span>
                                </td>
                                <td class="text-center">
                                    @if($service->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.services.show', $service) }}"
                                           class="btn btn-sm btn-outline-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                           class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer ce service et toutes ses structures ?')">
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
                                    <i class="fas fa-building fa-2x mb-2 d-block opacity-25"></i>
                                    Aucun service créé pour le moment.
                                    <div class="mt-2">
                                        <a href="{{ route('admin.services.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i>Créer le premier service
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($services->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
