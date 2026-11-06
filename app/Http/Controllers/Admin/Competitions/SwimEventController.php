<?php

namespace App\Http\Controllers\Admin\Competitions;

use App\Http\Controllers\Controller;
use App\Models\SwimEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Compétitions → Épreuves : référentiel des épreuves proposées dans la grille du programme
 * et les convocations. Ajouter, renommer, réordonner, désactiver ; supprimer si jamais utilisée.
 * Sert les espaces admin, éditeur et coachs (admin|editor|coach.competitions.events.*), comme le reste du module.
 */
class SwimEventController extends Controller
{
    public function index(): View
    {
        $events = SwimEvent::ordered()->get();

        return view('admin.competitions.events', [
            'individual' => $events->where('is_relay', false)->groupBy('stroke'),
            'relays' => $events->where('is_relay', true),
            'used' => \DB::table('competition_events')->pluck('swim_event_id')
                ->merge(\DB::table('competition_entries')->pluck('swim_event_id'))->unique()->flip(),
            'area' => $this->area(),
            'layout' => 'layouts.' . $this->area(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['individuel', 'relais'])],
            'stroke' => ['required', Rule::in(array_keys(SwimEvent::STROKES))],
            'distance' => ['required', 'integer', 'min:10', 'max:5000'],
            'legs' => ['nullable', 'required_if:type,relais', 'integer', 'min:2', 'max:20'],
            'label' => ['nullable', 'string', 'max:50'],
        ], [
            'legs.required_if' => 'Indiquez le nombre de relayeurs.',
        ]);

        $relais = $data['type'] === 'relais';
        [$code, $label] = SwimEvent::suggest($data['stroke'], (int) $data['distance'], $relais ? (int) $data['legs'] : null);

        if (SwimEvent::where('code', $code)->exists()) {
            return back()->withInput()->withErrors(['distance' => "L'épreuve « {$label} » existe déjà (réactivez-la si elle est désactivée)."]);
        }

        // Ordre : après la dernière épreuve de la même nage (individuelles) ou après le dernier relais.
        $apres = SwimEvent::where('is_relay', $relais)->when(! $relais, fn ($q) => $q->where('stroke', $data['stroke'])->where('distance', '<', $data['distance']))
            ->max('sort_order') ?? ($relais ? 1000 : 0);

        SwimEvent::create([
            'code' => $code,
            'stroke' => $data['stroke'],
            'distance' => $data['distance'],
            'is_relay' => $relais,
            'label' => filled($data['label'] ?? null) ? trim($data['label']) : $label,
            'sort_order' => $apres + 5,
            'is_active' => true,
        ]);

        return redirect()->route($this->area() . '.competitions.events.index')->with('success', "Épreuve « {$label} » ajoutée.");
    }

    public function update(Request $request, SwimEvent $event): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:60000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $event->update([
            'label' => trim($data['label']),
            'sort_order' => $data['sort_order'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route($this->area() . '.competitions.events.index')
            ->with('success', "Épreuve « {$event->label} » enregistrée" . ($event->is_active ? '.' : ' (désactivée : elle n\'apparaît plus dans les nouvelles grilles).'));
    }

    public function destroy(SwimEvent $event): RedirectResponse
    {
        if ($event->isUsed()) {
            $event->update(['is_active' => false]);

            return redirect()->route($this->area() . '.competitions.events.index')
                ->with('success', "« {$event->label} » est utilisée dans un programme ou une convocation : elle a été désactivée au lieu d'être supprimée.");
        }

        $event->delete();

        return redirect()->route($this->area() . '.competitions.events.index')->with('success', "Épreuve « {$event->label} » supprimée.");
    }

    private function area(): string
    {
        return match (true) {
            request()->routeIs('editor.*') => 'editor',
            request()->routeIs('coach.*') => 'coach',
            default => 'admin',
        };
    }
}
