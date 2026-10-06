@extends('layouts.public')

@section('title', 'Le Club — informations pour votre saison')
@section('meta_description', 'Toutes les informations du CNBB, club de natation de Bressuire, pour bien vivre votre saison : fonctionnement du club, groupes, règles de vie, démarches.')

@section('content')

<x-public.hero
    title="Le Club"
    eyebrow="Informations du club"
    video="assets/images/team/CNBB-natation-3.mp4"
    lead="Retrouvez ici toutes les informations pratiques nécessaires au bon déroulement de votre saison au club. Que vous soyez nouvel adhérent ou nageur confirmé, cette rubrique rassemble les éléments essentiels pour vous guider.">
    <a href="{{ route('about') }}" class="btn btn-light btn-lg">
        <i class="fas fa-water me-2" aria-hidden="true"></i>Présentation et historique
    </a>
    <a href="{{ route('pricing') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>S'inscrire au club
    </a>
</x-public.hero>


<!-- Catégories -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <h2 class="title-aqua-secondary mb-5">Les informations, par thème</h2>

        @if ($categories->count() > 0)
            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-12 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <x-public.category-row
                            :category="$category"
                            :url="route('public.pages.category', $category)"
                            :count="$category->published_pages_count"
                            singular="page"
                            plural="pages"
                            :fallback="'Les pages de la catégorie ' . $category->name . '.'" />
                    </div>
                @endforeach
            </div>
        @else
            <div class="card-aqua text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-0">Les premières pages arrivent bientôt.</p>
            </div>
        @endif

        <p class="text-center text-muted mt-5 mb-0">
            Une question n'est pas traitée ici ? L'équipe du club reste à votre disposition :
            <a href="{{ route('contact') }}">écrivez-nous</a>.
        </p>
    </div>
</section>

@endsection
