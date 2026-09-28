@csrf

<div class="row g-4">

    {{-- Contenu principal --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">
                    <i class="fas fa-layer-group me-2 text-primary"></i>Informations de la structure
                </h5>
            </div>
            <div class="card-body p-4">

                {{-- Nom --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom de la structure *</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', isset($structure) ? $structure->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: Centre aquatique, Salle fitness..."
                           required>
                    @error('name')
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
                              class="d-none @error('description') is-invalid @enderror">{{ old('description', isset($structure) ? $structure->description : '') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- Service parent --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-building me-2 text-primary"></i>Service parent *
                </h6>
            </div>
            <div class="card-body p-4">
                <select name="service_id"
                        id="service_id"
                        class="form-select @error('service_id') is-invalid @enderror"
                        required>
                    <option value="">— Sélectionner un service —</option>
                    @foreach($services as $id => $serviceName)
                        <option value="{{ $id }}"
                            {{ old('service_id', isset($structure) ? $structure->service_id : request('service_id')) == $id ? 'selected' : '' }}>
                            {{ $serviceName }}
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Installation principale de rattachement</div>
            </div>
        </div>

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
                           {{ old('is_active', isset($structure) ? $structure->is_active : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        <strong>Structure active</strong>
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
                           value="{{ old('capacity', isset($structure) ? $structure->capacity : '') }}"
                           class="form-control @error('capacity') is-invalid @enderror"
                           placeholder="Ex: 200"
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
                           id="structurePhoto"
                           value="{{ old('photo', isset($structure) ? $structure->photo : '') }}"
                           class="form-control @error('photo') is-invalid @enderror"
                           placeholder="Sélectionner depuis la médiathèque...">
                    <button type="button"
                            class="btn btn-outline-primary"
                            onclick="openMediaSelector('structurePhoto', 'structurePhotoPreview')">
                        <i class="fas fa-images"></i>
                    </button>
                </div>
                @error('photo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                @if(isset($structure) && $structure->photo)
                    <div id="structurePhotoPreviewContainer">
                        <img src="{{ $structure->photo }}"
                             id="structurePhotoPreview"
                             alt="{{ $structure->name }}"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 180px; width: 100%; object-fit: cover;">
                    </div>
                @else
                    <div class="d-none" id="structurePhotoPreviewContainer">
                        <img id="structurePhotoPreview"
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
                    <a href="{{ route('admin.structures.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <button type="submit" name="action" value="save" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ $submitLabel }}
                    </button>
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
    const photoInput     = document.getElementById('structurePhoto');
    const photoPreview   = document.getElementById('structurePhotoPreview');
    const photoContainer = document.getElementById('structurePhotoPreviewContainer');

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

    setTimeout(() => {
        if (typeof window.initQuillAI === 'function') window.initQuillAI();
    }, 1500);
});
</script>
@endpush
