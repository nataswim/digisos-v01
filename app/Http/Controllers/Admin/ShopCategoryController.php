<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Boutique vitrine : gestion des catégories (administrateurs uniquement, routes admin.shop.categories.*).
 */
class ShopCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ShopCategory::ordered()->withCount('products')->get();

        return view('admin.shop.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.shop.categories.form', [
            'category' => new ShopCategory([
                'is_active' => true,
                'sort_order' => (int) ShopCategory::max('sort_order') + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $category = ShopCategory::create($this->validated($request));

        return redirect()->route('admin.shop.categories.index')
            ->with('success', "Catégorie « {$category->name} » créée.");
    }

    public function edit(ShopCategory $category): View
    {
        return view('admin.shop.categories.form', compact('category'));
    }

    public function update(Request $request, ShopCategory $category): RedirectResponse
    {
        $category->update($this->validated($request));

        return redirect()->route('admin.shop.categories.index')
            ->with('success', "Catégorie « {$category->name} » mise à jour.");
    }

    public function destroy(ShopCategory $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.shop.categories.index')
                ->with('error', "La catégorie « {$category->name} » contient encore des produits : déplacez-les ou supprimez-les d'abord.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.shop.categories.index')
            ->with('success', "Catégorie « {$name} » supprimée.");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/)#i'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ], [
            'image.regex' => 'L\'image doit être choisie dans la médiathèque ou être une adresse commençant par https://',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
