{{-- Bloc de natation (formulaire). Variables : $b (clé), $block (array) --}}
<div class="card border-0 shadow-sm mb-3 tr-block" data-block>
    <div class="card-header bg-white d-flex flex-wrap gap-2 align-items-end">
        <div style="min-width: 180px; flex: 1 1 180px">
            <label class="form-label small mb-0" for="b{{ $b }}-kind">Type de bloc</label>
            <input type="text" id="b{{ $b }}-kind" name="content[blocks][{{ $b }}][kind]" list="liste-blocks" maxlength="100"
                   class="form-control form-control-sm fw-semibold" value="{{ $block['kind'] ?? '' }}" placeholder="Échauffement">
        </div>
        <div style="min-width: 160px; flex: 2 1 160px">
            <label class="form-label small mb-0" for="b{{ $b }}-title">Intitulé (facultatif)</label>
            <input type="text" id="b{{ $b }}-title" name="content[blocks][{{ $b }}][title]" maxlength="150"
                   class="form-control form-control-sm" value="{{ $block['title'] ?? '' }}" placeholder="Pyramide, travail de virages…">
        </div>
        <div style="width: 90px">
            <label class="form-label small mb-0" for="b{{ $b }}-repeat">Répéter</label>
            <input type="number" id="b{{ $b }}-repeat" name="content[blocks][{{ $b }}][repeat]" min="1" max="20"
                   class="form-control form-control-sm" value="{{ $block['repeat'] ?? 1 }}" data-repeat>
        </div>
        <div class="ms-auto d-flex gap-1 align-self-center">
            <span class="badge bg-primary fs-6 me-1" data-block-distance>0 m</span>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-move="-1" title="Monter le bloc"><i class="fas fa-arrow-up" aria-hidden="true"></i><span class="visually-hidden">Monter le bloc</span></button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-move="1" title="Descendre le bloc"><i class="fas fa-arrow-down" aria-hidden="true"></i><span class="visually-hidden">Descendre le bloc</span></button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-block title="Supprimer le bloc"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer le bloc</span></button>
        </div>
    </div>
    <div class="card-body">
        <div data-lines>
            @foreach (($block['lines'] ?? []) as $l => $line)
                @include('coach.training._line', ['b' => $b, 'l' => $l, 'line' => $line])
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" data-add-line><i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une série</button>
        <div class="mt-2">
            <label class="form-label small mb-0" for="b{{ $b }}-note">Consigne du bloc</label>
            <input type="text" id="b{{ $b }}-note" name="content[blocks][{{ $b }}][note]" maxlength="500" class="form-control form-control-sm" value="{{ $block['note'] ?? '' }}">
        </div>
    </div>
</div>
