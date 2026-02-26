<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoGallery;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Requests\StorePhotoGalleryRequest;
use App\Http\Requests\UpdatePhotoGalleryRequest;

class PhotoGalleryController extends Controller
{
    private function checkAdminAccess()
    {
        $user = auth()->user();
        
        if (!$user || !$user->role) {
            abort(403, 'Accès non autorisé - Aucun rôle assigné');
        }
        
        if (!$user->hasRole('admin') && !$user->hasRole('editor')) {
            abort(403, 'Accès non autorisé');
        }
    }

    /**
     * Liste des galeries
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();
        
        $search = $request->input('search');
        $visibility = $request->input('visibility');
        $status = $request->input('status');
        
        $query = PhotoGallery::withCount('photos');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        $galleries = $query->orderBy('sort_order')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('admin.photo-galleries.index', compact('galleries', 'search', 'visibility', 'status'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->checkAdminAccess();
        
        return view('admin.photo-galleries.create');
    }

    /**
     * Enregistrer une nouvelle galerie
     */
    public function store(StorePhotoGalleryRequest $request)
    {
        $this->checkAdminAccess();
        
        $data = $request->validated();
        
        // Créer la galerie
        $gallery = PhotoGallery::create($data);
        
        // Attacher les photos sélectionnées
        if (!empty($data['photos'])) {
            $this->syncPhotos($gallery, $data['photos'], $data['captions'] ?? []);
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('admin.photo-galleries.edit', $gallery)
                ->with('success', 'Galerie créée avec succès. Vous pouvez continuer à l\'éditer.');
        }

        return redirect()->route('admin.photo-galleries.index')
            ->with('success', 'Galerie créée avec succès.');
    }

    /**
     * Afficher une galerie
     */
    public function show(PhotoGallery $photoGallery)
    {
        $this->checkAdminAccess();
        
        $photoGallery->load(['coverImage', 'photos']);
        
        return view('admin.photo-galleries.show', compact('photoGallery'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(PhotoGallery $photoGallery)
    {
        $this->checkAdminAccess();
        
        $photoGallery->load(['coverImage', 'photos']);
        
        return view('admin.photo-galleries.edit', compact('photoGallery'));
    }

    /**
     * Mettre à jour une galerie
     */
    public function update(UpdatePhotoGalleryRequest $request, PhotoGallery $photoGallery)
    {
        $this->checkAdminAccess();
        
        $data = $request->validated();
        
        // Mettre à jour la galerie
        $photoGallery->update($data);
        
        // Synchroniser les photos
        if (isset($data['photos'])) {
            $this->syncPhotos($photoGallery, $data['photos'], $data['captions'] ?? []);
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('admin.photo-galleries.edit', $photoGallery)
                ->with('success', 'Galerie mise à jour avec succès.');
        }

        return redirect()->route('admin.photo-galleries.index')
            ->with('success', 'Galerie mise à jour avec succès.');
    }

    /**
     * Supprimer une galerie
     */
    public function destroy(PhotoGallery $photoGallery)
    {
        $this->checkAdminAccess();
        
        // Détacher toutes les photos
        $photoGallery->photos()->detach();
        
        // Supprimer la galerie
        $photoGallery->delete();

        return redirect()->route('admin.photo-galleries.index')
            ->with('success', 'Galerie supprimée avec succès.');
    }

    /**
     * Dupliquer une galerie
     */
    public function duplicate(PhotoGallery $photoGallery)
    {
        $this->checkAdminAccess();
        
        $newGallery = $photoGallery->replicate();
        $newGallery->title = $photoGallery->title . ' (Copie)';
        $newGallery->slug = \Str::slug($newGallery->title);
        $newGallery->is_published = false;
        $newGallery->published_at = null;
        $newGallery->save();
        
        // Dupliquer les photos
        foreach ($photoGallery->photos as $photo) {
            $newGallery->photos()->attach($photo->id, [
                'sort_order' => $photo->pivot->sort_order,
                'caption' => $photo->pivot->caption,
            ]);
        }

        return redirect()->route('admin.photo-galleries.edit', $newGallery)
            ->with('success', 'Galerie dupliquée avec succès.');
    }

    /**
     * Actions en lot
     */
    public function bulkAction(Request $request)
    {
        $this->checkAdminAccess();
        
        $action = $request->input('action');
        $galleryIds = $request->input('galleries', []);
        
        if (empty($galleryIds)) {
            return redirect()->back()->with('error', 'Aucune galerie sélectionnée.');
        }
        
        $count = 0;
        
        switch ($action) {
            case 'publish':
                $count = PhotoGallery::whereIn('id', $galleryIds)->update([
                    'is_published' => true,
                    'published_at' => now(),
                ]);
                $message = "{$count} galerie(s) publiée(s).";
                break;
                
            case 'unpublish':
                $count = PhotoGallery::whereIn('id', $galleryIds)->update([
                    'is_published' => false,
                ]);
                $message = "{$count} galerie(s) dépubliée(s).";
                break;
                
            case 'feature':
                $count = PhotoGallery::whereIn('id', $galleryIds)->update([
                    'is_featured' => true,
                ]);
                $message = "{$count} galerie(s) mise(s) en avant.";
                break;
                
            case 'unfeature':
                $count = PhotoGallery::whereIn('id', $galleryIds)->update([
                    'is_featured' => false,
                ]);
                $message = "{$count} galerie(s) retirée(s) de la une.";
                break;
                
            case 'delete':
                foreach ($galleryIds as $galleryId) {
                    $gallery = PhotoGallery::find($galleryId);
                    if ($gallery) {
                        $gallery->photos()->detach();
                        $gallery->delete();
                        $count++;
                    }
                }
                $message = "{$count} galerie(s) supprimée(s).";
                break;
                
            default:
                return redirect()->back()->with('error', 'Action non reconnue.');
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Méthode privée pour synchroniser les photos
     */
    private function syncPhotos(PhotoGallery $gallery, array $photoIds, array $captions = [])
    {
        $syncData = [];
        
        foreach ($photoIds as $index => $photoId) {
            $syncData[$photoId] = [
                'sort_order' => $index,
                'caption' => $captions[$index] ?? null,
            ];
        }
        
        $gallery->photos()->sync($syncData);
    }
}
