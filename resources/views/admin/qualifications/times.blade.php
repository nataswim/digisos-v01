@extends('layouts.admin')

@section('title', 'Temps — '.$grid->name)

@push('styles')
<style>
    .qg-table { font-size: .85rem; }
    .qg-table th, .qg-table td { white-space: nowrap; vertical-align: middle; }
    .qg-table thead th { position: sticky; top: 0; background: #f8fafc; z-index: 1; }
    .qg-table .qg-event { position: sticky; left: 0; background: #fff; z-index: 1; font-weight: 700; }
    .qg-table .form-control.form-control-sm { width: 6.6rem; min-width: 6.6rem; font-variant-numeric: tabular-nums; text-align: right; padding: .2rem .45rem; font-size: .85rem; }
    .qg-table tr.qg-m td { border-bottom-width: 2px; }
    .qg-table .qg-cat { border-left: 2px solid #dbe4ee; }
    .qg-masquer tr.qg-vide { display: none; }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4">
    @include('admin.qualifications._nav')

    @if ($grid->categories->isEmpty())
        <div class="alert alert-info">Ajoutez d'abord les catégories d'âge dans l'onglet <a href="{{ route('admin.qualifications.edit', $grid) }}">Grille et catégories</a>, ou <a href="{{ route('admin.qualifications.import', $grid) }}">importez un tableau</a> avec sa ligne d'en-tête.</div>
    @else
        @if ($anomalies)
            <details class="alert alert-warning">
                <summary class="fw-semibold">{{ count($anomalies) }} incohérence(s) possible(s) à vérifier</summary>
                <ul class="mb-0 mt-2 small">@foreach ($anomalies as $a)<li>{{ $a }}</li>@endforeach</ul>
            </details>
        @endif

        <form method="POST" action="{{ route('admin.qualifications.times.update', $grid) }}">
            @csrf @method('PUT')
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <p class="small text-muted mb-0">Format libre : 28.96, 1:05.35, 01:05,35, 1.05.35… Case vide = épreuve non ouverte.</p>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="masquer-vides" checked>
                    <label class="form-check-label small" for="masquer-vides">Masquer les épreuves sans temps</label>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="table-responsive position-relative" style="max-height: 75vh;">
                    <table class="table table-sm table-hover mb-0 qg-table qg-masquer" id="qg-table">
                        <thead>
                            <tr>
                                <th scope="col" rowspan="2" class="qg-event">Épreuve</th>
                                <th scope="col" rowspan="2">Sexe</th>
                                @foreach ($grid->categories as $cat)
                                    <th scope="colgroup" colspan="2" class="text-center qg-cat">{{ $cat->label }}<br><span class="fw-normal text-muted small">{{ $cat->years_label }}</span></th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($grid->categories as $cat)
                                    <th scope="col" class="text-center qg-cat">25 m</th>
                                    <th scope="col" class="text-center">50 m</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                                @php
                                    $vide = true;
                                    foreach ($grid->categories as $cat) { if (! empty($map[$cat->id][$event])) { $vide = false; break; } }
                                @endphp
                                @foreach (['F' => 'Dames', 'M' => 'Messieurs'] as $g => $sexe)
                                    <tr class="{{ $g === 'M' ? 'qg-m' : '' }} {{ $vide ? 'qg-vide' : '' }}">
                                        @if ($g === 'F')
                                            <th scope="rowgroup" rowspan="2" class="qg-event">{{ $event }}</th>
                                        @endif
                                        <td class="small">{{ $sexe }}</td>
                                        @foreach ($grid->categories as $cat)
                                            @foreach ([25, 50] as $pool)
                                                @php
                                                    $cs = $map[$cat->id][$event][$g][$pool] ?? null;
                                                    $valeur = old("times.{$cat->id}.{$event}.{$g}.{$pool}", $cs ? \App\Support\SwimTime::format($cs) : '');
                                                @endphp
                                                <td class="{{ $pool === 25 ? 'qg-cat' : '' }}">
                                                    <input type="text" inputmode="decimal" class="form-control form-control-sm"
                                                           name="times[{{ $cat->id }}][{{ $event }}][{{ $g }}][{{ $pool }}]" value="{{ $valeur }}"
                                                           aria-label="{{ $event }} {{ $sexe }} {{ $cat->label }} bassin de {{ $pool }} m">
                                                </td>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <button class="btn btn-primary"><i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer les temps</button>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    var bascule = document.getElementById('masquer-vides');
    var table = document.getElementById('qg-table');
    if (!bascule || !table) return;
    // Aucune épreuve remplie : tout afficher
    if (!table.querySelector('tbody tr:not(.qg-vide)')) { bascule.checked = false; table.classList.remove('qg-masquer'); }
    bascule.addEventListener('change', function () { table.classList.toggle('qg-masquer', bascule.checked); });
})();
</script>
@endpush
