@extends('layouts.coach')

@section('title', $document->exists ? 'Modifier un document' : 'Ajouter un document')

@section('content')
<div class="container-lg" style="max-width: 820px">
    <a href="{{ route('coach.documents.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Documents
    </a>
    <h1 class="h3 fw-bold mb-4">{{ $document->exists ? 'Modifier le document' : 'Ajouter un document' }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $document->exists ? route('coach.documents.update', $document) : route('coach.documents.store') }}"
          class="card border-0 shadow-sm">
        @csrf
        @if ($document->exists) @method('PUT') @endif

        <div class="card-body p-4">
            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Titre <span class="text-danger" aria-hidden="true">*</span></label>
                <input type="text" id="title" name="title" required maxlength="255" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $document->title) }}">
            </div>

            <div class="mb-3">
                <label for="category" class="form-label fw-semibold">Catégorie</label>
                <select id="category" name="category" class="form-select">
                    <option value="">—</option>
                    @foreach (\App\Models\CoachDocument::CATEGORIES as $key => $label)
                        <option value="{{ $key }}" @selected(old('category', $document->category) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-semibold">Description</label>
                <textarea id="description" name="description" rows="3" maxlength="2000" class="form-control">{{ old('description', $document->description) }}</textarea>
            </div>

            <fieldset class="border rounded p-3 mb-3">
                <legend class="float-none w-auto px-2 fs-6 fw-semibold mb-0">Fichier ou lien</legend>

                @if ($document->hasFile())
                    <p class="small mb-2">
                        Fichier actuel : <a href="{{ route('coach.documents.download', $document) }}">{{ $document->original_name }}</a>
                        @if ($document->readable_size)({{ $document->readable_size }})@endif
                    </p>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="remove_file" name="remove_file" value="1">
                        <label class="form-check-label" for="remove_file">Retirer ce fichier</label>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="file" class="form-label">{{ $document->hasFile() ? 'Remplacer par un autre fichier' : 'Fichier' }}</label>
                    <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror"
                           accept=".{{ implode(',.', \App\Models\CoachDocument::EXTENSIONS) }}" aria-describedby="file-help">
                    <div id="file-help" class="form-text">PDF, Word, Excel, PowerPoint, OpenDocument, texte ou image — 20 Mo maximum.</div>
                </div>

                <div>
                    <label for="url" class="form-label">Ou un lien (Google Drive, vidéo…)</label>
                    <input type="url" id="url" name="url" maxlength="500" class="form-control @error('url') is-invalid @enderror"
                           value="{{ old('url', $document->url) }}" placeholder="https://">
                </div>
            </fieldset>

            <p class="small text-muted mb-0">
                <i class="fas fa-lock me-1" aria-hidden="true"></i>
                Les documents ne sont visibles que des coachs et des administrateurs. N'y mettez pas de données personnelles
                de nageurs sans nécessité (certificats médicaux, coordonnées…).
            </p>
        </div>
        <div class="card-footer bg-white d-flex gap-2 justify-content-end">
            <a href="{{ route('coach.documents.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn-primary"><i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer</button>
        </div>
    </form>
</div>
@endsection
