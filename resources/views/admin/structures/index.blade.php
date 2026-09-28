@extends('layouts.admin')

@section('title', 'Structures')
@section('page-title', 'Structures')
@section('page-description', 'Gestion des divisions fonctionnelles')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                <li class="breadcrumb-item active">Structures</li>
            </ol>
        </nav>
        <a href="{{ route('admin.structures.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle structure
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-layer-group me-2 text-primary"></i>Liste des structures
            </h5>
            <span class="badge bg-primary rounded-pill">{{ $structures->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:60px;">Photo</th>
                            <th>Nom</th>
                            <th>Service parent</th>
                            <th class="text-center">Capacité</th>
                            <th class="text-center">Espaces</th>
                            <th class="text-center">Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($structures as $structure)
                            <tr>
                                <td class="ps-4">
                                    @if($structure->photo)
                                        <img src="{{ $structure->photo }}"
                                             alt="{{ $structure->name }}"
                                             class="rounded"
                                             style="width:48px;height:48px;object-fit:cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                             style="width:48px;height:48px;">
                                            <i class="fas fa-layer-group text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $structure->name }}</div>
                                    @if($structure->description)
                                        <div class="text-muted small">
                                            {{ Str::limit(strip_tags($structure->description), 80) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($structure->service)
                                        <a href="{{ route('admin.services.show', $structure->service) }}"
                                           class="badge bg-light text-dark border text-decoration-none">
                                            <i class="fas fa-building me-1"></i>{{ $structure->service->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($structure->capacity)
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-users me-1"></i>{{ number_format($structure->capacity) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill">{{ $structure->espaces_count }}</span>
                                </td>
                                <td class="text-center">
                                    @if($structure->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.structures.show', $structure) }}"
                                           class="btn btn-sm btn-outline-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.structures.edit', $structure) }}"
                                           class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.structures.destroy', $structure) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer cette structure et tous ses espaces ?')">
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
                                    <i class="fas fa-layer-group fa-2x mb-2 d-block opacity-25"></i>
                                    Aucune structure créée pour le moment.
                                    <div class="mt-2">
                                        <a href="{{ route('admin.structures.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i>Créer la première structure
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($structures->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $structures->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
