@extends('layouts.coach')

@section('title', 'Saisir des temps')

@section('content')
@php use App\Models\SwimPerformance as P; @endphp
<div class="container-lg">
    <h1 class="h3 fw-bold mb-1">Saisir des temps</h1>
    <p class="text-muted">{{ $competition ? $competition->title : P::CONTEXTS[$context] }} — l'écart avec la meilleure performance personnelle s'affiche dès que le temps est tapé.</p>
    @include('coach.performances._nav')

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('coach.performances.store') }}" id="perf-form">
        @csrf
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h2 class="h5 fw-bold mb-0">1. Réunion</h2></div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label for="context" class="form-label fw-semibold">Type</label>
                    <select id="context" name="context" class="form-select" data-context>
                        @foreach (P::CONTEXTS as $key => $label)<option value="{{ $key }}" @selected(old('context', $context) === $key)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-8" data-show="competition">
                    <label for="competition_id" class="form-label fw-semibold">Compétition du calendrier</label>
                    <select id="competition_id" name="competition_id" class="form-select" onchange="if (this.value) window.location = '{{ route('coach.performances.create') }}?competition=' + this.value">
                        <option value="">Choisir…</option>
                        @foreach ($competitions as $c)<option value="{{ $c->id }}" @selected((int) old('competition_id', $competition?->id) === $c->id)>{{ $c->starts_on?->format('d/m/Y') }} · {{ $c->title }}</option>@endforeach
                    </select>
                    <div class="form-text">Changer de compétition recharge la liste des nageurs convoqués.</div>
                </div>
                <div class="col-md-8" data-show="meeting training">
                    <label for="meet_name" class="form-label fw-semibold">Nom de la réunion</label>
                    <input type="text" id="meet_name" name="meet_name" maxlength="255" class="form-control" value="{{ old('meet_name', $defaults['meet_name']) }}" placeholder="Meeting de Niort, contre-la-montre du mardi…">
                </div>
                <div class="col-sm-6 col-md-4">
                    <label for="performed_on" class="form-label fw-semibold">Date</label>
                    <input type="date" id="performed_on" name="performed_on" required class="form-control" max="{{ today()->toDateString() }}" value="{{ old('performed_on', $defaults['date']) }}">
                    @if ($competition && $competition->ends_on && ! $competition->ends_on->equalTo($competition->starts_on))
                        <div class="form-text">Compétition sur plusieurs jours : enregistrez chaque journée à sa date.</div>
                    @endif
                </div>
                <div class="col-sm-6 col-md-3">
                    <span class="form-label fw-semibold d-block">Bassin</span>
                    <div class="btn-group" role="group" aria-label="Bassin">
                        @foreach ([25, 50] as $p)
                            <input type="radio" class="btn-check" name="pool" id="pool{{ $p }}" value="{{ $p }}" autocomplete="off" @checked((int) old('pool', $defaults['pool']) === $p) data-pool>
                            <label class="btn btn-outline-primary perf-pool" for="pool{{ $p }}">{{ $p }} m</label>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="location" class="form-label fw-semibold">Lieu</label>
                    <input type="text" id="location" name="location" maxlength="255" class="form-control" value="{{ old('location', $defaults['location']) }}">
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between gap-2">
                <h2 class="h5 fw-bold mb-0">2. Temps</h2>
                <span class="small text-muted align-self-center">Lignes sans temps ignorées · temps : 28.45 ou 1:05.32</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 perf-grid">
                    <thead>
                        <tr><th scope="col">Nageur</th><th scope="col">Épreuve</th><th scope="col">Tour</th><th scope="col">Temps</th><th scope="col">Statut</th><th scope="col">Écart à la MPP</th><th scope="col">Note</th><th scope="col"><span class="visually-hidden">Retirer</span></th></tr>
                    </thead>
                    <tbody data-rows>
                        @foreach (old('rows', $rows) as $i => $row)
                            @include('coach.performances._row', ['i' => $i, 'row' => $row])
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" data-add><i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une ligne</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-finale title="Copie la ligne sélectionnée en « Finale »"><i class="fas fa-clone me-1" aria-hidden="true"></i>Ajouter la finale de la dernière ligne</button>
            </div>
        </section>
        <template id="tpl-row">@include('coach.performances._row', ['i' => '__I__', 'row' => ['round' => $context === 'training' ? 'clm' : 'serie']])</template>

        <div class="text-end mb-5">
            <button class="btn btn-primary btn-lg"><i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer les temps</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .perf-grid select, .perf-grid input { min-width: 90px; }
    .perf-grid .perf-swimmer { min-width: 180px; }
    .perf-grid .perf-time-input { width: 105px; font-family: var(--bs-font-monospace); font-weight: 700; }
    .perf-live { font-size: .8rem; font-weight: 600; white-space: nowrap; }
    .perf-live--pb { color: #7a5518; } .perf-live--slower { color: #45556c; } .perf-live--first { color: #094a84; } .perf-live--error { color: #dc2626; }
    .btn-check:checked + .perf-pool { background: #0090D4 !important; color: #fff !important; border-color: #0090D4 !important; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var form = document.getElementById('perf-form');
    var bests = @json($bests);
    var body = form.querySelector('[data-rows]');
    var compteur = Date.now();

    // Afficher les champs selon le type de réunion
    var ctx = form.querySelector('[data-context]');
    function majContexte() {
        form.querySelectorAll('[data-show]').forEach(function (el) { el.hidden = el.getAttribute('data-show').split(' ').indexOf(ctx.value) === -1; });
    }
    ctx.addEventListener('change', majContexte);
    majContexte();

    function parse(v) {
        v = (v || '').trim().replace(/,/g, '.').replace(/''/g, '.').replace(/"/g, '.').replace(/'/g, ':');
        var m = v.match(/^(?:(\d{1,2}):)?(\d{1,2})\.(\d{1,2})$/);
        if (!m) return null;
        var cs = m[3].length === 1 ? parseInt(m[3], 10) * 10 : parseInt(m[3], 10);
        return ((parseInt(m[1] || '0', 10) * 60) + parseInt(m[2], 10)) * 100 + cs;
    }
    function fmt(cs) {
        var min = Math.floor(cs / 6000), sec = Math.floor((cs % 6000) / 100), c = cs % 100;
        var s = (min ? min + ':' + String(sec).padStart(2, '0') : String(sec)) + '.' + String(c).padStart(2, '0');
        return s;
    }
    function pool() { var r = form.querySelector('[data-pool]:checked'); return r ? r.value : '25'; }

    function majLigne(tr) {
        var out = tr.querySelector('[data-live]');
        var sw = tr.querySelector('[data-f=swimmer]').value, ev = tr.querySelector('[data-f=event]').value;
        var st = tr.querySelector('[data-f=status]').value, raw = tr.querySelector('[data-f=time]').value;
        out.className = 'perf-live'; out.textContent = '';
        if (st !== 'valid' || !raw) return;
        var t = parse(raw);
        if (t === null) { out.textContent = 'temps illisible'; out.classList.add('perf-live--error'); return; }
        if (!sw || !ev) return;
        var best = bests[sw + '|' + ev + '|' + pool()];
        if (!best) { out.textContent = '1er temps'; out.classList.add('perf-live--first'); return; }
        var gap = t - best, pct = (gap * 100 / best);
        var signe = gap > 0 ? '+' : (gap < 0 ? '−' : '');
        out.textContent = (gap < 0 ? '★ Record perso ' : 'MPP ') + signe + fmt(Math.abs(gap)) + ' (' + signe + Math.abs(pct).toFixed(1).replace('.', ',') + ' %)';
        out.classList.add(gap < 0 ? 'perf-live--pb' : 'perf-live--slower');
        out.title = 'MPP actuelle : ' + fmt(best);
    }
    function tout() { body.querySelectorAll('tr').forEach(majLigne); }

    form.addEventListener('input', function (e) { var tr = e.target.closest('tr'); if (tr) majLigne(tr); });
    form.addEventListener('change', function (e) { if (e.target.matches('[data-pool]')) tout(); else { var tr = e.target.closest('tr'); if (tr) majLigne(tr); } });

    function nouvelleLigne() {
        var html = document.getElementById('tpl-row').innerHTML.split('__I__').join('n' + (compteur++));
        var t = document.createElement('tbody'); t.innerHTML = html.trim();
        var tr = t.firstElementChild; body.appendChild(tr); return tr;
    }
    form.querySelector('[data-add]').addEventListener('click', function () { nouvelleLigne().querySelector('select').focus(); });
    form.querySelector('[data-finale]').addEventListener('click', function () {
        var lignes = body.querySelectorAll('tr'); var src = lignes[lignes.length - 1];
        var tr = nouvelleLigne();
        if (src) {
            tr.querySelector('[data-f=swimmer]').value = src.querySelector('[data-f=swimmer]').value;
            tr.querySelector('[data-f=event]').value = src.querySelector('[data-f=event]').value;
        }
        tr.querySelector('[data-f=round]').value = 'finale';
        tr.querySelector('[data-f=time]').focus();
    });
    body.addEventListener('click', function (e) { var b = e.target.closest('[data-remove]'); if (b) b.closest('tr').remove(); });
    tout();
})();
</script>
@endpush
