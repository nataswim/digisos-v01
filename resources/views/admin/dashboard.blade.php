@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid dashboard-modules">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h1 class="h3 fw-bold mb-0">Tableau de bord</h1>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
            <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Voir le site
        </a>
    </div>

    @foreach ($groups as $group)
        <section class="mb-5" aria-labelledby="rubrique-{{ $loop->index }}">
            <h2 id="rubrique-{{ $loop->index }}" class="h5 fw-bold mb-3 dashboard-group-title">
                <i class="fas {{ $group['icon'] }} me-2" aria-hidden="true"></i>{{ $group['title'] }}
            </h2>

            <ul class="row g-3 list-unstyled mb-0">
                @foreach ($group['cards'] as $card)
                    <li class="col-6 col-md-4 col-lg-3 col-xl-2">
                        <a href="{{ $card['url'] }}" class="dashboard-card card border-0 shadow-sm h-100 text-decoration-none">
                            <span class="card-body p-3 d-flex flex-column align-items-center text-center">
                                <span class="dashboard-card-icon mb-2" aria-hidden="true">
                                    <i class="fas {{ $card['icon'] }}"></i>
                                </span>
                                <span class="dashboard-card-title fw-bold">{{ $card['label'] }}</span>
                                @if ($card['count_label'])
                                    <span class="dashboard-card-count small text-muted mt-1">{{ $card['count_label'] }}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endforeach
</div>
@endsection

@push('styles')
<style>
    .dashboard-group-title {
        display: inline-block;
        padding: 0.4rem 1.1rem;
        border-radius: 15px 0 15px 0;
        background-image: linear-gradient(129deg, #f9be38 85%, #2f80b8 0);
        color: #1c2111;
        box-shadow: 0 5px 6px 4px rgba(0, 0, 0, 0.05);
    }

    .dashboard-card {
        border-radius: 15px;
        color: #1c2111;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-card:hover,
    .dashboard-card:focus {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(47, 128, 184, 0.18) !important;
        color: #1c2111;
    }

    .dashboard-card:focus-visible {
        outline: 3px solid #2f80b8;
        outline-offset: 2px;
    }

    .dashboard-card-icon {
        display: inline-flex;
        width: 56px;
        height: 56px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e8f2f9;
        color: #2f80b8;
        font-size: 1.4rem;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dashboard-card:hover .dashboard-card-icon,
    .dashboard-card:focus .dashboard-card-icon {
        background: #2f80b8;
        color: #fff;
    }

    .dashboard-card-title {
        line-height: 1.25;
    }

    @media (prefers-reduced-motion: reduce) {
        .dashboard-card, .dashboard-card-icon { transition: none; }
        .dashboard-card:hover, .dashboard-card:focus { transform: none; }
    }
</style>
@endpush
