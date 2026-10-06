{{--
    Carte d'article, utilisée sur l'accueil, la liste des actualités,
    les pages de catégorie et la recherche.

    Exemple : <x-public.post-card :post="$post" />

    Paramètres :
        post        L'article (modèle Post)
        introLimit  Nombre de caractères de l'introduction (120 par défaut)

    Styles : resources/scss/layout/_public.scss
--}}
@props([
    'post',
    'introLimit' => 120,
])

@php
    // Introduction en texte brut : on retire le HTML de l'éditeur puis on laisse Blade échapper le résultat
    $intro = $post->intro
        ? Str::limit(trim(html_entity_decode(strip_tags($post->intro), ENT_QUOTES | ENT_HTML5, 'UTF-8')), $introLimit)
        : null;

    $date        = $post->published_at ?? $post->created_at;
    $membersOnly = $post->visibility === 'authenticated';
    $url         = route('posts.public.show', $post);
@endphp

<article {{ $attributes->class(['card-aqua h-100 d-flex flex-column']) }}>
    <div class="card-image-wrapper mb-3 position-relative">
        @if ($post->image)
            <img src="{{ $post->image }}" class="card-image" alt="" loading="lazy">
        @else
            <div class="card-image-placeholder">
                <i class="fas fa-newspaper fa-3x text-primary opacity-25" aria-hidden="true"></i>
            </div>
        @endif

        @if ($post->is_featured || $membersOnly)
            <div class="position-absolute top-0 end-0 p-3 text-end">
                @if ($post->is_featured)
                    <span class="badge badge-warning mb-2 d-block">
                        <i class="fas fa-star me-1" aria-hidden="true"></i>À la une
                    </span>
                @endif

                @if ($membersOnly)
                    <span class="badge badge-info d-block">
                        <i class="fas fa-lock me-1" aria-hidden="true"></i>Membre
                    </span>
                @endif
            </div>
        @endif

        @if ($post->reading_time)
            <div class="position-absolute bottom-0 start-0 p-3">
                <span class="badge badge-dark">
                    <i class="fas fa-clock me-1" aria-hidden="true"></i>{{ $post->reading_time }} min
                </span>
            </div>
        @endif
    </div>

    <div class="card-meta mb-2">
        <span class="badge badge-primary">{{ $post->category->name ?? 'Actualité' }}</span>
    </div>

    <h3 class="card-title h6 mb-2">
        <a href="{{ $url }}" class="text-decoration-none text-dark hover-primary">
            {{ $post->name }}
        </a>
    </h3>

    @if ($intro)
        <p class="card-text text-muted small mb-3">{{ $intro }}</p>
    @endif

    <div class="card-footer-info mt-auto">
        <small class="text-muted">
            <i class="fas fa-eye me-1" aria-hidden="true"></i>{{ number_format($post->hits ?? 0, 0, ',', ' ') }}
            <span class="visually-hidden">vues</span>
        </small>

        @if ($membersOnly && auth()->guest())
            <small class="text-warning">
                <i class="fas fa-lock me-1" aria-hidden="true"></i>Connexion requise
            </small>
        @elseif ($date)
            <small class="text-muted">
                <time datetime="{{ $date->format('Y-m-d') }}">{{ $date->format('d/m/Y') }}</time>
            </small>
        @endif
    </div>
</article>
