<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class EditorPostController extends Controller
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
        $status = $request->input('status');
        $visibility = $request->input('visibility');
        $categoryId = $request->input('category');
        $featured = $request->input('featured');
        
        $query = Post::with(['category', 'tags', 'creator']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('intro', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($featured) {
            $query->where('is_featured', true);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15);

        $categories = Category::orderBy('name')->get();

        $stats = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'draft' => Post::where('status', 'draft')->count(),
            'public' => Post::where('visibility', 'public')->count(),
            'authenticated' => Post::where('visibility', 'authenticated')->count(),
            'featured' => Post::where('is_featured', true)->count(),
        ];

        return view('editor.posts.index', compact(
            'posts',
            'categories',
            'stats',
            'search',
            'status',
            'visibility',
            'categoryId',
            'featured'
        ));
    }

    public function create()
    {
        $this->checkEditorAccess();
        
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        return view('editor.posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        $this->checkEditorAccess();
        
        $data = $request->validated();
        
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }
        
        if (!empty($data['status']) && $data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        $data['created_by'] = auth()->id();
        $data['created_by_name'] = auth()->user()->name;
        
        $post = Post::create($data);
        
        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('editor.posts.edit', $post)
                ->with('success', 'Article créé avec succès. Vous pouvez continuer à l\'éditer.');
        }

        return redirect()->route('editor.posts.index')
            ->with('success', 'Article créé avec succès.');
    }

    public function show(Post $post)
    {
        $this->checkEditorAccess();
        
        $post->load(['category', 'tags', 'creator', 'updater']);
        
        return view('editor.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->checkEditorAccess();
        
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        $post->load(['category', 'tags']);
        
        return view('editor.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->checkEditorAccess();
        
        $data = $request->validated();
        
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }
        
        if (!empty($data['status']) && $data['status'] === 'published') {
            if (empty($data['published_at']) && $post->status !== 'published') {
                $data['published_at'] = now();
            }
        }
        
        $data['updated_by'] = auth()->id();
        
        $post->update($data);
        
        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('editor.posts.edit', $post)
                ->with('success', 'Article mis à jour avec succès.');
        }

        return redirect()->route('editor.posts.index')
            ->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Post $post)
    {
        $this->checkEditorAccess();
        
        $post->delete();

        return redirect()->route('editor.posts.index')
            ->with('success', 'Article supprimé avec succès.');
    }

    public function bulkAction(Request $request)
    {
        $this->checkEditorAccess();
        
        $validated = $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'post_ids' => 'required|array|min:1',
            'post_ids.*' => 'exists:posts,id'
        ]);

        $postIds = $validated['post_ids'];
        $action = $validated['action'];

        switch ($action) {
            case 'delete':
                Post::whereIn('id', $postIds)->delete();
                $message = count($postIds) . ' article(s) supprimé(s) avec succès.';
                break;

            case 'publish':
                Post::whereIn('id', $postIds)->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                $message = count($postIds) . ' article(s) publié(s) avec succès.';
                break;

            case 'unpublish':
                Post::whereIn('id', $postIds)->update(['status' => 'draft']);
                $message = count($postIds) . ' article(s) dépublié(s) avec succès.';
                break;

            case 'feature':
                Post::whereIn('id', $postIds)->update(['is_featured' => true]);
                $message = count($postIds) . ' article(s) mis en avant avec succès.';
                break;

            case 'unfeature':
                Post::whereIn('id', $postIds)->update(['is_featured' => false]);
                $message = count($postIds) . ' article(s) retirés de la mise en avant.';
                break;

            default:
                $message = 'Action non reconnue.';
        }

        return redirect()->route('editor.posts.index')
            ->with('success', $message);
    }
}