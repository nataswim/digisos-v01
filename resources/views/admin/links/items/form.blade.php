@extends('layouts.admin')

@section('title', $link->exists ? 'Modifier un lien' : 'Ajouter un lien')

@php
    $image = old('image', $link->image);
@endphp

@section('content')
<div class="links-admin">
    @include('admin.links._nav', ['active' => 'items'])

    <form method="POST" action="{{ $link->exists ? route('admin.links.items.update', $link) : route('admin.links.items.store') }}">
        @csrf
        @if ($link->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">{{ $link->exists ? 'Modifier « ' . $link->title . ' »' : 'Nouveau lien' }}</h2>

                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label for="title" class="form-label fw-semibold">Titre</label>
                                <input type="text" id="title" name="title" maxlength="150" required
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $link->title) }}" placeholder="ex. Fédération Française de Natation">
                            </div>
                            <div class="col-md-5">
                                <label for="link_category_id" class="form-label fw-semibold">Catégorie</label>
                                <select id="link_category_id" name="link_category_id" required
                                        class="form-select @error('link_category_id') is-invalid @enderror">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected((int) old('link_category_id', $link->link_category_id) === $cat->id)>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="url" class="form-label fw-semibold">Adresse du site</label>
                            <input type="text" id="url" name="url" maxlength="500" required inputmode="url"
                                   class="form-control @error('url') is-invalid @enderror"
                                   value="{{ old('url', $link->url) }}" placeholder="https://www.ffnatation.fr">
                            <div class="form-text">Collez l'adresse complète ; « https:// » est ajouté s'il manque.</div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="facebook_url" class="form-label fw-semibold">
                                    <i class="fab fa-facebook-f fa-fw me-1" aria-hidden="true"></i>Facebook <span class="text-muted fw-normal">(facultatif)</span>
                                </label>
                                <input type="text" id="facebook_url" name="facebook_url" maxlength="500" inputmode="url"
                                       class="form-control @error('facebook_url') is-invalid @enderror"
                                       value="{{ old('facebook_url', $link->facebook_url) }}" placeholder="https://www.facebook.com/…">
                            </div>
                            <div class="col-md-6">
                                <label for="instagram_url" class="form-label fw-semibold">
                                    <i class="fab fa-instagram fa-fw me-1" aria-hidden="true"></i>Instagram <span class="text-muted fw-normal">(facultatif)</span>
                                </label>
                                <input type="text" id="instagram_url" name="instagram_url" maxlength="500" inputmode="url"
                                       class="form-control @error('instagram_url') is-invalid @enderror"
                                       value="{{ old('instagram_url', $link->instagram_url) }}" placeholder="https://www.instagram.com/…">
                            </div>
                            <div class="col-12 form-text mt-1">Les icônes s'affichent sur la carte du lien seulement si l'adresse est renseignée.</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description <span class="text-muted fw-normal">(facultatif)</span></label>
                            <textarea id="description" name="description" rows="3" maxlength="1000"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="ex. Calendrier des compétitions, résultats et classements officiels.">{{ old('description', $link->description) }}</textarea>
                            <div class="form-text">Une ou deux phrases pour dire ce qu'on trouve sur ce site.</div>
                        </div>

                        <div class="mb-3">
                            <label for="keywords" class="form-label fw-semibold">Mots-clés <span class="text-muted fw-normal">(facultatif)</span></label>
                            <input type="text" id="keywords" name="keywords" maxlength="500"
                                   class="form-control @error('keywords') is-invalid @enderror"
                                   value="{{ old('keywords', $link->keywords) }}" placeholder="ex. résultats, licence, FFN">
                            <div class="form-text">Séparés par des virgules. Ils s'affichent sous le lien.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-sm-5">
                                <label for="sort_order" class="form-label fw-semibold">Ordre dans la catégorie</label>
                                <input type="number" id="sort_order" name="sort_order" min="0" max="65000"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       value="{{ old('sort_order', $link->sort_order) }}">
                            </div>
                            <div class="col-sm-7 d-flex align-items-end">
                                <div class="form-check form-switch mb-2">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                           @checked(old('is_active', $link->is_active))>
                                    <label class="form-check-label" for="is_active">Afficher ce lien</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Image <span class="text-muted fw-normal">(facultatif)</span></h2>
                        <div class="links-image-preview mb-3">
                            <img src="{{ $image }}" alt="" id="image-preview" @if (! $image) hidden @endif>
                            <span class="text-muted small text-center px-3" id="image-empty" @if ($image) hidden @endif>
                                <i class="fas fa-globe fa-2x d-block mb-2" aria-hidden="true"></i>
                                Sans image, l'icône du site s'affiche.
                            </span>
                        </div>
                        <div class="input-group">
                            <input type="text" id="image" name="image" maxlength="500"
                                   class="form-control @error('image') is-invalid @enderror"
                                   value="{{ $image }}" placeholder="Choisir une image…" aria-label="Image du lien">
                            <button type="button" class="btn btn-primary" onclick="openMediaSelector('image', 'image-preview')" title="Choisir dans la médiathèque">
                                <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                            </button>
                        </div>
                        <div class="form-text">Logo du site ou capture d'écran, de préférence en format paysage.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route('admin.links.items.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .links-image-preview {
        height: 150px;
        border: 1px dashed #ced4da;
        border-radius: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .links-image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script>
    // La médiathèque (media-selector.js) est chargée par layouts.admin.
    document.addEventListener('DOMContentLoaded', function () {
        var champ = document.getElementById('image');
        var apercu = document.getElementById('image-preview');
        var vide = document.getElementById('image-empty');
        function maj() {
            var url = champ.value.trim();
            if (url) { apercu.src = url; }
            apercu.hidden = url === '';
            vide.hidden = url !== '';
        }
        champ.addEventListener('input', maj);
        champ.addEventListener('change', maj);
        new MutationObserver(function () { vide.hidden = !apercu.hidden && apercu.getAttribute('src') !== ''; })
            .observe(apercu, { attributes: true, attributeFilter: ['src', 'hidden', 'style'] });
    });
</script>
@endpush
