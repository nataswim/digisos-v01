@extends('layouts.public')

@section('title', 'Calendrier des compétitions')
@section('meta_description', 'Calendrier des compétitions de natation du Cercle des Nageurs du Bocage Bressuirais : dates, lieux, programme et informations pratiques.')

@section('content')

@php
    $filtres = array_filter(['vue' => $vue, 'saison' => $vue === 'passees' ? $season : null, 'niveau' => $level, 'groupe' => $groupSlug]);
@endphp

<x-public.hero
    title="Compétitions"
    eyebrow="Calendrier du club"
    icon="fa-trophy"
    lead="Les compétitions des nageurs du CNBB : dates, lieux, programme et informations pratiques pour les familles.">
    <a href="#calendrier" class="btn btn-primary btn-lg competitions-btn">
        <i class="fas fa-arrow-down me-2" aria-hidden="true"></i>Voir le calendrier
    </a>
    <a href="{{ route('competitions.calendar') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-calendar-plus me-2" aria-hidden="true"></i>S'abonner (agenda)
    </a>
</x-public.hero>


<section id="calendrier" class="anchor-section py-5 bg-light">
    <div class="container-lg">

        {{-- À venir / passées --}}
        <nav class="d-flex justify-content-center mb-4" aria-label="Choisir la liste">
            <ul class="nav nav-pills competitions-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $vue === 'a-venir' ? 'active' : '' }}" href="{{ route('competitions.index', array_filter(['niveau' => $level, 'groupe' => $groupSlug])) }}#calendrier"
                       @if ($vue === 'a-venir') aria-current="page" @endif>
                        <i class="fas fa-calendar-day me-1" aria-hidden="true"></i>À venir
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $vue === 'passees' ? 'active' : '' }}" href="{{ route('competitions.index', array_filter(['vue' => 'passees', 'niveau' => $level, 'groupe' => $groupSlug])) }}#calendrier"
                       @if ($vue === 'passees') aria-current="page" @endif>
                        <i class="fas fa-history me-1" aria-hidden="true"></i>Passées
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Filtres --}}
        <form method="GET" action="{{ route('competitions.index') }}#calendrier" class="row g-2 justify-content-center mb-4">
            <input type="hidden" name="vue" value="{{ $vue }}">
            @if ($vue === 'passees' && $seasons->isNotEmpty())
                <div class="col-sm-auto">
                    <label for="saison" class="visually-hidden">Saison</label>
                    <select id="saison" name="saison" class="form-select">
                        @foreach ($seasons as $s)
                            <option value="{{ $s }}" @selected($season === $s)>Saison {{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-sm-auto">
                <label for="niveau" class="visually-hidden">Niveau</label>
                <select id="niveau" name="niveau" class="form-select">
                    <option value="">Tous les niveaux</option>
                    @foreach (\App\Models\Competition::LEVELS as $cle => $libelle)
                        <option value="{{ $cle }}" @selected($level === $cle)>{{ $libelle }}</option>
                    @endforeach
                </select>
            </div>
            @if ($groups->isNotEmpty())
                <div class="col-sm-auto">
                    <label for="groupe" class="visually-hidden">Groupe</label>
                    <select id="groupe" name="groupe" class="form-select">
                        <option value="">Tous les groupes</option>
                        @foreach ($groups as $groupe)
                            <option value="{{ $groupe->slug }}" @selected($groupSlug === $groupe->slug)>{{ $groupe->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-sm-auto">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-filter me-1" aria-hidden="true"></i>Filtrer</button>
            </div>
        </form>

        @if ($competitions->isEmpty())
            <div class="card border-0 shadow-sm mx-auto" style="max-width: 640px;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-2x text-primary opacity-50 mb-3" aria-hidden="true"></i>
                    <p class="mb-0">
                        {{ $vue === 'a-venir' ? 'Aucune compétition à venir n\'est encore publiée.' : 'Aucune compétition passée pour cette sélection.' }}
                    </p>
                </div>
            </div>
        @else
            @foreach ($months as $mois => $liste)
                <h2 class="h5 fw-bold text-uppercase competitions-month mt-4 mb-3">
                    {{ ucfirst(\Illuminate\Support\Carbon::createFromFormat('Y-m', $mois)->locale('fr')->isoFormat('MMMM YYYY')) }}
                </h2>

                <div class="row g-3">
                    @foreach ($liste as $competition)
                        <div class="col-12">
                            <article class="card border-0 shadow-sm competition-card {{ in_array($competition->state, ['annulee', 'reportee'], true) ? 'is-cancelled' : '' }}">
                                <div class="card-body d-flex flex-column flex-md-row gap-3 align-items-md-center">
                                    <div class="competition-date text-center flex-shrink-0" aria-hidden="true">
                                        <span class="competition-date-day">{{ $competition->starts_on->format('d') }}</span>
                                        <span class="competition-date-month">{{ $competition->starts_on->locale('fr')->isoFormat('MMM') }}</span>
                                        @if ($competition->duration_days > 1)
                                            <span class="competition-date-more">{{ $competition->duration_days }} jours</span>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap gap-1 mb-1">
                                            <span class="badge {{ $competition->state_badge }}">{{ $competition->state_label }}</span>
                                            <span class="badge bg-light text-dark border">{{ $competition->level_label }}</span>
                                            @if ($competition->pool_length)
                                                <span class="badge bg-light text-dark border">{{ $competition->pool_length }} m</span>
                                            @endif
                                        </div>
                                        <h3 class="h5 fw-bold mb-1">
                                            <a href="{{ route('competitions.show', $competition) }}" class="stretched-link text-decoration-none text-dark">{{ $competition->title }}</a>
                                        </h3>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-calendar-alt me-1" aria-hidden="true"></i>{{ $competition->date_range }}
                                            <span class="mx-1" aria-hidden="true">·</span>
                                            <i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>{{ $competition->venue }}, {{ $competition->city }}
                                        </p>
                                        @if ($competition->groups->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($competition->groups as $groupe)
                                                    <span class="badge" style="background: {{ $groupe->background_color }}; color: {{ $groupe->text_color }};">{{ $groupe->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    @if ($competition->image)
                                        <img src="{{ $competition->image }}" alt="" loading="lazy" class="competition-thumb rounded d-none d-sm-block flex-shrink-0">
                                    @endif
                                    <i class="fas fa-chevron-right text-muted d-none d-md-block" aria-hidden="true"></i>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif

        <p class="text-center text-muted small mt-5 mb-0">
            <i class="fas fa-calendar-plus me-1" aria-hidden="true"></i>
            Ajoutez le calendrier du club à votre téléphone :
            <a href="{{ route('competitions.calendar') }}">abonnement au calendrier (iCal)</a>.
        </p>
    </div>
</section>

@endsection


@push('styles')
<style>
    .competitions-btn,
    .competitions-btn i {
        background-color: #2f80b8;
        border-color: #2f80b8;
        color: #fff !important;
    }

    .competitions-btn i {
        background-color: transparent;
    }

    .competitions-tabs .nav-link {
        color: #1c2111;
        background: #fff;
        border: 1px solid #ced4da;
        margin: 0 0.25rem;
        font-weight: 600;
    }

    .competitions-tabs .nav-link.active {
        background: #2f80b8;
        border-color: #2f80b8;
        color: #fff !important;
    }

    .competitions-month {
        color: #2f80b8;
        border-bottom: 2px solid #f9be38;
        padding-bottom: 0.4rem;
        letter-spacing: 0.04em;
    }

    .competition-thumb { width: 140px; height: 90px; object-fit: cover; }

    .competition-card {
        border-left: 6px solid #2f80b8 !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .competition-card:hover,
    .competition-card:focus-within {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(47, 128, 184, 0.15) !important;
    }

    .competition-card.is-cancelled {
        border-left-color: #adb5bd !important;
        opacity: 0.8;
    }

    .competition-date {
        width: 84px;
        background: #2f80b8;
        color: #fff;
        border-radius: 0.5rem;
        padding: 0.5rem 0.25rem;
        line-height: 1.1;
    }

    .competition-date-day {
        display: block;
        font-size: 1.9rem;
        font-weight: 700;
    }

    .competition-date-month {
        display: block;
        text-transform: uppercase;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .competition-date-more {
        display: block;
        font-size: 0.7rem;
        margin-top: 0.2rem;
        opacity: 0.9;
    }

    @media (prefers-reduced-motion: reduce) {
        .competition-card {
            transition: none;
        }

        .competition-card:hover,
        .competition-card:focus-within {
            transform: none;
        }
    }
</style>
@endpush
