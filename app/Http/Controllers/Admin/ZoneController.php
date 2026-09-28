<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreZoneRequest;
use App\Http\Requests\UpdateZoneRequest;
use App\Models\Espace;
use App\Models\Service;
use App\Models\Structure;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ZoneController extends Controller
{
    public function index(): View
    {
        $zones = Zone::with(['espace.structure.service'])
            ->latest()
            ->paginate(15);

        return view('admin.zones.index', compact('zones'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        $structures = Structure::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        $espaces = Espace::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.zones.create', compact('services', 'structures', 'espaces'));
    }

    public function store(StoreZoneRequest $request): RedirectResponse
    {
        Zone::create($request->validated());

        flash()->success('Zone créée avec succès.');

        return redirect()->route('admin.zones.index');
    }

    public function show(Zone $zone): View
    {
        $zone->load('espace.structure.service');

        return view('admin.zones.show', compact('zone'));
    }

    public function edit(Zone $zone): View
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        $structures = Structure::where('is_active', true)
            ->where('service_id', $zone->espace->structure->service_id)
            ->orderBy('name')
            ->pluck('name', 'id');

        $espaces = Espace::where('is_active', true)
            ->where('structure_id', $zone->espace->structure_id)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.zones.edit', compact('zone', 'services', 'structures', 'espaces'));
    }

    public function update(UpdateZoneRequest $request, Zone $zone): RedirectResponse
    {
        $zone->update($request->validated());

        flash()->success('Zone mise à jour avec succès.');

        return redirect()->route('admin.zones.index');
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        $zone->delete();

        flash()->success('Zone supprimée avec succès.');

        return redirect()->route('admin.zones.index');
    }
}
