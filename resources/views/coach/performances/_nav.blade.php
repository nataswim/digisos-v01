{{-- Sous-menu du module Performances --}}
<nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Performances">
    @foreach ([
        ['coach.performances.index', 'Derniers temps', 'fa-list'],
        ['coach.performances.create', 'Saisir des temps', 'fa-stopwatch'],
        ['coach.performances.ranking', 'Classement du club', 'fa-ranking-star'],
    ] as [$route, $label, $icon])
        <a href="{{ route($route) }}" class="btn btn-sm {{ request()->routeIs($route) ? 'btn-primary' : 'btn-outline-primary' }}"
           @if (request()->routeIs($route)) aria-current="page" @endif><i class="fas {{ $icon }} me-1" aria-hidden="true"></i>{{ $label }}</a>
    @endforeach
</nav>
