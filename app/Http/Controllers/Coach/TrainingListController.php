<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\TrainingList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Espace coachs → Séances → Listes : nages, matériel, types de blocs, catégories d'exercices.
 * Une ligne par valeur. Modifier une liste ne change pas les séances déjà saisies.
 */
class TrainingListController extends Controller
{
    public function edit(): View
    {
        return view('coach.training.lists', [
            'lists' => collect(TrainingList::LISTS)->map(fn ($meta, $key) => [
                'title' => $meta[0],
                'help' => $meta[1],
                'values' => implode("\n", TrainingList::labels($key)),
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lists' => ['required', 'array'],
            'lists.*' => ['nullable', 'string', 'max:5000'],
        ]);

        foreach (array_keys(TrainingList::LISTS) as $key) {
            if (array_key_exists($key, $data['lists'])) {
                TrainingList::replace($key, preg_split('/\R/', (string) $data['lists'][$key]));
            }
        }

        return redirect()->route('coach.training-lists.edit')->with('success', 'Listes enregistrées.');
    }
}
