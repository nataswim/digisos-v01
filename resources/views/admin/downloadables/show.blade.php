@extends('layouts.admin')

@section('title', 'Detail du telechargement')
@section('page-title', $downloadable->title)
@section('page-description', 'Details du fichier telechargeable')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-water fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">{{ $downloadable->title }}</h5>
                                <small class="opacity-75">{{ $downloadable->format_display }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark">
                                {{ ucfirst($downloadable->status) }}
                            </span>
                            @if($downloadable->is_featured)
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>Vedette
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <small class="text-muted d-block">Slug</small>
                                <strong>{{ $downloadable->slug }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-info border-3 ps-3">
                                <small class="text-muted d-block">Permission</small>
                                <strong>{{ ucfirst($downloadable->user_permission) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Fichier -->
                    @if($downloadable->file_path)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Fichier</h6>
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-{{ $downloadable->format === 'pdf' ? 'pdf' : ($downloadable->format === 'mp4' ? 'video' : 'alt') }} fa-2x text-primary me-3"></i>
                                    <div class="flex-grow-1">
                                        <strong>{{ basename($downloadable->file_path) }}</strong>
                                        @if($downloadable->file_size)
                                            <div class="text-muted">{{ $downloadable->file_size }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="badge bg-secondary">{{ strtoupper($downloadable->format) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($downloadable->cover_image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image de couverture</h6>
                            <img src="{{ $downloadable->cover_image }}" 
                                 alt="{{ $downloadable->title }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($downloadable->short_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description courte</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $downloadable->short_description }}
                            </div>
                        </div>
                    @endif

                    @if($downloadable->long_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description complete</h6>
                            <div class="content-display">
                                {!! $downloadable->long_description !!}
                            </div>
                        </div>
                    @endif

                    <!-- Informations SEO -->
                    @if($downloadable->meta_title || $downloadable->meta_description || $downloadable->meta_keywords)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="fas fa-search me-2"></i>Informations SEO
                            </h6>
                            <div class="row g-3">
                                @if($downloadable->meta_title)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Titre SEO</small>
                                        <strong>{{ $downloadable->meta_title }}</strong>
                                    </div>
                                @endif
                                
                                @if($downloadable->meta_description)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Description SEO</small>
                                        <div class="bg-light p-2 rounded">{{ $downloadable->meta_description }}</div>
                                    </div>
                                @endif
                                
                                @if($downloadable->meta_keywords)
                                    <div class="col-12">
                                        <small class="text-muted d-block">Mots-cles</small>
                                        <div>
                                            @foreach(explode(',', $downloadable->meta_keywords) as $keyword)
                                                <span class="badge bg-secondary me-1">{{ trim($keyword) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques de telechargement -->
            @if($recentDownloads->count() > 0)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white text-primary p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>Telechargements recents
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Utilisateur</th>
                                        <th class="border-0 px-4 py-3">IP</th>
                                        <th class="border-0 px-4 py-3">Date</th>
                                        <th class="border-0 px-4 py-3">Navigateur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentDownloads as $log)
                                        <tr>
                                            <td class="px-4 py-3">
                                                @if($log->user)
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px;">
                                                            <span class="small fw-bold text-primary">
                                                                {{ substr($log->user->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $log->user->name }}</div>
                                                            <small class="text-muted">{{ $log->user->email }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Utilisateur anonyme</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-monospace">{{ $log->ip_address }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div>{{ $log->created_at->format('d/m/Y H:i') }}</div>
                                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($log->user_agent)
                                                    <small class="text-muted">{!! Str::limit($log->user_agent, 50) !!}</small>
                                                @else
                                                    <span class="text-muted">Non disponible</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
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
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ number_format($downloadable->download_count) }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $downloadStats['today'] }}</h4>
                                <small class="text-muted">Aujourd'hui</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-warning mb-1">{{ $downloadStats['this_week'] }}</h4>
                                <small class="text-muted">Cette semaine</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-info mb-1">{{ $downloadStats['this_month'] }}</h4>
                                <small class="text-muted">Ce mois</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categorie -->
            @if($downloadable->category)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white p-3">
                        <h6 class="mb-0">
                            <i class="fas fa-folder me-2"></i>Categorie
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px;">
                                @if($downloadable->category->icon)
                                    <i class="{{ $downloadable->category->icon }} text-white"></i>
                                @else
                                    <i class="fas fa-folder text-white"></i>
                                @endif
                            </div>
                            <div>
                                <strong>{{ $downloadable->category->name }}</strong>
                                @if($downloadable->category->short_description)
                                    <div class="text-muted small">{!! Str::limit($downloadable->category->short_description, 50) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Permissions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Permissions d'acces
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="text-center">
                        @if($downloadable->user_permission === 'public')
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <i class="fas fa-water fa-2x text-success mb-2"></i>
                                <h6 class="fw-bold text-success">Public</h6>
                                <small class="text-muted">Accessible A tous les visiteurs</small>
                            </div>
                        @elseif($downloadable->user_permission === 'visitor')
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <i class="fas fa-eye fa-2x text-info mb-2"></i>
                                <h6 class="fw-bold text-info">Visiteur</h6>
                                <small class="text-muted">Reserve aux non-connectes</small>
                            </div>
                        @else
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <i class="fas fa-user fa-2x text-warning mb-2"></i>
                                <h6 class="fw-bold text-warning">Membre</h6>
                                <small class="text-muted">Reserve aux utilisateurs connectes</small>
                            </div>
                        @endif
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
                        @if($downloadable->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Cree par</small>
                                <strong>{{ $downloadable->creator->name }}</strong>
                            </div>
                        @endif
                        
                        <div class="col-12">
                            <small class="text-muted d-block">Date de creation</small>
                            <strong>{{ $downloadable->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        
                        @if($downloadable->updated_at && $downloadable->updated_at != $downloadable->created_at)
                            <div class="col-12">
                                <small class="text-muted d-block">Derniere modification</small>
                                <strong>{{ $downloadable->updated_at->format('d/m/Y H:i') }}</strong>
                                @if($downloadable->updater)
                                    <div class="text-muted">par {{ $downloadable->updater->name }}</div>
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
                        <a href="{{ route('admin.downloadables.edit', $downloadable) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($downloadable->status === 'active' && $downloadable->category)
                            <a href="{{ route('ebook.show', [$downloadable->category->slug, $downloadable->slug]) }}" target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                            </a>
                        @endif
                        <form method="POST" action="{{ route('admin.downloadables.duplicate', $downloadable) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="fas fa-copy me-2"></i>Dupliquer
                            </button>
                        </form>
                        <a href="{{ route('admin.downloadables.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.downloadables.destroy', $downloadable) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce telechargement ?')">
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
.content-display {
    line-height: 1.6;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    background: #f9fafb;
}

.content-display h1,
.content-display h2,
.content-display h3 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.content-display p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.content-display ul,
.content-display ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}







.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush