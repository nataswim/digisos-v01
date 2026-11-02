{{-- Sous-menu du module Adhérents. Variables : $area, $season (facultatif) --}}
@php $s = $season ?? \App\Models\Membership::currentSeason(); @endphp
<nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Adhérents">
    @foreach ([
        ['index', 'Adhérents', 'fa-users', ['saison' => $s]],
        ['stats', 'Tableau de bord', 'fa-chart-pie', ['saison' => $s]],
        ['create', 'Nouvel adhérent', 'fa-user-plus', ['saison' => $s]],
        ['import', 'Importer le tableau', 'fa-file-import', ['saison' => $s]],
        ['categories.index', 'Catégories et tarifs', 'fa-tags', []],
    ] as [$r, $label, $icon, $params])
        @php $actif = request()->routeIs($area . '.members.' . $r); @endphp
        <a href="{{ route($area . '.members.' . $r, $params) }}" class="btn btn-sm {{ $actif ? 'btn-primary' : 'btn-outline-primary' }}" @if ($actif) aria-current="page" @endif>
            <i class="fas {{ $icon }} me-1" aria-hidden="true"></i>{{ $label }}
        </a>
    @endforeach
</nav>
<p class="small text-muted mb-3"><i class="fas fa-lock me-1" aria-hidden="true"></i>Données personnelles : accès réservé aux administrateurs et aux éditeurs. Ne les copiez pas hors du site.</p>
