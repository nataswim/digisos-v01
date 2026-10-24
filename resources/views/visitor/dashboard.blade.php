@extends('layouts.public')

@section('title', 'Mon espace visiteur')

@section('content')
@php
    $acces = [
        ['icon' => 'fa-handshake',      'titre' => 'Nos partenaires', 'texte' => 'Les entreprises et collectivités qui soutiennent le club.', 'route' => 'partners.index'],
        ['icon' => 'fa-hand-holding-heart', 'titre' => 'Devenir mécène', 'texte' => 'Soutenez le club et bénéficiez d\'une réduction d\'impôt.', 'route' => 'support.mecenat'],
        ['icon' => 'fa-heart',          'titre' => 'Faire un don',    'texte' => 'Un don par chèque ou par virement, avec reçu fiscal.', 'route' => 'support.donation'],
        ['icon' => 'fa-calendar-alt',   'titre' => 'Calendrier',      'texte' => 'Les compétitions et événements à venir.', 'route' => 'competitions.index'],
    ];
    $acces = array_values(array_filter($acces, fn ($a) => Route::has($a['route'])));
@endphp

<div class="container-lg py-5 visitor-space">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Bonjour {{ $user->first_name ?: $user->name }}</h1>
            <p class="text-muted mb-0">Bienvenue dans votre espace sur le site du club.</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
            <i class="fas fa-user-edit me-2" aria-hidden="true"></i>Mon profil
        </a>
    </div>

    {{-- Statut du compte --}}
    <div class="visitor-status d-flex gap-3 align-items-start mb-5" role="note">
        <span class="visitor-status-icon" aria-hidden="true"><i class="fas fa-user-clock"></i></span>
        <div>
            <p class="fw-bold mb-1">Vous avez le statut « visiteur ».</p>
            <p class="mb-0">
                Un administrateur du club validera votre compte après vérification, pour vous permettre de voir plus de contenu
                et d'accéder à l'espace membre du club.
            </p>
        </div>
    </div>

    {{-- Accès rapides --}}
    @if ($acces)
        <section class="mb-5" aria-labelledby="titre-acces">
            <h2 id="titre-acces" class="visually-hidden">Accès rapides</h2>
            <ul class="row g-3 list-unstyled mb-0">
                @foreach ($acces as $a)
                    <li class="col-6 col-lg-3">
                        <a href="{{ route($a['route']) }}" class="visitor-tile card border-0 shadow-sm h-100 text-decoration-none">
                            <span class="card-body p-4 d-flex flex-column align-items-center text-center">
                                <span class="visitor-tile-icon mb-3" aria-hidden="true"><i class="fas {{ $a['icon'] }}"></i></span>
                                <span class="h5 fw-bold mb-2">{{ $a['titre'] }}</span>
                                <span class="small text-muted d-none d-sm-block">{{ $a['texte'] }}</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- Derniers articles --}}
    @if ($posts->isNotEmpty())
        <section class="mb-5" aria-labelledby="titre-articles">
            <div class="visitor-section-head">
                <h2 id="titre-articles" class="h4 fw-bold mb-0">
                    <i class="fas fa-newspaper me-2 text-primary" aria-hidden="true"></i>Derniers articles
                </h2>
                <a href="{{ route('posts.public.index') }}" class="btn btn-sm btn-outline-primary">Toutes les actualités</a>
            </div>
            <div class="row g-4">
                @foreach ($posts as $post)
                    <div class="col-sm-6 col-lg-3">
                        <x-public.post-card :post="$post" :intro-limit="80" />
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Dernières fiches --}}
    @if ($fiches->isNotEmpty())
        <section class="mb-5" aria-labelledby="titre-fiches">
            <div class="visitor-section-head">
                <h2 id="titre-fiches" class="h4 fw-bold mb-0">
                    <i class="fas fa-file-alt me-2 text-primary" aria-hidden="true"></i>Dernières fiches
                </h2>
                <a href="{{ route('public.fiches.index') }}" class="btn btn-sm btn-outline-primary">Toutes les fiches</a>
            </div>
            <div class="row g-4">
                @foreach ($fiches as $fiche)
                    <div class="col-sm-6 col-lg-3">
                        <x-public.content-card :item="$fiche" :url="$fiche->url !== '#' ? $fiche->url : null" :excerpt-limit="80" />
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Dernières pages --}}
    @if ($pages->isNotEmpty())
        <section class="mb-5" aria-labelledby="titre-pages">
            <div class="visitor-section-head">
                <h2 id="titre-pages" class="h4 fw-bold mb-0">
                    <i class="fas fa-book-open me-2 text-primary" aria-hidden="true"></i>Dernières pages
                </h2>
                <a href="{{ route('public.pages.index') }}" class="btn btn-sm btn-outline-primary">Toutes les pages</a>
            </div>
            <div class="row g-4">
                @foreach ($pages as $page)
                    <div class="col-sm-6 col-lg-3">
                        <x-public.content-card :item="$page" :url="$page->category ? $page->url : null" :excerpt-limit="80" />
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('styles')
<style>
    .visitor-status {
        padding: 1.25rem 1.5rem;
        border-radius: 14px;
        background: #F0F9FF;
        border-left: 5px solid #0090D4;
        color: #1d293d;
    }
    .visitor-status-icon {
        display: inline-flex; flex-shrink: 0;
        width: 44px; height: 44px; border-radius: 50%;
        align-items: center; justify-content: center;
        background: #0090D4; color: #fff; font-size: 1.1rem;
    }
    .visitor-tile {
        border-radius: 14px; color: #1d293d;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .visitor-tile:hover, .visitor-tile:focus { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 144, 212, .18) !important; color: #1d293d; }
    .visitor-tile:focus-visible { outline: 3px solid #0090D4; outline-offset: 2px; }
    .visitor-tile-icon {
        display: inline-flex; width: 60px; height: 60px; border-radius: 50%;
        align-items: center; justify-content: center;
        background: #E0F2FE; color: #0090D4; font-size: 1.5rem;
    }
    .visitor-section-head {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
        gap: .75rem; margin-bottom: 1.25rem; padding-bottom: .75rem; border-bottom: 2px solid #E0F2FE;
    }
    @media (prefers-reduced-motion: reduce) {
        .visitor-tile, .visitor-tile:hover { transition: none; transform: none; }
    }
</style>
@endpush
