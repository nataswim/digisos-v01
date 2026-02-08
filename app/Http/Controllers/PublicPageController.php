<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PagesCategory;
use Illuminate\Http\Request;

/**
 * 🇬🇧 PublicPageController - Public display of pages
 * 🇫🇷 PublicPageController - Affichage public des pages
 * 
 * @file app/Http/Controllers/PublicPageController.php
 */
class PublicPageController extends Controller
{
    /**
     * 🇬🇧 Display list of all categories
     * 🇫🇷 Afficher la liste de toutes les catégories
     */
    public function index()
    {
        $categories = PagesCategory::active()
            ->ordered()
            ->withCount(['pages as published_pages_count' => function ($query) {
                $query->where('is_published', true);
            }])
            ->get();

        return view('public.pages.index', compact('categories'));
    }

    /**
     * 🇬🇧 Display pages from a specific category
     * 🇫🇷 Afficher les pages d'une catégorie spécifique
     */
    public function category(PagesCategory $category)
    {
        $pages = Page::with(['category'])
            ->byCategory($category->id)
            ->published()
            ->ordered()
            ->paginate(12);

        return view('public.pages.category', compact('category', 'pages'));
    }

    /**
     * 🇬🇧 Display a single page
     * 🇫🇷 Afficher une page individuelle
     */
    public function show($categorySlug = null, $pageSlug = null)
    {
        // 🇬🇧 Handle both routes: /pages/{page} and /pages/{category}/{page}
        // 🇫🇷 Gérer les deux routes : /pages/{page} et /pages/{category}/{page}
        
        if ($pageSlug === null) {
            $pageSlug = $categorySlug;
            $categorySlug = null;
        }

        $query = Page::where('slug', $pageSlug);

        if ($categorySlug) {
            $category = PagesCategory::where('slug', $categorySlug)->firstOrFail();
            $query->where('pages_category_id', $category->id);
        }

        $page = $query->firstOrFail();

        if (!$page->is_published && (!auth()->check() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('editor')))) {
            abort(404, 'Cette page n\'est pas disponible.');
        }

        $page->load(['category', 'creator']);

        return view('public.pages.show', compact('page'));
    }
}
