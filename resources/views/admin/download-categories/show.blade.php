@extends('layouts.admin')

@section('title', 'Detail de la categorie')
@section('page-title', $downloadCategory->name)
@section('page-description', 'Details de la categorie de telechargement')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            @if($downloadCategory->icon)
                                <i class="{{ $downloadCategory->icon }} fa-2x me-3"></i>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $downloadCategory->name }}</h5>
                                <small class="opacity-75">{{ $downloadCategory->downloadables->count() }} telechargement(s)</small>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark">
                            {{ ucfirst($downloadCategory->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug</small>
                                <strong>{{ $downloadCategory->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-info border-3 ps-3">
                                <small class="text-muted d-block">Ordre</small>
                                <strong>{{ $downloadCategory->order }}</strong>
                            </div>
                        </div>
                    </div>

                    @if($downloadCategory->short_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description courte</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $downloadCategory->short_description }}
                            </div>
                        </div>
                    @endif

                    @if($downloadCategory->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description complete</h6>
                            <div class="content-display">
                                {!! nl2br(e($downloadCategory->description)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Liste des telechargements -->
                    @if($downloadCategory->downloadables->count() > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-water me-2"></i>Telechargements dans cette categorie
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Format</th>
                                            <th>Permission</th>
                                            <th>Statut</th>
                                            <th>Telechargements</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($downloadCategory->downloadables as $downloadable)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $downloadable->title }}</div>
                                                    @if($downloadable->file_size)
                                                        <small class="text-muted">{{ $downloadable->file_size }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        {{ strtoupper($downloadable->format) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info-subtle text-info">
                                                        {{ ucfirst($downloadable->user_permission) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $downloadable->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $downloadable->status === 'active' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($downloadable->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-primary">{{ number_format($downloadable->download_count) }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.downloadables.show', $downloadable) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar informations -->
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $downloadCategory->downloadables->count() }}</h4>
                                <small class="text-muted">Fichiers</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $downloadCategory->downloadables->where('status', 'active')->count() }}</h4>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ number_format($downloadCategory->downloadables->sum('download_count')) }}</h4>
                                <small class="text-muted">Telechargements totaux</small>
                            </div>
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
                        @if($downloadCategory->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Cree par</small>
                                <strong>{{ $downloadCategory->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de creation</small>
                            <strong>{{ $downloadCategory->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($downloadCategory->updated_at && $downloadCategory->updated_at != $downloadCategory->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Derniere modification</small>
                                <strong>{{ $downloadCategory->updated_at->format('d/m/Y H:i') }}</strong>
                                @if($downloadCategory->updater)
                                    <div class="text-muted">par {{ $downloadCategory->updater->name }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.download-categories.edit', $downloadCategory) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('ebook.category', $downloadCategory->slug) }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                        </a>
                        <a href="{{ route('admin.downloadables.create') }}?category={{ $downloadCategory->id }}" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>Ajouter un fichier
                        </a>
                        <a href="{{ route('admin.download-categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.download-categories.destroy', $downloadCategory) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette categorie ?')">
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

.content-display {
    line-height: 1.6;
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    background: #f9fafb;
}
</style>
@endpush