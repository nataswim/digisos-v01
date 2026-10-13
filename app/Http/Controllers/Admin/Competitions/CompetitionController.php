<?php

namespace App\Http\Controllers\Admin\Competitions;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionDocument;
use App\Models\CompetitionStaff;
use App\Models\PlanningGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Gestion des compétitions (module Compétitions, V1 et V2).
 * Les convocations et engagements sont gérés par CompetitionEntryController.
 * Sert l'espace admin (admin.competitions.*) et l'espace éditeur (editor.competitions.*).
 */
class CompetitionController extends Controller
{
    private const DISK = 'local';
    private const FOLDER = 'competitions';

    public function index(Request $request)
    {
        $filter = in_array($request->query('vue'), ['a-venir', 'passees', 'brouillons'], true) ? $request->query('vue') : 'a-venir';
        $season = $request->query('saison');

        $competitions = Competition::withCount([
                'documents', 'staff', 'sessions',
                'attendances',
                'attendances as confirmed_count' => fn ($q) => $q->where('status', 'confirme'),
                'attendances as pending_count' => fn ($q) => $q->where('status', 'convoque'),
            ])
            ->with('groups')
            ->when($filter === 'a-venir', fn ($q) => $q->upcoming())
            ->when($filter === 'passees', fn ($q) => $q->past())
            ->when($filter === 'brouillons', fn ($q) => $q->where('is_published', false)->orderBy('starts_on'))
            ->when($season, fn ($q) => $q->where('season', $season))
            ->paginate(25)
            ->withQueryString();

        return $this->view('index', [
            'competitions' => $competitions,
            'filter' => $filter,
            'season' => $season,
            'seasons' => Competition::query()->distinct()->orderByDesc('season')->pluck('season'),
        ]);
    }

    public function create()
    {
        return $this->view('form', [
            'competition' => new Competition([
                'level' => 'departemental',
                'kind' => 'individuel',
                'pool_length' => 25,
                'is_published' => false,
            ]),
            'groups' => PlanningGroup::ordered()->get(),
            'selectedGroups' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $competition = DB::transaction(function () use ($request, $data) {
            $competition = Competition::create($data + ['created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
            $this->syncChildren($request, $competition);

            return $competition;
        });

        return redirect()->route($this->route('edit'), $competition)
            ->with('success', "Compétition « {$competition->title} » créée.");
    }

    public function edit(Competition $competition)
    {
        $competition->load(['staff', 'sessions', 'documents']);

        return $this->view('form', [
            'competition' => $competition,
            'groups' => PlanningGroup::ordered()->get(),
            'selectedGroups' => $competition->groups()->pluck('planning_groups.id')->all(),
        ]);
    }

    public function update(Request $request, Competition $competition): RedirectResponse
    {
        $data = $this->validated($request, $competition);

        DB::transaction(function () use ($request, $competition, $data) {
            $competition->update($data + ['updated_by' => $request->user()->id]);
            $this->syncChildren($request, $competition);
        });

        return redirect()->route($this->route('edit'), $competition)
            ->with('success', 'Compétition enregistrée.');
    }

    public function destroy(Competition $competition): RedirectResponse
    {
        $title = $competition->title;

        foreach ($competition->documents as $document) {
            Storage::disk(self::DISK)->delete($document->file_path);
        }
        $competition->delete();

        return redirect()->route($this->route('index'))->with('success', "Compétition « {$title} » supprimée.");
    }

    /** Copie une compétition (ex. même meeting l'année suivante) sans ses documents. */
    public function duplicate(Request $request, Competition $competition): RedirectResponse
    {
        $copy = DB::transaction(function () use ($request, $competition) {
            $copy = $competition->replicate(['slug', 'season', 'response_deadline', 'entries_visible', 'created_by', 'updated_by']);
            $copy->title = $competition->title . ' (copie)';
            $copy->is_published = false;
            $copy->state_override = null;
            $copy->created_by = $copy->updated_by = $request->user()->id;
            $copy->save();

            $copy->groups()->sync($competition->groups()->pluck('planning_groups.id'));
            foreach ($competition->staff as $staff) {
                $copy->staff()->create($staff->only(['name', 'role', 'phone', 'sort_order']));
            }
            foreach ($competition->sessions()->with('programEvents')->get() as $session) {
                $nouvelle = $copy->sessions()->create($session->only(['day', 'label', 'doors_open_at', 'warmup_at', 'starts_at', 'events', 'sort_order']));
                foreach ($session->programEvents as $epreuve) {
                    $nouvelle->programEvents()->create($epreuve->only(['swim_event_id', 'gender', 'round', 'sort_order']));
                }
            }

            return $copy;
        });

        return redirect()->route($this->route('edit'), $copy)
            ->with('success', 'Copie créée (non publiée) : modifiez les dates puis enregistrez.');
    }

    // ------------------------------------------------------------------ enregistrement

    private function validated(Request $request, ?Competition $competition = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'level' => ['required', Rule::in(array_keys(Competition::LEVELS))],
            'kind' => ['required', Rule::in(array_keys(Competition::KINDS))],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'venue' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'pool_length' => ['nullable', Rule::in([25, 50])],
            'lanes' => ['nullable', 'integer', 'between:1,12'],
            'organizer' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:200000'],
            'image' => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/)[^\s<>"\']+$#i'],
            'extranat_url' => ['nullable', 'url', 'max:255'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'state_override' => ['nullable', Rule::in(array_keys(Competition::STATE_OVERRIDES))],

            'meeting_point' => ['nullable', 'string', 'max:255'],
            'departure_at' => ['nullable', 'date'],
            'return_at' => ['nullable', 'date', Rule::when($request->filled('departure_at'), 'after_or_equal:departure_at')],
            'transport' => ['nullable', Rule::in(array_keys(Competition::TRANSPORTS))],
            'accommodation' => ['nullable', 'string', 'max:3000'],
            'meals' => ['nullable', 'string', 'max:3000'],
            'logistics_notes' => ['nullable', 'string', 'max:3000'],
            'response_deadline' => ['nullable', 'date', 'before_or_equal:starts_on'],

            'groups' => ['nullable', 'array'],
            'groups.*' => ['integer', 'exists:planning_groups,id'],

            'staff' => ['nullable', 'array', 'max:40'],
            'staff.*.name' => ['nullable', 'string', 'max:120'],
            'staff.*.role' => ['nullable', Rule::in(array_keys(CompetitionStaff::ROLES))],
            'staff.*.phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9 +().-]*$/'],

            'sessions' => ['nullable', 'array', 'max:20'],
            'sessions.*.id' => ['nullable', 'integer'],
            'sessions.*.day' => ['nullable', 'date'],
            'sessions.*.label' => ['nullable', 'string', 'max:100'],
            'sessions.*.doors_open_at' => ['nullable', 'date_format:H:i'],
            'sessions.*.warmup_at' => ['nullable', 'date_format:H:i'],
            'sessions.*.starts_at' => ['nullable', 'date_format:H:i'],
            'sessions.*.events' => ['nullable', 'string', 'max:3000'],

            'new_documents' => ['nullable', 'array', 'max:10'],
            'new_documents.*.file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'new_documents.*.title' => ['nullable', 'string', 'max:150'],
            'new_documents.*.visibility' => ['nullable', Rule::in(array_keys(CompetitionDocument::VISIBILITIES))],
            'documents' => ['nullable', 'array'],
            'documents.*.visibility' => ['nullable', Rule::in(array_keys(CompetitionDocument::VISIBILITIES))],
            'documents.*.delete' => ['nullable', 'boolean'],
        ], [
            'ends_on.after_or_equal' => 'La date de fin doit être le même jour ou après la date de début.',
            'return_at.after_or_equal' => 'Le retour doit être après le départ.',
            'response_deadline.before_or_equal' => 'La date limite de réponse doit précéder le début de la compétition.',
            'staff.*.phone.regex' => 'Un numéro de téléphone de l\'encadrement contient des caractères non valides.',
            'new_documents.*.file.mimes' => 'Les documents doivent être au format PDF.',
            'new_documents.*.file.max' => 'Un document dépasse 10 Mo.',
            'image.regex' => 'L\'image doit être une adresse web (https://…) ou un chemin de la médiathèque (/storage/…).',
            'description.max' => 'La présentation est trop longue.',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['description'] = \App\Support\HtmlSanitizer::clean($data['description'] ?? null);   // HTML de l'éditeur nettoyé
        $data['entries_visible'] = $request->boolean('entries_visible');

        // Les tableaux sont enregistrés par syncChildren()
        return collect($data)->except(['groups', 'staff', 'sessions', 'new_documents', 'documents'])->all();
    }

    /** Groupes, encadrement, programme et documents : remplacés par le contenu du formulaire. */
    private function syncChildren(Request $request, Competition $competition): void
    {
        $competition->groups()->sync($request->input('groups', []));

        // Encadrement : les lignes sans nom sont ignorées
        $competition->staff()->delete();
        $ordre = 0;
        foreach ($request->input('staff', []) as $ligne) {
            if (blank($ligne['name'] ?? null)) {
                continue;
            }
            $competition->staff()->create([
                'name' => trim($ligne['name']),
                'role' => $ligne['role'] ?? 'entraineur',
                'phone' => $ligne['phone'] ?? null,
                'sort_order' => ++$ordre,
            ]);
        }

        // Programme : une réunion sans date est ignorée.
        // Les réunions existantes sont mises à jour (et non recréées) pour conserver leur grille d'épreuves.
        $conservees = [];
        $ordre = 0;
        foreach ($request->input('sessions', []) as $ligne) {
            if (blank($ligne['day'] ?? null)) {
                continue;
            }
            $valeurs = [
                'day' => $ligne['day'],
                'label' => filled($ligne['label'] ?? null) ? trim($ligne['label']) : 'Réunion',
                'doors_open_at' => $ligne['doors_open_at'] ?? null,
                'warmup_at' => $ligne['warmup_at'] ?? null,
                'starts_at' => $ligne['starts_at'] ?? null,
                'events' => $ligne['events'] ?? null,
                'sort_order' => ++$ordre,
            ];
            $existante = filled($ligne['id'] ?? null) ? $competition->sessions()->find($ligne['id']) : null;
            if ($existante) {
                $existante->update($valeurs);
                $conservees[] = $existante->id;
            } else {
                $conservees[] = $competition->sessions()->create($valeurs)->id;
            }
        }
        // Réunions retirées du formulaire (leurs épreuves sont supprimées en cascade)
        $competition->sessions()->whereNotIn('id', $conservees ?: [0])->delete();

        // Documents existants : visibilité ou suppression
        foreach ($request->input('documents', []) as $id => $ligne) {
            $document = $competition->documents()->find($id);
            if (! $document) {
                continue;
            }
            if (! empty($ligne['delete'])) {
                Storage::disk(self::DISK)->delete($document->file_path);
                $document->delete();
            } elseif (! empty($ligne['visibility'])) {
                $document->update(['visibility' => $ligne['visibility']]);
            }
        }

        // Nouveaux documents (PDF)
        foreach ($request->file('new_documents', []) as $i => $fichiers) {
            $file = $fichiers['file'] ?? null;
            if (! $file || ! $file->isValid()) {
                continue;
            }
            $meta = $request->input("new_documents.$i", []);
            $competition->documents()->create([
                'title' => filled($meta['title'] ?? null) ? trim($meta['title']) : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_path' => $file->store(self::FOLDER . '/' . $competition->id, self::DISK),
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'visibility' => $meta['visibility'] ?? 'members',
            ]);
        }
    }

    // ------------------------------------------------------------------ espace admin / éditeur

    private function area(): string
    {
        return request()->routeIs('editor.*') ? 'editor' : 'admin';
    }

    private function route(string $name): string
    {
        return $this->area() . '.competitions.' . $name;
    }

    private function view(string $name, array $data)
    {
        return view('admin.competitions.' . $name, $data + [
            'area' => $this->area(),
            'layout' => 'layouts.' . $this->area(),
        ]);
    }
}
