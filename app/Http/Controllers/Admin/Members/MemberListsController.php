<?php

namespace App\Http\Controllers\Admin\Members;

use App\Models\Membership;
use App\Models\PlanningSwimmer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Listes d'adhérents de la saison (adhésions non abandonnées), en petits tableaux :
 *  - par année de naissance (avec l'âge au 1er septembre de la saison) ;
 *  - par commune.
 * Chaque ligne indique le ou les groupes du planning : ceux de la fiche nageur liée à l'adhérent,
 * sinon ceux du nageur du planning de même nom, prénom et année de naissance (« trouvé par le nom »).
 * Accès : administrateurs et éditeurs (comme tout le module).
 */
class MemberListsController extends MembersArea
{
    public function byYear(Request $request): View
    {
        $season = $this->season($request);
        $rows = $this->rows($season);
        $debut = (int) substr($season, 0, 4);

        $years = $rows->groupBy(fn ($r) => $r['birth_year'] ?? 0)
            ->sortKeysDesc()   // les plus jeunes d'abord, « inconnue » (0) à la fin
            ->map(fn ($liste, $annee) => [
                'year' => $annee ?: null,
                'age' => $annee ? $debut - $annee : null,   // âge atteint dans l'année civile du début de saison
                'rows' => $liste->sortBy(fn ($r) => Str::slug($r['last_name'] . ' ' . $r['first_name']))->values(),
                'girls' => $liste->where('gender', 'F')->count(),
                'boys' => $liste->where('gender', 'M')->count(),
            ])->values();

        return $this->membersView('lists.by-year', ['season' => $season, 'years' => $years, 'total' => $rows->count()]);
    }

    public function byCity(Request $request): View
    {
        $season = $this->season($request);
        $rows = $this->rows($season);

        $cities = $rows->groupBy(fn ($r) => $r['city'] !== '' ? $r['postal_code'] . '|' . $r['city'] : '|')
            ->map(fn ($liste, $cle) => [
                'city' => explode('|', $cle)[1] ?: null,
                'postal_code' => explode('|', $cle)[0],
                'rows' => $liste->sortBy(fn ($r) => Str::slug($r['last_name'] . ' ' . $r['first_name']))->values(),
            ])
            ->sortBy([fn ($a, $b) => count($b['rows']) <=> count($a['rows']), fn ($a, $b) => strcmp(Str::slug((string) $a['city']), Str::slug((string) $b['city']))])
            ->sortBy(fn ($c) => $c['city'] === null ? 1 : 0)   // « non renseignée » à la fin
            ->values();

        return $this->membersView('lists.by-city', ['season' => $season, 'cities' => $cities, 'total' => $rows->count()]);
    }

    private function season(Request $request): string
    {
        return preg_match('/^\d{4}-\d{4}$/', (string) $request->query('saison')) ? $request->query('saison') : Membership::currentSeason();
    }

    /** Une ligne par adhérent actif de la saison, avec ses groupes. */
    private function rows(string $season): Collection
    {
        $adhesions = Membership::with(['member.swimmer.groups', 'category'])
            ->where('season', $season)->where('status', '!=', 'abandon')->get()
            ->filter(fn ($a) => $a->member);

        // Nageurs du planning pour retrouver le groupe d'un adhérent sans fiche liée (nom + prénom + année)
        $cle = fn ($nom, $prenom, $annee) => Str::slug($nom) . '|' . Str::slug($prenom) . '|' . $annee;
        $nageurs = PlanningSwimmer::with('groups')->where('is_active', true)->get()
            ->keyBy(fn ($n) => $cle($n->last_name, $n->first_name, $n->birth_year));

        return $adhesions->map(function ($a) use ($nageurs, $cle) {
            $m = $a->member;
            $annee = $m->birth_date?->year;
            $nageur = $m->swimmer ?? $nageurs->get($cle($m->last_name, $m->first_name, $annee));

            return [
                'member' => $m,
                'last_name' => (string) $m->last_name,
                'first_name' => (string) $m->first_name,
                'gender' => $m->gender,
                'birth_date' => $m->birth_date,
                'birth_year' => $annee,
                'postal_code' => preg_replace('/\D/', '', (string) $m->postal_code),
                'city' => mb_strtoupper(trim((string) $m->city)),
                'category' => $a->category?->code,
                'status' => $a->status,
                'groups' => $nageur?->groups ?? collect(),
                'swimmer' => $nageur,
                'swimmer_guessed' => $nageur && ! $m->swimmer,
            ];
        })->values();
    }
}
