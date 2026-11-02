<?php

namespace App\Http\Controllers\Admin\Members;

use App\Models\Member;
use App\Models\MemberCategory;
use App\Models\MemberLog;
use App\Models\Membership;
use App\Services\MemberSheetParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Reprise du tableau des adhérents (copier-coller depuis Excel ou fichier CSV) : aperçu, puis enregistrement.
 * Un adhérent déjà connu (même nom, prénom et date de naissance) est mis à jour ; les cellules vides n'effacent rien.
 */
class MemberImportController extends MembersArea
{
    public function create(Request $request): View
    {
        return $this->membersView('import', ['season' => $request->query('saison', Membership::currentSeason()), 'text' => '', 'parsed' => null, 'preview' => []]);
    }

    public function store(Request $request, MemberSheetParser $parser): View|RedirectResponse
    {
        $data = $request->validate([
            'season' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'text' => ['nullable', 'string', 'max:2000000'],
            'file' => ['nullable', 'file', 'mimes:csv,txt,tsv', 'max:5120'],
            'confirm' => ['nullable', 'boolean'],
        ]);
        $text = $request->hasFile('file') ? file_get_contents($request->file('file')->getRealPath()) : (string) ($data['text'] ?? '');
        if (trim($text) === '') {
            throw ValidationException::withMessages(['text' => 'Collez le tableau ou choisissez un fichier CSV.']);
        }

        $parsed = $parser->parse($text);
        $preview = $this->preview($parsed['rows']);

        if (! $request->boolean('confirm') || $parsed['missing'] || ! $parsed['rows']) {
            return $this->membersView('import', ['season' => $data['season'], 'text' => $text, 'parsed' => $parsed, 'preview' => $preview]);
        }

        $counts = ['created' => 0, 'updated' => 0, 'categories' => 0];
        Member::$logging = Membership::$logging = false;

        try {
            DB::transaction(function () use ($parsed, $preview, $data, $request, &$counts) {
                $categories = MemberCategory::all()->keyBy(fn ($c) => mb_strtoupper($c->code));

                foreach ($parsed['rows'] as $i => $row) {
                    $member = $preview[$i]['member'] ?? new Member();
                    $isNew = ! $member->exists;
                    foreach (['last_name', 'first_name', 'gender', 'birth_date', 'birth_place', 'address', 'postal_code', 'city', 'phone1', 'phone2', 'email', 'image_rights'] as $champ) {
                        if ($row[$champ] !== null && $row[$champ] !== '') {
                            $member->$champ = $row[$champ];
                        }
                    }
                    $member->save();

                    $categorie = null;
                    if ($row['category']) {
                        $categorie = $categories->get($row['category']);
                        if (! $categorie) {
                            $categorie = MemberCategory::create(['code' => $row['category'], 'label' => Str::title(Str::lower(str_replace('-', ' ', $row['category']))),
                                'price_cents' => $row['price_cents'] ?? 0, 'sort_order' => 100]);
                            $categories->put($row['category'], $categorie);
                            $counts['categories']++;
                        }
                    }

                    $adhesion = Membership::firstOrNew(['member_id' => $member->id, 'season' => $data['season']]);
                    $adhesion->fill([
                        'situation' => $row['situation'] ?? $adhesion->situation,
                        'member_category_id' => $categorie?->id ?? $adhesion->member_category_id,
                        'price_cents' => $row['price_cents'] ?? $categorie?->price_cents ?? $adhesion->price_cents ?? 0,
                        'discount_cents' => $row['discount_cents'],
                        'status' => $row['status'] ?? ($adhesion->exists ? $adhesion->status : ($row['file_complete'] ? 'complet' : 'en_cours')),
                        'file_complete' => $row['file_complete'],
                        'pass_sport' => $row['pass_sport'],
                        'pass_sport_code' => $row['pass_sport_code'] ?? $adhesion->pass_sport_code,
                        'payment_certificate' => $row['payment_certificate'],
                        'mini_coach' => $row['mini_coach'],
                        'medical_certificate_year' => $row['medical_certificate_year'] ?? $adhesion->medical_certificate_year,
                        'observations' => $row['observations'] ?? $adhesion->observations,
                    ])->save();

                    MemberLog::create(['member_id' => $member->id, 'user_id' => $request->user()->id, 'action' => 'imported',
                        'subject' => 'Import du tableau (' . $data['season'] . ')', 'created_at' => now()]);
                    $counts[$isNew ? 'created' : 'updated']++;
                }
            });
        } finally {
            Member::$logging = Membership::$logging = true;
        }

        return redirect()->route($this->membersRoute('index'), ['saison' => $data['season']])->with('success', sprintf(
            'Import terminé : %d adhérent(s) créé(s), %d mis à jour%s.',
            $counts['created'], $counts['updated'], $counts['categories'] ? ", {$counts['categories']} catégorie(s) ajoutée(s) (tarifs à vérifier)" : ''
        ));
    }

    /** Pour chaque ligne : adhérent déjà connu ? (nom + prénom + date de naissance) */
    private function preview(array $rows): array
    {
        $cle = fn ($nom, $prenom, $date) => MemberSheetParser::normalize($nom) . '|' . MemberSheetParser::normalize($prenom) . '|' . $date;
        $existants = Member::all()->keyBy(fn ($m) => $cle($m->last_name, $m->first_name, $m->birth_date?->toDateString()));

        return array_map(fn ($r) => ['member' => $existants->get($cle($r['last_name'], $r['first_name'], $r['birth_date']))], $rows);
    }
}
