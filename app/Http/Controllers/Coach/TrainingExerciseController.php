<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\TrainingExercise;
use App\Models\TrainingList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace coachs → Séances → Bibliothèque d'exercices (PPG et séances à la maison).
 */
class TrainingExerciseController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('categorie');
        $search = trim((string) $request->query('q', ''));

        $exercises = TrainingExercise::query()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%")))
            ->orderByDesc('is_active')->orderBy('category')->orderBy('name')
            ->paginate(24)->withQueryString();

        return view('coach.training.exercises.index', [
            'exercises' => $exercises,
            'categories' => TrainingList::labels('categories'),
            'category' => $category,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('coach.training.exercises.form', ['exercise' => new TrainingExercise(['is_active' => true]), 'categories' => TrainingList::labels('categories')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $exercise = TrainingExercise::create($this->validated($request) + ['created_by' => $request->user()->id]);

        return redirect()->route('coach.exercises.index')->with('success', "Exercice « {$exercise->name} » ajouté à la bibliothèque.");
    }

    public function edit(TrainingExercise $exercise): View
    {
        return view('coach.training.exercises.form', ['exercise' => $exercise, 'categories' => TrainingList::labels('categories')]);
    }

    public function update(Request $request, TrainingExercise $exercise): RedirectResponse
    {
        $exercise->update($this->validated($request));

        return redirect()->route('coach.exercises.index')->with('success', "Exercice « {$exercise->name} » mis à jour.");
    }

    /** Un exercice utilisé dans des séances est seulement désactivé, pour ne pas casser ces séances. */
    public function destroy(TrainingExercise $exercise): RedirectResponse
    {
        if ($exercise->usageCount() > 0) {
            $exercise->update(['is_active' => false]);

            return redirect()->route('coach.exercises.index')
                ->with('success', "« {$exercise->name} » est utilisé dans des séances : il est désactivé (il n'est plus proposé) au lieu d'être supprimé.");
        }

        $exercise->delete();

        return redirect()->route('coach.exercises.index')->with('success', "Exercice « {$exercise->name} » supprimé.");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/)#i'],
            'video_url' => ['nullable', 'url:http,https', 'max:500'],
            'description' => ['nullable', 'string', 'max:3000'],
            'tips' => ['nullable', 'string', 'max:3000'],
            'is_active' => ['nullable', 'boolean'],
        ], ['image.regex' => "L'image doit être choisie dans la médiathèque."]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
