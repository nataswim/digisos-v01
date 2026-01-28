@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', $exercice->titre . ' - Exercice d\'Entraînement')
@section('meta_description', 'Découvrez l\'exercice ' . $exercice->titre . ($exercice->type_exercice ? ' - ' . $exercice->type_exercice_label : '') . ($exercice->niveau ? ' niveau ' . $exercice->niveau_label : '') . '. Instructions détaillées et conseils de sécurité.')

{{-- Open Graph / Facebook --}}
@section('og_type', 'article')
@section('og_title', $exercice->titre . ' - Exercice')
@section('og_description', $exercice->description ? Str::limit(strip_tags($exercice->description), 200) : 'Exercice d\'entraînement avec instructions détaillées')
@section('og_url', route('exercices.show', $exercice))
@if($exercice->image)
@section('og_image', $exercice->image)
@section('og_image_alt', $exercice->titre)
@endif

{{-- Twitter Card --}}
@section('twitter_title', $exercice->titre)
@section('twitter_description', $exercice->description ? Str::limit(strip_tags($exercice->description), 200) : 'Exercice d\'entraînement')
@if($exercice->image)
@section('twitter_image', $exercice->image)
@endif

@section('content')

<!-- En-tête de section -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg">
                <h1 class="display-5 fw-bold mb-3">{{ $exercice->titre }}</h1>
            </div>
        </div>
    </div>
    @if($exercice->image)
    <div>

        <div>
            <div class="content-display fs-6 lh-lg">
                <img src="{{ $exercice->image }}"
                    alt="{{ $exercice->titre }}">
            </div>
        </div>
    </div>
    @endif
</section>

<!-- Breadcrumb -->
<section class="py-3 bg-light border-bottom">
    <div class="container-lg">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('exercices.index') }}">
                        <i class="fas fa-dumbbell me-1"></i>Exercices
                    </a>
                </li>
                @if($exercice->categories->isNotEmpty())
                <li class="breadcrumb-item">
                    <a href="{{ route('exercices.category', $exercice->categories->first()) }}">
                        {{ $exercice->categories->first()->name }}
                    </a>
                </li>
                @endif
                @if($exercice->sousCategories->isNotEmpty())
                <li class="breadcrumb-item">
                    <a href="{{ route('exercices.sous-category', [$exercice->categories->first(), $exercice->sousCategories->first()]) }}">
                        {{ $exercice->sousCategories->first()->name }}
                    </a>
                </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">
                    {!! Str::limit($exercice->titre, 50) !!}
                </li>
            </ol>
        </nav>
    </div>
</section>

<article class="py-4">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-12">

                <!-- Card 2: Description -->
                @if($exercice->description)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="content-display fs-6 lh-lg">
                            {!! $exercice->description !!}
                        </div>
                    </div>
                </div>
                @endif



                <!-- Card 4: Consignes  -->
                @if($exercice->consignes_securite)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="content-display-warning fs-6 lh-lg">
                            {!! $exercice->consignes_securite !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Card 5: Vidéo  -->
                @if($exercice->video_url)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-video me-2"></i>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @php
                        $videoUrl = $exercice->video_url;
                        $isYoutube = str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be');
                        $isVimeo = str_contains($videoUrl, 'vimeo.com');
                        $isDirectFile = preg_match('/\.(mp4|webm|ogg)$/i', $videoUrl);

                        // Conversion URL YouTube
                        if ($isYoutube) {
                        if (str_contains($videoUrl, 'youtu.be/')) {
                        $videoId = substr(parse_url($videoUrl, PHP_URL_PATH), 1);
                        $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                        } else {
                        $embedUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                        }
                        }

                        // Conversion URL Vimeo
                        if ($isVimeo) {
                        $videoId = substr(parse_url($videoUrl, PHP_URL_PATH), 1);
                        $embedUrl = "https://player.vimeo.com/video/{$videoId}";
                        }
                        @endphp

                        {{-- YouTube --}}
                        @if($isYoutube)
                        <div class="ratio ratio-16x9">
                            <iframe
                                src="{{ $embedUrl }}"
                                title="Vidéo YouTube"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                class="rounded">
                            </iframe>
                        </div>

                        {{-- Vimeo --}}
                        @elseif($isVimeo)
                        <div class="ratio ratio-16x9">
                            <iframe
                                src="{{ $embedUrl }}"
                                title="Vidéo Vimeo"
                                frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen
                                class="rounded">
                            </iframe>
                        </div>

                        {{-- Fichier direct (MP4, WebM, OGG) --}}
                        @elseif($isDirectFile)
                        <div class="ratio ratio-16x9">
                            <video
                                controls
                                controlsList="nodownload"
                                class="rounded w-100"
                                preload="metadata">
                                <source src="{{ $videoUrl }}" type="video/{{ pathinfo($videoUrl, PATHINFO_EXTENSION) }}">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        </div>

                        {{-- URL non reconnue - Fallback --}}
                        @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Format de vidéo non supporté.
                            <a href="{{ $videoUrl }}" target="_blank" rel="noopener noreferrer" class="alert-link">
                                Ouvrir la vidéo dans un nouvel onglet
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Card 6: Exercices similaires -->
                @if(isset($exercicesSimilaires) && $exercicesSimilaires->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        @foreach($exercicesSimilaires as $similaire)
                        <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="row align-items-center">
                                @if($similaire->image)
                                <div class="col-auto">
                                    <img src="{{ $similaire->image }}"
                                        class="rounded"
                                        style="width: 80px; height: 60px; object-fit: cover;"
                                        alt="">
                                </div>
                                @else
                                <div class="col-auto">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="width: 80px; height: 60px;">
                                        <i class="fas fa-dumbbell text-muted fa-2x"></i>
                                    </div>
                                </div>
                                @endif
                                <div class="col">
                                    <a href="{{ route('exercices.show', $similaire) }}"
                                        class="text-decoration-none">
                                        <h6 class="mb-1">{!! Str::limit($similaire->titre, 60) !!}</h6>
                                    </a>
                                    <div class="small text-muted">
                                        @if($similaire->category)
                                        <span class="badge bg-primary-subtle text-primary me-2">
                                            {{ $similaire->category->name }}
                                        </span>
                                        @endif
                                        @if($similaire->sousCategory)
                                        <span class="badge bg-info-subtle text-info">
                                            {{ $similaire->sousCategory->name }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section Navigation -->
                <div class="row g-4 mb-4">
                    <!-- Catégories -->
                    @if($exercice->categories->isNotEmpty() || $exercice->sousCategories->isNotEmpty())
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-folder me-2"></i>Classification
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($exercice->categories->isNotEmpty())
                                <h6 class="fw-bold mb-2">Catégories</h6>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach($exercice->categories as $cat)
                                    <a href="{{ route('exercices.category', $cat) }}"
                                        class="badge bg-primary text-decoration-none px-3 py-2">
                                        <i class="fas fa-folder me-1"></i>{{ $cat->name }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif

                                @if($exercice->sousCategories->isNotEmpty())
                                <h6 class="fw-bold mb-2">Sous-catégories</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($exercice->sousCategories as $sousCat)
                                    <a href="{{ route('exercices.sous-category', [$sousCat->category, $sousCat]) }}"
                                        class="badge bg-info text-decoration-none px-3 py-2">
                                        <i class="fas fa-layer-group me-1"></i>{{ $sousCat->name }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Boutons de navigation -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    Plus
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    @if($exercice->sousCategory)
                                    <a href="{{ route('exercices.sous-category', [$exercice->category, $exercice->sousCategory]) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-arrow-left me-2"></i>{{ $exercice->sousCategory->name }}
                                    </a>
                                    @endif
                                    @if($exercice->category)
                                    <a href="{{ route('exercices.category', $exercice->category) }}"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-folder me-2"></i>{{ $exercice->category->name }}
                                    </a>
                                    @endif
                                    <a href="{{ route('exercices.index') }}"
                                        class="btn btn-outline-secondary">
                                        <i class="fas fa-th me-2"></i>Tous les exercices
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</article>

<!-- Section Découvrez aussi -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <h3 class="fw-bold text-center mb-4">Découvrez aussi</h3>
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('plans.index') }}" class="btn btn-outline-primary btn-lg w-100">
                    <i class="fas fa-calendar-alt me-2"></i>Plans de musculation
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('tools.index') }}" class="btn btn-outline-success btn-lg w-100">
                    <i class="fas fa-calculator me-2"></i>Outils de calcul
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('posts.public.index') }}" class="btn btn-outline-info btn-lg w-100">
                    <i class="fas fa-book me-2"></i>Articles & conseils
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    /* Styles pour le contenu HTML */
    .content-display h1,
    .content-display h2,
    .content-display h3,
    .content-display-warning h1,
    .content-display-warning h2,
    .content-display-warning h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .content-display h1,
    .content-display-warning h1 {
        font-size: 1.7rem;
    color: #0d7f8a;
    }

    .content-display h2,
    .content-display-warning h2 {
        font-size: 1.5rem;
    color: #0a7db1;
    }

    .content-display h3,
    .content-display-warning h3 {
        font-size: 1.3rem;
        color: #6a1414;
    }

    .content-display p,
    .content-display-warning p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
        text-align: justify;
        color: #4a5568;
    }

    .content-display ul,
    .content-display ol,
    .content-display-warning ul,
    .content-display-warning ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
        line-height: 1.7;
    }

    .content-display li,
    .content-display-warning li {
        margin-bottom: 0.5rem;
    }

    .content-display blockquote,
    .content-display-warning blockquote {
        border-left: 4px solid #3182ce;
        padding: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        background: #f7fafc;
        border-radius: 0.375rem;
        color: #2d3748;
    }

    .content-display-warning blockquote {
        border-left-color: #f59e0b;
        background: #fffbeb;
    }

    .content-display img,
    .content-display-warning img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 2rem 0;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .content-display pre,
    .content-display-warning pre {
        background: #1a202c;
        color: #e2e8f0;
        padding: 1.5rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 2rem 0;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .content-display code,
    .content-display-warning code {
        background-color: #edf2f7;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875em;
        color: #d63384;
        font-family: 'Courier New', monospace;
    }

    .content-display strong,
    .content-display-warning strong {
        font-weight: 600;
        color: #1e293b;
    }

    .card {
        transition: box-shadow 0.2s ease;
    }
.ql-video {
width: -webkit-fill-available;
    display: block;
    margin: 15px auto;
    height: 480px;
}
    @media (max-width: 991px) {

        .col-lg-7,
        .col-lg-5 {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 768px) {

        .content-display,
        .content-display-warning {
            font-size: 0.95rem;
        }

        .display-5 {
            font-size: 1.75rem !important;
        }

        .d-flex.gap-3 {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem !important;
        }
    }
</style>
@endpush