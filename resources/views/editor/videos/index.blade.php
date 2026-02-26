@extends('layouts.editor')

@section('title', 'Mes Vidéos')

@section('content')

<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-secondary  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white "> Gestion des Vidéos</h5>
                <a href="{{ route('editor.videos.create') }}" class="btn btn-warning">
            <i class="fas fa-plus me-2"></i>Nouvelle vidéo
        </a>
            </div>
        </div>
</section>

<div class="container-fluid">
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-primary">{{ $stats['total'] ?? 0 }}</h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-success">{{ $stats['published'] ?? 0 }}</h3>
                    <small class="text-muted">Publiées</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-warning">{{ $stats['draft'] ?? 0 }}</h3>
                    <small class="text-muted">Brouillons</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-info">{{ $stats['upload'] ?? 0 }}</h3>
                    <small class="text-muted">Uploads</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-secondary">{{ $stats['external'] ?? 0 }}</h3>
                    <small class="text-muted">Externes</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-danger">{{ $stats['featured'] ?? 0 }}</h3>
                    <small class="text-muted">En vedette</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('editor.videos.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="upload" {{ ($type ?? '') === 'upload' ? 'selected' : '' }}>Upload</option>
                        <option value="youtube" {{ ($type ?? '') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                        <option value="vimeo" {{ ($type ?? '') === 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                        <option value="dailymotion" {{ ($type ?? '') === 'dailymotion' ? 'selected' : '' }}>Dailymotion</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="visibility" class="form-select">
                        <option value="">Toutes visibilités</option>
                        <option value="public" {{ ($visibility ?? '') === 'public' ? 'selected' : '' }}>Public</option>
                        <option value="authenticated" {{ ($visibility ?? '') === 'authenticated' ? 'selected' : '' }}>Premium</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">Toutes catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="featured" class="form-select">
                        <option value="">En vedette ?</option>
                        <option value="1" {{ ($featured ?? false) ? 'selected' : '' }}>Oui</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Titre</th>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 120px;">Visibilité</th>
                            <th style="width: 120px;">Durée</th>
                            <th style="width: 120px;">Auteur</th>
                            <th style="width: 100px;">Vues</th>
                            <th style="width: 120px;">Date</th>
                            <th style="width: 150px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($videos as $video)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($video->thumbnail)
                                            <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" 
                                                 class="rounded me-2" style="width: 80px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $video->title }}</div>
                                            @if($video->is_featured)
                                                <span class="badge bg-danger badge-sm">
                                                    <i class="fas fa-star"></i> Vedette
                                                </span>
                                            @endif
                                            @if($video->is_published)
                                                <span class="badge bg-success badge-sm">Publié</span>
                                            @else
                                                <span class="badge bg-warning badge-sm">Brouillon</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($video->type) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $video->visibility === 'public' ? 'info' : 'secondary' }}">
                                        {{ $video->visibility === 'public' ? 'Public' : 'Premium' }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $video->getFormattedDuration() }}</small>
                                </td>
                                <td>
                                    <small>{{ $video->created_by_name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-eye text-muted me-1"></i>
                                    {{ number_format($video->views_count ?? 0) }}
                                </td>
                                <td>
                                    <small>{{ $video->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        @if($video->is_published)
                                            <a href="{{ route('public.videos.show', $video->slug) }}" 
                                               class="btn btn-outline-primary" target="_blank" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('editor.videos.edit', $video) }}" class="btn btn-primary" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="deleteVideo({{ $video->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Aucune vidéo trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($videos->hasPages())
        <div class="mt-4">
            {{ $videos->links() }}
        </div>
    @endif
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function deleteVideo(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/editor/videos/${id}`;
        form.submit();
    }
}
</script>
@endpush