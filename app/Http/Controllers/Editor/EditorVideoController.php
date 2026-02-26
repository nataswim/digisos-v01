<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Services\VideoService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;

/**
 * EditorVideoController - Gestion des vidéos pour les éditeurs
 * Les éditeurs peuvent voir, créer, modifier et supprimer TOUTES les vidéos
 * 
 * @file app/Http/Controllers/Editor/EditorVideoController.php
 */
class EditorVideoController extends Controller
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    /**
     * Vérifier que l'utilisateur a le rôle éditeur
     */
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

    /**
     * Liste de toutes les vidéos
     */
    public function index(Request $request)
    {
        $this->checkEditorAccess();
        
        $search = $request->input('search');
        $type = $request->input('type');
        $visibility = $request->input('visibility');
        $categoryId = $request->input('category');
        $featured = $request->input('featured');
        
        $query = Video::with(['categories', 'creator']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        if ($categoryId) {
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('video_categories.id', $categoryId);
            });
        }

        if ($featured) {
            $query->where('is_featured', true);
        }

        $videos = $query->orderBy('created_at', 'desc')
                       ->orderBy('sort_order', 'asc')
                       ->paginate(15);

        $categories = VideoCategory::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total' => Video::count(),
            'published' => Video::where('is_published', true)->count(),
            'draft' => Video::where('is_published', false)->count(),
            'upload' => Video::where('type', 'upload')->count(),
            'external' => Video::whereIn('type', ['url', 'youtube', 'vimeo', 'dailymotion'])->count(),
            'featured' => Video::where('is_featured', true)->count(),
        ];

        return view('editor.videos.index', compact(
            'videos',
            'categories',
            'stats',
            'search',
            'type',
            'visibility',
            'categoryId',
            'featured'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $this->checkEditorAccess();
        
        $categories = VideoCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('editor.videos.create', compact('categories'));
    }

    /**
     * Enregistrer une nouvelle vidéo
     */
    public function store(StoreVideoRequest $request)
    {
        $this->checkEditorAccess();
        
        $data = $request->validated();
        
        // Gestion de l'upload
        if ($request->hasFile('file')) {
            $uploadData = $this->videoService->uploadVideo($request->file('file'));
            $data = array_merge($data, $uploadData);
        }
        // Gestion depuis la bibliothèque
        elseif ($request->filled('library_file_path')) {
            $data['type'] = 'upload';
            $data['file_path'] = $request->input('library_file_path');
            $data['file_size'] = $request->input('library_file_size');
            $data['mime_type'] = $request->input('library_mime_type');
        }
        
        // Gestion des métadonnées externes
        if (in_array($data['type'], ['youtube', 'vimeo', 'dailymotion']) && !empty($data['external_url'])) {
            $metadata = null;
            
            switch ($data['type']) {
                case 'youtube':
                    $metadata = $this->videoService->getYoutubeMetadata($data['external_url']);
                    break;
                case 'vimeo':
                    $metadata = $this->videoService->getVimeoMetadata($data['external_url']);
                    break;
                case 'dailymotion':
                    $metadata = $this->videoService->getDailymotionMetadata($data['external_url']);
                    break;
            }
            
            if ($metadata) {
                $data['external_id'] = $metadata['external_id'] ?? null;
                $data['thumbnail'] = $data['thumbnail'] ?? $metadata['thumbnail'] ?? null;
                $data['width'] = $data['width'] ?? $metadata['width'] ?? null;
                $data['height'] = $data['height'] ?? $metadata['height'] ?? null;
                $data['duration'] = $data['duration'] ?? $metadata['duration'] ?? null;
            }
        }
        
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }
        
        if (!empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        $video = Video::create($data);
        
        if ($request->has('categories')) {
            $video->categories()->sync($request->input('categories'));
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('editor.videos.edit', $video)
                ->with('success', 'Vidéo créée avec succès. Vous pouvez continuer à l\'éditer.');
        }

        return redirect()->route('editor.videos.index')
            ->with('success', 'Vidéo créée avec succès.');
    }

    /**
     * Afficher une vidéo
     */
    public function show(Video $video)
    {
        $this->checkEditorAccess();
        
        $video->load(['categories', 'creator', 'updater']);
        
        return view('editor.videos.show', compact('video'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Video $video)
    {
        $this->checkEditorAccess();
        
        $categories = VideoCategory::where('is_active', true)->orderBy('name')->get();
        $video->load('categories');
        
        return view('editor.videos.edit', compact('video', 'categories'));
    }

    /**
     * Mettre à jour une vidéo
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        $this->checkEditorAccess();
        
        $data = $request->validated();
        
        // Gestion nouvel upload
        if ($request->hasFile('file')) {
            if ($video->file_path) {
                $this->videoService->deleteVideo($video->file_path);
            }
            
            $uploadData = $this->videoService->uploadVideo($request->file('file'));
            $data = array_merge($data, $uploadData);
        }
        // Gestion depuis la bibliothèque
        elseif ($request->filled('library_file_path')) {
            if ($video->file_path && $video->file_path !== $request->input('library_file_path')) {
                $this->videoService->deleteVideo($video->file_path);
            }
            
            $data['type'] = 'upload';
            $data['file_path'] = $request->input('library_file_path');
            $data['file_size'] = $request->input('library_file_size');
            $data['mime_type'] = $request->input('library_mime_type');
        }
        
        // Mise à jour métadonnées externes
        if (in_array($data['type'], ['youtube', 'vimeo', 'dailymotion']) && 
            !empty($data['external_url']) && 
            $data['external_url'] !== $video->external_url) {
            
            $metadata = null;
            
            switch ($data['type']) {
                case 'youtube':
                    $metadata = $this->videoService->getYoutubeMetadata($data['external_url']);
                    break;
                case 'vimeo':
                    $metadata = $this->videoService->getVimeoMetadata($data['external_url']);
                    break;
                case 'dailymotion':
                    $metadata = $this->videoService->getDailymotionMetadata($data['external_url']);
                    break;
            }
            
            if ($metadata) {
                $data['external_id'] = $metadata['external_id'] ?? $video->external_id;
                if (empty($data['thumbnail'])) {
                    $data['thumbnail'] = $metadata['thumbnail'] ?? $video->thumbnail;
                }
                if (empty($data['width'])) {
                    $data['width'] = $metadata['width'] ?? $video->width;
                }
                if (empty($data['height'])) {
                    $data['height'] = $metadata['height'] ?? $video->height;
                }
                if (empty($data['duration'])) {
                    $data['duration'] = $metadata['duration'] ?? $video->duration;
                }
            }
        }
        
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }
        
        if (!empty($data['is_published'])) {
            if (empty($data['published_at']) && !$video->is_published) {
                $data['published_at'] = now();
            }
        }
        
        $video->update($data);
        
        if ($request->has('categories')) {
            $video->categories()->sync($request->input('categories'));
        }

        $action = $request->input('action', 'save');
        
        if ($action === 'save_and_continue') {
            return redirect()->route('editor.videos.edit', $video)
                ->with('success', 'Vidéo mise à jour avec succès.');
        }

        return redirect()->route('editor.videos.index')
            ->with('success', 'Vidéo mise à jour avec succès.');
    }

    /**
     * Supprimer une vidéo
     */
    public function destroy(Video $video)
    {
        $this->checkEditorAccess();
        
        if ($video->type === 'upload' && $video->file_path) {
            $this->videoService->deleteVideo($video->file_path);
        }
        
        $video->delete();

        return redirect()->route('editor.videos.index')
            ->with('success', 'Vidéo supprimée avec succès.');
    }

    /**
     * Actions groupées
     */
    public function bulkAction(Request $request)
    {
        $this->checkEditorAccess();
        
        $validated = $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'video_ids' => 'required|array|min:1',
            'video_ids.*' => 'exists:videos,id'
        ]);

        $videoIds = $validated['video_ids'];
        $action = $validated['action'];

        switch ($action) {
            case 'delete':
                $videos = Video::whereIn('id', $videoIds)->get();
                foreach ($videos as $video) {
                    if ($video->type === 'upload' && $video->file_path) {
                        $this->videoService->deleteVideo($video->file_path);
                    }
                    $video->delete();
                }
                $message = count($videoIds) . ' vidéo(s) supprimée(s) avec succès.';
                break;

            case 'publish':
                Video::whereIn('id', $videoIds)->update([
                    'is_published' => true,
                    'published_at' => now()
                ]);
                $message = count($videoIds) . ' vidéo(s) publiée(s) avec succès.';
                break;

            case 'unpublish':
                Video::whereIn('id', $videoIds)->update(['is_published' => false]);
                $message = count($videoIds) . ' vidéo(s) dépubliée(s) avec succès.';
                break;

            case 'feature':
                Video::whereIn('id', $videoIds)->update(['is_featured' => true]);
                $message = count($videoIds) . ' vidéo(s) mise(s) en avant avec succès.';
                break;

            case 'unfeature':
                Video::whereIn('id', $videoIds)->update(['is_featured' => false]);
                $message = count($videoIds) . ' vidéo(s) retirée(s) de la mise en avant.';
                break;

            default:
                $message = 'Action non reconnue.';
        }

        return redirect()->route('editor.videos.index')
            ->with('success', $message);
    }
}
