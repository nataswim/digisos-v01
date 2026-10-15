@extends($layout)

@section('title', $partner->exists ? 'Modifier un partenaire' : 'Ajouter un partenaire')

@section('content')
<div class="partners-admin">
    @include('admin.partners._header')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $partner->exists ? route($area . '.partners.update', $partner) : route($area . '.partners.store') }}">
        @csrf
        @if ($partner->exists) @method('PUT') @endif

        <div class="row g-4">
            {{-- Contenu --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">{{ $partner->exists ? 'Modifier « ' . $partner->name . ' »' : 'Nouveau partenaire' }}</h2>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nom du partenaire</label>
                            <input type="text" id="name" name="name" maxlength="150" required
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $partner->name) }}" placeholder="ex. Boulangerie du Bocage">
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label fw-semibold">Phrase d'accroche <span class="text-muted fw-normal">(facultatif)</span></label>
                            <input type="text" id="summary" name="summary" maxlength="255"
                                   class="form-control @error('summary') is-invalid @enderror"
                                   value="{{ old('summary', $partner->summary) }}"
                                   placeholder="ex. Fournit les bonnets du club depuis 2019">
                            <div class="form-text">Affichée sous le logo sur la page des partenaires.</div>
                        </div>

                        <div class="mb-4">
                            <label for="presentation" class="form-label fw-semibold">Présentation <span class="text-muted fw-normal">(facultatif)</span></label>
                            <div id="presentation-editor" class="partners-editor partners-editor--lg @error('presentation') is-invalid @enderror"></div>
                            <textarea id="presentation" name="presentation" class="d-none">{{ old('presentation', $partner->presentation_html) }}</textarea>
                            <div class="form-text">Activité, histoire, lien avec le Bocage Bressuirais… Le bouton image de la barre d'outils insère une image de la médiathèque.</div>
                        </div>

                        <div class="mb-0">
                            <label for="actions" class="form-label fw-semibold">Actions auprès du club <span class="text-muted fw-normal">(facultatif)</span></label>
                            <div id="actions-editor" class="partners-editor @error('actions') is-invalid @enderror"></div>
                            <textarea id="actions" name="actions" class="d-none">{{ old('actions', $partner->actions_html) }}</textarea>
                            <div class="form-text">Conseil : utilisez la liste à puces de la barre d'outils, une action par puce.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Réglages --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Logo</h2>

                        <div class="partners-form-preview mb-3">
                            <img src="{{ old('logo', $partner->logo) }}" alt="Aperçu du logo" id="logo-preview" @if (! old('logo', $partner->logo)) hidden @endif>
                            <span class="text-muted small" id="logo-empty" @if (old('logo', $partner->logo)) hidden @endif>Aucun logo</span>
                        </div>

                        <label for="logo" class="form-label fw-semibold">Image de la médiathèque</label>
                        <div class="input-group">
                            <input type="text" id="logo" name="logo" maxlength="500"
                                   class="form-control @error('logo') is-invalid @enderror"
                                   value="{{ old('logo', $partner->logo) }}" placeholder="Choisir une image…">
                            <button type="button" class="btn btn-primary" onclick="openMediaSelector('logo', 'logo-preview')"
                                    title="Choisir dans la médiathèque">
                                <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                            </button>
                        </div>
                        <div class="form-text mb-3">Cliquez sur <i class="fas fa-images" aria-hidden="true"></i> pour choisir un logo déjà présent sur le site.</div>

                        <details class="partners-upload" @if ($errors->has('logo_file')) open @endif>
                            <summary class="small fw-semibold">… ou envoyer une image depuis l'ordinateur</summary>
                            <input type="file" id="logo_file" name="logo_file" accept="image/png,image/jpeg,image/webp,image/gif"
                                   class="form-control form-control-sm mt-2 @error('logo_file') is-invalid @enderror"
                                   aria-label="Envoyer une image depuis l'ordinateur">
                            <div class="form-text">PNG, JPG, WebP ou GIF, 2 Mo maximum. Un fond transparent ou blanc rend mieux.</div>
                        </details>

                        @if ($partner->logo)
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                <label class="form-check-label" for="remove_logo">Retirer le logo</label>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Partenariat</h2>

                        <div class="mb-3">
                            <label for="level" class="form-label fw-semibold">Niveau</label>
                            <select id="level" name="level" class="form-select @error('level') is-invalid @enderror" required>
                                @foreach (\App\Models\Partner::LEVELS as $cle => $info)
                                    <option value="{{ $cle }}" @selected(old('level', $partner->level) === $cle)>{{ $info['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label fw-semibold">Type <span class="text-muted fw-normal">(facultatif)</span></label>
                            <select id="category" name="category" class="form-select @error('category') is-invalid @enderror">
                                <option value="">—</option>
                                @foreach (\App\Models\Partner::CATEGORIES as $cle => $libelle)
                                    <option value="{{ $cle }}" @selected(old('category', $partner->category) === $cle)>{{ $libelle }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label fw-semibold">Site internet <span class="text-muted fw-normal">(facultatif)</span></label>
                            <input type="url" id="website" name="website" maxlength="255"
                                   class="form-control @error('website') is-invalid @enderror"
                                   value="{{ old('website', $partner->website) }}" placeholder="https://www.exemple.fr">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="partner_since" class="form-label fw-semibold">Partenaire depuis</label>
                                <input type="number" id="partner_since" name="partner_since" min="1954" max="{{ now()->year + 1 }}"
                                       class="form-control @error('partner_since') is-invalid @enderror"
                                       value="{{ old('partner_since', $partner->partner_since) }}" placeholder="{{ now()->year }}">
                            </div>
                            <div class="col-6">
                                <label for="sort_order" class="form-label fw-semibold">Ordre</label>
                                <input type="number" id="sort_order" name="sort_order" min="0" max="65000"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       value="{{ old('sort_order', $partner->sort_order) }}">
                            </div>
                        </div>
                        <div class="form-text mb-3">L'ordre classe les partenaires à l'intérieur de leur niveau (le plus petit en premier).</div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $partner->is_active))>
                            <label class="form-check-label" for="is_active">Afficher ce partenaire sur le site</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route($area . '.partners.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .partners-form-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 140px;
        background: #fff;
        border: 1px dashed #ced4da;
        border-radius: 8px;
        padding: 10px;
    }

    .partners-editor {
        height: 280px;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 0 0 0.375rem 0.375rem;
    }

    .partners-editor--lg {
        height: 380px;
    }

    .partners-editor.is-invalid,
    .partners-editor.is-invalid + .ql-toolbar {
        border-color: #dc3545;
    }

    .partners-upload summary {
        cursor: pointer;
        color: #2f80b8;
    }

    .partners-form-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script>
    // L'éditeur de texte (quill-advanced.js) et la médiathèque (media-selector.js)
    // sont chargés par les gabarits layouts.admin et layouts.editor, comme pour les fiches.
    document.addEventListener('DOMContentLoaded', function () {
        var editeurs = {};
        if (typeof initQuillEditor === 'function') {
            editeurs.presentation = initQuillEditor('#presentation-editor', 'presentation');
            editeurs.actions = initQuillEditor('#actions-editor', 'actions');
        }

        // Recopie le contenu des éditeurs dans le formulaire à l'enregistrement
        var formulaire = document.querySelector('form[enctype="multipart/form-data"]');
        if (formulaire) {
            formulaire.addEventListener('submit', function () {
                Object.keys(editeurs).forEach(function (champ) {
                    if (editeurs[champ]) {
                        document.getElementById(champ).value = editeurs[champ].root.innerHTML;
                    }
                });
            });
        }

        // Aperçu du logo : image de la médiathèque, adresse saisie ou fichier choisi
        var champ = document.getElementById('logo');
        var fichier = document.getElementById('logo_file');
        var apercu = document.getElementById('logo-preview');
        var vide = document.getElementById('logo-empty');

        function afficher(src) {
            apercu.hidden = !src;
            vide.hidden = !!src;
            if (src) { apercu.src = src; }
        }

        champ && champ.addEventListener('input', function () { afficher(champ.value.trim()); });
        fichier && fichier.addEventListener('change', function () {
            if (fichier.files && fichier.files[0]) { afficher(URL.createObjectURL(fichier.files[0])); }
        });
    });
</script>
@endpush
