<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PagesCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;

/**
 * 🇬🇧 PageController - Admin management for pages
 * 🇫🇷 PageController - Gestion admin des pages
 * 
 * @file app/Http/Controllers/PageController.php
 */
class PageController extends Controller
{
    private function checkAdminAccess(): void
    {
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('editor')) {
            abort(403, 'Accès non autorisé');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdminAccess();
        
        $search = $request->input('search');
        $visibility = $request->input('visibility');
        $categoryId = $request->input('category');
        
        $query = Page::with(['category', 'creator']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('long_description', 'like', "%{$search}%");
            });
        }

        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        if ($categoryId) {
            $query->where('pages_category_id', $categoryId);
        }

        $pages = $query->orderBy('sort_order', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        $categories = PagesCategory::active()->ordered()->get();

        $stats = [
            'total' => Page::count(),
            'published' => Page::where('is_published', true)->count(),
            'draft' => Page::where('is_published', false)->count(),
            'public' => Page::where('visibility', 'public')->count(),
            'authenticated' => Page::where('visibility', 'authenticated')->count(),
        ];

        return view('admin.pages.index', compact(
            'pages',
            'categories',
            'stats',
            'search',
            'visibility',
            'categoryId'
        ));
    }

    public function create()
    {
        $this->checkAdminAccess();
        
        $categories = PagesCategory::active()->ordered()->get();
        
        return view('admin.pages.create', compact('categories'));
    }

    public function store(StorePageRequest $request)
    {
        $this->checkAdminAccess();
        
        $data = $request->validated();
        
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }
        
        if (!empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        $data['created_by'] = auth()->id();
        $data['created_by_name'] = auth()->user()->name;
        
        $page = Page::create($data);

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('admin.pages.edit', $page)
                ->with('success', 'Page créée avec succès. Vous pouvez continuer à l\'éditer.');
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page créée avec succès.');
    }

    public function show(Page $page)
    {
        $this->checkAdminAccess();
        
        $page->load(['category', 'creator', 'updater']);
        
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        $this->checkAdminAccess();
        
        $categories = PagesCategory::active()->ordered()->get();
        
        $page->load(['category']);
        
        return view('admin.pages.edit', compact('page', 'categories'));
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $this->checkAdminAccess();
        
        $data = $request->validated();
        
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }
        
        if (!empty($data['is_published'])) {
            if (empty($data['published_at']) && !$page->is_published) {
                $data['published_at'] = now();
            }
        }
        
        $data['updated_by'] = auth()->id();
        
        $page->update($data);

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('admin.pages.edit', $page)
                ->with('success', 'Page mise à jour avec succès.');
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page mise à jour avec succès.');
    }

    public function destroy(Page $page)
    {
        $this->checkAdminAccess();
        
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page supprimée avec succès.');
    }

    public function bulkAssignCategories(Request $request)
    {
        $this->checkAdminAccess();
        
        $validated = $request->validate([
            'page_ids' => 'required|array|min:1',
            'page_ids.*' => 'exists:pages,id',
            'pages_category_id' => 'nullable|exists:pages_categories,id',
        ], [
            'page_ids.required' => 'Veuillez sélectionner au moins une page.',
            'page_ids.min' => 'Veuillez sélectionner au moins une page.',
            'pages_category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ]);
        
        $pageIds = $validated['page_ids'];
        $categoryId = $validated['pages_category_id'] ?? null;
        
        $updateData = [
            'updated_by' => auth()->id(),
            'pages_category_id' => $categoryId,
        ];
        
        $updatedCount = Page::whereIn('id', $pageIds)->update($updateData);
        
        $message = "✓ {$updatedCount} page(s) mise(s) à jour avec succès.";
        
        return redirect()->route('admin.pages.index')
            ->with('success', $message);
    }
}
