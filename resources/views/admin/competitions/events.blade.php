@extends($layout)

@section('title', 'Épreuves des compétitions')

@php
    $nages = \App\Models\SwimEvent::STROKES;
    $r = fn ($n, $p = []) => route($area . '.competitions.events.' . $n, $p);
@endphp

@section('content')
<div class="competitions-admin container-lg">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-swimmer text-primary me-2" aria-hidden="true"></i>Épreuves</h1>
            <p class="text-muted mb-0">Liste proposée dans la grille « Programme des épreuves » et dans les convocations.</p>
        </div>
        <a href="{{ route($area . '.competitions.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Compétitions</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    {{-- Ajout --}}
    <form method="POST" action="{{ $r('store') }}" class="card border-0 shadow-sm mb-4">
        @csrf
        <div class="card-body">
            <h2 class="h6 fw-bold mb-3">Ajouter une épreuve</h2>
            <div class="row g-3 align-items-end">
                <div class="col-sm-6 col-lg-2">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" name="type" class="form-select">
                        <option value="individuel" @selected(old('type') !== 'relais')>Individuelle</option>
                        <option value="relais" @selected(old('type') === 'relais')>Relais</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2" id="bloc-relayeurs">
                    <label for="legs" class="form-label">Relayeurs</label>
                    <input type="number" id="legs" name="legs" min="2" max="20" value="{{ old('legs', 4) }}" class="form-control">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label for="distance" class="form-label">Distance (m)<span class="visually-hidden"> par nageur</span></label>
                    <input type="number" id="distance" name="distance" min="10" max="5000" step="5" value="{{ old('distance', 50) }}" class="form-control" required>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label for="stroke" class="form-label">Nage</label>
                    <select id="stroke" name="stroke" class="form-select">
                        @foreach ($nages as $code => $libelle)<option value="{{ $code }}" @selected(old('stroke') === $code)>{{ $libelle }}</option>@endforeach
                    </select>
                </div>
                <div class="col-sm-8 col-lg-2">
                    <label for="label" class="form-label">Libellé <span class="text-muted small">(facultatif)</span></label>
                    <input type="text" id="label" name="label" maxlength="50" value="{{ old('label') }}" class="form-control" placeholder="automatique">
                </div>
                <div class="col-sm-4 col-lg-2">
                    <button class="btn btn-primary w-100"><i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter</button>
                </div>
            </div>
            <p class="small text-muted mt-2 mb-0">Exemples : relais, 10 relayeurs, 50 m, Nage libre → « 10×50 NL » ; individuelle, 50 m, Divers → « 50 Divers ». Les relais ont les colonnes Dames, Messieurs et Mixte.</p>
        </div>
    </form>

    @php
        $groupes = $individual->map(fn ($liste, $nage) => [$nages[$nage] ?? $nage, $liste])->values();
        if ($relays->isNotEmpty()) { $groupes->push(['Relais', $relays]); }
    @endphp

    @foreach ($groupes as [$titre, $liste])
        <section class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">{{ $titre }}</h2></div>
            <ul class="list-group list-group-flush">
                @foreach ($liste as $e)
                    <li class="list-group-item {{ $e->is_active ? '' : 'bg-light' }}">
                        <div class="ev-row">
                            <form method="POST" action="{{ $r('update', $e) }}" class="ev-form" id="ev-{{ $e->id }}">
                                @csrf @method('PUT')
                                <label class="visually-hidden" for="label-{{ $e->id }}">Libellé de {{ $e->label }}</label>
                                <input id="label-{{ $e->id }}" name="label" value="{{ $e->label }}" maxlength="50" class="form-control form-control-sm ev-label" required>
                                <label class="small text-muted ev-order" for="order-{{ $e->id }}">Ordre
                                    <input id="order-{{ $e->id }}" name="sort_order" type="number" min="0" value="{{ $e->sort_order }}" class="form-control form-control-sm d-inline-block" style="width: 110px">
                                </label>
                                <div class="form-check form-switch mb-0">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="act-{{ $e->id }}" @checked($e->is_active)>
                                    <label class="form-check-label small" for="act-{{ $e->id }}">Active</label>
                                </div>
                            </form>
                            <div class="ev-actions">
                                @if (isset($used[$e->id]))<span class="badge bg-info-subtle text-info-emphasis">utilisée</span>@endif
                                <button form="ev-{{ $e->id }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-save" aria-hidden="true"></i><span class="visually-hidden">Enregistrer {{ $e->label }}</span></button>
                                <form method="POST" action="{{ $r('destroy', $e) }}" class="d-inline"
                                      onsubmit="return confirm('{{ isset($used[$e->id]) ? 'Épreuve utilisée : elle sera désactivée.' : 'Supprimer cette épreuve ?' }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer {{ $e->label }}</span></button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
    @endforeach

    <p class="small text-muted">Désactiver une épreuve la retire des grilles et des convocations à venir ; les programmes déjà enregistrés la conservent. L'ordre range les épreuves dans la grille (plus petit = plus haut).</p>
</div>
@endsection

@push('styles')
<style>
    .ev-row { display: flex; flex-wrap: wrap; gap: .5rem 1rem; align-items: center; justify-content: space-between; }
    .ev-form { display: flex; flex-wrap: wrap; gap: .5rem 1rem; align-items: center; flex: 1 1 320px; }
    .ev-label { flex: 1 1 160px; max-width: 240px; }
    .ev-actions { display: flex; gap: .4rem; align-items: center; }
</style>
@endpush

@push('scripts')
<script>
    (() => {
        const type = document.getElementById('type'), bloc = document.getElementById('bloc-relayeurs');
        const maj = () => { bloc.hidden = type.value !== 'relais'; };
        type.addEventListener('change', maj); maj();
    })();
</script>
@endpush
