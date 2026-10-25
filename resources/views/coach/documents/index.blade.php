@extends('layouts.coach')

@section('title', 'Documents des coachs')

@section('content')
<div class="container-lg">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Documents des coachs</h1>
            <p class="text-muted mb-0">Réservés aux coachs et aux administrateurs : ils ne sont pas visibles sur le site public.</p>
        </div>
        <a href="{{ route('coach.documents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2" aria-hidden="true"></i>Ajouter un document
        </a>
    </div>

    <form method="GET" class="card border-0 shadow-sm mb-4" role="search">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label for="q" class="form-label small mb-1">Rechercher</label>
                <input type="search" id="q" name="q" value="{{ $search }}" class="form-control" placeholder="Titre ou description">
            </div>
            <div>
                <label for="categorie" class="form-label small mb-1">Catégorie</label>
                <select id="categorie" name="categorie" class="form-select">
                    <option value="">Toutes</option>
                    @foreach (\App\Models\CoachDocument::CATEGORIES as $key => $label)
                        <option value="{{ $key }}" @selected($category === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-outline-primary">Filtrer</button>
            @if ($search !== '' || $category)
                <a href="{{ route('coach.documents.index') }}" class="btn btn-link">Effacer</a>
            @endif
        </div>
    </form>

    @if ($documents->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-folder-open fa-2x mb-3" aria-hidden="true"></i>
                <p class="mb-0">Aucun document{{ $search !== '' || $category ? ' pour ce filtre' : ' pour l\'instant' }}.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <ul class="list-group list-group-flush">
                @foreach ($documents as $document)
                    <li class="list-group-item py-3">
                        <div class="d-flex flex-wrap gap-3 align-items-start">
                            <i class="fas {{ $document->icon }} fa-2x text-primary mt-1" aria-hidden="true"></i>
                            <div class="flex-grow-1" style="min-width: 220px">
                                <div class="fw-semibold">{{ $document->title }}</div>
                                <div class="small text-muted">
                                    @if ($document->category){{ $document->category_label }} · @endif
                                    @if ($document->hasFile()){{ strtoupper($document->extension) }}@if ($document->readable_size), {{ $document->readable_size }}@endif @else Lien externe @endif
                                    · ajouté le {{ $document->created_at->format('d/m/Y') }}@if ($document->uploader) par {{ $document->uploader->name }}@endif
                                </div>
                                @if ($document->description)
                                    <p class="small mb-0 mt-1">{{ $document->description }}</p>
                                @endif
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('coach.documents.download', $document) }}" class="btn btn-sm btn-primary"
                                   @unless ($document->hasFile()) target="_blank" rel="noopener" @endunless>
                                    <i class="fas {{ $document->hasFile() ? 'fa-download' : 'fa-external-link-alt' }} me-1" aria-hidden="true"></i>
                                    {{ $document->hasFile() ? 'Télécharger' : 'Ouvrir' }}<span class="visually-hidden"> : {{ $document->title }}</span>
                                </a>
                                <a href="{{ route('coach.documents.edit', $document) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier {{ $document->title }}</span>
                                </a>
                                <form method="POST" action="{{ route('coach.documents.destroy', $document) }}"
                                      onsubmit="return confirm('Supprimer ce document ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer {{ $document->title }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mt-3">{{ $documents->links() }}</div>
    @endif
</div>
@endsection
