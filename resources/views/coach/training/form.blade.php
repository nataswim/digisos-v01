@extends('layouts.coach')

@php
    $type = $session->type;
    [$typeLabel, $typeIcon, $typeColor] = \App\Models\TrainingSession::TYPES[$type];
    $content = old('content', $session->content ?? []);
    $blocks = $content['blocks'] ?? [];
    $items = $content['items'] ?? [];
    $steps = $content['steps'] ?? [];
    $interne = in_array($type, \App\Models\TrainingSession::INTERNAL_TYPES, true);
    $ecole = $type === 'ecole';
    if ($interne && ! $steps) {
        $steps = [['title' => $ecole ? 'Accueil et échauffement' : '']];
    }
    if ($type === 'natation' && ! $session->exists && ! old('content')) {
        // Trame de départ : échauffement, corps de séance, retour au calme
        $kinds = $lists['blocks'];
        $blocks = [
            ['kind' => $kinds[0] ?? 'Échauffement', 'repeat' => 1, 'lines' => [['reps' => 1, 'distance' => 400, 'stroke' => $lists['strokes'][0] ?? 'Crawl', 'zone' => 'Z1']]],
            ['kind' => $kinds[2] ?? 'Corps de séance', 'repeat' => 1, 'lines' => [['reps' => 4, 'distance' => 100, 'zone' => 'Z3']]],
            ['kind' => end($kinds) ?: 'Retour au calme', 'repeat' => 1, 'lines' => [['reps' => 1, 'distance' => 200, 'zone' => 'Z1']]],
        ];
    }
    if (in_array($type, ['ppg', 'maison'], true) && ! $items) {
        $items = [['sets' => 3]];
    }
    $equipment = old('equipment', $session->equipment ?? []);
@endphp

@section('title', ($session->exists ? 'Modifier' : 'Nouvelle') . ' séance — ' . $typeLabel)

@section('content')
<div class="container-lg training-form" style="--type-color: {{ $typeColor }}">
    <a href="{{ route('coach.training.index', ['semaine' => $session->session_date?->copy()->startOfWeek()->toDateString()]) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Séances
    </a>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <span class="badge rounded-pill" style="background: {{ $typeColor }}"><i class="fas {{ $typeIcon }} me-1" aria-hidden="true"></i>{{ $typeLabel }}</span>
            <h1 class="h3 fw-bold mt-2 mb-0">{{ $session->exists ? 'Modifier la ' : 'Nouvelle ' }}{{ $interne ? 'fiche' : 'séance' }}</h1>
        </div>
        @if ($session->exists)
            <div class="d-flex flex-wrap gap-2">
                <span class="badge fs-6 {{ $interne ? 'bg-dark' : ($session->isPublished() ? 'bg-success' : 'bg-secondary') }} align-self-center">{{ $interne ? 'Fiche coachs' : \App\Models\TrainingSession::STATUSES[$session->status] }}</span>
                <a href="{{ route('coach.training.show', $session) }}" class="btn btn-outline-primary"><i class="fas fa-eye me-1" aria-hidden="true"></i>{{ $interne ? 'Aperçu' : 'Aperçu adhérent' }}</a>
            </div>
        @endif
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $session->exists ? route('coach.training.update', $session) : route('coach.training.store') }}" id="training-form">
        @csrf
        @if ($session->exists) @method('PUT') @else <input type="hidden" name="type" value="{{ $type }}"> @endif

        {{-- 1. Informations --}}
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h2 class="h5 fw-bold mb-0">1. Informations</h2></div>
            <div class="card-body row g-3">
                <div class="col-md-7">
                    <label for="title" class="form-label fw-semibold">Titre <span class="text-danger" aria-hidden="true">*</span></label>
                    <input type="text" id="title" name="title" required maxlength="255" class="form-control" value="{{ old('title', $session->title) }}"
                           placeholder="{{ ['natation' => 'Endurance et virages', 'ppg' => 'Gainage et renforcement', 'maison' => 'Circuit maison 20 minutes', 'ecole' => 'Découverte de la flottaison', 'divers' => 'Réunion technique, sortie, stage…'][$type] }}">
                </div>
                <div class="col-md-5">
                    <label for="objective" class="form-label fw-semibold">Objectif</label>
                    <input type="text" id="objective" name="objective" maxlength="255" class="form-control" value="{{ old('objective', $session->objective) }}" placeholder="Travail aérobie, technique de dos…">
                </div>

                @if ($slots->isNotEmpty() && $type !== 'maison')
                    <div class="col-12">
                        <label for="planning_slot_id" class="form-label fw-semibold">Créneau du planning <span class="fw-normal text-muted">(remplit horaires, lieu et groupe)</span></label>
                        <select id="planning_slot_id" name="planning_slot_id" class="form-select" data-slot-select>
                            <option value="">Aucun</option>
                            @foreach ($slots as $slot)
                                <option value="{{ $slot->id }}" data-start="{{ substr($slot->starts_at, 0, 5) }}" data-end="{{ substr($slot->ends_at, 0, 5) }}"
                                        data-location="{{ $slot->location }}" data-group="{{ $slot->planning_group_id }}" data-day="{{ $slot->day_of_week }}"
                                        @selected((int) old('planning_slot_id', $session->planning_slot_id) === $slot->id)>
                                    {{ $slot->day_name }} {{ $slot->time_range }} · {{ $slot->group?->name }}@if ($slot->location) · {{ $slot->location }}@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-sm-6 col-md-4">
                    <label for="session_date" class="form-label fw-semibold">{{ $type === 'maison' ? 'À faire avant le' : 'Date' }}</label>
                    <input type="date" id="session_date" name="session_date" required class="form-control" value="{{ old('session_date', $session->session_date?->toDateString()) }}">
                </div>
                @if ($type !== 'maison')
                    <div class="col-6 col-md-2">
                        <label for="starts_at" class="form-label fw-semibold">Début</label>
                        <input type="time" id="starts_at" name="starts_at" class="form-control" value="{{ old('starts_at', $session->starts_at ? substr($session->starts_at, 0, 5) : '') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label for="ends_at" class="form-label fw-semibold">Fin</label>
                        <input type="time" id="ends_at" name="ends_at" class="form-control" value="{{ old('ends_at', $session->ends_at ? substr($session->ends_at, 0, 5) : '') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="location" class="form-label fw-semibold">Lieu</label>
                        <input type="text" id="location" name="location" maxlength="255" class="form-control" value="{{ old('location', $session->location) }}">
                    </div>
                @else
                    <div class="col-sm-6 col-md-3">
                        <label for="rounds" class="form-label fw-semibold">Nombre de tours</label>
                        <input type="number" id="rounds" name="rounds" min="1" max="20" class="form-control" value="{{ old('rounds', $session->rounds ?: 1) }}" data-rounds aria-describedby="rounds-help">
                        <div id="rounds-help" class="form-text">1 = exercices les uns après les autres ; 3 = circuit répété 3 fois.</div>
                    </div>
                @endif
                @if ($type === 'ppg')
                    <div class="col-sm-6 col-md-3">
                        <label for="rounds" class="form-label fw-semibold">Nombre de tours</label>
                        <input type="number" id="rounds" name="rounds" min="1" max="20" class="form-control" value="{{ old('rounds', $session->rounds ?: 1) }}" data-rounds aria-describedby="rounds-help">
                        <div id="rounds-help" class="form-text">1 = exercices à la suite ; 3 = circuit répété 3 fois.</div>
                    </div>
                @endif

                <div class="col-12">
                    <span class="form-label fw-semibold d-block">Matériel</span>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (collect($lists['equipment'])->merge($equipment)->unique() as $item)
                            <input type="checkbox" class="btn-check" name="equipment[]" value="{{ $item }}" id="eq-{{ $loop->index }}" autocomplete="off" @checked(in_array($item, $equipment, true))>
                            <label class="btn btn-sm btn-outline-secondary rounded-pill tr-chip" for="eq-{{ $loop->index }}">{{ $item }}</label>
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <label for="instructions" class="form-label fw-semibold">{{ $interne ? 'Consignes et observations' : 'Consignes générales' }}</label>
                    <textarea id="instructions" name="instructions" rows="{{ $interne ? 4 : 3 }}" maxlength="5000" class="form-control" placeholder="{{ $interne ? 'Consignes, observations sur le groupe, points à revoir…' : 'Objectifs, points techniques, conseils…' }}">{{ old('instructions', $session->instructions) }}</textarea>
                </div>
            </div>
        </section>

        {{-- 2. Programme --}}
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between gap-2">
                <h2 class="h5 fw-bold mb-0">2. {{ $type === 'natation' || $interne ? 'Programme' : 'Exercices' }}</h2>
                @unless ($interne)
                    <span class="fw-bold fs-5" aria-live="polite">
                        @if ($type === 'natation') Total : <span data-total>0</span> m @else Durée estimée : ≈ <span data-total>0</span> min @endif
                    </span>
                @endunless
            </div>
            <div class="card-body bg-light">
                @if ($type === 'natation')
                    <div data-blocks>
                        @foreach ($blocks as $b => $block)
                            @include('coach.training._block', ['b' => $b, 'block' => $block])
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary" data-add-block><i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un bloc</button>

                    <template id="tpl-block">@include('coach.training._block', ['b' => '__B__', 'block' => ['lines' => []]])</template>
                    <template id="tpl-line">@include('coach.training._line', ['b' => '__B__', 'l' => '__L__', 'line' => []])</template>
                    <datalist id="liste-blocks">@foreach ($lists['blocks'] as $v)<option value="{{ $v }}">@endforeach</datalist>
                    <datalist id="liste-strokes">@foreach ($lists['strokes'] as $v)<option value="{{ $v }}">@endforeach</datalist>
                    <datalist id="liste-equipment">@foreach ($lists['equipment'] as $v)<option value="{{ $v }}">@endforeach</datalist>
                @elseif ($interne)
                    <div data-steps>
                        @foreach ($steps as $s => $step)
                            @include('coach.training._step', ['s' => $s, 'step' => $step])
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary" data-add-step><i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une étape</button>
                    <template id="tpl-step">@include('coach.training._step', ['s' => '__S__', 'step' => []])</template>
                @else
                    @if ($exercises->isEmpty())
                        <div class="alert alert-warning">
                            La bibliothèque d'exercices est vide. <a href="{{ route('coach.exercises.create') }}" target="_blank" rel="noopener">Ajoutez des exercices</a> (avec image), puis rechargez cette page.
                        </div>
                    @endif
                    <div data-items>
                        @foreach ($items as $i => $item)
                            @include('coach.training._item', ['i' => $i, 'item' => $item])
                        @endforeach
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-primary" data-add-item><i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un exercice</button>
                        <a href="{{ route('coach.exercises.create') }}" class="btn btn-outline-secondary" target="_blank" rel="noopener">
                            <i class="fas fa-book-medical me-1" aria-hidden="true"></i>Nouvel exercice dans la bibliothèque
                        </a>
                    </div>
                    <template id="tpl-item">@include('coach.training._item', ['i' => '__I__', 'item' => ['sets' => 3]])</template>
                @endif
            </div>
        </section>

        @if ($interne)
            {{-- 3. Groupe(s) concerné(s) : fiche interne, jamais montrée aux adhérents --}}
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h2 class="h5 fw-bold mb-0">3. Groupe(s) concerné(s)</h2></div>
                <div class="card-body">
                    <p class="small text-muted"><i class="fas fa-lock me-1" aria-hidden="true"></i>Fiche réservée aux coachs : elle n'est jamais visible dans l'espace des adhérents.</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($groups as $group)
                            <input type="checkbox" class="btn-check" name="groups[]" value="{{ $group->id }}" id="grp-{{ $group->id }}" autocomplete="off" @checked(in_array($group->id, array_map('intval', $selectedGroups), true))>
                            <label class="btn btn-outline-primary tr-chip" for="grp-{{ $group->id }}">{{ $group->name }}</label>
                        @endforeach
                    </div>
                </div>
            </section>
        @else
        {{-- 3. Destinataires --}}
        <section class="card border-0 shadow-sm mb-4" id="destinataires">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between gap-2">
                <h2 class="h5 fw-bold mb-0">3. Qui voit la séance ?</h2>
                <span class="fw-semibold" aria-live="polite"><span data-count>0</span> nageur(s) sélectionné(s)</span>
            </div>
            <div class="card-body">
                <p class="small text-muted">
                    Cochez un groupe pour sélectionner tous ses nageurs, puis décochez ou ajoutez des nageurs un par un
                    (séance personnalisée : blessure, préparation de compétition…). Seuls les nageurs cochés verront la séance une fois publiée,
                    dans l'espace adhérent du compte auquel ils sont rattachés.
                </p>

                <fieldset class="mb-3">
                    <legend class="fs-6 fw-semibold">Groupes</legend>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($groups as $group)
                            @php $ids = $swimmers->filter(fn ($s) => $s->groups->contains('id', $group->id))->pluck('id')->values(); @endphp
                            <input type="checkbox" class="btn-check" name="groups[]" value="{{ $group->id }}" id="grp-{{ $group->id }}" autocomplete="off"
                                   data-group-swimmers="{{ $ids->toJson() }}" @checked(in_array($group->id, array_map('intval', $selectedGroups), true))>
                            <label class="btn btn-outline-primary tr-chip" for="grp-{{ $group->id }}">{{ $group->name }} <span class="badge bg-light text-dark ms-1">{{ $ids->count() }}</span></label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset>
                    <legend class="fs-6 fw-semibold d-flex flex-wrap justify-content-between gap-2">
                        <span>Nageurs</span>
                        <span class="d-flex gap-2">
                            <label for="swimmer-filter" class="visually-hidden">Filtrer les nageurs</label>
                            <input type="search" id="swimmer-filter" class="form-control form-control-sm" placeholder="Rechercher un nageur" style="max-width: 220px">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-none>Tout décocher</button>
                        </span>
                    </legend>
                    <div class="tr-swimmers">
                        @foreach ($swimmers as $swimmer)
                            <div class="form-check" data-swimmer-row data-name="{{ mb_strtolower($swimmer->full_name) }}">
                                <input class="form-check-input" type="checkbox" name="swimmers[]" value="{{ $swimmer->id }}" id="sw-{{ $swimmer->id }}"
                                       data-swimmer @checked(in_array($swimmer->id, array_map('intval', $selectedSwimmers), true))>
                                <label class="form-check-label" for="sw-{{ $swimmer->id }}">
                                    {{ $swimmer->full_name }}
                                    <small class="text-muted">{{ $swimmer->groups->pluck('name')->join(', ') }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @if ($swimmers->isEmpty())
                        <p class="text-muted mb-0">Aucun nageur actif dans le planning.</p>
                    @endif
                </fieldset>
            </div>
        </section>

        @endif

        <div class="tr-actions d-flex flex-wrap gap-2 justify-content-end">
            @unless ($interne)
                <button type="submit" name="action" value="draft" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer en brouillon
                </button>
            @endunless
            @if ($interne)
                <button type="submit" name="action" value="draft" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer la fiche
                </button>
            @else
                <button type="submit" name="action" value="publish" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane me-2" aria-hidden="true"></i>{{ $session->isPublished() ? 'Enregistrer (publiée)' : 'Enregistrer et publier' }}
                </button>
            @endif
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    /* !important : le thème force le fond blanc des boutons « outline » */
    .btn-check:checked + .tr-chip, .btn-check:checked + .tr-chip:hover { background: #0090D4 !important; border-color: #0090D4 !important; color: #fff !important; }
    .btn-check:checked + .tr-chip .badge { background: #fff !important; }
    .tr-step-num { display: inline-flex; width: 2rem; height: 2rem; align-items: center; justify-content: center; border-radius: 50%; background: var(--type-color); color: #fff; font-weight: 700; align-self: center; }
    .tr-step-img { width: 100%; max-height: 140px; object-fit: cover; border-radius: 8px; }
    .tr-line { display: flex; flex-wrap: wrap; gap: .5rem; align-items: flex-end; padding: .5rem 0; border-bottom: 1px dashed #e2e8f0; }
    .tr-line:last-child { border-bottom: 0; }
    .tr-field--xs { width: 84px; } .tr-field--sm { width: 110px; } .tr-field--md { width: 150px; } .tr-field--lg { flex: 1 1 200px; }
    .tr-field--sm select { min-width: 110px; }
    .tr-times { font-weight: 700; padding-bottom: .35rem; }
    .tr-remove { padding-bottom: .35rem; }
    .tr-item-thumb { width: 72px; height: 72px; object-fit: cover; border-radius: 10px; }
    .tr-swimmers { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: .25rem 1rem; max-height: 340px; overflow-y: auto; padding: .5rem; border: 1px solid #e2e8f0; border-radius: 10px; }
    .tr-actions { position: sticky; bottom: 0; padding: .75rem 0; background: linear-gradient(to top, #f8fafc 70%, rgba(248,250,252,0)); z-index: 5; }
    .training-form .card-header h2 { color: #0e1655; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var form = document.getElementById('training-form');
    if (!form) return;
    var isSwim = {{ $type === 'natation' ? 'true' : 'false' }};
    var compteur = Date.now();
    function cle() { return 'n' + (compteur++); }
    function fromTemplate(id, map) {
        var html = document.getElementById(id).innerHTML;
        Object.keys(map).forEach(function (k) { html = html.split(k).join(map[k]); });
        var tmp = document.createElement('div');
        tmp.innerHTML = html.trim();
        return tmp.firstElementChild;
    }
    function num(el) { return el ? (parseInt(el.value, 10) || 0) : 0; }
    function fmt(n) { return n.toLocaleString('fr-FR'); }

    var totalEl = form.querySelector('[data-total]');
    function numeroter() {
        form.querySelectorAll('[data-step]').forEach(function (st, i) { var n = st.querySelector('[data-step-num]'); if (n) n.textContent = i + 1; });
    }

    // ----- Totaux -----
    function totaux() {
        numeroter();
        if (!totalEl) return;
        var total = 0;
        if (isSwim) {
            form.querySelectorAll('[data-block]').forEach(function (block) {
                var d = 0;
                block.querySelectorAll('[data-line]').forEach(function (line) {
                    d += Math.max(1, num(line.querySelector('[data-reps]'))) * num(line.querySelector('[data-distance]'));
                });
                d *= Math.max(1, num(block.querySelector('[data-repeat]')));
                block.querySelector('[data-block-distance]').textContent = fmt(d) + ' m';
                total += d;
            });
            form.querySelector('[data-total]').textContent = fmt(total);
        } else {
            form.querySelectorAll('[data-item]').forEach(function (item) {
                if (!item.querySelector('[data-exercise]').value) return;
                var f = function (n) { return num(item.querySelector('[name$="[' + n + ']"]')); };
                var sets = Math.max(1, f('sets'));
                var effort = f('duration') || Math.max(1, f('reps')) * {{ \App\Models\TrainingSession::SECONDS_PER_REP }};
                total += sets * effort + (sets - 1) * f('rest_sets') + f('rest_after');
            });
            var tours = Math.max(1, num(form.querySelector('[data-rounds]')));
            form.querySelector('[data-total]').textContent = Math.ceil(total * tours / 60);
        }
    }
    form.addEventListener('input', totaux);
    form.addEventListener('change', totaux);

    // ----- Déplacer / supprimer -----
    form.addEventListener('click', function (e) {
        var btn = e.target.closest('button');
        if (!btn) return;
        var card = btn.closest('[data-block], [data-item], [data-step]');
        if (btn.hasAttribute('data-move') && card) {
            if (btn.dataset.move === '-1' && card.previousElementSibling) card.parentNode.insertBefore(card, card.previousElementSibling);
            if (btn.dataset.move === '1' && card.nextElementSibling) card.parentNode.insertBefore(card.nextElementSibling, card);
            btn.focus();
        }
        if (btn.hasAttribute('data-remove-block') && confirm('Supprimer ce bloc et ses séries ?')) card.remove();
        if (btn.hasAttribute('data-remove-item')) card.remove();
        if (btn.hasAttribute('data-remove-step') && confirm('Supprimer cette étape ?')) card.remove();
        if (btn.hasAttribute('data-step-clear')) {
            card.querySelector('[data-step-image]').value = '';
            var pv = card.querySelector('[data-step-preview]'); pv.hidden = true; pv.removeAttribute('src');
        }
        if (btn.hasAttribute('data-add-step')) {
            var etape = fromTemplate('tpl-step', { '__S__': cle() });
            form.querySelector('[data-steps]').appendChild(etape);
            etape.querySelector('input').focus();
        }
        if (btn.hasAttribute('data-remove-line')) btn.closest('[data-line]').remove();
        if (btn.hasAttribute('data-add-line')) {
            var block = btn.closest('[data-block]');
            var b = block.querySelector('[data-repeat]').name.match(/blocks\]\[([^\]]+)\]/)[1];
            var line = fromTemplate('tpl-line', { '__B__': b, '__L__': cle() });
            block.querySelector('[data-lines]').appendChild(line);
            line.querySelector('[data-distance]').focus();
        }
        if (btn.hasAttribute('data-add-block')) {
            var nb = cle();
            var nouveau = fromTemplate('tpl-block', { '__B__': nb });
            nouveau.querySelector('[data-lines]').appendChild(fromTemplate('tpl-line', { '__B__': nb, '__L__': cle() }));
            form.querySelector('[data-blocks]').appendChild(nouveau);
            nouveau.querySelector('input').focus();
        }
        if (btn.hasAttribute('data-add-item')) {
            var item = fromTemplate('tpl-item', { '__I__': cle() });
            form.querySelector('[data-items]').appendChild(item);
            item.querySelector('select').focus();
        }
        totaux();
    });

    // ----- Image d'une étape saisie à la main -----
    form.addEventListener('input', function (e) {
        if (!e.target.matches('[data-step-image]')) return;
        var pv = e.target.closest('[data-step]').querySelector('[data-step-preview]');
        var u = e.target.value.trim(); pv.hidden = !u; if (u) pv.src = u;
    });

    // ----- Vignette de l'exercice choisi -----
    form.addEventListener('change', function (e) {
        if (!e.target.matches('[data-exercise]')) return;
        var opt = e.target.selectedOptions[0];
        var img = e.target.closest('[data-item]').querySelector('[data-thumb]');
        var src = opt ? opt.getAttribute('data-image') : '';
        img.hidden = !src;
        if (src) img.src = src;
    });

    // ----- Créneau du planning -----
    var slot = form.querySelector('[data-slot-select]');
    if (slot) slot.addEventListener('change', function () {
        var o = slot.selectedOptions[0];
        if (!o || !o.value) return;
        ['starts_at', 'ends_at', 'location'].forEach(function (id, i) {
            var champ = document.getElementById(id);
            var val = o.getAttribute(['data-start', 'data-end', 'data-location'][i]);
            if (champ && val) champ.value = val;
        });
        // Date : prochain jour correspondant au créneau
        var date = document.getElementById('session_date');
        var jour = parseInt(o.getAttribute('data-day'), 10);
        var d = date.value ? new Date(date.value + 'T12:00:00') : new Date();
        var iso = d.getDay() === 0 ? 7 : d.getDay();
        d.setDate(d.getDate() + ((jour - iso + 7) % 7));
        date.value = d.toISOString().slice(0, 10);
        var g = form.querySelector('#grp-' + o.getAttribute('data-group'));
        if (g && !g.checked) { g.checked = true; g.dispatchEvent(new Event('change', { bubbles: true })); }
    });

    // ----- Destinataires -----
    var nageurs = form.querySelectorAll('[data-swimmer]');
    var compteurEl = form.querySelector('[data-count]');
    function compter() { if (compteurEl) compteurEl.textContent = form.querySelectorAll('[data-swimmer]:checked').length; }
    form.querySelectorAll('[data-group-swimmers]').forEach(function (g) {
        g.addEventListener('change', function () {
            var ids = JSON.parse(g.getAttribute('data-group-swimmers'));
            // Nageurs encore couverts par un autre groupe coché
            var autres = [];
            form.querySelectorAll('[data-group-swimmers]:checked').forEach(function (o) { if (o !== g) autres = autres.concat(JSON.parse(o.getAttribute('data-group-swimmers'))); });
            ids.forEach(function (id) {
                var c = form.querySelector('#sw-' + id);
                if (!c) return;
                if (g.checked) c.checked = true;
                else if (autres.indexOf(id) === -1) c.checked = false;
            });
            compter();
        });
    });
    nageurs.forEach(function (c) { c.addEventListener('change', compter); });
    var aucun = form.querySelector('[data-none]');
    if (aucun) aucun.addEventListener('click', function () { nageurs.forEach(function (c) { c.checked = false; }); compter(); });
    var filtre = document.getElementById('swimmer-filter');
    if (filtre) filtre.addEventListener('input', function () {
        var q = this.value.trim().toLowerCase();
        form.querySelectorAll('[data-swimmer-row]').forEach(function (r) { r.hidden = q && r.getAttribute('data-name').indexOf(q) === -1; });
    });

    compter();
    totaux();
})();
</script>
@endpush
