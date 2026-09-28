<?php

namespace App\Http\Controllers;

use App\Models\Espace;
use App\Models\Service;
use App\Models\Structure;
use App\Models\Zone;
use Illuminate\View\View;

class PublicInstallationController extends Controller
{
    /**
     * Liste de tous les services actifs — /installations
     */
    public function index(): View
    {
        $services = Service::where('is_active', true)
            ->withCount(['structures' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('public.installations.index', compact('services'));
    }

    /**
     * Détail d'un service + ses structures actives
     * URL : /installations/{service} — résolu par slug via getRouteKeyName()
     * Exemple : /installations/complexe-aquatique-municipal
     */
    public function showService(Service $service): View
    {
        abort_if(! $service->is_active, 404);

        $service->load([
            'structures' => fn ($q) => $q
                ->where('is_active', true)
                ->withCount(['espaces' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('name'),
        ]);

        return view('public.installations.services.show', compact('service'));
    }

    /**
     * Détail d'une structure + ses espaces actifs
     * URL : /installations/structures/{structure}
     * Exemple : /installations/structures/complexe-aquatique-centre-aquatique
     */
    public function showStructure(Structure $structure): View
    {
        abort_if(! $structure->is_active, 404);

        $structure->load([
            'service',
            'espaces' => fn ($q) => $q
                ->where('is_active', true)
                ->withCount(['zones' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('name'),
        ]);

        return view('public.installations.structures.show', compact('structure'));
    }

    /**
     * Détail d'un espace + ses zones actives
     * URL : /installations/espaces/{espace}
     * Exemple : /installations/espaces/centre-aquatique-piscine-interieure
     */
    public function showEspace(Espace $espace): View
    {
        abort_if(! $espace->is_active, 404);

        $espace->load([
            'structure.service',
            'zones' => fn ($q) => $q
                ->where('is_active', true)
                ->orderBy('name'),
        ]);

        return view('public.installations.espaces.show', compact('espace'));
    }

    /**
     * Détail d'une zone
     * URL : /installations/zones/{zone}
     * Exemple : /installations/zones/piscine-interieure-bassin-25m
     */
    public function showZone(Zone $zone): View
    {
        abort_if(! $zone->is_active, 404);

        $zone->load('espace.structure.service');

        return view('public.installations.zones.show', compact('zone'));
    }
}