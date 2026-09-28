<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::withCount('structures')
            ->latest()
            ->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        Service::create($request->validated());

        flash()->success('Service créé avec succès.');

        return redirect()->route('admin.services.index');
    }

    public function show(Service $service): View
    {
        $service->loadCount('structures')
                ->load(['structures' => fn ($q) => $q->withCount('espaces')]);

        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        flash()->success('Service mis à jour avec succès.');

        return redirect()->route('admin.services.index');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        flash()->success('Service supprimé avec succès.');

        return redirect()->route('admin.services.index');
    }
}
