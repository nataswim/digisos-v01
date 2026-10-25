@extends('layouts.public')

@section('title', 'Records — ' . \App\Models\SwimRecord::levelLabel($level))
@section('meta_description', 'Records du Cercle des Nageurs du Bocage Bressuirais et records officiels (département, région, France, Europe, monde) en bassin de 25 m et de 50 m, par catégorie d\'âge.')

@section('content')
@php use App\Models\SwimRecord as R; @endphp

<x-public.hero
    title="Meilleures performances et records"
    eyebrow="Vie du club"
    icon="fa-trophy"
    lead="Les records du club et les records officiels, en bassin de 25 m et de 50 m, toutes catégories et de 12 à 18 ans.">
</x-public.hero>

<section class="py-5 bg-light">
    <div class="container-lg">

        {{-- Choix : bassin, niveau, catégorie --}}
        <nav class="rec-filters card border-0 shadow-sm mb-4" aria-label="Choisir les records à afficher">
            <div class="card-body">
                <div class="rec-row">
                    <span class="rec-label">Bassin</span>
                    <div class="rec-chips">
                        @foreach (R::POOLS as $p => $label)
                            <a href="{{ route('records.index', ['niveau' => $level, 'bassin' => $p, 'categorie' => $category]) }}"
                               class="rec-chip rec-chip--lg {{ $pool === $p ? 'is-active' : '' }}" @if ($pool === $p) aria-current="page" @endif>
                                <i class="fas fa-swimming-pool me-1" aria-hidden="true"></i>{{ $p }} m
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="rec-row">
                    <span class="rec-label">Records</span>
                    <div class="rec-chips">
                        @foreach (R::LEVELS as $key => $label)
                            <a href="{{ route('records.index', ['niveau' => $key, 'bassin' => $pool, 'categorie' => $category]) }}"
                               class="rec-chip {{ $level === $key ? 'is-active' : '' }} {{ $key === 'club' ? 'rec-chip--club' : '' }}" @if ($level === $key) aria-current="page" @endif>
                                {{ $key === 'club' ? 'Club' : \Illuminate\Support\Str::after(R::levelLabel($key), 'Records ') }}
                                @if (! empty($counts[$key]))<span class="rec-count">{{ $counts[$key] }}</span>@endif
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="rec-row">
                    <span class="rec-label">Catégorie</span>
                    <div class="rec-chips">
                        @foreach (R::CATEGORIES as $key => $label)
                            <a href="{{ route('records.index', ['niveau' => $level, 'bassin' => $pool, 'categorie' => $key]) }}"
                               class="rec-chip {{ $category === (string) $key ? 'is-active' : '' }}" @if ($category === (string) $key) aria-current="page" @endif>{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </nav>

        <h2 class="h3 fw-bold mb-1">{{ R::levelLabel($level) }}</h2>
        <p class="text-muted mb-4">{{ R::POOLS[$pool] }} · {{ R::CATEGORIES[$category] }}</p>

        @if ($records->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-stopwatch fa-2x mb-3" aria-hidden="true"></i>
                    <p class="mb-0">Pas encore de records publiés pour cette sélection.</p>
                    @if ($ffnUrl)
                        <a href="{{ $ffnUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary mt-3">
                            Voir les records officiels sur le site de la FFN<span class="visually-hidden"> (nouvel onglet)</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @foreach (R::SEXES as $sex => $sexLabel)
            @continue(! $records->has($sex))
            <section class="card border-0 shadow-sm mb-4" aria-labelledby="rec-{{ $sex }}">
                <div class="card-header bg-white rec-sex rec-sex--{{ $sex }}">
                    <h3 id="rec-{{ $sex }}" class="h5 fw-bold mb-0">{{ $sexLabel }}</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 rec-table">
                        <caption class="visually-hidden">{{ R::levelLabel($level) }}, {{ $sexLabel }}, {{ R::POOLS[$pool] }}, {{ R::CATEGORIES[$category] }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">Épreuve</th>
                                <th scope="col">Temps</th>
                                <th scope="col">{{ $sex === 'X' ? 'Relayeurs' : 'Nageur' }}</th>
                                @if ($level !== 'club')<th scope="col">Club</th>@endif
                                <th scope="col">Date</th>
                                <th scope="col">Lieu</th>
                                @if ($level === 'club' && $references->isNotEmpty())<th scope="col">Écart aux records</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records[$sex] as $record)
                                <tr>
                                    <th scope="row" class="text-nowrap">{{ $record->event }}</th>
                                    <td class="rec-time">{{ $record->time_label }}</td>
                                    <td>
                                        @foreach ($record->swimmer_list as $swimmer)
                                            <span class="d-block">{{ $swimmer }}</span>
                                        @endforeach
                                    </td>
                                    @if ($level !== 'club')<td class="small">{{ $record->club }}</td>@endif
                                    <td class="text-nowrap small">{{ $record->record_date?->format('d/m/Y') }}</td>
                                    <td class="small">{{ $record->location }}@if ($record->competition)<span class="d-block text-muted">{{ $record->competition }}</span>@endif</td>
                                    @if ($level === 'club' && $references->isNotEmpty())
                                        <td class="small">
                                            @foreach ($compare as $refLevel => $refLabel)
                                                @php $ref = $references->get($record->sex . '|' . $record->event)?->get($refLevel); @endphp
                                                @if ($ref)
                                                    @php $ecart = $record->time_cs - $ref->time_cs; @endphp
                                                    <span class="rec-gap {{ $ecart <= 0 ? 'rec-gap--best' : '' }}" title="{{ R::levelLabel($refLevel) }} : {{ $ref->time_label }}">
                                                        {{ $refLabel }} {{ $ecart === 0 ? '= record' : ($ecart < 0 ? '★ ' . R::formatGap($ecart) : R::formatGap($ecart)) }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach

        @if ($records->isNotEmpty())
            <p class="small text-muted">
                @if ($updatedAt) Mis à jour le {{ \Illuminate\Support\Carbon::parse($updatedAt)->format('d/m/Y') }}. @endif
                @if ($level === 'club')
                    Records établis par les nageurs du CNBB. Les écarts sont calculés avec les records départementaux, régionaux et de France de la même catégorie (★ : temps du club plus rapide que le record).
                @elseif ($ffnUrl)
                    Source : Fédération Française de Natation —
                    <a href="{{ $ffnUrl }}" target="_blank" rel="noopener">records officiels sur extraNat<span class="visually-hidden"> (nouvel onglet)</span></a>, qui font foi.
                @endif
            </p>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .rec-row { display: flex; flex-wrap: wrap; align-items: baseline; gap: .5rem 1rem; padding: .4rem 0; }
    .rec-row + .rec-row { border-top: 1px solid #f1f5f9; }
    .rec-label { width: 90px; font-weight: 700; font-size: .85rem; text-transform: uppercase; color: #62748e; }
    .rec-chips { display: flex; flex-wrap: wrap; gap: .4rem; flex: 1; }
    .rec-chip { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .85rem; border-radius: 999px; border: 2px solid #e2e8f0; background: #fff; color: #314158; font-weight: 600; font-size: .9rem; text-decoration: none; }
    .rec-chip:hover, .rec-chip:focus { border-color: #0090D4; color: #0090D4; }
    .rec-chip.is-active { background: #0090D4; border-color: #0090D4; color: #fff; }
    .rec-chip--lg { padding: .45rem 1.1rem; font-size: 1rem; }
    .rec-chip--club:not(.is-active) { border-color: #D49F45; }
    .rec-count { display: inline-block; min-width: 1.4em; padding: 0 .35em; border-radius: 999px; background: rgba(0,0,0,.08); font-size: .75rem; text-align: center; }
    .rec-chip.is-active .rec-count { background: rgba(255,255,255,.25); }
    .rec-sex { border-left: 5px solid #0090D4; }
    .rec-sex--F { border-left-color: #db2777; } .rec-sex--M { border-left-color: #0090D4; } .rec-sex--X { border-left-color: #D49F45; }
    .rec-table th[scope=row] { font-weight: 700; }
    .rec-time { font-family: var(--bs-font-monospace); font-weight: 700; font-size: 1.05rem; white-space: nowrap; }
    .rec-gap { display: inline-block; margin: 0 .35rem .2rem 0; padding: .1rem .5rem; border-radius: 6px; background: #f1f5f9; white-space: nowrap; }
    .rec-gap--best { background: #FBF6EC; color: #7a5518; font-weight: 700; }
    @media (max-width: 575.98px) { .rec-label { width: 100%; } }
</style>
@endpush
