@extends('layouts.public')

@section('title', 'Groupes et horaires')
@section('meta_description', 'Planning des entraînements du Cercle des Nageurs du Bocage Bressuirais : créneaux de la période scolaire et des vacances scolaires, groupe par groupe.')

@section('content')

@php
    // Données fournies par PublicPlanningController (module Planning, géré dans l'administration)
    $jours = \App\Models\PlanningSlot::DAYS;
    $periodes = \App\Models\PlanningSlot::PERIODS;
    $lieuParDefaut = 'Centre aquatique Cœur d\'O';
@endphp


<x-public.hero
    title="Groupes et horaires"
    eyebrow="Planning des entraînements"
    icon="fa-calendar-alt"
    video="assets/images/team/CNBB-natation-3.mp4"
    lead="Les créneaux de chaque groupe, en période scolaire et pendant les vacances scolaires.">
    <a href="#planning" class="btn btn-primary btn-lg schedule-btn">
        <i class="fas fa-arrow-down me-2" aria-hidden="true"></i>Voir le planning
    </a>
    <a href="{{ url('/pages/nos-groupes/parcours-du-nageur') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-route me-2" aria-hidden="true"></i>Le parcours du nageur
    </a>
</x-public.hero>


<section id="planning" class="anchor-section py-5 bg-light">
    <div class="container-xl">

        {{-- Choix du planning : période scolaire / vacances --}}
        <nav class="d-flex justify-content-center mb-4" aria-label="Choisir le planning">
            <ul class="nav nav-pills schedule-periods">
                @foreach ($periodes as $cle => $libelle)
                    <li class="nav-item">
                        <a class="nav-link {{ $period === $cle ? 'active' : '' }}"
                           href="{{ route('schedule', ['periode' => $cle]) }}#planning"
                           @if ($period === $cle) aria-current="page" @endif>
                            <i class="fas {{ $cle === 'scolaire' ? 'fa-school' : 'fa-umbrella-beach' }} me-1" aria-hidden="true"></i>{{ $libelle }}
                            <span class="badge rounded-pill ms-1">{{ $counts[$cle] ?? 0 }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <header class="text-center mb-4">
            <h2 class="display-6 fw-bold mb-2">{{ $periodes[$period] }}</h2>
            <p class="text-muted mb-0">
                @if ($period === 'scolaire')
                    Hors vacances scolaires, jours fériés et fermetures exceptionnelles du centre aquatique.
                @else
                    Pendant les vacances, l'école de natation, la Jeunesse et les Maîtres ne s'entraînent pas, sauf information spécifique.
                    Les stages des groupes compétitifs se font sur convocation.
                @endif
            </p>
        </header>

        @if ($planning->isEmpty())
            <div class="card border-0 shadow-sm mx-auto" style="max-width: 640px;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-2x text-primary opacity-50 mb-3" aria-hidden="true"></i>
                    <p class="mb-0">Aucun créneau publié pour « {{ $periodes[$period] }} » pour le moment.</p>
                </div>
            </div>
        @else
            {{-- Filtre par section (le planning reste lisible sans JavaScript) --}}
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4" role="group" aria-label="Afficher une section">
                <button type="button" class="btn btn-sm schedule-filter is-active" data-section="" aria-pressed="true">
                    Toutes les sections
                </button>
                @foreach ($sections as $section => [$fond, $texte])
                    @if ($groups->contains('section', $section))
                        <button type="button" class="btn btn-sm schedule-filter" data-section="{{ $section }}" aria-pressed="false"
                                style="--section-bg: {{ $fond }}; --section-fg: {{ $texte }};">
                            <span class="schedule-dot" aria-hidden="true"></span>{{ $section }}
                        </button>
                    @endif
                @endforeach
            </div>

            <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-{{ min(5, $planning->count()) }}">
                @foreach ($planning as $numero => $creneaux)
                    <div class="col schedule-col">
                        <section class="card border-0 shadow-sm h-100" aria-labelledby="jour-{{ $numero }}">
                            <h3 id="jour-{{ $numero }}" class="h5 fw-bold schedule-day mb-0">{{ $jours[$numero] }}</h3>
                            <ul class="list-unstyled p-2 mb-0">
                                @foreach ($creneaux as $creneau)
                                    @php $groupe = $creneau->group; @endphp
                                    <li class="schedule-slot" data-section="{{ $groupe->section }}"
                                        style="--section-bg: {{ $groupe->background_color }}; --section-fg: {{ $groupe->text_color }};">
                                        <div class="d-flex flex-wrap justify-content-between align-items-baseline column-gap-2">
                                            <strong class="schedule-time">{{ $creneau->time_range }}</strong>
                                            <span class="schedule-section">{{ $groupe->section }}</span>
                                        </div>
                                        <div class="schedule-groups">
                                            <a href="#groupe-{{ $groupe->slug }}">{{ $groupe->name }}</a>
                                        </div>
                                        @if ($creneau->lanes || $creneau->location !== $lieuParDefaut)
                                            <small class="schedule-space d-block">
                                                <i class="fas fa-swimming-pool me-1" aria-hidden="true"></i>{{ collect([$creneau->location !== $lieuParDefaut ? $creneau->location : null, $creneau->lanes ? ucfirst($creneau->lanes) : null])->filter()->implode(' · ') }}
                                            </small>
                                        @endif
                                        @if ($creneau->notes)
                                            <small class="schedule-note d-block"><i class="fas fa-info-circle me-1" aria-hidden="true"></i>{{ $creneau->notes }}</small>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    </div>
                @endforeach
            </div>

            <p class="text-center text-muted small mt-4 mb-0">
                <i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>
                Sauf mention contraire, les entraînements ont lieu au centre aquatique Cœur d'O, à Bressuire.
            </p>
        @endif
    </div>
</section>


@if ($groups->isNotEmpty())
<section id="groupes" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-2">Les groupes</h2>
            <p class="text-muted mb-0">Composition des groupes présents sur ce planning</p>
        </header>

        @if (! ($canSeeSwimmers))
            <div class="alert alert-info d-flex align-items-start gap-2 mx-auto mb-4" style="max-width: 760px;" role="note">
                <i class="fas fa-lock mt-1" aria-hidden="true"></i>
                <div>
                    La liste des nageurs de chaque groupe est réservée aux adhérents du club.
                    @guest
                        <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour la consulter.
                    @else
                        Votre compte n'est pas encore validé comme adhérent.
                    @endguest
                </div>
            </div>
        @endif

        <div class="row g-4">
            @foreach ($groups as $groupe)
                <div class="col-md-6 col-lg-4">
                    <article id="groupe-{{ $groupe->slug }}" class="card border-0 shadow-sm h-100 schedule-group-card"
                             style="--section-bg: {{ $groupe->background_color }}; --section-fg: {{ $groupe->text_color }};">
                        <div class="card-body p-4">
                            <p class="mb-1"><span class="schedule-section">{{ $groupe->section }}</span></p>
                            <h3 class="h5 fw-bold mb-2">{{ $groupe->name }}</h3>

                            @if ($groupe->description)
                                <p class="text-muted small mb-3">{{ $groupe->description }}</p>
                            @endif

                            @if ($canSeeSwimmers)
                                @if ($groupe->swimmers->isNotEmpty())
                                    <p class="small fw-semibold mb-1">{{ $groupe->swimmers->count() }} nageur{{ $groupe->swimmers->count() > 1 ? 's' : '' }}</p>
                                    <ul class="list-unstyled schedule-swimmers mb-3">
                                        @foreach ($groupe->swimmers as $nageur)
                                            <li>{{ $nageur->short_name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="small text-muted mb-3">Composition du groupe à venir.</p>
                                @endif
                            @elseif ($groupe->swimmers_count)
                                <p class="small text-muted mb-3">
                                    <i class="fas fa-users me-1" aria-hidden="true"></i>{{ $groupe->swimmers_count }} nageur{{ $groupe->swimmers_count > 1 ? 's' : '' }}
                                </p>
                            @endif

                            @if ($groupe->page_url)
                                <a href="{{ $groupe->page_url }}" class="fw-semibold small">Prérequis et contenu du groupe</a>
                            @endif
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <p class="text-center mt-5 mb-0">
            <a href="{{ url('/pages/regles-du-club/reglement-de-fonctionnement') }}">Règlement de fonctionnement</a>
            <span class="mx-2 text-muted" aria-hidden="true">·</span>
            <a href="{{ route('pricing') }}">Tarifs et inscription</a>
            <span class="mx-2 text-muted" aria-hidden="true">·</span>
            <a href="{{ route('contact') }}">Une question sur un créneau ?</a>
        </p>
    </div>
</section>
@endif

@endsection


@push('styles')
<style>
    .schedule-btn,
    .schedule-btn i {
        background-color: #2f80b8;
        border-color: #2f80b8;
        color: #fff !important;
    }

    .schedule-btn i {
        background-color: transparent;
    }

    .schedule-periods .nav-link {
        color: #1c2111;
        background: #fff;
        border: 1px solid #ced4da;
        margin: 0 0.25rem;
        font-weight: 600;
    }

    .schedule-periods .nav-link.active {
        background: #2f80b8;
        border-color: #2f80b8;
        color: #fff !important;
    }

    .schedule-periods .badge {
        background: rgba(0, 0, 0, 0.12);
        color: inherit;
    }

    .schedule-day {
        background-color: #2f80b8;
        color: #fff !important;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .schedule-slot {
        border-left: 6px solid var(--section-bg);
        background: #fff;
        border-radius: 0.375rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 0.6rem 0.75rem;
        margin-bottom: 0.5rem;
    }

    .schedule-slot[hidden],
    .schedule-col[hidden] {
        display: none;
    }

    .schedule-time {
        font-size: 0.95rem;
        white-space: nowrap;
    }

    .schedule-section {
        display: inline-block;
        background: var(--section-bg);
        color: var(--section-fg);
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.1rem 0.45rem;
        border-radius: 1rem;
        white-space: nowrap;
    }

    .schedule-groups a {
        color: #1c2111;
        font-weight: 600;
        text-decoration: none;
    }

    .schedule-groups a:hover,
    .schedule-groups a:focus {
        color: #2f80b8;
        text-decoration: underline;
    }

    .schedule-space,
    .schedule-note {
        color: #6c757d;
    }

    .schedule-group-card {
        border-top: 5px solid var(--section-bg) !important;
        scroll-margin-top: 120px;
    }

    .schedule-swimmers {
        columns: 2;
        column-gap: 1rem;
        font-size: 0.9rem;
    }

    /* Filtres */
    .schedule-filter {
        background: #fff;
        border: 1px solid #ced4da;
        color: #1c2111;
    }

    .schedule-filter.is-active {
        background: var(--section-bg, #2f80b8);
        border-color: var(--section-bg, #2f80b8);
        color: var(--section-fg, #fff);
    }

    .schedule-dot {
        display: inline-block;
        width: 0.7rem;
        height: 0.7rem;
        border-radius: 50%;
        background: var(--section-bg);
        margin-right: 0.4rem;
        vertical-align: -0.05rem;
    }

    .schedule-filter.is-active .schedule-dot {
        background: var(--section-fg);
    }
</style>
@endpush


@push('scripts')
<script>
    // Filtre du planning par section
    document.querySelectorAll('.schedule-filter').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            var section = bouton.dataset.section;

            document.querySelectorAll('.schedule-filter').forEach(function (b) {
                var actif = b === bouton;
                b.classList.toggle('is-active', actif);
                b.setAttribute('aria-pressed', actif ? 'true' : 'false');
            });

            document.querySelectorAll('.schedule-slot').forEach(function (creneau) {
                creneau.hidden = section !== '' && creneau.dataset.section !== section;
            });

            // Masquer les jours sans créneau pour la section choisie
            document.querySelectorAll('.schedule-col').forEach(function (jour) {
                jour.hidden = jour.querySelector('.schedule-slot:not([hidden])') === null;
            });
        });
    });
</script>
@endpush
