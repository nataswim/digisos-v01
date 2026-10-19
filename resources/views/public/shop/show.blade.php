@extends('layouts.public')

@section('title', $product->name . ' — Boutique du club')
@section('meta_description', Str::limit($product->summary ?: trim(html_entity_decode(strip_tags((string) $product->description), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: $product->name . ', boutique du CNBB.', 155))
{{-- Partage sur les réseaux sociaux : photo du produit, nom, prix et accroche --}}
@php
    $imageDePartage = $product->images[0] ?? null;
    $imageDePartage = $imageDePartage ? url($imageDePartage) : null;   // adresse complète, même si l'image a été saisie en « /storage/… »
    $resumeDePartage = Str::limit(trim(implode(' — ', array_filter([
        $product->price_label,
        $product->summary ?: trim(html_entity_decode(strip_tags((string) $product->description), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
    ]))) ?: 'Boutique du Cercle des Nageurs du Bocage Bressuirais.', 200);
@endphp
@section('og_type', 'website')
@section('og_title', $product->name . ' — Boutique du CNBB')
@section('og_description', $resumeDePartage)
@section('twitter_title', $product->name . ' — Boutique du CNBB')
@section('twitter_description', $resumeDePartage)
@if ($imageDePartage)
    @section('og_image', $imageDePartage)
    @section('og_image_alt', $product->name)
    @section('twitter_image', $imageDePartage)
    @section('twitter_image_alt', $product->name)
@endif

@section('content')

@php
    $images = $product->images;
    $description = $product->description_html;
    $isAdmin = auth()->check() && auth()->user()->hasRole('admin');
@endphp

@include('public.shop._styles')

<!-- En-tête -->
<section class="nataswim-titre1 position-relative text-white">
    <div class="container-lg">
        <nav aria-label="Fil d'Ariane">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Boutique</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.category', $category) }}">{{ $category->name }}</a></li>
            </ol>
        </nav>
        <h1 class="text-white display-6 fw-bold mb-0">{{ $product->name }}</h1>
    </div>
</section>


<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row g-4 g-lg-5">
            {{-- Images --}}
            <div class="col-lg-6">
                <div class="shop-gallery-main mb-3">
                    @if (count($images))
                        <img src="{{ $images[0] }}" alt="{{ $product->name }}" id="shop-main-image">
                    @else
                        <span class="shop-card-placeholder"><i class="fas fa-shopping-bag fa-4x" aria-hidden="true"></i></span>
                    @endif

                    @if ($product->badge_info)
                        <span class="shop-badge" style="background: {{ $product->badge_info['bg'] }}; color: {{ $product->badge_info['text'] }};">
                            {{ $product->badge_info['label'] }}
                        </span>
                    @endif
                </div>

                @if (count($images) > 1)
                    <ul class="list-unstyled d-flex flex-wrap gap-2 mb-0" aria-label="Autres photos du produit">
                        @foreach ($images as $i => $src)
                            <li>
                                <button type="button" class="shop-thumb {{ $i === 0 ? 'is-active' : '' }}" data-src="{{ $src }}"
                                        aria-label="Afficher la photo {{ $i + 1 }} sur {{ count($images) }}" @if ($i === 0) aria-pressed="true" @else aria-pressed="false" @endif>
                                    <img src="{{ $src }}" alt="" loading="lazy">
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Informations et commande --}}
            <div class="col-lg-6">
                <a href="{{ route('shop.category', $category) }}" class="badge badge-primary text-decoration-none mb-3">{{ $category->name }}</a>

                @if ($product->summary)
                    <p class="lead">{{ $product->summary }}</p>
                @endif

                @if ($product->price_label)
                    <p class="shop-price display-6 mb-1">{{ $product->price_label }}</p>
                    <p class="small text-muted mb-4">
                        Prix indicatif{{ $product->price_note ? ' — ' . $product->price_note : '' }}
                    </p>
                @endif

                @if ($product->is_sold_out)
                    <div class="alert alert-secondary d-inline-block" role="status">
                        <i class="fas fa-box-open me-2" aria-hidden="true"></i>Ce produit est épuisé pour le moment.
                    </div>
                @elseif ($product->order_url)
                    <a href="{{ $product->order_url }}" class="btn btn-lg btn-primary text-white shop-order-btn" target="_blank" rel="noopener">
                        <i class="fas fa-shopping-cart me-2" aria-hidden="true"></i>{{ $product->order_button_label }}
                        <span class="visually-hidden">(site externe, nouvel onglet)</span>
                    </a>
                    <p class="small text-muted mt-2 mb-4">
                        <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>La commande se fait sur {{ $product->order_host }}.
                    </p>
                @else
                    <p class="mb-4">
                        Pour commander, <a href="{{ route('contact') }}">contactez le club</a>.
                    </p>
                @endif

                @if ($description !== '')
                    <hr class="my-4">
                    <div class="shop-description">{!! $description !!}</div>
                @endif

                @if ($isAdmin)
                    <hr>
                    <a href="{{ route('admin.shop.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit me-1" aria-hidden="true"></i>Modifier ce produit
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>


{{-- Autres produits de la catégorie --}}
@if ($others->isNotEmpty())
    <section class="py-5 bg-light">
        <div class="container-lg">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <h2 class="h4 fw-bold mb-0">Dans la même catégorie</h2>
                <a href="{{ route('shop.category', $category) }}" class="small fw-semibold">
                    Tout voir<i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                </a>
            </div>
            <div class="row g-4">
                @foreach ($others as $other)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('public.shop._card', ['product' => $other, 'category' => $category])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection


@push('styles')
<style>
    .shop-gallery-main {
        position: relative;
        aspect-ratio: 1 / 1;
        background: #f6f8fa;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .shop-gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .shop-thumb {
        width: 72px;
        height: 72px;
        padding: 0;
        border: 2px solid transparent;
        border-radius: 8px;
        overflow: hidden;
        background: #f6f8fa;
    }

    .shop-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .shop-thumb.is-active,
    .shop-thumb:focus-visible {
        border-color: #2f80b8;
        outline: none;
    }

    .shop-description {
        line-height: 1.75;
    }

    .shop-description img,
    .shop-description iframe {
        max-width: 100%;
        height: auto;
    }
</style>
@endpush

@push('scripts')
<script>
    // Miniatures : clic = la photo s'affiche en grand
    (function () {
        var grande = document.getElementById('shop-main-image');
        var miniatures = document.querySelectorAll('.shop-thumb');
        if (!grande || !miniatures.length) return;

        miniatures.forEach(function (bouton) {
            bouton.addEventListener('click', function () {
                grande.src = bouton.dataset.src;
                miniatures.forEach(function (b) {
                    b.classList.toggle('is-active', b === bouton);
                    b.setAttribute('aria-pressed', b === bouton ? 'true' : 'false');
                });
            });
        });
    })();
</script>
@endpush
