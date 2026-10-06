@extends('layouts.public')

@section('title', 'Recherche' . ($query ? ' : ' . $query : ''))
@section('meta_description', 'Recherchez un article, une fiche pratique ou une vidéo sur le site du CNBB, club de natation de Bressuire.')

@section('content')

@php
    // Les trois familles de résultats (une famille absente est traitée comme vide)
    $posts  = $results['posts']  ?? collect();
    $fiches = $results['fiches'] ?? collect();
    $videos = $results['videos'] ?? collect();

    // Texte brut pour les extraits : on retire le HTML puis Blade échappe le résultat
    $extrait = fn ($html, $limite = 120) => Str::limit(
        trim(html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
        $limite
    );
@endphp

<div class="container-lg py-5">

    <!-- En-tête de recherche -->
    <header class="text-center mb-4">
        <h1 class="display-5 fw-bold mb-3">
            @if ($query)
                Résultats pour « {{ $query }} »
            @else
                Rechercher sur le site du club
            @endif
        </h1>

        @if ($query)
            <p class="text-muted mb-0" role="status">
                {{ $totalResults }} {{ $totalResults > 1 ? 'résultats trouvés' : 'résultat trouvé' }}
            </p>
        @else
            <p class="lead text-muted mb-0">
                Articles, fiches pratiques et vidéos : un ou deux mots suffisent.
            </p>
        @endif
    </header>

    <!-- Formulaire de recherche -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            @include('public.partials.search-form')
        </div>
    </div>

    @if ($query && $totalResults > 0)

        {{-- Accès direct à chaque famille de résultats --}}
        <nav class="d-flex flex-wrap justify-content-center gap-2 mb-5" aria-label="Types de résultats">
            @if ($posts->isNotEmpty())
                <a href="#resultats-articles" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="fas fa-newspaper me-1" aria-hidden="true"></i>Articles ({{ $posts->count() }})
                </a>
            @endif
            @if ($fiches->isNotEmpty())
                <a href="#resultats-fiches" class="btn btn-outline-success btn-sm rounded-pill">
                    <i class="fas fa-clipboard-list me-1" aria-hidden="true"></i>Fiches ({{ $fiches->count() }})
                </a>
            @endif
            @if ($videos->isNotEmpty())
                <a href="#resultats-videos" class="btn btn-outline-warning btn-sm rounded-pill">
                    <i class="fas fa-play-circle me-1" aria-hidden="true"></i>Vidéos ({{ $videos->count() }})
                </a>
            @endif
        </nav>

        <!-- Articles -->
        @if ($posts->isNotEmpty())
            <section id="resultats-articles" class="anchor-section mb-5">
                <h2 class="h4 mb-3">
                    <i class="fas fa-newspaper text-primary me-2" aria-hidden="true"></i>
                    Articles ({{ $posts->count() }})
                </h2>
                <div class="row g-4">
                    @foreach ($posts as $post)
                        <div class="col-md-6 col-lg-4">
                            <article class="card h-100 shadow-sm border-0 search-card">
                                @if ($post->image)
                                    <img src="{{ $post->image }}" class="card-img-top search-card-image" alt="" loading="lazy">
                                @endif
                                <div class="card-body">
                                    <h3 class="card-title h5">
                                        <a href="{{ $post->url }}" class="text-decoration-none text-dark stretched-link">
                                            {{ $post->name }}
                                        </a>
                                    </h3>
                                    <p class="card-text text-muted small mb-0">{{ $extrait($post->intro) }}</p>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <span class="btn btn-sm btn-outline-primary">Lire l'article</span>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Fiches -->
        @if ($fiches->isNotEmpty())
            <section id="resultats-fiches" class="anchor-section mb-5">
                <h2 class="h4 mb-3">
                    <i class="fas fa-clipboard-list text-success me-2" aria-hidden="true"></i>
                    Fiches pratiques ({{ $fiches->count() }})
                </h2>
                <div class="row g-4">
                    @foreach ($fiches as $fiche)
                        <div class="col-md-6 col-lg-4">
                            <article class="card h-100 shadow-sm border-0 search-card">
                                @if ($fiche->image)
                                    <img src="{{ $fiche->image }}" class="card-img-top search-card-image" alt="" loading="lazy">
                                @endif
                                <div class="card-body">
                                    <h3 class="card-title h5">
                                        <a href="{{ $fiche->url }}" class="text-decoration-none text-dark stretched-link">
                                            {{ $fiche->title }}
                                        </a>
                                    </h3>
                                    <p class="card-text text-muted small mb-0">{{ $extrait($fiche->short_description) }}</p>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <span class="btn btn-sm btn-outline-success">Voir la fiche</span>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Vidéos -->
        @if ($videos->isNotEmpty())
            <section id="resultats-videos" class="anchor-section mb-5">
                <h2 class="h4 mb-3">
                    <i class="fas fa-play-circle text-warning me-2" aria-hidden="true"></i>
                    Vidéos ({{ $videos->count() }})
                </h2>
                <div class="row g-4">
                    @foreach ($videos as $video)
                        <div class="col-md-6 col-lg-4">
                            <article class="card h-100 shadow-sm border-0 search-card">
                                @if ($video->thumbnail)
                                    <img src="{{ $video->thumbnail }}" class="card-img-top search-card-image" alt="" loading="lazy">
                                @endif
                                <div class="card-body">
                                    <h3 class="card-title h5">
                                        <a href="{{ route('public.videos.show', $video->slug) }}"
                                           class="text-decoration-none text-dark stretched-link">
                                            {{ $video->title }}
                                        </a>
                                    </h3>
                                    <p class="card-text text-muted small mb-0">{{ $extrait($video->description) }}</p>
                                    @if ($video->duration)
                                        <div class="mt-2">
                                            <span class="badge bg-dark">
                                                <i class="fas fa-clock me-1" aria-hidden="true"></i>{{ $video->formatted_duration }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <span class="btn btn-sm btn-outline-warning">Voir la vidéo</span>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    @elseif ($query)

        <!-- Aucun résultat -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="alert alert-info border-0 text-center p-4" role="status">
                    <i class="fas fa-search fa-3x mb-3" aria-hidden="true"></i>
                    <h2 class="h4">Aucun résultat pour « {{ $query }} »</h2>
                    <p class="mb-0">
                        Vérifiez l'orthographe, essayez un mot plus court ou un synonyme
                        (par exemple « inscription » plutôt que « s'inscrire »).
                    </p>
                </div>
            </div>
        </div>

    @endif

    {{-- Sans recherche ou sans résultat : proposer les rubriques --}}
    @if (! $query || $totalResults === 0)
        <section class="mt-5" aria-labelledby="rubriques-titre">
            <h2 id="rubriques-titre" class="h4 text-center mb-4">Ou parcourez les rubriques</h2>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-newspaper me-2" aria-hidden="true"></i>Vie du club
                </a>
                <a href="{{ route('public.fiches.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-clipboard-list me-2" aria-hidden="true"></i>Infos pratiques
                </a>
                <a href="{{ route('public.videos.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-play-circle me-2" aria-hidden="true"></i>Au fil de l'eau
                </a>
                <a href="{{ route('ebook.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-file-download me-2" aria-hidden="true"></i>Ressources
                </a>
                <a href="{{ route('pricing') }}" class="btn btn-outline-primary">
                    <i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>Inscription au club
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2" aria-hidden="true"></i>Contact
                </a>
            </div>
        </section>
    @endif

</div>
@endsection


@push('styles')
<style>
    .search-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .search-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12) !important;
    }

    .search-card-image {
        height: 200px;
        object-fit: cover;
    }

    @media (prefers-reduced-motion: reduce) {
        .search-card {
            transition: none;
        }

        .search-card:hover {
            transform: none;
        }
    }
</style>
@endpush
