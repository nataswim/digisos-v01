{{--
    Carte d'un produit de la boutique.
    Variables : $product (avec sa relation category chargée), $category (catégorie du produit)
--}}
@php
    $url = route('shop.show', [$category, $product]);
@endphp

<article class="shop-card card border-0 shadow-sm h-100">
    <a href="{{ $url }}" class="shop-card-image" tabindex="-1" aria-hidden="true">
        @if ($product->image)
            <img src="{{ $product->image }}" alt="" loading="lazy">
        @else
            <span class="shop-card-placeholder"><i class="fas fa-shopping-bag fa-3x"></i></span>
        @endif

        @if ($product->badge_info)
            <span class="shop-badge" style="background: {{ $product->badge_info['bg'] }}; color: {{ $product->badge_info['text'] }};">
                {{ $product->badge_info['label'] }}
            </span>
        @endif
    </a>

    <div class="card-body p-3 d-flex flex-column">
        <h3 class="h6 fw-bold mb-1">
            <a href="{{ $url }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
        </h3>

        @if ($product->summary)
            <p class="small text-muted mb-2">{{ $product->summary }}</p>
        @endif

        <div class="mt-auto">
            @if ($product->price_label)
                <p class="shop-price mb-2">
                    {{ $product->price_label }}
                    @if ($product->price_note)<small class="text-muted fw-normal d-block">{{ $product->price_note }}</small>@endif
                </p>
            @endif

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ $url }}" class="btn btn-sm btn-outline-primary">
                    Voir<span class="visually-hidden"> {{ $product->name }}</span>
                </a>
                @if ($product->order_url && ! $product->is_sold_out)
                    <a href="{{ $product->order_url }}" class="btn btn-sm btn-primary text-white shop-order-btn" target="_blank" rel="noopener">
                        <i class="fas fa-shopping-cart me-1" aria-hidden="true"></i>{{ $product->order_button_label }}
                        <span class="visually-hidden">{{ $product->name }} (site externe, nouvel onglet)</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</article>
