<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\SwimmerNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Espace coachs → Suivi des nageurs : notes par nageur, visibles des coachs et administrateurs.
 * Chaque coach peut ajouter des notes ; seuls l'auteur et les administrateurs les modifient ou suppriment.
 */
class SwimmerNoteController extends Controller
{
    public function index(Request $request): View
    {
        $groupId = $request->integer('groupe') ?: null;
        $search = trim((string) $request->query('q', ''));

        $swimmers = PlanningSwimmer::query()
            ->active()
            ->with('groups')
            ->withCount('coachNotes')
            ->withMax('coachNotes', 'noted_on')
            ->when($groupId, fn ($q) => $q->whereHas('groups', fn ($g) => $g->where('planning_groups.id', $groupId)))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('last_name', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")))
            ->orderBy('last_name')->orderBy('first_name')
            ->paginate(40)
            ->withQueryString();

        return view('coach.notes.index', [
            'swimmers' => $swimmers,
            'groups' => PlanningGroup::ordered()->get(),
            'groupId' => $groupId,
            'search' => $search,
        ]);
    }

    public function show(Request $request, PlanningSwimmer $swimmer): View
    {
        $category = $request->query('categorie');

        return view('coach.notes.show', [
            'swimmer' => $swimmer->load('groups'),
            'notes' => $swimmer->coachNotes()->with('author')
                ->when($category && isset(SwimmerNote::CATEGORIES[$category]), fn ($q) => $q->where('category', $category))
                ->get(),
            'category' => $category,
            'note' => new SwimmerNote(['noted_on' => today(), 'category' => 'entrainement']),
        ]);
    }

    public function store(Request $request, PlanningSwimmer $swimmer): RedirectResponse
    {
        $swimmer->coachNotes()->create($this->validated($request) + ['user_id' => $request->user()->id]);

        return redirect()->route('coach.notes.show', $swimmer)->with('success', 'Note ajoutée.');
    }

    public function edit(Request $request, SwimmerNote $note): View
    {
        abort_unless($note->isEditableBy($request->user()), 403);

        return view('coach.notes.edit', ['note' => $note->load('swimmer'), 'swimmer' => $note->swimmer]);
    }

    public function update(Request $request, SwimmerNote $note): RedirectResponse
    {
        abort_unless($note->isEditableBy($request->user()), 403);
        $note->update($this->validated($request));

        return redirect()->route('coach.notes.show', $note->planning_swimmer_id)->with('success', 'Note modifiée.');
    }

    public function destroy(Request $request, SwimmerNote $note): RedirectResponse
    {
        abort_unless($note->isEditableBy($request->user()), 403);
        $swimmerId = $note->planning_swimmer_id;
        $note->delete();

        return redirect()->route('coach.notes.show', $swimmerId)->with('success', 'Note supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'noted_on' => ['required', 'date', 'before_or_equal:' . today()->addDay()->toDateString()],
            'category' => ['required', Rule::in(array_keys(SwimmerNote::CATEGORIES))],
            'content' => ['required', 'string', 'max:5000'],
        ], [
            'content.required' => 'Écrivez la note.',
            'noted_on.before_or_equal' => 'La date ne peut pas être dans le futur.',
        ]);
    }
}
