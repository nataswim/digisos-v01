<?php

namespace App\Http\Controllers\Admin\Members;

use App\Models\Member;
use App\Models\MemberCategory;
use App\Models\Membership;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Adhérents : liste de la saison, fiche, création / modification, nouvelle saison, attestation, export,
 * liens avec les licences FFN, le planning (fiche nageur) et les comptes du site.
 */
class MemberController extends MembersArea
{
    public function index(Request $request): View
    {
        $season = $this->season($request);
        $f = [
            'q' => trim((string) $request->query('q', '')),
            'statut' => array_key_exists($request->query('statut'), Membership::STATUSES) ? $request->query('statut') : null,
            'categorie' => $request->integer('categorie') ?: null,
            'filtre' => in_array($request->query('filtre'), ['dossier', 'passsport', 'image', 'certificat', 'reste', 'minicoach', 'attestation'], true) ? $request->query('filtre') : null,
        ];

        $memberships = $this->query($season, $f)->get();
        if ($f['filtre'] === 'certificat') {
            $memberships = $memberships->filter(fn ($m) => $m->certificateAlert() !== null);
        }
        if ($f['filtre'] === 'reste') {
            $memberships = $memberships->filter(fn ($m) => $m->due_cents > 0 && $m->status !== 'abandon');
        }

        return $this->membersView('index', [
            'season' => $season,
            'seasons' => $this->seasons($season),
            'memberships' => $memberships->sortBy(fn ($m) => mb_strtolower($m->member->last_name . ' ' . $m->member->first_name))->values(),
            'categories' => MemberCategory::ordered()->get(),
            'filters' => $f,
            'nextSeason' => Membership::nextSeason($season),
            'nextExists' => Membership::where('season', Membership::nextSeason($season))->exists(),
        ]);
    }

    public function create(Request $request): View
    {
        $season = $this->season($request);

        return $this->form(new Member(), new Membership(['season' => $season, 'status' => 'preinscrit', 'situation' => 'nouveau']));
    }

    public function store(Request $request): RedirectResponse
    {
        [$memberData, $membershipData] = $this->validated($request);

        $member = DB::transaction(function () use ($memberData, $membershipData) {
            $member = Member::create($memberData);
            $member->memberships()->create($membershipData);

            return $member;
        });

        return redirect()->route($this->membersRoute('show'), [$member, 'saison' => $membershipData['season']])
            ->with('success', "Adhérent {$member->full_name} enregistré pour la saison {$membershipData['season']}.");
    }

    public function show(Request $request, Member $member): View
    {
        $member->load(['memberships.category', 'memberships.payments', 'swimmer.groups', 'user', 'logs.user']);
        $season = $request->query('saison') ?: ($member->memberships->first()?->season ?? Membership::currentSeason());

        return $this->membersView('show', [
            'member' => $member,
            'season' => $season,
            'membership' => $member->membershipFor($season),
            'license' => $member->findLicense(),
            'family' => $this->family($member, $season),
            'groups' => PlanningGroup::active()->ordered()->get(),
            'swimmers' => $member->swimmer ? collect() : PlanningSwimmer::orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }

    public function edit(Request $request, Member $member): View
    {
        $season = $request->query('saison') ?: Membership::currentSeason();
        $membership = $member->membershipFor($season) ?? new Membership(['season' => $season, 'status' => 'preinscrit', 'situation' => 'renouvellement']);

        return $this->form($member, $membership);
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        [$memberData, $membershipData] = $this->validated($request, $member);

        DB::transaction(function () use ($member, $memberData, $membershipData) {
            $member->update($memberData);
            $membership = $member->memberships()->firstOrNew(['season' => $membershipData['season']]);
            $membership->fill($membershipData)->save();
        });

        return redirect()->route($this->membersRoute('show'), [$member, 'saison' => $membershipData['season']])
            ->with('success', 'Fiche enregistrée.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $nom = $member->full_name;
        $member->delete();

        return redirect()->route($this->membersRoute('index'))->with('success', "Adhérent {$nom} supprimé (avec ses adhésions et règlements).");
    }

    /** Supprimer seulement l'adhésion d'une saison. */
    public function destroyMembership(Member $member, Membership $membership): RedirectResponse
    {
        abort_unless($membership->member_id === $member->id, 404);
        $saison = $membership->season;
        $membership->delete();

        return redirect()->route($this->membersRoute('show'), $member)->with('success', "Adhésion {$saison} supprimée.");
    }

    /** Nouvelle saison : chaque adhérent non « abandon » de la saison reçoit une adhésion « pré-inscrit / renouvellement ». */
    public function prepareSeason(Request $request): RedirectResponse
    {
        $from = $this->season($request);
        $to = Membership::nextSeason($from);
        $count = 0;

        DB::transaction(function () use ($from, $to, &$count) {
            foreach (Membership::with('category')->where('season', $from)->where('status', '!=', 'abandon')->get() as $old) {
                if (Membership::where(['member_id' => $old->member_id, 'season' => $to])->exists()) {
                    continue;
                }
                Membership::create([
                    'member_id' => $old->member_id,
                    'season' => $to,
                    'situation' => 'renouvellement',
                    'member_category_id' => $old->member_category_id,
                    'price_cents' => $old->category?->price_cents ?? $old->price_cents,
                    'status' => 'preinscrit',
                    'medical_certificate_year' => $old->medical_certificate_year,
                    'mini_coach' => $old->mini_coach,
                ]);
                $count++;
            }
        });

        return redirect()->route($this->membersRoute('index'), ['saison' => $to])
            ->with('success', "Saison {$to} préparée : {$count} adhérent(s) à renouveler (statut « Pré-inscrit »).");
    }

    /** Attestation de paiement imprimable ; enregistre la remise de l'attestation. */
    public function attestation(Request $request, Member $member, Membership $membership): View
    {
        abort_unless($membership->member_id === $member->id, 404);
        $membership->load(['payments', 'category']);
        if ($request->boolean('remise') && ! $membership->payment_certificate) {
            $membership->update(['payment_certificate' => true, 'payment_certificate_on' => today()]);
        }

        return view('admin.members.attestation', ['member' => $member, 'membership' => $membership]);
    }

    /** Créer la fiche nageur du planning (ou rattacher une fiche existante). */
    public function linkSwimmer(Request $request, Member $member): RedirectResponse
    {
        $data = $request->validate([
            'swimmer' => ['nullable', 'integer', 'exists:planning_swimmers,id'],
            'groups' => ['nullable', 'array'],
            'groups.*' => ['integer', 'exists:planning_groups,id'],
        ]);

        $swimmer = ! empty($data['swimmer']) ? PlanningSwimmer::find($data['swimmer']) : PlanningSwimmer::create([
            'first_name' => $member->first_name,
            'last_name' => $member->last_name,
            'birth_year' => $member->birth_year,
            'gender' => $member->gender,
            'license_number' => $member->ffn_iuf,
            'user_id' => $member->user_id,
            'is_active' => true,
        ]);
        if (! empty($data['groups'])) {
            $swimmer->groups()->syncWithoutDetaching($data['groups']);
        }
        $member->update(['planning_swimmer_id' => $swimmer->id]);

        return back()->with('success', "Fiche nageur « {$swimmer->full_name} » rattachée.");
    }

    /** Rattacher le compte du site dont l'e-mail correspond (ou en retirer le lien). */
    public function linkUser(Request $request, Member $member): RedirectResponse
    {
        if ($request->boolean('detach')) {
            $member->update(['user_id' => null]);

            return back()->with('success', 'Compte du site détaché.');
        }
        $user = User::where('email', $request->input('email', $member->email))->first();
        if (! $user) {
            return back()->with('error', "Aucun compte du site avec l'e-mail « " . e($request->input('email', $member->email)) . ' ».');
        }
        $member->update(['user_id' => $user->id]);
        if ($member->swimmer && ! $member->swimmer->user_id) {
            $member->swimmer->update(['user_id' => $user->id]);
        }

        return back()->with('success', "Compte « {$user->email} » rattaché.");
    }

    /** Compléter les champs vides depuis la licence FFN. */
    public function fillFromLicense(Member $member): RedirectResponse
    {
        $licence = $member->findLicense();
        if (! $licence) {
            return back()->with('error', 'Aucune licence FFN correspondante.');
        }

        $champs = [
            'ffn_iuf' => $licence->iuf,
            'gender' => $licence->gender,
            'address' => trim($licence->address . ' ' . $licence->address2) ?: null,
            'postal_code' => $licence->postal_code,
            'city' => $licence->city,
            'phone1' => $licence->phone1,
            'phone2' => $licence->phone2,
            'email' => $licence->email,
        ];
        $remplis = [];
        foreach ($champs as $champ => $valeur) {
            if (blank($member->$champ) && filled($valeur)) {
                $member->$champ = $valeur;
                $remplis[] = $champ;
            }
        }
        $member->save();

        // Année du certificat : sur l'adhésion de la saison en cours si elle est vide
        $adhesion = $member->membershipFor(Membership::currentSeason());
        if ($adhesion && ! $adhesion->medical_certificate_year && $licence->medical_certificate_on) {
            $adhesion->update(['medical_certificate_year' => $licence->medical_certificate_on->year]);
            $remplis[] = 'certificat';
        }

        return back()->with('success', $remplis ? count($remplis) . ' champ(s) complété(s) depuis la licence FFN.' : 'Rien à compléter : les champs étaient déjà remplis.');
    }

    /** Export CSV de la saison (Excel : séparateur « ; », UTF-8). */
    public function export(Request $request): StreamedResponse
    {
        $season = $this->season($request);
        $type = $request->query('type') === 'image' ? 'image' : 'complet';
        $rows = $this->query($season, [])->get()
            ->when($type === 'image', fn ($c) => $c->filter(fn ($m) => $m->member->image_rights === false))
            ->sortBy(fn ($m) => mb_strtolower($m->member->last_name . ' ' . $m->member->first_name));

        $oui = fn ($b) => $b === null ? '' : ($b ? 'O' : 'N');
        $euros = fn ($c) => number_format($c / 100, 2, ',', '');
        $entetes = $type === 'image'
            ? ['NOM', 'Prénom', 'Catégorie', 'Groupes']
            : ['Observations', "saisie pass'sport", 'Code Pass\'Sport', 'Montant Pass\'Sport', 'Dossier complet', 'Attest. Paie.', 'mini-coach', 'NOM', 'Prénom', 'Sexe',
                'Date naissance', 'Année de naissance', 'lieu de naissance', 'Adresse', 'Code postal', 'Ville', 'Téléphone1', 'Téléphone 2', 'Mail',
                "Droit à l'image", 'Année Certificat Médical', 'Situation', 'Catégorie', 'Tarif', 'Statut', 'Remise', 'Montant', 'Réglé', 'Reste à payer',
                'Responsable légal 1', 'Tél. responsable 1', 'Responsable légal 2', 'Tél. responsable 2'];

        return response()->streamDownload(function () use ($rows, $entetes, $type, $oui, $euros) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\u{FEFF}");
            fputcsv($out, $entetes, ';');
            foreach ($rows as $m) {
                $p = $m->member;
                if ($type === 'image') {
                    fputcsv($out, [$p->last_name, $p->first_name, $m->category?->code, $p->swimmer?->groups?->pluck('name')->join(', ')], ';');
                    continue;
                }
                $g = array_values((array) $p->guardians);
                fputcsv($out, [
                    $m->observations, $oui($m->pass_sport), $m->pass_sport_code, $m->pass_sport ? $euros($m->pass_sport_cents) : '',
                    $oui($m->file_complete), $oui($m->payment_certificate), $oui($m->mini_coach),
                    $p->last_name, $p->first_name, $p->gender, $p->birth_date?->format('d/m/Y'), $p->birth_year, $p->birth_place,
                    $p->address, $p->postal_code, $p->city, $p->phone1, $p->phone2, $p->email, $oui($p->image_rights),
                    $m->medical_certificate_year, Membership::SITUATIONS[$m->situation] ?? '', $m->category?->code, $euros($m->price_cents),
                    $m->status_label, $euros($m->discount_cents), $euros($m->amount_cents), $euros($m->paid_cents), $euros($m->due_cents),
                    $g[0]['name'] ?? '', $g[0]['phone'] ?? '', $g[1]['name'] ?? '', $g[1]['phone'] ?? '',
                ], ';');
            }
            fclose($out);
        }, 'adherents-' . $season . ($type === 'image' ? '-droit-image-refuse' : '') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ------------------------------------------------------------------

    private function form(Member $member, Membership $membership): View
    {
        return $this->membersView('form', [
            'member' => $member,
            'membership' => $membership,
            'categories' => MemberCategory::ordered()->where(fn ($q) => $q->where('is_active', true)->orWhere('id', $membership->member_category_id))->get(),
            'seasons' => $this->seasons($membership->season),
            'family' => $member->exists ? $this->family($member, $membership->season) : collect(),
        ]);
    }

    private function validated(Request $request, ?Member $member = null): array
    {
        $data = $request->validate([
            'season' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'gender' => ['nullable', Rule::in(['F', 'M'])],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'birth_place' => ['nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone1' => ['nullable', 'string', 'max:30'],
            'phone2' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'image_rights' => ['nullable', Rule::in(['1', '0', ''])],
            'guardians' => ['nullable', 'array', 'max:2'],
            'guardians.*.name' => ['nullable', 'string', 'max:150'],
            'guardians.*.relation' => ['nullable', 'string', 'max:50'],
            'guardians.*.phone' => ['nullable', 'string', 'max:30'],
            'guardians.*.email' => ['nullable', 'email', 'max:255'],

            'situation' => ['nullable', Rule::in(array_keys(Membership::SITUATIONS))],
            'member_category_id' => ['nullable', 'integer', 'exists:member_categories,id'],
            'price' => ['nullable', 'string', 'max:20'],
            'discount' => ['nullable', 'string', 'max:20'],
            'status' => ['required', Rule::in(array_keys(Membership::STATUSES))],
            'file_items' => ['nullable', 'array'],
            'file_items.*' => [Rule::in(array_keys(Membership::FILE_ITEMS))],
            'file_complete' => ['nullable', 'boolean'],
            'pass_sport' => ['nullable', 'boolean'],
            'pass_sport_code' => ['nullable', 'string', 'max:40'],
            'pass_sport_amount' => ['nullable', 'string', 'max:20'],
            'payment_certificate' => ['nullable', 'boolean'],
            'payment_certificate_on' => ['nullable', 'date'],
            'mini_coach' => ['nullable', 'boolean'],
            'medical_certificate_year' => ['nullable', 'integer', 'min:1990', 'max:' . (now()->year + 1)],
            'observations' => ['nullable', 'string', 'max:3000'],
        ], [
            'season.regex' => 'Saison au format 2026-2027.',
            'last_name.required' => 'Le nom est obligatoire.',
            'first_name.required' => 'Le prénom est obligatoire.',
        ]);

        foreach (['price' => 'Tarif', 'discount' => 'Remise', 'pass_sport_amount' => "Montant Pass'Sport"] as $champ => $label) {
            if (filled($data[$champ] ?? null) && \App\Models\Membership::parseMoney($data[$champ]) === null) {
                throw \Illuminate\Validation\ValidationException::withMessages([$champ => "{$label} : montant illisible (exemple : 190 ou 190,00)."]);
            }
        }

        $categorie = ! empty($data['member_category_id']) ? MemberCategory::find($data['member_category_id']) : null;
        $prix = filled($data['price'] ?? null) ? Membership::parseMoney($data['price']) : ($categorie?->price_cents ?? 0);

        $guardians = collect($data['guardians'] ?? [])->map(fn ($g) => array_filter($g, 'filled'))->filter()->values()->all();

        $memberData = [
            'last_name' => trim($data['last_name']),
            'first_name' => trim($data['first_name']),
            'gender' => $data['gender'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'birth_place' => $data['birth_place'] ?? null,
            'address' => $data['address'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'phone1' => $data['phone1'] ?? null,
            'phone2' => $data['phone2'] ?? null,
            'email' => isset($data['email']) ? mb_strtolower($data['email']) : null,
            'image_rights' => ($data['image_rights'] ?? '') === '' ? null : $data['image_rights'] === '1',
            'guardians' => $guardians ?: null,
        ];

        $items = array_values($data['file_items'] ?? []);
        $membershipData = [
            'season' => $data['season'],
            'situation' => $data['situation'] ?? null,
            'member_category_id' => $categorie?->id,
            'price_cents' => $prix,
            'discount_cents' => Membership::parseMoney($data['discount'] ?? '') ?? 0,
            'status' => $data['status'],
            'file_items' => $items,
            'file_complete' => $request->boolean('file_complete') || count($items) === count(Membership::FILE_ITEMS),
            'pass_sport' => $request->boolean('pass_sport'),
            'pass_sport_code' => $data['pass_sport_code'] ?? null,
            'pass_sport_cents' => $request->boolean('pass_sport') ? (Membership::parseMoney($data['pass_sport_amount'] ?? '') ?? 0) : 0,
            'payment_certificate' => $request->boolean('payment_certificate'),
            'payment_certificate_on' => $request->boolean('payment_certificate') ? ($data['payment_certificate_on'] ?? today()->toDateString()) : null,
            'mini_coach' => $request->boolean('mini_coach'),
            'medical_certificate_year' => $data['medical_certificate_year'] ?? null,
            'observations' => $data['observations'] ?? null,
        ];

        return [$memberData, $membershipData];
    }

    /** Adhésions de la saison avec filtres. */
    private function query(string $season, array $f)
    {
        return Membership::with(['member.swimmer.groups', 'category', 'payments'])
            ->where('season', $season)
            ->when($f['statut'] ?? null, fn ($q, $s) => $q->where('status', $s))
            ->when($f['categorie'] ?? null, fn ($q, $c) => $q->where('member_category_id', $c))
            ->when(($f['filtre'] ?? null) === 'dossier', fn ($q) => $q->where('file_complete', false)->where('status', '!=', 'abandon'))
            ->when(($f['filtre'] ?? null) === 'passsport', fn ($q) => $q->where('pass_sport', true))
            ->when(($f['filtre'] ?? null) === 'minicoach', fn ($q) => $q->where('mini_coach', true))
            ->when(($f['filtre'] ?? null) === 'attestation', fn ($q) => $q->where('payment_certificate', false)->where('status', '!=', 'abandon'))
            ->when(($f['filtre'] ?? null) === 'image', fn ($q) => $q->whereHas('member', fn ($m) => $m->where('image_rights', false)))
            ->when(($f['q'] ?? '') !== '', fn ($q) => $q->whereHas('member', fn ($m) => $m->where(fn ($w) => $w
                ->where('last_name', 'like', '%' . $f['q'] . '%')->orWhere('first_name', 'like', '%' . $f['q'] . '%')
                ->orWhere('email', 'like', '%' . $f['q'] . '%')->orWhere('city', 'like', '%' . $f['q'] . '%'))));
    }

    private function season(Request $request): string
    {
        $s = (string) $request->query('saison', $request->input('saison', ''));

        return preg_match('/^\d{4}-\d{4}$/', $s) ? $s : Membership::currentSeason();
    }

    /** Saisons proposées : celles en base, la saison en cours et la suivante. */
    private function seasons(string $current): Collection
    {
        return Membership::query()->distinct()->pluck('season')
            ->push(Membership::currentSeason(), Membership::nextSeason(Membership::currentSeason()), $current)
            ->unique()->sortDesc()->values();
    }

    /** Autres adhérents du même foyer (même e-mail ou même adresse) avec une adhésion active la même saison. */
    private function family(Member $member, string $season): Collection
    {
        $keys = $member->householdKeys();
        if (! $keys) {
            return collect();
        }

        return Member::with(['memberships' => fn ($q) => $q->where('season', $season)->where('status', '!=', 'abandon')])
            ->where('id', '!=', $member->id)
            ->where(fn ($q) => $q->when($member->email, fn ($w) => $w->orWhere('email', $member->email))
                ->when($member->postal_code, fn ($w) => $w->orWhere('postal_code', $member->postal_code)))
            ->get()
            ->filter(fn ($m) => array_intersect($m->householdKeys(), $keys) && $m->memberships->isNotEmpty())
            ->values();
    }
}
