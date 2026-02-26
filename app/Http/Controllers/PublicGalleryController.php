<?php

namespace App\Http\Controllers;

use App\Models\PhotoGallery;
use Illuminate\Http\Request;

class PublicGalleryController extends Controller
{
    /**
     * Liste des galeries publiques
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = PhotoGallery::with(['coverImage'])
            ->withCount('photos')
            ->visibleTo(auth()->user())
            ->ordered();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $galleries = $query->paginate(12);

        $featuredGalleries = PhotoGallery::with(['coverImage'])
            ->withCount('photos')
            ->visibleTo(auth()->user())
            ->featured()
            ->ordered()
            ->limit(6)
            ->get();

        return view('public.galleries.index', compact('galleries', 'featuredGalleries', 'search'));
    }

    /**
     * Afficher une galerie
     */
    public function show(PhotoGallery $photoGallery)
    {
        // Vérifier la visibilité
        if (!$photoGallery->isVisibleTo(auth()->user())) {
            abort(404, 'Galerie non disponible');
        }

        // Charger les photos avec leurs légendes
        $photoGallery->load(['coverImage', 'photos']);

        // Galeries similaires
        $relatedGalleries = PhotoGallery::with(['coverImage'])
            ->withCount('photos')
            ->where('id', '!=', $photoGallery->id)
            ->visibleTo(auth()->user())
            ->ordered()
            ->limit(4)
            ->get();

        return view('public.galleries.show', compact('photoGallery', 'relatedGalleries'));
    }
}
