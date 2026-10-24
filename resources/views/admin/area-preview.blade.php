@extends('layouts.admin')

@section('title', 'Aperçu des espaces')

@section('content')
<div class="container-fluid area-preview">
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1">Aperçu des espaces</h1>
        <p class="text-muted mb-0">
            Ouvrez les pages de chaque espace pour vérifier leur apparence. Elles s'ouvrent dans un nouvel onglet.
        </p>
    </div>

    <div class="alert alert-info d-flex gap-2 align-items-start" role="note">
        <i class="fas fa-info-circle mt-1" aria-hidden="true"></i>
        <div>
            Les pages s'affichent avec <strong>votre compte administrateur</strong> : la mise en page est celle de l'espace,
            mais les informations personnelles (fiche, nageurs, compétitions) sont les vôtres et peuvent être vides.
            Pour voir la page de connexion ou d'inscription, utilisez une fenêtre de navigation privée.
        </div>
    </div>

    <div class="row g-4">
        @foreach ($areas as $key => $area)
            <section class="col-12 col-lg-6 {{ $key === 'public' ? 'col-xl-12' : 'col-xl-4' }}" aria-labelledby="espace-{{ $key }}">
                <div class="card border-0 shadow-sm h-100 area-card area-card--{{ $area['class'] }}">
                    <div class="area-card-head">
                        <h2 id="espace-{{ $key }}" class="h5 fw-bold mb-1">
                            <i class="fas {{ $area['icon'] }} me-2" aria-hidden="true"></i>{{ $area['title'] }}
                        </h2>
                        <p class="small mb-0">{{ $area['text'] }}</p>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 area-links">
                            @foreach ($area['links'] as $link)
                                <li>
                                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="area-link">
                                        <span class="fw-semibold">{{ $link['label'] }}</span>
                                        <span class="area-link-path">{{ $link['path'] }}</span>
                                        <i class="fas fa-external-link-alt ms-auto" aria-hidden="true"></i>
                                        <span class="visually-hidden">(nouvel onglet)</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
    .area-card { border-radius: 14px; overflow: hidden; }
    .area-card-head { padding: 1rem 1.25rem; }
    .area-card--public .area-card-head { background: #0090D4; color: #fff; }
    .area-card--member .area-card-head { background: #fff; color: #1d293d; border-bottom: 3px solid #0090D4; }
    .area-card--editor .area-card-head { background: #D49F45; color: #020618; }

    .area-links { display: grid; gap: 0.4rem; }
    .area-card--public .area-links { grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); }

    .area-link {
        display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;
        padding: 0.55rem 0.8rem; border: 1px solid #e2e8f0; border-radius: 10px;
        color: #1d293d; text-decoration: none; background: #fff;
        transition: background .15s ease, border-color .15s ease;
    }
    .area-link:hover, .area-link:focus { background: #F0F9FF; border-color: #0090D4; color: #1d293d; }
    .area-link:focus-visible { outline: 3px solid #0090D4; outline-offset: 2px; }
    .area-link-path { font-size: .78rem; color: #62748e; }
    .area-link .fa-external-link-alt { font-size: .75rem; color: #0090D4; }
</style>
@endpush
