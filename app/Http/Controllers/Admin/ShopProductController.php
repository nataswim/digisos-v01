<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Boutique vitrine : gestion des produits (administrateurs uniquement, routes admin.shop.products.*).
 */
class ShopProductController extends Controller
{
    /** Adresse d'image : page du site (« /storage/… ») ou adresse complète en http(s). */
    private const IMAGE_RULE = ['nullable', 'string', 'max:500', 'regex:#^(https?://|/)#i'];

    public function index(Request $request): View
    {
        $categories = ShopCategory::ordered()->withCount('products')->get();
        $categoryId = $categories->firstWhere('id', (int) $request->query('categorie'))?->id;

        $products = ShopProduct::with('category')
            ->when($categoryId, fn ($q) => $q->where('shop_category_id', $categoryId))
            ->orderBy('shop_category_id')
            ->ordered()
            ->get();

        return view('admin.shop.products.index', compact('products', 'categories', 'categoryId'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (ShopCategory::count() === 0) {
            return redirect()->route('admin.shop.categories.create')
                ->with('error', 'Créez d\'abord une catégorie, puis ajoutez vos produits.');
        }

        return view('admin.shop.products.form', [
            'product' => new ShopProduct([
                'shop_category_id' => (int) $request->query('categorie') ?: null,
                'is_active' => true,
                'sort_order' => (int) ShopProduct::max('sort_order') + 1,
            ]),
            'categories' => ShopCategory::ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $product = ShopProduct::create($this->validated($request));

        return redirect()->route('admin.shop.products.index')
            ->with('success', "Produit « {$product->name} » ajouté.");
    }

    public function edit(ShopProduct $product): View
    {
        return view('admin.shop.products.form', [
            'product' => $product,
            'categories' => ShopCategory::ordered()->get(),
        ]);
    }

    public function update(Request $request, ShopProduct $product): RedirectResponse
    {
        $product->update($this->validated($request));

        return redirect()->route('admin.shop.products.index')
            ->with('success', "Produit « {$product->name} » mis à jour.");
    }

    public function destroy(ShopProduct $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();   // les images restent dans la médiathèque

        return redirect()->route('admin.shop.products.index')
            ->with('success', "Produit « {$name} » supprimé.");
    }

    private function validated(Request $request): array
    {
        // Prix saisis « 12,50 » → 12.50
        $request->merge([
            'price_min' => $this->toNumber($request->input('price_min')),
            'price_max' => $this->toNumber($request->input('price_max')),
        ]);

        $data = $request->validate([
            'shop_category_id' => ['required', 'integer', 'exists:shop_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'summary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:50000'],
            'price_min' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'price_max' => ['nullable', 'numeric', 'min:0', 'max:99999', 'gte:price_min'],
            'price_note' => ['nullable', 'string', 'max:100'],
            'image' => self::IMAGE_RULE,
            'gallery' => ['nullable', 'array', 'max:12'],
            'gallery.*' => self::IMAGE_RULE,
            'order_url' => ['nullable', 'string', 'max:500', 'url:http,https'],
            'order_label' => ['nullable', 'string', 'max:50'],
            'badge' => ['nullable', Rule::in(array_keys(ShopProduct::BADGES))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ], [
            'shop_category_id.required' => 'Choisissez une catégorie.',
            'price_min.numeric' => 'Le prix doit être un nombre, par exemple 12 ou 12,50.',
            'price_max.numeric' => 'Le prix maximum doit être un nombre, par exemple 15.',
            'price_max.gte' => 'Le haut de la fourchette doit être supérieur ou égal au prix de départ.',
            'image.regex' => 'L\'image doit être choisie dans la médiathèque ou être une adresse commençant par https://',
            'gallery.*.regex' => 'Chaque image de la galerie doit être choisie dans la médiathèque ou être une adresse commençant par https://',
            'gallery.max' => '12 images au maximum dans la galerie.',
            'order_url.url' => 'Le lien de commande doit être une adresse complète, commençant par https://',
        ]);

        $data['gallery'] = collect($data['gallery'] ?? [])->map(fn ($u) => trim((string) $u))->filter()->unique()->values()->all() ?: null;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    private function toNumber(mixed $value): mixed
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        $clean = str_replace([' ', "\u{00A0}", "\u{202F}", '€'], '', (string) $value);

        return str_replace(',', '.', $clean);
    }
}
