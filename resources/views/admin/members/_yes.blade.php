{{-- Oui / non / inconnu. Variables : $v (bool|null), $neutral (non = gris au lieu de rouge) --}}
@if ($v === true)
    <i class="fas fa-check-circle text-success" aria-hidden="true"></i><span class="visually-hidden">Oui</span>
@elseif ($v === false)
    <i class="fas {{ ($neutral ?? false) ? 'fa-minus text-muted' : 'fa-times-circle text-danger' }}" aria-hidden="true"></i><span class="visually-hidden">Non</span>
@else
    <span class="text-muted" aria-hidden="true">·</span><span class="visually-hidden">Non renseigné</span>
@endif
