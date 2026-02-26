@csrf

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white p-3">
        <h6 class="mb-0 fw-semibold">
            <i class="fas fa-cog text-primary me-2"></i>Paramètres de la bannière
        </h6>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">

            {{-- NOM --}}
            <div class="col-md-8">
                <label for="name" class="form-label fw-semibold">
                    Nom de la bannière <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $banner->name ?? '') }}"
                       placeholder="Ex: Bannière Accueil"
                       required>
                <div class="form-text">Nom interne pour identifier la bannière</div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- SLUG --}}
            <div class="col-md-4">
                <label for="slug" class="form-label fw-semibold">Slug</label>
                <input type="text"
                       name="slug"
                       id="slug"
                       class="form-control @error('slug') is-invalid @enderror"
                       value="{{ old('slug', $banner->slug ?? '') }}"
                       placeholder="banniere-accueil">
                <div class="form-text">Auto-généré si vide</div>
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div class="col-12">
                <label for="description" class="form-label fw-semibold">Description</label>
                <textarea name="description"
                          id="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="2"
                          placeholder="Description optionnelle de la bannière">{{ old('description', $banner->description ?? '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- HAUTEUR --}}
            <div class="col-md-4">
                <label for="height" class="form-label fw-semibold">
                    Hauteur <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="number"
                           name="height"
                           id="height"
                           class="form-control @error('height') is-invalid @enderror"
                           value="{{ old('height', $banner->height ?? 600) }}"
                           min="400"
                           max="1200"
                           step="50"
                           required>
                    <span class="input-group-text">px</span>
                </div>
                <div class="form-text">Entre 400 et 1200 pixels</div>
                @error('height')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- TRANSITION --}}
            <div class="col-md-4">
                <label for="transition" class="form-label fw-semibold">Type de transition</label>
                <select name="transition"
                        id="transition"
                        class="form-select @error('transition') is-invalid @enderror">
                    <option value="slide" {{ old('transition', $banner->transition ?? 'slide') === 'slide' ? 'selected' : '' }}>
                        Glissement (slide)
                    </option>
                    <option value="fade" {{ old('transition', $banner->transition ?? '') === 'fade' ? 'selected' : '' }}>
                        Fondu (fade)
                    </option>
                </select>
                @error('transition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- DÉLAI AUTOPLAY --}}
            <div class="col-md-4">
                <label for="autoplay_delay" class="form-label fw-semibold">Délai autoplay</label>
                <div class="input-group">
                    <input type="number"
                           name="autoplay_delay"
                           id="autoplay_delay"
                           class="form-control @error('autoplay_delay') is-invalid @enderror"
                           value="{{ old('autoplay_delay', $banner->autoplay_delay ?? 5000) }}"
                           min="1000"
                           max="30000"
                           step="1000">
                    <span class="input-group-text">ms</span>
                </div>
                <div class="form-text">Entre 1000 et 30000 millisecondes</div>
                @error('autoplay_delay')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- CHECKBOXES --}}
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="autoplay"
                                   id="autoplay"
                                   value="1"
                                   {{ old('autoplay', $banner->autoplay ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="autoplay">
                                <i class="fas fa-play-circle me-1"></i>Autoplay
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="show_indicators"
                                   id="show_indicators"
                                   value="1"
                                   {{ old('show_indicators', $banner->show_indicators ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_indicators">
                                <i class="fas fa-circle me-1"></i>Indicateurs
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="show_controls"
                                   id="show_controls"
                                   value="1"
                                   {{ old('show_controls', $banner->show_controls ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_controls">
                                <i class="fas fa-chevron-circle-left me-1"></i>Contrôles
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="pause_on_hover"
                                   id="pause_on_hover"
                                   value="1"
                                   {{ old('pause_on_hover', $banner->pause_on_hover ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="pause_on_hover">
                                <i class="fas fa-pause-circle me-1"></i>Pause au survol
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTIF --}}
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="is_active">
                        <i class="fas fa-toggle-on me-1"></i>Bannière active
                    </label>
                    <div class="form-text">Décochez pour désactiver temporairement la bannière sans la supprimer</div>
                </div>
            </div>

        </div>{{-- /row --}}
    </div>{{-- /card-body --}}

    {{-- FOOTER AVEC BOUTONS --}}
    <div class="card-footer bg-light p-3">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i>Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>{{ $submitLabel ?? 'Enregistrer' }}
            </button>
        </div>
    </div>
</div>{{-- /card --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-génération du slug depuis le nom
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function () {
            // Ne générer le slug que si l'utilisateur ne l'a pas modifié manuellement
            if (!slugInput.dataset.manualEdit) {
                const slug = this.value
                    .toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Retirer accents
                    .replace(/[^a-z0-9\s-]/g, '') // Garder lettres, chiffres, espaces, tirets
                    .trim()
                    .replace(/\s+/g, '-') // Espaces → tirets
                    .replace(/-+/g, '-'); // Multiple tirets → 1 tiret
                slugInput.value = slug;
            }
        });

        // Marquer le slug comme édité manuellement si l'utilisateur y touche
        slugInput.addEventListener('input', function () {
            this.dataset.manualEdit = 'true';
        });
    }
});
</script>
@endpush