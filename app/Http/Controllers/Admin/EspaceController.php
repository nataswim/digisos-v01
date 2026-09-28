<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEspaceRequest;
use App\Http\Requests\UpdateEspaceRequest;
use App\Models\Espace;
use App\Models\Service;
use App\Models\Structure;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EspaceController extends Controller
{
    public function index(): View
    {
        $espaces = Espace::with(['structure.service'])
            ->withCount('zones')
            ->latest()
            ->paginate(15);

        return view('admin.espaces.index', compact('espaces'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        $structures = Structure::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.espaces.create', compact('services', 'structures'));
    }

    public function store(StoreEspaceRequest $request): RedirectResponse
    {
        Espace::create($request->validated());

        flash()->success('Espace créé avec succès.');

        return redirect()->route('admin.espaces.index');
    }

    public function show(Espace $espace): View
    {
        $espace->load('structure.service')
               ->loadCount('zones')
               ->load(['zones' => fn ($q) => $q->orderBy('name')]);

        return view('admin.espaces.show', compact('espace'));
    }

    public function edit(Espace $espace): View
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        $structures = Structure::where('is_active', true)
            ->where('service_id', $espace->structure->service_id)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.espaces.edit', compact('espace', 'services', 'structures'));
    }

    public function update(UpdateEspaceRequest $request, Espace $espace): RedirectResponse
    {
        $espace->update($request->validated());

        flash()->success('Espace mis à jour avec succès.');

        return redirect()->route('admin.espaces.index');
    }

    public function destroy(Espace $espace): RedirectResponse
    {
        $espace->delete();

        flash()->success('Espace supprimé avec succès.');

        return redirect()->route('admin.espaces.index');
    }
}
