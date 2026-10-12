<?php

namespace App\Http\Controllers\Admin\Planning;

use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanningGroupController extends PlanningController
{
    public function index()
    {
        $groups = PlanningGroup::withCount(['swimmers', 'slots'])->ordered()->get();

        return $this->planningView('groups.index', compact('groups'));
    }

    public function create()
    {
        return $this->planningView('groups.form', [
            'group' => new PlanningGroup(['is_active' => true, 'sort_order' => (int) PlanningGroup::max('sort_order') + 1]),
            'swimmers' => PlanningSwimmer::active()->orderBy('last_name')->orderBy('first_name')->get(),
            'selected' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $group = PlanningGroup::create($this->validated($request));
        $group->swimmers()->sync($request->input('swimmers', []));

        return redirect()->route($this->planningRoute('groups.index'))
            ->with('success', "Groupe « {$group->name} » créé.");
    }

    public function edit(PlanningGroup $group)
    {
        return $this->planningView('groups.form', [
            'group' => $group,
            'swimmers' => PlanningSwimmer::where(fn ($q) => $q->where('is_active', true)->orWhereHas('groups', fn ($g) => $g->whereKey($group->id)))
                ->orderBy('last_name')->orderBy('first_name')->get(),
            'selected' => $group->swimmers()->pluck('planning_swimmers.id')->all(),
        ]);
    }

    public function update(Request $request, PlanningGroup $group): RedirectResponse
    {
        $group->update($this->validated($request, $group));
        $group->swimmers()->sync($request->input('swimmers', []));

        return redirect()->route($this->planningRoute('groups.index'))
            ->with('success', "Groupe « {$group->name} » mis à jour.");
    }

    public function destroy(PlanningGroup $group): RedirectResponse
    {
        $name = $group->name;
        $group->delete();   // créneaux et composition supprimés en cascade

        return redirect()->route($this->planningRoute('groups.index'))
            ->with('success', "Groupe « {$name} » supprimé, avec ses créneaux.");
    }

    private function validated(Request $request, ?PlanningGroup $group = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('planning_groups', 'name')->ignore($group)],
            'section' => ['required', Rule::in(array_keys(PlanningGroup::SECTIONS))],
            'color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'description' => ['nullable', 'string', 'max:2000'],
            'page_url' => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/)#'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
            'swimmers' => ['nullable', 'array'],
            'swimmers.*' => ['integer', 'exists:planning_swimmers,id'],
        ], [
            'page_url.regex' => 'Le lien doit commencer par « / » (page du site) ou par « https:// ».',
            'color.regex' => 'La couleur doit être au format #RRGGBB.',
        ]);

        unset($data['swimmers']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        // La case « couleur de la section » vide le champ couleur
        if ($request->boolean('use_section_color')) {
            $data['color'] = null;
        }

        return $data;
    }
}
