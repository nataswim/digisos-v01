@extends('layouts.editor')

@section('title', 'Galeries photos')

@section('content')
<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-secondary  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white ">Galeries photos</h5>
                        <div class="d-flex gap-2">
            <a href="{{ route('galleries.index') }}" 
               class="btn btn-warning"
               target="_blank">
                <i class="fas fa-eye me-2"></i>Voir les Galeries
            </a>
            <a href="{{ route('editor.photo-galleries.create') }}" 
               class="btn btn-warning">
                <i class="fas fa-plus me-2"></i>Nouvelle galerie
            </a>
        </div>
            </div>
        </div>
</section>

<div class="container-fluid py-4">
    {{-- Statistiques --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total galeries</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $stats['published'] }}</h3>
                    <small class="text-success">Publiées</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-warning">{{ $stats['featured'] }}</h3>
                    <small class="text-warning">En vedette</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('editor.photo-galleries.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Rechercher..."
                               value="{{ $search }}">
                    </div>
                    
                    <div class="col-md-3">
                        <select name="visibility" class="form-select">
                            <option value="">Toutes visibilités</option>
                            <option value="public" {{ $visibility === 'public' ? 'selected' : '' }}>Public</option>
                            <option value="authenticated" {{ $visibility === 'authenticated' ? 'selected' : '' }}>Authentifié</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tous statuts</option>
                            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Publiées</option>
                            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Brouillons</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('editor.photo-galleries.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Liste --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3">
            <h5 class="mb-0">Galeries ({{ $galleries->total() }})</h5>
        </div>
        
        <div class="card-body p-0">
            @if($galleries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Couverture</th>
                                <th>Titre</th>
                                <th>Photos</th>
                                <th>Visibilité</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($galleries as $gallery)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="gallery-checkbox" value="{{ $gallery->id }}">
                                    </td>
                                    <td>
                                        @if($gallery->coverImage)
                                            <img src="{{ $gallery->coverImage->url }}" 
                                                 alt="{{ $gallery->title }}" 
                                                 class="rounded"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-images text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $gallery->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $gallery->slug }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $gallery->photos_count }}</span>
                                    </td>
                                    <td>
                                        @if($gallery->visibility === 'public')
                                            <span class="badge bg-success">Public</span>
                                        @else
                                            <span class="badge bg-warning">Authentifié</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gallery->is_published)
                                            <span class="badge bg-success">Publié</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                        @if($gallery->is_featured)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $gallery->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('editor.photo-galleries.show', $gallery) }}" 
                                               class="btn btn-outline-info"
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('editor.photo-galleries.edit', $gallery) }}" 
                                               class="btn btn-outline-primary"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('editor.photo-galleries.destroy', $gallery) }}" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Supprimer cette galerie ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-3">
                    {{ $galleries->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune galerie trouvée</p>
                    <a href="{{ route('editor.photo-galleries.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la première galerie
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection