@extends('layouts.public')

@php
    // Résumé en texte brut pour les moteurs de recherche et le partage
    $plainSummary = trim(html_entity_decode(strip_tags((string) $fiche->short_description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $publishedAt  = $fiche->published_at ?? $fiche->created_at;
    $sousCategory = $sousCategory ?? $fiche->sousCategory;
    $views        = $fiche->views_count ?? 0;
    $canView      = $fiche->canViewContent(auth()->user());
@endphp

{{-- SEO --}}
@section('title', $fiche->title . ' — Infos pratiques')
@section('meta_description', Str::limit($plainSummary, 160))

{{-- Partage --}}
@section('og_type', 'article')
@section('og_title', $fiche->title)
@section('og_description', Str::limit($plainSummary, 200))
@if($fiche->image)
    @section('og_image', $fiche->image)
    @section('og_image_alt', $fiche->title)
@endif

@section('twitter_title', $fiche->title)
@section('twitter_description', Str::limit($plainSummary, 200))
@if($fiche->image)
    @section('twitter_image', $fiche->image)
    @section('twitter_image_alt', $fiche->title)
@endif

@section('content')

<!-- En-tête -->
<section class="nataswim-titre1 position-relative text-white">
    <div class="container-lg">
        <nav aria-label="Fil d'Ariane">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.fiches.index') }}">Infos pratiques</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.fiches.category', $category) }}">{{ $category->name }}</a></li>
                @if ($sousCategory)
                    <li class="breadcrumb-item">
                        <a href="{{ route('public.fiches.sous-category', [$category, $sousCategory]) }}">{{ $sousCategory->name }}</a>
                    </li>
                @endif
            </ol>
        </nav>

        <h1 class="text-white display-5 fw-bold mb-3">{{ $fiche->title }}</h1>

        <p class="page-meta mb-0">
            @if ($publishedAt)
                <span class="me-3">
                    <i class="fas fa-calendar me-1" aria-hidden="true"></i>
                    <time datetime="{{ $publishedAt->format('Y-m-d') }}">{{ $publishedAt->locale('fr')->isoFormat('D MMMM YYYY') }}</time>
                </span>
            @endif
            <span class="me-3">
                <i class="fas fa-eye me-1" aria-hidden="true"></i>{{ number_format($views, 0, ',', ' ') }} {{ $views > 1 ? 'lectures' : 'lecture' }}
            </span>
            @if ($fiche->creator)
                <span class="me-3">
                    <i class="fas fa-user me-1" aria-hidden="true"></i>{{ $fiche->creator->name }}
                </span>
            @endif
            @if ($fiche->visibility === 'authenticated')
                <span>
                    <i class="fas fa-lock me-1" aria-hidden="true"></i>Réservée aux adhérents
                </span>
            @endif
        </p>

        @if ($fiche->image)
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8">
                    <img src="{{ $fiche->image }}" alt="" class="img-fluid rounded-lg shadow-aqua">
                </div>
            </div>
        @endif
    </div>
</section>


<article class="py-5 bg-white">
    <div class="container-lg">

        <!-- En bref -->
        @if ($fiche->short_description)
            <div class="card-aqua mb-4">
                <div class="alert alert-info border-0 mb-0 bg-info-lighter">
                    <div class="content-display">
                        {!! $fiche->short_description !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenu -->
        @if ($fiche->long_description)
            <div class="card-aqua mb-4">
                @if ($canView)
                    <div class="content-display">
                        {!! $fiche->long_description !!}
                    </div>
                @else
                    <x-public.locked-notice title="Fiche réservée aux adhérents" />
                @endif
            </div>
        @endif

        <!-- Navigation -->
        <div class="row g-4">
            <div class="col-md-6">
                <a href="{{ route('public.fiches.category', $category) }}" class="text-decoration-none d-block h-100">
                    <div class="card-aqua h-100 hover-lift d-flex align-items-center">
                        @if ($category->image)
                            <img src="{{ $category->image }}"
                                 class="rounded me-3"
                                 style="width: 70px; height: 70px; object-fit: cover;"
                                 alt="" loading="lazy">
                        @else
                            <div class="bg-primary-lighter rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                 style="width: 70px; height: 70px;">
                                <i class="fas fa-folder text-primary fs-3" aria-hidden="true"></i>
                            </div>
                        @endif
                        <div>
                            <small class="text-muted d-block">Dans la même catégorie</small>
                            <span class="h6 mb-0 text-dark">{{ $category->name }}</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <div class="card-aqua h-100">
                    <div class="d-grid gap-2">
                        @if ($sousCategory)
                            <a href="{{ route('public.fiches.sous-category', [$category, $sousCategory]) }}" class="btn btn-primary text-white">
                                <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Retour à {{ Str::limit($sousCategory->name, 30) }}
                            </a>
                        @else
                            <a href="{{ route('public.fiches.category', $category) }}" class="btn btn-primary text-white">
                                <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Retour à {{ Str::limit($category->name, 30) }}
                            </a>
                        @endif
                        <a href="{{ route('public.fiches.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-th me-2" aria-hidden="true"></i>Toutes les infos pratiques
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</article>

@endsection
