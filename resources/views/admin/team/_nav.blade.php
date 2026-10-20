{{--
    En-tête commun du module « L'équipe du club » (titre, onglets, erreurs de formulaire).
    Les messages de succès et d'erreur sont affichés par layouts.admin.
    Variable : $active ('members' ou 'sections')
--}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-users text-primary me-2" aria-hidden="true"></i>L'équipe du club</h1>
    <a href="{{ route('team.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
        <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Voir la page
    </a>
</div>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $active === 'members' ? 'active' : '' }}" href="{{ route('admin.team.members.index') }}"
           @if ($active === 'members') aria-current="page" @endif>
            <i class="fas fa-id-badge me-1" aria-hidden="true"></i>Membres
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active === 'sections' ? 'active' : '' }}" href="{{ route('admin.team.sections.index') }}"
           @if ($active === 'sections') aria-current="page" @endif>
            <i class="fas fa-layer-group me-1" aria-hidden="true"></i>Sections
        </a>
    </li>
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
