{{--
    Formulaire d'ajout d'un nouveau slide
    Champs préfixés "new_slide_*" pour ne pas collisionner avec les slides existants.
--}}

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white p-3">
        <h6 class="mb-0 fw-semibold">
            <i class="fas fa-plus-circle text-primary me-2"></i>Ajouter un slide
        </h6>
    </div>
    <div class="card-body p-4">
        <form id="addSlideForm"
              method="POST"
              action="{{ route('admin.banners.slides.store', $banner) }}">
            @csrf

            <div class="row g-3">

                {{-- IMAGE --}}
                <div class="col-12">
                    <label for="new_slide_image" class="form-label fw-semibold">
                        Image <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text"
                               name="image"
                               id="new_slide_image"
                               class="form-control"
                               placeholder="/storage/media/mon-image.jpg"
                               required>
                        <button type="button"
                                class="btn btn-outline-primary"
                                onclick="openMediaSelector('new_slide_image', 'newSlideImagePreview')">
                            <i class="fas fa-images me-1"></i>Médiathèque
                        </button>
                    </div>
                    <div class="form-text">Sélectionnez depuis la médiathèque ou saisissez une URL</div>

                    {{-- Prévisualisation --}}
                    <div id="newSlideImagePreviewContainer" class="mt-2 d-none">
                        <img id="newSlideImagePreview"
                             src=""
                             alt="Aperçu"
                             class="img-thumbnail"
                             style="max-height: 120px; object-fit: cover;">
                    </div>
                </div>

                {{-- ALT IMAGE --}}
                <div class="col-md-6">
                    <label for="new_slide_image_alt" class="form-label fw-semibold">Texte alternatif (alt)</label>
                    <input type="text"
                           name="image_alt"
                           id="new_slide_image_alt"
                           class="form-control"
                           placeholder="Description de l'image pour l'accessibilité">
                </div>

                {{-- TITRE --}}
                <div class="col-md-6">
                    <label for="new_slide_title" class="form-label fw-semibold">Titre</label>
                    <input type="text"
                           name="title"
                           id="new_slide_title"
                           class="form-control"
                           placeholder="Titre du slide">
                </div>

                {{-- SOUS-TITRE --}}
                <div class="col-12">
                    <label for="new_slide_subtitle" class="form-label fw-semibold">Sous-titre</label>
                    <input type="text"
                           name="subtitle"
                           id="new_slide_subtitle"
                           class="form-control"
                           placeholder="Sous-titre ou accroche">
                </div>

                {{-- CORPS --}}
                <div class="col-12">
                    <label for="new_slide_body" class="form-label fw-semibold">Corps de texte</label>
                    <textarea name="body"
                              id="new_slide_body"
                              class="form-control"
                              rows="2"
                              placeholder="Texte descriptif (optionnel)"></textarea>
                </div>

                {{-- CTA --}}
                <div class="col-md-5">
                    <label for="new_slide_cta_label" class="form-label fw-semibold">Label du bouton CTA</label>
                    <input type="text"
                           name="cta_label"
                           id="new_slide_cta_label"
                           class="form-control"
                           placeholder="En savoir plus">
                </div>
                <div class="col-md-5">
                    <label for="new_slide_cta_url" class="form-label fw-semibold">URL du bouton CTA</label>
                    <input type="url"
                           name="cta_url"
                           id="new_slide_cta_url"
                           class="form-control"
                           placeholder="https://exemple.com/page">
                </div>
                <div class="col-md-2">
                    <label for="new_slide_cta_target" class="form-label fw-semibold">Cible</label>
                    <select name="cta_target" id="new_slide_cta_target" class="form-select">
                        <option value="_self">Même onglet</option>
                        <option value="_blank">Nouvel onglet</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="new_slide_cta_style" class="form-label fw-semibold">Style bouton</label>
                    <select name="cta_style" id="new_slide_cta_style" class="form-select">
                        <option value="btn-primary">Primaire</option>
                        <option value="btn-outline-light">Outline blanc</option>
                        <option value="btn-light">Blanc</option>
                        <option value="btn-outline-primary">Outline primaire</option>
                        <option value="btn-secondary">Secondaire</option>
                    </select>
                </div>

                {{-- COULEUR TEXTE --}}
                <div class="col-md-3">
                    <label for="new_slide_text_color" class="form-label fw-semibold">Couleur texte</label>
                    <input type="color"
                           name="text_color"
                           id="new_slide_text_color"
                           class="form-control form-control-color w-100"
                           value="#ffffff">
                </div>

                {{-- POSITION TEXTE --}}
                <div class="col-md-3">
                    <label for="new_slide_text_position" class="form-label fw-semibold">Position texte</label>
                    <select name="text_position" id="new_slide_text_position" class="form-select">
                        <option value="center">Centre</option>
                        <option value="left">Gauche</option>
                        <option value="right">Droite</option>
                    </select>
                </div>

                {{-- OPACITÉ OVERLAY --}}
                <div class="col-md-2">
                    <label for="new_slide_overlay_opacity" class="form-label fw-semibold">
                        Overlay <span id="newSlideOverlayValue">40</span>%
                    </label>
                    <input type="range"
                           name="overlay_opacity"
                           id="new_slide_overlay_opacity"
                           class="form-range"
                           min="0" max="100" step="5"
                           value="40"
                           oninput="document.getElementById('newSlideOverlayValue').textContent = this.value">
                </div>

                {{-- ACTIF --}}
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               name="is_active"
                               id="new_slide_is_active"
                               value="1"
                               checked>
                        <label class="form-check-label" for="new_slide_is_active">Slide actif</label>
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="reset"
                            class="btn btn-outline-secondary"
                            onclick="clearNewSlideForm()">
                        <i class="fas fa-times me-1"></i>Effacer
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Ajouter ce slide
                    </button>
                </div>

            </div>{{-- /row --}}
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Prévisualisation dynamique pour le champ image du nouveau slide
    const newImageInput = document.getElementById('new_slide_image');
    const newImagePreview = document.getElementById('newSlideImagePreview');
    const newImagePreviewContainer = document.getElementById('newSlideImagePreviewContainer');

    if (newImageInput) {
        newImageInput.addEventListener('input', function () {
            const url = this.value.trim();
            if (url) {
                newImagePreview.src = url;
                newImagePreviewContainer.classList.remove('d-none');
            } else {
                newImagePreviewContainer.classList.add('d-none');
            }
        });
    }

    // Callback appelé par openMediaSelector quand une image est sélectionnée
    // (la mise à jour du preview se fait via l'event 'input' ci-dessus)
});

function clearNewSlideForm() {
    const container = document.getElementById('newSlideImagePreviewContainer');
    if (container) container.classList.add('d-none');
}
</script>
@endpush