{{--
    En-tête commun du module Planning (onglets + erreurs de formulaire).
    Les messages de succès sont affichés par layouts.admin / layouts.editor.
    Variables : $area ('admin' ou 'editor'), $active ('slots', 'groups', 'swimmers')
--}}
@php
    $onglets = [
        'slots'    => ['label' => 'Créneaux', 'icon' => 'fa-calendar-alt', 'route' => 'slots.index'],
        'groups'   => ['label' => 'Groupes',  'icon' => 'fa-layer-group',  'route' => 'groups.index'],
        'swimmers' => ['label' => 'Nageurs',  'icon' => 'fa-swimmer',      'route' => 'swimmers.index'],
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-calendar-week text-primary me-2" aria-hidden="true"></i>Planning des entraînements</h1>
    <a href="{{ route('schedule') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
        <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Voir la page publique
    </a>
</div>

<ul class="nav nav-tabs mb-4">
    @foreach ($onglets as $cle => $onglet)
        <li class="nav-item">
            <a class="nav-link {{ $active === $cle ? 'active' : '' }}"
               href="{{ route($area . '.planning.' . $onglet['route']) }}"
               @if ($active === $cle) aria-current="page" @endif>
                <i class="fas {{ $onglet['icon'] }} me-1" aria-hidden="true"></i>{{ $onglet['label'] }}
            </a>
        </li>
    @endforeach
</ul>

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong>Le formulaire contient des erreurs :</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    </div>
@endif
