@extends('layouts.admin')

@section('title', 'Boutique — produits')

@section('content')
<div class="shop-admin">
    @include('admin.shop._nav', ['active' => 'products'])

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        {{-- Filtre par catégorie --}}
        <ul class="nav nav-pills small">
            <li class="nav-item">
                <a class="nav-link {{ $categoryId ? '' : 'active' }}" href="{{ route('admin.shop.products.index') }}">
                    Toutes <span class="badge bg-light text-dark ms-1">{{ $categories->sum('products_count') }}</span>
                </a>
            </li>
            @foreach ($categories as $cat)
                <li class="nav-item">
                    <a class="nav-link {{ $categoryId === $cat->id ? 'active' : '' }}" href="{{ route('admin.shop.products.index', ['categorie' => $cat->id]) }}">
                        {{ $cat->name }} <span class="badge bg-light text-dark ms-1">{{ $cat->products_count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('admin.shop.products.create', $categoryId ? ['categorie' => $categoryId] : []) }}" class="btn btn-primary">
            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un produit
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Image</th>
                        <th scope="col">Produit</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Commande</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="ps-3">
                                @if ($product->image)
                                    <img src="{{ $product->image }}" alt="" class="shop-admin-thumb">
                                @else
                                    <span class="shop-admin-thumb shop-admin-thumb--empty" aria-hidden="true"><i class="fas fa-image"></i></span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if ($product->is_featured)
                                    <span class="badge bg-warning text-dark ms-1"><i class="fas fa-star" aria-hidden="true"></i> À la une</span>
                                @endif
                                @if ($product->badge_info)
                                    <span class="badge ms-1" style="background: {{ $product->badge_info['bg'] }}; color: {{ $product->badge_info['text'] }};">{{ $product->badge_info['label'] }}</span>
                                @endif
                                @if ($product->summary)
                                    <br><small class="text-muted">{{ Str::limit($product->summary, 70) }}</small>
                                @endif
                            </td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td class="text-nowrap">{{ $product->price_label ?? '—' }}</td>
                            <td>
                                @if ($product->order_url)
                                    <a href="{{ $product->order_url }}" target="_blank" rel="noopener" class="small">{{ $product->order_host }}</a>
                                @else
                                    <span class="badge bg-warning text-dark">Sans lien</span>
                                @endif
                            </td>
                            <td>
                                @if ($product->is_active)
                                    <span class="badge bg-success">Affiché</span>
                                @else
                                    <span class="badge bg-secondary">Masqué</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                @if ($product->is_active && $product->category?->is_active)
                                    <a href="{{ route('shop.show', [$product->category, $product]) }}" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener" title="Voir sur le site">
                                        <i class="fas fa-eye" aria-hidden="true"></i><span class="visually-hidden">Voir sur le site</span>
                                    </a>
                                @endif
                                <a href="{{ route('admin.shop.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route('admin.shop.products.destroy', $product) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer le produit « {{ addslashes($product->name) }} » ? Les images restent dans la médiathèque.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                @if ($categories->isEmpty())
                                    Commencez par <a href="{{ route('admin.shop.categories.create') }}">créer une catégorie</a> (par exemple « Textile »).
                                @else
                                    Aucun produit {{ $categoryId ? 'dans cette catégorie' : 'pour l\'instant' }}.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .shop-admin-thumb {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: #fff;
    }

    .shop-admin-thumb--empty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
    }
</style>
@endpush
