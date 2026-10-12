{{-- Ligne « encadrement » du formulaire de compétition. Variables : $i (index ou __INDEX__), $ligne --}}
<div class="ligne-repetable row g-2 align-items-end mb-2">
    <div class="col-md-5">
        <label for="staff-{{ $i }}-name" class="form-label small mb-1">Nom</label>
        <input type="text" id="staff-{{ $i }}-name" name="staff[{{ $i }}][name]" maxlength="120"
               class="form-control form-control-sm" value="{{ $ligne['name'] ?? '' }}">
    </div>
    <div class="col-6 col-md-3">
        <label for="staff-{{ $i }}-role" class="form-label small mb-1">Rôle</label>
        <select id="staff-{{ $i }}-role" name="staff[{{ $i }}][role]" class="form-select form-select-sm">
            @foreach (\App\Models\CompetitionStaff::ROLES as $cle => $libelle)
                <option value="{{ $cle }}" @selected(($ligne['role'] ?? 'entraineur') === $cle)>{{ $libelle }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-3">
        <label for="staff-{{ $i }}-phone" class="form-label small mb-1">Téléphone</label>
        <input type="tel" id="staff-{{ $i }}-phone" name="staff[{{ $i }}][phone]" maxlength="30"
               class="form-control form-control-sm" value="{{ $ligne['phone'] ?? '' }}">
    </div>
    <div class="col-md-1 text-md-end">
        <button type="button" class="btn btn-sm btn-outline-danger" data-retirer title="Retirer cette ligne">
            <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Retirer cette ligne</span>
        </button>
    </div>
</div>
