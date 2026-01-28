<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;


class PublicController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $tag = $request->input('tag');
        
        // Tous les posts publies sont listes (metadonnees visibles)
        $query = Post::with(['category', 'tags'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        // Recherche dans titre et intro (toujours visibles)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('intro', 'like', "%{$search}%")
                  ->orWhere('meta_keywords', 'like', "%{$search}%");
            });
        }

        // Filtrage par categorie
        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // Filtrage par tag
        if ($tag) {
            $query->whereHas('tags', function($q) use ($tag) {
                $q->where('slug', $tag);
            });
        }

        $posts = $query->orderBy('published_at', 'desc')
                      ->orderBy('order', 'asc')
                      ->paginate(12);

        // Recuperer les categories et tags actifs pour les filtres
        $categories = Category::where('status', 'active')
                             ->withCount('posts')
                             ->orderBy('order', 'asc')
                             ->orderBy('name', 'asc')
                             ->get();

        $tags = Tag::where('status', 'active')
                   ->withCount('posts')
                   ->orderBy('name', 'asc')
                   ->limit(20)
                   ->get();

        return view('public.index', compact('posts', 'categories', 'tags', 'search', 'category', 'tag'));
    }

    public function show(Post $post)
{
    // Verifier si le post est publie (metadonnees visibles)
    if (!$post->isMetadataVisible()) {
        abort(404, 'Article non trouve.');
    }

    // Incrementer les vues pour tous les visiteurs (même si contenu restreint)
    $post->increment('hits');

    // Charger les relations necessaires
    $post->load(['category', 'tags', 'creator']);

    // Determiner si le contenu complet est visible
    $contentVisible = $post->isContentVisibleTo(auth()->user());

    // Articles similaires (même categorie, metadonnees visibles)
    $relatedPosts = collect();
    if ($post->category_id) {
        $relatedPosts = Post::where('status', 'published')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();
    }

    // Articles populaires si pas assez d'articles similaires
    if ($relatedPosts->count() < 3) {
        $popularPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('hits', 'desc')
            ->limit(4 - $relatedPosts->count())
            ->get();
        
        $relatedPosts = $relatedPosts->merge($popularPosts);
    }

    // Posts recents pour la sidebar
    $recentPosts = Post::where('status', 'published')
        ->where('id', '!=', $post->id)
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->latest()
        ->take(5)
        ->get();

    return view('public.show', compact('post', 'relatedPosts', 'contentVisible', 'recentPosts'));
}
    public function about()
    {
        return view('public.about');
    }

 public function accessibility()
    {
        return view('public.accessibility');
    }

public function cookies()
{
    return view('public.cookies');
}
public function features()
{
    return view('public.features');
}
public function legal()
{
    return view('public.legal');
}
public function privacy()
{
    return view('public.privacy');
}
public function pricing()
{
    return view('public.pricing');
}
public function guide()
{
    return view('public.guide');
}
public function guideplanif()
{
    return view('public.guideplanif');
}
public function guidecarnet()
{
    return view('public.guidecarnet');
}
    /**
     * Afficher le formulaire de contact
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
 * Traiter l'envoi du formulaire de contact
 */
public function contactSend(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255', // Validation simplifiée
        'phone' => 'nullable|string|max:20',
        'subject' => 'required|in:information,support,partnership,billing,other',
        'message' => 'required|string|min:20|max:5000',
    ], [
        'first_name.required' => 'Le prénom est requis.',
        'last_name.required' => 'Le nom est requis.',
        'email.required' => 'L\'email est requis.',
        'email.email' => 'L\'email doit être valide.',
        'subject.required' => 'Le sujet est requis.',
        'subject.in' => 'Le sujet sélectionné n\'est pas valide.',
        'message.required' => 'Le message est requis.',
        'message.min' => 'Le message doit contenir au moins 20 caractères.',
        'message.max' => 'Le message ne peut pas dépasser 5000 caractères.',
    ]);

    // Préparer les données avec le libellé du sujet
    $subjectLabels = [
        'information' => 'Demande d\'information',
        'support' => 'Support technique',
        'partnership' => 'Partenariat',
        'billing' => 'Facturation',
        'other' => 'Autre'
    ];

    $contactData = array_merge($validated, [
        'subject_label' => $subjectLabels[$validated['subject']]
    ]);

    // Envoyer l'email
    try {
        Mail::to('natation.swimming@gmail.com')
            ->send(new ContactFormMail($contactData));

        return redirect()->route('contact')
            ->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
            
    } catch (\Exception $e) {
        // Logger l'erreur pour debug
        \Log::error('Erreur envoi email contact: ' . $e->getMessage());
        
        return back()
            ->withInput()
            ->with('error', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer ultérieurement.');
    }
}

    public function home()
    {
        // Page d'accueil avec articles mis en avant
        $featuredPosts = Post::where('status', 'published')
            ->where('is_featured', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        // Articles recents
        $recentPosts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->limit(8)
            ->get();

        // Categories populaires
        $popularCategories = Category::where('status', 'active')
            ->withCount(['posts' => function($query) {
                $query->where('status', 'published')
                      ->whereNotNull('published_at')
                      ->where('published_at', '<=', now());
            }])
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit(6)
            ->get();

        return view('public.home', compact('featuredPosts', 'recentPosts', 'popularCategories'));
    }



/**
 * Afficher toutes les catégories
 */
public function categories()
{
    // Récupérer toutes les catégories actives avec le nombre d'articles publiés
    $categories = Category::where('status', 'active')
        ->withCount([
            'posts' => function($query) {
                $query->where('status', 'published')
                      ->whereNotNull('published_at')
                      ->where('published_at', '<=', now());
            }
        ])
        ->with([
            'posts' => function($query) {
                $query->where('status', 'published')
                      ->whereNotNull('published_at')
                      ->where('published_at', '<=', now())
                      ->orderBy('published_at', 'desc')
                      ->limit(4);
            }
        ])
        ->orderBy('order', 'asc')
        ->orderBy('name', 'asc')
        ->get();

    return view('public.index', compact('categories'));
}



    /**
     * Recherche avancee
     */
    public function search(Request $request)
    {
        $search = $request->input('q');
        $category_id = $request->input('category_id');
        $tag_id = $request->input('tag_id');
        
        if (empty($search) && empty($category_id) && empty($tag_id)) {
            return redirect()->route('public.index');
        }

        $query = Post::with(['category', 'tags'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('intro', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('meta_keywords', 'like', "%{$search}%");
            });
        }

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        if ($tag_id) {
            $query->whereHas('tags', function($q) use ($tag_id) {
                $q->where('tags.id', $tag_id);
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(12);
        
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $tags = Tag::where('status', 'active')->orderBy('name')->get();

        return view('public.search', compact('posts', 'categories', 'tags', 'search', 'category_id', 'tag_id'));
    }

    /**
     * Flux RSS
     */
    public function rss()
    {
        $posts = Post::where('status', 'published')
            ->where('visibility', 'public') // Seulement les posts publics dans le RSS
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category'])
            ->orderBy('published_at', 'desc')
            ->limit(20)
            ->get();

        return response()->view('public.rss', compact('posts'))
            ->header('Content-Type', 'application/rss+xml');
    }

    /**
     * Sitemap XML
     */
    public function sitemap()
    {
        $posts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = Category::where('status', 'active')
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->view('public.sitemap', compact('posts', 'categories'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Affichage par categorie
     */
    public function category(Category $category)
    {
        if ($category->status !== 'active') {
            abort(404, 'Categorie non trouvee.');
        }

        $posts = Post::where('status', 'published')
            ->where('category_id', $category->id)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('public.category', compact('category', 'posts'));
    }

    /**
     * Affichage par tag
     */
    public function tag(Tag $tag)
    {
        if ($tag->status !== 'active') {
            abort(404, 'Tag non trouve.');
        }

        $posts = $tag->posts()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('public.tag', compact('tag', 'posts'));
    }

    /**
     * API pour la recherche auto-complete
     */
    public function searchSuggestions(Request $request)
    {
        $query = $request->input('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $posts = Post::where('status', 'published')
            ->where('name', 'like', "%{$query}%")
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->select('name', 'slug')
            ->limit(10)
            ->get();

        return response()->json($posts);
    }
}