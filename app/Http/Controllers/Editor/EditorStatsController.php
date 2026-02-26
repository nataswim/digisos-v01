<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Fiche;
use App\Models\Video;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * EditorStatsController - Statistiques pour les éditeurs
 * 
 * @file app/Http/Controllers/Editor/EditorStatsController.php
 */
class EditorStatsController extends Controller
{
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
     * Afficher les statistiques de l'éditeur
     */
    public function index(Request $request): View
    {
        $this->checkEditorAccess();
        
        $user = auth()->user();
        $period = $request->input('period', '30'); // 7, 30, 90, 365 jours
        
        $startDate = Carbon::now()->subDays($period);
        
        // ========== STATISTIQUES GLOBALES ==========
        $globalStats = [
            // Posts
            'total_posts' => Post::count(),
            'my_posts' => Post::where('created_by', $user->id)->count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'my_published_posts' => Post::where('created_by', $user->id)
                                       ->where('status', 'published')
                                       ->count(),
            'my_draft_posts' => Post::where('created_by', $user->id)
                                   ->where('status', 'draft')
                                   ->count(),
            
            // Fiches
            'total_fiches' => Fiche::count(),
            'my_fiches' => Fiche::where('created_by', $user->id)->count(),
            'published_fiches' => Fiche::where('is_published', true)->count(),
            'my_published_fiches' => Fiche::where('created_by', $user->id)
                                          ->where('is_published', true)
                                          ->count(),
            
            // Vidéos
            'total_videos' => Video::count(),
            'my_videos' => Video::where('created_by', $user->id)->count(),
            'published_videos' => Video::where('is_published', true)->count(),
            'my_published_videos' => Video::where('created_by', $user->id)
                                          ->where('is_published', true)
                                          ->count(),
            
            // Médias
            'total_media' => Media::count(),
            'my_media' => Media::where('uploaded_by', $user->id)->count(),
        ];
        
        // ========== VUES TOTALES ==========
        $viewsStats = [
            'total_post_views' => Post::sum('hits') ?? 0,
            'my_post_views' => Post::where('created_by', $user->id)->sum('hits') ?? 0,
            'total_fiche_views' => Fiche::sum('views_count') ?? 0,
            'my_fiche_views' => Fiche::where('created_by', $user->id)->sum('views_count') ?? 0,
            'total_video_views' => Video::sum('views_count') ?? 0,
            'my_video_views' => Video::where('created_by', $user->id)->sum('views_count') ?? 0,
        ];
        
        // ========== CONTENUS RÉCENTS (période) ==========
        $recentStats = [
            'posts_created' => Post::where('created_at', '>=', $startDate)->count(),
            'my_posts_created' => Post::where('created_by', $user->id)
                                     ->where('created_at', '>=', $startDate)
                                     ->count(),
            
            'fiches_created' => Fiche::where('created_at', '>=', $startDate)->count(),
            'my_fiches_created' => Fiche::where('created_by', $user->id)
                                        ->where('created_at', '>=', $startDate)
                                        ->count(),
            
            'videos_created' => Video::where('created_at', '>=', $startDate)->count(),
            'my_videos_created' => Video::where('created_by', $user->id)
                                        ->where('created_at', '>=', $startDate)
                                        ->count(),
        ];
        
        // ========== TOP 5 CONTENUS LES PLUS VUS (créés par l'éditeur) ==========
        $myTopPosts = Post::where('created_by', $user->id)
                         ->where('status', 'published')
                         ->orderBy('hits', 'desc')
                         ->take(5)
                         ->get();
        
        $myTopFiches = Fiche::where('created_by', $user->id)
                           ->where('is_published', true)
                           ->orderBy('views_count', 'desc')
                           ->take(5)
                           ->get();
        
        $myTopVideos = Video::where('created_by', $user->id)
                           ->where('is_published', true)
                           ->orderBy('views_count', 'desc')
                           ->take(5)
                           ->get();
        
        // ========== ACTIVITÉ PAR JOUR (derniers 30 jours) ==========
        $activityData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $activityData[] = [
                'date' => $date,
                'posts' => Post::where('created_by', $user->id)
                              ->whereDate('created_at', $date)
                              ->count(),
                'fiches' => Fiche::where('created_by', $user->id)
                                ->whereDate('created_at', $date)
                                ->count(),
                'videos' => Video::where('created_by', $user->id)
                                ->whereDate('created_at', $date)
                                ->count(),
            ];
        }
        
        // ========== RÉPARTITION PAR STATUT ==========
        $statusBreakdown = [
            'posts' => [
                'published' => $globalStats['my_published_posts'],
                'draft' => $globalStats['my_draft_posts'],
            ],
            'fiches' => [
                'published' => $globalStats['my_published_fiches'],
                'draft' => $globalStats['my_fiches'] - $globalStats['my_published_fiches'],
            ],
            'videos' => [
                'published' => $globalStats['my_published_videos'],
                'draft' => $globalStats['my_videos'] - $globalStats['my_published_videos'],
            ],
        ];
        
        return view('editor.stats', compact(
            'globalStats',
            'viewsStats',
            'recentStats',
            'myTopPosts',
            'myTopFiches',
            'myTopVideos',
            'activityData',
            'statusBreakdown',
            'period'
        ));
    }
}