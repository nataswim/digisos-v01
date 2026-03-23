@csrf

<div class="row g-4">

    {{-- Contenu principal --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Informations personnelles
                </h5>
            </div>
            <div class="card-body p-4">

                {{-- Nom complet --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        Nom complet <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $user->name ?? '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex : Jean Dupont"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">
                        Adresse email <span class="text-danger">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $user->email ?? '') }}"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           placeholder="exemple@domain.com"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nom d'utilisateur --}}
                <div class="mb-4">
                    <label for="username" class="form-label fw-semibold">Nom d'utilisateur</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">@</span>
                        <input type="text"
                               name="username"
                               id="username"
                               value="{{ old('username', $user->username ?? '') }}"
                               class="form-control @error('username') is-invalid @enderror"
                               placeholder="nom_utilisateur">
                    </div>
                    <div class="form-text">Nom d'utilisateur unique (optionnel).</div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div class="border-top pt-4">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="fas fa-lock me-2"></i>
                        {{ isset($user) ? 'Changer le mot de passe' : 'Mot de passe' }}
                    </h6>

                    @if(! isset($user))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Le mot de passe doit contenir au moins 8 caractères.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Laissez vide pour conserver le mot de passe actuel.
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">
                                {{ isset($user) ? 'Nouveau mot de passe' : 'Mot de passe *' }}
                            </label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   {{ ! isset($user) ? 'required' : '' }}>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                {{ isset($user) ? 'Confirmer le nouveau mot de passe' : 'Confirmer le mot de passe *' }}
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   {{ ! isset($user) ? 'required' : '' }}>
                        </div>
                    </div>
                </div>

                {{-- Informations complémentaires --}}
                <div class="border-top pt-4">
                    <h6 class="fw-semibold mb-3 text-secondary">
                        <i class="fas fa-info me-2"></i>Informations complémentaires
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Prénom</label>
                            <input type="text"
                                   name="first_name"
                                   id="first_name"
                                   value="{{ old('first_name', $user->first_name ?? '') }}"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   placeholder="Jean">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Nom de famille</label>
                            <input type="text"
                                   name="last_name"
                                   id="last_name"
                                   value="{{ old('last_name', $user->last_name ?? '') }}"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   placeholder="Dupont">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone', $user->phone ?? '') }}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="+33 1 23 45 67 89">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Date de naissance</label>
                            <input type="date"
                                   name="date_of_birth"
                                   id="date_of_birth"
                                   value="{{ old('date_of_birth', isset($user) ? $user->date_of_birth?->format('Y-m-d') : '') }}"
                                   class="form-control @error('date_of_birth') is-invalid @enderror">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="bio" class="form-label">Biographie</label>
                            <textarea name="bio"
                                      id="bio"
                                      rows="3"
                                      class="form-control @error('bio') is-invalid @enderror"
                                      placeholder="Courte présentation de l'utilisateur...">{{ old('bio', $user->bio ?? '') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- ============================================================
             PHOTO DE PROFIL — via médiathèque (media-selector.js)
        ============================================================ --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-camera me-2 text-primary"></i>Photo de profil
                </h6>
            </div>
            <div class="card-body p-4 text-center">

                {{-- Prévisualisation --}}
                @if(isset($user) && $user->avatar)
                    <img id="avatar-preview"
                         src="{{ $user->avatar }}"
                         alt="Photo de profil"
                         class="rounded-circle border mb-3"
                         style="width:100px;height:100px;object-fit:cover;">
                @else
                    {{-- Placeholder affiché tant qu'aucune image n'est sélectionnée --}}
                    <div id="avatar-placeholder"
                         class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:100px;height:100px;">
                        <i class="fas fa-user fa-2x text-primary opacity-50"></i>
                    </div>
                    {{-- Preview cachée, révélée après sélection --}}
                    <img id="avatar-preview"
                         src=""
                         alt="Prévisualisation"
                         class="rounded-circle border mb-3 d-none mx-auto"
                         style="width:100px;height:100px;object-fit:cover;">
                @endif

                {{-- Champ caché — l'URL est injectée par openMediaSelector() --}}
                <input type="hidden"
                       name="avatar"
                       id="avatar"
                       value="{{ old('avatar', $user->avatar ?? '') }}">

                {{-- Bouton d'ouverture de la médiathèque --}}
                <button type="button"
                        class="btn btn-outline-primary btn-sm w-100 mb-2"
                        onclick="openMediaSelector('avatar', 'avatar-preview'); revealPreview();">
                    <i class="fas fa-images me-2"></i>
                    {{ isset($user) && $user->avatar ? 'Changer la photo' : 'Choisir depuis la médiathèque' }}
                </button>

                {{-- Bouton suppression — visible uniquement si avatar existant --}}
                @if(isset($user) && $user->avatar)
                    <button type="button"
                            class="btn btn-outline-danger btn-sm w-100"
                            onclick="clearAvatar()">
                        <i class="fas fa-trash me-1"></i>Supprimer la photo
                    </button>
                @endif

                <div class="form-text mt-2">
                    Sélectionnez une image depuis votre médiathèque.
                </div>

                @error('avatar')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

            </div>
        </div>

        {{-- Rôle et permissions --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Rôle et permissions
                </h6>
            </div>
            <div class="card-body p-4">
                <label for="role_id" class="form-label fw-semibold">
                    Rôle <span class="text-danger">*</span>
                </label>
                <select name="role_id"
                        id="role_id"
                        class="form-select @error('role_id') is-invalid @enderror"
                        required>
                    <option value="">Choisir un rôle</option>
                    @foreach($roles ?? [] as $role)
                        <option value="{{ $role->id }}"
                                {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name ?? $role->name }}
                            @if($role->description)
                                — {{ Str::limit($role->description, 30) }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if(isset($user) && $user->role)
                    <div class="mt-3 p-3 bg-light rounded">
                        <small class="text-muted d-block mb-1">Rôle actuel</small>
                        <strong>{{ $user->role->display_name ?? $user->role->name }}</strong>
                        @if($user->role->description)
                            <div class="small text-muted mt-1">{{ $user->role->description }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Statut --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white p-4">
                <h6 class="mb-0 text-primary">
                    <i class="fas fa-toggle-on me-2"></i>Statut du compte
                </h6>
            </div>
            <div class="card-body p-4">
                <label for="status" class="form-label fw-semibold">Statut</label>
                <select name="status"
                        id="status"
                        class="form-select @error('status') is-invalid @enderror">
                    <option value="active"   {{ old('status', $user->status ?? 'active') === 'active'   ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ old('status', $user->status ?? '')       === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <small class="text-muted">Les utilisateurs inactifs ne peuvent pas se connecter.</small>
                </div>
            </div>
        </div>

        {{-- Localisation --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-globe me-2"></i>Localisation
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="locale" class="form-label">Langue</label>
                    <select name="locale" id="locale" class="form-select @error('locale') is-invalid @enderror">
                        <option value="fr" {{ old('locale', $user->locale ?? 'fr') === 'fr' ? 'selected' : '' }}>Français</option>
                        <option value="en" {{ old('locale', $user->locale ?? '')   === 'en' ? 'selected' : '' }}>English</option>
                        <option value="es" {{ old('locale', $user->locale ?? '')   === 'es' ? 'selected' : '' }}>Español</option>
                    </select>
                    @error('locale')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="timezone" class="form-label">Fuseau horaire</label>
                    <select name="timezone" id="timezone" class="form-select @error('timezone') is-invalid @enderror">
                        <option value="Europe/Paris"     {{ old('timezone', $user->timezone ?? 'Europe/Paris') === 'Europe/Paris'     ? 'selected' : '' }}>Europe/Paris</option>
                        <option value="Europe/London"    {{ old('timezone', $user->timezone ?? '')             === 'Europe/London'    ? 'selected' : '' }}>Europe/London</option>
                        <option value="America/New_York" {{ old('timezone', $user->timezone ?? '')             === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                    </select>
                    @error('timezone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
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
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    /**
     * Révèle la preview et masque le placeholder après sélection d'une image.
     * Appelé juste avant openMediaSelector() pour préparer l'affichage.
     */
    function revealPreview() {
        // Sera déclenché après que selectMedia() a mis à jour avatar-preview.src
        document.getElementById('avatar')?.addEventListener('change', function handler() {
            const placeholder = document.getElementById('avatar-placeholder');
            const preview     = document.getElementById('avatar-preview');

            if (placeholder) placeholder.classList.add('d-none');
            if (preview)     preview.classList.remove('d-none');

            // Écoute une seule fois
            this.removeEventListener('change', handler);
        });
    }

    /**
     * Réinitialise la photo de profil.
     */
    function clearAvatar() {
        const field       = document.getElementById('avatar');
        const preview     = document.getElementById('avatar-preview');
        const placeholder = document.getElementById('avatar-placeholder');

        if (field)       field.value = '';
        if (preview)   { preview.src = ''; preview.classList.add('d-none'); }
        if (placeholder) placeholder.classList.remove('d-none');
    }
</script>
@endpush