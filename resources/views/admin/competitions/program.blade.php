@extends($layout)

@section('title', 'Programme — ' . $competition->title)

@php
    $sexes = \App\Models\CompetitionEvent::GENDERS;
    $tours = \App\Models\CompetitionEvent::ROUNDS;
    $nages = \App\Models\SwimEvent::STROKES;
@endphp

@section('content')
<div class="competitions-admin competition-program">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-list-ol text-primary me-2" aria-hidden="true"></i>Programme des épreuves</h1>
            <p class="mb-0 text-muted"><strong class="text-body">{{ $competition->title }}</strong> — {{ $competition->date_range }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route($area . '.competitions.edit', $competition) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-edit me-1" aria-hidden="true"></i>Fiche compétition
            </a>
            <a href="{{ route($area . '.competitions.entries.index', $competition) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-users me-1" aria-hidden="true"></i>Convocations
            </a>
            <a href="{{ route('competitions.show', $competition) }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
                <i class="fas fa-eye me-1" aria-hidden="true"></i>Page publique
            </a>
        </div>
    </div>

    @if ($sessions->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5 text-center text-muted">
                <i class="fas fa-calendar-plus fa-2x mb-3 d-block text-primary" aria-hidden="true"></i>
                Aucune réunion pour l'instant. Ajoutez les réunions (date, horaires) dans l'onglet « Programme » de la fiche compétition,
                enregistrez, puis revenez ici pour cocher les épreuves.
                <div class="mt-3">
                    <a href="{{ route($area . '.competitions.edit', $competition) }}" class="btn btn-primary">Ajouter les réunions</a>
                </div>
            </div>
        </div>
    @else
        <p class="text-muted small mb-3">
            Pour chaque réunion, cochez les épreuves nagées : <strong>S</strong> = séries, <strong>FC</strong> / <strong>FB</strong> / <strong>FA</strong> = finales C, B, A.
            Cliquez sur un en-tête de colonne pour cocher ou décocher toute la colonne.
        </p>

        <form method="POST" action="{{ route($area . '.competitions.program.update', $competition) }}" id="form-grille">
            @csrf
            @method('PUT')

            <ul class="nav nav-tabs" role="tablist">
                @foreach ($sessions as $session)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="onglet-reunion-{{ $session->id }}" type="button"
                                data-bs-toggle="tab" data-bs-target="#reunion-{{ $session->id }}" role="tab"
                                aria-controls="reunion-{{ $session->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $session->label }}
                            <small class="text-muted d-block">{{ ucfirst($session->day->locale('fr')->isoFormat('ddd D MMM')) }}</small>
                            <span class="badge bg-primary rounded-pill" data-compteur="{{ $session->id }}">{{ $session->programEvents->count() }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content card border-0 border-top-0 shadow-sm mb-3">
                @foreach ($sessions as $session)
                    @php $coche = $checked[$session->id] ?? []; @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} p-3" id="reunion-{{ $session->id }}" role="tabpanel"
                         aria-labelledby="onglet-reunion-{{ $session->id }}" data-reunion="{{ $session->id }}">

                        <div class="d-flex justify-content-end mb-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-vider>
                                <i class="fas fa-eraser me-1" aria-hidden="true"></i>Tout décocher dans cette réunion
                            </button>
                        </div>

                        {{-- Épreuves individuelles --}}
                        <div class="table-responsive position-relative">
                            <table class="table table-sm table-bordered align-middle text-center grille-epreuves mb-4">
                                <caption class="visually-hidden">Épreuves individuelles — {{ $session->label }}</caption>
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" rowspan="2" class="text-start">Épreuve individuelle</th>
                                        @foreach (['F', 'M'] as $sexe)
                                            <th scope="colgroup" colspan="{{ count($tours) }}" class="sexe-{{ $sexe }}">{{ $sexes[$sexe][0] }}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach (['F', 'M'] as $sexe)
                                            @foreach ($tours as $tour => [$libelle, $court])
                                                <th scope="col" class="sexe-{{ $sexe }}">
                                                    <button type="button" class="btn btn-link btn-sm p-0 fw-semibold text-decoration-none" data-colonne="{{ $sexe }}-{{ $tour }}"
                                                            title="{{ $libelle }} {{ $sexes[$sexe][0] }} : tout cocher / décocher">{{ $court }}</button>
                                                </th>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($individualEvents as $nage => $epreuves)
                                        <tr>
                                            <th scope="rowgroup" colspan="{{ 1 + 2 * count($tours) }}" class="text-start small text-uppercase text-muted bg-light">{{ $nages[$nage] ?? $nage }}</th>
                                        </tr>
                                        @foreach ($epreuves as $epreuve)
                                            <tr>
                                                <th scope="row" class="text-start fw-semibold text-nowrap">{{ $epreuve->label }}</th>
                                                @foreach (['F', 'M'] as $sexe)
                                                    @foreach ($tours as $tour => [$libelle, $court])
                                                        <td class="sexe-{{ $sexe }}">
                                                            <input class="form-check-input" type="checkbox" data-col="{{ $sexe }}-{{ $tour }}"
                                                                   name="grid[{{ $session->id }}][{{ $epreuve->id }}][{{ $sexe }}][]" value="{{ $tour }}"
                                                                   aria-label="{{ $epreuve->label }} {{ $sexes[$sexe][0] }} — {{ $libelle }}"
                                                                   @checked(isset($coche[$epreuve->id][$sexe][$tour]))>
                                                        </td>
                                                    @endforeach
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Relais --}}
                        @if ($relayEvents->isNotEmpty())
                            <div class="table-responsive position-relative">
                                <table class="table table-sm table-bordered align-middle text-center grille-epreuves mb-0">
                                    <caption class="visually-hidden">Relais — {{ $session->label }}</caption>
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" rowspan="2" class="text-start">Relais</th>
                                            @foreach ($sexes as $sexe => [$libelleSexe])
                                                <th scope="colgroup" colspan="{{ count($tours) }}" class="sexe-{{ $sexe }}">{{ $libelleSexe }}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($sexes as $sexe => [$libelleSexe])
                                                @foreach ($tours as $tour => [$libelle, $court])
                                                    <th scope="col" class="sexe-{{ $sexe }}">
                                                        <button type="button" class="btn btn-link btn-sm p-0 fw-semibold text-decoration-none" data-colonne="R{{ $sexe }}-{{ $tour }}"
                                                                title="{{ $libelle }} relais {{ $libelleSexe }} : tout cocher / décocher">{{ $court }}</button>
                                                    </th>
                                                @endforeach
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($relayEvents as $epreuve)
                                            <tr>
                                                <th scope="row" class="text-start fw-semibold text-nowrap">{{ $epreuve->label }}</th>
                                                @foreach ($sexes as $sexe => [$libelleSexe])
                                                    @foreach ($tours as $tour => [$libelle, $court])
                                                        <td class="sexe-{{ $sexe }}">
                                                            <input class="form-check-input" type="checkbox" data-col="R{{ $sexe }}-{{ $tour }}"
                                                                   name="grid[{{ $session->id }}][{{ $epreuve->id }}][{{ $sexe }}][]" value="{{ $tour }}"
                                                                   aria-label="{{ $epreuve->label }} {{ $libelleSexe }} — {{ $libelle }}"
                                                                   @checked(isset($coche[$epreuve->id][$sexe][$tour]))>
                                                        </td>
                                                    @endforeach
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3 mb-5">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer le programme
                </button>
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="auto_sort" value="1" id="auto_sort">
                    <label class="form-check-label small" for="auto_sort">
                        Trier automatiquement l'ordre de passage (épreuve, Dames puis Messieurs, séries puis finales C, B, A)
                    </label>
                </div>
            </div>
        </form>

        {{-- Ordre de passage par réunion --}}
        <h2 class="h5 fw-bold mb-1"><i class="fas fa-sort-numeric-down text-primary me-2" aria-hidden="true"></i>Ordre de passage</h2>
        <p class="text-muted small mb-3">Utilisez les flèches pour suivre l'ordre officiel du programme, puis enregistrez chaque réunion.</p>

        <div class="row g-3">
            @foreach ($sessions as $session)
                <div class="col-lg-6" id="ordre-{{ $session->id }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-2">
                            <h3 class="h6 fw-bold mb-0">{{ $session->label }}
                                <small class="text-muted fw-normal">— {{ ucfirst($session->day->locale('fr')->isoFormat('dddd D MMMM')) }}</small>
                            </h3>
                        </div>
                        <div class="card-body">
                            @if ($session->programEvents->isEmpty())
                                <p class="text-muted small mb-0">Aucune épreuve cochée pour cette réunion.</p>
                            @else
                                <form method="POST" action="{{ route($area . '.competitions.program.reorder', [$competition, $session->id]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <ol class="list-group list-group-numbered mb-3" data-ordre>
                                        @foreach ($session->programEvents as $ligne)
                                            <li class="list-group-item d-flex align-items-center gap-2 py-1">
                                                <input type="hidden" name="order[]" value="{{ $ligne->id }}">
                                                <span class="flex-grow-1">
                                                    {{ $ligne->event->label }} <span class="text-muted">{{ $ligne->gender_label }}</span>
                                                    <span class="badge {{ $ligne->round === 'series' ? 'bg-light text-dark border' : 'bg-warning text-dark' }} ms-1">{{ $ligne->round_label }}</span>
                                                </span>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0" data-monter title="Monter">
                                                    <i class="fas fa-arrow-up" aria-hidden="true"></i><span class="visually-hidden">Monter {{ $ligne->full_label }}</span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0" data-descendre title="Descendre">
                                                    <i class="fas fa-arrow-down" aria-hidden="true"></i><span class="visually-hidden">Descendre {{ $ligne->full_label }}</span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ol>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer l'ordre
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .competition-program .grille-epreuves { min-width: 640px; }
    .competition-program .grille-epreuves th,
    .competition-program .grille-epreuves td { padding: .3rem .4rem; }
    .competition-program .grille-epreuves .form-check-input { margin: 0; width: 1.15em; height: 1.15em; cursor: pointer; }
    .competition-program .sexe-F { background-color: rgba(214, 51, 132, .05); }
    .competition-program .sexe-M { background-color: rgba(13, 110, 253, .05); }
    .competition-program .sexe-X { background-color: rgba(25, 135, 84, .05); }
    .competition-program .nav-tabs .nav-link { line-height: 1.2; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Compteur d'épreuves cochées dans l'onglet
    function compter(reunion) {
        var badge = document.querySelector('[data-compteur="' + reunion.dataset.reunion + '"]');
        if (badge) badge.textContent = reunion.querySelectorAll('input[type="checkbox"]:checked').length;
    }
    document.querySelectorAll('[data-reunion]').forEach(function (reunion) {
        reunion.addEventListener('change', function () { compter(reunion); });

        // En-tête de colonne : tout cocher / décocher
        reunion.querySelectorAll('[data-colonne]').forEach(function (bouton) {
            bouton.addEventListener('click', function () {
                var cases = reunion.querySelectorAll('[data-col="' + bouton.dataset.colonne + '"]');
                var toutCoche = Array.prototype.every.call(cases, function (c) { return c.checked; });
                cases.forEach(function (c) { c.checked = ! toutCoche; });
                compter(reunion);
            });
        });

        var vider = reunion.querySelector('[data-vider]');
        if (vider) vider.addEventListener('click', function () {
            reunion.querySelectorAll('input[type="checkbox"]').forEach(function (c) { c.checked = false; });
            compter(reunion);
        });
    });

    // Ordre de passage : flèches
    document.querySelectorAll('[data-ordre]').forEach(function (liste) {
        liste.addEventListener('click', function (e) {
            var monter = e.target.closest('[data-monter]');
            var descendre = e.target.closest('[data-descendre]');
            if (! monter && ! descendre) return;
            var ligne = e.target.closest('li');
            if (monter && ligne.previousElementSibling) {
                liste.insertBefore(ligne, ligne.previousElementSibling);
            } else if (descendre && ligne.nextElementSibling) {
                liste.insertBefore(ligne.nextElementSibling, ligne);
            }
            (monter || descendre).focus();
        });
    });
});
</script>
@endpush
