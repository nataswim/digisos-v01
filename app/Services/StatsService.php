<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;
use App\Models\Fiche;
use App\Models\Video;
use App\Models\Media;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Service de statistiques centralisé
 * 
 * @file app/Services/StatsService.php
 */
class StatsService
{
    /**
     * Obtenir les statistiques globales du site
     */
    public function getGlobalStats(): array
    {
        return [
            // Contenus
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            
            'total_fiches' => Fiche::count(),
            'published_fiches' => Fiche::where('is_published', true)->count(),
            
            'total_videos' => Video::count(),
            'published_videos' => Video::where('is_published', true)->count(),
            
            'total_pages' => Page::count(),
            'published_pages' => Page::where('is_published', true)->count(),
            
            'total_media' => Media::count(),
            
            // Vues totales
            'total_post_views' => Post::sum('hits') ?? 0,
            'total_fiche_views' => Fiche::sum('views_count') ?? 0,
            'total_video_views' => Video::sum('views_count') ?? 0,
        ];
    }

    /**
     * Obtenir les statistiques personnelles d'un utilisateur
     */
    public function getMyStats(int $userId): array
    {
        return [
            // Mes contenus
            'my_posts' => Post::where('created_by', $userId)->count(),
            'my_published_posts' => Post::where('created_by', $userId)
                                       ->where('status', 'published')
                                       ->count(),
            'my_draft_posts' => Post::where('created_by', $userId)
                                   ->where('status', 'draft')
                                   ->count(),
            
            'my_fiches' => Fiche::where('created_by', $userId)->count(),
            'my_published_fiches' => Fiche::where('created_by', $userId)
                                          ->where('is_published', true)
                                          ->count(),
            
            'my_videos' => Video::where('created_by', $userId)->count(),
            'my_published_videos' => Video::where('created_by', $userId)
                                          ->where('is_published', true)
                                          ->count(),
            
            'my_pages' => Page::where('created_by', $userId)->count(),
            'my_published_pages' => Page::where('created_by', $userId)
                                        ->where('is_published', true)
                                        ->count(),
            
            'my_media' => Media::where('uploaded_by', $userId)->count(),
            
            // Mes vues totales
            'my_post_views' => Post::where('created_by', $userId)->sum('hits') ?? 0,
            'my_fiche_views' => Fiche::where('created_by', $userId)->sum('views_count') ?? 0,
            'my_video_views' => Video::where('created_by', $userId)->sum('views_count') ?? 0,
        ];
    }

    /**
     * Obtenir les statistiques utilisateurs (Admin uniquement)
     */
    public function getUsersStats(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            
            // Par rôle
            'admins' => User::whereHas('role', function($q) {
                $q->where('slug', 'admin');
            })->count(),
            
            'editors' => User::whereHas('role', function($q) {
                $q->where('slug', 'editor');
            })->count(),
            
            'users' => User::whereHas('role', function($q) {
                $q->where('slug', 'user');
            })->count(),
            
            'visitors' => User::whereHas('role', function($q) {
                $q->where('slug', 'visitor');
            })->count(),
        ];
    }

    /**
     * Obtenir l'activité récente
     */
    public function getRecentActivity(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);

        return [
            'posts_created' => Post::where('created_at', '>=', $startDate)->count(),
            'fiches_created' => Fiche::where('created_at', '>=', $startDate)->count(),
            'videos_created' => Video::where('created_at', '>=', $startDate)->count(),
            'pages_created' => Page::where('created_at', '>=', $startDate)->count(),
            'media_uploaded' => Media::where('created_at', '>=', $startDate)->count(),
            'users_registered' => User::where('created_at', '>=', $startDate)->count(),
        ];
    }

    /**
     * Obtenir l'activité récente personnelle
     */
    public function getMyRecentActivity(int $userId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);

        return [
            'my_posts_created' => Post::where('created_by', $userId)
                                     ->where('created_at', '>=', $startDate)
                                     ->count(),
            'my_fiches_created' => Fiche::where('created_by', $userId)
                                        ->where('created_at', '>=', $startDate)
                                        ->count(),
            'my_videos_created' => Video::where('created_by', $userId)
                                        ->where('created_at', '>=', $startDate)
                                        ->count(),
            'my_pages_created' => Page::where('created_by', $userId)
                                      ->where('created_at', '>=', $startDate)
                                      ->count(),
            'my_media_uploaded' => Media::where('uploaded_by', $userId)
                                        ->where('created_at', '>=', $startDate)
                                        ->count(),
        ];
    }

    /**
     * Obtenir le top des contributeurs
     */
    public function getTopContributors(int $limit = 5): array
    {
        return User::select('users.*')
            ->withCount(['posts', 'categories', 'tags'])
            ->orderBy('posts_count', 'desc')
            ->take($limit)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'posts_count' => $user->posts_count,
                    'role' => $user->role ? $user->role->display_name : 'N/A',
                ];
            })
            ->toArray();
    }

    /**
     * Obtenir mes contenus les plus vus
     */
    public function getMyTopContent(int $userId, int $limit = 5): array
    {
        return [
            'top_posts' => Post::where('created_by', $userId)
                              ->where('status', 'published')
                              ->orderBy('hits', 'desc')
                              ->take($limit)
                              ->get(),
            
            'top_fiches' => Fiche::where('created_by', $userId)
                                 ->where('is_published', true)
                                 ->orderBy('views_count', 'desc')
                                 ->take($limit)
                                 ->get(),
            
            'top_videos' => Video::where('created_by', $userId)
                                 ->where('is_published', true)
                                 ->orderBy('views_count', 'desc')
                                 ->take($limit)
                                 ->get(),
        ];
    }

    /**
     * Obtenir l'activité quotidienne (30 derniers jours)
     */
    public function getDailyActivity(int $userId = null): array
    {
        $data = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            
            if ($userId) {
                // Activité personnelle
                $data[] = [
                    'date' => $date,
                    'posts' => Post::where('created_by', $userId)
                                  ->whereDate('created_at', $date)
                                  ->count(),
                    'fiches' => Fiche::where('created_by', $userId)
                                    ->whereDate('created_at', $date)
                                    ->count(),
                    'videos' => Video::where('created_by', $userId)
                                    ->whereDate('created_at', $date)
                                    ->count(),
                ];
            } else {
                // Activité globale
                $data[] = [
                    'date' => $date,
                    'posts' => Post::whereDate('created_at', $date)->count(),
                    'fiches' => Fiche::whereDate('created_at', $date)->count(),
                    'videos' => Video::whereDate('created_at', $date)->count(),
                ];
            }
        }
        
        return $data;
    }

    /**
     * Obtenir la répartition par statut
     */
    public function getStatusBreakdown(int $userId = null): array
    {
        if ($userId) {
            // Répartition personnelle
            $myPublishedPosts = Post::where('created_by', $userId)->where('status', 'published')->count();
            $myDraftPosts = Post::where('created_by', $userId)->where('status', 'draft')->count();
            $myPublishedFiches = Fiche::where('created_by', $userId)->where('is_published', true)->count();
            $myTotalFiches = Fiche::where('created_by', $userId)->count();
            $myPublishedVideos = Video::where('created_by', $userId)->where('is_published', true)->count();
            $myTotalVideos = Video::where('created_by', $userId)->count();

            return [
                'posts' => [
                    'published' => $myPublishedPosts,
                    'draft' => $myDraftPosts,
                ],
                'fiches' => [
                    'published' => $myPublishedFiches,
                    'draft' => $myTotalFiches - $myPublishedFiches,
                ],
                'videos' => [
                    'published' => $myPublishedVideos,
                    'draft' => $myTotalVideos - $myPublishedVideos,
                ],
            ];
        } else {
            // Répartition globale
            return [
                'posts' => [
                    'published' => Post::where('status', 'published')->count(),
                    'draft' => Post::where('status', 'draft')->count(),
                ],
                'fiches' => [
                    'published' => Fiche::where('is_published', true)->count(),
                    'draft' => Fiche::where('is_published', false)->count(),
                ],
                'videos' => [
                    'published' => Video::where('is_published', true)->count(),
                    'draft' => Video::where('is_published', false)->count(),
                ],
            ];
        }
    }
}