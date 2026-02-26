@extends('layouts.admin')

@section('title', 'Modifier la bannière')
@section('page-title', 'Modifier la bannière')
@section('page-description', $banner->name)

@push('styles')
<style>
.slide-card { transition: box-shadow .2s; }
.slide-card:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important; }
.slide-drag-handle { cursor: grab; }
.slide-drag-handle:active { cursor: grabbing; }
.slide-card.sortable-ghost { opacity: .4; }
.slide-img-thumb {
    width: 80px; height: 60px;
    object-fit: cover;
    border-radius: .375rem;
}
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
            <span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }} ms-1">
                {{ $banner->is_active ? 'Actif' : 'Inactif' }}
            </span>
        </div>
        <div class="bg-light border rounded p-2 small text-muted">
            <i class="fas fa-code me-1"></i>
            Intégration&nbsp;:
            <code>&#64;include('components.banner', ['slug' => '{{ $banner->slug }}'])</code>
        </div>
    </div>

    {{-- Onglets --}}
    <ul class="nav nav-tabs mb-4" id="bannerTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="settings-tab"
                    data-bs-toggle="tab" data-bs-target="#settings" type="button">
                <i class="fas fa-cog me-1"></i>Paramètres
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="slides-tab"
                    data-bs-toggle="tab" data-bs-target="#slides" type="button">
                <i class="fas fa-images me-1"></i>
                Slides
                <span class="badge bg-primary ms-1">{{ $banner->slides->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="bannerTabsContent">

        {{-- ===== ONGLET PARAMÈTRES ===== --}}
        <div class="tab-pane fade show active" id="settings" role="tabpanel">
            <form method="POST" action="{{ route('admin.banners.update', $banner) }}">
                @method('PUT')
                @include('admin.banners.partials.form', ['banner' => $banner])
            </form>
        </div>

        {{-- ===== ONGLET SLIDES ===== --}}
        <div class="tab-pane fade" id="slides" role="tabpanel">

            {{-- Formulaire ajout slide --}}
            @include('admin.banners.partials.slide-form', ['banner' => $banner])

            {{-- Liste des slides existants --}}
            @if($banner->slides->isNotEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-list-ol text-primary me-2"></i>
                        Slides existants
                        <span class="badge bg-secondary ms-1">{{ $banner->slides->count() }}</span>
                    </h6>
                    <small class="text-muted">
                        <i class="fas fa-grip-vertical me-1"></i>Glissez pour réordonner
                    </small>
                </div>
                <div class="card-body p-0">
                    <div id="slidesList">
                        @foreach($banner->slides as $slide)
                        <div class="slide-card border-bottom p-3" data-slide-id="{{ $slide->id }}">
                            <div class="d-flex align-items-start gap-3">

                                {{-- Poignée drag --}}
                                <div class="slide-drag-handle pt-2 text-muted">
                                    <i class="fas fa-grip-vertical"></i>
                                </div>

                                {{-- Miniature image --}}
                                <div class="flex-shrink-0">
                                    @if($slide->image)
                                        <img src="{{ $slide->image }}"
                                             alt="{{ $slide->image_alt ?? $slide->title }}"
                                             class="slide-img-thumb border">
                                    @else
                                        <div class="slide-img-thumb border bg-light d-flex align-items-center justify-content-center text-muted">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Infos résumées --}}
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">
                                        {{ $slide->title ?: '(sans titre)' }}
                                        <span class="badge bg-{{ $slide->is_active ? 'success' : 'secondary' }} ms-1" style="font-size:.7rem;">
                                            {{ $slide->is_active ? 'actif' : 'inactif' }}
                                        </span>
                                    </div>
                                    @if($slide->subtitle)
                                        <div class="small text-muted">{{ Str::limit($slide->subtitle, 60) }}</div>
                                    @endif
                                    @if($slide->cta_label)
                                        <span class="badge bg-light text-dark border mt-1">
                                            <i class="fas fa-link me-1"></i>{{ $slide->cta_label }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="d-flex gap-1 flex-shrink-0">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            onclick="toggleSlideEdit({{ $slide->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST"
                                          action="{{ route('admin.banners.slides.destroy', [$banner, $slide]) }}"
                                          onsubmit="return confirm('Supprimer ce slide ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>{{-- /d-flex --}}

                            {{-- Formulaire édition inline (caché par défaut) --}}
                            <div id="slideEditForm_{{ $slide->id }}" class="mt-3 pt-3 border-top d-none">
                                <form method="POST"
                                      action="{{ route('admin.banners.slides.update', [$banner, $slide]) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="row g-3">

                                        {{-- IMAGE --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Image</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       name="image"
                                                       id="slide_image_{{ $slide->id }}"
                                                       class="form-control"
                                                       value="{{ $slide->image }}"
                                                       placeholder="/storage/media/image.jpg">
                                                <button type="button"
                                                        class="btn btn-outline-primary"
                                                        onclick="openMediaSelector('slide_image_{{ $slide->id }}', 'slideImagePreview_{{ $slide->id }}')">
                                                    <i class="fas fa-images me-1"></i>Médiathèque
                                                </button>
                                            </div>
                                            {{-- Prévisualisation --}}
                                            <div id="slideImagePreviewContainer_{{ $slide->id }}"
                                                 class="mt-2 {{ $slide->image ? '' : 'd-none' }}">
                                                <img id="slideImagePreview_{{ $slide->id }}"
                                                     src="{{ $slide->image }}"
                                                     alt="Aperçu"
                                                     class="img-thumbnail"
                                                     style="max-height: 100px; object-fit: cover;">
                                            </div>
                                        </div>

                                        {{-- ALT --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Alt image</label>
                                            <input type="text"
                                                   name="image_alt"
                                                   class="form-control"
                                                   value="{{ $slide->image_alt }}"
                                                   placeholder="Description de l'image">
                                        </div>

                                        {{-- TITRE --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Titre</label>
                                            <input type="text"
                                                   name="title"
                                                   class="form-control"
                                                   value="{{ $slide->title }}">
                                        </div>

                                        {{-- SOUS-TITRE --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Sous-titre</label>
                                            <input type="text"
                                                   name="subtitle"
                                                   class="form-control"
                                                   value="{{ $slide->subtitle }}">
                                        </div>

                                        {{-- CORPS --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Corps de texte</label>
                                            <textarea name="body"
                                                      class="form-control"
                                                      rows="2">{{ $slide->body }}</textarea>
                                        </div>

                                        {{-- CTA --}}
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Label bouton CTA</label>
                                            <input type="text"
                                                   name="cta_label"
                                                   class="form-control"
                                                   value="{{ $slide->cta_label }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">URL CTA</label>
                                            <input type="url"
                                                   name="cta_url"
                                                   class="form-control"
                                                   value="{{ $slide->cta_url }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Cible</label>
                                            <select name="cta_target" class="form-select">
                                                <option value="_self" {{ $slide->cta_target === '_self' ? 'selected' : '' }}>Même onglet</option>
                                                <option value="_blank" {{ $slide->cta_target === '_blank' ? 'selected' : '' }}>Nouvel onglet</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Style bouton</label>
                                            <select name="cta_style" class="form-select">
                                                @foreach(['btn-primary' => 'Primaire', 'btn-outline-light' => 'Outline blanc', 'btn-light' => 'Blanc', 'btn-outline-primary' => 'Outline primaire', 'btn-secondary' => 'Secondaire'] as $val => $label)
                                                    <option value="{{ $val }}" {{ $slide->cta_style === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- COULEUR TEXTE --}}
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Couleur texte</label>
                                            <input type="color"
                                                   name="text_color"
                                                   class="form-control form-control-color w-100"
                                                   value="{{ $slide->text_color ?? '#ffffff' }}">
                                        </div>

                                        {{-- POSITION --}}
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Position texte</label>
                                            <select name="text_position" class="form-select">
                                                <option value="center" {{ ($slide->text_position ?? 'center') === 'center' ? 'selected' : '' }}>Centre</option>
                                                <option value="left"   {{ ($slide->text_position ?? '') === 'left'   ? 'selected' : '' }}>Gauche</option>
                                                <option value="right"  {{ ($slide->text_position ?? '') === 'right'  ? 'selected' : '' }}>Droite</option>
                                            </select>
                                        </div>

                                        {{-- OVERLAY --}}
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">
                                                Overlay <span id="overlayVal_{{ $slide->id }}">{{ $slide->overlay_opacity ?? 40 }}</span>%
                                            </label>
                                            <input type="range"
                                                   name="overlay_opacity"
                                                   class="form-range"
                                                   min="0" max="100" step="5"
                                                   value="{{ $slide->overlay_opacity ?? 40 }}"
                                                   oninput="document.getElementById('overlayVal_{{ $slide->id }}').textContent = this.value">
                                        </div>

                                        {{-- ACTIF --}}
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="is_active"
                                                       id="slide_active_{{ $slide->id }}"
                                                       value="1"
                                                       {{ $slide->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="slide_active_{{ $slide->id }}">
                                                    Slide actif
                                                </label>
                                            </div>
                                        </div>

                                        {{-- BOUTONS --}}
                                        <div class="col-12 d-flex justify-content-end gap-2">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    onclick="toggleSlideEdit({{ $slide->id }})">
                                                <i class="fas fa-times me-1"></i>Annuler
                                            </button>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-save me-1"></i>Enregistrer
                                            </button>
                                        </div>

                                    </div>{{-- /row --}}
                                </form>
                            </div>{{-- /slideEditForm --}}

                        </div>{{-- /slide-card --}}
                        @endforeach
                    </div>{{-- /slidesList --}}
                </div>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Aucun slide pour l'instant. Ajoutez-en un avec le formulaire ci-dessus.
            </div>
            @endif

        </div>{{-- /tab slides --}}
    </div>{{-- /tab-content --}}

</div>
@endsection

@push('scripts')
{{-- Sélecteur de médias --}}
<script src="{{ asset('js/media-selector.js') }}"></script>

{{-- Drag & drop --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Drag & drop reorder ─────────────────────────────────────────────
    const slidesList = document.getElementById('slidesList');
    if (slidesList) {
        Sortable.create(slidesList, {
            handle: '.slide-drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function () {
                const ids = [...slidesList.querySelectorAll('.slide-card')]
                    .map(el => el.dataset.slideId);

                fetch('{{ route('admin.banners.slides.reorder', $banner) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ ids }),
                })
                .then(r => r.json())
                .then(() => {
                    showToast('Ordre mis à jour', 'success');
                })
                .catch(() => {
                    showToast('Erreur lors du réordonnancement', 'danger');
                });
            }
        });
    }

    // ── Prévisualisation dynamique sur chaque champ image de slide ──────
    document.querySelectorAll('[id^="slide_image_"]').forEach(function (input) {
        const slideId = input.id.replace('slide_image_', '');
        const preview   = document.getElementById('slideImagePreview_' + slideId);
        const container = document.getElementById('slideImagePreviewContainer_' + slideId);

        input.addEventListener('input', function () {
            const url = this.value.trim();
            if (url && preview && container) {
                preview.src = url;
                container.classList.remove('d-none');
            } else if (container) {
                container.classList.add('d-none');
            }
        });
    });

    // ── Activation de l'onglet Slides si #slides dans l'URL ─────────────
    if (window.location.hash === '#slides') {
        const slidesTab = document.getElementById('slides-tab');
        if (slidesTab) {
            bootstrap.Tab.getOrCreateInstance(slidesTab).show();
        }
    }
});

// ── Toggle formulaire d'édition inline ──────────────────────────────────
function toggleSlideEdit(slideId) {
    const form = document.getElementById('slideEditForm_' + slideId);
    if (form) form.classList.toggle('d-none');
}

// ── Toast notification simple ────────────────────────────────────────────
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed shadow`;
    toast.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:220px;';
    toast.innerHTML = `<i class="fas fa-check me-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endpush