@extends($layout)

@section('title', $member->full_name)

@section('content')
@php use App\Models\Membership as M; $r = fn ($n, $p = []) => route($area . '.members.' . $n, $p); @endphp
<div class="container-lg">
    <a href="{{ $r('index', ['saison' => $season]) }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Adhérents {{ $season }}</a>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ $member->full_name }}</h1>
            <p class="text-muted mb-0">
                @if ($member->birth_date)Né(e) le {{ $member->birth_date->format('d/m/Y') }} ({{ $member->ageAt() }} ans)@if ($member->birth_place) à {{ $member->birth_place }}@endif · @endif
                {{ $member->gender === 'F' ? 'Féminin' : ($member->gender === 'M' ? 'Masculin' : 'Sexe non renseigné') }}
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ $r('edit', [$member, 'saison' => $season]) }}" class="btn btn-primary"><i class="fas fa-edit me-1" aria-hidden="true"></i>Modifier ({{ $season }})</a>
            <form method="POST" action="{{ $r('destroy', $member) }}" onsubmit="return confirm('Supprimer définitivement {{ $member->full_name }}, ses adhésions et ses règlements ?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer l'adhérent</span></button>
            </form>
        </div>
    </div>

    {{-- Saisons --}}
    <nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Saisons">
        @foreach ($member->memberships as $m)
            <a href="{{ $r('show', [$member, 'saison' => $m->season]) }}" class="btn btn-sm {{ $m->season === $season ? 'btn-dark' : 'btn-outline-secondary' }}">
                {{ $m->season }} <span class="badge bg-{{ $m->status_color }} ms-1">{{ $m->status_label }}</span>
            </a>
        @endforeach
        @unless ($member->membershipFor(M::currentSeason()))
            <a href="{{ $r('edit', [$member, 'saison' => M::currentSeason()]) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus me-1" aria-hidden="true"></i>Adhésion {{ M::currentSeason() }}</a>
        @endunless
    </nav>

    <div class="row g-4">
        <div class="col-lg-5">
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Coordonnées</h2></div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item"><i class="fas fa-home fa-fw me-2 text-muted" aria-hidden="true"></i>{{ $member->address ?: '—' }}<br><span class="ms-4">{{ $member->postal_code }} {{ $member->city }}</span></li>
                    <li class="list-group-item"><i class="fas fa-phone fa-fw me-2 text-muted" aria-hidden="true"></i>{{ $member->phone1 ?: '—' }}@if ($member->phone2) · {{ $member->phone2 }}@endif</li>
                    <li class="list-group-item"><i class="fas fa-envelope fa-fw me-2 text-muted" aria-hidden="true"></i>@if ($member->email)<a href="mailto:{{ $member->email }}">{{ $member->email }}</a>@else — @endif</li>
                    <li class="list-group-item"><i class="fas fa-camera fa-fw me-2 text-muted" aria-hidden="true"></i>Droit à l'image :
                        <strong class="{{ $member->image_rights === false ? 'text-danger' : '' }}">{{ $member->image_rights === null ? 'non renseigné' : ($member->image_rights ? 'oui' : 'non') }}</strong></li>
                </ul>
            </section>

            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Responsables légaux</h2></div>
                @if ($member->guardians)
                    <ul class="list-group list-group-flush small">
                        @foreach ($member->guardians as $g)
                            <li class="list-group-item">
                                <strong>{{ $g['name'] ?? '—' }}</strong>@if (! empty($g['relation'])) <span class="text-muted">({{ $g['relation'] }})</span>@endif<br>
                                {{ $g['phone'] ?? '' }}@if (! empty($g['email'])) · <a href="mailto:{{ $g['email'] }}">{{ $g['email'] }}</a>@endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="card-body small {{ $member->isMinor() ? 'text-danger' : 'text-muted' }}">{{ $member->isMinor() ? 'Mineur : aucun responsable légal renseigné.' : 'Non renseigné.' }}</div>
                @endif
            </section>

            {{-- Liens avec le reste du site --}}
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Liens</h2></div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item">
                        <strong>Licence FFN :</strong>
                        @if ($license)
                            {{ $license->iuf }} · saison {{ $license->season }} · {{ $license->license_type }}
                            @if ($area === 'admin')<a href="{{ route('admin.ffn-licenses.show', $license) }}" class="ms-1">voir</a>@endif
                            <form method="POST" action="{{ $r('fill-license', $member) }}" class="mt-1">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-file-import me-1" aria-hidden="true"></i>Compléter les champs vides depuis la licence</button>
                            </form>
                        @else
                            <span class="text-muted">aucune licence importée ne correspond (nom, prénom, date de naissance).</span>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <strong>Fiche nageur (planning) :</strong>
                        @if ($member->swimmer)
                            <a href="{{ route($area . '.planning.swimmers.edit', $member->swimmer) }}">{{ $member->swimmer->full_name }}</a>
                            <span class="text-muted">{{ $member->swimmer->groups->pluck('name')->join(', ') }}</span>
                        @else
                            <form method="POST" action="{{ $r('link-swimmer', $member) }}" class="mt-1">
                                @csrf
                                <div class="row g-1">
                                    <div class="col-12">
                                        <label for="swimmer" class="visually-hidden">Fiche nageur existante</label>
                                        <select id="swimmer" name="swimmer" class="form-select form-select-sm">
                                            <option value="">Créer une nouvelle fiche nageur</option>
                                            @foreach ($swimmers as $s)<option value="{{ $s->id }}">Rattacher : {{ $s->full_name }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="col-8">
                                        <label for="groups" class="visually-hidden">Groupe</label>
                                        <select id="groups" name="groups[]" class="form-select form-select-sm">
                                            <option value="">Sans groupe</option>
                                            @foreach ($groups as $g)<option value="{{ $g->id }}">{{ $g->name }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="col-4"><button class="btn btn-sm btn-outline-primary w-100">Valider</button></div>
                                </div>
                            </form>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <strong>Compte du site :</strong>
                        @if ($member->user)
                            {{ $member->user->email }}
                            <form method="POST" action="{{ $r('link-user', $member) }}" class="d-inline">@csrf<input type="hidden" name="detach" value="1"><button class="btn btn-sm btn-link p-0 ms-1">détacher</button></form>
                        @else
                            <form method="POST" action="{{ $r('link-user', $member) }}" class="d-flex gap-1 mt-1">
                                @csrf
                                <label for="link-email" class="visually-hidden">E-mail du compte</label>
                                <input type="email" id="link-email" name="email" class="form-control form-control-sm" value="{{ $member->email }}" placeholder="e-mail du compte">
                                <button class="btn btn-sm btn-outline-primary text-nowrap">Rattacher</button>
                            </form>
                            <span class="text-muted d-block mt-1">Le compte rattaché voit les séances, l'assiduité et les performances du nageur.</span>
                        @endif
                    </li>
                </ul>
            </section>
        </div>

        <div class="col-lg-7">
            @if (! $membership)
                <div class="card border-0 shadow-sm mb-4"><div class="card-body text-center py-4">
                    Pas d'adhésion pour la saison {{ $season }}.
                    <a href="{{ $r('edit', [$member, 'saison' => $season]) }}" class="btn btn-sm btn-primary ms-2">Créer l'adhésion</a>
                </div></div>
            @else
                @php $alerte = $membership->certificateAlert(); @endphp
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h2 class="h6 fw-bold mb-0">Adhésion {{ $membership->season }}</h2>
                        <span class="badge bg-{{ $membership->status_color }} fs-6">{{ $membership->status_label }}</span>
                    </div>
                    <div class="card-body">
                        <dl class="row small mb-0">
                            <dt class="col-5">Situation</dt><dd class="col-7">{{ M::SITUATIONS[$membership->situation] ?? '—' }}</dd>
                            <dt class="col-5">Catégorie</dt><dd class="col-7">{{ $membership->category ? $membership->category->code . ' — ' . $membership->category->label : '—' }}</dd>
                            <dt class="col-5">Tarif</dt><dd class="col-7">{{ M::money($membership->price_cents) }}</dd>
                            <dt class="col-5">Remise</dt><dd class="col-7">{{ M::money($membership->discount_cents) }}</dd>
                            <dt class="col-5">Montant</dt><dd class="col-7 fw-bold">{{ M::money($membership->amount_cents) }}</dd>
                            <dt class="col-5">Pass'Sport</dt><dd class="col-7">{{ $membership->pass_sport ? 'oui' . ($membership->pass_sport_code ? ' · code ' . $membership->pass_sport_code : '') . ' · ' . M::money($membership->pass_sport_cents) : 'non' }}</dd>
                            <dt class="col-5">Certificat médical</dt><dd class="col-7 {{ $alerte ? 'text-danger fw-bold' : '' }}">{{ $membership->medical_certificate_year ?? '—' }}@if ($alerte) · {{ $alerte }}@elseif ($member->isMinor($membership->season)) · mineur : questionnaire de santé @endif</dd>
                            <dt class="col-5">Mini-coach</dt><dd class="col-7">{{ $membership->mini_coach ? 'oui' : 'non' }}</dd>
                            <dt class="col-5">Dossier</dt>
                            <dd class="col-7">
                                <span class="fw-bold {{ $membership->file_complete ? 'text-success' : 'text-danger' }}">{{ $membership->file_complete ? 'complet' : 'incomplet' }}</span>
                                <ul class="list-unstyled mb-0 mt-1">
                                    @foreach (M::FILE_ITEMS as $k => $l)
                                        <li><i class="fas {{ in_array($k, (array) $membership->file_items, true) ? 'fa-check text-success' : 'fa-minus text-muted' }} fa-fw me-1" aria-hidden="true"></i>{{ $l }}</li>
                                    @endforeach
                                </ul>
                            </dd>
                            @if ($membership->observations)
                                <dt class="col-5">Observations</dt><dd class="col-7" style="white-space: pre-line">{{ $membership->observations }}</dd>
                            @endif
                        </dl>
                    </div>
                </section>

                {{-- Règlements --}}
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex flex-wrap justify-content-between gap-2">
                        <h2 class="h6 fw-bold mb-0">Règlements</h2>
                        <span class="small">Réglé : <strong>{{ M::money($membership->paid_cents) }}</strong>@if ($membership->pass_sport) + Pass'Sport {{ M::money($membership->pass_sport_cents) }}@endif ·
                            Reste : <strong class="{{ $membership->due_cents > 0 ? 'text-danger' : 'text-success' }}">{{ $membership->due_cents > 0 ? M::money($membership->due_cents) : 'soldé' }}</strong></span>
                    </div>
                    @if ($membership->payments->isNotEmpty())
                        <ul class="list-group list-group-flush small">
                            @foreach ($membership->payments as $pay)
                                <li class="list-group-item d-flex flex-wrap gap-2 align-items-center">
                                    <span class="fw-semibold" style="min-width: 120px">{{ $pay->method_label }}</span>
                                    <span class="fw-bold" style="min-width: 80px">{{ M::money($pay->amount_cents) }}</span>
                                    <span class="text-muted">{{ $pay->cashed_on ? ($pay->is_cashed ? 'encaissé le ' : 'à encaisser le ') . $pay->cashed_on->format('d/m/Y') : '' }}</span>
                                    @if ($pay->reference)<span class="text-muted">· {{ $pay->reference }}</span>@endif
                                    <span class="ms-auto d-flex gap-1">
                                        <form method="POST" action="{{ $r('payments.update', $pay) }}">@csrf @method('PATCH')
                                            <button class="btn btn-sm {{ $pay->is_cashed ? 'btn-success' : 'btn-outline-success' }}" title="{{ $pay->is_cashed ? 'Encaissé (cliquer pour annuler)' : 'Marquer encaissé' }}"><i class="fas fa-check" aria-hidden="true"></i><span class="visually-hidden">{{ $pay->is_cashed ? 'Encaissé' : 'Marquer encaissé' }}</span></button>
                                        </form>
                                        <form method="POST" action="{{ $r('payments.destroy', $pay) }}" onsubmit="return confirm('Supprimer ce règlement ?')">@csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer</span></button>
                                        </form>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <form method="POST" action="{{ $r('payments.store', $membership) }}" class="card-body border-top">
                        @csrf
                        <h3 class="small fw-bold">Ajouter un règlement</h3>
                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <label for="method" class="form-label small mb-0">Mode</label>
                                <select id="method" name="method" class="form-select form-select-sm">@foreach (M::PAYMENT_METHODS as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach</select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="amount" class="form-label small mb-0">Montant (€)</label>
                                <input type="text" id="amount" name="amount" inputmode="decimal" class="form-control form-control-sm" value="{{ $membership->due_cents ? number_format($membership->due_cents / 100, 2, ',', '') : '' }}" required>
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="count" class="form-label small mb-0">En</label>
                                <select id="count" name="count" class="form-select form-select-sm">@for ($n = 1; $n <= 4; $n++)<option value="{{ $n }}">{{ $n }} fois</option>@endfor</select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="cashed_on" class="form-label small mb-0">Encaissement</label>
                                <input type="date" id="cashed_on" name="cashed_on" class="form-control form-control-sm" value="{{ today()->day <= 15 ? today()->setDay(15)->toDateString() : today()->addMonthNoOverflow()->setDay(15)->toDateString() }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox" name="is_cashed" value="1" id="is_cashed"><label class="form-check-label small" for="is_cashed">Encaissé</label></div>
                            </div>
                            <div class="col-md-9">
                                <label for="reference" class="form-label small mb-0">Référence (n° de chèque, banque, émetteur…)</label>
                                <input type="text" id="reference" name="reference" maxlength="120" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3 d-flex align-items-end"><button class="btn btn-sm btn-primary w-100">Ajouter</button></div>
                        </div>
                        <p class="small text-muted mt-2 mb-0">Paiement en plusieurs fois : le montant est réparti et les encaissements sont prévus un mois d'écart (15/09, 15/10, 15/11, 15/12…).</p>
                    </form>
                    <div class="card-footer bg-white d-flex flex-wrap gap-2 align-items-center">
                        <a href="{{ $r('attestation', [$member, $membership]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1" aria-hidden="true"></i>Voir l'attestation de paiement</a>
                        <a href="{{ $r('attestation', [$member, $membership, 'remise' => 1]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success"><i class="fas fa-check me-1" aria-hidden="true"></i>Imprimer et noter comme remise</a>
                        <span class="small {{ $membership->payment_certificate ? 'text-success' : 'text-muted' }}">{{ $membership->payment_certificate ? 'Attestation remise' . ($membership->payment_certificate_on ? ' le ' . $membership->payment_certificate_on->format('d/m/Y') : '') : 'Attestation non remise' }}</span>
                        <form method="POST" action="{{ $r('memberships.destroy', [$member, $membership]) }}" class="ms-auto" onsubmit="return confirm('Supprimer l\'adhésion {{ $membership->season }} et ses règlements ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-link text-danger">Supprimer l'adhésion {{ $membership->season }}</button>
                        </form>
                    </div>
                </section>
            @endif

            @if ($family->isNotEmpty())
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Même foyer ({{ $season }})</h2></div>
                    <ul class="list-group list-group-flush small">
                        @foreach ($family as $f)<li class="list-group-item"><a href="{{ $r('show', [$f, 'saison' => $season]) }}">{{ $f->full_name }}</a></li>@endforeach
                    </ul>
                </section>
            @endif

            {{-- Journal --}}
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Journal des modifications</h2></div>
                @if ($member->logs->isEmpty())
                    <div class="card-body small text-muted">Aucune modification enregistrée.</div>
                @else
                    <ul class="list-group list-group-flush small mbr-log">
                        @foreach ($member->logs->take(30) as $log)
                            <li class="list-group-item">
                                <span class="text-muted">{{ $log->created_at?->format('d/m/Y H:i') }}</span> ·
                                <strong>{{ \App\Models\MemberLog::ACTIONS[$log->action] ?? $log->action }}</strong> · {{ $log->subject }}
                                · <span class="text-muted">{{ $log->user?->name ?? 'système' }}</span>
                                @if ($log->changes)
                                    <ul class="mb-0 mt-1">
                                        @foreach ($log->changes as $champ => [$avant, $apres])
                                            <li><code>{{ $champ }}</code> : {{ \Illuminate\Support\Str::limit((string) ($avant ?? '—'), 60) }} → {{ \Illuminate\Support\Str::limit((string) ($apres ?? '—'), 60) }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
