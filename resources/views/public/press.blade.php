@extends('layouts.public')

@section('title', 'Revue de presse')
@section('meta_description', 'Le CNBB dans la presse : les articles de journaux consacrés au Cercle des Nageurs du Bocage Bressuirais, à ses nageurs et à ses événements.')

@section('content')

@php
    // Les articles de presse sont des articles du site rangés dans la catégorie « Presse »
    // (voir PublicController::press). $category vaut null tant que cette catégorie n'existe pas.
    $total = $posts->total();

    $canPublish = auth()->check()
        && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor'));
@endphp


<x-public.hero
    title="Revue de presse"
    eyebrow="Médias"
    lead="Le CNBB dans les journaux : retrouvez ici les articles consacrés au club, à ses nageurs et à ses événements.">
    <a href="{{ route('public.videos.index') }}" class="btn btn-light btn-lg">
        <i class="fas fa-play-circle me-2" aria-hidden="true"></i>Au fil de l'eau
    </a>
    <a href="{{ route('galleries.index') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-images me-2" aria-hidden="true"></i>Galeries photo
    </a>
</x-public.hero>


<!-- Articles de presse -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <h2 class="title-aqua-secondary mb-4">
            <i class="fas fa-newspaper me-2" aria-hidden="true"></i>Ils parlent du club
        </h2>

        @if ($posts->count() > 0)
            <p class="text-muted mb-4">
                {{ $total }} {{ $total > 1 ? 'articles' : 'article' }}, du plus récent au plus ancien.
            </p>

            <div class="row g-4">
                @foreach ($posts as $post)
                    <div class="col-md-6 col-lg-4">
                        <x-public.post-card :post="$post" />
                    </div>
                @endforeach
            </div>

            @if ($posts->hasPages())
                <div class="mt-5">
                    {{ $posts->links('pagination.five-per-row') }}
                </div>
            @endif
        @else
            <div class="card-aqua text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-2">Les premiers articles de presse arrivent bientôt.</p>
                <p class="text-muted mb-0">
                    En attendant, suivez la <a href="{{ route('posts.public.index') }}">vie du club</a>.
                </p>
            </div>

            {{-- Aide affichée uniquement aux administrateurs et aux rédacteurs --}}
            @if ($canPublish)
                <div class="alert alert-info border-0 shadow-sm mt-4 mb-0" role="note">
                    <strong><i class="fas fa-info-circle me-2" aria-hidden="true"></i>Pour alimenter cette page</strong>
                    <ol class="mb-0 mt-2">
                        @if (! $category)
                            <li>Dans l'administration, créez une catégorie d'articles nommée <strong>Presse</strong> (son identifiant doit être <code>presse</code>) et activez-la.</li>
                        @endif
                        <li>Publiez un article dans la catégorie « Presse » : titre de l'article de journal, nom du journal et date dans l'introduction, photo ou scan en image.</li>
                        <li>Il apparaît ici dès sa publication.</li>
                    </ol>
                </div>
            @endif
        @endif
    </div>
</section>


<!-- Contact presse -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5 text-center">
                        <i class="fas fa-microphone-alt text-primary fa-3x mb-3" aria-hidden="true"></i>
                        <h2 class="h3 fw-bold mb-3">Vous êtes journaliste ?</h2>
                        <p class="text-muted mb-4">
                            Pour un reportage, une interview ou des résultats de compétition, contactez le club.
                        </p>
                        <p class="mb-4">
                            <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>
                            <span class="mx-2" aria-hidden="true">·</span>
                            <a href="tel:+33602350843">06 02 35 08 43</a>
                        </p>
                        <a href="{{ route('contact') }}" class="btn btn-primary text-white">
                            <i class="fas fa-envelope me-2" aria-hidden="true"></i>Contacter le club
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
