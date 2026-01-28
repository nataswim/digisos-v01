@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Informations personnelles
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Nom complet -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom complet *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', isset($user) ? $user->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: Jean Dupont"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Adresse email *</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', isset($user) ? $user->email : '') }}"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           placeholder="exemple@domain.com"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nom d'utilisateur (optionnel) -->
                <div class="mb-4">
                    <label for="username" class="form-label fw-semibold">Nom d'utilisateur</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">@</span>
                        <input type="text" 
                               name="username" 
                               id="username" 
                               value="{{ old('username', isset($user) ? $user->username : '') }}"
                               class="form-control @error('username') is-invalid @enderror"
                               placeholder="nom_utilisateur">
                    </div>
                    <div class="form-text">Nom d'utilisateur unique (optionnel)</div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mots de passe -->
                <div class="border-top pt-4">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="fas fa-lock me-2"></i>
                        {{ isset($user) ? 'Changer le mot de passe' : 'Mot de passe' }}
                    </h6>
                    
                    @if(!isset($user))
                        <div class="alert alert-info">
                            <i class="fas fa-water me-2"></i>
                            Le mot de passe doit contenir au moins 8 caracteres.
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
                                   {{ !isset($user) ? 'required' : '' }}>
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
                                   {{ !isset($user) ? 'required' : '' }}>
                        </div>
                    </div>
                </div>

                <!-- Informations complementaires -->
                <div class="border-top pt-4">
                    <h6 class="fw-semibold mb-3 text-secondary">
                        <i class="fas fa-info me-2"></i>Informations complementaires
                    </h6>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Prenom</label>
                            <input type="text" 
                                   name="first_name" 
                                   id="first_name" 
                                   value="{{ old('first_name', isset($user) ? $user->first_name : '') }}"
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
                                   value="{{ old('last_name', isset($user) ? $user->last_name : '') }}"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   placeholder="Dupont">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telephone</label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', isset($user) ? $user->phone : '') }}"
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
                                      placeholder="Courte presentation de l'utilisateur...">{{ old('bio', isset($user) ? $user->bio : '') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Rôle et permissions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Rôle et permissions
                </h6>
            </div>
            <div class="card-body p-4">
                <label for="role_id" class="form-label fw-semibold">Rôle *</label>
                <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                    <option value="">Choisir un rôle</option>
                    @foreach($roles ?? [] as $role)
                        <option value="{{ $role->id }}" 
                                {{ old('role_id', isset($user) ? $user->role_id : '') == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name ?? $role->name }}
                            @if($role->description)
                                - {!! Str::limit($role->description, 30) !!}
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

        <!-- Statut du compte -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-toggle-on me-2"></i>Statut du compte
                </h6>
            </div>
            <div class="card-body p-4">
                <label for="status" class="form-label fw-semibold">Statut</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="active" {{ old('status', isset($user) ? $user->status : 'active') === 'active' ? 'selected' : '' }}>
                        Actif
                    </option>
                    <option value="inactive" {{ old('status', isset($user) ? $user->status : '') === 'inactive' ? 'selected' : '' }}>
                        Inactif
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="form-text">
                    <small class="text-muted">
                        <i class="fas fa-water me-1"></i>
                        Les utilisateurs inactifs ne peuvent pas se connecter
                    </small>
                </div>
            </div>
        </div>

        <!-- Avatar -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>Photo de profil
                </h6>
            </div>
            <div class="card-body p-4">
                <input type="text" 
                       name="avatar" 
                       value="{{ old('avatar', isset($user) ? $user->avatar : '') }}"
                       class="form-control @error('avatar') is-invalid @enderror"
                       placeholder="https://example.com/avatar.jpg">
                <div class="form-text">URL de l'image de profil (temporaire)</div>
                @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if(isset($user) && $user->avatar)
                    <div class="mt-3 text-center">
                        <img src="{{ $user->avatar }}" 
                             alt="Avatar actuel" 
                             class="rounded-circle"
                             style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="small text-muted mt-1">Avatar actuel</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Localisation -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-water me-2"></i>Localisation
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="locale" class="form-label">Langue</label>
                    <select name="locale" id="locale" class="form-select @error('locale') is-invalid @enderror">
                        <option value="">Defaut (Français)</option>
                        <option value="fr" {{ old('locale', isset($user) ? $user->locale : '') === 'fr' ? 'selected' : '' }}>Français</option>
                        <option value="en" {{ old('locale', isset($user) ? $user->locale : '') === 'en' ? 'selected' : '' }}>English</option>
                        <option value="es" {{ old('locale', isset($user) ? $user->locale : '') === 'es' ? 'selected' : '' }}>Español</option>
                    </select>
                    @error('locale')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="timezone" class="form-label">Fuseau horaire</label>
                    <select name="timezone" id="timezone" class="form-select @error('timezone') is-invalid @enderror">
                        <option value="">Defaut (Europe/Paris)</option>
                        <option value="Europe/Paris" {{ old('timezone', isset($user) ? $user->timezone : '') === 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                        <option value="Europe/London" {{ old('timezone', isset($user) ? $user->timezone : '') === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                        <option value="America/New_York" {{ old('timezone', isset($user) ? $user->timezone : '') === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                    </select>
                    @error('timezone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>