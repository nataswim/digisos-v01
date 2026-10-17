@extends('layouts.admin')

@section('title', $category->exists ? 'Modifier une catégorie' : 'Ajouter une catégorie')

@section('content')
<div class="shop-admin">
    @include('admin.shop._nav', ['active' => 'categories'])

    <form method="POST" action="{{ $category->exists ? route('admin.shop.categories.update', $category) : route('admin.shop.categories.store') }}">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">{{ $category->exists ? 'Modifier « ' . $category->name . ' »' : 'Nouvelle catégorie' }}</h2>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nom</label>
                            <input type="text" id="name" name="name" maxlength="120" required
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}" placeholder="ex. Textile">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description <span class="text-muted fw-normal">(facultatif)</span></label>
                            <textarea id="description" name="description" rows="3" maxlength="500"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="ex. Tee-shirts, sweats et serviettes aux couleurs du club">{{ old('description', $category->description) }}</textarea>
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
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Image <span class="text-muted fw-normal">(facultatif)</span></h2>
                        <div class="shop-cat-preview mb-3">
                            <img src="{{ old('image', $category->image) }}" alt="" id="image-preview" @if (! old('image', $category->image)) hidden @endif>
                        </div>
                        <div class="input-group">
                            <input type="text" id="image" name="image" maxlength="500"
                                   class="form-control @error('image') is-invalid @enderror"
                                   value="{{ old('image', $category->image) }}" placeholder="Choisir une image…" aria-label="Image de la catégorie">
                            <button type="button" class="btn btn-primary" onclick="openMediaSelector('image', 'image-preview')" title="Choisir dans la médiathèque">
                                <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                            </button>
                        </div>
                        <div class="form-text">Affichée en tête de la catégorie sur la boutique.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route('admin.shop.categories.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .shop-cat-preview {
        height: 140px;
        border: 1px dashed #ced4da;
        border-radius: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .shop-cat-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var champ = document.getElementById('image');
        var apercu = document.getElementById('image-preview');
        champ.addEventListener('input', function () {
            apercu.src = champ.value.trim();
            apercu.hidden = champ.value.trim() === '';
        });
    });
</script>
@endpush
