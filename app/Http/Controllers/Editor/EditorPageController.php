<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PagesCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;

/**
 * EditorPageController - Gestion des pages pour les éditeurs
 * 
 * @file app/Http/Controllers/Editor/EditorPageController.php
 */
class EditorPageController extends Controller
{
    /**
     * Vérifier l'accès éditeur
     */
    private function checkEditorAccess(): void
    {
        $user = auth()->user();
        
        if (!$user || !$user->role) {
            abort(403, 'Authentification requise');
        }
        
        if (!$user->hasRole('editor') && !$user->hasRole('admin')) {
            abort(403, 'Accès non autorisé - Rôle éditeur requis');
        }
    }

    /**
     * Liste des pages
     */
    public function index(Request $request)
    {
        $this->checkEditorAccess();
        
        $search = $request->input('search');
        $visibility = $request->input('visibility');
        $categoryId = $request->input('category');
        $myPages = $request->input('my_pages'); // Filtre "mes pages"
        
        $query = Page::with(['category', 'creator']);

        // Filtre "Mes pages" pour voir uniquement ses propres pages
        if ($myPages) {
            $query->where('created_by', auth()->id());
        }

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

        $pages = $query->orderBy('created_at', 'desc')
                       ->paginate(15);

        $categories = PagesCategory::active()->ordered()->get();

        $stats = [
            'total' => Page::count(),
            'my_total' => Page::where('created_by', auth()->id())->count(),
            'published' => Page::where('is_published', true)->count(),
            'my_published' => Page::where('created_by', auth()->id())
                                  ->where('is_published', true)
                                  ->count(),
            'draft' => Page::where('is_published', false)->count(),
            'my_draft' => Page::where('created_by', auth()->id())
                              ->where('is_published', false)
                              ->count(),
        ];

        return view('editor.pages.index', compact(
            'pages',
            'categories',
            'stats',
            'search',
            'visibility',
            'categoryId',
            'myPages'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->checkEditorAccess();
        
        $categories = PagesCategory::active()->ordered()->get();
        
        return view('editor.pages.create', compact('categories'));
    }

    /**
     * Enregistrer une nouvelle page
     */
    public function store(StorePageRequest $request)
    {
        $this->checkEditorAccess();
        
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
            return redirect()->route('editor.pages.edit', $page)
                ->with('success', 'Page créée avec succès. Vous pouvez continuer à l\'éditer.');
        }

        return redirect()->route('editor.pages.index')
            ->with('success', 'Page créée avec succès.');
    }

    /**
     * Afficher une page
     */
    public function show(Page $page)
    {
        $this->checkEditorAccess();
        
        $page->load(['category', 'creator', 'updater']);
        
        return view('editor.pages.show', compact('page'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Page $page)
    {
        $this->checkEditorAccess();
        
        $categories = PagesCategory::active()->ordered()->get();
        $page->load(['category']);
        
        return view('editor.pages.edit', compact('page', 'categories'));
    }

    /**
     * Mettre à jour une page
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $this->checkEditorAccess();
        
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
            return redirect()->route('editor.pages.edit', $page)
                ->with('success', 'Page mise à jour avec succès.');
        }

        return redirect()->route('editor.pages.index')
            ->with('success', 'Page mise à jour avec succès.');
    }

    /**
     * Supprimer une page
     */
    public function destroy(Page $page)
    {
        $this->checkEditorAccess();
        
        // Les éditeurs peuvent supprimer n'importe quelle page
        $page->delete();

        return redirect()->route('editor.pages.index')
            ->with('success', 'Page supprimée avec succès.');
    }

    /**
     * Assigner des catégories en masse
     */
    public function bulkAssignCategories(Request $request)
    {
        $this->checkEditorAccess();
        
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
        
        return redirect()->route('editor.pages.index')
            ->with('success', $message);
    }
}