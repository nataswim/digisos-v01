@extends('layouts.admin')

@section('title', $product->exists ? 'Modifier un produit' : 'Ajouter un produit')

@php
    // Galerie : valeurs du formulaire en cas d'erreur, sinon celles du produit ; au moins une ligne vide
    $galerie = old('gallery', $product->gallery ?? []);
    if (empty($galerie)) {
        $galerie = [''];
    }

    $prixMin = old('price_min', $product->price_min !== null ? rtrim(rtrim(str_replace('.', ',', (string) $product->price_min), '0'), ',') : '');
    $prixMax = old('price_max', $product->price_max !== null ? rtrim(rtrim(str_replace('.', ',', (string) $product->price_max), '0'), ',') : '');
@endphp

@section('content')
<div class="shop-admin">
    @include('admin.shop._nav', ['active' => 'products'])

    <form method="POST" action="{{ $product->exists ? route('admin.shop.products.update', $product) : route('admin.shop.products.store') }}" id="shop-product-form">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <div class="row g-4">
            {{-- Contenu --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">{{ $product->exists ? 'Modifier « ' . $product->name . ' »' : 'Nouveau produit' }}</h2>

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label fw-semibold">Nom du produit</label>
                                <input type="text" id="name" name="name" maxlength="150" required
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $product->name) }}" placeholder="ex. Bonnet silicone CNBB">
                            </div>
                            <div class="col-md-4">
                                <label for="shop_category_id" class="form-label fw-semibold">Catégorie</label>
                                <select id="shop_category_id" name="shop_category_id" required
                                        class="form-select @error('shop_category_id') is-invalid @enderror">
                                    <option value="">Choisir…</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected((int) old('shop_category_id', $product->shop_category_id) === $cat->id)>
                                            {{ $cat->name }}@unless ($cat->is_active) (masquée)@endunless
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="summary" class="form-label fw-semibold">Phrase d'accroche <span class="text-muted fw-normal">(facultatif)</span></label>
                            <input type="text" id="summary" name="summary" maxlength="255"
                                   class="form-control @error('summary') is-invalid @enderror"
                                   value="{{ old('summary', $product->summary) }}" placeholder="ex. Aux couleurs du club, taille unique">
                            <div class="form-text">Affichée sous le nom, sur la carte du produit.</div>
                        </div>

                        <div class="mb-0">
                            <label for="description" class="form-label fw-semibold">Description <span class="text-muted fw-normal">(facultatif)</span></label>
                            <div id="description-editor" class="shop-editor @error('description') is-invalid @enderror"></div>
                            <textarea id="description" name="description" class="d-none">{{ old('description', $product->description) }}</textarea>
                            <div class="form-text">Matière, tailles disponibles, conseils d'entretien, délais…</div>
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Images</h2>

                        <label for="image" class="form-label fw-semibold">Image principale</label>
                        <div class="d-flex gap-3 align-items-start mb-4">
                            <div class="shop-preview">
                                <img src="{{ old('image', $product->image) }}" alt="" id="image-preview" @if (! old('image', $product->image)) hidden @endif>
                            </div>
                            <div class="flex-grow-1">
                                <div class="input-group">
                                    <input type="text" id="image" name="image" maxlength="500" data-preview="image-preview"
                                           class="form-control shop-image-input @error('image') is-invalid @enderror"
                                           value="{{ old('image', $product->image) }}" placeholder="Choisir une image…">
                                    <button type="button" class="btn btn-primary" onclick="openMediaSelector('image', 'image-preview')" title="Choisir dans la médiathèque">
                                        <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                                    </button>
                                </div>
                                <div class="form-text">Utilisée sur la carte du produit. Une photo carrée sur fond clair rend le mieux.</div>
                            </div>
                        </div>

                        <p class="form-label fw-semibold mb-2">Galerie <span class="text-muted fw-normal">(facultatif, 12 images au maximum)</span></p>
                        <div id="gallery-rows">
                            @foreach ($galerie as $i => $url)
                                <div class="d-flex gap-3 align-items-center mb-2 shop-gallery-row">
                                    <div class="shop-preview shop-preview--sm">
                                        <img src="{{ $url }}" alt="" id="gallery-preview-{{ $i }}" @if (! $url) hidden @endif>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" id="gallery-{{ $i }}" name="gallery[]" maxlength="500" data-preview="gallery-preview-{{ $i }}"
                                               class="form-control shop-image-input @error('gallery.' . $i) is-invalid @enderror"
                                               value="{{ $url }}" placeholder="Image supplémentaire…" aria-label="Image de la galerie">
                                        <button type="button" class="btn btn-outline-primary shop-gallery-pick" title="Choisir dans la médiathèque">
                                            <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger shop-gallery-remove" title="Retirer cette image">
                                            <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Retirer cette image</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="gallery-add">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une image
                        </button>
                    </div>
                </div>
            </div>

            {{-- Prix, commande, réglages --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Prix indicatif</h2>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label for="price_min" class="form-label">Prix (€)</label>
                                <input type="text" inputmode="decimal" id="price_min" name="price_min"
                                       class="form-control @error('price_min') is-invalid @enderror"
                                       value="{{ $prixMin }}" placeholder="12">
                            </div>
                            <div class="col-6">
                                <label for="price_max" class="form-label">à (€) <span class="text-muted">facultatif</span></label>
                                <input type="text" inputmode="decimal" id="price_max" name="price_max"
                                       class="form-control @error('price_max') is-invalid @enderror"
                                       value="{{ $prixMax }}" placeholder="15">
                            </div>
                        </div>
                        <div class="form-text mb-3">
                            Un seul prix : « 12 € ». Les deux : fourchette « 10 à 15 € ».
                            Aperçu : <strong id="price-preview">—</strong>
                        </div>

                        <label for="price_note" class="form-label">Précision <span class="text-muted">(facultatif)</span></label>
                        <input type="text" id="price_note" name="price_note" maxlength="100"
                               class="form-control @error('price_note') is-invalid @enderror"
                               value="{{ old('price_note', $product->price_note) }}" placeholder="ex. selon la taille">
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Commande</h2>

                        <label for="order_url" class="form-label fw-semibold">Lien pour commander</label>
                        <input type="url" id="order_url" name="order_url" maxlength="500"
                               class="form-control @error('order_url') is-invalid @enderror"
                               value="{{ old('order_url', $product->order_url) }}" placeholder="https://…">
                        <div class="form-text mb-3">Le site externe où se fait la commande (HelloAsso, boutique du fournisseur…). Il s'ouvre dans un nouvel onglet.</div>

                        <label for="order_label" class="form-label">Texte du bouton <span class="text-muted">(facultatif)</span></label>
                        <input type="text" id="order_label" name="order_label" maxlength="50"
                               class="form-control @error('order_label') is-invalid @enderror"
                               value="{{ old('order_label', $product->order_label) }}" placeholder="Commander">
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Affichage</h2>

                        <label for="badge" class="form-label">Badge <span class="text-muted">(facultatif)</span></label>
                        <select id="badge" name="badge" class="form-select mb-3 @error('badge') is-invalid @enderror">
                            <option value="">Aucun</option>
                            @foreach (\App\Models\ShopProduct::BADGES as $cle => $info)
                                <option value="{{ $cle }}" @selected(old('badge', $product->badge) === $cle)>{{ $info['label'] }}</option>
                            @endforeach
                        </select>
                        <div class="form-text mt-n2 mb-3">« Épuisé » désactive le bouton de commande.</div>

                        <label for="sort_order" class="form-label">Ordre dans la catégorie</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" max="65000"
                               class="form-control mb-3 @error('sort_order') is-invalid @enderror"
                               value="{{ old('sort_order', $product->sort_order) }}">

                        <div class="form-check form-switch mb-2">
                            <input type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1"
                                   @checked(old('is_featured', $product->is_featured))>
                            <label class="form-check-label" for="is_featured">Mettre en avant (en tête de sa catégorie)</label>
                        </div>
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $product->is_active))>
                            <label class="form-check-label" for="is_active">Afficher ce produit sur le site</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route('admin.shop.products.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>

{{-- Modèle d'une ligne de galerie (copié par « Ajouter une image ») --}}
<template id="gallery-row-template">
    <div class="d-flex gap-3 align-items-center mb-2 shop-gallery-row">
        <div class="shop-preview shop-preview--sm"><img src="" alt="" hidden></div>
        <div class="input-group">
            <input type="text" name="gallery[]" maxlength="500" class="form-control shop-image-input" placeholder="Image supplémentaire…" aria-label="Image de la galerie">
            <button type="button" class="btn btn-outline-primary shop-gallery-pick" title="Choisir dans la médiathèque">
                <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
            </button>
            <button type="button" class="btn btn-outline-danger shop-gallery-remove" title="Retirer cette image">
                <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Retirer cette image</span>
            </button>
        </div>
    </div>
</template>
@endsection

@push('styles')
<style>
    .shop-editor {
        height: 320px;
        background: #fff;
    }

    .shop-preview {
        flex: 0 0 auto;
        width: 96px;
        height: 96px;
        border: 1px dashed #ced4da;
        border-radius: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .shop-preview--sm {
        width: 56px;
        height: 56px;
    }

    .shop-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
    // L'éditeur de texte (quill-advanced.js) et la médiathèque (media-selector.js) sont chargés par layouts.admin.
    document.addEventListener('DOMContentLoaded', function () {
        var editeur = typeof initQuillEditor === 'function'
            ? initQuillEditor('#description-editor', 'description')
            : null;

        document.getElementById('shop-product-form').addEventListener('submit', function () {
            if (editeur) {
                document.getElementById('description').value = editeur.root.innerHTML;
            }
        });

        // ---- Aperçu des images (choix dans la médiathèque ou adresse saisie)
        document.addEventListener('input', function (event) {
            var champ = event.target;
            if (!champ.classList || !champ.classList.contains('shop-image-input')) return;
            var apercu = document.getElementById(champ.dataset.preview);
            if (!apercu) return;
            apercu.src = champ.value.trim();
            apercu.hidden = champ.value.trim() === '';
        });

        // ---- Galerie : ajout, choix, retrait de lignes
        var lignes = document.getElementById('gallery-rows');
        var modele = document.getElementById('gallery-row-template');
        var compteur = lignes.querySelectorAll('.shop-gallery-row').length;

        document.getElementById('gallery-add').addEventListener('click', function () {
            if (lignes.querySelectorAll('.shop-gallery-row').length >= 12) return;
            var ligne = modele.content.firstElementChild.cloneNode(true);
            var champ = ligne.querySelector('input');
            var apercu = ligne.querySelector('img');
            champ.id = 'gallery-' + compteur;
            apercu.id = 'gallery-preview-' + compteur;
            champ.dataset.preview = apercu.id;
            compteur++;
            lignes.appendChild(ligne);
            champ.focus();
        });

        lignes.addEventListener('click', function (event) {
            var bouton = event.target.closest('button');
            if (!bouton) return;
            var ligne = bouton.closest('.shop-gallery-row');
            var champ = ligne.querySelector('input');

            if (bouton.classList.contains('shop-gallery-pick')) {
                openMediaSelector(champ.id, champ.dataset.preview);
            } else if (bouton.classList.contains('shop-gallery-remove')) {
                if (lignes.querySelectorAll('.shop-gallery-row').length > 1) {
                    ligne.remove();
                } else {
                    champ.value = '';
                    champ.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        });

        // ---- Aperçu du prix, comme sur le site
        var prixMin = document.getElementById('price_min');
        var prixMax = document.getElementById('price_max');
        var apercuPrix = document.getElementById('price-preview');

        function nombre(valeur) {
            var n = parseFloat(String(valeur).replace(/[\s€]/g, '').replace(',', '.'));
            return isNaN(n) ? null : n;
        }

        function euros(n) {
            return (Number.isInteger(n) ? String(n) : n.toFixed(2).replace('.', ','));
        }

        function majPrix() {
            var a = nombre(prixMin.value), b = nombre(prixMax.value);
            if (a === null && b === null) { apercuPrix.textContent = '—'; return; }
            if (a !== null && b !== null && b > a) { apercuPrix.textContent = euros(a) + ' à ' + euros(b) + ' €'; return; }
            apercuPrix.textContent = euros(a !== null ? a : b) + ' €';
        }

        prixMin.addEventListener('input', majPrix);
        prixMax.addEventListener('input', majPrix);
        majPrix();
    });
</script>
@endpush
