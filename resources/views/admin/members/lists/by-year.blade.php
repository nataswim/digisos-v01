@extends($layout)

@section('title', 'Adhérents par année de naissance — ' . $season)

@php $r = fn ($n, $p = []) => route($area . '.members.' . $n, $p); @endphp

@section('content')
@include('admin.members.lists._styles')
<div class="container-lg">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <h1 class="h3 fw-bold mb-0">Adhérents par année de naissance — {{ $season }}</h1>
        <button type="button" class="btn btn-sm btn-outline-secondary mbr-noprint" onclick="window.print()"><i class="fas fa-print me-1" aria-hidden="true"></i>Imprimer</button>
    </div>
    @include('admin.members._nav')
    <p class="text-muted small">{{ $total }} adhérent(s) actif(s) (abandons exclus). Âge = âge atteint en {{ substr($season, 0, 4) }} (année civile du début de saison, comme pour les catégories FFN). * = groupe trouvé par le nom et l'année de naissance, fiche nageur pas encore liée à l'adhérent.</p>

    @if ($years->isEmpty())
        <p class="text-muted">Aucun adhérent pour cette saison.</p>
    @else
        <nav class="mbr-jump mb-4 mbr-noprint" aria-label="Aller à une année">
            @foreach ($years as $y)
                <a href="#annee-{{ $y['year'] ?? 'inconnue' }}">{{ $y['year'] ?? 'Inconnue' }} <span class="text-muted">({{ $y['rows']->count() }})</span></a>
            @endforeach
        </nav>

        <div class="row g-4">
            @foreach ($years as $y)
                <div class="col-xl-6">
                    <section class="card border-0 shadow-sm mbr-block h-100" id="annee-{{ $y['year'] ?? 'inconnue' }}" aria-labelledby="titre-annee-{{ $y['year'] ?? 'inconnue' }}">
                        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-baseline gap-2">
                            <h2 id="titre-annee-{{ $y['year'] ?? 'inconnue' }}" class="h6 fw-bold mb-0">
                                @if ($y['year'])
                                    Né(e)s en {{ $y['year'] }} <span class="text-primary">· {{ $y['age'] }} ans</span>
                                @else
                                    Date de naissance non renseignée
                                @endif
                            </h2>
                            <span class="small text-muted">{{ $y['rows']->count() }} adhérent(s) · {{ $y['girls'] }} F · {{ $y['boys'] }} M</span>
                        </div>
                        @include('admin.members.lists._table', ['rows' => $y['rows'], 'columns' => ['year'], 'caption' => 'Adhérents nés en ' . ($y['year'] ?? 'année inconnue')])
                    </section>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
