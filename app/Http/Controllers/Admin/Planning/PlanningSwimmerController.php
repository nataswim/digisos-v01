<?php

namespace App\Http\Controllers\Admin\Planning;

use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlanningSwimmerController extends PlanningController
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $groupId = $request->integer('groupe') ?: null;

        $swimmers = PlanningSwimmer::with('groups')
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")))
            ->when($groupId, fn ($q) => $q->whereHas('groups', fn ($g) => $g->whereKey($groupId)))
            ->orderBy('last_name')->orderBy('first_name')
            ->paginate(30)->withQueryString();

        return $this->planningView('swimmers.index', [
            'swimmers' => $swimmers,
            'groups' => PlanningGroup::ordered()->get(),
            'search' => $search,
            'groupId' => $groupId,
        ]);
    }

    public function create(Request $request)
    {
        return $this->planningView('swimmers.form', [
            'swimmer' => new PlanningSwimmer(['is_active' => true]),
            'groups' => PlanningGroup::ordered()->get(),
            'selected' => array_filter([$request->integer('groupe')]),
            'users' => $this->members(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $swimmer = PlanningSwimmer::create($this->validated($request));
        $swimmer->groups()->sync($request->input('groups', []));

        $redirect = $request->boolean('add_another')
            ? redirect()->route($this->planningRoute('swimmers.create'), ['groupe' => $request->input('groups.0')])
            : redirect()->route($this->planningRoute('swimmers.index'));

        return $redirect->with('success', "Nageur « {$swimmer->full_name} » ajouté.");
    }

    public function edit(PlanningSwimmer $swimmer)
    {
        return $this->planningView('swimmers.form', [
            'swimmer' => $swimmer,
            'groups' => PlanningGroup::ordered()->get(),
            'selected' => $swimmer->groups()->pluck('planning_groups.id')->all(),
            'users' => $this->members(),
        ]);
    }

    public function update(Request $request, PlanningSwimmer $swimmer): RedirectResponse
    {
        $swimmer->update($this->validated($request));
        $swimmer->groups()->sync($request->input('groups', []));

        return redirect()->route($this->planningRoute('swimmers.index'))
            ->with('success', "Nageur « {$swimmer->full_name} » mis à jour.");
    }

    public function destroy(PlanningSwimmer $swimmer): RedirectResponse
    {
        $name = $swimmer->full_name;
        $swimmer->delete();

        return redirect()->route($this->planningRoute('swimmers.index'))
            ->with('success', "Nageur « {$name} » supprimé.");
    }

    /** Comptes du site pouvant être liés à un nageur (hors visiteurs). */
    private function members()
    {
        return User::whereHas('role', fn ($q) => $q->whereIn('slug', ['user', 'editor', 'admin']))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birth_year' => ['nullable', 'integer', 'min:1920', 'max:' . now()->year],
            'gender' => ['nullable', \Illuminate\Validation\Rule::in(array_keys(PlanningSwimmer::GENDERS))],
            'license_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9A-Za-z -]*$/'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'groups' => ['nullable', 'array'],
            'groups.*' => ['integer', 'exists:planning_groups,id'],
        ], [
            'license_number.regex' => 'Le numéro de licence ne doit contenir que des chiffres et des lettres.',
        ]);

        unset($data['groups']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
