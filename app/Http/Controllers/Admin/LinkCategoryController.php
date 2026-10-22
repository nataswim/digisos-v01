<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LinkCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Liens utiles : gestion des catégories (administrateurs uniquement, routes admin.links.categories.*).
 */
class LinkCategoryController extends Controller
{
    public function index(): View
    {
        $categories = LinkCategory::ordered()->withCount('links')->get();

        return view('admin.links.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.links.categories.form', [
            'category' => new LinkCategory([
                'is_active' => true,
                'sort_order' => (int) LinkCategory::max('sort_order') + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $category = LinkCategory::create($this->validated($request));

        return redirect()->route('admin.links.categories.index')
            ->with('success', "Catégorie « {$category->name} » créée.");
    }

    public function edit(LinkCategory $category): View
    {
        return view('admin.links.categories.form', compact('category'));
    }

    public function update(Request $request, LinkCategory $category): RedirectResponse
    {
        $category->update($this->validated($request));

        return redirect()->route('admin.links.categories.index')
            ->with('success', "Catégorie « {$category->name} » mise à jour.");
    }

    public function destroy(LinkCategory $category): RedirectResponse
    {
        if ($category->links()->exists()) {
            return redirect()->route('admin.links.categories.index')
                ->with('error', "La catégorie « {$category->name} » contient encore des liens : déplacez-les ou supprimez-les d'abord.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.links.categories.index')
            ->with('success', "Catégorie « {$name} » supprimée.");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
