@extends('layouts.public')

@php
    $descriptionText = trim(html_entity_decode(strip_tags((string) $category->description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $total = $fiches->total();
@endphp

@section('title', $category->name . ' — Infos pratiques')
@section('meta_description', $descriptionText !== '' ? Str::limit($descriptionText, 160) : 'Les fiches d\'information du CNBB dans la catégorie ' . $category->name . '.')

@section('content')

<!-- En-tête -->
<section class="nataswim-titre1 position-relative text-white">
    <div class="container-lg">
        <nav aria-label="Fil d'Ariane">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.fiches.index') }}">Infos pratiques</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1 class="text-white display-5 fw-bold mb-3">{{ $category->name }}</h1>

        @if ($descriptionText !== '')
            <p class="lead mb-0">{{ $descriptionText }}</p>
        @endif

        <p class="page-meta mt-3 mb-0">
            <i class="fas fa-file-alt me-1" aria-hidden="true"></i>{{ $total }} {{ $total > 1 ? 'fiches' : 'fiche' }}
        </p>
    </div>
</section>


<!-- Sous-catégories -->
@if (isset($sousCategories) && $sousCategories->count() > 0)
    <section class="py-5 bg-white">
        <div class="container-lg">
            <h2 class="title-aqua-secondary mb-4">Affiner par sous-catégorie</h2>

            <div class="row g-4">
                @foreach ($sousCategories as $sousCategory)
                    @php $nb = $sousCategory->published_fiches_count ?? 0; @endphp
                    <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <a href="{{ route('public.fiches.sous-category', [$category, $sousCategory]) }}"
                           class="text-decoration-none d-block h-100">
                            <div class="card-aqua h-100 hover-lift">
                                <div class="d-flex align-items-center">
                                    @if ($sousCategory->image)
                                        <img src="{{ $sousCategory->image }}"
                                             class="rounded me-3"
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             alt="" loading="lazy">
                                    @else
                                        <div class="bg-info-lighter rounded d-flex align-items-center justify-content-center me-3"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-layer-group text-info fs-3" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h3 class="h6 mb-1 text-dark">{{ $sousCategory->name }}</h3>
                                        <small class="text-muted">{{ $nb }} {{ $nb > 1 ? 'fiches' : 'fiche' }}</small>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted" aria-hidden="true"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif


<!-- Liste des fiches -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <h2 class="title-aqua-secondary mb-4">Les fiches</h2>

        @if ($fiches->count() > 0)
            <div class="row g-4">
                @foreach ($fiches as $fiche)
                    <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <x-public.content-card
                            :item="$fiche"
                            :url="$fiche->sousCategory ? route('public.fiches.show', [$category, $fiche->sousCategory, $fiche]) : null"
                            :meta="number_format($fiche->views_count ?? 0, 0, ',', ' ') . (($fiche->views_count ?? 0) > 1 ? ' lectures' : ' lecture')">
                            @if ($fiche->sousCategory)
                                <span class="badge badge-primary">
                                    <i class="fas fa-layer-group me-1" aria-hidden="true"></i>{{ $fiche->sousCategory->name }}
                                </span>
                            @endif
                        </x-public.content-card>
                    </div>
                @endforeach
            </div>

            @if ($fiches->hasPages())
                <div class="mt-5">
                    {{ $fiches->links('pagination.five-per-row') }}
                </div>
            @endif
        @else
            <div class="card-aqua text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-0">Aucune fiche dans cette catégorie pour le moment.</p>
            </div>
        @endif
    </div>
</section>


<!-- Navigation -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start">
                <a href="{{ route('public.fiches.index') }}" class="btn btn-primary btn-lg text-white">
                    <i class="fas fa-th me-2" aria-hidden="true"></i>Toutes les infos pratiques
                </a>
            </div>

            @if ($category->image)
                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                    <img src="{{ $category->image }}"
                         alt=""
                         class="img-fluid rounded-lg shadow-aqua"
                         style="max-height: 300px;" loading="lazy">
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
