{{-- Exercice d'une séance PPG / maison (formulaire). Variables : $i (clé), $item (array), $exercises --}}
@php $n = "content[items][{$i}]"; $id = "it{$i}"; $current = $exercises->firstWhere('id', $item['exercise_id'] ?? null); @endphp
<div class="card border-0 shadow-sm mb-3" data-item>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-3 align-items-start">
            <img src="{{ $current?->image }}" alt="" class="tr-item-thumb" data-thumb @if (! $current?->image) hidden @endif>
            <div class="flex-grow-1" style="min-width: 220px">
                <label class="form-label small mb-0 fw-semibold" for="{{ $id }}-ex">Exercice</label>
                <select id="{{ $id }}-ex" name="{{ $n }}[exercise_id]" class="form-select" data-exercise>
                    <option value="">Choisir dans la bibliothèque…</option>
                    @foreach ($exercises->groupBy(fn ($e) => $e->category ?: 'Autres') as $categorie => $liste)
                        <optgroup label="{{ $categorie }}">
                            @foreach ($liste as $exercise)
                                <option value="{{ $exercise->id }}" data-image="{{ $exercise->image }}" @selected(($item['exercise_id'] ?? null) == $exercise->id)>{{ $exercise->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="d-flex gap-1 ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-move="-1" title="Monter"><i class="fas fa-arrow-up" aria-hidden="true"></i><span class="visually-hidden">Monter l'exercice</span></button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-move="1" title="Descendre"><i class="fas fa-arrow-down" aria-hidden="true"></i><span class="visually-hidden">Descendre l'exercice</span></button>
                <button type="button" class="btn btn-sm btn-outline-danger" data-remove-item title="Retirer"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Retirer l'exercice</span></button>
            </div>
        </div>
        <div class="row g-2 mt-1">
            <div class="col-4 col-md-2">
                <label class="form-label small mb-0" for="{{ $id }}-sets">Séries</label>
                <input type="number" id="{{ $id }}-sets" name="{{ $n }}[sets]" min="1" max="50" class="form-control form-control-sm" value="{{ $item['sets'] ?? 3 }}" data-calc>
            </div>
            <div class="col-4 col-md-2">
                <label class="form-label small mb-0" for="{{ $id }}-reps">Répétitions</label>
                <input type="number" id="{{ $id }}-reps" name="{{ $n }}[reps]" min="0" max="1000" class="form-control form-control-sm" value="{{ $item['reps'] ?? '' }}" data-calc>
            </div>
            <div class="col-4 col-md-2">
                <label class="form-label small mb-0" for="{{ $id }}-dur">ou durée (s)</label>
                <input type="number" id="{{ $id }}-dur" name="{{ $n }}[duration]" min="0" max="3600" step="5" class="form-control form-control-sm" value="{{ $item['duration'] ?? '' }}" data-calc>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small mb-0" for="{{ $id }}-rs">Repos entre séries (s)</label>
                <input type="number" id="{{ $id }}-rs" name="{{ $n }}[rest_sets]" min="0" max="1800" step="5" class="form-control form-control-sm" value="{{ $item['rest_sets'] ?? '' }}" data-calc>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small mb-0" for="{{ $id }}-ra">Repos après l'exercice (s)</label>
                <input type="number" id="{{ $id }}-ra" name="{{ $n }}[rest_after]" min="0" max="1800" step="5" class="form-control form-control-sm" value="{{ $item['rest_after'] ?? '' }}" data-calc>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-0" for="{{ $id }}-load">Charge</label>
                <input type="text" id="{{ $id }}-load" name="{{ $n }}[load]" maxlength="100" class="form-control form-control-sm" value="{{ $item['load'] ?? '' }}" placeholder="Poids du corps, élastique moyen, 2 kg…">
            </div>
            <div class="col-md-8">
                <label class="form-label small mb-0" for="{{ $id }}-note">Consigne pour cette séance</label>
                <input type="text" id="{{ $id }}-note" name="{{ $n }}[note]" maxlength="255" class="form-control form-control-sm" value="{{ $item['note'] ?? '' }}" placeholder="Objectif, variante, point d'attention…">
            </div>
        </div>
    </div>
</div>
