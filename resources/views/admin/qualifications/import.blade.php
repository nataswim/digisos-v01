@extends('layouts.admin')

@section('title', 'Importer — '.$grid->name)

@section('content')
<div class="container-lg">
    @include('admin.qualifications._nav')

    <form method="POST" action="{{ route('admin.qualifications.import.store', $grid) }}">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <ol class="small mb-3">
                    <li>Dans le PDF ou le tableur de la grille, sélectionnez le tableau (avec sa ligne d'en-tête « U12 (2014) U13 (2013)… » si elle existe), copiez, puis collez ci-dessous. Plusieurs pages peuvent être collées à la suite.</li>
                    <li>Chaque ligne de temps doit commencer par « Dames » ou « Messieurs » ; l'épreuve (« 50 nage libre », « 100 dos », « 200 4 nages »…) peut être sur la même ligne ou juste à côté.</li>
                    <li>« Lire » affiche un aperçu : rien n'est enregistré avant « Importer ».</li>
                </ol>
                <label for="text" class="form-label fw-semibold">Tableau copié</label>
                <textarea id="text" name="text" rows="{{ $preview ? 5 : 14 }}" class="form-control font-monospace small" required>{{ old('text', request('text')) }}</textarea>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label for="pools" class="form-label">Colonnes de chaque catégorie</label>
                        <select id="pools" name="pools" class="form-select">
                            @foreach (\App\Http\Controllers\Admin\Qualifications\QualificationGridController::POOL_ORDERS as $v => $l)
                                <option value="{{ $v }}" @selected(old('pools', request('pools', '25-50')) === $v)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <span class="form-label d-block">Temps déjà saisis</span>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mode" id="mode-merge" value="merge" @checked(old('mode', request('mode', 'merge')) === 'merge')>
                            <label class="form-check-label" for="mode-merge">Compléter : remplacer seulement les cases présentes dans le tableau</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mode" id="mode-replace" value="replace" @checked(old('mode', request('mode')) === 'replace')>
                            <label class="form-check-label" for="mode-replace">Remplacer : effacer tous les temps de la grille avant l'import</label>
                        </div>
                    </div>
                </div>
                @if ($grid->categories->isNotEmpty())
                    <p class="small text-muted mt-3 mb-0">Sans ligne d'en-tête, les colonnes suivent les catégories de la grille : {{ $grid->categories->pluck('label')->implode(', ') }}.</p>
                @endif
            </div>
            <div class="card-footer bg-white text-end">
                <button class="btn btn-outline-primary"><i class="fas fa-search me-1" aria-hidden="true"></i>Lire</button>
            </div>
        </div>

        @if ($preview)
            <section class="card border-0 shadow-sm mb-4" aria-labelledby="apercu-titre">
                <div class="card-header bg-white">
                    <h2 id="apercu-titre" class="h6 fw-bold mb-0">Aperçu : {{ $preview['count'] }} temps, {{ count($preview['rows']) }} lignes</h2>
                </div>
                <div class="card-body">
                    <p class="small mb-2">Catégories :
                        @foreach ($preview['cats'] as $c)
                            <span class="badge {{ $c['existing'] ? 'bg-primary-subtle text-primary-emphasis' : 'bg-success-subtle text-success-emphasis' }} me-1">
                                {{ $c['label'] }}
                                @php $cat = new \App\Models\QualificationCategory(['label' => $c['label'], 'birth_year_from' => $c['from'], 'birth_year_to' => $c['to']]); @endphp
                                @if ($cat->years_label)({{ $cat->years_label }})@endif
                                · {{ $c['existing'] ? 'existante' : 'nouvelle' }}
                            </span>
                        @endforeach
                    </p>

                    @if ($preview['warnings'])
                        <details class="alert alert-info small" open>
                            <summary class="fw-semibold">Remarques de lecture ({{ count($preview['warnings']) }})</summary>
                            <ul class="mb-0 mt-1">@foreach ($preview['warnings'] as $w)<li>{{ $w }}</li>@endforeach</ul>
                        </details>
                    @endif
                    @if ($preview['anomalies'])
                        <details class="alert alert-warning small" open>
                            <summary class="fw-semibold">Incohérences possibles dans la grille d'origine ({{ count($preview['anomalies']) }})</summary>
                            <ul class="mb-0 mt-1">@foreach ($preview['anomalies'] as $a)<li>{{ $a }}</li>@endforeach</ul>
                            <p class="mb-0 mt-2">Elles sont importées telles quelles : corrigez-les ensuite dans l'onglet « Temps » si besoin.</p>
                        </details>
                    @endif

                    <div class="table-responsive position-relative">
                        <table class="table table-sm table-bordered small mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Épreuve</th>
                                    @foreach ($preview['columns'] as [$ci, $pool])
                                        <th scope="col" class="text-center text-nowrap">{{ $preview['cats'][$ci]['label'] }}<br>{{ $pool }} m</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($preview['rows'] as $r)
                                    <tr>
                                        <th scope="row" class="text-nowrap">{{ $r['event'] }} {{ $r['gender'] === 'F' ? 'Dames' : 'Messieurs' }}</th>
                                        @foreach ($preview['columns'] as $col => $c)
                                            <td class="text-end text-nowrap">{{ isset($r['cells'][$col]) ? \App\Support\SwimTime::format($r['cells'][$col]) : '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-secondary"><i class="fas fa-redo me-1" aria-hidden="true"></i>Relire</button>
                    <button class="btn btn-primary" name="confirm" value="1"><i class="fas fa-file-import me-1" aria-hidden="true"></i>Importer {{ $preview['count'] }} temps</button>
                </div>
            </section>
        @endif
    </form>
</div>
@endsection
