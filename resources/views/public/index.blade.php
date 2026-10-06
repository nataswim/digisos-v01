@extends('layouts.public')

@section('title', 'Vie du club — actualités')
@section('meta_description', 'Toutes les actualités du CNBB, club de natation de Bressuire : résultats de compétitions, événements, stages, informations pratiques et vie du club.')

@section('content')

@php
    // Filtres actifs (les variables peuvent ne pas être transmises par le contrôleur)
    $activeSearch   = $search ?? '';
    $activeCategory = $category ?? '';
    $activeTag      = $tag ?? '';
    $hasFilters     = $activeSearch || $activeCategory || $activeTag;

    $activeCategoryName = $activeCategory
        ? ($categories->firstWhere('slug', $activeCategory)?->name ?? $activeCategory)
        : null;

    $activeTagName = ($activeTag && isset($tags))
        ? ($tags->firstWhere('slug', $activeTag)?->name ?? $activeTag)
        : $activeTag;
@endphp


<x-public.hero
    title="Vie du club"
    eyebrow="Actualités"
    lead="Résultats de compétitions, événements, stages et informations pratiques : suivez la saison du Cercle des Nageurs du Bocage Bressuirais." />


<!-- Filtres et recherche -->
<section class="py-4 bg-white">
    <div class="container-lg">
        <form method="GET" action="{{ route('posts.public.index') }}" class="row g-3 align-items-center" role="search" aria-label="Filtrer les actualités">
            <div class="col-md-4">
                <label for="filtre-recherche" class="visually-hidden">Rechercher dans les articles</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search text-muted" aria-hidden="true"></i>
                    </span>
                    <input type="search"
                           id="filtre-recherche"
                           name="search"
                           value="{{ $activeSearch }}"
                           class="form-control"
                           placeholder="Rechercher dans les articles…">
                </div>
            </div>

            <div class="col-md-3">
                <label for="filtre-categorie" class="visually-hidden">Catégorie</label>
                <select id="filtre-categorie" name="category" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected($activeCategory === $cat->slug)>
                            {{ $cat->name }} ({{ $cat->posts_count ?? 0 }})
                        </option>
                    @endforeach
                </select>
            </div>

            @if (isset($tags) && $tags->count() > 0)
                <div class="col-md-2">
                    <label for="filtre-tag" class="visually-hidden">Mot-clé</label>
                    <select id="filtre-tag" name="tag" class="form-select">
                        <option value="">Tous les mots-clés</option>
                        @foreach ($tags as $tagItem)
                            <option value="{{ $tagItem->slug }}" @selected($activeTag === $tagItem->slug)>
                                {{ $tagItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary text-white flex-fill">
                        <i class="fas fa-filter me-2" aria-hidden="true"></i>Filtrer
                    </button>
                    @if ($hasFilters)
                        <a href="{{ route('posts.public.index') }}" class="btn btn-outline-secondary" title="Effacer les filtres">
                            <i class="fas fa-times" aria-hidden="true"></i>
                            <span class="visually-hidden">Effacer les filtres</span>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>


<!-- Articles -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <h2 class="visually-hidden">Liste des articles</h2>

        @if ($posts->count() > 0)
            {{-- Rappel des filtres --}}
            @if ($hasFilters)
                <div class="alert alert-info border-0 mb-4" role="status">
                    <i class="fas fa-water me-2" aria-hidden="true"></i>
                    {{ $posts->total() }} {{ $posts->total() > 1 ? 'résultats trouvés' : 'résultat trouvé' }}
                    @if ($activeSearch)
                        pour « <strong>{{ $activeSearch }}</strong> »
                    @endif
                    @if ($activeCategoryName)
                        dans la catégorie « <strong>{{ $activeCategoryName }}</strong> »
                    @endif
                    @if ($activeTagName)
                        avec le mot-clé « <strong>{{ $activeTagName }}</strong> »
                    @endif
                </div>
            @endif

            <div class="row g-4">
                @foreach ($posts as $post)
                    <div class="col-lg-4 col-md-6">
                        <x-public.post-card :post="$post" />
                    </div>
                @endforeach
            </div>

            @if ($posts->hasPages())
                <div class="mt-5">
                    {{ $posts->appends(request()->query())->links('pagination.five-per-row') }}
                </div>
            @endif
        @else
            {{-- État vide --}}
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                     style="width: 120px; height: 120px;">
                    <i class="fas fa-search text-muted fa-3x" aria-hidden="true"></i>
                </div>
                <p class="h3 fw-bold mb-3">Aucun article trouvé</p>
                <p class="text-muted mb-4">
                    @if ($hasFilters)
                        Aucun article ne correspond à vos critères. Essayez un autre mot ou retirez un filtre.
                    @else
                        Les premières actualités du club arrivent bientôt.
                    @endif
                </p>
                @if ($hasFilters)
                    <a href="{{ route('posts.public.index') }}" class="btn btn-primary text-white">
                        <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Voir tous les articles
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>


<!-- Catégories -->
@if ($categories->count() > 0)
    <section class="py-5 bg-secondary">
        <div class="container-lg">
            <h2 class="h3 fw-bold text-white text-center mb-4">Parcourir par catégorie</h2>

            <div class="row g-4">
                {{-- $cat et non $category : $category contient déjà le filtre choisi --}}
                @foreach ($categories as $cat)
                    <div class="col-md-6 col-lg-4">
                        <x-public.category-card :category="$cat" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
