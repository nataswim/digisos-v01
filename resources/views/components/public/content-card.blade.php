{{--
    Carte d'une fiche (Infos pratiques) ou d'une page (Le Club).

    Exemple :
        <x-public.content-card :item="$fiche" :url="route('public.fiches.show', [...])" meta="12 lectures">
            <span class="badge badge-primary">Catégorie</span>
        </x-public.content-card>

    Paramètres :
        item         Le modèle (title, image, short_description, visibility, is_featured)
        url          Lien vers le contenu ; sans lien, le bouton est remplacé par la mention « unavailable »
        label        Texte du bouton (« Lire » par défaut)
        meta         Texte affiché en bas à gauche (nombre de lectures, etc.)
        unavailable  Mention affichée quand il n'y a pas de lien
        excerptLimit Longueur de l'extrait

    Contenu par défaut : badges supplémentaires, affichés avant « En vedette » et « Adhérents ».

    Styles : resources/scss/layout/_public.scss
--}}
@props([
    'item',
    'url'          => null,
    'label'        => 'Lire',
    'meta'         => null,
    'unavailable'  => 'Bientôt disponible',
    'excerptLimit' => 120,
])

@php
    // Extrait en texte brut : on retire le HTML de l'éditeur puis on laisse Blade échapper le résultat
    $excerpt = $item->short_description
        ? Str::limit(trim(html_entity_decode(strip_tags($item->short_description), ENT_QUOTES | ENT_HTML5, 'UTF-8')), $excerptLimit)
        : null;

    $membersOnly = $item->visibility === 'authenticated';
@endphp

<article {{ $attributes->class(['card-aqua content-card h-100 hover-lift']) }}>
    @if ($item->image)
        <img src="{{ $item->image }}" class="content-card-image" alt="" loading="lazy">
    @else
        <div class="content-card-placeholder">
            <i class="fas fa-file-alt fa-4x text-primary opacity-50" aria-hidden="true"></i>
        </div>
    @endif

    <div class="content-card-body">
        @if ($slot->isNotEmpty() || $item->is_featured || $membersOnly)
            <div class="d-flex flex-wrap gap-2 mb-3">
                {{ $slot }}

                @if ($item->is_featured)
                    <span class="badge badge-warning">
                        <i class="fas fa-star me-1" aria-hidden="true"></i>En vedette
                    </span>
                @endif

                @if ($membersOnly)
                    <span class="badge badge-info">
                        <i class="fas fa-lock me-1" aria-hidden="true"></i>Adhérents
                    </span>
                @endif
            </div>
        @endif

        <h3 class="h5 mb-3">
            @if ($url)
                <a href="{{ $url }}" class="text-decoration-none text-dark hover-primary">{{ $item->title }}</a>
            @else
                {{ $item->title }}
            @endif
        </h3>

        @if ($excerpt)
            <p class="text-muted flex-grow-1">{{ $excerpt }}</p>
        @else
            <div class="flex-grow-1"></div>
        @endif

        <div class="d-flex align-items-center justify-content-between gap-2 mt-3 pt-3 border-top">
            <small class="text-muted">{{ $meta }}</small>

            @if ($url)
                <a href="{{ $url }}" class="btn btn-sm btn-primary text-white" aria-label="{{ $label }} : {{ $item->title }}">
                    {{ $label }} <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                </a>
            @else
                <span class="badge bg-secondary">
                    <i class="fas fa-clock me-1" aria-hidden="true"></i>{{ $unavailable }}
                </span>
            @endif
        </div>
    </div>
</article>
