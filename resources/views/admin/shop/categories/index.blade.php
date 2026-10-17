@extends('layouts.admin')

@section('title', 'Boutique — catégories')

@section('content')
<div class="shop-admin">
    @include('admin.shop._nav', ['active' => 'categories'])

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.shop.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une catégorie
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Ordre</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col" class="text-center">Produits</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="ps-3 text-muted">{{ $category->sort_order }}</td>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                @if ($category->description)
                                    <br><small class="text-muted">{{ Str::limit($category->description, 80) }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.shop.products.index', ['categorie' => $category->id]) }}">{{ $category->products_count }}</a>
                            </td>
                            <td>
                                @if ($category->is_active)
                                    <span class="badge bg-success">Affichée</span>
                                @else
                                    <span class="badge bg-secondary">Masquée</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ route('admin.shop.products.create', ['categorie' => $category->id]) }}" class="btn btn-sm btn-outline-success" title="Ajouter un produit dans cette catégorie">
                                    <i class="fas fa-plus" aria-hidden="true"></i><span class="visually-hidden">Ajouter un produit</span>
                                </a>
                                <a href="{{ route('admin.shop.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route('admin.shop.categories.destroy', $category) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer la catégorie « {{ addslashes($category->name) }} » ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                            @if ($category->products_count > 0) disabled aria-disabled="true" @endif>
                                        <i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Aucune catégorie. Exemples : Textile, Matériel de natation, Accessoires.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <p class="small text-muted mt-2 mb-0">Une catégorie ne peut être supprimée que si elle ne contient plus de produit.</p>
</div>
@endsection
