<?php

namespace App\Http\Controllers;

use App\Models\Fiche;
use App\Models\FichesCategory;
use App\Models\FichesSousCategory;
use Illuminate\Http\Request;

/**
 * 🇬🇧 PublicFicheController - Public display of fiches
 * 🇫🇷 PublicFicheController - Affichage public des fiches
 * 
 * @file app/Http/Controllers/PublicFicheController.php
 */
class PublicFicheController extends Controller
{
    /**
     * 🇬🇧 Display list of all categories
     * 🇫🇷 Afficher la liste de toutes les catégories
     */
    public function index()
    {
        // 🇬🇧 Get active categories with published fiches count / 🇫🇷 Récupérer les catégories actives avec le nombre de fiches publiées
        $categories = FichesCategory::active()
            ->ordered()
            ->withCount(['fiches as published_fiches_count' => function ($query) {
                $query->where('is_published', true);
            }])
            ->get();

        // 🇬🇧 Get featured fiches / 🇫🇷 Récupérer les fiches en vedette
        $featuredFiches = Fiche::with(['category', 'sousCategory'])
            ->published()
            ->featured()
            ->ordered()
            ->limit(6)
            ->get();

        return view('public.fiches.index', compact('categories', 'featuredFiches'));
    }

    /**
     * 🇬🇧 Display fiches from a specific category
     * 🇫🇷 Afficher les fiches d'une catégorie spécifique
     */
    public function category(FichesCategory $category)
    {
        // 🇬🇧 Get published fiches from this category / 🇫🇷 Récupérer les fiches publiées de cette catégorie
        $fiches = Fiche::with(['category', 'sousCategory'])
            ->byCategory($category->id)
            ->published()
            ->ordered()
            ->paginate(12);

        // 🇬🇧 Get active sub-categories of this category / 🇫🇷 Récupérer les sous-catégories actives de cette catégorie
        $sousCategories = FichesSousCategory::where('fiches_category_id', $category->id)
            ->active()
            ->ordered()
            ->withCount(['fiches as published_fiches_count' => function ($query) {
                $query->where('is_published', true);
            }])
            ->get();

        return view('public.fiches.category', compact('category', 'fiches', 'sousCategories'));
    }

    /**
     * 🇬🇧 Display fiches from a specific sub-category
     * 🇫🇷 Afficher les fiches d'une sous-catégorie spécifique
     */
    public function sousCategory(FichesCategory $category, FichesSousCategory $sousCategory)
    {
        // 🇬🇧 Verify sous-category belongs to category / 🇫🇷 Vérifier que la sous-catégorie appartient à la catégorie
        if ($sousCategory->fiches_category_id !== $category->id) {
            abort(404, 'La sous-catégorie ne correspond pas à la catégorie.');
        }

        // 🇬🇧 Get published fiches from this sous-category / 🇫🇷 Récupérer les fiches publiées de cette sous-catégorie
        $fiches = Fiche::with(['category', 'sousCategory'])
            ->bySousCategory($sousCategory->id)
            ->published()
            ->ordered()
            ->paginate(12);

        return view('public.fiches.sous-category', compact('category', 'sousCategory', 'fiches'));
    }

    /**
     * 🇬🇧 Display a single fiche
     * 🇫🇷 Afficher une fiche individuelle
     */
    public function show(FichesCategory $category, FichesSousCategory $sousCategory, Fiche $fiche)
    {
        // 🇬🇧 Verify fiche belongs to category / 🇫🇷 Vérifier que la fiche appartient à la catégorie
        if ($fiche->fiches_category_id !== $category->id) {
            abort(404, 'La fiche ne correspond pas à la catégorie.');
        }

        // 🇬🇧 Verify fiche belongs to sous-category / 🇫🇷 Vérifier que la fiche appartient à la sous-catégorie
        if ($fiche->fiches_sous_category_id !== $sousCategory->id) {
            abort(404, 'La fiche ne correspond pas à la sous-catégorie.');
        }

        // 🇬🇧 Check if fiche is published / 🇫🇷 Vérifier si la fiche est publiée
        if (!$fiche->is_published && (!auth()->check() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('editor')))) {
            abort(404, 'Cette fiche n\'est pas disponible.');
        }

        // 🇬🇧 Load relationships / 🇫🇷 Charger les relations
        $fiche->load(['category', 'sousCategory', 'creator']);

        // 🇬🇧 Increment views count / 🇫🇷 Incrémenter le compteur de vues
        $fiche->incrementViews();

        // 🇬🇧 Get related fiches from same sous-category / 🇫🇷 Récupérer les fiches liées de la même sous-catégorie
        $relatedFiches = Fiche::with(['category', 'sousCategory'])
            ->bySousCategory($sousCategory->id)
            ->published()
            ->where('id', '!=', $fiche->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('public.fiches.show', compact('fiche', 'category', 'sousCategory', 'relatedFiches'));
    }
}