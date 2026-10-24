@extends('layouts.public')

@section('title', 'Liens utiles')
@section('meta_description', 'Les sites internet utiles aux nageurs, parents et bénévoles du CNBB : fédération, résultats, compétitions, santé et bien plus.')
@section('og_title', 'Liens utiles — CNBB')
@section('og_description', 'Une sélection de sites utiles aux nageurs, parents et bénévoles du club de natation de Bressuire.')

@section('content')

@php
    $isAdmin = auth()->check() && auth()->user()->hasRole('admin');
@endphp

<x-public.hero
    title="Liens utiles"
    eyebrow="Vie du club"
    icon="fa-link"
    lead="Une sélection de sites internet utiles aux nageurs, aux parents et aux bénévoles, choisie par le club." />


<section class="py-5 bg-white">
    <div class="container-lg">

        @if ($categories->isEmpty())
            <div class="card-aqua text-center py-5">
                <i class="fas fa-link fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-0">Les liens utiles arrivent bientôt.</p>
            </div>

            @if ($isAdmin)
                <div class="alert alert-info border-0 shadow-sm mt-4 mb-0" role="note">
                    <i class="fas fa-info-circle me-2" aria-hidden="true"></i>
                    Ajoutez une catégorie puis vos liens dans l'administration :
                    <a href="{{ route('admin.links.items.index') }}">gérer les liens utiles</a>.
                </div>
            @endif
        @else
            {{-- Accès direct aux catégories --}}
            @if ($categories->count() > 1)
                <nav aria-label="Catégories de liens" class="mb-5">
                    <ul class="nav nav-pills links-jump justify-content-center gap-2">
                        @foreach ($categories as $category)
                            <li class="nav-item">
                                <a class="nav-link" href="#{{ $category->slug }}">
                                    {{ $category->name }} <span class="links-count">{{ $category->links->count() }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            @foreach ($categories as $category)
                <section id="{{ $category->slug }}" class="links-section {{ $loop->last ? '' : 'mb-5 pb-3' }}" aria-labelledby="{{ $category->slug }}-title">
                    <h2 id="{{ $category->slug }}-title" class="h4 fw-bold mb-3 links-title">{{ $category->name }}</h2>
                    @if ($category->description)
                        <p class="text-muted mb-4 links-intro">{{ $category->description }}</p>
                    @endif

                    <ul class="row g-4 list-unstyled mb-0 {{ $category->description ? '' : 'mt-1' }}">
                        @foreach ($category->links as $link)
                            <li class="col-sm-6 col-lg-4">
                                <article class="links-card card border-0 shadow-sm h-100">
                                    <div class="links-image">
                                        @if ($link->image)
                                            <img src="{{ $link->image }}" alt="" loading="lazy">
                                        @else
                                            {{-- Icône du site ; si elle ne se charge pas, un globe la remplace --}}
                                            <span class="links-favicon">
                                                @if ($link->favicon_url)
                                                    <img src="{{ $link->favicon_url }}" alt="" loading="lazy" width="48" height="48"
                                                         onerror="this.remove()">
                                                @endif
                                                <i class="fas fa-globe" aria-hidden="true"></i>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="card-body p-4 d-flex flex-column">
                                        <h3 class="h5 fw-bold mb-1">
                                            <a href="{{ $link->url }}" target="_blank" rel="noopener" class="stretched-link text-decoration-none links-name">
                                                {{ $link->title }}
                                                <span class="visually-hidden">(nouvel onglet)</span>
                                            </a>
                                        </h3>
                                        @if ($link->host)
                                            <p class="small links-host mb-2">
                                                <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>{{ $link->host }}
                                            </p>
                                        @endif

                                        @if ($link->description)
                                            <p class="text-muted mb-3">{{ $link->description }}</p>
                                        @endif

                                        @if (count($link->social_links))
                                            {{-- Au-dessus du lien « carte entière » pour rester cliquables --}}
                                            <ul class="links-social list-unstyled d-flex gap-2 mb-3">
                                                @foreach ($link->social_links as $reseau)
                                                    <li>
                                                        <a href="{{ $reseau['url'] }}" target="_blank" rel="noopener"
                                                           class="links-social-btn links-social-btn--{{ $reseau['class'] }}"
                                                           title="{{ $reseau['label'] }} — {{ $link->title }}">
                                                            <i class="{{ $reseau['icon'] }}" aria-hidden="true"></i>
                                                            <span class="visually-hidden">{{ $reseau['label'] }} de {{ $link->title }} (nouvel onglet)</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @if (count($link->keyword_list))
                                            <p class="mt-auto mb-0 d-flex flex-wrap gap-1">
                                                <span class="visually-hidden">Mots-clés :</span>
                                                @foreach ($link->keyword_list as $mot)
                                                    <span class="links-keyword">{{ $mot }}</span>
                                                @endforeach
                                            </p>
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
                    <a href="{{ route('admin.links.items.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit me-1" aria-hidden="true"></i>Gérer les liens utiles
                    </a>
                </p>
            @endif
        @endif

        <p class="small text-muted text-center mt-5 mb-0">
            <i class="fas fa-info-circle me-1" aria-hidden="true"></i>
            Ces liens mènent vers des sites extérieurs au club, qui s'ouvrent dans un nouvel onglet.
            Un lien ne fonctionne plus ? <a href="{{ route('contact') }}">Signalez-le-nous</a>.
        </p>
    </div>
</section>

@endsection


@push('styles')
<style>
    .links-section {
        scroll-margin-top: 110px;
    }

    .links-title {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 15px 0 15px 0;
        background-image: linear-gradient(129deg, #f9be38 85%, #2f80b8 0);
        color: #1c2111;
        box-shadow: 0 5px 6px 4px rgba(0, 0, 0, 0.05);
    }

    .links-intro {
        max-width: 760px;
    }

    .links-jump .nav-link {
        border-radius: 999px;
        border: 1px solid #2f80b8;
        color: #2f80b8;
    }

    .links-jump .nav-link:hover,
    .links-jump .nav-link:focus {
        background: #2f80b8;
        color: #fff;
    }

    .links-count {
        display: inline-block;
        min-width: 1.5em;
        margin-left: 0.25rem;
        padding: 0 0.4em;
        border-radius: 999px;
        background: rgba(47, 128, 184, 0.12);
        font-size: 0.8em;
    }

    .links-card {
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .links-card:hover,
    .links-card:focus-within {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(47, 128, 184, 0.18) !important;
    }

    .links-card:focus-within {
        outline: 3px solid #2f80b8;
        outline-offset: 2px;
    }

    .links-image {
        aspect-ratio: 16 / 9;
        background: #f6f8fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .links-image > img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1rem;
    }

    .links-favicon {
        position: relative;
        display: inline-flex;
        width: 88px;
        height: 88px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        color: #2f80b8;
        font-size: 2.25rem;
    }

    /* Icône du site par-dessus le globe ; le globe reste visible si l'icône ne se charge pas */
    .links-favicon img {
        position: absolute;
        width: 48px;
        height: 48px;
        object-fit: contain;
        background: #fff;
    }

    .links-name {
        color: #1c2111;
    }

    .links-name:hover,
    .links-name:focus {
        color: #2f80b8;
        text-decoration: underline !important;
    }

    .links-host {
        color: #2f80b8;
        word-break: break-all;
    }

    .links-social {
        position: relative;
        z-index: 2;   /* devant le lien qui couvre la carte */
    }

    .links-social-btn {
        display: inline-flex;
        width: 38px;
        height: 38px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        font-size: 1.05rem;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .links-social-btn--facebook { background: #1877f2; }
    .links-social-btn--instagram { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285aeb 90%); }

    .links-social-btn:hover,
    .links-social-btn:focus {
        color: #fff;
        transform: scale(1.08);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .links-social-btn:focus-visible {
        outline: 3px solid #f9be38;
        outline-offset: 2px;
    }

    .links-keyword {
        display: inline-block;
        padding: 0.15rem 0.6rem;
        border-radius: 999px;
        background: #e8f2f9;
        color: #24668f;
        font-size: 0.8rem;
        font-weight: 600;
    }

    @media (prefers-reduced-motion: reduce) {
        .links-card, .links-social-btn { transition: none; }
        .links-card:hover, .links-card:focus-within { transform: none; }
    }
</style>
@endpush
