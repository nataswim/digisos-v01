@extends('layouts.public')

@section('title', 'L\'équipe du club')
@section('meta_description', 'Comité directeur, entraîneurs, bénévoles et officiels : les personnes qui font vivre le Cercle des Nageurs du Bocage Bressuirais.')
@section('og_title', 'L\'équipe du CNBB')
@section('og_description', 'Comité directeur, entraîneurs, bénévoles et officiels : les personnes qui font vivre le club de natation de Bressuire.')

@section('content')

@php
    $isAdmin = auth()->check() && auth()->user()->hasRole('admin');
@endphp

<x-public.hero
    title="L'équipe du club"
    eyebrow="Le Club"
    icon="fa-users"
    video="assets/images/team/CNBB-natation-2.mp4"
    lead="Dirigeants, entraîneurs, bénévoles et officiels : les personnes qui font vivre le CNBB au bord du bassin et en coulisses.">
    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-envelope me-2" aria-hidden="true"></i>Nous contacter
    </a>
</x-public.hero>


<section class="py-5 bg-white">
    <div class="container-lg">

        @if ($sections->isEmpty())
            <div class="card-aqua text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-0">La présentation de l'équipe arrive bientôt.</p>
            </div>

            @if ($isAdmin)
                <div class="alert alert-info border-0 shadow-sm mt-4 mb-0" role="note">
                    <i class="fas fa-info-circle me-2" aria-hidden="true"></i>
                    Ajoutez les membres dans l'administration :
                    <a href="{{ route('admin.team.members.index') }}">gérer l'équipe du club</a>.
                </div>
            @endif
        @else
            {{-- Accès direct aux sections --}}
            @if ($sections->count() > 1)
                <nav aria-label="Sections de la page" class="mb-5">
                    <ul class="nav nav-pills team-jump justify-content-center gap-2">
                        @foreach ($sections as $section)
                            <li class="nav-item">
                                <a class="nav-link" href="#{{ $section->slug }}">{{ $section->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            @foreach ($sections as $section)
                <section id="{{ $section->slug }}" class="team-section {{ $loop->last ? '' : 'mb-5 pb-3' }}" aria-labelledby="{{ $section->slug }}-title">
                    <h2 id="{{ $section->slug }}-title" class="h4 fw-bold mb-3 team-section-title">{{ $section->name }}</h2>
                    @if ($section->description)
                        <p class="text-muted mb-4 team-section-intro">{{ $section->description }}</p>
                    @endif

                    <ul class="row g-4 list-unstyled mb-0 {{ $section->description ? '' : 'mt-1' }}">
                        @foreach ($section->members as $member)
                            <li class="col-6 col-md-4 col-lg-3">
                                <article class="team-card card border-0 shadow-sm h-100 text-center">
                                    <div class="card-body p-3 p-md-4 d-flex flex-column align-items-center">
                                        <div class="team-photo mb-3">
                                            @if ($member->photo)
                                                <img src="{{ $member->photo }}" alt="Photo de {{ $member->full_name }}" loading="lazy">
                                            @else
                                                <span class="team-initials" aria-hidden="true">{{ $member->initials }}</span>
                                            @endif
                                        </div>

                                        <h3 class="h6 fw-bold mb-1 team-name">
                                            <span class="team-first">{{ $member->first_name }}</span>
                                            <span class="team-last">{{ $member->last_name }}</span>
                                        </h3>

                                        @if ($member->pivot->position)
                                            <p class="team-position small mb-0">{{ $member->pivot->position }}</p>
                                        @endif

                                        @if (count($member->links))
                                            <ul class="team-links list-unstyled d-flex justify-content-center gap-2 mt-auto pt-3 mb-0">
                                                @foreach ($member->links as $link)
                                                    <li>
                                                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="team-link"
                                                           title="{{ $link['label'] }} de {{ $member->full_name }}">
                                                            <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
                                                            <span class="visually-hidden">{{ $link['label'] }} de {{ $member->full_name }} (nouvel onglet)</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </article>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach

            @if ($isAdmin)
                <p class="text-center mt-5 mb-0">
                    <a href="{{ route('admin.team.members.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit me-1" aria-hidden="true"></i>Gérer l'équipe du club
                    </a>
                </p>
            @endif
        @endif
    </div>
</section>


{{-- Appel à l'engagement --}}
<section class="py-5 bg-light">
    <div class="container-lg text-center">
        <h2 class="h4 fw-bold mb-2">Envie de rejoindre l'équipe ?</h2>
        <p class="text-muted mb-4 mx-auto" style="max-width: 640px;">
            Le club vit grâce à ses bénévoles : accueil, organisation des compétitions, officiels au bord du bassin…
            Chaque coup de main compte, même ponctuel.
        </p>
        <a href="{{ route('contact') }}" class="btn btn-primary text-white">
            <i class="fas fa-hands-helping me-2" aria-hidden="true"></i>Proposer son aide
        </a>
    </div>
</section>

@endsection


@push('styles')
<style>
    .team-section {
        scroll-margin-top: 110px;   /* l'en-tête fixe ne cache pas le titre après un clic dans le sommaire */
    }

    .team-section-title {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 15px 0 15px 0;
        background-image: linear-gradient(129deg, #f9be38 85%, #2f80b8 0);
        color: #1c2111;
        box-shadow: 0 5px 6px 4px rgba(0, 0, 0, 0.05);
    }

    .team-section-intro {
        max-width: 760px;
    }

    .team-jump .nav-link {
        border-radius: 999px;
        border: 1px solid #2f80b8;
        color: #2f80b8;
    }

    .team-jump .nav-link:hover,
    .team-jump .nav-link:focus {
        background: #2f80b8;
        color: #fff;
    }

    .team-card {
        border-radius: 15px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .team-card:hover,
    .team-card:focus-within {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(47, 128, 184, 0.18) !important;
    }

    .team-photo {
        width: 132px;
        height: 132px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: #e8f2f9;
        box-shadow: 0 0 0 4px #fff, 0 0 0 6px #f9be38;
    }

    .team-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .team-initials {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        font-size: 2.25rem;
        font-weight: 700;
        color: #2f80b8;
    }

    .team-name {
        color: #1c2111;
        line-height: 1.3;
    }

    .team-last {
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .team-position {
        color: #2f80b8;
        font-weight: 600;
    }

    .team-link {
        display: inline-flex;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        color: #2f80b8;
        text-decoration: none;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .team-link:hover,
    .team-link:focus {
        background: #2f80b8;
        color: #fff;
    }

    .team-link:focus-visible {
        outline: 3px solid #f9be38;
        outline-offset: 2px;
    }

    @media (max-width: 575.98px) {
        .team-photo { width: 104px; height: 104px; }
        .team-initials { font-size: 1.75rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .team-card, .team-link { transition: none; }
        .team-card:hover, .team-card:focus-within { transform: none; }
    }
</style>
@endpush
