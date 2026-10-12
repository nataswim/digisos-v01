<?php

namespace App\Http\Controllers\Admin\Competitions;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionEvent;
use App\Models\CompetitionSession;
use App\Models\SwimEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Grille du programme d'une compétition : pour chaque réunion, les épreuves cochées
 * (Dames / Messieurs / Mixte × séries, finales C, B, A) et leur ordre de passage.
 * Sert l'espace admin (admin.competitions.program.*) et l'espace éditeur (editor.competitions.program.*).
 */
class CompetitionProgramController extends Controller
{
    public function edit(Competition $competition)
    {
        $sessions = $competition->sessions()->with('programEvents.event')->get();

        // Cases cochées : [session][épreuve][sexe][tour] = true
        $coches = [];
        foreach ($sessions as $session) {
            foreach ($session->programEvents as $e) {
                $coches[$session->id][$e->swim_event_id][$e->gender][$e->round] = true;
            }
        }

        return view('admin.competitions.program', [
            'competition' => $competition,
            'sessions' => $sessions,
            'individualEvents' => SwimEvent::active()->where('is_relay', false)->ordered()->get()->groupBy('stroke'),
            'relayEvents' => SwimEvent::active()->where('is_relay', true)->ordered()->get(),
            'checked' => $coches,
            'area' => $this->area(),
            'layout' => 'layouts.' . $this->area(),
        ]);
    }

    /**
     * Enregistre la grille. Les épreuves déjà au programme gardent leur ordre ;
     * les nouvelles sont insérées à leur place logique (ordre du référentiel, Dames avant Messieurs,
     * séries puis finales C, B, A). Le bouton « Trier automatiquement » recalcule tout l'ordre.
     */
    public function update(Request $request, Competition $competition): RedirectResponse
    {
        $request->validate([
            'grid' => ['nullable', 'array'],
            'auto_sort' => ['nullable', 'boolean'],
        ]);

        $sessions = $competition->sessions()->with('programEvents')->get()->keyBy('id');
        $evenements = SwimEvent::all(['id', 'sort_order', 'is_relay'])->keyBy('id');
        $grille = $request->input('grid', []);
        $total = 0;

        DB::transaction(function () use ($sessions, $evenements, $grille, $request, &$total) {
            foreach ($sessions as $session) {
                // Combinaisons demandées pour cette réunion
                $voulues = [];
                foreach ((array) ($grille[$session->id] ?? []) as $eventId => $parSexe) {
                    $evenement = $evenements->get((int) $eventId);
                    if (! $evenement) {
                        continue;
                    }
                    foreach ((array) $parSexe as $sexe => $tours) {
                        // Mixte : relais uniquement
                        if (! isset(CompetitionEvent::GENDERS[$sexe]) || ($sexe === 'X' && ! $evenement->is_relay)) {
                            continue;
                        }
                        foreach ((array) $tours as $tour) {
                            if (isset(CompetitionEvent::ROUNDS[$tour])) {
                                $voulues["{$eventId}|{$sexe}|{$tour}"] = [(int) $eventId, $sexe, $tour];
                            }
                        }
                    }
                }

                // Supprimer les épreuves décochées, garder les autres dans leur ordre actuel
                $liste = [];
                foreach ($session->programEvents as $existante) {
                    $cle = "{$existante->swim_event_id}|{$existante->gender}|{$existante->round}";
                    if (isset($voulues[$cle])) {
                        $liste[] = ['model' => $existante, 'key' => $this->key($evenements, $existante->swim_event_id, $existante->gender, $existante->round)];
                        unset($voulues[$cle]);
                    } else {
                        $existante->delete();
                    }
                }

                if ($request->boolean('auto_sort')) {
                    usort($liste, fn ($a, $b) => strcmp($a['key'], $b['key']));
                }

                // Insérer les nouvelles à leur place logique
                $nouvelles = array_values($voulues);
                usort($nouvelles, fn ($a, $b) => strcmp($this->key($evenements, ...$a), $this->key($evenements, ...$b)));
                foreach ($nouvelles as [$eventId, $sexe, $tour]) {
                    $cle = $this->key($evenements, $eventId, $sexe, $tour);
                    $position = count($liste);
                    foreach ($liste as $i => $item) {
                        if (strcmp($item['key'], $cle) > 0) {
                            $position = $i;
                            break;
                        }
                    }
                    array_splice($liste, $position, 0, [[
                        'model' => new CompetitionEvent(['swim_event_id' => $eventId, 'gender' => $sexe, 'round' => $tour]),
                        'key' => $cle,
                    ]]);
                }

                // Numérotation
                foreach ($liste as $i => $item) {
                    $item['model']->competition_session_id = $session->id;
                    $item['model']->sort_order = $i + 1;
                    $item['model']->save();
                }
                $total += count($liste);
            }
        });

        return redirect()->route($this->area() . '.competitions.program.edit', $competition)
            ->with('success', "Programme enregistré : {$total} épreuve" . ($total > 1 ? 's' : '') . ' au total.');
    }

    /** Ordre manuel d'une réunion (boutons ↑ ↓). */
    public function reorder(Request $request, Competition $competition, CompetitionSession $session): RedirectResponse
    {
        abort_unless((int) $session->competition_id === (int) $competition->id, 404);

        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        DB::transaction(function () use ($session, $data) {
            $rang = 0;
            foreach ($data['order'] as $id) {
                CompetitionEvent::where('competition_session_id', $session->id)->whereKey($id)->update(['sort_order' => ++$rang]);
            }
        });

        return redirect()->to(route($this->area() . '.competitions.program.edit', $competition) . '#ordre-' . $session->id)
            ->with('success', "Ordre de la réunion « {$session->label} » enregistré.");
    }

    private function key($evenements, int $eventId, string $gender, string $round): string
    {
        return CompetitionEvent::autoKey((int) ($evenements->get($eventId)->sort_order ?? 0), $gender, $round);
    }

    private function area(): string
    {
        return request()->routeIs('editor.*') ? 'editor' : 'admin';
    }
}
