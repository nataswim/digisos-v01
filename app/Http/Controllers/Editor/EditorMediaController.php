<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * EditorMediaController - Gestion des médias pour les éditeurs
 * Les éditeurs peuvent voir et gérer TOUS les médias
 * 
 * @file app/Http/Controllers/Editor/EditorMediaController.php
 */
class EditorMediaController extends Controller
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
     * Liste de tous les médias
     */
    public function index(Request $request)
    {
        $this->checkEditorAccess();
        
        $search = $request->input('search');
        $type = $request->input('type');
        $categoryId = $request->input('category_id');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $query = Media::with(['category', 'uploader']);

        // Recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        // Type de fichier
        if ($type) {
            if ($type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($type === 'video') {
                $query->where('mime_type', 'like', 'video/%');
            } elseif ($type === 'document') {
                $query->where('mime_type', 'not like', 'image/%')
                      ->where('mime_type', 'not like', 'video/%');
            }
        }

        // Catégorie
        if ($categoryId) {
            $query->where('media_category_id', $categoryId);
        }

        // Tri
        $query->orderBy($sortBy, $sortOrder);

        $media = $query->paginate(24);

        $categories = MediaCategory::orderBy('name')->get();

        // Statistiques
        $stats = [
            'total' => Media::count(),
            'images' => Media::where('mime_type', 'like', 'image/%')->count(),
            'documents' => Media::where('mime_type', 'not like', 'image/%')
                               ->where('mime_type', 'not like', 'video/%')->count(),
            'size' => Media::sum('size'),
        ];

        return view('editor.media.index', compact(
            'media',
            'categories',
            'stats',
            'search',
            'type',
            'categoryId',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Uploader un nouveau média
     */
    public function store(Request $request)
    {
        $this->checkEditorAccess();
        
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'media_category_id' => 'nullable|exists:media_categories,id',
        ]);

        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        
        // Générer un nom unique
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        
        // Uploader
        $path = $file->storeAs('media', $filename, 'public');
        
        // Créer l'entrée
        $media = Media::create([
            'name' => $request->input('title', $file->getClientOriginalName()),
            'file_name' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mimeType,
            'path' => $path,
            'size' => $file->getSize(),
            'alt_text' => $request->input('alt_text'),
            'description' => $request->input('description'),
            'media_category_id' => $request->input('media_category_id'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'media' => $media,
                'url' => Storage::url($path)
            ]);
        }

        return redirect()->route('editor.media.index')
            ->with('success', 'Média uploadé avec succès.');
    }

    /**
     * Afficher un média
     */
    public function show(Media $media)
    {
        $this->checkEditorAccess();
        
        $media->load(['category', 'uploader']);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'media' => $media,
                'url' => Storage::url($media->path)
            ]);
        }
        
        $categories = MediaCategory::orderBy('name')->get();
        
        return view('editor.media.show', compact('media', 'categories'));
    }

    /**
     * Mettre à jour un média
     */
    public function update(Request $request, Media $media)
    {
        $this->checkEditorAccess();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_category_id' => 'nullable|exists:media_categories,id',
        ]);
        
        $media->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'media' => $media
            ]);
        }

        return redirect()->route('editor.media.index')
            ->with('success', 'Média mis à jour avec succès.');
    }

    /**
     * Supprimer un média
     */
    public function destroy(Media $media)
    {
        $this->checkEditorAccess();
        
        // Supprimer le fichier physique
        if ($media->path && Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }
        
        $media->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média supprimé avec succès.'
            ]);
        }

        return redirect()->route('editor.media.index')
            ->with('success', 'Média supprimé avec succès.');
    }

    /**
     * Actions groupées
     */
    public function bulkAction(Request $request)
    {
        $this->checkEditorAccess();
        
        $validated = $request->validate([
            'action' => 'required|in:delete,categorize',
            'media_ids' => 'required|array|min:1',
            'media_ids.*' => 'exists:media,id',
            'category_id' => 'required_if:action,categorize|nullable|exists:media_categories,id'
        ]);

        $mediaIds = $validated['media_ids'];
        $action = $validated['action'];

        switch ($action) {
            case 'delete':
                $medias = Media::whereIn('id', $mediaIds)->get();
                foreach ($medias as $media) {
                    if ($media->path && Storage::disk('public')->exists($media->path)) {
                        Storage::disk('public')->delete($media->path);
                    }
                    $media->delete();
                }
                $message = count($mediaIds) . ' média(s) supprimé(s) avec succès.';
                break;

            case 'categorize':
                Media::whereIn('id', $mediaIds)->update([
                    'media_category_id' => $validated['category_id']
                ]);
                $message = count($mediaIds) . ' média(s) catégorisé(s) avec succès.';
                break;

            default:
                $message = 'Action non reconnue.';
        }

        return redirect()->route('editor.media.index')
            ->with('success', $message);
    }
}