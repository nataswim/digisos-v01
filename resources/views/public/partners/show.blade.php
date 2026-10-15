@extends('layouts.public')

@section('title', $partner->name . ' — Partenaire du club')
@section('meta_description', Str::limit($partner->summary ?: trim(html_entity_decode(strip_tags((string) $partner->presentation), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: $partner->name . ', partenaire du Cercle des Nageurs du Bocage Bressuirais.', 155))

@section('content')

@php
    $info = $partner->level_info;

    // Textes de l'éditeur, nettoyés par le modèle (App\Support\RichText)
    $presentation = $partner->presentation_html;
    $actions = $partner->actions_html;

    $canManage = auth()->check()
        && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor'));
@endphp


<x-public.hero
    :title="$partner->name"
    :eyebrow="$info['label']"
    icon="fa-handshake"
    video="assets/images/team/CNBB-natation-3.mp4"
    :lead="$partner->summary">
    <a href="{{ route('partners.index') }}" class="btn btn-light btn-lg">
        <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Tous les partenaires
    </a>
    @if ($partner->website)
        <a href="{{ $partner->website }}" class="btn btn-outline-light btn-lg" target="_blank" rel="noopener">
            <i class="fas fa-external-link-alt me-2" aria-hidden="true"></i>Site internet<span class="visually-hidden"> (nouvel onglet)</span>
        </a>
    @endif
</x-public.hero>


<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row g-4 g-lg-5">
            {{-- Carte d'identité --}}
            <aside class="col-lg-4 order-lg-2">
                <div class="card border-0 shadow-sm partners-id">
                    <div class="partners-id-logo">
                        @if ($partner->logo_url)
                            <img src="{{ $partner->logo_url }}" alt="Logo de {{ $partner->name }}">
                        @else
                            <span class="partners-initials" aria-hidden="true">{{ $partner->initials }}</span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <span class="badge mb-3" style="background: {{ $info['bg'] }}; color: {{ $info['text'] }};">
                            <i class="fas {{ $info['icon'] }} me-1" aria-hidden="true"></i>{{ $info['label'] }}
                        </span>

                        <dl class="mb-0">
                            @if ($partner->category_label)
                                <dt class="small text-muted fw-normal">Type de partenariat</dt>
                                <dd class="fw-semibold">{{ $partner->category_label }}</dd>
                            @endif
                            @if ($partner->partner_since)
                                <dt class="small text-muted fw-normal">Partenaire depuis</dt>
                                <dd class="fw-semibold">{{ $partner->partner_since }}</dd>
                            @endif
                            @if ($partner->website)
                                <dt class="small text-muted fw-normal">Site internet</dt>
                                <dd class="mb-0">
                                    <a href="{{ $partner->website }}" target="_blank" rel="noopener">
                                        {{ $partner->website_host }}<span class="visually-hidden"> (nouvel onglet)</span>
                                        <i class="fas fa-external-link-alt small ms-1" aria-hidden="true"></i>
                                    </a>
                                </dd>
                            @endif
                        </dl>

                        @if ($canManage)
                            <hr>
                            <a href="{{ route((auth()->user()->hasRole('admin') ? 'admin' : 'editor') . '.partners.edit', $partner) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1" aria-hidden="true"></i>Modifier cette fiche
                            </a>
                        @endif
                    </div>
                </div>
            </aside>

            {{-- Présentation et actions --}}
            <div class="col-lg-8 order-lg-1">
                <h2 class="h3 fw-bold mb-3">Présentation</h2>
                @if ($presentation !== '')
                    <div class="partners-text">{!! $presentation !!}</div>
                @else
                    <p class="text-muted">{{ $partner->name }} soutient le Cercle des Nageurs du Bocage Bressuirais.</p>
                @endif

                @if ($actions !== '')
                    <h2 class="h3 fw-bold mt-5 mb-3">Ses actions auprès du club</h2>
                    <div class="partners-text partners-actions">{!! $actions !!}</div>
                @endif
            </div>
        </div>
    </div>
</section>


{{-- Autres partenaires du même niveau --}}
@if ($others->isNotEmpty())
    <section class="py-5 bg-light">
        <div class="container-lg">
            <h2 class="h4 fw-bold text-center mb-4">Les autres {{ mb_strtolower($info['label']) }}</h2>
            <div class="row g-3 justify-content-center">
                @foreach ($others as $autre)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('partners.show', $autre) }}" class="partners-mini card border-0 shadow-sm h-100 text-decoration-none text-center p-3">
                            <div class="partners-mini-logo mb-2">
                                @if ($autre->logo_url)
                                    <img src="{{ $autre->logo_url }}" alt="" loading="lazy">
                                @else
                                    <span class="partners-initials" aria-hidden="true">{{ $autre->initials }}</span>
                                @endif
                            </div>
                            <span class="small fw-semibold text-dark">{{ $autre->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif


<!-- Devenir partenaire -->
<section class="py-5 bg-primary text-white partners-join">
    <div class="container-lg text-center">
        <h2 class="h3 fw-bold mb-3">Vous aussi, soutenez le club</h2>
        <p class="mb-4">Sponsoring, mécénat ou don : chaque partenaire aide le CNBB à former ses nageurs.</p>
        <a href="{{ $becomePartnerUrl }}" class="btn btn-light btn-lg">
            <i class="fas fa-hands-helping me-2" aria-hidden="true"></i>Devenir partenaire
        </a>
    </div>
</section>

@endsection


@push('styles')
<style>
    .partners-id-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 220px;
        padding: 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }

    .partners-id-logo img,
    .partners-mini-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .partners-initials {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 6rem;
        height: 6rem;
        border-radius: 50%;
        background: #f9f5f4;
        color: #2f80b8;
        font-size: 2rem;
        font-weight: 700;
    }

    .partners-mini-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 70px;
    }

    .partners-mini-logo .partners-initials {
        width: 3.5rem;
        height: 3.5rem;
        font-size: 1.1rem;
    }

    .partners-text {
        line-height: 1.75;
    }

    .partners-text img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .partners-text iframe {
        max-width: 100%;
    }

    /* Listes d'actions : coche verte à la place de la puce */
    .partners-actions ul {
        list-style: none;
        padding-left: 0;
    }

    .partners-actions ul > li {
        position: relative;
        padding: 0.6rem 0 0.6rem 1.9rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .partners-actions ul > li:last-child {
        border-bottom: 0;
    }

    .partners-actions ul > li::before {
        content: "\f058";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 0.6rem;
        color: #198754;
    }

    .partners-mini {
        transition: transform 0.25s ease;
    }

    .partners-mini:hover,
    .partners-mini:focus {
        transform: translateY(-3px);
    }

    .partners-join h2,
    .partners-join p {
        color: #fff !important;
    }

    @media (prefers-reduced-motion: reduce) {
        .partners-mini { transition: none; }
        .partners-mini:hover,
        .partners-mini:focus { transform: none; }
    }
</style>
@endpush
