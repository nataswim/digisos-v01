<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Liens utiles : gestion des liens (administrateurs uniquement, routes admin.links.items.*).
 */
class LinkController extends Controller
{
    public function index(Request $request): View
    {
        $categories = LinkCategory::ordered()->withCount('links')->get();
        $categoryId = $request->integer('categorie') ?: null;
        $search = trim((string) $request->query('q', ''));

        $links = Link::with('category')
            ->when($categoryId, fn ($q) => $q->where('link_category_id', $categoryId))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")
                ->orWhere('keywords', 'like', "%{$search}%")))
            ->ordered()
            ->get();

        return view('admin.links.items.index', compact('links', 'categories', 'categoryId', 'search'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $categories = LinkCategory::ordered()->get();
        if ($categories->isEmpty()) {
            return redirect()->route('admin.links.categories.create')
                ->with('error', 'Créez d\'abord une catégorie (par exemple « Fédérations »).');
        }

        $categoryId = $request->integer('categorie') ?: $categories->first()->id;

        return view('admin.links.items.form', [
            'link' => new Link([
                'is_active' => true,
                'link_category_id' => $categoryId,
                'sort_order' => (int) Link::where('link_category_id', $categoryId)->max('sort_order') + 1,
            ]),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $link = Link::create($this->validated($request));

        return redirect()->route('admin.links.items.index', ['categorie' => $link->link_category_id])
            ->with('success', "Lien « {$link->title} » ajouté.");
    }

    public function edit(Link $link): View
    {
        return view('admin.links.items.form', [
            'link' => $link,
            'categories' => LinkCategory::ordered()->get(),
        ]);
    }

    public function update(Request $request, Link $link): RedirectResponse
    {
        $link->update($this->validated($request));

        return redirect()->route('admin.links.items.index', ['categorie' => $link->link_category_id])
            ->with('success', "Lien « {$link->title} » mis à jour.");
    }

    public function destroy(Link $link): RedirectResponse
    {
        $title = $link->title;
        $link->delete();

        return redirect()->route('admin.links.items.index')
            ->with('success', "Lien « {$title} » supprimé.");
    }

    private function validated(Request $request): array
    {
        // « ffnatation.fr » saisi sans https:// : on le complète
        foreach (['url', 'facebook_url', 'instagram_url'] as $champ) {
            $valeur = trim((string) $request->input($champ));
            if ($valeur !== '' && ! preg_match('#^https?://#i', $valeur)) {
                $request->merge([$champ => 'https://' . $valeur]);
            }
        }

        $data = $request->validate([
            'link_category_id' => ['required', 'integer', 'exists:link_categories,id'],
            'title' => ['required', 'string', 'max:150'],
            'url' => ['required', 'string', 'max:500', 'url:http,https'],
            'facebook_url' => ['nullable', 'string', 'max:500', 'url:http,https', 'regex:#^https?://([a-z0-9-]+\.)*(facebook\.com|fb\.com|fb\.me)(/|$)#i'],
            'instagram_url' => ['nullable', 'string', 'max:500', 'url:http,https', 'regex:#^https?://([a-z0-9-]+\.)*(instagram\.com|instagr\.am)(/|$)#i'],
            'image' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/)#i'],
            'description' => ['nullable', 'string', 'max:1000'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ], [
            'url.url' => 'L\'adresse du site doit être une adresse internet valide (ex. https://www.ffnatation.fr).',
            'facebook_url.regex' => 'Le lien Facebook doit être une adresse facebook.com (ex. https://www.facebook.com/ffnatation).',
            'instagram_url.regex' => 'Le lien Instagram doit être une adresse instagram.com (ex. https://www.instagram.com/ffnatation).',
            'facebook_url.url' => 'Le lien Facebook doit être une adresse internet valide.',
            'instagram_url.url' => 'Le lien Instagram doit être une adresse internet valide.',
            'image.regex' => 'L\'image doit être choisie dans la médiathèque ou être une adresse commençant par https://',
        ], [
            'link_category_id' => 'catégorie',
            'title' => 'titre',
            'url' => 'adresse du site',
            'facebook_url' => 'lien Facebook',
            'instagram_url' => 'lien Instagram',
            'keywords' => 'mots-clés',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
