@extends('layouts.coach')

@section('title', 'Appel — ' . $session->label)

@section('content')
<div class="container-lg presence-sheet">
    <a href="{{ route('coach.presence.index', ['date' => $session->date->toDateString()]) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Séances du {{ $session->date->translatedFormat('j F') }}
    </a>

    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ $session->label }}</h1>
            <p class="text-muted mb-0">
                {{ $session->date->translatedFormat('l j F Y') }}
                @if ($session->time_range) · {{ $session->time_range }}@endif
                @if ($session->location) · {{ $session->location }}@endif
                @if ($session->title && $session->group) · {{ $session->group->name }}@endif
            </p>
        </div>
        <form method="POST" action="{{ route('coach.presence.destroy', $session) }}"
              onsubmit="return confirm('Supprimer cette séance et tous ses émargements ?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1" aria-hidden="true"></i>Supprimer la séance</button>
        </form>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('coach.presence.update', $session) }}" id="feuille-presence">
        @csrf @method('PUT')

        {{-- Compteurs et action groupée --}}
        <div class="card border-0 shadow-sm mb-3 presence-summary">
            <div class="card-body d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-success fs-6" data-compteur="present">0</span><span class="small me-2">présents</span>
                <span class="badge bg-danger fs-6" data-compteur="absent">0</span><span class="small me-2">absents</span>
                <span class="badge bg-warning text-dark fs-6" data-compteur="excuse">0</span><span class="small me-2">excusés</span>
                <span class="badge bg-light text-dark border fs-6" data-compteur="vide">0</span><span class="small me-auto">non émargés</span>
                <button type="button" class="btn btn-sm btn-outline-success" id="tous-presents">
                    <i class="fas fa-check-double me-1" aria-hidden="true"></i>Non émargés → présents
                </button>
            </div>
        </div>

        @if ($swimmers->isEmpty())
            <div class="alert alert-info">Aucun nageur dans ce groupe. Ajoutez des nageurs ci-dessous.</div>
        @endif

        <ul class="list-unstyled mb-3">
            @foreach ($swimmers as $swimmer)
                @php
                    $record = $records->get($swimmer->id);
                    $current = old("status.{$swimmer->id}", $record?->status);
                    $horsGroupe = $session->group && ! $swimmer->groups->contains('id', $session->planning_group_id);
                @endphp
                <li class="card border-0 shadow-sm mb-2 presence-row" data-row>
                    <div class="card-body py-2 d-flex flex-wrap align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 200px">
                            @if ($swimmer->photo)
                                <img src="{{ $swimmer->photo }}" alt="" width="40" height="40" class="rounded-circle object-fit-cover flex-shrink-0" style="width:40px;height:40px">
                            @else
                                <span class="espace-avatar presence-avatar" aria-hidden="true">{{ $swimmer->initials }}</span>
                            @endif
                            <div>
                                <div class="fw-semibold" id="nom-{{ $swimmer->id }}">{{ $swimmer->full_name }}</div>
                                @if ($horsGroupe)<span class="badge bg-light text-muted border">Hors groupe</span>@endif
                            </div>
                        </div>

                        <fieldset class="presence-choices" aria-labelledby="nom-{{ $swimmer->id }}">
                            <legend class="visually-hidden">Présence de {{ $swimmer->full_name }}</legend>
                            <input type="hidden" name="status[{{ $swimmer->id }}]" value="">
                            @foreach ($statuses as $value => $label)
                                <input type="radio" class="btn-check" name="status[{{ $swimmer->id }}]" value="{{ $value }}"
                                       id="st-{{ $swimmer->id }}-{{ $value }}" autocomplete="off" @checked($current === $value)>
                                <label class="btn presence-btn presence-btn--{{ $value }}" for="st-{{ $swimmer->id }}-{{ $value }}">{{ $label }}</label>
                            @endforeach
                            <button type="button" class="btn btn-link btn-sm text-muted px-1" data-effacer title="Effacer">
                                <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Effacer la présence de {{ $swimmer->full_name }}</span>
                            </button>
                        </fieldset>

                        <details class="presence-details w-100" @if ($record?->comment || $record?->arrived_at) open @endif>
                            <summary class="small text-muted">
                                Arrivée et commentaire
                                @if ($record?->arrival_label)<span class="ms-1">· arrivé(e) à {{ $record->arrival_label }}</span>@endif
                            </summary>
                            <div class="row g-2 mt-1">
                                <div class="col-5 col-sm-3">
                                    <label for="arr-{{ $swimmer->id }}" class="form-label small mb-0">Heure d'arrivée</label>
                                    <input type="time" id="arr-{{ $swimmer->id }}" name="arrived_at[{{ $swimmer->id }}]" class="form-control form-control-sm"
                                           value="{{ old("arrived_at.{$swimmer->id}", $record?->arrived_at ? substr($record->arrived_at, 0, 5) : '') }}">
                                </div>
                                <div class="col-7 col-sm-9">
                                    <label for="com-{{ $swimmer->id }}" class="form-label small mb-0">Commentaire</label>
                                    <input type="text" id="com-{{ $swimmer->id }}" name="comment[{{ $swimmer->id }}]" maxlength="255" class="form-control form-control-sm"
                                           value="{{ old("comment.{$swimmer->id}", $record?->comment) }}">
                                </div>
                            </div>
                        </details>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mb-3">
            <label for="notes" class="form-label fw-semibold">Notes sur la séance</label>
            <textarea id="notes" name="notes" rows="2" maxlength="2000" class="form-control" placeholder="Contenu de la séance, remarques…">{{ old('notes', $session->notes) }}</textarea>
        </div>

        <div class="presence-save">
            <button class="btn btn-primary btn-lg w-100">
                <i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer l'émargement
            </button>
        </div>
    </form>

    {{-- Ajouter un nageur hors groupe --}}
    @if ($others->isNotEmpty())
        <form method="POST" action="{{ route('coach.presence.add-swimmer', $session) }}" class="card border-0 shadow-sm mt-4">
            @csrf
            <div class="card-body d-flex flex-wrap gap-2 align-items-end">
                <div class="flex-grow-1">
                    <label for="swimmer" class="form-label fw-semibold mb-1">Ajouter un nageur hors groupe</label>
                    <select id="swimmer" name="swimmer" class="form-select" required>
                        <option value="">Choisir un nageur…</option>
                        @foreach ($others as $other)
                            <option value="{{ $other->id }}">{{ $other->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-outline-primary"><i class="fas fa-user-plus me-1" aria-hidden="true"></i>Ajouter (présent)</button>
            </div>
            <p class="small text-muted px-3 pb-3 mb-0">Pensez à enregistrer l'émargement avant d'ajouter un nageur.</p>
        </form>
    @endif
</div>
@endsection

@push('styles')
<style>
    .presence-avatar { width: 40px !important; height: 40px !important; }
    .presence-choices { display: flex; flex-wrap: wrap; gap: .35rem; align-items: center; margin: 0; }
    .presence-btn { min-width: 92px; min-height: 44px; border: 2px solid #e2e8f0; background: #fff; color: #314158; font-weight: 600; border-radius: 10px; }
    .presence-btn:hover { border-color: #90a1b9; }
    /* !important : l'état coché reste visible au survol et après un appui sur écran tactile */
    .btn-check:checked + .presence-btn--present, .btn-check:checked + .presence-btn--present:hover { background: #059669 !important; border-color: #059669 !important; color: #fff !important; }
    .btn-check:checked + .presence-btn--absent, .btn-check:checked + .presence-btn--absent:hover { background: #dc2626 !important; border-color: #dc2626 !important; color: #fff !important; }
    .btn-check:checked + .presence-btn--excuse, .btn-check:checked + .presence-btn--excuse:hover { background: #D49F45 !important; border-color: #D49F45 !important; color: #020618 !important; }
    .btn-check:focus-visible + .presence-btn { outline: 3px solid #0090D4; outline-offset: 2px; }
    .presence-details summary { cursor: pointer; }
    .presence-save { position: sticky; bottom: 0; padding: .75rem 0; background: linear-gradient(to top, #f8fafc 70%, rgba(248,250,252,0)); z-index: 5; }
    @media (max-width: 575.98px) {
        .presence-choices { width: 100%; }
        .presence-btn { flex: 1 1 0; min-width: 0; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var form = document.getElementById('feuille-presence');
    if (!form) return;
    var rows = form.querySelectorAll('[data-row]');

    function compter() {
        var c = { present: 0, absent: 0, excuse: 0, vide: 0 };
        rows.forEach(function (row) {
            var checked = row.querySelector('.btn-check:checked');
            c[checked ? checked.value : 'vide']++;
        });
        Object.keys(c).forEach(function (k) {
            var el = form.querySelector('[data-compteur="' + k + '"]');
            if (el) el.textContent = c[k];
        });
    }

    form.addEventListener('change', compter);
    rows.forEach(function (row) {
        row.querySelector('[data-effacer]').addEventListener('click', function () {
            row.querySelectorAll('.btn-check').forEach(function (r) { r.checked = false; });
            compter();
        });
    });
    document.getElementById('tous-presents').addEventListener('click', function () {
        rows.forEach(function (row) {
            if (!row.querySelector('.btn-check:checked')) {
                row.querySelector('.btn-check[value="present"]').checked = true;
            }
        });
        compter();
    });
    compter();
})();
</script>
@endpush
