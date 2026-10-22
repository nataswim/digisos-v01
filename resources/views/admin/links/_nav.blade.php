{{--
    En-tête commun du module « Liens utiles » (titre, onglets, erreurs de formulaire).
    Les messages de succès et d'erreur sont affichés par layouts.admin.
    Variable : $active ('items' ou 'categories')
--}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-link text-primary me-2" aria-hidden="true"></i>Liens utiles</h1>
    <a href="{{ route('links.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
        <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Voir la page
    </a>
</div>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $active === 'items' ? 'active' : '' }}" href="{{ route('admin.links.items.index') }}"
           @if ($active === 'items') aria-current="page" @endif>
            <i class="fas fa-link me-1" aria-hidden="true"></i>Liens
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active === 'categories' ? 'active' : '' }}" href="{{ route('admin.links.categories.index') }}"
           @if ($active === 'categories') aria-current="page" @endif>
            <i class="fas fa-folder-open me-1" aria-hidden="true"></i>Catégories
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
