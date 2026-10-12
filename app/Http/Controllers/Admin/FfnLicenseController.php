<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FfnLicense;
use App\Models\FfnLicenseImport;
use App\Models\PlanningSwimmer;
use App\Models\User;
use App\Services\FfnLicenseCsvParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Licences FFN : import du fichier « extraction licences » d'extraNat et consultation.
 * Réservé aux administrateurs (routes admin.ffn-licenses.*, jamais incluses dans l'espace éditeur).
 *
 * Déroulement d'un import :
 *  1. dépôt du fichier → lecture et contrôle (FfnLicenseCsvParser), résultat stocké en privé (storage/app/private/ffn-imports) ;
 *  2. prévisualisation : nouvelles licences, modifications, erreurs, nageurs du planning reconnus ;
 *  3. confirmation avec options → enregistrement, puis suppression du fichier temporaire.
 */
class FfnLicenseController extends Controller
{
    private const DISK = 'local';
    private const FOLDER = 'ffn-imports';

    // ================================================================== consultation

    public function index(Request $request)
    {
        $seasons = FfnLicense::query()->distinct()->orderByDesc('season')->pluck('season');
        $season = (int) ($request->query('saison') ?: $seasons->first());

        $search = trim((string) $request->query('q', ''));
        $type = $request->query('type');
        $lien = in_array($request->query('lien'), ['lie', 'non-lie'], true) ? $request->query('lien') : null;

        $base = FfnLicense::season($season);

        $licenses = (clone $base)
            ->with('swimmer')
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('last_name', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('iuf', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->when($type, fn ($q) => $q->where('license_type', $type))
            ->when($lien === 'lie', fn ($q) => $q->whereNotNull('planning_swimmer_id'))
            ->when($lien === 'non-lie', fn ($q) => $q->whereNull('planning_swimmer_id'))
            ->orderBy('last_name')->orderBy('first_name')
            ->paginate(50)
            ->withQueryString();

        return view('admin.ffn-licenses.index', [
            'licenses' => $licenses,
            'seasons' => $seasons,
            'season' => $season,
            'search' => $search,
            'type' => $type,
            'lien' => $lien,
            'types' => (clone $base)->select('license_type', DB::raw('count(*) as total'))->groupBy('license_type')->orderByDesc('total')->pluck('total', 'license_type'),
            'stats' => [
                'total' => (clone $base)->count(),
                'new' => (clone $base)->where('license_status', 'like', 'Nouvel%')->count(),
                'linked' => (clone $base)->whereNotNull('planning_swimmer_id')->count(),
                'girls' => (clone $base)->where('gender', 'F')->count(),
                'boys' => (clone $base)->where('gender', 'M')->count(),
            ],
            'lastImport' => FfnLicenseImport::with('user')->latest()->first(),
        ]);
    }

    public function show(FfnLicense $license)
    {
        $license->load(['swimmer.groups', 'import.user']);

        return view('admin.ffn-licenses.show', [
            'license' => $license,
            'otherSeasons' => FfnLicense::where('iuf', $license->iuf)->whereKeyNot($license->id)->orderByDesc('season')->get(['id', 'season', 'license_type']),
        ]);
    }

    /** Suppression de toutes les licences d'une saison (RGPD : données qui ne sont plus utiles). */
    public function destroySeason(int $season): RedirectResponse
    {
        $nombre = FfnLicense::season($season)->delete();

        return redirect()->route('admin.ffn-licenses.index')
            ->with('success', "{$nombre} licence(s) de la saison {$season} supprimée(s). Les fiches nageurs du planning sont conservées.");
    }

    // ================================================================== import

    public function create()
    {
        $this->purgeOldUploads();

        return view('admin.ffn-licenses.import', [
            'imports' => FfnLicenseImport::with('user')->latest()->limit(15)->get(),
        ]);
    }

    /** Étape 1 : dépôt et lecture du fichier. */
    public function upload(Request $request, FfnLicenseCsvParser $parser): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'file.required' => 'Choisissez le fichier exporté depuis extraNat.',
            'file.mimes' => 'Le fichier doit être au format CSV (export extraNat).',
            'file.max' => 'Le fichier dépasse 5 Mo.',
        ]);

        $fichier = $request->file('file');
        $resultat = $parser->parse($fichier->getRealPath());

        if ($resultat['missing']) {
            return back()->withErrors(['file' => 'Fichier non reconnu : colonne(s) manquante(s) ' . implode(', ', $resultat['missing']) . '. Utilisez l\'extraction des licences d\'extraNat.']);
        }
        if (! $resultat['rows']) {
            return back()->withErrors(['file' => 'Aucune licence valide dans ce fichier.'])->with('import_errors', $resultat['errors']);
        }
        if (count($resultat['seasons']) > 1) {
            return back()->withErrors(['file' => 'Le fichier contient plusieurs saisons (' . implode(', ', $resultat['seasons']) . ') : exportez une saison à la fois.']);
        }

        $token = (string) Str::uuid();
        Storage::disk(self::DISK)->put(self::FOLDER . "/{$token}.json", json_encode([
            'original_name' => mb_substr($fichier->getClientOriginalName(), 0, 255),
            'uploaded_by' => $request->user()->id,
            'parsed' => $resultat,
        ], JSON_UNESCAPED_UNICODE));

        return redirect()->route('admin.ffn-licenses.import.review', $token);
    }

    /** Étape 2 : prévisualisation. */
    public function review(string $token)
    {
        $data = $this->loadUpload($token);
        if (! $data) {
            return redirect()->route('admin.ffn-licenses.import.create')->with('error', 'Prévisualisation expirée : déposez à nouveau le fichier.');
        }

        $season = $data['parsed']['seasons'][0] ?? null;
        $lignes = $this->analyse($data['parsed']['rows'], $season);

        return view('admin.ffn-licenses.review', [
            'token' => $token,
            'originalName' => $data['original_name'],
            'season' => $season,
            'encoding' => $data['parsed']['encoding'],
            'rows' => $lignes,
            'importErrors' => $data['parsed']['errors'],
            'types' => collect($lignes)->countBy(fn ($l) => $l['row']['license_type'] ?? 'Non renseigné')->sortDesc(),
            'summary' => [
                'new' => collect($lignes)->where('status', 'new')->count(),
                'updated' => collect($lignes)->where('status', 'updated')->count(),
                'unchanged' => collect($lignes)->where('status', 'unchanged')->count(),
                'match_iuf' => collect($lignes)->where('match', 'iuf')->count(),
                'match_name' => collect($lignes)->where('match', 'name')->count(),
                'ambiguous' => collect($lignes)->where('match', 'ambiguous')->count(),
                'unmatched' => collect($lignes)->whereNull('match')->count(),
                'accounts' => collect($lignes)->filter(fn ($l) => $l['account'] && $l['swimmer'] && ! $l['swimmer']->user_id)->count(),
            ],
        ]);
    }

    /** Étape 3 : enregistrement. */
    public function store(Request $request, string $token): RedirectResponse
    {
        $data = $this->loadUpload($token);
        if (! $data) {
            return redirect()->route('admin.ffn-licenses.import.create')->with('error', 'Prévisualisation expirée : déposez à nouveau le fichier.');
        }

        $options = $request->validate([
            'update_swimmers' => ['nullable', 'boolean'],
            'create_swimmers' => ['nullable', 'boolean'],
            'create_types' => ['nullable', 'array'],
            'create_types.*' => ['string', 'max:60'],
            'link_accounts' => ['nullable', 'boolean'],
        ]);
        $typesACreer = $options['create_types'] ?? [];

        $season = $data['parsed']['seasons'][0] ?? null;
        $lignes = $this->analyse($data['parsed']['rows'], $season);
        $compteurs = array_fill_keys(['created', 'updated', 'unchanged', 'linked', 'swimmers_updated', 'swimmers_created', 'accounts'], 0);

        $import = DB::transaction(function () use ($request, $data, $season, $lignes, $typesACreer, &$compteurs) {
            $import = FfnLicenseImport::create([
                'user_id' => $request->user()->id,
                'original_name' => $data['original_name'],
                'season' => $season,
                'rows_total' => count($lignes) + count($data['parsed']['errors']),
                'error_count' => count($data['parsed']['errors']),
                'errors' => array_slice($data['parsed']['errors'], 0, 200),
            ]);

            foreach ($lignes as $ligne) {
                $row = $ligne['row'];
                $nageur = $ligne['swimmer'];

                // Création d'une fiche nageur pour un licencié non reconnu (types choisis)
                if (! $nageur && $ligne['match'] === null && $request->boolean('create_swimmers')
                    && in_array($row['license_type'] ?? 'Non renseigné', $typesACreer, true)) {
                    $nageur = PlanningSwimmer::create([
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'birth_year' => (int) substr($row['birth_date'], 0, 4),
                        'gender' => $row['gender'],
                        'license_number' => $row['iuf'],
                        'is_active' => true,
                        'notes' => 'Fiche créée par l\'import des licences FFN' . ($season ? " (saison {$season})" : '') . '.',
                    ]);
                    $compteurs['swimmers_created']++;
                } elseif ($nageur && $request->boolean('update_swimmers')) {
                    // Mise à jour des informations officielles de la fiche nageur
                    $nageur->fill([
                        'license_number' => $row['iuf'],
                        'gender' => $row['gender'] ?? $nageur->gender,
                        'birth_year' => (int) substr($row['birth_date'], 0, 4),
                    ]);
                    if ($nageur->isDirty()) {
                        $nageur->save();
                        $compteurs['swimmers_updated']++;
                    }
                }

                // Rattachement du compte du site dont l'e-mail correspond
                if ($nageur && ! $nageur->user_id && $ligne['account'] && $request->boolean('link_accounts')) {
                    $nageur->update(['user_id' => $ligne['account']->id]);
                    $compteurs['accounts']++;
                }

                // Licence (création ou mise à jour)
                $valeurs = collect($row)->only(FfnLicense::IMPORTED_FIELDS)->all() + [
                    'planning_swimmer_id' => $nageur?->id,
                    'ffn_license_import_id' => $import->id,
                ];
                FfnLicense::updateOrCreate(['season' => $season ?? (int) date('Y'), 'iuf' => $row['iuf']], $valeurs);

                $compteurs[$ligne['status'] === 'new' ? 'created' : ($ligne['status'] === 'updated' ? 'updated' : 'unchanged')]++;
                $compteurs['linked'] += $nageur ? 1 : 0;
            }

            $import->update([
                'created_count' => $compteurs['created'],
                'updated_count' => $compteurs['updated'],
                'unchanged_count' => $compteurs['unchanged'],
                'swimmers_linked' => $compteurs['linked'],
                'swimmers_updated' => $compteurs['swimmers_updated'],
                'swimmers_created' => $compteurs['swimmers_created'],
                'accounts_linked' => $compteurs['accounts'],
            ]);

            return $import;
        });

        Storage::disk(self::DISK)->delete(self::FOLDER . "/{$token}.json");

        return redirect()->route('admin.ffn-licenses.index', ['saison' => $import->season])
            ->with('success', sprintf(
                'Import terminé : %d nouvelle(s) licence(s), %d mise(s) à jour, %d identique(s). Nageurs : %d lié(s), %d fiche(s) mise(s) à jour, %d créée(s). Comptes rattachés : %d.',
                $compteurs['created'], $compteurs['updated'], $compteurs['unchanged'],
                $compteurs['linked'], $compteurs['swimmers_updated'], $compteurs['swimmers_created'], $compteurs['accounts']
            ));
    }

    public function cancel(string $token): RedirectResponse
    {
        if ($this->validToken($token)) {
            Storage::disk(self::DISK)->delete(self::FOLDER . "/{$token}.json");
        }

        return redirect()->route('admin.ffn-licenses.import.create')->with('success', 'Import annulé, le fichier déposé a été supprimé.');
    }

    // ================================================================== analyse

    /**
     * Pour chaque ligne : statut de la licence (nouvelle / modifiée / identique),
     * nageur du planning reconnu (par IUF, puis par nom + prénom + année de naissance)
     * et compte du site dont l'e-mail correspond.
     *
     * @return array<int, array{row: array, status: string, changes: array, swimmer: ?PlanningSwimmer, match: ?string, account: ?User}>
     */
    private function analyse(array $rows, ?int $season): array
    {
        $iufs = array_column($rows, 'iuf');
        $existantes = FfnLicense::where('season', $season ?? (int) date('Y'))->whereIn('iuf', $iufs)->get()->keyBy('iuf');

        $nageurs = PlanningSwimmer::all();
        $parLicence = $nageurs->filter(fn ($n) => filled($n->license_number))
            ->keyBy(fn ($n) => preg_replace('/\D/', '', $n->license_number));
        $parNom = $nageurs->groupBy(fn ($n) => FfnLicenseCsvParser::nameKey($n->last_name) . '|' . FfnLicenseCsvParser::nameKey($n->first_name));

        $emails = array_values(array_filter(array_column($rows, 'email')));
        $comptes = $emails
            ? User::whereIn('email', $emails)->get()->keyBy(fn ($u) => mb_strtolower($u->email))
            : collect();

        $resultat = [];
        foreach ($rows as $row) {
            // Licence existante ?
            $existante = $existantes->get($row['iuf']);
            $changements = $existante ? $this->changes($existante, $row) : [];
            $statut = ! $existante ? 'new' : ($changements ? 'updated' : 'unchanged');

            // Nageur du planning
            $nageur = null;
            $correspondance = null;
            if ($existante && $existante->planning_swimmer_id && $nageurs->contains('id', $existante->planning_swimmer_id)) {
                $nageur = $nageurs->firstWhere('id', $existante->planning_swimmer_id);
                $correspondance = 'iuf';
            } elseif ($parLicence->has($row['iuf'])) {
                $nageur = $parLicence->get($row['iuf']);
                $correspondance = 'iuf';
            } else {
                $annee = (int) substr($row['birth_date'], 0, 4);
                $candidats = ($parNom->get(FfnLicenseCsvParser::nameKey($row['last_name']) . '|' . FfnLicenseCsvParser::nameKey($row['first_name'])) ?? collect())
                    ->filter(fn ($n) => ! $n->birth_year || (int) $n->birth_year === $annee)
                    ->filter(fn ($n) => blank($n->license_number));   // une fiche déjà licenciée sous un autre IUF n'est pas reprise
                if ($candidats->count() === 1) {
                    $nageur = $candidats->first();
                    $correspondance = 'name';
                } elseif ($candidats->count() > 1) {
                    $correspondance = 'ambiguous';
                }
            }

            $resultat[] = [
                'row' => $row,
                'status' => $statut,
                'changes' => $changements,
                'swimmer' => $nageur,
                'match' => $correspondance,
                'account' => $row['email'] ? $comptes->get($row['email']) : null,
            ];
        }

        return $resultat;
    }

    /** Champs modifiés par rapport à la licence enregistrée. */
    private function changes(FfnLicense $license, array $row): array
    {
        $modifies = [];
        foreach (FfnLicense::IMPORTED_FIELDS as $champ) {
            $actuel = $license->{$champ};
            if ($actuel instanceof \DateTimeInterface) {
                $actuel = $actuel->format('Y-m-d');
            }
            $nouveau = $row[$champ] ?? null;
            if ($champ === 'activities') {
                $actuel = array_values($actuel ?? []);
                $nouveau = array_values($nouveau ?? []);
            }
            if ($actuel != $nouveau) {
                $modifies[] = $champ;
            }
        }

        return $modifies;
    }

    // ================================================================== fichiers temporaires

    private function validToken(string $token): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $token);
    }

    private function loadUpload(string $token): ?array
    {
        $chemin = self::FOLDER . "/{$token}.json";
        if (! $this->validToken($token) || ! Storage::disk(self::DISK)->exists($chemin)) {
            return null;
        }

        return json_decode(Storage::disk(self::DISK)->get($chemin), true) ?: null;
    }

    /** Supprime les prévisualisations abandonnées depuis plus de 24 h. */
    private function purgeOldUploads(): void
    {
        $disque = Storage::disk(self::DISK);
        foreach ($disque->files(self::FOLDER) as $fichier) {
            if ($disque->lastModified($fichier) < now()->subDay()->getTimestamp()) {
                $disque->delete($fichier);
            }
        }
    }
}
