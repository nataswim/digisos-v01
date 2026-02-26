@extends('layouts.editor')

@section('title', 'Mes Articles')

@section('content')

<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-secondary  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white "> <i class="fas fa-newspaper text-white me-2"></i>
                Gestion des Articles</h5>
     <a href="{{ route('editor.posts.create') }}" class="btn btn-warning">
            <i class="fas fa-plus me-2"></i>Nouvel article
        </a>
            </div>
        </div>
</section>


<div class="container-fluid">
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-primary">{{ $stats['total'] ?? 0 }}</h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-success">{{ $stats['published'] ?? 0 }}</h3>
                    <small class="text-muted">Publiés</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-warning">{{ $stats['draft'] ?? 0 }}</h3>
                    <small class="text-muted">Brouillons</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="mb-0 text-info">{{ $stats['featured'] ?? 0 }}</h3>
                    <small class="text-muted">En vedette</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('editor.posts.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="published" {{ ($status ?? '') === 'published' ? 'selected' : '' }}>Publié</option>
                        <option value="draft" {{ ($status ?? '') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filtrer
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
                            <th style="width: 120px;">Statut</th>
                            <th style="width: 150px;">Catégorie</th>
                            <th style="width: 120px;">Auteur</th>
                            <th style="width: 100px;">Vues</th>
                            <th style="width: 120px;">Date</th>
                            <th style="width: 150px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($post->featured_image)
                                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->name }}" 
                                                 class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $post->name }}</div>
                                            @if($post->is_featured)
                                                <span class="badge bg-danger badge-sm">
                                                    <i class="fas fa-star"></i> Vedette
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($post->status === 'published')
                                        <span class="badge bg-success">Publié</span>
                                    @else
                                        <span class="badge bg-warning">Brouillon</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->category)
                                        {{ $post->category->name }}
                                    @else
                                        <span class="text-muted">Sans catégorie</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $post->user?->name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-eye text-muted me-1"></i>
                                    {{ number_format($post->hits ?? 0) }}
                                </td>
                                <td>
                                    <small>{{ $post->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        @if($post->status === 'published')
                                            <a href="{{ route('posts.public.show', $post->slug) }}" 
                                               class="btn btn-outline-primary" target="_blank" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('editor.posts.edit', $post) }}" class="btn btn-primary" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="deletePost({{ $post->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Aucun article trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($posts->hasPages())
        <div class="mt-4">
            {{ $posts->links() }}
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
function deletePost(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/editor/posts/${id}`;
        form.submit();
    }
}
</script>
@endpush