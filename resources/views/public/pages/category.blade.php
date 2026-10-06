@extends('layouts.public')

@php
    $descriptionText = trim(html_entity_decode(strip_tags((string) $category->description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $total = $pages->total();
@endphp

@section('title', $category->name . ' — Le Club')
@section('meta_description', $descriptionText !== '' ? Str::limit($descriptionText, 160) : 'Les informations du CNBB dans la rubrique ' . $category->name . '.')

@section('content')

<!-- En-tête -->
<section class="nataswim-titre1 position-relative text-white">
    <div class="container-lg">
        <nav aria-label="Fil d'Ariane">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.pages.index') }}">Le Club</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1 class="text-white display-5 fw-bold mb-3">{{ $category->name }}</h1>

        {{-- La description vient de l'éditeur (HTML) : on en affiche un extrait en texte brut,
             car couper du HTML au milieu d'une balise casserait la page. --}}
        @if ($descriptionText !== '')
            <p class="lead mb-0">{{ Str::limit($descriptionText, 200) }}</p>
        @endif

        <p class="page-meta mt-3 mb-0">
            <i class="fas fa-file-alt me-1" aria-hidden="true"></i>{{ $total }} {{ $total > 1 ? 'pages' : 'page' }}
        </p>
    </div>
</section>


<!-- Liste des pages -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <h2 class="visually-hidden">Pages de la rubrique {{ $category->name }}</h2>

        @if ($pages->count() > 0)
            <div class="row g-4">
                @foreach ($pages as $page)
                    <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <x-public.content-card
                            :item="$page"
                            :url="$page->canViewContent(auth()->user()) ? route('public.pages.show', [$category, $page]) : null"
                            :meta="$page->visibility === 'public' ? 'Accès libre' : 'Réservé aux adhérents'"
                            unavailable="Accès réservé" />
                    </div>
                @endforeach
            </div>

            @if ($pages->hasPages())
                <div class="mt-5">
                    {{ $pages->links('pagination.five-per-row') }}
                </div>
            @endif
        @else
            <div class="card-aqua text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-0">Aucune page dans cette rubrique pour le moment.</p>
            </div>
        @endif
    </div>
</section>


<!-- Navigation -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start">
                <a href="{{ route('public.pages.index') }}" class="btn btn-primary btn-lg text-white">
                    <i class="fas fa-th me-2" aria-hidden="true"></i>Toutes les informations du club
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
