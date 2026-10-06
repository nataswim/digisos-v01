@extends('layouts.public')

@section('title', $category->meta_title ?: $category->name . ' — Vie du club')
@section('meta_description', $category->meta_description ?: ($category->description ? Str::limit($category->description, 160) : 'Les articles de la catégorie ' . $category->name . ' sur le site du CNBB, club de natation de Bressuire.'))
@section('meta_keywords', $category->meta_keywords ?: '')

@section('content')

<!-- En-tête de la catégorie -->
<section class="nataswim-titre1 position-relative text-white">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg">
                <nav aria-label="Fil d'Ariane">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('posts.public.index') }}">Vie du club</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                    </ol>
                </nav>

                <h1 class="text-white display-5 fw-bold mb-3">{{ $category->name }}</h1>

                @if ($category->description)
                    <p class="lead mb-0">{{ $category->description }}</p>
                @endif

                <p class="page-meta mt-3 mb-0">
                    <i class="fas fa-file-alt me-1" aria-hidden="true"></i>{{ $posts->total() }} {{ $posts->total() > 1 ? 'articles' : 'article' }}
                    @if ($category->group_name)
                        <span class="badge bg-warning text-dark ms-2">
                            <i class="fas fa-layer-group me-1" aria-hidden="true"></i>{{ $category->group_name }}
                        </span>
                    @endif
                </p>
            </div>

            @if ($category->image)
                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                    <img src="{{ $category->image }}"
                         alt=""
                         class="img-fluid rounded shadow"
                         style="max-height: 200px; object-fit: cover;">
                </div>
            @endif
        </div>
    </div>
</section>


<!-- Liste des articles -->
<section class="category-posts py-5">
    <div class="container-lg">
        <h2 class="visually-hidden">Articles de la catégorie {{ $category->name }}</h2>

        @if ($posts->count() > 0)
            <div class="row g-4">
                @foreach ($posts as $post)
                    <div class="col-md-6 col-lg-4">
                        <x-public.post-card :post="$post" :intro-limit="100" />
                    </div>
                @endforeach
            </div>

            @if ($posts->hasPages())
                <div class="mt-5">
                    {{ $posts->links('pagination.five-per-row') }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h3 text-muted">Aucun article dans cette catégorie pour le moment</p>
                <p class="text-muted mb-4">Revenez bientôt, ou parcourez les autres actualités du club.</p>
            </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Toute la vie du club
            </a>
        </div>
    </div>
</section>

@endsection


@push('styles')
<style>
    /* Fond de la liste d'articles */
    .category-posts {
        background-image: linear-gradient(229deg, #f9f5f4 85%, #ffffff 0);
        background-attachment: fixed;
        background-position: top;
    }
</style>
@endpush
