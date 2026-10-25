@extends('layouts.coach')

@section('title', 'Importer des records FFN')

@section('content')
@php use App\Models\SwimRecord as R; $url = R::ffnUrl($level, $pool, $category); @endphp
<div class="container-lg" style="max-width: 1100px">
    <a href="{{ route('coach.records.index', ['niveau' => $level, 'bassin' => $pool, 'categorie' => $category]) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Records
    </a>
    <h1 class="h3 fw-bold mb-1">Importer des records officiels</h1>
    <p class="text-muted">Copiez le tableau depuis le site de la FFN, collez-le ci-dessous : le site le lit et vous montre le résultat avant d'enregistrer.</p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('coach.records.import') }}">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label for="level" class="form-label fw-semibold">Niveau</label>
                    <select id="level" name="level" class="form-select">
                        @foreach (array_keys(R::FFN_LEVELS) as $key)<option value="{{ $key }}" @selected($level === $key)>{{ R::levelLabel($key) }}</option>@endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="pool" class="form-label fw-semibold">Bassin</label>
                    <select id="pool" name="pool" class="form-select">
                        @foreach (R::POOLS as $p => $label)<option value="{{ $p }}" @selected($pool === $p)>{{ $p }} m</option>@endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="category" class="form-label fw-semibold">Catégorie</label>
                    <select id="category" name="category" class="form-select">
                        @foreach (R::CATEGORIES as $key => $label)<option value="{{ $key }}" @selected($category === (string) $key)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12">
                    <ol class="small mb-2">
                        <li>Ouvrez la page officielle :
                            @if ($url)<a href="{{ $url }}" target="_blank" rel="noopener">{{ R::levelLabel($level) }}, {{ $pool }} m, {{ R::CATEGORIES[$category] }}<span class="visually-hidden"> (nouvel onglet)</span></a>@endif
                            (le lien suit les choix ci-dessus après « Lire le tableau »).</li>
                        <li>Sur cette page, sélectionnez <strong>tout</strong> : <kbd>Ctrl</kbd> + <kbd>A</kbd> (Mac : <kbd>Cmd</kbd> + <kbd>A</kbd>), puis copiez : <kbd>Ctrl</kbd> + <kbd>C</kbd> (Mac : <kbd>Cmd</kbd> + <kbd>C</kbd>). Le menu et les textes autour du tableau sont ignorés.</li>
                        <li>Collez ici (<kbd>Ctrl</kbd>/<kbd>Cmd</kbd> + <kbd>V</kbd>) et cliquez sur « Lire le tableau ». Vérifiez l'aperçu, puis enregistrez.</li>
                    </ol>
                    <label for="text" class="form-label fw-semibold">Tableau copié</label>
                    <textarea id="text" name="text" rows="10" class="form-control font-monospace small" required>{{ $text }}</textarea>
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <button class="btn btn-outline-primary"><i class="fas fa-search me-1" aria-hidden="true"></i>Lire le tableau</button>
            </div>
        </div>

        @if ($parsed)
            <section class="card border-0 shadow-sm mb-4" aria-labelledby="apercu">
                <div class="card-header bg-white">
                    <h2 id="apercu" class="h5 fw-bold mb-0">Aperçu : {{ count($parsed['records']) }} record(s) lu(s)</h2>
                </div>
                @if (! $parsed['records'])
                    <div class="card-body">
                        <p class="text-danger fw-semibold mb-1">Aucun record reconnu.</p>
                        <p class="small mb-1">Vérifiez que le texte collé contient bien les lignes du tableau (épreuve, temps, nageur, club, date, lieu) : sur la page de la FFN, faites <kbd>Ctrl</kbd>/<kbd>Cmd</kbd> + <kbd>A</kbd> puis <kbd>Ctrl</kbd>/<kbd>Cmd</kbd> + <kbd>C</kbd>.</p>
                        <p class="small text-muted mb-0">Début du texte reçu : « {{ \Illuminate\Support\Str::limit(preg_replace('/\s+/u', ' ', $text), 300) }} »</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead><tr><th scope="col">Sexe</th><th scope="col">Épreuve</th><th scope="col">Temps</th><th scope="col">Nageur(s)</th><th scope="col">Club</th><th scope="col">Date</th><th scope="col">Lieu</th></tr></thead>
                            <tbody>
                                @foreach ($parsed['records'] as $r)
                                    <tr>
                                        <td>{{ R::SEXES[$r['sex']] }}</td>
                                        <th scope="row">{{ $r['event'] }}</th>
                                        <td class="fw-bold font-monospace">{{ R::formatTime($r['time_cs']) }}</td>
                                        <td class="small">{!! implode('<br>', array_map('e', $r['swimmers'])) ?: '<span class="text-muted">—</span>' !!}</td>
                                        <td class="small">{{ $r['club'] }}</td>
                                        <td class="small text-nowrap">{{ $r['record_date'] ? \Illuminate\Support\Carbon::parse($r['record_date'])->format('d/m/Y') : '' }}</td>
                                        <td class="small">{{ $r['location'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if ($parsed['ignored'])
                    <div class="card-body border-top">
                        <p class="small text-warning-emphasis mb-1"><i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>{{ count($parsed['ignored']) }} ligne(s) non lue(s) :</p>
                        <ul class="small text-muted mb-0">@foreach (array_slice($parsed['ignored'], 0, 10) as $line)<li>{{ \Illuminate\Support\Str::limit($line, 140) }}</li>@endforeach</ul>
                    </div>
                @endif
                @if ($parsed['records'])
                    <div class="card-footer bg-white d-flex flex-wrap justify-content-between gap-2">
                        <div class="form-check align-self-center">
                            <input type="hidden" name="replace" value="0">
                            <input class="form-check-input" type="checkbox" name="replace" value="1" id="replace" checked>
                            <label class="form-check-label" for="replace">Remplacer toute la liste ({{ R::levelLabel($level) }}, {{ $pool }} m, {{ R::CATEGORIES[$category] }})</label>
                        </div>
                        <button class="btn btn-primary" name="confirm" value="1"><i class="fas fa-check me-1" aria-hidden="true"></i>Enregistrer ces records</button>
                    </div>
                @endif
            </section>
        @endif
    </form>
</div>
@endsection
