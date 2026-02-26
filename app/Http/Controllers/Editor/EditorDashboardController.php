<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Fiche;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EditorDashboardController extends Controller
{
    public function index(Request $request): View
    {
        if (!$request->user()->hasRole('editor') && !$request->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé. Vous devez avoir le rôle Éditeur.');
        }

        $user = $request->user();

        $stats = [
            'total_posts' => Post::count(),
            'my_posts' => Post::where('created_by', $user->id)->count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'my_draft_posts' => Post::where('created_by', $user->id)
                                   ->where('status', 'draft')
                                   ->count(),
            'total_fiches' => Fiche::count(),
            'my_fiches' => Fiche::where('created_by', $user->id)->count(),
            'total_videos' => Video::count(),
            'my_videos' => Video::where('created_by', $user->id)->count(),
        ];

        $drafts = Post::where('created_by', $user->id)
                     ->where('status', 'draft')
                     ->orderBy('updated_at', 'desc')
                     ->take(5)
                     ->get();

        $recentPosts = Post::where('created_by', $user->id)
                          ->orderBy('created_at', 'desc')
                          ->take(5)
                          ->get();

        $activity = Post::where('created_by', $user->id)
                       ->orderBy('updated_at', 'desc')
                       ->take(10)
                       ->get();

        return view('editor.dashboard', compact('stats', 'drafts', 'recentPosts', 'activity'));
    }
}