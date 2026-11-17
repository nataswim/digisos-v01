@extends('layouts.public')

@section('title', $grid->name)
@section('meta_description', \Illuminate\Support\Str::limit($grid->description ?: 'Grille de qualification : temps à réaliser par catégorie d\'âge, Dames et Messieurs, en bassin de 25 m et de 50 m.', 155))

@php
    use App\Models\QualificationGrid;
    use App\Support\SwimTime;
    $nages = collect($events)->map(fn ($e) => QualificationGrid::strokeOf($e))->unique()->values();
    $bassins = collect($grid->times)->pluck('pool')->unique()->sort()->values();
@endphp

@push('styles')
<style>
    .qg-filtres { position: sticky; top: 0; z-index: 10; background: rgba(255,255,255,.97); border-bottom: 1px solid #dbe4ee; padding: .75rem 0; }
    .qg-filtre-groupe { display: flex; flex-wrap: wrap; align-items: center; gap: .35rem; }
    .qg-filtre-groupe .qg-filtre-titre { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #52627a; margin-right: .25rem; }
    .qg-btn { border: 1px solid #c9d6e3; background: #fff; color: #0f1f33; border-radius: 999px; padding: .25rem .75rem; font-size: .88rem; font-weight: 600; }
    .qg-btn[aria-pressed="true"] { background: #0f1f33; border-color: #0f1f33; color: #fff; }
    .qg-btn:focus-visible { outline: 3px solid #f59e0b; outline-offset: 2px; }
    .qg-public { font-variant-numeric: tabular-nums; }
    .qg-public th, .qg-public td { white-space: nowrap; vertical-align: middle; }
    .qg-public thead th { background: #0f1f33; color: #fff; text-align: center; border-color: #2a3b52; }
    .qg-public thead tr:nth-child(2) th { background: #1e3a5f; font-weight: 600; font-size: .85rem; }
    .qg-public .qg-epreuve { position: sticky; left: 0; background: #fff; font-weight: 700; z-index: 1; }
    .qg-public td.qg-temps { text-align: center; }
    .qg-public tr.qg-F td.qg-sexe { color: #b02a6b; }
    .qg-public tr.qg-M td.qg-sexe { color: #1d5fa8; }
    .qg-public tbody tr.qg-fin > * { border-bottom: 2px solid #c9d6e3; }
    .qg-public .qg-cat-debut { border-left: 2px solid #c9d6e3; }
    .qg-cache { display: none !important; }
    @media print { .qg-filtres, .hero-video-section .btn { display: none !important; } }
</style>
@endpush

@section('content')
<x-public.hero
    :title="$grid->name"
    eyebrow="Grille de qualification"
    icon="fa-stopwatch"
    titleClass="display-5"
    :lead="$grid->period_label ? 'Temps réalisés '.$grid->period_label.'.' : null">
    <a href="#grille" class="btn btn-primary btn-lg"><i class="fas fa-arrow-down me-2" aria-hidden="true"></i>Voir les temps</a>
    <a href="{{ route('qualifications.index') }}" class="btn btn-outline-light btn-lg"><i class="fas fa-list me-2" aria-hidden="true"></i>Toutes les grilles</a>
</x-public.hero>

@if ($grid->description)
    <section class="pt-5">
        <div class="container-lg">
            <div class="mx-auto" style="max-width: 820px;">{!! nl2br(e($grid->description)) !!}</div>
        </div>
    </section>
@endif

<section id="grille" class="anchor-section py-5" data-qg>
    @if ($grid->categories->isEmpty() || empty($events))
        <p class="text-center text-muted">Les temps de cette grille seront bientôt disponibles.</p>
    @else
        {{-- Filtres (sans JavaScript, tout reste affiché) --}}
        <div class="qg-filtres mb-3" hidden data-qg-filtres>
            <div class="container-lg d-flex flex-column gap-2">
                <div class="qg-filtre-groupe" role="group" aria-label="Catégories d'âge">
                    <span class="qg-filtre-titre">Catégorie</span>
                    <button type="button" class="qg-btn" data-filtre="cat" data-valeur="*" aria-pressed="true">Toutes</button>
                    @foreach ($grid->categories as $cat)
                        <button type="button" class="qg-btn" data-filtre="cat" data-valeur="{{ $cat->id }}" aria-pressed="false">{{ $cat->label }}@if ($cat->years_label) <span class="fw-normal">({{ $cat->years_label }})</span>@endif</button>
                    @endforeach
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <div class="qg-filtre-groupe" role="group" aria-label="Sexe">
                        <span class="qg-filtre-titre">Sexe</span>
                        <button type="button" class="qg-btn" data-filtre="sexe" data-valeur="*" aria-pressed="true">Tous</button>
                        <button type="button" class="qg-btn" data-filtre="sexe" data-valeur="F" aria-pressed="false">Dames</button>
                        <button type="button" class="qg-btn" data-filtre="sexe" data-valeur="M" aria-pressed="false">Messieurs</button>
                    </div>
                    @if ($bassins->count() > 1)
                        <div class="qg-filtre-groupe" role="group" aria-label="Bassin">
                            <span class="qg-filtre-titre">Bassin</span>
                            <button type="button" class="qg-btn" data-filtre="bassin" data-valeur="*" aria-pressed="true">Les deux</button>
                            @foreach ($bassins as $b)
                                <button type="button" class="qg-btn" data-filtre="bassin" data-valeur="{{ $b }}" aria-pressed="false">{{ $b }} m</button>
                            @endforeach
                        </div>
                    @endif
                    <div class="qg-filtre-groupe" role="group" aria-label="Nage">
                        <span class="qg-filtre-titre">Nage</span>
                        <button type="button" class="qg-btn" data-filtre="nage" data-valeur="*" aria-pressed="true">Toutes</button>
                        @foreach ($nages as $n)
                            <button type="button" class="qg-btn" data-filtre="nage" data-valeur="{{ $n }}" aria-pressed="false">{{ $strokes[$n] ?? $n }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="container-lg">
            <div class="table-responsive position-relative border rounded-3">
                <table class="table table-sm table-hover mb-0 qg-public">
                    <caption class="visually-hidden">{{ $grid->name }} : temps de qualification par épreuve, sexe, catégorie d'âge et bassin</caption>
                    <thead>
                        <tr>
                            <th scope="col" rowspan="2" class="qg-epreuve" style="background:#0f1f33;color:#fff">Épreuve</th>
                            <th scope="col" rowspan="2">Sexe</th>
                            @foreach ($grid->categories as $cat)
                                <th scope="colgroup" colspan="{{ $bassins->count() }}" class="qg-cat-debut" data-cat="{{ $cat->id }}">{{ $cat->label }}@if ($cat->years_label)<br><span class="fw-normal small">{{ $cat->years_label }}</span>@endif</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($grid->categories as $cat)
                                @foreach ($bassins as $i => $b)
                                    <th scope="col" class="{{ $loop->first ? 'qg-cat-debut' : '' }}" data-cat="{{ $cat->id }}" data-bassin="{{ $b }}">{{ $b }} m</th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            @foreach (['F' => 'Dames', 'M' => 'Messieurs'] as $g => $sexe)
                                <tr class="qg-{{ $g }} {{ $g === 'M' ? 'qg-fin' : '' }}" data-sexe="{{ $g }}" data-nage="{{ QualificationGrid::strokeOf($event) }}">
                                    <th scope="row" class="qg-epreuve">{{ QualificationGrid::eventLabel($event) }}</th>
                                    <td class="qg-sexe small fw-semibold">{{ $sexe }}</td>
                                    @foreach ($grid->categories as $cat)
                                        @foreach ($bassins as $b)
                                            @php $cs = $map[$cat->id][$event][$g][$b] ?? null; @endphp
                                            <td class="qg-temps {{ $loop->first ? 'qg-cat-debut' : '' }}" data-cat="{{ $cat->id }}" data-bassin="{{ $b }}">
                                                @if ($cs){{ SwimTime::format($cs) }}@else<span class="text-muted" aria-label="pas de temps">–</span>@endif
                                            </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="small text-muted mt-2 mb-0">Qualification : temps égal ou plus rapide que celui de la grille, réalisé dans le bassin indiqué. « – » : épreuve non ouverte dans cette catégorie.</p>

            @if ($others->isNotEmpty())
                <p class="mt-4 mb-0">Autres grilles :
                    @foreach ($others as $o)
                        <a href="{{ route('qualifications.show', $o->slug) }}">{{ $o->name }}</a>@if (! $loop->last) · @endif
                    @endforeach
                </p>
            @endif
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
(function () {
    var racine = document.querySelector('[data-qg]');
    var barre = racine && racine.querySelector('[data-qg-filtres]');
    if (!barre) return;
    barre.hidden = false;
    var etat = { cat: '*', sexe: '*', bassin: '*', nage: '*' };

    function appliquer() {
        racine.querySelectorAll('[data-cat]').forEach(function (el) {
            var visible = (etat.cat === '*' || el.dataset.cat === etat.cat)
                && (etat.bassin === '*' || !el.dataset.bassin || el.dataset.bassin === etat.bassin);
            el.classList.toggle('qg-cache', !visible);
            // En-tête de catégorie : une seule colonne quand un bassin est choisi
            if (!el.dataset.bassin && el.hasAttribute('colspan')) el.colSpan = etat.bassin === '*' ? el.dataset.colspan || el.colSpan : 1;
        });
        racine.querySelectorAll('tbody tr').forEach(function (tr) {
            var visible = (etat.sexe === '*' || tr.dataset.sexe === etat.sexe) && (etat.nage === '*' || tr.dataset.nage === etat.nage);
            tr.classList.toggle('qg-cache', !visible);
        });
    }

    racine.querySelectorAll('th[colspan][data-cat]').forEach(function (th) { th.dataset.colspan = th.colSpan; });

    barre.addEventListener('click', function (ev) {
        var b = ev.target.closest('[data-filtre]');
        if (!b) return;
        etat[b.dataset.filtre] = b.dataset.valeur;
        barre.querySelectorAll('[data-filtre="' + b.dataset.filtre + '"]').forEach(function (x) { x.setAttribute('aria-pressed', String(x === b)); });
        appliquer();
    });
})();
</script>
@endpush
