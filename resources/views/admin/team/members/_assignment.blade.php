{{-- Une ligne « section + fonction + ordre » du formulaire d'un membre. Variables : $i, $ligne, $sections --}}
<div class="team-assignment row g-2 align-items-start mb-2">
    <div class="col-md-4">
        <label class="form-label small fw-semibold" for="assignments-{{ $i }}-section">Section</label>
        <select id="assignments-{{ $i }}-section" name="assignments[{{ $i }}][section_id]" class="form-select" required>
            <option value="">Choisir…</option>
            @foreach ($sections as $sec)
                <option value="{{ $sec->id }}" @selected((string) ($ligne['section_id'] ?? '') === (string) $sec->id)>{{ $sec->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5">
        <label class="form-label small fw-semibold" for="assignments-{{ $i }}-position">Fonction</label>
        <input type="text" id="assignments-{{ $i }}-position" name="assignments[{{ $i }}][position]" maxlength="150" class="form-control"
               value="{{ $ligne['position'] ?? '' }}" placeholder="ex. Président, Entraîneur Avenirs, Officiel B">
    </div>
    <div class="col-8 col-md-2">
        <label class="form-label small fw-semibold" for="assignments-{{ $i }}-order">Ordre</label>
        <input type="number" id="assignments-{{ $i }}-order" name="assignments[{{ $i }}][sort_order]" min="0" max="65000" class="form-control"
               value="{{ $ligne['sort_order'] ?? 0 }}">
    </div>
    <div class="col-4 col-md-1 text-end team-assignment-remove">
        <button type="button" class="btn btn-outline-danger remove-assignment" title="Retirer de cette section">
            <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Retirer cette ligne</span>
        </button>
    </div>
</div>
