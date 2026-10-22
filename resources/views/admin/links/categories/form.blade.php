@extends('layouts.admin')

@section('title', $category->exists ? 'Modifier une catégorie' : 'Ajouter une catégorie')

@section('content')
<div class="links-admin">
    @include('admin.links._nav', ['active' => 'categories'])

    <form method="POST" action="{{ $category->exists ? route('admin.links.categories.update', $category) : route('admin.links.categories.store') }}">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div class="card border-0 shadow-sm" style="max-width: 760px;">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-4">{{ $category->exists ? 'Modifier « ' . $category->name . ' »' : 'Nouvelle catégorie' }}</h2>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nom</label>
                    <input type="text" id="name" name="name" maxlength="120" required
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name) }}" placeholder="ex. Fédérations">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description <span class="text-muted fw-normal">(facultatif)</span></label>
                    <textarea id="description" name="description" rows="3" maxlength="500"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="ex. Les sites officiels de la natation française.">{{ old('description', $category->description) }}</textarea>
                    <div class="form-text">Affichée sous le titre de la catégorie.</div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-5">
                        <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" max="65000"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               value="{{ old('sort_order', $category->sort_order) }}">
                    </div>
                    <div class="col-sm-7 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $category->is_active))>
                            <label class="form-check-label" for="is_active">Afficher cette catégorie</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route('admin.links.categories.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
