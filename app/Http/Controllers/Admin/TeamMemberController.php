<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\TeamSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * L'équipe du club : gestion des membres (administrateurs uniquement, routes admin.team.members.*).
 * Chaque membre est rattaché à une ou plusieurs sections, avec une fonction et un ordre pour chacune.
 */
class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $sections = TeamSection::ordered()->withCount('members')->get();
        $sectionId = $request->integer('section') ?: null;

        $members = TeamMember::with('sections')
            ->when($sectionId, fn ($q) => $q->whereHas('sections', fn ($s) => $s->whereKey($sectionId)))
            ->ordered()
            ->get();

        return view('admin.team.members.index', [
            'members' => $members,
            'sections' => $sections,
            'sectionId' => $sectionId,
            'withoutSection' => TeamMember::doesntHave('sections')->count(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $sections = TeamSection::ordered()->get();
        if ($sections->isEmpty()) {
            return redirect()->route('admin.team.sections.create')
                ->with('error', 'Créez d\'abord une section (par exemple « Comité directeur »).');
        }

        $member = new TeamMember(['is_active' => true]);
        $preselect = $request->integer('section') ?: $sections->first()->id;

        return view('admin.team.members.form', [
            'member' => $member,
            'sections' => $sections,
            'rows' => [['section_id' => $preselect, 'position' => '', 'sort_order' => 0]],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        [$data, $assignments] = $this->validated($request);

        $member = TeamMember::create($data);
        $member->sections()->sync($assignments);

        return redirect()->route('admin.team.members.index')
            ->with('success', "« {$member->full_name} » ajouté(e) à l'équipe.");
    }

    public function edit(TeamMember $member): View
    {
        $member->load('sections');

        return view('admin.team.members.form', [
            'member' => $member,
            'sections' => TeamSection::ordered()->get(),
            'rows' => $member->sections->map(fn ($s) => [
                'section_id' => $s->id,
                'position' => $s->pivot->position,
                'sort_order' => $s->pivot->sort_order,
            ])->all(),
        ]);
    }

    public function update(Request $request, TeamMember $member): RedirectResponse
    {
        [$data, $assignments] = $this->validated($request);

        $member->update($data);
        $member->sections()->sync($assignments);

        return redirect()->route('admin.team.members.index')
            ->with('success', "« {$member->full_name} » mis(e) à jour.");
    }

    public function destroy(TeamMember $member): RedirectResponse
    {
        $name = $member->full_name;
        $member->delete();

        return redirect()->route('admin.team.members.index')
            ->with('success', "« {$name} » retiré(e) de l'équipe.");
    }

    /**
     * @return array{0: array, 1: array<int, array{position: ?string, sort_order: int}>}
     */
    private function validated(Request $request): array
    {
        $url = ['nullable', 'string', 'max:500', 'url:http,https'];

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'photo' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/)#i'],
            'facebook_url' => $url,
            'instagram_url' => $url,
            'linkedin_url' => $url,
            'website_url' => $url,
            'assignments' => ['required', 'array', 'min:1'],
            'assignments.*.section_id' => ['required', 'integer', 'exists:team_sections,id'],
            'assignments.*.position' => ['nullable', 'string', 'max:150'],
            'assignments.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ], [
            'photo.regex' => 'La photo doit être choisie dans la médiathèque ou être une adresse commençant par https://',
            '*.url' => 'Le lien doit être une adresse complète commençant par https://',
            'assignments.required' => 'Choisissez au moins une section.',
            'assignments.min' => 'Choisissez au moins une section.',
            'assignments.*.section_id.required' => 'Choisissez la section de chaque ligne.',
        ], [
            'first_name' => 'prénom',
            'last_name' => 'nom',
            'facebook_url' => 'lien Facebook',
            'instagram_url' => 'lien Instagram',
            'linkedin_url' => 'lien LinkedIn',
            'website_url' => 'site web',
        ]);

        $assignments = [];
        foreach ($data['assignments'] as $row) {
            $id = (int) $row['section_id'];
            if (isset($assignments[$id])) {
                throw ValidationException::withMessages([
                    'assignments' => 'Une même section est choisie deux fois : gardez une seule ligne par section.',
                ]);
            }
            $assignments[$id] = [
                'position' => filled($row['position'] ?? null) ? trim($row['position']) : null,
                'sort_order' => (int) ($row['sort_order'] ?? 0),
            ];
        }
        unset($data['assignments']);

        $data['is_active'] = $request->boolean('is_active');

        return [$data, $assignments];
    }
}
