<?php

namespace App\Http\Controllers\Admin\Planning;

use App\Models\FfnLicense;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\User;
use App\Services\FfnLicenseCsvParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Planning → Nageurs → « Ajouter depuis les licences FFN » (administrateurs uniquement).
 *
 * Liste les licenciés d'une saison qui n'ont pas encore de fiche nageur.
 * Pour chaque licencié coché :
 *   - une fiche nageur existante est reprise si elle correspond (même nom, prénom et année de naissance,
 *     sans autre licence) : on la complète et on la lie à la licence ;
 *   - sinon une nouvelle fiche est créée (prénom, nom, année, sexe, n° de licence).
 * Les groupes choisis sont ajoutés aux fiches ; le compte du site dont l'e-mail correspond peut être rattaché.
 */
class SwimmerFromLicenseController extends PlanningController
{
    public function index(Request $request)
    {
        $seasons = FfnLicense::query()->distinct()->orderByDesc('season')->pluck('season');
        $season = (int) ($request->query('saison') ?: $seasons->first());
        $type = $request->query('type');
        $search = trim((string) $request->query('q', ''));

        $base = FfnLicense::season($season)->whereNull('planning_swimmer_id');

        $licenses = (clone $base)
            ->when($type, fn ($q) => $q->where('license_type', $type))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('last_name', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")))
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        $matches = $this->existingSwimmers($licenses);

        return $this->planningView('swimmers.from-licenses', [
            'licenses' => $licenses,
            'matches' => $matches,
            'seasons' => $seasons,
            'season' => $season,
            'type' => $type,
            'search' => $search,
            'types' => (clone $base)->select('license_type', DB::raw('count(*) as total'))
                ->groupBy('license_type')->orderByDesc('total')->pluck('total', 'license_type'),
            'remaining' => (clone $base)->count(),
            'groups' => PlanningGroup::ordered()->get(),
            'selectedGroup' => $request->integer('groupe') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'licenses' => ['required', 'array', 'min:1'],
            'licenses.*' => ['integer', 'exists:ffn_licenses,id'],
            'groups' => ['nullable', 'array'],
            'groups.*' => ['integer', 'exists:planning_groups,id'],
            'link_accounts' => ['nullable', 'boolean'],
        ], [
            'licenses.required' => 'Cochez au moins un licencié.',
            'licenses.min' => 'Cochez au moins un licencié.',
        ]);

        $licenses = FfnLicense::whereIn('id', $data['licenses'])->whereNull('planning_swimmer_id')->get();
        $matches = $this->existingSwimmers($licenses);
        $groups = $data['groups'] ?? [];
        $comptes = $request->boolean('link_accounts')
            ? User::whereIn('email', $licenses->pluck('email')->filter()->all())->get()->keyBy(fn ($u) => mb_strtolower($u->email))
            : collect();

        $compteurs = ['created' => 0, 'linked' => 0, 'accounts' => 0];

        DB::transaction(function () use ($licenses, $matches, $groups, $comptes, &$compteurs) {
            foreach ($licenses as $license) {
                $nageur = $matches->get($license->id);
                $annee = $license->birth_date?->year;

                if ($nageur) {
                    // Fiche existante : on la complète avec les informations officielles
                    $nageur->fill([
                        'license_number' => $license->iuf,
                        'gender' => $license->gender ?? $nageur->gender,
                        'birth_year' => $annee ?? $nageur->birth_year,
                    ])->save();
                    $compteurs['linked']++;
                } else {
                    $nageur = PlanningSwimmer::create([
                        'first_name' => $license->first_name,
                        'last_name' => $license->last_name,
                        'birth_year' => $annee,
                        'gender' => $license->gender,
                        'license_number' => $license->iuf,
                        'is_active' => true,
                        'notes' => "Fiche créée depuis les licences FFN (saison {$license->season}).",
                    ]);
                    $compteurs['created']++;
                }

                if ($groups) {
                    $nageur->groups()->syncWithoutDetaching($groups);
                }

                $compte = $license->email ? $comptes->get(mb_strtolower($license->email)) : null;
                if ($compte && ! $nageur->user_id) {
                    $nageur->update(['user_id' => $compte->id]);
                    $compteurs['accounts']++;
                }

                $license->update(['planning_swimmer_id' => $nageur->id]);
            }
        });

        $message = sprintf('%d fiche(s) nageur créée(s), %d fiche(s) existante(s) reliée(s) à leur licence.', $compteurs['created'], $compteurs['linked']);
        if ($compteurs['accounts']) {
            $message .= " {$compteurs['accounts']} compte(s) du site rattaché(s).";
        }

        return redirect()->route('admin.planning.swimmers.index', array_filter(['groupe' => $groups[0] ?? null]))
            ->with('success', $message);
    }

    /**
     * Fiches nageur existantes correspondant aux licences (même nom + prénom, même année de naissance
     * ou année inconnue, sans n° de licence ou avec le même). Une correspondance ambiguë (plusieurs fiches)
     * n'est pas reprise : une nouvelle fiche sera créée.
     *
     * @return Collection<int, PlanningSwimmer>  clé = id de la licence
     */
    private function existingSwimmers(Collection $licenses): Collection
    {
        if ($licenses->isEmpty()) {
            return collect();
        }

        $nageurs = PlanningSwimmer::all();
        $parLicence = $nageurs->filter(fn ($n) => filled($n->license_number))
            ->keyBy(fn ($n) => preg_replace('/\s+/', '', $n->license_number));
        $parNom = $nageurs->groupBy(fn ($n) => FfnLicenseCsvParser::nameKey($n->last_name) . '|' . FfnLicenseCsvParser::nameKey($n->first_name));

        $resultat = collect();
        foreach ($licenses as $license) {
            if ($parLicence->has($license->iuf)) {
                $resultat->put($license->id, $parLicence->get($license->iuf));
                continue;
            }

            $candidats = ($parNom->get(FfnLicenseCsvParser::nameKey($license->last_name) . '|' . FfnLicenseCsvParser::nameKey($license->first_name)) ?? collect())
                ->filter(fn ($n) => ! $n->birth_year || ! $license->birth_date || (int) $n->birth_year === $license->birth_date->year)
                ->filter(fn ($n) => blank($n->license_number));

            if ($candidats->count() === 1) {
                $resultat->put($license->id, $candidats->first());
            }
        }

        return $resultat;
    }
}
