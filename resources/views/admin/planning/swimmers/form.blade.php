@extends($layout)

@section('title', $swimmer->exists ? 'Modifier un nageur' : 'Ajouter un nageur')

@section('content')
<div class="planning-admin">
    @include('admin.planning._nav', ['active' => 'swimmers'])

    <div class="card border-0 shadow-sm" style="max-width: 760px;">
        <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-4">{{ $swimmer->exists ? 'Modifier le nageur' : 'Nouveau nageur' }}</h2>

            <form method="POST"
                  action="{{ $swimmer->exists ? route($area . '.planning.swimmers.update', $swimmer) : route($area . '.planning.swimmers.store') }}">
                @csrf
                @if ($swimmer->exists) @method('PUT') @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label fw-semibold">Prénom</label>
                        <input type="text" id="first_name" name="first_name" maxlength="100" required autocomplete="off"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $swimmer->first_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label fw-semibold">Nom</label>
                        <input type="text" id="last_name" name="last_name" maxlength="100" required autocomplete="off"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name', $swimmer->last_name) }}">
                    </div>

                    {{-- Photo (médiathèque) : affichée uniquement dans l'administration --}}
                    @php $photo = old('photo', $swimmer->photo); @endphp
                    <div class="col-12">
                        <span class="form-label fw-semibold d-block mb-2">Photo <span class="text-muted fw-normal">(facultatif)</span></span>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <span class="swimmer-photo-preview" aria-hidden="true">
                                <img src="{{ $photo }}" alt="" id="photo-preview" @if (! $photo) hidden @endif>
                                <span id="photo-empty" @if ($photo) hidden @endif><i class="fas fa-user"></i></span>
                            </span>
                            <div style="flex: 1 1 240px;">
                                <div class="input-group">
                                    <input type="text" id="photo" name="photo" maxlength="500"
                                           class="form-control @error('photo') is-invalid @enderror"
                                           value="{{ $photo }}" placeholder="Choisir une photo…" aria-label="Photo du nageur">
                                    <button type="button" class="btn btn-primary" onclick="openMediaSelector('photo', 'photo-preview')" title="Choisir dans la médiathèque">
                                        <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="photo-clear" title="Retirer la photo">
                                        <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Retirer la photo</span>
                                    </button>
                                </div>
                                <div class="form-text">
                                    Visible seulement dans l'administration. Avec l'accord des parents pour un mineur.
                                    Attention : une image de la médiathèque a une adresse internet.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="birth_year" class="form-label fw-semibold">Année de naissance <span class="text-muted fw-normal">(facultatif)</span></label>
                        <input type="number" id="birth_year" name="birth_year" min="1920" max="{{ now()->year }}"
                               class="form-control @error('birth_year') is-invalid @enderror"
                               value="{{ old('birth_year', $swimmer->birth_year) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="gender" class="form-label fw-semibold">Sexe <span class="text-muted fw-normal">(engagements)</span></label>
                        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">—</option>
                            @foreach (\App\Models\PlanningSwimmer::GENDERS as $cle => $libelle)
                                <option value="{{ $cle }}" @selected(old('gender', $swimmer->gender) === $cle)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="license_number" class="form-label fw-semibold">N° de licence FFN</label>
                        <input type="text" id="license_number" name="license_number" maxlength="20" autocomplete="off"
                               class="form-control @error('license_number') is-invalid @enderror"
                               value="{{ old('license_number', $swimmer->license_number) }}">
                    </div>

                    <div class="col-12">
                        <label for="user_id" class="form-label fw-semibold">Compte du site <span class="text-muted fw-normal">(facultatif : la famille pourra répondre aux convocations)</span></label>
                        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                            <option value="">— Aucun compte lié —</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((int) old('user_id', $swimmer->user_id) === $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <span class="form-label fw-semibold d-block mb-2">Groupe(s)</span>
                        <div class="row row-cols-1 row-cols-sm-2 g-1">
                            @foreach ($groups as $group)
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="groups[]" value="{{ $group->id }}"
                                               id="groupe-{{ $group->id }}"
                                               @checked(in_array($group->id, array_map('intval', old('groups', $selected)), true))>
                                        <label class="form-check-label" for="groupe-{{ $group->id }}">
                                            {{ $group->name }} <small class="text-muted">— {{ $group->section }}</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label fw-semibold">Note interne <span class="text-muted fw-normal">(jamais affichée sur le site)</span></label>
                        <textarea id="notes" name="notes" rows="3" maxlength="2000"
                                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $swimmer->notes) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $swimmer->is_active))>
                            <label class="form-check-label" for="is_active">Nageur actif cette saison</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
                    </button>
                    @if (! ($swimmer->exists))
                        <button type="submit" name="add_another" value="1" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>Enregistrer et ajouter un autre
                        </button>
                    @endif
                    <a href="{{ route($area . '.planning.swimmers.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .swimmer-photo-preview {
        display: inline-flex;
        width: 88px;
        height: 88px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        overflow: hidden;
        background: #e8f2f9;
        color: #2f80b8;
        font-size: 2rem;
        flex-shrink: 0;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #f9be38;
    }

    .swimmer-photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
    // La médiathèque (media-selector.js) est chargée par le gabarit admin / éditeur.
    document.addEventListener('DOMContentLoaded', function () {
        var champ = document.getElementById('photo');
        var apercu = document.getElementById('photo-preview');
        var vide = document.getElementById('photo-empty');
        if (!champ) return;

        function maj() {
            var url = champ.value.trim();
            if (url) { apercu.src = url; }
            apercu.hidden = url === '';
            vide.hidden = url !== '';
        }
        champ.addEventListener('input', maj);
        champ.addEventListener('change', maj);
        document.getElementById('photo-clear').addEventListener('click', function () {
            champ.value = '';
            apercu.removeAttribute('src');
            maj();
        });
        new MutationObserver(function () { vide.hidden = !apercu.hidden && !!apercu.getAttribute('src'); })
            .observe(apercu, { attributes: true, attributeFilter: ['src', 'hidden', 'style'] });
    });
</script>
@endpush
