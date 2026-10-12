@extends($layout)

@section('title', $group->exists ? 'Modifier un groupe' : 'Ajouter un groupe')

@section('content')
<div class="planning-admin">
    @include('admin.planning._nav', ['active' => 'groups'])

    <form method="POST"
          action="{{ $group->exists ? route($area . '.planning.groups.update', $group) : route($area . '.planning.groups.store') }}">
        @csrf
        @if ($group->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">{{ $group->exists ? 'Modifier le groupe' : 'Nouveau groupe' }}</h2>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nom du groupe</label>
                            <input type="text" id="name" name="name" maxlength="100" required
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $group->name) }}" placeholder="ex. Relève 1">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-7">
                                <label for="section" class="form-label fw-semibold">Section</label>
                                <select id="section" name="section" class="form-select @error('section') is-invalid @enderror" required>
                                    @foreach (array_keys(\App\Models\PlanningGroup::SECTIONS) as $section)
                                        <option value="{{ $section }}" @selected(old('section', $group->section) === $section)>{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                                <input type="number" id="sort_order" name="sort_order" min="0" max="65000"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       value="{{ old('sort_order', $group->sort_order) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label fw-semibold">Couleur sur le planning</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" id="color" name="color" class="form-control form-control-color"
                                       value="{{ old('color', $group->color ?: $group->background_color) }}">
                                <div class="form-check mb-0">
                                    <input type="hidden" name="use_section_color" value="0">
                                    <input class="form-check-input" type="checkbox" id="use_section_color" name="use_section_color" value="1"
                                           @checked(old('use_section_color', ! $group->color))>
                                    <label class="form-check-label" for="use_section_color">Utiliser la couleur de la section</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description courte <span class="text-muted fw-normal">(facultatif)</span></label>
                            <textarea id="description" name="description" rows="3" maxlength="2000"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $group->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="page_url" class="form-label fw-semibold">Lien vers la page du groupe <span class="text-muted fw-normal">(facultatif)</span></label>
                            <input type="text" id="page_url" name="page_url" maxlength="255"
                                   class="form-control @error('page_url') is-invalid @enderror"
                                   value="{{ old('page_url', $group->page_url) }}" placeholder="/pages/nos-groupes/groupe-releve-1">
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $group->is_active))>
                            <label class="form-check-label" for="is_active">Afficher ce groupe sur le site</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 fw-bold mb-0">Nageurs du groupe</h2>
                            <span class="badge bg-primary" id="nb-coches">{{ count(old('swimmers', $selected)) }}</span>
                        </div>

                        @if ($swimmers->isEmpty())
                            <p class="text-muted">
                                Aucun nageur enregistré.
                                <a href="{{ route($area . '.planning.swimmers.create') }}">Ajouter un nageur</a>
                            </p>
                        @else
                            <label for="filtre-nageurs" class="visually-hidden">Filtrer les nageurs</label>
                            <input type="search" id="filtre-nageurs" class="form-control form-control-sm mb-2" placeholder="Filtrer par nom…">

                            <div class="border rounded p-2" style="max-height: 420px; overflow-y: auto;" id="liste-nageurs">
                                @foreach ($swimmers as $swimmer)
                                    <div class="form-check" data-nom="{{ mb_strtolower($swimmer->first_name . ' ' . $swimmer->last_name) }}">
                                        <input class="form-check-input" type="checkbox" name="swimmers[]" value="{{ $swimmer->id }}"
                                               id="nageur-{{ $swimmer->id }}"
                                               @checked(in_array($swimmer->id, array_map('intval', old('swimmers', $selected)), true))>
                                        <label class="form-check-label" for="nageur-{{ $swimmer->id }}">
                                            {{ $swimmer->full_name }}
                                            @if ($swimmer->birth_year)<small class="text-muted">({{ $swimmer->birth_year }})</small>@endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route($area . '.planning.groups.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var filtre = document.getElementById('filtre-nageurs');
        var liste = document.getElementById('liste-nageurs');
        var compteur = document.getElementById('nb-coches');
        if (!liste) return;

        filtre && filtre.addEventListener('input', function () {
            var texte = filtre.value.trim().toLowerCase();
            liste.querySelectorAll('[data-nom]').forEach(function (ligne) {
                ligne.hidden = texte !== '' && ligne.dataset.nom.indexOf(texte) === -1;
            });
        });

        liste.addEventListener('change', function () {
            compteur.textContent = liste.querySelectorAll('input:checked').length;
        });
    })();
</script>
@endpush
