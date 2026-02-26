<?php

namespace App\Http\Controllers;

use App\Services\StatsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller de statistiques unique pour tous les rôles
 * 
 * @file app/Http/Controllers/StatsController.php
 */
class StatsController extends Controller
{
    protected $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Vérifier l'accès (authentifié uniquement)
     */
    private function checkAccess(): void
    {
        $user = auth()->user();
        
        if (!$user || !$user->role) {
            abort(403, 'Authentification requise');
        }
        
        // Seulement user, editor, admin (pas visitor)
        if ($user->hasRole('visitor')) {
            abort(403, 'Accès non autorisé - Rôle insuffisant');
        }
    }

    /**
     * Afficher les statistiques selon le rôle
     */
    public function index(Request $request): View
    {
        $this->checkAccess();
        
        $user = auth()->user();
        $period = $request->input('period', '30'); // 7, 30, 90 jours
        
        // ========== STATS GLOBALES (tous les rôles) ==========
        $globalStats = $this->statsService->getGlobalStats();
        $recentActivity = $this->statsService->getRecentActivity((int)$period);
        $statusBreakdownGlobal = $this->statsService->getStatusBreakdown();
        $dailyActivityGlobal = $this->statsService->getDailyActivity();
        
        // ========== MES STATS (editor + admin uniquement) ==========
        $myStats = null;
        $myRecentActivity = null;
        $myTopContent = null;
        $myStatusBreakdown = null;
        $myDailyActivity = null;
        
        if ($user->hasRole('editor') || $user->hasRole('admin')) {
            $myStats = $this->statsService->getMyStats($user->id);
            $myRecentActivity = $this->statsService->getMyRecentActivity($user->id, (int)$period);
            $myTopContent = $this->statsService->getMyTopContent($user->id, 5);
            $myStatusBreakdown = $this->statsService->getStatusBreakdown($user->id);
            $myDailyActivity = $this->statsService->getDailyActivity($user->id);
        }
        
        // ========== STATS UTILISATEURS (admin uniquement) ==========
        $usersStats = null;
        $topContributors = null;
        
        if ($user->hasRole('admin')) {
            $usersStats = $this->statsService->getUsersStats();
            $topContributors = $this->statsService->getTopContributors(5);
        }
        
        return view('stats.index', compact(
            'user',
            'period',
            'globalStats',
            'recentActivity',
            'statusBreakdownGlobal',
            'dailyActivityGlobal',
            'myStats',
            'myRecentActivity',
            'myTopContent',
            'myStatusBreakdown',
            'myDailyActivity',
            'usersStats',
            'topContributors'
        ));
    }

    /**
     * API JSON pour graphiques dynamiques
     */
    public function api(Request $request)
    {
        $this->checkAccess();
        
        $user = auth()->user();
        $type = $request->input('type'); // 'global' ou 'personal'
        $period = $request->input('period', 30);
        
        switch ($type) {
            case 'global':
                return response()->json([
                    'stats' => $this->statsService->getGlobalStats(),
                    'activity' => $this->statsService->getDailyActivity(),
                ]);
                
            case 'personal':
                if (!$user->hasRole('editor') && !$user->hasRole('admin')) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                
                return response()->json([
                    'stats' => $this->statsService->getMyStats($user->id),
                    'activity' => $this->statsService->getDailyActivity($user->id),
                ]);
                
            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
    }
}