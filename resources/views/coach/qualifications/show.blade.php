@extends('layouts.coach')

@section('title', 'Qualifiés — '.$grid->name)

@php
    use App\Support\SwimTime;
    $ecart = fn ($cs) => ($cs > 0 ? '+' : '−').SwimTime::format(abs($cs));
@endphp

@push('styles')
<style>
    .qual-chip { display: inline-flex; flex-direction: column; border-radius: 8px; padding: .2rem .5rem; margin: 0 .3rem .3rem 0; font-size: .78rem; line-height: 1.25; }
    .qual-chip strong { font-size: .82rem; }
    .qual-ok { background: #e6f4ea; color: #0f5132; }
    .qual-proche { background: #fff4d6; color: #6b4500; }
    @media print {
        .qual-filtres, .espace-sidebar, .espace-topbar, .btn { display: none !important; }
        .qual-chip { border: 1px solid #999; }
    }
</style>
@endpush

@section('content')
<div class="container-lg">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
        <div>
            <p class="small text-muted mb-1"><a href="{{ route('coach.qualifications.index') }}">Nageurs qualifiés</a></p>
            <h1 class="h3 fw-bold mb-1">{{ $grid->name }}</h1>
            <p class="text-muted small mb-0">
                Temps égal ou plus rapide que la grille, dans le même bassin.
                {{ $grid->period_label ? 'Performances '.$grid->period_label.'.' : 'Toutes les performances enregistrées.' }}
                Catégorie d'après l'année de naissance du nageur.
            </p>
        </div>
        <div class="d-flex gap-2">
            @can('access-admin')
                <a href="{{ route('admin.qualifications.times', $grid) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-pen me-1" aria-hidden="true"></i>Modifier la grille</a>
            @endcan
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="fas fa-print me-1" aria-hidden="true"></i>Imprimer</button>
        </div>
    </div>

    <form method="GET" class="card border-0 shadow-sm my-3 qual-filtres">
        <div class="card-body row g-2 align-items-end">
            <div class="col-sm-6 col-lg-2">
                <label for="groupe" class="form-label small mb-1">Groupe</label>
                <select id="groupe" name="groupe" class="form-select form-select-sm">
                    <option value="">Tous les groupes</option>
                    @foreach ($groupes as $g)<option value="{{ $g->id }}" @selected(($filtres['groupe'] ?? '') == $g->id)>{{ $g->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label for="categorie" class="form-label small mb-1">Catégorie</label>
                <select id="categorie" name="categorie" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    @foreach ($grid->categories as $c)<option value="{{ $c->id }}" @selected(($filtres['categorie'] ?? '') == $c->id)>{{ $c->full_label }}</option>@endforeach
                </select>
            </div>
            <div class="col-4 col-lg-2">
                <label for="sexe" class="form-label small mb-1">Sexe</label>
                <select id="sexe" name="sexe" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    <option value="F" @selected(($filtres['sexe'] ?? '') === 'F')>Dames</option>
                    <option value="M" @selected(($filtres['sexe'] ?? '') === 'M')>Messieurs</option>
                </select>
            </div>
            <div class="col-4 col-lg-2">
                <label for="bassin" class="form-label small mb-1">Bassin</label>
                <select id="bassin" name="bassin" class="form-select form-select-sm">
                    <option value="">Les deux</option>
                    <option value="25" @selected(($filtres['bassin'] ?? '') == '25')>25 m</option>
                    <option value="50" @selected(($filtres['bassin'] ?? '') == '50')>50 m</option>
                </select>
            </div>
            <div class="col-4 col-lg-2">
                <label for="affichage" class="form-label small mb-1">Afficher</label>
                <select id="affichage" name="affichage" class="form-select form-select-sm">
                    <option value="qualifies" @selected($filtres['affichage'] === 'qualifies')>Qualifiés</option>
                    <option value="proches" @selected($filtres['affichage'] === 'proches')>Qualifiés et proches</option>
                    <option value="tous" @selected($filtres['affichage'] === 'tous')>Tous les nageurs</option>
                </select>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-filter me-1" aria-hidden="true"></i>Filtrer</button>
            </div>
        </div>
    </form>

    <p class="mb-3"><strong>{{ $totalQualifies }}</strong> nageur(s) qualifié(s), <strong>{{ $totalPerfs }}</strong> qualification(s).
        <span class="small text-muted">« Proche » : moins de {{ \App\Services\QualificationChecker::NEAR_PCT }} % au-dessus du temps de la grille.</span></p>

    @if ($grid->times()->doesntExist())
        <div class="alert alert-info">Cette grille n'a pas encore de temps.</div>
    @elseif ($lignes->isEmpty())
        <div class="alert alert-light border">Aucun nageur pour ces critères. Vérifiez que les nageurs ont une année de naissance et un sexe renseignés, et des performances sur la période.</div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive position-relative">
                <table class="table align-middle mb-0">
                    <thead><tr>
                        <th scope="col">Nageur</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col" class="text-center">Qualifs</th>
                        <th scope="col">Épreuves qualifiées <span class="fw-normal small text-muted">(temps / grille)</span></th>
                        @if ($filtres['affichage'] !== 'qualifies')<th scope="col">Proches <span class="fw-normal small text-muted">(écart)</span></th>@endif
                    </tr></thead>
                    <tbody>
                        @foreach ($lignes as $l)
                            <tr>
                                <td>
                                    <a href="{{ route('coach.performances.swimmer', $l['swimmer']) }}" class="fw-semibold">{{ $l['swimmer']->last_name }} {{ $l['swimmer']->first_name }}</a>
                                    <div class="small text-muted">{{ $l['swimmer']->birth_year }} · {{ $l['swimmer']->groups->pluck('name')->implode(', ') ?: 'sans groupe' }}</div>
                                </td>
                                <td class="text-nowrap">{{ $l['category']->label }} <span class="small text-muted">{{ $l['swimmer']->gender === 'F' ? 'Dames' : 'Messieurs' }}</span></td>
                                <td class="text-center fw-bold">{{ $l['qualified'] }}</td>
                                <td>
                                    @foreach ($l['results'] as $r)
                                        @if ($r['qualified'])
                                            <span class="qual-chip qual-ok" title="Réalisé le {{ $r['date'] }}"><strong>{{ $r['event'] }} · {{ $r['pool'] }} m</strong>{{ SwimTime::format($r['best_cs']) }} / {{ SwimTime::format($r['grid_cs']) }}</span>
                                        @endif
                                    @endforeach
                                    @if (! $l['qualified'])<span class="text-muted small">—</span>@endif
                                </td>
                                @if ($filtres['affichage'] !== 'qualifies')
                                    <td>
                                        @foreach ($l['results'] as $r)
                                            @if ($r['near'])
                                                <span class="qual-chip qual-proche" title="Meilleur temps {{ SwimTime::format($r['best_cs']) }}, grille {{ SwimTime::format($r['grid_cs']) }}"><strong>{{ $r['event'] }} · {{ $r['pool'] }} m</strong>{{ $ecart($r['gap']) }} ({{ str_replace('.', ',', $r['pct']) }} %)</span>
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
