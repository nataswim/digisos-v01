@extends('layouts.public')

@php
    // Résumé en texte brut pour les balises de partage et les moteurs de recherche
    $plainSummary = trim(html_entity_decode(strip_tags($post->intro ?: $post->content), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $publishedAt  = $post->published_at ?? $post->created_at;
@endphp

@section('title', $post->meta_title ?: $post->name)
@section('meta_description', $post->meta_description ?: Str::limit($plainSummary, 160))
@section('meta_keywords', $post->meta_keywords ?: '')

@section('og_type', 'article')
@section('og_title', $post->name)
@section('og_description', Str::limit($plainSummary, 200))
@section('og_url', route('posts.public.show', $post))
@if($post->image)
@section('og_image', $post->image)
@section('og_image_alt', $post->name)
@endif

@section('twitter_title', $post->name)
@section('twitter_description', Str::limit($plainSummary, 200))
@if($post->image)
@section('twitter_image', $post->image)
@section('twitter_image_alt', $post->name)
@endif

@section('content')

@php
    // Données structurées : aident les moteurs de recherche à présenter l'article
    $structuredData = array_filter([
        '@context'         => 'https://schema.org',
        '@type'            => 'NewsArticle',
        'headline'         => Str::limit($post->name, 110, ''),
        'description'      => Str::limit($plainSummary, 200),
        'image'            => $post->image ? [url($post->image)] : null,
        'datePublished'    => $publishedAt?->toIso8601String(),
        'dateModified'     => ($post->updated_at ?? $publishedAt)?->toIso8601String(),
        'mainEntityOfPage' => route('posts.public.show', $post),
        'publisher'        => [
            '@type' => 'SportsOrganization',
            'name'  => 'Cercle des Nageurs du Bocage Bressuirais',
            'url'   => route('home'),
        ],
    ]);
@endphp

<!-- En-tête de l'article -->
<section class="nataswim-titre1 position-relative text-white">
    <div class="container-lg">
        <div class="row align-items-center" style="min-height: 250px;">
            <div class="col-lg-10 mx-auto">
                <nav aria-label="Fil d'Ariane" class="animate-slide-up">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('posts.public.index') }}">Vie du club</a></li>
                        @if ($post->category)
                            <li class="breadcrumb-item">
                                <a href="{{ route('posts.public.category', $post->category) }}">{{ $post->category->name }}</a>
                            </li>
                        @endif
                    </ol>
                </nav>

                <h1 class="display-3 fw-bold mb-3 text-white animate-slide-up">{{ $post->name }}</h1>

                <p class="page-meta mb-0 animate-slide-up">
                    @if ($publishedAt)
                        <span class="me-3">
                            <i class="fas fa-calendar me-1" aria-hidden="true"></i>
                            <time datetime="{{ $publishedAt->format('Y-m-d') }}">{{ $publishedAt->locale('fr')->isoFormat('D MMMM YYYY') }}</time>
                        </span>
                    @endif
                    @if ($post->reading_time)
                        <span class="me-3">
                            <i class="fas fa-clock me-1" aria-hidden="true"></i>{{ $post->reading_time }} min de lecture
                        </span>
                    @endif
                    @if ($post->visibility === 'authenticated')
                        <span>
                            <i class="fas fa-lock me-1" aria-hidden="true"></i>Réservé aux membres
                        </span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>


<article class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg col-xl">

                @if ($post->intro)
                    <div class="card-aqua mb-4 animate-fade-in">
                        <div class="lead article-intro">{!! $post->intro !!}</div>
                    </div>
                @endif

                @if ($post->image)
                    <div class="mb-4 animate-fade-in animation-delay-1">
                        <div class="article-image-wrapper">
                            <img src="{{ $post->image }}" alt="{{ $post->name }}" class="article-image">
                        </div>
                    </div>
                @endif

                <div class="card-aqua mb-4 animate-fade-in animation-delay-2">
                    <div class="article-content">
                        @if ($contentVisible)
                            {!! $post->content !!}
                        @else
                            <div class="alert alert-info border-0 mb-4">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-lock fs-2 text-primary" aria-hidden="true"></i>
                                    <div class="flex-grow-1">
                                        <h2 class="h5 mb-2">Contenu réservé aux membres</h2>
                                        @guest
                                            <p class="mb-3">
                                                Créez votre compte gratuit ou connectez-vous pour lire la suite de cet article.
                                            </p>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm text-white">
                                                    <i class="fas fa-user-plus me-1" aria-hidden="true"></i>Créer mon compte
                                                </a>
                                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-sign-in-alt me-1" aria-hidden="true"></i>Me connecter
                                                </a>
                                            </div>
                                        @else
                                            <p class="mb-3">
                                                Votre compte ne donne pas encore accès à cet article.
                                                Si vous êtes adhérent du club, contactez-nous pour faire le point.
                                            </p>
                                            <a href="{{ route('contact') }}" class="btn btn-primary btn-sm text-white">
                                                <i class="fas fa-envelope me-1" aria-hidden="true"></i>Contacter le club
                                            </a>
                                        @endguest
                                    </div>
                                </div>
                            </div>

                            <div class="content-preview position-relative">
                                <div class="text-muted p-3 border rounded" style="max-height: 150px; overflow: hidden;">
                                    {{ Str::limit(trim(html_entity_decode(strip_tags($post->content), ENT_QUOTES | ENT_HTML5, 'UTF-8')), 500) }}
                                </div>
                                <div class="content-preview-fade position-absolute bottom-0 start-0 w-100 text-center py-2">
                                    <small class="text-muted">La suite est réservée aux membres</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($post->tags->isNotEmpty())
                    <div class="card-aqua mb-4">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="small text-muted me-1">
                                <i class="fas fa-tags me-1" aria-hidden="true"></i>Mots-clés :
                            </span>
                            @foreach ($post->tags as $tag)
                                <a href="{{ route('posts.public.tag', $tag) }}" class="badge badge-secondary text-decoration-none">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (isset($recentPosts) && $recentPosts->count() > 0)
                    <div class="card-aqua">
                        <h2 class="h5 mb-4">
                            <i class="fas fa-newspaper me-2 text-primary" aria-hidden="true"></i>Articles récents
                        </h2>

                        <div class="row g-3">
                            @foreach ($recentPosts->take(4) as $recentPost)
                                <div class="col-md-6">
                                    <div class="recent-post-item">
                                        <div class="row g-3 align-items-center">
                                            @if ($recentPost->image)
                                                <div class="col-auto">
                                                    <div class="recent-post-image-wrapper">
                                                        <img src="{{ $recentPost->image }}" class="recent-post-image" alt="" loading="lazy">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col">
                                                <h3 class="h6 mb-1">
                                                    <a href="{{ route('posts.public.show', $recentPost) }}"
                                                       class="text-decoration-none text-dark hover-primary">
                                                        {{ Str::limit($recentPost->name, 50) }}
                                                    </a>
                                                </h3>
                                                <div class="d-flex flex-wrap gap-2 small text-muted">
                                                    <span>
                                                        <i class="fas fa-calendar me-1" aria-hidden="true"></i>
                                                        {{ ($recentPost->published_at ?? $recentPost->created_at)?->format('d/m/Y') }}
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-eye me-1" aria-hidden="true"></i>{{ number_format($recentPost->hits ?? 0, 0, ',', ' ') }}
                                                    </span>
                                                    @if ($recentPost->visibility === 'authenticated')
                                                        <span class="badge badge-info badge-sm">
                                                            <i class="fas fa-lock" aria-hidden="true"></i>
                                                            <span class="visually-hidden">Réservé aux membres</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4 pt-4 border-top">
                            <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Toute la vie du club
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</article>


@if (isset($categories) && $categories->count() > 0)
    <section class="py-5 bg-secondary">
        <div class="container-lg">
            <h2 class="h3 fw-bold text-white text-center mb-4">Parcourir par catégorie</h2>

            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <x-public.category-card :category="$category" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>

@endsection


@push('styles')
<style>
/* Articles récents (les autres styles de l'article sont dans resources/scss/layout/_public.scss) */
.recent-post-item {
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid rgba(56, 133, 155, 0.1);
}

.recent-post-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.recent-post-image-wrapper {
    width: 80px;
    height: 60px;
    overflow: hidden;
    border-radius: 0.5rem;
}

.recent-post-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
@endpush
