{{-- Étape d'une séance école de natation / divers (formulaire). Variables : $s (clé), $step (array), $ecole (bool), $lists --}}
@php $n = "content[steps][{$s}]"; $id = "st{$s}"; $stepEq = $step['equipment'] ?? []; @endphp
<div class="card border-0 shadow-sm mb-3" data-step>
    <div class="card-header bg-white d-flex flex-wrap gap-2 align-items-end">
        <span class="tr-step-num" aria-hidden="true" data-step-num></span>
        <div style="flex: 1 1 240px">
            <label class="form-label small mb-0" for="{{ $id }}-title">Titre de l'étape</label>
            <input type="text" id="{{ $id }}-title" name="{{ $n }}[title]" maxlength="150" class="form-control fw-semibold" value="{{ $step['title'] ?? '' }}"
                   placeholder="{{ $ecole ? 'Atelier 1 : immersion' : 'Accueil, jeu, atelier…' }}">
        </div>
        <div class="d-flex gap-1 ms-auto">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-move="-1" title="Monter"><i class="fas fa-arrow-up" aria-hidden="true"></i><span class="visually-hidden">Monter l'étape</span></button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-move="1" title="Descendre"><i class="fas fa-arrow-down" aria-hidden="true"></i><span class="visually-hidden">Descendre l'étape</span></button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-step title="Supprimer"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer l'étape</span></button>
        </div>
    </div>
    <div class="card-body row g-3">
        <div class="col-md-8">
            <label class="form-label small mb-0" for="{{ $id }}-desc">Description / contenu</label>
            <textarea id="{{ $id }}-desc" name="{{ $n }}[description]" rows="4" maxlength="3000" class="form-control">{{ $step['description'] ?? '' }}</textarea>
        </div>
        <div class="col-md-4">
            <span class="form-label small d-block mb-1">Image (facultatif)</span>
            <img src="{{ $step['image'] ?? '' }}" alt="" id="{{ $id }}-preview" class="tr-step-img mb-2" @if (empty($step['image'])) hidden @endif data-step-preview>
            <div class="input-group input-group-sm">
                <input type="text" id="{{ $id }}-image" name="{{ $n }}[image]" maxlength="500" class="form-control" value="{{ $step['image'] ?? '' }}"
                       placeholder="Médiathèque…" aria-label="Image de l'étape" data-step-image>
                <button type="button" class="btn btn-primary" onclick="openMediaSelector('{{ $id }}-image', '{{ $id }}-preview')">
                    <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                </button>
                <button type="button" class="btn btn-outline-secondary" data-step-clear title="Retirer l'image">
                    <i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Retirer l'image</span>
                </button>
            </div>
        </div>
        @if ($ecole)
            <div class="col-12">
                <span class="form-label small d-block mb-1">Matériel de l'étape</span>
                <div class="d-flex flex-wrap gap-2">
                    @foreach (collect($lists['school_equipment'])->merge($stepEq)->unique() as $item)
                        <input type="checkbox" class="btn-check" name="{{ $n }}[equipment][]" value="{{ $item }}" id="{{ $id }}-eq{{ $loop->index }}" autocomplete="off" @checked(in_array($item, $stepEq, true))>
                        <label class="btn btn-sm btn-outline-secondary rounded-pill tr-chip" for="{{ $id }}-eq{{ $loop->index }}">{{ $item }}</label>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <label class="form-label small mb-0" for="{{ $id }}-orga">Organisation / consigne</label>
                <input type="text" id="{{ $id }}-orga" name="{{ $n }}[organisation]" maxlength="500" class="form-control" value="{{ $step['organisation'] ?? '' }}"
                       placeholder="Par 2, petit bain, en file indienne…">
            </div>
        @endif
    </div>
</div>
