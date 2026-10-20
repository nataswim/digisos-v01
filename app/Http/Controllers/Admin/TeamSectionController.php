<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * L'équipe du club : gestion des sections (administrateurs uniquement, routes admin.team.sections.*).
 */
class TeamSectionController extends Controller
{
    public function index(): View
    {
        $sections = TeamSection::ordered()->withCount('members')->get();

        return view('admin.team.sections.index', compact('sections'));
    }

    public function create(): View
    {
        return view('admin.team.sections.form', [
            'section' => new TeamSection([
                'is_active' => true,
                'sort_order' => (int) TeamSection::max('sort_order') + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $section = TeamSection::create($this->validated($request));

        return redirect()->route('admin.team.sections.index')
            ->with('success', "Section « {$section->name} » créée.");
    }

    public function edit(TeamSection $section): View
    {
        return view('admin.team.sections.form', compact('section'));
    }

    public function update(Request $request, TeamSection $section): RedirectResponse
    {
        $section->update($this->validated($request));

        return redirect()->route('admin.team.sections.index')
            ->with('success', "Section « {$section->name} » mise à jour.");
    }

    /** Les membres ne sont pas supprimés : ils sont seulement retirés de cette section. */
    public function destroy(TeamSection $section): RedirectResponse
    {
        $name = $section->name;
        $section->members()->detach();
        $section->delete();

        return redirect()->route('admin.team.sections.index')
            ->with('success', "Section « {$name} » supprimée. Ses membres restent dans la liste des membres.");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
