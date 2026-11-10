@extends($layout)

@section('title', 'Adhérents par commune — ' . $season)

@php $r = fn ($n, $p = []) => route($area . '.members.' . $n, $p); @endphp

@section('content')
@include('admin.members.lists._styles')
<div class="container-lg">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <h1 class="h3 fw-bold mb-0">Adhérents par commune — {{ $season }}</h1>
        <button type="button" class="btn btn-sm btn-outline-secondary mbr-noprint" onclick="window.print()"><i class="fas fa-print me-1" aria-hidden="true"></i>Imprimer</button>
    </div>
    @include('admin.members._nav')
    <p class="text-muted small">{{ $total }} adhérent(s) actif(s) dans {{ $cities->whereNotNull('city')->count() }} commune(s) (abandons exclus), les communes les plus représentées d'abord. * = groupe trouvé par le nom et l'année de naissance.</p>

    @if ($cities->isEmpty())
        <p class="text-muted">Aucun adhérent pour cette saison.</p>
    @else
        <nav class="mbr-jump mb-4 mbr-noprint" aria-label="Aller à une commune">
            @foreach ($cities as $i => $c)
                <a href="#commune-{{ $i }}">{{ $c['city'] ?? 'Non renseignée' }} <span class="text-muted">({{ $c['rows']->count() }})</span></a>
            @endforeach
        </nav>

        <div class="row g-4">
            @foreach ($cities as $i => $c)
                <div class="col-xl-6">
                    <section class="card border-0 shadow-sm mbr-block h-100" id="commune-{{ $i }}" aria-labelledby="titre-commune-{{ $i }}">
                        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-baseline gap-2">
                            <h2 id="titre-commune-{{ $i }}" class="h6 fw-bold mb-0">
                                {{ $c['city'] ?? 'Commune non renseignée' }} @if ($c['postal_code'])<span class="text-muted fw-normal">{{ $c['postal_code'] }}</span>@endif
                            </h2>
                            <span class="small text-muted">{{ $c['rows']->count() }} adhérent(s)</span>
                        </div>
                        @include('admin.members.lists._table', ['rows' => $c['rows'], 'columns' => ['year'], 'caption' => 'Adhérents de ' . ($c['city'] ?? 'commune non renseignée')])
                    </section>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
