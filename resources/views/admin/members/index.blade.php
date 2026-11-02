@extends($layout)

@section('title', 'Adhérents ' . $season)

@section('content')
@php use App\Models\Membership as M; $r = fn ($n, $p = []) => route($area . '.members.' . $n, $p); @endphp
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <h1 class="h3 fw-bold mb-0">Adhérents — saison {{ $season }}</h1>
        <form method="GET" class="d-flex gap-2">
            <label for="saison" class="visually-hidden">Saison</label>
            <select id="saison" name="saison" class="form-select" onchange="this.form.submit()">
                @foreach ($seasons as $s)<option value="{{ $s }}" @selected($s === $season)>{{ $s }}</option>@endforeach
            </select>
            <noscript><button class="btn btn-outline-primary">OK</button></noscript>
        </form>
    </div>

    @include('admin.members._nav')

    {{-- Filtres --}}
    <form method="GET" class="card border-0 shadow-sm mb-3">
        <input type="hidden" name="saison" value="{{ $season }}">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label for="q" class="form-label small mb-1">Rechercher</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Nom, prénom, e-mail, ville">
            </div>
            <div>
                <label for="statut" class="form-label small mb-1">Statut</label>
                <select id="statut" name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach (M::STATUSES as $k => [$l])<option value="{{ $k }}" @selected($filters['statut'] === $k)>{{ $l }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="categorie" class="form-label small mb-1">Catégorie</label>
                <select id="categorie" name="categorie" class="form-select">
                    <option value="">Toutes</option>
                    @foreach ($categories as $c)<option value="{{ $c->id }}" @selected($filters['categorie'] === $c->id)>{{ $c->code }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="filtre" class="form-label small mb-1">À surveiller</label>
                <select id="filtre" name="filtre" class="form-select">
                    <option value="">—</option>
                    @foreach (['dossier' => 'Dossier incomplet', 'reste' => 'Reste à payer', 'certificat' => 'Certificat à renouveler', 'attestation' => 'Attestation non remise', 'passsport' => "Pass'Sport", 'minicoach' => 'Mini-coachs', 'image' => "Droit à l'image refusé"] as $k => $l)
                        <option value="{{ $k }}" @selected($filters['filtre'] === $k)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-outline-primary">Filtrer</button>
            @if (array_filter($filters))<a href="{{ $r('index', ['saison' => $season]) }}" class="btn btn-link">Effacer</a>@endif
            <div class="ms-auto d-flex flex-wrap gap-2">
                <a href="{{ $r('export', ['saison' => $season]) }}" class="btn btn-outline-secondary"><i class="fas fa-file-excel me-1" aria-hidden="true"></i>Export Excel</a>
                <a href="{{ $r('export', ['saison' => $season, 'type' => 'image']) }}" class="btn btn-outline-secondary" title="Liste pour les photographes"><i class="fas fa-camera me-1" aria-hidden="true"></i>Droit à l'image refusé</a>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        @if ($memberships->isEmpty())
            <div class="card-body text-center text-muted py-5">
                <p>Aucune adhésion pour cette saison{{ array_filter($filters) ? ' avec ces filtres' : '' }}.</p>
                @unless (array_filter($filters))
                    <a href="{{ $r('import', ['saison' => $season]) }}" class="btn btn-primary btn-sm">Importer votre tableau</a>
                    <a href="{{ $r('create', ['saison' => $season]) }}" class="btn btn-outline-primary btn-sm">Ajouter un adhérent</a>
                @endunless
            </div>
        @else
            <div class="table-responsive position-relative">
                <table class="table table-sm table-hover align-middle mb-0 mbr-table">
                    <caption class="visually-hidden">Adhérents de la saison {{ $season }}</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Naiss.</th>
                            <th scope="col">Catégorie</th>
                            <th scope="col">Situation</th>
                            <th scope="col">Statut</th>
                            <th scope="col" class="text-center" title="Dossier complet">Dossier</th>
                            <th scope="col" class="text-center" title="Pass'Sport">P.S.</th>
                            <th scope="col" class="text-center" title="Attestation de paiement remise">Attest.</th>
                            <th scope="col" class="text-center" title="Mini-coach">M.C.</th>
                            <th scope="col" class="text-center" title="Droit à l'image">Image</th>
                            <th scope="col" class="text-center" title="Année du certificat médical">Certif.</th>
                            <th scope="col" class="text-end">Montant</th>
                            <th scope="col" class="text-end">Reste</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memberships as $m)
                            @php $p = $m->member; $alerte = $m->certificateAlert(); @endphp
                            <tr>
                                <th scope="row">
                                    <a href="{{ $r('show', [$p, 'saison' => $season]) }}" class="fw-semibold text-decoration-none">{{ $p->full_name }}</a>
                                    @if ($m->observations)<i class="fas fa-comment-dots text-muted ms-1" title="{{ $m->observations }}" aria-label="Observation : {{ $m->observations }}"></i>@endif
                                </th>
                                <td class="small text-nowrap">{{ $p->birth_date?->format('d/m/Y') }}</td>
                                <td class="small">{{ $m->category?->code }}</td>
                                <td class="small">{{ M::SITUATIONS[$m->situation] ?? '' }}</td>
                                <td><span class="badge bg-{{ $m->status_color }} {{ $m->status_color === 'warning' ? 'text-dark' : '' }}">{{ $m->status_label }}</span></td>
                                <td class="text-center">@include('admin.members._yes', ['v' => $m->file_complete])</td>
                                <td class="text-center">@include('admin.members._yes', ['v' => $m->pass_sport, 'neutral' => true])</td>
                                <td class="text-center">@include('admin.members._yes', ['v' => $m->payment_certificate, 'neutral' => true])</td>
                                <td class="text-center">@include('admin.members._yes', ['v' => $m->mini_coach, 'neutral' => true])</td>
                                <td class="text-center">@include('admin.members._yes', ['v' => $p->image_rights])</td>
                                <td class="text-center small {{ $alerte ? 'text-danger fw-bold' : '' }}" @if ($alerte) title="{{ $alerte }}" @endif>{{ $m->medical_certificate_year ?? '—' }}</td>
                                <td class="text-end small text-nowrap">{{ M::money($m->amount_cents) }}@if ($m->discount_cents)<span class="d-block text-muted">remise {{ M::money($m->discount_cents) }}</span>@endif</td>
                                <td class="text-end small text-nowrap {{ $m->due_cents > 0 && $m->status !== 'abandon' ? 'text-danger fw-bold' : 'text-success' }}">{{ $m->due_cents > 0 ? M::money($m->due_cents) : 'Soldé' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th scope="row" colspan="11">{{ $memberships->count() }} adhésion(s)</th>
                            <td class="text-end fw-bold text-nowrap">{{ M::money($memberships->where('status', '!=', 'abandon')->sum('amount_cents')) }}</td>
                            <td class="text-end fw-bold text-nowrap">{{ M::money($memberships->where('status', '!=', 'abandon')->sum(fn ($m) => $m->due_cents)) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 class="h6 fw-bold mb-1">Préparer la saison {{ $nextSeason }}</h2>
                <p class="small text-muted mb-0">Chaque adhérent de {{ $season }} (sauf abandons) reçoit une adhésion {{ $nextSeason }} « Pré-inscrit / Renouvellement » avec sa catégorie et le tarif actuel. Rien n'est ressaisi.</p>
            </div>
            <form method="POST" action="{{ $r('prepare-season') }}" onsubmit="return confirm('Créer les adhésions {{ $nextSeason }} à partir de {{ $season }} ?')">
                @csrf
                <input type="hidden" name="saison" value="{{ $season }}">
                <button class="btn btn-outline-primary"><i class="fas fa-forward me-1" aria-hidden="true"></i>{{ $nextExists ? 'Compléter' : 'Préparer' }} la saison {{ $nextSeason }}</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .mbr-table th, .mbr-table td { white-space: nowrap; }
    .mbr-table th[scope=row] { white-space: normal; min-width: 180px; }
</style>
@endpush
