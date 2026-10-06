@extends('layouts.public')

@php
    // Résumé en texte brut pour les moteurs de recherche et le partage
    $plainSummary = trim(html_entity_decode(strip_tags((string) $page->short_description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $publishedAt  = $page->published_at ?? $page->created_at;
    $pageCategory = $page->category;
    $canView      = $page->canViewContent(auth()->user());
@endphp

{{-- SEO --}}
@section('title', $page->title . ' — Le Club')
@section('meta_description', Str::limit($plainSummary, 160))

{{-- Partage --}}
@section('og_type', 'article')
@section('og_title', $page->title)
@section('og_description', Str::limit($plainSummary, 200))
@if($page->image)
    @section('og_image', $page->image)
    @section('og_image_alt', $page->title)
@endif

@section('twitter_title', $page->title)
@section('twitter_description', Str::limit($plainSummary, 200))
@if($page->image)
    @section('twitter_image', $page->image)
    @section('twitter_image_alt', $page->title)
@endif

@section('content')

<!-- En-tête -->
<section class="nataswim-titre1 position-relative text-white">
    <div class="container-lg">
        <nav aria-label="Fil d'Ariane">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.pages.index') }}">Le Club</a></li>
                @if ($pageCategory)
                    <li class="breadcrumb-item">
                        <a href="{{ route('public.pages.category', $pageCategory) }}">{{ $pageCategory->name }}</a>
                    </li>
                @endif
            </ol>
        </nav>

        <h1 class="text-white display-5 fw-bold mb-3">{{ $page->title }}</h1>

        <p class="page-meta mb-0">
            @if ($publishedAt)
                <span class="me-3">
                    <i class="fas fa-calendar me-1" aria-hidden="true"></i>
                    Mise en ligne le
                    <time datetime="{{ $publishedAt->format('Y-m-d') }}">{{ $publishedAt->locale('fr')->isoFormat('D MMMM YYYY') }}</time>
                </span>
            @endif
            @if ($page->creator)
                <span class="me-3">
                    <i class="fas fa-user me-1" aria-hidden="true"></i>{{ $page->creator->name }}
                </span>
            @endif
            @if ($page->visibility === 'authenticated')
                <span>
                    <i class="fas fa-lock me-1" aria-hidden="true"></i>Réservée aux adhérents
                </span>
            @endif
        </p>

        @if ($page->image)
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8">
                    <img src="{{ $page->image }}" alt="" class="img-fluid rounded-lg shadow-aqua">
                </div>
            </div>
        @endif
    </div>
</section>


<article class="py-5 bg-white">
    <div class="container-lg">

        <!-- En bref -->
        @if ($page->short_description)
            <div class="card-aqua mb-4">
                <div class="alert alert-info border-0 mb-0 bg-info-lighter">
                    <div class="content-display">
                        {!! $page->short_description !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenu -->
        @if ($page->long_description)
            <div class="card-aqua mb-4">
                @if ($canView)
                    <div class="content-display">
                        {!! $page->long_description !!}
                    </div>
                @else
                    <x-public.locked-notice title="Page réservée aux adhérents" />
                @endif
            </div>
        @endif

        <!-- Navigation -->
        <div class="row g-4">
            @if ($pageCategory)
                <div class="col-md-6">
                    <a href="{{ route('public.pages.category', $pageCategory) }}" class="text-decoration-none d-block h-100">
                        <div class="card-aqua h-100 hover-lift d-flex align-items-center">
                            @if ($pageCategory->image)
                                <img src="{{ $pageCategory->image }}"
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
                                <small class="text-muted d-block">Dans la même rubrique</small>
                                <span class="h6 mb-0 text-dark">{{ $pageCategory->name }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            <div class="col-md-6">
                <div class="card-aqua h-100">
                    <div class="d-grid gap-2">
                        @if ($pageCategory)
                            <a href="{{ route('public.pages.category', $pageCategory) }}" class="btn btn-primary text-white">
                                <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Retour à {{ Str::limit($pageCategory->name, 30) }}
                            </a>
                        @endif
                        <a href="{{ route('public.pages.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-th me-2" aria-hidden="true"></i>Toutes les informations du club
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</article>

@endsection
