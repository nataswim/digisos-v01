@csrf

<div class="row g-4">

    {{-- Contenu principal --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2 text-primary"></i>Informations du service
                </h5>
            </div>
            <div class="card-body p-4">

                {{-- Nom --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom du service *</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', isset($service) ? $service->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: Complexe Aquatique Municipal de Lyon"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Adresse --}}
                <div class="mb-4">
                    <label for="address" class="form-label fw-semibold">Adresse</label>
                    <input type="text"
                           name="address"
                           id="address"
                           value="{{ old('address', isset($service) ? $service->address : '') }}"
                           class="form-control @error('address') is-invalid @enderror"
                           placeholder="Ex: 12 rue des Sports, 69001 Lyon">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description avec Quill --}}
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description</label>

                    <div id="description-editor"
                         style="height: 300px; border: 1px solid #ced4da; border-radius: 0.375rem; background: white;"></div>

                    <textarea name="description"
                              id="description"
                              class="d-none @error('description') is-invalid @enderror">{{ old('description', isset($service) ? $service->description : '') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- Statut --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-toggle-on me-2 text-success"></i>Statut
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           {{ old('is_active', isset($service) ? $service->is_active : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        <strong>Service actif</strong>
                        <div class="text-muted small">Visible et utilisable dans l'application</div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Capacité --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-users me-2 text-info"></i>Capacité
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="input-group">
                    <input type="number"
                           name="capacity"
                           id="capacity"
                           value="{{ old('capacity', isset($service) ? $service->capacity : '') }}"
                           class="form-control @error('capacity') is-invalid @enderror"
                           placeholder="Ex: 500"
                           min="1">
                    <span class="input-group-text">personnes</span>
                </div>
                @error('capacity')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Photo — Media Library --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2 text-warning"></i>Photo
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="input-group mb-3">
                    <input type="text"
                           name="photo"
                           id="servicePhoto"
                           value="{{ old('photo', isset($service) ? $service->photo : '') }}"
                           class="form-control @error('photo') is-invalid @enderror"
                           placeholder="Sélectionner depuis la médiathèque...">
                    <button type="button"
                            class="btn btn-outline-primary"
                            onclick="openMediaSelector('servicePhoto', 'servicePhotoPreview')">
                        <i class="fas fa-images"></i>
                    </button>
                </div>
                @error('photo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                {{-- Aperçu --}}
                @if(isset($service) && $service->photo)
                    <div id="servicePhotoPreviewContainer">
                        <img src="{{ $service->photo }}"
                             id="servicePhotoPreview"
                             alt="{{ $service->name }}"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 180px; width: 100%; object-fit: cover;">
                    </div>
                @else
                    <div class="d-none" id="servicePhotoPreviewContainer">
                        <img id="servicePhotoPreview"
                             alt="Aperçu"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 180px; width: 100%; object-fit: cover;">
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Actions --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Quill description ──────────────────────────────────────────────────
    let quillDescription = initQuillEditor('#description-editor', 'description');

    document.querySelector('form').addEventListener('submit', function () {
        document.getElementById('description').value = quillDescription.root.innerHTML;
    });

    // ── Aperçu photo (média selector) ─────────────────────────────────────
    const photoInput   = document.getElementById('servicePhoto');
    const photoPreview = document.getElementById('servicePhotoPreview');
    const photoContainer = document.getElementById('servicePhotoPreviewContainer');

    if (photoInput) {
        photoInput.addEventListener('input', function () {
            if (this.value.trim()) {
                photoPreview.src = this.value;
                photoContainer.classList.remove('d-none');
            } else {
                photoContainer.classList.add('d-none');
            }
        });
    }

    // ── Init IA Quill si disponible ────────────────────────────────────────
    setTimeout(() => {
        if (typeof window.initQuillAI === 'function') window.initQuillAI();
    }, 1500);
});
</script>
@endpush
