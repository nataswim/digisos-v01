<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStructureRequest;
use App\Http\Requests\UpdateStructureRequest;
use App\Models\Service;
use App\Models\Structure;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StructureController extends Controller
{
    public function index(): View
    {
        $structures = Structure::with('service')
            ->withCount('espaces')
            ->latest()
            ->paginate(15);

        return view('admin.structures.index', compact('structures'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.structures.create', compact('services'));
    }

    public function store(StoreStructureRequest $request): RedirectResponse
    {
        Structure::create($request->validated());

        flash()->success('Structure créée avec succès.');

        return redirect()->route('admin.structures.index');
    }

    public function show(Structure $structure): View
    {
        $structure->loadCount('espaces')
                  ->load(['espaces' => fn ($q) => $q->withCount('zones')]);

        return view('admin.structures.show', compact('structure'));
    }

    public function edit(Structure $structure): View
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.structures.edit', compact('structure', 'services'));
    }

    public function update(UpdateStructureRequest $request, Structure $structure): RedirectResponse
    {
        $structure->update($request->validated());

        flash()->success('Structure mise à jour avec succès.');

        return redirect()->route('admin.structures.index');
    }

    public function destroy(Structure $structure): RedirectResponse
    {
        $structure->delete();

        flash()->success('Structure supprimée avec succès.');

        return redirect()->route('admin.structures.index');
    }
}
