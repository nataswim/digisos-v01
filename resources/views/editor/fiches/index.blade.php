@extends('layouts.editor')

@section('title', 'Mes Fiches')

@section('content')

<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-secondary  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white ">  <i class="fas fa-file-medical text-white me-2"></i>
                Gestion des Fiches</h5>
  <a href="{{ route('editor.fiches.create') }}" class="btn btn-warning">
            <i class="fas fa-plus me-2"></i>Nouvelle fiche
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
                    <h3 class="mb-0 text-info">{{ $stats['public'] ?? 0 }}</h3>
                    <small class="text-muted">Publiques</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-secondary">{{ $stats['authenticated'] ?? 0 }}</h3>
                    <small class="text-muted">Premium</small>
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
            <form method="GET" action="{{ route('editor.fiches.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ $search ?? '' }}">
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
                    <select name="sous_category" class="form-select">
                        <option value="">Toutes sous-catégories</option>
                        @foreach($sousCategories as $sousCategory)
                            <option value="{{ $sousCategory->id }}" {{ ($sousCategoryId ?? '') == $sousCategory->id ? 'selected' : '' }}>
                                {{ $sousCategory->name }}
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
                            <th style="width: 120px;">Visibilité</th>
                            <th style="width: 150px;">Catégorie</th>
                            <th style="width: 150px;">Sous-catégorie</th>
                            <th style="width: 120px;">Auteur</th>
                            <th style="width: 100px;">Vues</th>
                            <th style="width: 120px;">Date</th>
                            <th style="width: 150px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fiches as $fiche)
                            <tr>
                                <td> 
                                    <div class="d-flex align-items-center">
                                        @if($fiche->image)
                                            <img src="{{ $fiche->image }}" alt="{{ $fiche->title }}" 
                                                 class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $fiche->title }}</div>
                                            @if($fiche->is_featured)
                                                <span class="badge bg-danger badge-sm">
                                                    <i class="fas fa-star"></i> Vedette
                                                </span>
                                            @endif
                                            @if($fiche->is_published)
                                                <span class="badge bg-success badge-sm">Publié</span>
                                            @else
                                                <span class="badge bg-warning badge-sm">Brouillon</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $fiche->visibility === 'public' ? 'info' : 'secondary' }}">
                                        {{ $fiche->visibility === 'public' ? 'Public' : 'Premium' }}
                                    </span>
                                </td>
                                <td>
                                    @if($fiche->category)
                                        {{ $fiche->category->name }}
                                    @else
                                        <span class="text-muted">Sans catégorie</span>
                                    @endif
                                </td>
                                <td>
                                    @if($fiche->sousCategory)
                                        {{ $fiche->sousCategory->name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $fiche->created_by_name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-eye text-muted me-1"></i>
                                    {{ number_format($fiche->views_count ?? 0) }}
                                </td>
                                <td>
                                    <small>{{ $fiche->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        @if($fiche->is_published && $fiche->category && $fiche->sousCategory)
                                            <a href="{{ route('public.fiches.show', [$fiche->category->slug, $fiche->sousCategory->slug, $fiche->slug]) }}" 
                                               class="btn btn-outline-primary" target="_blank" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('editor.fiches.edit', $fiche) }}" class="btn btn-primary" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="deleteFiche({{ $fiche->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Aucune fiche trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($fiches->hasPages())
        <div class="mt-4">
            {{ $fiches->links() }}
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
function deleteFiche(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette fiche ?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/editor/fiches/${id}`;
        form.submit();
    }
}
</script>
@endpush