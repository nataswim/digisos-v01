@extends('layouts.admin')

@section('title', 'Gestion des Pages')
@section('page-title', 'Pages')
@section('page-description', 'Gestion des pages statiques')

@section('content')
<div class="container-fluid">
    
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-white">{{ $stats['published'] }}</h3>
                    <small class="text-white">Publiées</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-secondary bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-white">{{ $stats['draft'] }}</h3>
                    <small class="text-white">Brouillons</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-white">{{ $stats['public'] }}</h3>
                    <small class="text-white">Publiques</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-white">{{ $stats['authenticated'] }}</h3>
                    <small class="text-white">Authentifiées</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pages.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Rechercher..."
                               value="{{ $search }}">
                    </div>
                    
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="visibility" class="form-select">
                            <option value="">Toutes visibilités</option>
                            <option value="public" {{ $visibility === 'public' ? 'selected' : '' }}>Public</option>
                            <option value="authenticated" {{ $visibility === 'authenticated' ? 'selected' : '' }}>Authentifié</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions et liste -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Liste des pages ({{ $pages->total() }})</h5>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle page
                </a>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($pages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th width="120">Visibilité</th>
                                <th width="100">Statut</th>
                                <th width="120">Créée le</th>
                                <th width="150" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="page_ids[]" value="{{ $page->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $page->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $page->slug }}</small>
                                    </td>
                                    <td>
                                        @if($page->category)
                                            <span class="badge bg-primary">{{ $page->category->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($page->visibility === 'public')
                                            <span class="badge bg-success">Public</span>
                                        @else
                                            <span class="badge bg-warning">Authentifié</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($page->is_published)
                                            <span class="badge bg-success">Publié</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $page->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.pages.show', $page) }}" 
                                               class="btn btn-outline-info"
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.pages.edit', $page) }}" 
                                               class="btn btn-outline-primary"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('admin.pages.destroy', $page) }}" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Supprimer cette page ?')">
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
                    {{ $pages->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune page trouvée</p>
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la première page
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
