{{--
    Bandeau défilant de la boutique, juste avant le pied de page de toutes les pages publiques
    (sauf les pages de la boutique, qui montrent déjà ces produits).
    Produits affichés : badge « Nouveau » ou case « À la une ».
--}}
@php
    $produitsDefilants = collect();

    if (Route::has('shop.index') && ! request()->routeIs('shop.*')) {
        try {
            $produitsDefilants = App\Models\ShopProduct::visible()
                ->with('category')
                ->where(fn ($q) => $q->where('is_featured', true)->orWhere('badge', 'nouveau'))
                ->ordered()
                ->limit(20)
                ->get();
        } catch (\Throwable $e) {
            // Module boutique pas encore installé (table absente) : pas de bandeau
            $produitsDefilants = collect();
        }
    }

    // Assez de cartes pour remplir la largeur de l'écran, même avec 1 ou 2 produits
    $repetitions = $produitsDefilants->isEmpty() ? 0 : (int) max(1, ceil(8 / $produitsDefilants->count()));
    $duree = max(20, $produitsDefilants->count() * $repetitions * 4);   // secondes pour un tour complet
@endphp

@if ($produitsDefilants->isNotEmpty())
<section class="shop-ticker py-5" aria-labelledby="shop-ticker-title">
    <div class="container-lg">
        <div class="shop-ticker-head mb-4">
            <h2 id="shop-ticker-title" class="h4 fw-bold mb-0 shop-ticker-title">
                <i class="fas fa-shopping-bag me-2" aria-hidden="true"></i>À découvrir à la boutique
            </h2>
            <div class="shop-ticker-actions">
                <button type="button" class="btn btn-sm btn-outline-secondary shop-ticker-toggle" aria-pressed="false">
                    <i class="fas fa-pause" aria-hidden="true"></i>
                    <span class="shop-ticker-toggle-label">Mettre en pause</span>
                </button>
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-primary text-white">
                    Toute la boutique<i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="shop-ticker-viewport">
        <div class="shop-ticker-track" style="--shop-ticker-duration: {{ $duree }}s;">
            {{-- Deux copies identiques : la seconde prend le relais pour une boucle sans à-coup --}}
            @foreach ([false, true] as $copie)
                <ul class="shop-ticker-list list-unstyled" @if ($copie) aria-hidden="true" @endif>
                    @for ($r = 0; $r < $repetitions; $r++)
                        @foreach ($produitsDefilants as $produit)
                            @php $doublon = $copie || $r > 0; @endphp
                            <li>
                                <a href="{{ route('shop.show', [$produit->category, $produit]) }}" class="shop-ticker-item"
                                   @if ($doublon) tabindex="-1" @endif>
                                    <span class="shop-ticker-image">
                                        @if ($produit->image)
                                            <img src="{{ $produit->image }}" alt="" loading="lazy">
                                        @else
                                            <i class="fas fa-shopping-bag fa-2x" aria-hidden="true"></i>
                                        @endif
                                        @if ($produit->badge === 'nouveau')
                                            <span class="shop-ticker-badge" style="background:#198754;color:#fff;">Nouveau</span>
                                        @elseif ($produit->is_featured)
                                            <span class="shop-ticker-badge" style="background:#f9be38;color:#1c2111;">
                                                <i class="fas fa-star" aria-hidden="true"></i> À la une
                                            </span>
                                        @endif
                                    </span>
                                    <span class="shop-ticker-name">{{ $produit->name }}</span>
                                    @if ($produit->price_label)
                                        <span class="shop-ticker-price">{{ $produit->price_label }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    @endfor
                </ul>
            @endforeach
        </div>
    </div>

<style>
    .shop-ticker {
        background: #f6f8fa;
        border-top: 1px solid #e9ecef;
        overflow: hidden;
        flex-shrink: 0;   /* le <body> du site est en flex : sans ceci le bandeau serait écrasé */
    }

    .shop-ticker-head {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }

    .shop-ticker-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    .shop-ticker-title {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 15px 0 15px 0;
        background-image: linear-gradient(129deg, #f9be38 85%, #2f80b8 0);
        color: #1c2111;
        box-shadow: 0 5px 6px 4px rgba(0, 0, 0, 0.05);
    }

    .shop-ticker-viewport {
        overflow: hidden;
        -webkit-mask-image: linear-gradient(90deg, transparent, #000 4%, #000 96%, transparent);
                mask-image: linear-gradient(90deg, transparent, #000 4%, #000 96%, transparent);
    }

    .shop-ticker-track {
        display: flex;
        width: max-content;
        animation: shop-ticker-scroll var(--shop-ticker-duration, 40s) linear infinite;
    }

    .shop-ticker-viewport:hover .shop-ticker-track,
    .shop-ticker-viewport:focus-within .shop-ticker-track,
    .shop-ticker.is-paused .shop-ticker-track {
        animation-play-state: paused;
    }

    @keyframes shop-ticker-scroll {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    .shop-ticker-list {
        display: flex;
        gap: 1.25rem;
        margin: 0;
        padding: 0.5rem 0.625rem 0.75rem;
    }

    .shop-ticker-item {
        display: flex;
        flex-direction: column;
        width: 180px;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        color: #1c2111;
        text-decoration: none;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .shop-ticker-item:hover,
    .shop-ticker-item:focus {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(47, 128, 184, 0.18);
        color: #1c2111;
    }

    .shop-ticker-item:focus-visible {
        outline: 3px solid #2f80b8;
        outline-offset: 2px;
    }

    .shop-ticker-image {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        aspect-ratio: 1 / 1;
        background: #f6f8fa;
        color: rgba(47, 128, 184, 0.35);
    }

    .shop-ticker-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .shop-ticker-badge {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        padding: 0.2rem 0.5rem;
        border-radius: 12px 0 12px 0;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .shop-ticker-name {
        padding: 0.75rem 0.75rem 0.25rem;
        font-weight: 700;
        font-size: 0.95rem;
        line-height: 1.3;
    }

    .shop-ticker-price {
        padding: 0 0.75rem 0.75rem;
        font-weight: 700;
        color: #2f80b8;
    }

    @media (max-width: 575.98px) {
        .shop-ticker-item { width: 150px; }
    }

    /* Mouvement réduit : pas de défilement automatique, la rangée se fait défiler à la main */
    @media (prefers-reduced-motion: reduce) {
        .shop-ticker-track { animation: none; }
        .shop-ticker-viewport { overflow-x: auto; -webkit-mask-image: none; mask-image: none; }
        .shop-ticker-list[aria-hidden="true"] { display: none; }
        .shop-ticker-toggle { display: none; }
        .shop-ticker-item { transition: none; }
        .shop-ticker-item:hover, .shop-ticker-item:focus { transform: none; }
    }
</style>
</section>

@push('scripts')
<script>
    // Bouton pause / lecture du bandeau de la boutique
    (function () {
        var bandeau = document.querySelector('.shop-ticker');
        var bouton = bandeau && bandeau.querySelector('.shop-ticker-toggle');
        if (!bouton) return;

        bouton.addEventListener('click', function () {
            var enPause = bandeau.classList.toggle('is-paused');
            bouton.setAttribute('aria-pressed', enPause ? 'true' : 'false');
            bouton.querySelector('i').className = enPause ? 'fas fa-play' : 'fas fa-pause';
            bouton.querySelector('.shop-ticker-toggle-label').textContent = enPause ? 'Reprendre le défilement' : 'Mettre en pause';
        });
    })();
</script>
@endpush
@endif
