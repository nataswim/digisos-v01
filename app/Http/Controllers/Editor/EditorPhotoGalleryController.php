<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\PhotoGallery;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Requests\StorePhotoGalleryRequest;
use App\Http\Requests\UpdatePhotoGalleryRequest;

class EditorPhotoGalleryController extends Controller
{
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

    public function index(Request $request)
    {
        $this->checkEditorAccess();
        
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

        $galleries = $query->orderBy('created_at', 'desc')
                          ->paginate(15);

        $stats = [
            'total' => PhotoGallery::count(),
            'published' => PhotoGallery::where('is_published', true)->count(),
            'draft' => PhotoGallery::where('is_published', false)->count(),
            'featured' => PhotoGallery::where('is_featured', true)->count(),
        ];

        return view('editor.photo-galleries.index', compact(
            'galleries',
            'stats',
            'search',
            'visibility',
            'status'
        ));
    }

    public function create()
    {
        $this->checkEditorAccess();
        
        return view('editor.photo-galleries.create');
    }

    public function store(StorePhotoGalleryRequest $request)
    {
        $this->checkEditorAccess();
        
        $data = $request->validated();
        
        $gallery = PhotoGallery::create($data);
        
        if (!empty($data['photos'])) {
            $this->syncPhotos($gallery, $data['photos'], $data['captions'] ?? []);
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('editor.photo-galleries.edit', $gallery)
                ->with('success', 'Galerie créée avec succès.');
        }

        return redirect()->route('editor.photo-galleries.index')
            ->with('success', 'Galerie créée avec succès.');
    }

    public function show(PhotoGallery $photoGallery)
    {
        $this->checkEditorAccess();
        
        $photoGallery->load(['coverImage', 'photos']);
        
        return view('editor.photo-galleries.show', compact('photoGallery'));
    }

    public function edit(PhotoGallery $photoGallery)
    {
        $this->checkEditorAccess();
        
        $photoGallery->load(['coverImage', 'photos']);
        
        return view('editor.photo-galleries.edit', compact('photoGallery'));
    }

    public function update(UpdatePhotoGalleryRequest $request, PhotoGallery $photoGallery)
    {
        $this->checkEditorAccess();
        
        $data = $request->validated();
        
        $photoGallery->update($data);
        
        if (isset($data['photos'])) {
            $this->syncPhotos($photoGallery, $data['photos'], $data['captions'] ?? []);
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('editor.photo-galleries.edit', $photoGallery)
                ->with('success', 'Galerie mise à jour avec succès.');
        }

        return redirect()->route('editor.photo-galleries.index')
            ->with('success', 'Galerie mise à jour avec succès.');
    }

    public function destroy(PhotoGallery $photoGallery)
    {
        $this->checkEditorAccess();
        
        $photoGallery->photos()->detach();
        $photoGallery->delete();

        return redirect()->route('editor.photo-galleries.index')
            ->with('success', 'Galerie supprimée avec succès.');
    }

    public function duplicate(PhotoGallery $photoGallery)
    {
        $this->checkEditorAccess();
        
        $newGallery = $photoGallery->replicate();
        $newGallery->title = $photoGallery->title . ' (Copie)';
        $newGallery->slug = \Str::slug($newGallery->title);
        $newGallery->is_published = false;
        $newGallery->published_at = null;
        $newGallery->save();
        
        foreach ($photoGallery->photos as $photo) {
            $newGallery->photos()->attach($photo->id, [
                'sort_order' => $photo->pivot->sort_order,
                'caption' => $photo->pivot->caption,
            ]);
        }

        return redirect()->route('editor.photo-galleries.edit', $newGallery)
            ->with('success', 'Galerie dupliquée avec succès.');
    }

    public function bulkAction(Request $request)
    {
        $this->checkEditorAccess();
        
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