{{-- Ligne « nouveau document ». Variable : $i (index ou __INDEX__) --}}
<div class="ligne-repetable row g-2 align-items-end mb-2">
    <div class="col-md-4">
        <label for="doc-{{ $i }}-file" class="form-label small mb-1">Fichier PDF</label>
        <input type="file" id="doc-{{ $i }}-file" name="new_documents[{{ $i }}][file]" accept="application/pdf"
               class="form-control form-control-sm">
    </div>
    <div class="col-md-4">
        <label for="doc-{{ $i }}-title" class="form-label small mb-1">Titre affiché</label>
        <input type="text" id="doc-{{ $i }}-title" name="new_documents[{{ $i }}][title]" maxlength="150"
               class="form-control form-control-sm" placeholder="ex. Convocation du club">
    </div>
    <div class="col-10 col-md-3">
        <label for="doc-{{ $i }}-vis" class="form-label small mb-1">Visible par</label>
        <select id="doc-{{ $i }}-vis" name="new_documents[{{ $i }}][visibility]" class="form-select form-select-sm">
            @foreach (\App\Models\CompetitionDocument::VISIBILITIES as $cle => $libelle)
                <option value="{{ $cle }}" @selected($cle === 'members')>{{ $libelle }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-2 col-md-1 text-end">
        <button type="button" class="btn btn-sm btn-outline-danger" data-retirer title="Retirer cette ligne">
            <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Retirer cette ligne</span>
        </button>
    </div>
</div>
