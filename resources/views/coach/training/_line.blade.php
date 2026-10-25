{{-- Série de natation (formulaire). Variables : $b, $l (clés), $line (array) --}}
@php $n = "content[blocks][{$b}][lines][{$l}]"; $id = "b{$b}l{$l}"; @endphp
<div class="tr-line" data-line>
    <div class="tr-field tr-field--xs">
        <label class="form-label small mb-0" for="{{ $id }}-reps">Nb</label>
        <input type="number" id="{{ $id }}-reps" name="{{ $n }}[reps]" min="1" max="200" class="form-control form-control-sm" value="{{ $line['reps'] ?? 1 }}" data-reps>
    </div>
    <span class="tr-times" aria-hidden="true">×</span>
    <div class="tr-field tr-field--sm">
        <label class="form-label small mb-0" for="{{ $id }}-dist">Distance (m)</label>
        <input type="number" id="{{ $id }}-dist" name="{{ $n }}[distance]" min="0" max="10000" step="25" class="form-control form-control-sm" value="{{ $line['distance'] ?? '' }}" data-distance>
    </div>
    <div class="tr-field tr-field--md">
        <label class="form-label small mb-0" for="{{ $id }}-stroke">Nage / exercice</label>
        <input type="text" id="{{ $id }}-stroke" name="{{ $n }}[stroke]" list="liste-strokes" maxlength="100" class="form-control form-control-sm" value="{{ $line['stroke'] ?? '' }}">
    </div>
    <div class="tr-field tr-field--sm">
        <label class="form-label small mb-0" for="{{ $id }}-zone">Zone</label>
        <select id="{{ $id }}-zone" name="{{ $n }}[zone]" class="form-select form-select-sm">
            <option value="">—</option>
            @foreach (\App\Models\TrainingSession::ZONES as $code => $label)
                <option value="{{ $code }}" @selected(($line['zone'] ?? null) === $code)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="tr-field tr-field--xs">
        <label class="form-label small mb-0" for="{{ $id }}-dep">Départ</label>
        <input type="text" id="{{ $id }}-dep" name="{{ $n }}[departure]" maxlength="20" class="form-control form-control-sm" value="{{ $line['departure'] ?? '' }}" placeholder="1'45">
    </div>
    <div class="tr-field tr-field--xs">
        <label class="form-label small mb-0" for="{{ $id }}-rest">Récup</label>
        <input type="text" id="{{ $id }}-rest" name="{{ $n }}[rest]" maxlength="20" class="form-control form-control-sm" value="{{ $line['rest'] ?? '' }}" placeholder="20 s">
    </div>
    <div class="tr-field tr-field--sm">
        <label class="form-label small mb-0" for="{{ $id }}-eq">Matériel</label>
        <input type="text" id="{{ $id }}-eq" name="{{ $n }}[equipment]" list="liste-equipment" maxlength="100" class="form-control form-control-sm" value="{{ $line['equipment'] ?? '' }}">
    </div>
    <div class="tr-field tr-field--lg">
        <label class="form-label small mb-0" for="{{ $id }}-note">Consigne</label>
        <input type="text" id="{{ $id }}-note" name="{{ $n }}[note]" maxlength="255" class="form-control form-control-sm" value="{{ $line['note'] ?? '' }}" placeholder="Respiration 3 temps…">
    </div>
    <button type="button" class="btn btn-sm btn-link text-danger tr-remove" data-remove-line title="Supprimer la série">
        <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Supprimer la série</span>
    </button>
</div>
