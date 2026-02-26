<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Fiche;
use App\Models\FichesCategory;
use App\Models\FichesSousCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFicheRequest;
use App\Http\Requests\UpdateFicheRequest;

/**
 * EditorFicheController - Gestion des fiches pour les éditeurs
 * Les éditeurs peuvent voir, créer, modifier et supprimer TOUTES les fiches
 * 
 * @file app/Http/Controllers/Editor/EditorFicheController.php
 */
class EditorFicheController extends Controller
{
    /**
     * Vérifier que l'utilisateur a le rôle éditeur
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
     * Liste de toutes les fiches
     */
    public function index(Request $request)
    {
        $this->checkEditorAccess();
        
        $search = $request->input('search');
        $visibility = $request->input('visibility');
        $categoryId = $request->input('category');
        $sousCategoryId = $request->input('sous_category');
        $featured = $request->input('featured');
        
        $query = Fiche::with(['category', 'sousCategory', 'creator']);

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
            $query->where('fiches_category_id', $categoryId);
        }

        if ($sousCategoryId) {
            $query->where('fiches_sous_category_id', $sousCategoryId);
        }

        if ($featured) {
            $query->where('is_featured', true);
        }

        $fiches = $query->orderBy('sort_order', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        $categories = FichesCategory::where('is_active', true)->orderBy('name')->get();
        
        $sousCategories = $categoryId 
            ? FichesSousCategory::where('fiches_category_id', $categoryId)
                               ->where('is_active', true)
                               ->orderBy('name')
                               ->get()
            : collect();

        $stats = [
            'total' => Fiche::count(),
            'published' => Fiche::where('is_published', true)->count(),
            'draft' => Fiche::where('is_published', false)->count(),
            'public' => Fiche::where('visibility', 'public')->count(),
            'authenticated' => Fiche::where('visibility', 'authenticated')->count(),
            'featured' => Fiche::where('is_featured', true)->count(),
        ];

        return view('editor.fiches.index', compact(
            'fiches',
            'categories',
            'sousCategories',
            'stats',
            'search',
            'visibility',
            'categoryId',
            'sousCategoryId',
            'featured'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->checkEditorAccess();
        
        $categories = FichesCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('editor.fiches.create', compact('categories'));
    }

    /**
     * Enregistrer une nouvelle fiche
     */
    public function store(StoreFicheRequest $request)
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
        
        if (!empty($data['fiches_sous_category_id']) && empty($data['fiches_category_id'])) {
            $sousCategory = FichesSousCategory::find($data['fiches_sous_category_id']);
            if ($sousCategory) {
                $data['fiches_category_id'] = $sousCategory->fiches_category_id;
            }
        }
        
        $fiche = Fiche::create($data);

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('editor.fiches.edit', $fiche)
                ->with('success', 'Fiche créée avec succès. Vous pouvez continuer à l\'éditer.');
        }

        return redirect()->route('editor.fiches.index')
            ->with('success', 'Fiche créée avec succès.');
    }

    /**
     * Afficher une fiche
     */
    public function show(Fiche $fiche)
    {
        $this->checkEditorAccess();
        
        $fiche->load(['category', 'sousCategory', 'creator', 'updater']);
        
        return view('editor.fiches.show', compact('fiche'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Fiche $fiche)
    {
        $this->checkEditorAccess();
        
        $categories = FichesCategory::where('is_active', true)->orderBy('name')->get();
        
        $sousCategories = $fiche->fiches_category_id 
            ? FichesSousCategory::where('fiches_category_id', $fiche->fiches_category_id)
                               ->where('is_active', true)
                               ->orderBy('name')
                               ->get()
            : collect();
        
        $fiche->load(['category', 'sousCategory']);
        
        return view('editor.fiches.edit', compact('fiche', 'categories', 'sousCategories'));
    }

    /**
     * Mettre à jour une fiche
     */
    public function update(UpdateFicheRequest $request, Fiche $fiche)
    {
        $this->checkEditorAccess();
        
        $data = $request->validated();
        
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }
        
        if (!empty($data['is_published'])) {
            if (empty($data['published_at']) && !$fiche->is_published) {
                $data['published_at'] = now();
            }
        }
        
        $data['updated_by'] = auth()->id();
        
        if (!empty($data['fiches_sous_category_id'])) {
            $sousCategory = FichesSousCategory::find($data['fiches_sous_category_id']);
            if ($sousCategory) {
                $data['fiches_category_id'] = $sousCategory->fiches_category_id;
            }
        }
        
        $fiche->update($data);

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('editor.fiches.edit', $fiche)
                ->with('success', 'Fiche mise à jour avec succès.');
        }

        return redirect()->route('editor.fiches.index')
            ->with('success', 'Fiche mise à jour avec succès.');
    }

    /**
     * Supprimer une fiche
     */
    public function destroy(Fiche $fiche)
    {
        $this->checkEditorAccess();
        
        $fiche->delete();

        return redirect()->route('editor.fiches.index')
            ->with('success', 'Fiche supprimée avec succès.');
    }

    /**
     * Actions groupées
     */
    public function bulkAction(Request $request)
    {
        $this->checkEditorAccess();
        
        $validated = $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'fiche_ids' => 'required|array|min:1',
            'fiche_ids.*' => 'exists:fiches,id'
        ]);

        $ficheIds = $validated['fiche_ids'];
        $action = $validated['action'];

        switch ($action) {
            case 'delete':
                Fiche::whereIn('id', $ficheIds)->delete();
                $message = count($ficheIds) . ' fiche(s) supprimée(s) avec succès.';
                break;

            case 'publish':
                Fiche::whereIn('id', $ficheIds)->update([
                    'is_published' => true,
                    'published_at' => now()
                ]);
                $message = count($ficheIds) . ' fiche(s) publiée(s) avec succès.';
                break;

            case 'unpublish':
                Fiche::whereIn('id', $ficheIds)->update(['is_published' => false]);
                $message = count($ficheIds) . ' fiche(s) dépubliée(s) avec succès.';
                break;

            case 'feature':
                Fiche::whereIn('id', $ficheIds)->update(['is_featured' => true]);
                $message = count($ficheIds) . ' fiche(s) mise(s) en avant avec succès.';
                break;

            case 'unfeature':
                Fiche::whereIn('id', $ficheIds)->update(['is_featured' => false]);
                $message = count($ficheIds) . ' fiche(s) retirée(s) de la mise en avant.';
                break;

            default:
                $message = 'Action non reconnue.';
        }

        return redirect()->route('editor.fiches.index')
            ->with('success', $message);
    }
}
