<?php

namespace App\Http\Controllers;

use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Illuminate\Contracts\View\View;

/**
 * Pages publiques de la boutique vitrine :
 *   /boutique                         tous les produits, rangés par catégorie
 *   /boutique/{categorie}             les produits d'une catégorie
 *   /boutique/{categorie}/{produit}   fiche d'un produit
 */
class PublicShopController extends Controller
{
    public function index(): View
    {
        $categories = $this->categoriesWithProducts();

        return view('public.shop.index', [
            'categories' => $categories,
            'current' => null,
            'total' => $categories->sum(fn ($c) => $c->products->count()),
        ]);
    }

    public function category(ShopCategory $category): View
    {
        abort_unless($category->is_active, 404);

        $category->setRelation('products', $category->products()->where('is_active', true)->ordered()->get());

        return view('public.shop.index', [
            'categories' => collect([$category]),
            'current' => $category,
            'total' => $category->products->count(),
            'allCategories' => $this->categoriesWithProducts(),
        ]);
    }

    public function show(ShopCategory $category, ShopProduct $product): View
    {
        abort_unless($category->is_active && $product->is_active && $product->shop_category_id === $category->id, 404);

        $others = ShopProduct::visible()
            ->where('shop_category_id', $category->id)
            ->whereKeyNot($product->id)
            ->ordered()
            ->limit(4)
            ->get();

        return view('public.shop.show', compact('category', 'product', 'others'));
    }

    /** Catégories affichées qui contiennent au moins un produit affiché. */
    private function categoriesWithProducts()
    {
        return ShopCategory::active()
            ->ordered()
            ->with(['products' => fn ($q) => $q->where('is_active', true)->ordered()])
            ->get()
            ->filter(fn ($c) => $c->products->isNotEmpty())
            ->values();
    }
}
