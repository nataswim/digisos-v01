{{-- Bloc « réunion » du programme. Variables : $i (index ou __INDEX__), $ligne --}}
<fieldset class="ligne-repetable border rounded p-3 mb-3">
    <legend class="visually-hidden">Réunion</legend>
    <input type="hidden" name="sessions[{{ $i }}][id]" value="{{ $ligne['id'] ?? '' }}">
    <div class="row g-2">
        <div class="col-sm-6 col-lg-3">
            <label for="session-{{ $i }}-day" class="form-label small mb-1">Date</label>
            <input type="date" id="session-{{ $i }}-day" name="sessions[{{ $i }}][day]"
                   class="form-control form-control-sm" value="{{ $ligne['day'] ?? '' }}">
        </div>
        <div class="col-sm-6 col-lg-3">
            <label for="session-{{ $i }}-label" class="form-label small mb-1">Réunion</label>
            <input type="text" id="session-{{ $i }}-label" name="sessions[{{ $i }}][label]" maxlength="100"
                   class="form-control form-control-sm" value="{{ $ligne['label'] ?? '' }}" placeholder="ex. Matin — séries">
        </div>
        <div class="col-4 col-lg-2">
            <label for="session-{{ $i }}-doors" class="form-label small mb-1">Ouverture</label>
            <input type="time" id="session-{{ $i }}-doors" name="sessions[{{ $i }}][doors_open_at]"
                   class="form-control form-control-sm" value="{{ $ligne['doors_open_at'] ?? '' }}">
        </div>
        <div class="col-4 col-lg-2">
            <label for="session-{{ $i }}-warmup" class="form-label small mb-1">Échauffement</label>
            <input type="time" id="session-{{ $i }}-warmup" name="sessions[{{ $i }}][warmup_at]"
                   class="form-control form-control-sm" value="{{ $ligne['warmup_at'] ?? '' }}">
        </div>
        <div class="col-4 col-lg-2">
            <label for="session-{{ $i }}-start" class="form-label small mb-1">Début</label>
            <input type="time" id="session-{{ $i }}-start" name="sessions[{{ $i }}][starts_at]"
                   class="form-control form-control-sm" value="{{ $ligne['starts_at'] ?? '' }}">
        </div>
        <div class="col-12">
            <label for="session-{{ $i }}-events" class="form-label small mb-1">Remarques sur la réunion <span class="text-muted">(facultatif — les épreuves se cochent dans la grille du programme)</span></label>
            <textarea id="session-{{ $i }}-events" name="sessions[{{ $i }}][events]" rows="2" maxlength="3000"
                      class="form-control form-control-sm" placeholder="ex. Séries lentes et rapides, podiums à 12 h">{{ $ligne['events'] ?? '' }}</textarea>
        </div>
    </div>
    <div class="text-end mt-2">
        <button type="button" class="btn btn-sm btn-outline-danger" data-retirer>
            <i class="fas fa-times me-1" aria-hidden="true"></i>Retirer cette réunion
        </button>
    </div>
</fieldset>
