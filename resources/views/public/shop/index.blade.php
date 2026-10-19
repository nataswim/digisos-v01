@extends('layouts.public')

@section('title', $current ? $current->name . ' — Boutique du club' : 'Boutique du club')
@section('meta_description', $current?->description ?: 'La boutique du Cercle des Nageurs du Bocage Bressuirais : textile, matériel et accessoires aux couleurs du club de natation de Bressuire.')

{{-- Partage sur les réseaux sociaux : image de la catégorie, sinon photo du premier produit (mis en avant d'abord) --}}
@php
    $produitsDePartage = $categories->flatMap(fn ($c) => $c->products);
    $imageDePartage = $current?->image
        ?: ($produitsDePartage->first(fn ($p) => $p->is_featured && $p->image)?->image
        ?: $produitsDePartage->first(fn ($p) => $p->image)?->image);
    $imageDePartage = $imageDePartage ? url($imageDePartage) : null;
    $titreDePartage = $current ? $current->name . ' — Boutique du CNBB' : 'La boutique du CNBB';
    $resumeDePartage = Str::limit($current?->description ?: 'Textile, matériel et accessoires aux couleurs du Cercle des Nageurs du Bocage Bressuirais.', 200);
@endphp
@section('og_title', $titreDePartage)
@section('og_description', $resumeDePartage)
@section('twitter_title', $titreDePartage)
@section('twitter_description', $resumeDePartage)
@if ($imageDePartage)
    @section('og_image', $imageDePartage)
    @section('og_image_alt', $titreDePartage)
    @section('twitter_image', $imageDePartage)
    @section('twitter_image_alt', $titreDePartage)
@endif

@section('content')

@php
    // Liste des catégories pour le filtre (toutes, même sur la page d'une catégorie)
    $filtre = $current ? ($allCategories ?? collect()) : $categories;

    $isAdmin = auth()->check() && auth()->user()->hasRole('admin');
@endphp

@include('public.shop._styles')

<x-public.hero
    :title="$current ? $current->name : 'La boutique du club'"
    eyebrow="Le Club"
    icon="fa-shopping-bag"
    video="assets/images/team/CNBB-natation-2.mp4"
    :lead="$current?->description ?: 'Textile, matériel et accessoires aux couleurs du CNBB. Les prix sont indicatifs : la commande se fait sur le site indiqué pour chaque produit.'">
    @if ($current)
        <a href="{{ route('shop.index') }}" class="btn btn-light btn-lg">
            <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Toute la boutique
        </a>
    @endif
    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-envelope me-2" aria-hidden="true"></i>Une question ?
    </a>
</x-public.hero>


<section class="py-5 bg-white">
    <div class="container-lg">

        {{-- Filtre par catégorie --}}
        @if ($filtre->count() > 1)
            <nav aria-label="Catégories de la boutique" class="mb-5">
                <ul class="nav nav-pills shop-filter justify-content-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ $current ? '' : 'active' }}" href="{{ route('shop.index') }}" @unless ($current) aria-current="page" @endunless>
                            Tout voir
                        </a>
                    </li>
                    @foreach ($filtre as $cat)
                        <li class="nav-item">
                            <a class="nav-link {{ $current?->id === $cat->id ? 'active' : '' }}" href="{{ route('shop.category', $cat) }}"
                               @if ($current?->id === $cat->id) aria-current="page" @endif>
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif

        @forelse ($categories as $category)
            <div class="{{ $loop->last ? '' : 'mb-5 pb-2' }}">
                @unless ($current)
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                        <h2 class="h4 fw-bold mb-0 shop-category-title">{{ $category->name }}</h2>
                        <a href="{{ route('shop.category', $category) }}" class="small fw-semibold">
                            Voir la catégorie<i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    </div>
                @endunless

                @if ($category->products->isNotEmpty())
                    <div class="row g-4">
                        @foreach ($category->products as $product)
                            <div class="col-6 col-md-4 col-lg-3">
                                @include('public.shop._card', ['product' => $product, 'category' => $category])
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Aucun produit dans cette catégorie pour le moment.</p>
                @endif
            </div>
        @empty
            <div class="card-aqua text-center py-5">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3 opacity-25" aria-hidden="true"></i>
                <p class="h5 text-muted mb-0">La boutique ouvre bientôt.</p>
            </div>

            @if ($isAdmin)
                <div class="alert alert-info border-0 shadow-sm mt-4 mb-0" role="note">
                    <i class="fas fa-info-circle me-2" aria-hidden="true"></i>
                    Ajoutez une catégorie puis vos produits dans l'administration :
                    <a href="{{ route('admin.shop.products.index') }}">gérer la boutique</a>.
                </div>
            @endif
        @endforelse

        @if ($total > 0)
            <p class="small text-muted text-center mt-5 mb-0">
                <i class="fas fa-info-circle me-1" aria-hidden="true"></i>
                Prix indicatifs. La commande et le paiement se font sur le site de commande indiqué pour chaque produit, et non sur ce site.
            </p>
        @endif
    </div>
</section>

@endsection
