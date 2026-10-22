<?php

namespace App\Http\Controllers;

use App\Models\LinkCategory;
use Illuminate\Contracts\View\View;

/**
 * Page publique « Liens utiles » (/liens-utiles) : les liens, rangés par catégorie.
 */
class PublicLinkController extends Controller
{
    public function index(): View
    {
        $categories = LinkCategory::active()
            ->ordered()
            ->with(['links' => fn ($q) => $q->where('is_active', true)->ordered()])
            ->get()
            ->filter(fn ($category) => $category->links->isNotEmpty())
            ->values();

        return view('public.links.index', compact('categories'));
    }
}
