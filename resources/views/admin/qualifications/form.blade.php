@extends('layouts.admin')

@section('title', $grid->exists ? $grid->name : 'Nouvelle grille de qualification')

@php
    $categories = old('categories', $grid->exists
        ? $grid->categories->map(fn ($c) => ['id' => $c->id, 'label' => $c->label, 'from' => $c->birth_year_from, 'to' => $c->birth_year_to])->all()
        : []);
    $categories = array_values($categories);
    $categories[] = ['id' => null, 'label' => '', 'from' => null, 'to' => null];   // ligne vide pour ajouter
@endphp

@section('content')
<div class="container-lg">
    @if ($grid->exists)
        @include('admin.qualifications._nav')
    @else
        <p class="small text-muted mb-1"><a href="{{ route('admin.qualifications.index') }}">Grilles de qualification</a></p>
        <h1 class="h3 mb-3">Nouvelle grille de qualification</h1>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
    @endif

    <form method="POST" action="{{ $grid->exists ? route('admin.qualifications.update', $grid) : route('admin.qualifications.store') }}">
        @csrf
        @if ($grid->exists) @method('PUT') @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">La grille</h2>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="name" class="form-label">Nom <span class="text-danger" aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $grid->name) }}" class="form-control" maxlength="150" required>
                    </div>
                    <div class="col-md-4">
                        <label for="sort_order" class="form-label">Ordre d'affichage</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $grid->sort_order ?? 0) }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-control" maxlength="5000">{{ old('description', $grid->description) }}</textarea>
                        <div class="form-text">Affichée sur la page publique : compétition concernée, conditions, source de la grille…</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label for="period_start" class="form-label">Performances prises en compte du</label>
                        <input type="date" id="period_start" name="period_start" value="{{ old('period_start', $grid->period_start?->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label for="period_end" class="form-label">au</label>
                        <input type="date" id="period_end" name="period_end" value="{{ old('period_end', $grid->period_end?->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" @checked(old('is_published', $grid->is_published))>
                            <label class="form-check-label" for="is_published">Publier sur le site</label>
                        </div>
                    </div>
                </div>
                <p class="small text-muted mt-2 mb-0">Sans période, toutes les performances enregistrées sont prises en compte pour la liste des nageurs qualifiés.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-1">Catégories d'âge</h2>
                <p class="small text-muted">Une colonne de la grille par catégorie. Années de naissance : laissez « de » vide pour « et avant », « à » vide pour « et après ». Exemple : U17 et +, de (vide) à 2009.</p>

                <div class="table-responsive position-relative">
                    <table class="table table-sm align-middle mb-2" id="categories">
                        <thead><tr>
                            <th scope="col">Libellé</th>
                            <th scope="col">Née de</th>
                            <th scope="col">à</th>
                            <th scope="col" class="text-center">Supprimer</th>
                        </tr></thead>
                        <tbody>
                            @foreach ($categories as $i => $c)
                                <tr>
                                    <td>
                                        <input type="hidden" name="categories[{{ $i }}][id]" value="{{ $c['id'] ?? '' }}">
                                        <label class="visually-hidden" for="cat-{{ $i }}-label">Libellé de la catégorie {{ $i + 1 }}</label>
                                        <input type="text" id="cat-{{ $i }}-label" name="categories[{{ $i }}][label]" value="{{ $c['label'] ?? '' }}" class="form-control form-control-sm" maxlength="60" placeholder="ex. U12">
                                    </td>
                                    <td>
                                        <label class="visually-hidden" for="cat-{{ $i }}-from">Née à partir de</label>
                                        <input type="number" id="cat-{{ $i }}-from" name="categories[{{ $i }}][from]" value="{{ $c['from'] ?? '' }}" min="1900" max="2100" class="form-control form-control-sm" placeholder="2014">
                                    </td>
                                    <td>
                                        <label class="visually-hidden" for="cat-{{ $i }}-to">Née jusqu'à</label>
                                        <input type="number" id="cat-{{ $i }}-to" name="categories[{{ $i }}][to]" value="{{ $c['to'] ?? '' }}" min="1900" max="2100" class="form-control form-control-sm" placeholder="2014">
                                    </td>
                                    <td class="text-center">
                                        @if (! empty($c['id']))
                                            <input type="checkbox" class="form-check-input" name="categories[{{ $i }}][delete]" value="1" aria-label="Supprimer la catégorie {{ $c['label'] }} et ses temps">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="ajouter-categorie"><i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une catégorie</button>
                <p class="small text-muted mt-2 mb-0">Supprimer une catégorie efface aussi ses temps. Les catégories peuvent aussi être créées automatiquement à l'import, à partir de l'en-tête du tableau (ex. « U12 (2014) »).</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary"><i class="fas fa-save me-1" aria-hidden="true"></i>{{ $grid->exists ? 'Enregistrer' : 'Créer la grille' }}</button>
            <a href="{{ route('admin.qualifications.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('ajouter-categorie').addEventListener('click', function () {
    var corps = document.querySelector('#categories tbody');
    var modele = corps.lastElementChild;
    var n = corps.children.length;
    var ligne = modele.cloneNode(true);
    ligne.querySelectorAll('input').forEach(function (el) {
        el.name = el.name.replace(/categories\[\d+\]/, 'categories[' + n + ']');
        if (el.id) el.id = el.id.replace(/cat-\d+-/, 'cat-' + n + '-');
        if (el.type !== 'checkbox') el.value = '';
    });
    ligne.querySelectorAll('label').forEach(function (el) { el.htmlFor = el.htmlFor.replace(/cat-\d+-/, 'cat-' + n + '-'); });
    ligne.querySelectorAll('input[type=checkbox]').forEach(function (el) { el.remove(); });
    corps.appendChild(ligne);
    ligne.querySelector('input[type=text]').focus();
});
</script>
@endpush
