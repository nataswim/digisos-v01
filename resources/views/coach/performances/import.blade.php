@extends('layouts.coach')

@section('title', 'Importer des performances depuis extraNat')

@section('content')
<div class="container-lg">
    <h1 class="h3 fw-bold mb-1">Importer des performances depuis extraNat</h1>
    <p class="text-muted">Réservé aux administrateurs. Tout l'historique est importé, au tour « Série ». Les points FFN et le niveau (DEP, REG, NAT) ne sont pas repris. Une performance déjà enregistrée n'est jamais ajoutée deux fois : vous pouvez ré-importer la même page plus tard.</p>

    @include('coach.performances._nav')

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('coach.performances.import.store') }}">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <ol class="small mb-3">
                    <li>Sur extraNat : <em>Natation → Rechercher les performances d'une nageuse ou d'un nageur</em>, puis ouvrez la fiche du nageur (onglet « Performances »).</li>
                    <li>Sélectionnez toute la page (<kbd>Cmd</kbd>+<kbd>A</kbd> sur Mac, <kbd>Ctrl</kbd>+<kbd>A</kbd> sur PC), copiez, puis collez ci-dessous. Vous pouvez coller plusieurs nageurs à la suite.</li>
                    <li>« Lire » affiche un aperçu : rien n'est enregistré avant « Importer ».</li>
                </ol>
                <label for="text" class="form-label fw-semibold">Page « Performances » copiée</label>
                <textarea id="text" name="text" rows="{{ $blocks ? 4 : 12 }}" class="form-control font-monospace small" required>{{ old('text', $text) }}</textarea>
            </div>
            <div class="card-footer bg-white text-end">
                <button class="btn btn-outline-primary"><i class="fas fa-search me-1" aria-hidden="true"></i>Lire</button>
            </div>
        </div>

        @if ($blocks)
            @php $aImporter = collect($blocks)->filter(fn ($b) => $b['swimmer'])->sum(fn ($b) => count($b['new'])); @endphp
            @foreach ($blocks as $i => $b)
                <section class="card border-0 shadow-sm mb-3" aria-labelledby="bloc-{{ $i }}">
                    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h2 id="bloc-{{ $i }}" class="h6 fw-bold mb-0">{{ $b['name'] }} <span class="text-muted fw-normal">({{ $b['birth_year'] }}) · IUF {{ $b['iuf'] }}</span></h2>
                        <span class="small">
                            <span class="badge bg-success-subtle text-success-emphasis">{{ count($b['new']) }} nouvelle(s)</span>
                            @if ($b['duplicates'])<span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $b['duplicates'] }} déjà enregistrée(s)</span>@endif
                            @if ($b['linked'])<span class="badge bg-info-subtle text-info-emphasis">{{ $b['linked'] }} rattachée(s) au calendrier</span>@endif
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 align-items-end mb-2">
                            <div class="col-md-6">
                                <label for="assign-{{ $i }}" class="form-label small fw-semibold mb-1">Nageur du club</label>
                                <select id="assign-{{ $i }}" name="assign[{{ $i }}]" class="form-select {{ ! $b['swimmer'] ? 'border-warning' : ($b['mismatch'] ? 'border-danger' : '') }}" onchange="this.form.querySelector('[name=confirm]')?.setAttribute('disabled', 'disabled'); this.form.submit()">
                                    <option value="">— ne pas importer —</option>
                                    @foreach ($swimmers as $n)
                                        <option value="{{ $n->id }}" @selected($b['swimmer']?->id === $n->id)>{{ $n->full_name }}{{ $n->birth_year ? ' (' . $n->birth_year . ')' : '' }}{{ $n->is_active ? '' : ' — inactif' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 small text-muted">
                                @if ($b['swimmer'] && $b['mismatch'])
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Attention : la fiche trouvée ({{ $b['swimmer']->full_name }}{{ $b['swimmer']->birth_year ? ', ' . $b['swimmer']->birth_year : '' }}) ne correspond pas au nom ou à l'année d'extraNat. Vérifiez le nageur choisi (et l'IUF de sa fiche).</span>
                                @elseif ($b['swimmer'])
                                    <i class="fas fa-check text-success me-1" aria-hidden="true"></i>Reconnu{{ $b['iuf'] && ($b['swimmer']->license_label === $b['iuf']) ? ' par l\'IUF' : '' }}.
                                    @if ($b['first']) Du {{ \Illuminate\Support\Carbon::parse($b['first'])->format('d/m/Y') }} au {{ \Illuminate\Support\Carbon::parse($b['last'])->format('d/m/Y') }}.@endif
                                @else
                                    <i class="fas fa-exclamation-triangle text-warning me-1" aria-hidden="true"></i>Nageur non trouvé dans le planning : choisissez-le, ou créez d'abord sa fiche (Planning → Nageurs).
                                @endif
                            </div>
                        </div>
                        @if ($b['by_event']->isNotEmpty())
                            <p class="small mb-0">
                                @foreach ($b['by_event'] as $epreuve => $n)<span class="badge rounded-pill bg-light text-dark border me-1 mb-1">{{ $epreuve }} · {{ $n }}</span>@endforeach
                            </p>
                            <details class="mt-2">
                                <summary class="small text-muted">Voir les {{ count($b['new']) }} performance(s) à importer</summary>
                                <div class="table-responsive position-relative mt-2" style="max-height: 360px">
                                    <table class="table table-sm small mb-0">
                                        <caption class="visually-hidden">Performances à importer pour {{ $b['name'] }}</caption>
                                        <thead class="table-light sticky-top"><tr><th scope="col">Date</th><th scope="col">Épreuve</th><th scope="col">Bassin</th><th scope="col">Temps</th><th scope="col">Lieu</th><th scope="col">Compétition du calendrier</th></tr></thead>
                                        <tbody>
                                            @foreach ($b['new'] as $p)
                                                <tr>
                                                    <td>{{ \Illuminate\Support\Carbon::parse($p['date'])->format('d/m/Y') }}</td>
                                                    <td>{{ $p['event'] }}</td>
                                                    <td>{{ $p['pool'] }} m</td>
                                                    <td class="font-monospace">{{ $p['time'] }}</td>
                                                    <td>{{ $p['location'] }}</td>
                                                    <td>{{ $p['competition'] ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        @endif
                    </div>
                </section>
            @endforeach

            @if ($ignored)
                <p class="small text-muted">{{ $ignored }} ligne(s) ignorée(s) (relais ou épreuve non suivie).</p>
            @endif

            <div class="d-flex justify-content-end mb-4">
                @if ($aImporter)
                    <button class="btn btn-primary" name="confirm" value="1"><i class="fas fa-check me-1" aria-hidden="true"></i>Importer {{ $aImporter }} performance(s)</button>
                @else
                    <span class="text-muted">Rien de nouveau à importer.</span>
                @endif
            </div>
        @endif
    </form>
</div>
@endsection
