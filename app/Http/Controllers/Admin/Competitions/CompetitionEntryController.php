<?php

namespace App\Http\Controllers\Admin\Competitions;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionAttendance;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\SwimEvent;
use App\Notifications\CompetitionConvocationNotification;
use App\Support\SwimTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Convocations, réponses des familles, covoiturage et engagements d'une compétition (module Compétitions, V2).
 * Sert l'espace admin (admin.competitions.entries.*) et l'espace éditeur (editor.competitions.entries.*).
 */
class CompetitionEntryController extends Controller
{
    /** Tableau de bord des convocations d'une compétition. */
    public function index(Competition $competition)
    {
        $attendances = $competition->attendances()
            ->with(['swimmer.groups', 'swimmer.user', 'entries.event', 'responder'])
            ->get()
            ->sortBy(fn ($a) => mb_strtolower($a->swimmer->last_name . ' ' . $a->swimmer->first_name))
            ->values();

        // Nageurs actifs pas encore convoqués, regroupés par groupe (groupes de la compétition en premier)
        $dejaConvoques = $attendances->pluck('planning_swimmer_id')->all();
        $groupesCompetition = $competition->groups()->pluck('planning_groups.id')->all();

        $groups = PlanningGroup::ordered()
            ->with(['swimmers' => fn ($q) => $q->where('planning_swimmers.is_active', true)->whereNotIn('planning_swimmers.id', $dejaConvoques)->orderBy('last_name')->orderBy('first_name')])
            ->get()
            ->filter(fn ($g) => $g->swimmers->isNotEmpty())
            ->sortBy(fn ($g) => in_array($g->id, $groupesCompetition, true) ? 0 : 1)
            ->values();

        $sansGroupe = PlanningSwimmer::active()
            ->doesntHave('groups')
            ->whereNotIn('id', $dejaConvoques)
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        return $this->view('index', [
            'competition' => $competition,
            'attendances' => $attendances,
            'groups' => $groups,
            'competitionGroupIds' => $groupesCompetition,
            'ungrouped' => $sansGroupe,
            'stats' => $this->stats($attendances),
            'unnotified' => $attendances->filter(fn ($a) => $a->status === 'convoque' && ! $a->notified_at && $a->swimmer->user)->count(),
            'withoutAccount' => $attendances->filter(fn ($a) => $a->isExpected() && ! $a->swimmer->user)->count(),
        ]);
    }

    /** Convoquer des nageurs. */
    public function store(Request $request, Competition $competition): RedirectResponse
    {
        $data = $request->validate([
            'swimmers' => ['required', 'array', 'min:1'],
            'swimmers.*' => ['integer', 'exists:planning_swimmers,id'],
        ], [
            'swimmers.required' => 'Cochez au moins un nageur à convoquer.',
        ]);

        $ajoutes = 0;
        foreach (array_unique($data['swimmers']) as $swimmerId) {
            $attendance = $competition->attendances()->firstOrCreate(
                ['planning_swimmer_id' => $swimmerId],
                ['status' => 'convoque']
            );
            $ajoutes += $attendance->wasRecentlyCreated ? 1 : 0;
        }

        return redirect()->route($this->route('index'), $competition)
            ->with('success', $ajoutes > 1 ? "{$ajoutes} nageurs convoqués." : ($ajoutes === 1 ? '1 nageur convoqué.' : 'Ces nageurs étaient déjà convoqués.'));
    }

    /** Mise à jour groupée des statuts depuis le tableau. */
    public function updateStatuses(Request $request, Competition $competition): RedirectResponse
    {
        $data = $request->validate([
            'statuses' => ['required', 'array'],
            'statuses.*' => [Rule::in(array_keys(CompetitionAttendance::STATUSES))],
        ]);

        $modifies = 0;
        $attendances = $competition->attendances()->whereIn('id', array_keys($data['statuses']))->get();
        foreach ($attendances as $attendance) {
            $attendance->status = $data['statuses'][$attendance->id];
            if ($attendance->isDirty('status')) {
                $attendance->save();
                $modifies++;
            }
        }

        return redirect()->route($this->route('index'), $competition)
            ->with('success', $modifies ? "Statuts enregistrés ({$modifies} modification" . ($modifies > 1 ? 's' : '') . ').' : 'Aucun statut modifié.');
    }

    /** Fiche d'un nageur convoqué : statut, covoiturage et épreuves. */
    public function edit(Competition $competition, CompetitionAttendance $attendance)
    {
        $this->ensureBelongs($competition, $attendance);
        $attendance->load(['swimmer.groups', 'swimmer.user', 'entries', 'responder']);

        // Épreuves proposées : celles du programme (pour le sexe du nageur s'il est connu),
        // sinon tout le référentiel individuel. Les épreuves déjà engagées restent toujours affichées.
        $auProgramme = $competition->programEvents()
            ->when($attendance->swimmer->gender, fn ($q, $sexe) => $q->where('competition_events.gender', $sexe))
            ->pluck('competition_events.swim_event_id')
            ->unique();

        $events = SwimEvent::active()->where('is_relay', false)->ordered()
            ->when($auProgramme->isNotEmpty(), fn ($q) => $q->whereIn('id', $auProgramme->merge($attendance->entries->pluck('swim_event_id'))->all()))
            ->get();

        return $this->view('edit', [
            'competition' => $competition,
            'attendance' => $attendance,
            'events' => $events->groupBy('stroke'),
            'fromProgram' => $auProgramme->isNotEmpty(),
            'entries' => $attendance->entries->keyBy('swim_event_id'),
        ]);
    }

    public function update(Request $request, Competition $competition, CompetitionAttendance $attendance): RedirectResponse
    {
        $this->ensureBelongs($competition, $attendance);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(CompetitionAttendance::STATUSES))],
            'seats_offered' => ['nullable', 'integer', 'between:0,8'],
            'family_comment' => ['nullable', 'string', 'max:500'],
            'staff_note' => ['nullable', 'string', 'max:500'],
            'events' => ['nullable', 'array'],
            'events.*.selected' => ['nullable', 'boolean'],
            'events.*.time' => ['nullable', 'string', 'max:12'],
        ]);

        // Temps d'engagement : contrôle du format avant tout enregistrement
        $engagements = [];
        $erreurs = [];
        $libelles = SwimEvent::pluck('label', 'id');
        foreach ($request->input('events', []) as $eventId => $ligne) {
            if (empty($ligne['selected']) || ! $libelles->has($eventId)) {
                continue;
            }
            if (! SwimTime::isValid($ligne['time'] ?? null)) {
                $erreurs["events.$eventId.time"] = "Temps non valide pour le {$libelles[$eventId]} (format attendu : 1:02.34 ou 32.50).";
                continue;
            }
            $engagements[(int) $eventId] = SwimTime::parse($ligne['time'] ?? null);
        }
        if ($erreurs) {
            throw ValidationException::withMessages($erreurs);
        }

        DB::transaction(function () use ($request, $attendance, $data, $engagements) {
            $attendance->update([
                'status' => $data['status'],
                'needs_seat' => $request->boolean('needs_seat'),
                'seats_offered' => (int) ($data['seats_offered'] ?? 0),
                'family_comment' => $data['family_comment'] ?? null,
                'staff_note' => $data['staff_note'] ?? null,
            ]);

            $attendance->entries()->whereNotIn('swim_event_id', array_keys($engagements) ?: [0])->delete();
            foreach ($engagements as $eventId => $temps) {
                $attendance->entries()->updateOrCreate(['swim_event_id' => $eventId], ['entry_time_cs' => $temps]);
            }
        });

        $redirect = $request->boolean('next')
            ? $this->nextAttendanceRedirect($competition, $attendance)
            : redirect()->route($this->route('index'), $competition);

        return $redirect->with('success', "Fiche de {$attendance->swimmer->full_name} enregistrée.");
    }

    /** Retirer un nageur de la liste des convoqués. */
    public function destroy(Competition $competition, CompetitionAttendance $attendance): RedirectResponse
    {
        $this->ensureBelongs($competition, $attendance);
        $nom = $attendance->swimmer->full_name;
        $attendance->delete();

        return redirect()->route($this->route('index'), $competition)
            ->with('success', "{$nom} a été retiré(e) des convoqués.");
    }

    /**
     * Prévenir par e-mail les familles (comptes liés aux nageurs) des convocations en attente
     * qui n'ont pas encore été envoyées. Un seul e-mail par compte, même pour plusieurs enfants.
     */
    public function notify(Request $request, Competition $competition): RedirectResponse
    {
        $request->validate(['scope' => ['nullable', Rule::in(['new', 'pending'])]]);
        $relance = $request->input('scope') === 'pending';

        $attendances = $competition->attendances()
            ->with('swimmer.user')
            ->where('status', 'convoque')
            ->when(! $relance, fn ($q) => $q->whereNull('notified_at'))
            ->get()
            ->filter(fn ($a) => $a->swimmer->user);

        if ($attendances->isEmpty()) {
            return redirect()->route($this->route('index'), $competition)
                ->with('error', 'Aucune famille à prévenir (convocations déjà envoyées ou nageurs sans compte lié).');
        }

        $envoyes = 0;
        $echecs = 0;
        foreach ($attendances->groupBy(fn ($a) => $a->swimmer->user->id) as $groupe) {
            $user = $groupe->first()->swimmer->user;
            try {
                $user->notify(new CompetitionConvocationNotification($competition, $groupe->map(fn ($a) => $a->swimmer->first_name)->all(), $relance));
                CompetitionAttendance::whereIn('id', $groupe->pluck('id'))->update(['notified_at' => now()]);
                $envoyes++;
            } catch (\Throwable $e) {
                Log::warning('Convocation compétition non envoyée', ['user' => $user->id, 'competition' => $competition->id, 'error' => $e->getMessage()]);
                $echecs++;
            }
        }

        $message = "{$envoyes} e-mail" . ($envoyes > 1 ? 's' : '') . ' envoyé' . ($envoyes > 1 ? 's' : '') . '.';
        if ($echecs) {
            return redirect()->route($this->route('index'), $competition)
                ->with('error', $message . " {$echecs} envoi(s) en échec : vérifiez la configuration e-mail du site (journal Laravel).");
        }

        return redirect()->route($this->route('index'), $competition)->with('success', $message);
    }

    /** Export CSV (Excel) de la liste des convoqués et de leurs engagements. */
    public function export(Competition $competition)
    {
        $attendances = $competition->attendances()
            ->with(['swimmer.groups', 'entries.event'])
            ->get()
            ->sortBy(fn ($a) => mb_strtolower($a->swimmer->last_name . ' ' . $a->swimmer->first_name));

        $lignes = [[
            'Nom', 'Prénom', 'Année', 'Sexe', 'Licence', 'Groupe(s)', 'Statut', 'Épreuves (temps d\'engagement)',
            'Cherche une place', 'Places offertes', 'Commentaire de la famille', 'Note interne', 'Réponse le',
        ]];
        foreach ($attendances as $a) {
            $s = $a->swimmer;
            $lignes[] = [
                mb_strtoupper($s->last_name), $s->first_name, $s->birth_year, $s->gender, $s->license_number,
                $s->groups->pluck('name')->implode(', '),
                $a->status_label,
                $a->sorted_entries->map(fn ($e) => $e->event->label . ' (' . ($e->entry_time ?? 'NT') . ')')->implode(' ; '),
                $a->needs_seat ? 'oui' : '', $a->seats_offered ?: '',
                $a->family_comment, $a->staff_note,
                optional($a->responded_at)->format('d/m/Y H:i'),
            ];
        }

        $csv = fopen('php://temp', 'r+');
        fwrite($csv, "\xEF\xBB\xBF");   // BOM : accents corrects à l'ouverture dans Excel
        foreach ($lignes as $ligne) {
            fputcsv($csv, array_map(fn ($v) => $this->csvSafe($v), $ligne), ';');
        }
        rewind($csv);
        $contenu = stream_get_contents($csv);
        fclose($csv);

        return response($contenu, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="convocations-' . $competition->slug . '.csv"',
        ]);
    }

    // ------------------------------------------------------------------

    private function stats($attendances): array
    {
        $parStatut = $attendances->countBy('status');
        $attendus = $attendances->filter->isExpected();

        return [
            'total' => $attendances->count(),
            'convoque' => $parStatut['convoque'] ?? 0,
            'confirme' => $parStatut['confirme'] ?? 0,
            'absent' => $parStatut['absent'] ?? 0,
            'forfait' => $parStatut['forfait'] ?? 0,
            'entries' => $attendus->sum(fn ($a) => $a->entries->count()),
            'seats_needed' => $attendus->where('needs_seat', true)->count(),
            'seats_offered' => $attendus->sum('seats_offered'),
        ];
    }

    /** Après « Enregistrer et suivant » : fiche du nageur suivant (ordre alphabétique). */
    private function nextAttendanceRedirect(Competition $competition, CompetitionAttendance $current): RedirectResponse
    {
        $ordre = $competition->attendances()->with('swimmer')->get()
            ->sortBy(fn ($a) => mb_strtolower($a->swimmer->last_name . ' ' . $a->swimmer->first_name))
            ->pluck('id')->values();
        $position = $ordre->search($current->id);
        $suivant = $position !== false ? $ordre->get($position + 1) : null;

        return $suivant
            ? redirect()->route($this->route('edit'), [$competition, $suivant])
            : redirect()->route($this->route('index'), $competition);
    }

    private function ensureBelongs(Competition $competition, CompetitionAttendance $attendance): void
    {
        abort_unless((int) $attendance->competition_id === (int) $competition->id, 404);
    }

    /** Empêche l'interprétation de formules à l'ouverture du CSV dans un tableur. */
    private function csvSafe($value): string
    {
        $value = (string) $value;

        return $value !== '' && in_array($value[0], ['=', '+', '-', '@'], true) ? "'" . $value : $value;
    }

    private function area(): string
    {
        return request()->routeIs('editor.*') ? 'editor' : 'admin';
    }

    private function route(string $name): string
    {
        return $this->area() . '.competitions.entries.' . $name;
    }

    private function view(string $name, array $data)
    {
        return view('admin.competitions.entries.' . $name, $data + [
            'area' => $this->area(),
            'layout' => 'layouts.' . $this->area(),
        ]);
    }
}
