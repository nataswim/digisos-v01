@csrf

<div class="row g-4">

    {{-- Contenu principal --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">
                    <i class="fas fa-th-large me-2 text-primary"></i>Informations de l'espace
                </h5>
            </div>
            <div class="card-body p-4">

                {{-- Nom --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom de l'espace *</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', isset($espace) ? $espace->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: Piscine intérieure, Zone musculation..."
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
                              class="d-none @error('description') is-invalid @enderror">{{ old('description', isset($espace) ? $espace->description : '') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- Rattachement hiérarchique --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h6 class="mb-0">
                    <i class="fas fa-sitemap me-2 text-primary"></i>Rattachement hiérarchique
                </h6>
            </div>
            <div class="card-body p-4">

                {{-- Service (filtre JS) --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Service parent</label>
                    <select name="_service_id" id="service_id" class="form-select">
                        <option value="">— Tous les services —</option>
                        @foreach($services as $id => $serviceName)
                            <option value="{{ $id }}"
                                {{ old('_service_id', isset($espace) ? $espace->structure->service_id : request('service_id')) == $id ? 'selected' : '' }}>
                                {{ $serviceName }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Filtre pour la liste des structures</div>
                </div>

                {{-- Structure (requis) --}}
                <div class="mb-0">
                    <label for="structure_id" class="form-label fw-semibold small">Structure parente *</label>
                    <select name="structure_id"
                            id="structure_id"
                            class="form-select @error('structure_id') is-invalid @enderror"
                            required>
                        <option value="">— Sélectionner une structure —</option>
                        @foreach($structures as $id => $structureName)
                            <option value="{{ $id }}"
                                {{ old('structure_id', isset($espace) ? $espace->structure_id : request('structure_id')) == $id ? 'selected' : '' }}>
                                {{ $structureName }}
                            </option>
                        @endforeach
                    </select>
                    @error('structure_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

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
                           {{ old('is_active', isset($espace) ? $espace->is_active : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        <strong>Espace actif</strong>
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
                           value="{{ old('capacity', isset($espace) ? $espace->capacity : '') }}"
                           class="form-control @error('capacity') is-invalid @enderror"
                           placeholder="Ex: 50"
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
                           id="espacePhoto"
                           value="{{ old('photo', isset($espace) ? $espace->photo : '') }}"
                           class="form-control @error('photo') is-invalid @enderror"
                           placeholder="Sélectionner depuis la médiathèque...">
                    <button type="button"
                            class="btn btn-outline-primary"
                            onclick="openMediaSelector('espacePhoto', 'espacePhotoPreview')">
                        <i class="fas fa-images"></i>
                    </button>
                </div>
                @error('photo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                @if(isset($espace) && $espace->photo)
                    <div id="espacePhotoPreviewContainer">
                        <img src="{{ $espace->photo }}"
                             id="espacePhotoPreview"
                             alt="{{ $espace->name }}"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 180px; width: 100%; object-fit: cover;">
                    </div>
                @else
                    <div class="d-none" id="espacePhotoPreviewContainer">
                        <img id="espacePhotoPreview"
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
                    <a href="{{ route('admin.espaces.index') }}" class="btn btn-outline-secondary">
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
    const photoInput     = document.getElementById('espacePhoto');
    const photoPreview   = document.getElementById('espacePhotoPreview');
    const photoContainer = document.getElementById('espacePhotoPreviewContainer');

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
