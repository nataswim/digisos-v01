@extends('layouts.public')

@php
    $descriptionText = trim(html_entity_decode(strip_tags((string) $sousCategory->description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $total = $fiches->total();
@endphp

@section('title', $sousCategory->name . ' — ' . $category->name)
@section('meta_description', $descriptionText !== '' ? Str::limit($descriptionText, 160) : 'Les fiches d\'information du CNBB : ' . $category->name . ', ' . $sousCategory->name . '.')

@section('content')

<!-- En-tête -->
<section class="nataswim-titre1 position-relative text-white">
    <div class="container-lg">
        <nav aria-label="Fil d'Ariane">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.fiches.index') }}">Infos pratiques</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.fiches.category', $category) }}">{{ $category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $sousCategory->name }}</li>
            </ol>
        </nav>

        <h1 class="text-white display-5 fw-bold mb-3">{{ $sousCategory->name }}</h1>

        @if ($descriptionText !== '')
            <p class="lead mb-0">{{ $descriptionText }}</p>
        @endif

        <p class="page-meta mt-3 mb-0">
            <i class="fas fa-file-alt me-1" aria-hidden="true"></i>{{ $total }} {{ $total > 1 ? 'fiches' : 'fiche' }}
        </p>
    </div>
</section>


<!-- Liste des fiches -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <h2 class="visually-hidden">Fiches de la sous-catégorie {{ $sousCategory->name }}</h2>

        @if ($fiches->count() > 0)
            <div class="row g-4">
                @foreach ($fiches as $fiche)
                    <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <x-public.content-card
                            :item="$fiche"
                            :url="route('public.fiches.show', [$category, $sousCategory, $fiche])"
                            :meta="number_format($fiche->views_count ?? 0, 0, ',', ' ') . (($fiche->views_count ?? 0) > 1 ? ' lectures' : ' lecture')" />
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
                <p class="h5 text-muted mb-0">Aucune fiche dans cette sous-catégorie pour le moment.</p>
            </div>
        @endif
    </div>
</section>


<!-- Navigation -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('public.fiches.category', $category) }}" class="btn btn-primary text-white">
                        <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Retour à {{ $category->name }}
                    </a>
                    <a href="{{ route('public.fiches.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-th me-2" aria-hidden="true"></i>Toutes les infos pratiques
                    </a>
                </div>
            </div>

            @if ($sousCategory->image)
                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                    <img src="{{ $sousCategory->image }}"
                         alt=""
                         class="img-fluid rounded-lg shadow-aqua"
                         style="max-height: 300px;" loading="lazy">
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
