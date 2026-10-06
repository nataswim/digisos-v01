@extends('layouts.public')

@section('title', 'Infos pratiques')
@section('meta_description', 'Les fiches d\'information du CNBB, club de natation de Bressuire : organisation des entraînements, matériel, compétitions, démarches et conseils pour les nageurs et leurs parents.')

@section('content')

<x-public.hero
    title="Infos pratiques"
    eyebrow="Les fiches du club"
    video="assets/images/team/CNBB-natation-3.mp4"
    lead="Organisation des entraînements, matériel, compétitions, démarches : retrouvez ici les fiches d'information du club, classées par thème." />




<!-- Catégories -->
<section class="py-5 {{ $featuredFiches->count() > 0 ? '' : 'bg-aqua-light' }}">
    <div class="container-lg">
        <h2 class="title-aqua-secondary mb-5">Toutes les fiches, par thème</h2>

        @if ($categories->count() > 0)
            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-12 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <x-public.category-row
                            :category="$category"
                            :url="route('public.fiches.category', $category)"
                            :count="$category->published_fiches_count"
                            singular="fiche"
                            plural="fiches"
                            :limit="180"
                            :fallback="'Les fiches de la catégorie ' . $category->name . '.'" />
                    </div>
                @endforeach
            </div>
        @else
            <div class="card-aqua text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-0">Les premières fiches arrivent bientôt.</p>
            </div>
        @endif

        <p class="text-center text-muted mt-5 mb-0">
            Vous ne trouvez pas l'information ? <a href="{{ route('contact') }}">Posez votre question au club</a>.
        </p>
    </div>
</section>

@endsection
