<?php

namespace App\Http\Controllers;

use App\Models\PagesCategory;
use Illuminate\Http\Request;

/**
 * 🇬🇧 PagesCategoryController - Admin management for pages categories
 * 🇫🇷 PagesCategoryController - Gestion admin des catégories de pages
 * 
 * @file app/Http/Controllers/PagesCategoryController.php
 */
class PagesCategoryController extends Controller
{
    private function checkAdminAccess(): void
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdminAccess();
        
        $search = $request->input('search');
        $query = PagesCategory::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->withCount('pages')
                           ->orderBy('sort_order', 'asc')
                           ->orderBy('name', 'asc')
                           ->paginate(10);

        $stats = [
            'total' => PagesCategory::count(),
            'active' => PagesCategory::where('is_active', true)->count(),
            'inactive' => PagesCategory::where('is_active', false)->count(),
        ];

        return view('admin.pages-categories.index', compact('categories', 'search', 'stats'));
    }

    public function create()
    {
        $this->checkAdminAccess();
        
        return view('admin.pages-categories.create');
    }

    public function store(Request $request)
    {
        $this->checkAdminAccess();
        
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191|unique:pages_categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:2048',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }

        $data['created_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);
        
        PagesCategory::create($data);

        return redirect()->route('admin.pages-categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function show(PagesCategory $pagesCategory)
    {
        $this->checkAdminAccess();
        
        $pagesCategory->loadCount('pages');
        
        return view('admin.pages-categories.show', compact('pagesCategory'));
    }

    public function edit(PagesCategory $pagesCategory)
    {
        $this->checkAdminAccess();
        
        return view('admin.pages-categories.edit', compact('pagesCategory'));
    }

    public function update(Request $request, PagesCategory $pagesCategory)
    {
        $this->checkAdminAccess();
        
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191|unique:pages_categories,slug,' . $pagesCategory->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:2048',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }

        $data['updated_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);
        
        $pagesCategory->update($data);

        return redirect()->route('admin.pages-categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(PagesCategory $pagesCategory)
    {
        $this->checkAdminAccess();
        
        if ($pagesCategory->pages()->count() > 0) {
            return redirect()->route('admin.pages-categories.index')
                ->with('error', 'Impossible de supprimer une catégorie contenant des pages.');
        }
        
        $pagesCategory->update(['deleted_by' => auth()->id()]);
        $pagesCategory->delete();

        return redirect()->route('admin.pages-categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
