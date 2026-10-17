{{-- Styles communs aux pages de la boutique --}}
@push('styles')
<style>
    .shop-card {
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .shop-card:hover,
    .shop-card:focus-within {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(47, 128, 184, 0.18) !important;
    }

    .shop-card-image {
        position: relative;
        display: block;
        aspect-ratio: 1 / 1;
        background: #f6f8fa;
    }

    .shop-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .shop-card-placeholder {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        color: #2f80b8;
        opacity: 0.3;
    }

    .shop-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        padding: 0.25rem 0.6rem;
        border-radius: 15px 0 15px 0;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .shop-price {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1c2111;
    }

    .shop-order-btn,
    .shop-order-btn i {
        background-color: #2f80b8;
        border-color: #2f80b8;
        color: #fff !important;
    }

    .shop-order-btn:hover,
    .shop-order-btn:focus {
        background-color: #24668f;
        border-color: #24668f;
    }

    .shop-category-title {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 15px 0 15px 0;
        background-image: linear-gradient(129deg, #f9be38 85%, #2f80b8 0);
        color: #1c2111;
        box-shadow: 0 5px 6px 4px rgba(0, 0, 0, 0.05);
    }

    .shop-filter .nav-link {
        border-radius: 999px;
        color: #2f80b8;
    }

    .shop-filter .nav-link.active {
        background: #2f80b8;
        color: #fff;
    }

    @media (prefers-reduced-motion: reduce) {
        .shop-card { transition: none; }
        .shop-card:hover,
        .shop-card:focus-within { transform: none; }
    }
</style>
@endpush
