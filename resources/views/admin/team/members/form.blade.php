@extends('layouts.admin')

@section('title', $member->exists ? 'Modifier un membre' : 'Ajouter un membre')

@php
    $lignes = old('assignments', $rows);
    $photo = old('photo', $member->photo);
@endphp

@section('content')
<div class="team-admin">
    @include('admin.team._nav', ['active' => 'members'])

    <form method="POST" action="{{ $member->exists ? route('admin.team.members.update', $member) : route('admin.team.members.store') }}">
        @csrf
        @if ($member->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Identité --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">{{ $member->exists ? 'Modifier « ' . $member->full_name . ' »' : 'Nouveau membre' }}</h2>

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="first_name" class="form-label fw-semibold">Prénom</label>
                                <input type="text" id="first_name" name="first_name" maxlength="80" required autocomplete="off"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $member->first_name) }}">
                            </div>
                            <div class="col-sm-6">
                                <label for="last_name" class="form-label fw-semibold">Nom</label>
                                <input type="text" id="last_name" name="last_name" maxlength="80" required autocomplete="off"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $member->last_name) }}">
                            </div>
                        </div>

                        <div class="form-check form-switch mt-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $member->is_active))>
                            <label class="form-check-label" for="is_active">Afficher sur la page « L'équipe du club »</label>
                        </div>
                    </div>
                </div>

                {{-- Sections et fonctions --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-1">Sections et fonctions</h2>
                        <p class="small text-muted mb-3">
                            Une ligne par section. Une même personne peut être, par exemple, « Trésorière » au comité directeur et « Juge-arbitre » chez les officiels.
                            L'ordre range les personnes dans la section (1 = en premier) ; à ordre égal, elles sont classées par nom.
                        </p>

                        <div id="assignments" class="@error('assignments') is-invalid @enderror">
                            @foreach ($lignes as $i => $ligne)
                                @include('admin.team.members._assignment', ['i' => $i, 'ligne' => $ligne, 'sections' => $sections])
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-assignment">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une section
                        </button>

                        <template id="assignment-template">
                            @include('admin.team.members._assignment', ['i' => '__INDEX__', 'ligne' => ['section_id' => null, 'position' => '', 'sort_order' => 0], 'sections' => $sections])
                        </template>
                    </div>
                </div>

                {{-- Liens --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-1">Liens <span class="text-muted fw-normal">(facultatif)</span></h2>
                        <p class="small text-muted mb-3">Adresses complètes, commençant par https://. Seuls les liens renseignés s'affichent.</p>

                        <div class="row g-3">
                            @foreach (App\Models\TeamMember::LINKS as $champ => [$libelle, $icone])
                                <div class="col-md-6">
                                    <label for="{{ $champ }}" class="form-label fw-semibold small">
                                        <i class="{{ $icone }} fa-fw me-1" aria-hidden="true"></i>{{ $libelle }}
                                    </label>
                                    <input type="url" id="{{ $champ }}" name="{{ $champ }}" maxlength="500" inputmode="url"
                                           class="form-control @error($champ) is-invalid @enderror"
                                           value="{{ old($champ, $member->{$champ}) }}"
                                           placeholder="{{ match ($champ) {
                                               'facebook_url' => 'https://www.facebook.com/…',
                                               'instagram_url' => 'https://www.instagram.com/…',
                                               'linkedin_url' => 'https://www.linkedin.com/in/…',
                                               default => 'https://…',
                                           } }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Photo --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3">Photo <span class="text-muted fw-normal">(facultatif)</span></h2>
                        <div class="team-photo-preview mb-3">
                            <img src="{{ $photo }}" alt="" id="photo-preview" @if (! $photo) hidden @endif>
                            <span class="team-photo-empty" id="photo-empty" @if ($photo) hidden @endif aria-hidden="true">
                                <i class="fas fa-user fa-3x"></i>
                            </span>
                        </div>
                        <div class="input-group">
                            <input type="text" id="photo" name="photo" maxlength="500"
                                   class="form-control @error('photo') is-invalid @enderror"
                                   value="{{ $photo }}" placeholder="Choisir une photo…" aria-label="Photo">
                            <button type="button" class="btn btn-primary" onclick="openMediaSelector('photo', 'photo-preview')" title="Choisir dans la médiathèque">
                                <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                            </button>
                        </div>
                        <div class="form-text">
                            Photo cadrée sur le visage, de préférence carrée. Sans photo, les initiales s'affichent.
                            Pensez à demander l'accord de la personne avant de publier sa photo.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route('admin.team.members.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .team-photo-preview {
        width: 180px;
        height: 180px;
        margin: 0 auto;
        border: 1px dashed #ced4da;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .team-photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .team-photo-empty {
        color: #ced4da;
    }

    .team-assignment-remove {
        padding-top: 2rem;   /* aligné sur les champs, sous leurs libellés */
    }

    .team-assignment + .team-assignment {
        border-top: 1px solid #f1f3f5;
        padding-top: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // La médiathèque (media-selector.js) est chargée par layouts.admin.
    document.addEventListener('DOMContentLoaded', function () {
        // Aperçu de la photo
        var champ = document.getElementById('photo');
        var apercu = document.getElementById('photo-preview');
        var vide = document.getElementById('photo-empty');
        function majApercu() {
            var url = champ.value.trim();
            if (url) { apercu.src = url; }
            apercu.hidden = url === '';
            vide.hidden = url !== '';
        }
        champ.addEventListener('input', majApercu);
        champ.addEventListener('change', majApercu);
        new MutationObserver(function () { vide.hidden = !apercu.hidden && apercu.getAttribute('src') !== ''; })
            .observe(apercu, { attributes: true, attributeFilter: ['src', 'hidden', 'style'] });

        // Lignes « section + fonction »
        var liste = document.getElementById('assignments');
        var modele = document.getElementById('assignment-template');
        var compteur = 1000;

        document.getElementById('add-assignment').addEventListener('click', function () {
            var html = modele.innerHTML.replace(/__INDEX__/g, String(compteur++));
            liste.insertAdjacentHTML('beforeend', html);
            var nouvelle = liste.lastElementChild;
            nouvelle.querySelector('select').focus();
            majBoutons();
        });

        liste.addEventListener('click', function (e) {
            var bouton = e.target.closest('.remove-assignment');
            if (!bouton) return;
            bouton.closest('.team-assignment').remove();
            majBoutons();
            var premier = liste.querySelector('select');
            if (premier) premier.focus();
        });

        // Il faut garder au moins une ligne
        function majBoutons() {
            var lignes = liste.querySelectorAll('.team-assignment');
            lignes.forEach(function (l) {
                l.querySelector('.remove-assignment').disabled = lignes.length === 1;
            });
        }
        majBoutons();
    });
</script>
@endpush
