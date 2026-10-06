{{--
    Ligne de catégorie : image, nom, description et bouton.
    Utilisée par les index « Infos pratiques » (fiches) et « Le Club » (pages).

    Exemple :
        <x-public.category-row :category="$category"
                               :url="route('public.fiches.category', $category)"
                               :count="$category->published_fiches_count"
                               singular="fiche" plural="fiches" />

    Paramètres :
        category   Le modèle (name, image, description)
        url        Lien vers la catégorie
        count      Nombre de contenus publiés
        singular / plural   Nom du contenu au singulier et au pluriel
        fallback   Texte affiché quand la catégorie n'a pas de description
        limit      Longueur maximale de la description

    Styles : resources/scss/layout/_public.scss
--}}
@props([
    'category',
    'url',
    'count'    => null,
    'singular' => 'contenu',
    'plural'   => 'contenus',
    'fallback' => null,
    'limit'    => 250,
])

@php
    // La description peut contenir du HTML (éditeur). Couper du HTML au milieu d'une balise
    // casse la page : si elle est courte on l'affiche telle quelle, sinon on en tire un extrait en texte brut.
    $plain     = trim(html_entity_decode(strip_tags((string) $category->description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $isShort   = mb_strlen($plain) <= $limit;
    $hasMarkup = $category->description !== strip_tags((string) $category->description);
@endphp

<article {{ $attributes->class(['card-aqua category-row hover-lift']) }}>
    <div class="row g-0 align-items-stretch">
        <div class="col-12 col-md-3">
            <div class="category-row-media">
                @if ($category->image)
                    <img src="{{ $category->image }}" class="category-row-image" alt="" loading="lazy">
                @else
                    <div class="category-row-placeholder">
                        <i class="fas fa-folder" aria-hidden="true"></i>
                    </div>
                @endif

                @if (! is_null($count))
                    <span class="category-row-count badge badge-success shadow-sm fs-6">
                        {{ $count }} {{ $count > 1 ? $plural : $singular }}
                    </span>
                @endif
            </div>
        </div>

        <div class="col-12 col-md-7">
            <div class="p-4">
                <h3 class="h4 mb-3">
                    <a href="{{ $url }}" class="text-decoration-none text-dark hover-primary">{{ $category->name }}</a>
                </h3>

                @if ($plain === '')
                    <p class="text-muted mb-0">{{ $fallback }}</p>
                @elseif ($isShort && $hasMarkup)
                    <div class="category-row-text text-muted">{!! $category->description !!}</div>
                @else
                    <p class="category-row-text text-muted mb-0">{{ Str::limit($plain, $limit) }}</p>
                @endif
            </div>
        </div>

        <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
            <div class="p-3 w-100">
                <a href="{{ $url }}" class="btn btn-primary w-100 text-white" aria-label="Découvrir : {{ $category->name }}">
                    <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Découvrir
                </a>
            </div>
        </div>
    </div>
</article>
