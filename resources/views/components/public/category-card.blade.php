{{--
    Carte de catégorie d'articles.

    Exemple : <x-public.category-card :category="$cat" />

    Styles : resources/scss/layout/_public.scss
--}}
@props(['category'])

@php
    $count = $category->posts_count ?? null;
@endphp

<article {{ $attributes->class(['card-aqua h-100 d-flex flex-column']) }}>
    <div class="card-image-wrapper mb-3 position-relative">
        @if ($category->image)
            <img src="{{ $category->image }}" class="card-image" alt="" loading="lazy">
        @else
            <div class="card-image-placeholder">
                <i class="fas fa-folder fa-3x text-secondary opacity-25" aria-hidden="true"></i>
            </div>
        @endif

        @if (! is_null($count))
            <div class="position-absolute top-0 end-0 p-3">
                <span class="badge badge-primary">
                    <i class="fas fa-file-alt me-1" aria-hidden="true"></i>
                    {{ $count }} {{ $count > 1 ? 'articles' : 'article' }}
                </span>
            </div>
        @endif
    </div>

    <h3 class="card-title h6 mb-2">
        <a href="{{ route('posts.public.category', $category) }}" class="text-decoration-none text-dark hover-primary">
            {{ $category->name }}
        </a>
    </h3>

    @if ($category->description)
        <p class="card-text text-muted small mb-3">{{ Str::limit($category->description, 120) }}</p>
    @endif

    @if ($category->group_name)
        <div class="card-meta mt-auto">
            <span class="badge badge-secondary">
                <i class="fas fa-layer-group me-1" aria-hidden="true"></i>{{ $category->group_name }}
            </span>
        </div>
    @endif
</article>

