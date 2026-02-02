<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    private function checkAdminAccess()
    {
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('editor')) {
            abort(403, 'Acces non autorise');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdminAccess();
        
        $search = $request->input('search');
        $status = $request->input('status');
        $visibility = $request->input('visibility');
        $categoryId = $request->input('category');
        
        $query = Post::with(['category', 'creator', 'tags']);

        // Filtrage par recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('intro', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('meta_keywords', 'like', "%{$search}%");
            });
        }

        // Filtrage par statut
        if ($status) {
            $query->where('status', $status);
        }

        // Filtrage par visibilite
        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        // Filtrage par categorie
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $posts = $query->orderBy('created_at', 'desc')
                      ->orderBy('order', 'asc')
                      ->paginate(15);

        $categories = Category::orderBy('name')->get();

        return view('admin.posts.index', compact(
            'posts', 
            'categories', 
            'search', 
            'status', 
            'visibility', 
            'categoryId'
        ));
    }

    public function create()
    {
        $this->checkAdminAccess();
        
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $tags = Tag::where('status', 'active')->orderBy('name')->get();
        
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        $this->checkAdminAccess();
        
        $data = $request->validated();
        
        // Generer le slug si pas fourni
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }
        
        // Definir la date de publication si le statut est publie
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        // Informations de creation
        $data['created_by'] = auth()->id();
        $data['created_by_name'] = auth()->user()->name;
        
        // Creer le post
        $post = Post::create($data);
        
        // Attacher les tags si fournis
        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('admin.posts.edit', $post)
                ->with('success', 'Article cree avec succes. Vous pouvez continuer A l\'editer.');
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Article cree avec succes.');
    }

    public function show(Post $post)
    {
        $this->checkAdminAccess();
        
        $post->load(['category', 'tags', 'creator', 'updater']);
        
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->checkAdminAccess();
        
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $tags = Tag::where('status', 'active')->orderBy('name')->get();
        
        $post->load(['category', 'tags']);
        
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->checkAdminAccess();
        
        $data = $request->validated();
        
        // Gerer le slug
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }
        
        // Gerer la date de publication
        if ($data['status'] === 'published') {
            if (empty($data['published_at']) && $post->status !== 'published') {
                // Premier passage de brouillon A publie
                $data['published_at'] = now();
            }
        } else {
            // Si on repasse en brouillon, garder la date de publication existante
            if ($post->published_at) {
                $data['published_at'] = $post->published_at;
            }
        }
        
        // Informations de modification
        $data['updated_by'] = auth()->id();
        
        // Mettre A jour le post
        $post->update($data);
        
        // Synchroniser les tags
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        } else {
            $post->tags()->detach();
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('admin.posts.edit', $post)
                ->with('success', 'Article mis A jour avec succes.');
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Article mis A jour avec succes.');
    }

    public function destroy(Post $post)
    {
        $this->checkAdminAccess();
        
        // Detacher les tags avant suppression
        $post->tags()->detach();
        
        // Soft delete
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Article supprime avec succes.');
    }

    /**
     * Dupliquer un article
     */
    public function duplicate(Post $post)
    {
        $this->checkAdminAccess();
        
        $newPost = $post->replicate();
        $newPost->name = $post->name . ' (Copie)';
        $newPost->slug = \Str::slug($newPost->name);
        $newPost->status = 'draft';
        $newPost->published_at = null;
        $newPost->hits = 0;
        $newPost->created_by = auth()->id();
        $newPost->created_by_name = auth()->user()->name;
        $newPost->updated_by = null;
        $newPost->save();
        
        // Dupliquer les tags
        $newPost->tags()->sync($post->tags->pluck('id'));

        return redirect()->route('admin.posts.edit', $newPost)
            ->with('success', 'Article duplique avec succes.');
    }

    /**
     * Changer le statut rapidement
     */
    public function toggleStatus(Post $post)
    {
        $this->checkAdminAccess();
        
        $newStatus = $post->status === 'published' ? 'draft' : 'published';
        
        $post->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : $post->published_at,
            'updated_by' => auth()->id()
        ]);

        $message = $newStatus === 'published' ? 'Article publie avec succes.' : 'Article mis en brouillon.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Changer la visibilite rapidement
     */
    public function toggleVisibility(Post $post)
    {
        $this->checkAdminAccess();
        
        $newVisibility = $post->visibility === 'public' ? 'authenticated' : 'public';
        
        $post->update([
            'visibility' => $newVisibility,
            'updated_by' => auth()->id()
        ]);

        $message = $newVisibility === 'public' 
            ? 'Article rendu public.' 
            : 'Article restreint aux membres.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Statistiques detaillees
     */
    public function stats()
    {
        $this->checkAdminAccess();
        
        $stats = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'drafts' => Post::where('status', 'draft')->count(),
            'public' => Post::where('visibility', 'public')->count(),
            'members_only' => Post::where('visibility', 'authenticated')->count(),
            'featured' => Post::where('is_featured', true)->count(),
            'total_views' => Post::sum('hits'),
            'by_category' => Post::selectRaw('category_id, count(*) as count')
                ->with('category:id,name')
                ->groupBy('category_id')
                ->get(),
            'recent' => Post::with(['category', 'creator'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'popular' => Post::where('status', 'published')
                ->orderBy('hits', 'desc')
                ->limit(10)
                ->get()
        ];

        return view('admin.posts.stats', compact('stats'));
    }

    /**
     * Export des articles
     */
    public function export(Request $request)
    {
        $this->checkAdminAccess();
        
        $format = $request->input('format', 'csv');
        
        $posts = Post::with(['category', 'creator', 'tags'])->get();
        
        if ($format === 'csv') {
            $filename = 'articles_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            return response()->stream(function() use ($posts) {
                $file = fopen('php://output', 'w');
                
                // En-têtes CSV
                fputcsv($file, [
                    'ID', 'Titre', 'Slug', 'Statut', 'Visibilite', 'Categorie', 
                    'Auteur', 'Vues', 'Mis en avant', 'Date creation', 'Date publication'
                ]);
                
                // Donnees
                foreach ($posts as $post) {
                    fputcsv($file, [
                        $post->id,
                        $post->name,
                        $post->slug,
                        $post->status,
                        $post->visibility,
                        $post->category->name ?? '',
                        $post->creator->name ?? '',
                        $post->hits,
                        $post->is_featured ? 'Oui' : 'Non',
                        $post->created_at->format('d/m/Y H:i'),
                        $post->published_at?->format('d/m/Y H:i') ?? ''
                    ]);
                }
                
                fclose($file);
            }, 200, $headers);
        }
        
        return redirect()->back()->with('error', 'Format d\'export non supporte.');
    }

    /**
     * Actions en lot
     */
    public function bulkAction(Request $request)
    {
        $this->checkAdminAccess();
        
        $action = $request->input('action');
        $postIds = $request->input('posts', []);
        
        if (empty($postIds)) {
            return redirect()->back()->with('error', 'Aucun article selectionne.');
        }
        
        $count = 0;
        
        switch ($action) {
            case 'publish':
                $count = Post::whereIn('id', $postIds)->update([
                    'status' => 'published',
                    'published_at' => now(),
                    'updated_by' => auth()->id()
                ]);
                $message = "{$count} article(s) publie(s).";
                break;
                
            case 'draft':
                $count = Post::whereIn('id', $postIds)->update([
                    'status' => 'draft',
                    'updated_by' => auth()->id()
                ]);
                $message = "{$count} article(s) mis en brouillon.";
                break;
                
            case 'make_public':
                $count = Post::whereIn('id', $postIds)->update([
                    'visibility' => 'public',
                    'updated_by' => auth()->id()
                ]);
                $message = "{$count} article(s) rendu(s) public(s).";
                break;
                
            case 'make_members':
                $count = Post::whereIn('id', $postIds)->update([
                    'visibility' => 'authenticated',
                    'updated_by' => auth()->id()
                ]);
                $message = "{$count} article(s) restreint(s) aux membres.";
                break;
                
            case 'feature':
                $count = Post::whereIn('id', $postIds)->update([
                    'is_featured' => true,
                    'updated_by' => auth()->id()
                ]);
                $message = "{$count} article(s) mis en avant.";
                break;
                
            case 'unfeature':
                $count = Post::whereIn('id', $postIds)->update([
                    'is_featured' => false,
                    'updated_by' => auth()->id()
                ]);
                $message = "{$count} article(s) retire(s) de la une.";
                break;
                
            case 'delete':
                foreach ($postIds as $postId) {
                    $post = Post::find($postId);
                    if ($post) {
                        $post->tags()->detach();
                        $post->delete();
                        $count++;
                    }
                }
                $message = "{$count} article(s) supprime(s).";
                break;
                
            default:
                return redirect()->back()->with('error', 'Action non reconnue.');
        }
        
        return redirect()->back()->with('success', $message);
    }

// ========== MÉTHODES PUBLIQUES ==========

    /**
     * Liste des posts publics
     */
    
    
    
    
public function indexPublic(Request $request)
{
    $search = $request->input('search');
    $category = $request->input('category'); // Renommer de categoryId à category
    
    $query = Post::with(['category', 'tags'])
        ->visibleTo(auth()->user())
        ->orderBy('published_at', 'desc');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('intro', 'like', "%{$search}%");
        });
    }

    if ($category) {
        $query->where('category_id', $category);
    }

    $posts = $query->paginate(12);
    $categories = Category::whereHas('posts', function($q) {
        $q->visibleTo(auth()->user());
    })->orderBy('name')->get();

    return view('public.index', compact('posts', 'categories', 'search', 'category'));
}

    public function showPublic(Post $post)
{
    // Vérification de la visibilité
    if ($post->status !== 'published') {
        abort(404);
    }

    // Vérifier les permissions
    $contentVisible = true;
    if ($post->visibility === 'authenticated' && !auth()->check()) {
        $contentVisible = false;
    }

    // Incrémenter le compteur de vues
    $post->increment('hits');

    // Articles récents
    $recentPosts = Post::where('status', 'published')
        ->where('id', '!=', $post->id)
        ->when($post->category_id, function ($query) use ($post) {
            return $query->where('category_id', $post->category_id);
        })
        ->whereNotNull('published_at')
        ->orderBy('published_at', 'desc')
        ->limit(6)
        ->get();

    // ✅ AJOUT: Récupérer les catégories
    $categories = Category::withCount([
            'posts' => function ($query) {
                $query->where('status', 'published')
                    ->whereNotNull('published_at');
            }
        ])
        ->having('posts_count', '>', 0)
        ->orderBy('name')
        ->get();

    // ✅ Passer 'categories' à la vue
    return view('public.show', compact('post', 'contentVisible', 'recentPosts', 'categories'));
}

    /**
     * Posts par catégorie
     */
    public function byCategory(Category $category)
    {
        $posts = Post::with(['category', 'tags'])
            ->where('category_id', $category->id)
            ->visibleTo(auth()->user())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('public.category', compact('category', 'posts'));
    }

    /**
     * Posts par tag
     */
    public function byTag(Tag $tag)
    {
        $posts = $tag->posts()
            ->with(['category', 'tags'])
            ->visibleTo(auth()->user())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('public.tag', compact('tag', 'posts'));
    }

}