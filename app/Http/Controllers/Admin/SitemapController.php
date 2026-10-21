<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitemapUrl;
use App\Services\SitemapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SitemapController extends Controller
{
    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Afficher la liste des URLs
     */
    public function index(Request $request)
    {
        $query = SitemapUrl::query();

        // Filtres
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('approved') && $request->approved !== 'all') {
            $query->where('is_approved', $request->approved === 'true');
        }

        if ($request->filled('source') && $request->source !== 'all') {
            $query->where('source', $request->source);
        }

        if ($request->filled('search')) {
            $query->where('url', 'like', '%' . $request->search . '%');
        }

        // Tri (colonnes autorisées seulement)
        $orderBy = in_array($request->get('order_by'), ['url', 'priority', 'changefreq', 'last_modified', 'created_at'], true)
            ? $request->get('order_by') : 'priority';
        $orderDirection = $request->get('order_direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($orderBy, $orderDirection)->orderBy('url');

        // Pagination
        $urls = $query->paginate(50)->withQueryString();
        
        // Statistiques
        $statistics = $this->sitemapService->getStatistics();

        return view('admin.sitemap.index', compact('urls', 'statistics'));
    }

    /**
     * Découvrir les URLs
     */
    public function discover()
    {
        try {
            $stats = $this->sitemapService->discoverUrls();

            $message = "Découverte terminée : {$stats['total']} adresses trouvées ({$stats['static']} pages fixes, {$stats['dynamic']} contenus), "
                . "dont {$stats['new']} nouvelles à vérifier.";
            if ($stats['removed'] > 0) {
                $message .= " {$stats['removed']} adresses qui n'existent plus ont été retirées.";
            }
            if ($stats['errors']) {
                return redirect()->route('admin.sitemap.index')->with('error', $message
                    . ' Attention, ces modules n\'ont pas pu être lus : ' . implode(', ', $stats['errors']) . ' (détail dans storage/logs/laravel.log).');
            }

            return redirect()
                ->route('admin.sitemap.index')
                ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Sitemap discovery error: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.sitemap.index')
                ->with('error', 'Erreur lors de la découverte : ' . $e->getMessage());
        }
    }

    /**
     * Générer le sitemap XML
     */
    public function generate()
    {
        try {
            $result = $this->sitemapService->generateXml();
            
            return redirect()
                ->route('admin.sitemap.index')
                ->with('success', "Sitemap généré avec succès : {$result['urls_count']} URLs incluses.");
        } catch (\Throwable $e) {
            Log::error('Sitemap generation error: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.sitemap.index')
                ->with('error', 'Erreur lors de la génération : ' . $e->getMessage());
        }
    }

    /**
     * Ajouter une URL manuelle
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url:http,https|max:500|unique:sitemap_urls,url',
            'priority' => 'required|numeric|between:0,1',
            'changefreq' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never'
        ]);

        try {
            SitemapUrl::create([
                'url' => $request->url,
                'type' => 'manual',
                'priority' => $request->priority,
                'changefreq' => $request->changefreq,
                'is_approved' => true,
                'is_active' => true,
                'last_modified' => now()
            ]);

            return redirect()
                ->route('admin.sitemap.index')
                ->with('success', 'URL ajoutée avec succès.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.sitemap.index')
                ->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour une URL
     */
    public function update(Request $request, SitemapUrl $sitemapUrl)
    {
        $request->validate([
            'priority' => 'sometimes|numeric|between:0,1',
            'changefreq' => 'sometimes|in:always,hourly,daily,weekly,monthly,yearly,never',
            'is_approved' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean'
        ]);

        try {
            $sitemapUrl->update($request->only([
                'priority',
                'changefreq',
                'is_approved',
                'is_active'
            ]));

            return redirect()
                ->route('admin.sitemap.index')
                ->with('success', 'URL mise à jour avec succès.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.sitemap.index')
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Approuver/Désapprouver en masse
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'url_ids' => 'required|array',
            'url_ids.*' => 'exists:sitemap_urls,id',
            'approved' => 'required|boolean'
        ]);

        try {
            $updated = SitemapUrl::whereIn('id', $request->url_ids)
                ->update(['is_approved' => $request->approved]);

            $message = $request->approved 
                ? "{$updated} URLs approuvées avec succès."
                : "{$updated} URLs désapprouvées avec succès.";

            return redirect()
                ->route('admin.sitemap.index')
                ->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.sitemap.index')
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Basculer l'approbation
     */
    public function toggleApproval(SitemapUrl $sitemapUrl)
    {
        try {
            $sitemapUrl->update([
                'is_approved' => !$sitemapUrl->is_approved
            ]);

            $message = $sitemapUrl->is_approved 
                ? 'URL approuvée avec succès.'
                : 'URL désapprouvée avec succès.';

            return redirect()
                ->route('admin.sitemap.index')
                ->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.sitemap.index')
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une URL
     */
    public function destroy(SitemapUrl $sitemapUrl)
    {
        try {
            $sitemapUrl->delete();

            return redirect()
                ->route('admin.sitemap.index')
                ->with('success', 'URL supprimée avec succès.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.sitemap.index')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Nettoyer toutes les URLs
     */
    public function clean()
    {
        try {
            $deleted = SitemapUrl::count();
            SitemapUrl::truncate();

            return redirect()
                ->route('admin.sitemap.index')
                ->with('success', "{$deleted} URLs supprimées avec succès.");
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.sitemap.index')
                ->with('error', 'Erreur lors du nettoyage : ' . $e->getMessage());
        }
    }
}