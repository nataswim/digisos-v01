{{--
    En-tête commun du module Boutique (titre, onglets, erreurs de formulaire).
    Les messages de succès et d'erreur sont affichés par layouts.admin.
    Variable : $active ('products' ou 'categories')
--}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-shopping-bag text-primary me-2" aria-hidden="true"></i>Boutique du club</h1>
    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
        <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Voir la boutique
    </a>
</div>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $active === 'products' ? 'active' : '' }}" href="{{ route('admin.shop.products.index') }}"
           @if ($active === 'products') aria-current="page" @endif>
            <i class="fas fa-tshirt me-1" aria-hidden="true"></i>Produits
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active === 'categories' ? 'active' : '' }}" href="{{ route('admin.shop.categories.index') }}"
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
