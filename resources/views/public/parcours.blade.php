@extends('layouts.public')

@section('title', 'Le parcours du nageur')
@section('meta_description', 'Le parcours du nageur au CNBB, de l\'école de natation à la Ligue et aux Maîtres, étape par étape, en lien avec le parcours de développement de la Fédération Française de Natation.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/parcours.css') }}?v=1">
@endpush

@section('content')

<x-public.hero
    title="Le parcours du nageur"
    eyebrow="Nos groupes"
    icon="fa-route"
    video="assets/images/team/CNBB-natation-3.mp4"
    lead="De l'école de natation à la Ligue, et la section Maîtres pour les adultes : cinq étapes pour que chacun progresse à son rythme.">
    <a href="#trouver" class="btn btn-primary btn-lg">
        <i class="fas fa-search me-2" aria-hidden="true"></i>Trouver mon groupe
    </a>
    <a href="{{ route('schedule') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-calendar-alt me-2" aria-hidden="true"></i>Groupes et horaires
    </a>
</x-public.hero>

<div class="parcours" id="parcours" data-parcours>

    {{-- ===== Choix de la vue ===== --}}
    <nav class="parcours-vues" aria-label="Choisir la vue">
        <div class="container-xl d-flex flex-wrap align-items-center gap-2">
            <div class="parcours-vues-boutons" role="tablist" aria-label="Vues du parcours">
                <button type="button" role="tab" class="parcours-vue" id="vue-btn-etapes" data-vue="etapes" aria-controls="vue-etapes" aria-selected="true">
                    <i class="fas fa-stream" aria-hidden="true"></i> Par étape
                </button>
                <button type="button" role="tab" class="parcours-vue" id="vue-btn-disciplines" data-vue="disciplines" aria-controls="vue-disciplines" aria-selected="false">
                    <i class="fas fa-th" aria-hidden="true"></i> Vue d'ensemble
                </button>
            </div>
            <a href="#trouver" class="parcours-vue parcours-vue--lien ms-md-auto">
                <i class="fas fa-search" aria-hidden="true"></i> Trouver mon groupe
            </a>
        </div>
    </nav>

    {{-- ===== Les deux chemins ===== --}}
    <section class="container-xl parcours-intro">
        <p class="parcours-kicker">Le parcours au CNBB</p>
        <h2 class="parcours-h2">Cinq étapes, deux chemins</h2>
        <p class="parcours-lead">
            Notre parcours suit les cinq étapes du parcours de développement de la Fédération Française de Natation.
            Après l'école de natation, chacun choisit son chemin ; les passerelles restent ouvertes après évaluation.
        </p>
        <div class="parcours-chemins">
            <div class="parcours-chemin">
                <span class="parcours-chemin-titre"><i class="fas fa-trophy" aria-hidden="true"></i> Compétition</span>
                <ol class="parcours-chemin-etapes">
                    <li style="--c: #17a2b8">École de natation</li>
                    <li style="--c: #efa525">Relève</li>
                    <li style="--c: #2f80b8">Pré-ligue</li>
                    <li style="--c: #2f80b8">Ligue</li>
                </ol>
            </div>
            <div class="parcours-chemin">
                <span class="parcours-chemin-titre"><i class="fas fa-heart" aria-hidden="true"></i> Loisir sportif</span>
                <ol class="parcours-chemin-etapes">
                    <li style="--c: #17a2b8">École de natation</li>
                    <li style="--c: #198754">Promotion</li>
                    <li style="--c: #1c2111">Maîtres</li>
                </ol>
            </div>
            <p class="parcours-chemin-note">
                <i class="fas fa-universal-access" aria-hidden="true"></i>
                Le groupe Par-Natation accueille les personnes en situation de handicap, à toutes les étapes.
            </p>
        </div>
    </section>

    {{-- ===== Vue « Par étape » ===== --}}
    <section id="vue-etapes" class="parcours-panneau-vue" role="tabpanel" aria-labelledby="vue-btn-etapes" data-vue-panneau="etapes">
        <div class="container-xl">
            <div class="parcours-etapes" role="tablist" aria-label="Les cinq étapes">
                @foreach ($etapes as $n => $e)
                    <button type="button" role="tab" class="parcours-etape-btn" data-etape="{{ $n }}"
                            id="etape-btn-{{ $n }}" aria-controls="etape-{{ $n }}" aria-selected="{{ $n === 2 ? 'true' : 'false' }}">
                        <span class="parcours-num">P{{ $n }}</span>
                        <span class="parcours-etape-nom">{{ $e['nom'] }}</span>
                        <span class="parcours-etape-public">{{ $e['public'] }}</span>
                    </button>
                @endforeach
            </div>

            @foreach ($etapes as $n => $e)
                @php $auClub = array_values(array_filter($groupes, fn ($g) => in_array($n, $g['etapes'], true))); @endphp
                <article id="etape-{{ $n }}" class="parcours-etape" role="tabpanel" aria-labelledby="etape-btn-{{ $n }}" data-etape-panneau="{{ $n }}" tabindex="-1">
                    <header class="parcours-etape-tete">
                        <span class="parcours-num parcours-num--grand">P{{ $n }}</span>
                        <div>
                            <p class="parcours-kicker mb-1">{{ $e['public'] }} · {{ $e['cap'] }}</p>
                            <h3 class="parcours-h3">{{ $e['nom'] }}</h3>
                            <p class="parcours-intention">{{ $e['intention'] }}</p>
                        </div>
                    </header>

                    <div class="row g-4">
                        <div class="col-lg-7">
                            <p class="parcours-texte">{{ $e['periode'] }}</p>

                            <ul class="parcours-idees">
                                @foreach ($e['idees'] as $idee)
                                    <li><i class="fas fa-check" aria-hidden="true"></i>{{ $idee }}</li>
                                @endforeach
                            </ul>

                            <h4 class="parcours-h4">Thématiques de l'étape</h4>
                            <ul class="parcours-chips">
                                @foreach ($e['themes'] as $t)
                                    <li>{{ $t }}</li>
                                @endforeach
                            </ul>

                            @if ($e['repartition'])
                                <h4 class="parcours-h4">Répartition du temps d'entraînement (repère FFN)</h4>
                                @php
                                    $libelle = collect($e['repartition'])->filter()
                                        ->map(fn ($v, $k) => $themes[$k][0].' '.$v.' %')->implode(', ');
                                @endphp
                                <div class="parcours-barre" role="img" aria-label="Répartition : {{ $libelle }}">
                                    @foreach ($e['repartition'] as $k => $v)
                                        @if ($v)
                                            <span style="flex: {{ $v }}; background: {{ $themes[$k][1] }}" title="{{ $themes[$k][0] }} : {{ $v }} %">{{ $v }} %</span>
                                        @endif
                                    @endforeach
                                </div>
                                <ul class="parcours-legende" aria-hidden="true">
                                    @foreach ($e['repartition'] as $k => $v)
                                        @if ($v)
                                            <li><span style="background: {{ $themes[$k][1] }}"></span>{{ $themes[$k][0] }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif

                            @if (! empty($e['note']))
                                <p class="parcours-astuce"><i class="fas fa-lightbulb" aria-hidden="true"></i>{{ $e['note'] }}</p>
                            @endif

                            @if ($e['volumes'])
                                <details class="parcours-details">
                                    <summary>Repères de volumes FFN (trajectoire de performance)</summary>
                                    <p class="small text-muted mb-2">
                                        Ces volumes sont des repères pour une trajectoire vers le haut niveau.
                                        La FFN précise qu'ils ne sont pas des attendus pour tous les pratiquants.
                                    </p>
                                    <div class="table-responsive position-relative">
                                        <table class="table table-sm mb-0">
                                            <thead><tr><th scope="col">Âge</th><th scope="col">Séances par semaine</th><th scope="col">Volume dans l'eau</th></tr></thead>
                                            <tbody>
                                                @foreach ($e['volumes'] as [$age, $seances, $volume])
                                                    <tr><td>{{ $age }}</td><td>{{ $seances }}</td><td>{{ $volume }}</td></tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </details>
                            @endif
                        </div>

                        <div class="col-lg-5">
                            <div class="parcours-club">
                                <h4 class="parcours-h4 mb-3"><i class="fas fa-swimmer me-2" aria-hidden="true"></i>Au CNBB</h4>
                                @forelse ($auClub as $g)
                                    @include('public.partials.parcours-groupe', ['g' => $g])
                                @empty
                                    <p class="mb-2">Le club n'a pas de groupe avant 6 ans.</p>
                                    <p class="small text-muted mb-0">
                                        L'aisance aquatique (entrer et sortir seul de l'eau, s'immerger, nager 25 m sans aide)
                                        est le prérequis du groupe <a href="#etape-2" data-aller-etape="2">Initiation Bleu</a>.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ===== Vue d'ensemble (étapes en colonnes) ===== --}}
    <section id="vue-disciplines" class="parcours-panneau-vue" role="tabpanel" aria-labelledby="vue-btn-disciplines" data-vue-panneau="disciplines">
        <div class="container-xl">
            <h2 class="parcours-h2">Vue d'ensemble : natation course</h2>
            <p class="parcours-lead">Les âges sont indicatifs : la progression suit le développement de chacun, pas un calendrier strict.</p>

            <div class="parcours-grille-wrap" tabindex="0" aria-label="Tableau des cinq étapes, défilant horizontalement sur petit écran">
                <table class="parcours-grille">
                    <thead>
                        <tr>
                            <th scope="col" class="parcours-grille-coin"><span class="visually-hidden">Repère</span></th>
                            @foreach ($etapes as $n => $e)
                                <th scope="col">
                                    <a href="#etape-{{ $n }}" data-aller-etape="{{ $n }}" class="parcours-grille-etape">
                                        <span class="parcours-num">P{{ $n }}</span>{{ $e['nom'] }}
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Le cap</th>
                            <td colspan="2" class="parcours-cap">Devenir nageur</td>
                            <td colspan="2" class="parcours-cap">Meilleur nageur</td>
                            <td class="parcours-cap">Toujours meilleur nageur</td>
                        </tr>
                        <tr>
                            <th scope="row">Âge scolaire</th>
                            @foreach ($etapes as $e)<td>{{ $e['scolaire'] }}</td>@endforeach
                        </tr>
                        <tr>
                            <th scope="row">Années de pratique</th>
                            @foreach ($etapes as $e)<td>{{ $e['pratique'] }}</td>@endforeach
                        </tr>
                        <tr>
                            <th scope="row">Développement physique</th>
                            <td colspan="2">Croissance régulière</td>
                            <td>Pic de croissance : filles vers 12 ans, garçons vers 13 ans et demi</td>
                            <td colspan="2">Maturation physique, puissance qui grandit</td>
                        </tr>
                        <tr>
                            <th scope="row">Natation course</th>
                            @foreach ($etapes as $e)<td>{{ $e['intention'] }}</td>@endforeach
                        </tr>
                        <tr>
                            <th scope="row">Groupes du CNBB</th>
                            @foreach ($etapes as $n => $e)
                                <td>
                                    @php $auClub = array_filter($groupes, fn ($g) => in_array($n, $g['etapes'], true)); @endphp
                                    @forelse ($auClub as $g)
                                        <a href="{{ $g['lien'] }}" class="parcours-pastille" style="--c: {{ $g['couleur'] }}">{{ $g['nom'] }}</a>
                                    @empty
                                        <span class="text-muted small">Aisance aquatique, avant l'école de natation</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- ===== Trouver mon groupe ===== --}}
    <section id="trouver" class="parcours-trouver anchor-section">
        <div class="container-xl">
            <div class="row g-4 align-items-start">
                <div class="col-lg-4">
                    <p class="parcours-kicker">Outil</p>
                    <h2 class="parcours-h2">Trouver mon groupe</h2>
                    <p class="parcours-texte">
                        Indiquez l'âge du nageur et le type de pratique : les groupes possibles s'affichent.
                        Le groupe définitif est fixé après l'évaluation du premier cours.
                    </p>

                    <form class="parcours-form" data-trouver-form onsubmit="return false">
                        <label for="trouver-age" class="form-label fw-semibold">Âge du nageur : <output id="trouver-age-val" for="trouver-age">10</output> ans</label>
                        <input type="range" class="form-range" id="trouver-age" name="age" min="4" max="80" value="10">

                        <fieldset class="mt-3">
                            <legend class="form-label fw-semibold fs-6">Pratique souhaitée</legend>
                            <div class="parcours-choix">
                                <input type="radio" class="btn-check" name="pratique" id="pratique-tous" value="tous" checked>
                                <label class="btn btn-outline-primary btn-sm" for="pratique-tous">Je ne sais pas</label>
                                <input type="radio" class="btn-check" name="pratique" id="pratique-competition" value="competition">
                                <label class="btn btn-outline-primary btn-sm" for="pratique-competition">Compétition</label>
                                <input type="radio" class="btn-check" name="pratique" id="pratique-loisir" value="loisir">
                                <label class="btn btn-outline-primary btn-sm" for="pratique-loisir">Loisir sportif</label>
                            </div>
                        </fieldset>
                    </form>
                </div>

                <div class="col-lg-8">
                    <div class="parcours-resultats" aria-live="polite" data-trouver-resultats>
                        {{-- Sans JavaScript : tous les groupes --}}
                        @foreach ($groupes as $g)
                            <div data-groupe-min="{{ $g['min'] }}" data-groupe-max="{{ $g['max'] }}"
                                 data-groupe-type="{{ $g['competition'] === null ? 'tous' : ($g['competition'] ? 'competition' : 'loisir') }}"
                                 data-groupe-cle="{{ $g['cle'] }}">
                                @include('public.partials.parcours-groupe', ['g' => $g])
                            </div>
                        @endforeach
                        <p class="parcours-aucun" data-trouver-aucun hidden></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Rôle des parents ===== --}}
    <section class="container-xl parcours-parents">
        <p class="parcours-kicker">Accompagner</p>
        <h2 class="parcours-h2">Le rôle des parents</h2>
        <p class="parcours-lead">L'éducateur est le référent de la pratique sportive. Les parents, eux, créent les conditions pour que l'enfant ait envie de revenir.</p>
        <ul class="parcours-parents-grille">
            @foreach ($parents as [$titre, $texte])
                <li><strong>{{ $titre }}</strong><span>{{ $texte }}</span></li>
            @endforeach
        </ul>
    </section>

    <section class="container-xl parcours-source">
        <p class="small text-muted mb-0">
            Étapes, repères et répartitions d'après le
            <a href="{{ $sourceFfn }}" target="_blank" rel="noopener">parcours de développement de l'athlète de la Fédération Française de Natation</a>.
            Créneaux de la période scolaire, mis à jour depuis le <a href="{{ route('schedule') }}">planning du club</a>.
        </p>
    </section>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/parcours.js') }}?v=1" defer></script>
@endpush
