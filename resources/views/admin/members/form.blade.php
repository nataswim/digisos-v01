@extends($layout)

@section('title', ($member->exists ? $member->full_name : 'Nouvel adhérent') . ' — ' . $membership->season)

@section('content')
@php
    use App\Models\Membership as M;
    $r = fn ($n, $p = []) => route($area . '.members.' . $n, $p);
    $v = fn ($champ, $defaut = null) => old($champ, $defaut);
    $guardians = old('guardians', array_values((array) $member->guardians));
    $items = old('file_items', (array) $membership->file_items);
    $nbFamille = $family->count() + 1;
@endphp
<div class="container-lg">
    <a href="{{ $member->exists ? $r('show', [$member, 'saison' => $membership->season]) : $r('index', ['saison' => $membership->season]) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ $member->exists ? $member->full_name : 'Adhérents' }}
    </a>
    <h1 class="h3 fw-bold mb-4">{{ $member->exists ? 'Modifier : ' . $member->full_name : 'Nouvel adhérent' }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $member->exists ? $r('update', $member) : $r('store') }}" id="member-form">
        @csrf
        @if ($member->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-7">
                {{-- Identité --}}
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Identité</h2></div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label for="last_name" class="form-label fw-semibold">NOM <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="text" id="last_name" name="last_name" required maxlength="100" class="form-control text-uppercase" value="{{ $v('last_name', $member->last_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="first_name" class="form-label fw-semibold">Prénom <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="text" id="first_name" name="first_name" required maxlength="100" class="form-control" value="{{ $v('first_name', $member->first_name) }}">
                        </div>
                        <div class="col-6 col-md-3">
                            <label for="gender" class="form-label fw-semibold">Sexe</label>
                            <select id="gender" name="gender" class="form-select">
                                <option value="">—</option>
                                <option value="F" @selected($v('gender', $member->gender) === 'F')>F</option>
                                <option value="M" @selected($v('gender', $member->gender) === 'M')>M</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4">
                            <label for="birth_date" class="form-label fw-semibold">Date de naissance</label>
                            <input type="date" id="birth_date" name="birth_date" class="form-control" value="{{ $v('birth_date', $member->birth_date?->toDateString()) }}" max="{{ today()->toDateString() }}" data-birth>
                        </div>
                        <div class="col-md-5">
                            <label for="birth_place" class="form-label fw-semibold">Lieu de naissance</label>
                            <input type="text" id="birth_place" name="birth_place" maxlength="150" class="form-control" value="{{ $v('birth_place', $member->birth_place) }}" placeholder="Ville (département)">
                        </div>
                        <div class="col-md-6">
                            <span class="form-label fw-semibold d-block">Droit à l'image</span>
                            @php $ir = $v('image_rights', $member->image_rights === null ? '' : ($member->image_rights ? '1' : '0')); @endphp
                            <div class="btn-group" role="group" aria-label="Droit à l'image">
                                @foreach (['1' => 'Oui', '0' => 'Non', '' => 'Non renseigné'] as $val => $label)
                                    <input type="radio" class="btn-check" name="image_rights" id="ir{{ $val === '' ? 'x' : $val }}" value="{{ $val }}" @checked((string) $ir === (string) $val)>
                                    <label class="btn btn-outline-secondary btn-sm mbr-chip" for="ir{{ $val === '' ? 'x' : $val }}">{{ $label }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Coordonnées --}}
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Coordonnées</h2></div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">Adresse</label>
                            <input type="text" id="address" name="address" maxlength="255" class="form-control" value="{{ $v('address', $member->address) }}" autocomplete="off">
                        </div>
                        <div class="col-4">
                            <label for="postal_code" class="form-label fw-semibold">Code postal</label>
                            <input type="text" id="postal_code" name="postal_code" maxlength="10" class="form-control" value="{{ $v('postal_code', $member->postal_code) }}" autocomplete="off">
                        </div>
                        <div class="col-8">
                            <label for="city" class="form-label fw-semibold">Ville</label>
                            <input type="text" id="city" name="city" maxlength="100" class="form-control" value="{{ $v('city', $member->city) }}" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label for="phone1" class="form-label fw-semibold">Téléphone 1</label>
                            <input type="tel" id="phone1" name="phone1" maxlength="30" class="form-control" value="{{ $v('phone1', $member->phone1) }}" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label for="phone2" class="form-label fw-semibold">Téléphone 2</label>
                            <input type="tel" id="phone2" name="phone2" maxlength="30" class="form-control" value="{{ $v('phone2', $member->phone2) }}" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold">Mail</label>
                            <input type="email" id="email" name="email" maxlength="255" class="form-control" value="{{ $v('email', $member->email) }}" autocomplete="off">
                        </div>
                    </div>
                </section>

                {{-- Responsables légaux --}}
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Responsables légaux <span class="fw-normal text-muted small">(nageur mineur)</span></h2></div>
                    <div class="card-body">
                        @for ($g = 0; $g < 2; $g++)
                            <fieldset class="row g-2 {{ $g ? 'mt-2 pt-3 border-top' : '' }}">
                                <legend class="small fw-semibold mb-1">Responsable {{ $g + 1 }}</legend>
                                <div class="col-md-5">
                                    <label for="g{{ $g }}-name" class="form-label small mb-0">Nom et prénom</label>
                                    <input type="text" id="g{{ $g }}-name" name="guardians[{{ $g }}][name]" maxlength="150" class="form-control form-control-sm" value="{{ $guardians[$g]['name'] ?? '' }}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    <label for="g{{ $g }}-rel" class="form-label small mb-0">Lien</label>
                                    <input type="text" id="g{{ $g }}-rel" name="guardians[{{ $g }}][relation]" maxlength="50" class="form-control form-control-sm" list="liens" value="{{ $guardians[$g]['relation'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="g{{ $g }}-tel" class="form-label small mb-0">Téléphone</label>
                                    <input type="tel" id="g{{ $g }}-tel" name="guardians[{{ $g }}][phone]" maxlength="30" class="form-control form-control-sm" value="{{ $guardians[$g]['phone'] ?? '' }}" autocomplete="off">
                                </div>
                                <div class="col-12">
                                    <label for="g{{ $g }}-mail" class="form-label small mb-0">Mail</label>
                                    <input type="email" id="g{{ $g }}-mail" name="guardians[{{ $g }}][email]" maxlength="255" class="form-control form-control-sm" value="{{ $guardians[$g]['email'] ?? '' }}" autocomplete="off">
                                </div>
                            </fieldset>
                        @endfor
                        <datalist id="liens"><option value="Mère"><option value="Père"><option value="Tuteur"><option value="Tutrice"><option value="Autre"></datalist>
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                {{-- Adhésion --}}
                <section class="card border-0 shadow-sm mb-4 mbr-adhesion">
                    <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Adhésion</h2></div>
                    <div class="card-body row g-3">
                        <div class="col-6">
                            <label for="season" class="form-label fw-semibold">Saison</label>
                            <select id="season" name="season" class="form-select">
                                @foreach ($seasons as $s)<option value="{{ $s }}" @selected($v('season', $membership->season) === $s)>{{ $s }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="situation" class="form-label fw-semibold">Situation</label>
                            <select id="situation" name="situation" class="form-select">
                                <option value="">—</option>
                                @foreach (M::SITUATIONS as $k => $l)<option value="{{ $k }}" @selected($v('situation', $membership->situation) === $k)>{{ $l }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="status" class="form-label fw-semibold">Statut</label>
                            <select id="status" name="status" class="form-select">
                                @foreach (M::STATUSES as $k => [$l])<option value="{{ $k }}" @selected($v('status', $membership->status) === $k)>{{ $l }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="member_category_id" class="form-label fw-semibold">Catégorie</label>
                            <select id="member_category_id" name="member_category_id" class="form-select" data-category>
                                <option value="" data-price="">—</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}" data-price="{{ number_format($c->price_cents / 100, 2, ',', '') }}" @selected((int) $v('member_category_id', $membership->member_category_id) === $c->id)>{{ $c->code }} — {{ $c->label }} ({{ $c->price_label }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="price" class="form-label fw-semibold">Tarif (€)</label>
                            <input type="text" id="price" name="price" inputmode="decimal" class="form-control" value="{{ $v('price', $membership->exists || $membership->price_cents ? number_format($membership->price_cents / 100, 2, ',', '') : '') }}" data-price-input>
                        </div>
                        <div class="col-4">
                            <label for="discount" class="form-label fw-semibold">Remise (€)</label>
                            <input type="text" id="discount" name="discount" inputmode="decimal" class="form-control" value="{{ $v('discount', $membership->discount_cents ? number_format($membership->discount_cents / 100, 2, ',', '') : '') }}" data-discount>
                        </div>
                        <div class="col-4">
                            <span class="form-label fw-semibold d-block">Montant</span>
                            <output class="mbr-amount" data-amount aria-live="polite">{{ M::money($membership->amount_cents) }}</output>
                        </div>
                        @if ($family->isNotEmpty())
                            <div class="col-12">
                                <div class="alert alert-info small mb-0">
                                    <strong>Famille :</strong> {{ $family->map->full_name->join(', ') }} ({{ $nbFamille }} adhésions cette saison).
                                    @if ($nbFamille === 2)
                                        Remise prévue : {{ M::money(M::FAMILY_TWO_CENTS) }} sur la 2e adhésion.
                                        <button type="button" class="btn btn-sm btn-link p-0 align-baseline" data-family-fixed="{{ number_format(M::FAMILY_TWO_CENTS / 100, 2, ',', '') }}">Appliquer {{ M::money(M::FAMILY_TWO_CENTS) }}</button>
                                    @else
                                        Remise prévue : {{ M::FAMILY_THREE_PERCENT }} % sur chaque adhésion.
                                        <button type="button" class="btn btn-sm btn-link p-0 align-baseline" data-family-percent="{{ M::FAMILY_THREE_PERCENT }}">Appliquer {{ M::FAMILY_THREE_PERCENT }} %</button>
                                    @endif
                                    <span class="d-block text-muted">Remises famille : en début de saison uniquement.</span>
                                </div>
                            </div>
                        @endif
                        <div class="col-6">
                            <label for="medical_certificate_year" class="form-label fw-semibold">Année certificat médical</label>
                            <input type="number" id="medical_certificate_year" name="medical_certificate_year" min="1990" max="{{ now()->year + 1 }}" class="form-control" value="{{ $v('medical_certificate_year', $membership->medical_certificate_year) }}">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input type="hidden" name="mini_coach" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="mini_coach" name="mini_coach" value="1" @checked($v('mini_coach', $membership->mini_coach))>
                                <label class="form-check-label fw-semibold" for="mini_coach">Mini-coach</label>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Dossier --}}
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Dossier</h2></div>
                    <div class="card-body">
                        @foreach (M::FILE_ITEMS as $k => $l)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="file_items[]" value="{{ $k }}" id="fi-{{ $k }}" @checked(in_array($k, $items, true)) data-file-item>
                                <label class="form-check-label" for="fi-{{ $k }}">{{ $l }}</label>
                            </div>
                        @endforeach
                        <div class="form-check form-switch mt-2 pt-2 border-top">
                            <input type="hidden" name="file_complete" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="file_complete" name="file_complete" value="1" @checked($v('file_complete', $membership->file_complete)) data-file-complete>
                            <label class="form-check-label fw-semibold" for="file_complete">Dossier complet</label>
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="payment_certificate" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="payment_certificate" name="payment_certificate" value="1" @checked($v('payment_certificate', $membership->payment_certificate))>
                            <label class="form-check-label fw-semibold" for="payment_certificate">Attestation de paiement remise</label>
                        </div>
                        <input type="hidden" name="payment_certificate_on" value="{{ $membership->payment_certificate_on?->toDateString() }}">
                    </div>
                </section>

                {{-- Pass'Sport --}}
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Pass'Sport</h2></div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="hidden" name="pass_sport" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="pass_sport" name="pass_sport" value="1" @checked($v('pass_sport', $membership->pass_sport)) data-pass>
                                <label class="form-check-label fw-semibold" for="pass_sport">Pass'Sport saisi</label>
                            </div>
                        </div>
                        <div class="col-7" data-pass-field>
                            <label for="pass_sport_code" class="form-label small mb-0">Code</label>
                            <input type="text" id="pass_sport_code" name="pass_sport_code" maxlength="40" class="form-control font-monospace" value="{{ $v('pass_sport_code', $membership->pass_sport_code) }}" autocomplete="off">
                        </div>
                        <div class="col-5" data-pass-field>
                            <label for="pass_sport_amount" class="form-label small mb-0">Montant (€)</label>
                            <input type="text" id="pass_sport_amount" name="pass_sport_amount" inputmode="decimal" class="form-control" value="{{ $v('pass_sport_amount', $membership->pass_sport_cents ? number_format($membership->pass_sport_cents / 100, 2, ',', '') : '') }}">
                        </div>
                        <p class="small text-muted mb-0" data-pass-field>Le montant du Pass'Sport est déduit du reste à payer.</p>
                    </div>
                </section>

                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0"><label for="observations" class="mb-0">Observations</label></h2></div>
                    <div class="card-body">
                        <textarea id="observations" name="observations" rows="3" maxlength="3000" class="form-control" placeholder="Ex. : paiement + caution reçus par mail le 19/10, certificat mi-octobre…">{{ $v('observations', $membership->observations) }}</textarea>
                    </div>
                </section>
            </div>
        </div>

        <div class="mbr-actions text-end">
            <button class="btn btn-primary btn-lg"><i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .mbr-amount { display: block; font-size: 1.25rem; font-weight: 700; padding-top: .35rem; }
    .btn-check:checked + .mbr-chip { background: #0090D4 !important; color: #fff !important; border-color: #0090D4 !important; }
    .mbr-actions { position: sticky; bottom: 0; padding: .75rem 0; background: linear-gradient(to top, #f8fafc 70%, rgba(248,250,252,0)); z-index: 5; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var f = document.getElementById('member-form');
    if (!f) return;
    var prix = f.querySelector('[data-price-input]'), remise = f.querySelector('[data-discount]'), montant = f.querySelector('[data-amount]');
    function lire(v) { v = (v || '').replace(/[€\s ]/g, '').replace(',', '.'); var n = parseFloat(v); return isNaN(n) ? 0 : n; }
    function euros(n) { return n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €'; }
    function majMontant() { montant.textContent = euros(Math.max(0, lire(prix.value) - lire(remise.value))); }
    prix.addEventListener('input', majMontant); remise.addEventListener('input', majMontant);

    f.querySelector('[data-category]').addEventListener('change', function () {
        var p = this.selectedOptions[0].getAttribute('data-price');
        if (p !== '' && p !== null) { prix.value = p; majMontant(); }
    });
    var fixe = f.querySelector('[data-family-fixed]'), pct = f.querySelector('[data-family-percent]');
    if (fixe) fixe.addEventListener('click', function () { remise.value = fixe.getAttribute('data-family-fixed'); majMontant(); });
    if (pct) pct.addEventListener('click', function () { remise.value = (lire(prix.value) * parseInt(pct.getAttribute('data-family-percent'), 10) / 100).toFixed(2).replace('.', ','); majMontant(); });

    // Dossier complet coché automatiquement quand toutes les pièces sont là
    var items = f.querySelectorAll('[data-file-item]'), complet = f.querySelector('[data-file-complete]');
    items.forEach(function (c) { c.addEventListener('change', function () {
        if (Array.prototype.every.call(items, function (i) { return i.checked; })) complet.checked = true;
    }); });

    var pass = f.querySelector('[data-pass]');
    function majPass() { f.querySelectorAll('[data-pass-field]').forEach(function (el) { el.hidden = !pass.checked; }); }
    pass.addEventListener('change', majPass); majPass();
    majMontant();
})();
</script>
@endpush
