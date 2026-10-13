@extends('layouts.public')

@section('title', 'Nos partenaires')
@section('meta_description', 'Les partenaires du Cercle des Nageurs du Bocage Bressuirais (CNBB) : entreprises, institutions et mécènes qui soutiennent le club de natation de Bressuire.')

@section('content')

@php
    // Taille des cartes selon le niveau : plus le niveau est élevé, plus le logo est grand
    $colonnes = [
        'principal' => 'col-12 col-lg-10',
        'or'        => 'col-sm-6 col-lg-4',
        'argent'    => 'col-6 col-md-4 col-lg-3',
        'bronze'    => 'col-6 col-md-3 col-lg-2',
    ];

    $canManage = auth()->check()
        && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor'));
@endphp


<x-public.hero
    title="Nos partenaires"
    eyebrow="Le Club"
    icon="fa-handshake"
    video="assets/images/team/CNBB-natation-3.mp4"
    lead="Entreprises, collectivités et mécènes : ils soutiennent le club et la formation de ses nageurs. Merci à eux !">
    <a href="{{ $becomePartnerUrl }}" class="btn btn-light btn-lg">
        <i class="fas fa-hands-helping me-2" aria-hidden="true"></i>Devenir partenaire
    </a>
    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-envelope me-2" aria-hidden="true"></i>Nous contacter
    </a>
</x-public.hero>


@forelse ($blocks as $level => $block)
    <section class="py-5 {{ $loop->odd ? 'bg-white' : 'bg-light' }}" aria-labelledby="niveau-{{ $level }}">
        <div class="container-lg">
            <h2 id="niveau-{{ $level }}" class="partners-level-title mb-4"
                style="--level-bg: {{ $block['info']['bg'] }}; --level-text: {{ $block['info']['text'] }};">
                <span class="partners-level-chip">
                    <i class="fas {{ $block['info']['icon'] }} me-2" aria-hidden="true"></i>{{ $block['info']['label'] }}
                </span>
            </h2>

            <div class="row g-4 justify-content-center">
                @foreach ($block['partners'] as $partner)
                    <div class="{{ $colonnes[$level] ?? $colonnes['bronze'] }}">
                        <a href="{{ route('partners.show', $partner) }}"
                           class="partners-card partners-card--{{ $level }} card border-0 shadow-sm h-100 text-decoration-none">
                            <div class="{{ $level === 'principal' ? 'row g-0 align-items-center h-100' : 'd-flex flex-column h-100' }}">
                                <div class="{{ $level === 'principal' ? 'col-md-5' : '' }}">
                                    <div class="partners-logo">
                                        @if ($partner->logo_url)
                                            <img src="{{ $partner->logo_url }}" alt="Logo de {{ $partner->name }}" loading="lazy">
                                        @else
                                            <span class="partners-initials" aria-hidden="true">{{ $partner->initials }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="{{ $level === 'principal' ? 'col-md-7' : 'mt-auto' }}">
                                    <div class="card-body {{ $level === 'principal' ? 'p-4' : 'p-3 text-center' }}">
                                        <h3 class="{{ $level === 'principal' ? 'h3' : ($level === 'bronze' ? 'h6' : 'h5') }} fw-bold text-dark mb-1">
                                            {{ $partner->name }}
                                        </h3>

                                        @if ($partner->summary && $level !== 'bronze')
                                            <p class="text-muted mb-2 {{ $level === 'principal' ? '' : 'small' }}">{{ $partner->summary }}</p>
                                        @endif

                                        @if ($level === 'principal')
                                            <p class="small text-muted mb-3">
                                                @if ($partner->category_label){{ $partner->category_label }}@endif
                                                @if ($partner->category_label && $partner->partner_since) · @endif
                                                @if ($partner->partner_since)Partenaire depuis {{ $partner->partner_since }}@endif
                                            </p>
                                            <span class="btn btn-primary text-white partners-btn">
                                                Découvrir<span class="visually-hidden"> {{ $partner->name }}</span>
                                                <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                                            </span>
                                        @elseif ($partner->partner_since)
                                            <small class="text-muted">Depuis {{ $partner->partner_since }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@empty
    <section class="py-5 bg-aqua-light">
        <div class="container-lg">
            <div class="card-aqua text-center py-5">
                <i class="fas fa-handshake fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-2">La liste de nos partenaires arrive bientôt.</p>
                <p class="text-muted mb-0">Votre entreprise souhaite soutenir le club ? <a href="{{ $becomePartnerUrl }}">Devenez partenaire</a>.</p>
            </div>

            @if ($canManage)
                <div class="alert alert-info border-0 shadow-sm mt-4 mb-0" role="note">
                    <i class="fas fa-info-circle me-2" aria-hidden="true"></i>
                    Ajoutez les partenaires dans l'administration :
                    <a href="{{ route((auth()->user()->hasRole('admin') ? 'admin' : 'editor') . '.partners.create') }}">ajouter un partenaire</a>.
                </div>
            @endif
        </div>
    </section>
@endforelse


<!-- Devenir partenaire -->
<section class="py-5 bg-primary text-white partners-join">
    <div class="container-lg text-center">
        <h2 class="display-6 fw-bold mb-3">Et si vous rejoigniez l'aventure ?</h2>
        <p class="lead mb-4">
            Sponsoring, mécénat ou don : soutenez la formation des jeunes nageurs du Bocage Bressuirais
            et associez votre nom à un club créé en 1954.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ $becomePartnerUrl }}" class="btn btn-light btn-lg">
                <i class="fas fa-hands-helping me-2" aria-hidden="true"></i>Devenir partenaire
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-envelope me-2" aria-hidden="true"></i>Nous contacter
            </a>
        </div>
    </div>
</section>

@endsection


@push('styles')
<style>
    /* Titre de bloc : pastille aux couleurs du niveau */
    .partners-level-title {
        font-size: 1.5rem;
        text-align: center;
    }

    .partners-level-chip {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 15px 0 15px 0;
        background: var(--level-bg);
        color: var(--level-text);
        box-shadow: 0 5px 6px 4px rgba(0, 0, 0, 0.05);
    }

    /* Cartes */
    .partners-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
    }

    .partners-card:hover,
    .partners-card:focus {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(47, 128, 184, 0.18) !important;
    }

    .partners-card:focus-visible {
        outline: 3px solid #2f80b8;
        outline-offset: 3px;
    }

    .partners-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        padding: 1rem;
        height: 140px;
    }

    .partners-card--principal .partners-logo { height: 260px; padding: 2rem; }
    .partners-card--or .partners-logo        { height: 180px; }
    .partners-card--bronze .partners-logo    { height: 100px; padding: 0.75rem; }

    .partners-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .partners-initials {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4.5rem;
        height: 4.5rem;
        border-radius: 50%;
        background: #f9f5f4;
        color: #2f80b8;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .partners-card--principal .partners-initials {
        width: 8rem;
        height: 8rem;
        font-size: 2.5rem;
    }

    /* Bouton bleu : texte toujours blanc, quelle que soit la couleur de lien du thème */
    .partners-btn,
    .partners-btn i {
        color: #fff !important;
    }

    .partners-btn {
        background: #2f80b8 !important;
        border-color: #2f80b8 !important;
    }

    .partners-join h2,
    .partners-join .lead {
        color: #fff !important;
    }

    @media (prefers-reduced-motion: reduce) {
        .partners-card { transition: none; }
        .partners-card:hover,
        .partners-card:focus { transform: none; }
    }
</style>
@endpush
