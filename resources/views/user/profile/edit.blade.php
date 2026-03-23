@extends('layouts.user')

@section('title', 'Modifier mon profil')

@section('content')
<div class="container-lg py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">

                {{-- En-tête --}}
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width:50px;height:50px;">
                            <i class="fas fa-user-edit text-primary"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">Modifier mon profil</h3>
                            <small class="text-muted">Gérez vos informations personnelles</small>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="card-body p-4">
                        <div class="row g-4">

                            {{-- Informations personnelles --}}
                            <div class="col-12">
                                <h5 class="fw-semibold mb-3 pb-2 border-bottom">
                                    <i class="fas fa-id-card text-primary me-2"></i>
                                    Informations personnelles
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-semibold">Prénom</label>
                                <input type="text"
                                       name="first_name"
                                       id="first_name"
                                       value="{{ old('first_name', auth()->user()->first_name) }}"
                                       class="form-control @error('first_name') is-invalid @enderror">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-semibold">Nom</label>
                                <input type="text"
                                       name="last_name"
                                       id="last_name"
                                       value="{{ old('last_name', auth()->user()->last_name) }}"
                                       class="form-control @error('last_name') is-invalid @enderror">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    Nom d'affichage <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">Nom d'utilisateur</label>
                                <input type="text"
                                       name="username"
                                       id="username"
                                       value="{{ old('username', auth()->user()->username) }}"
                                       class="form-control @error('username') is-invalid @enderror">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label fw-semibold">Date de naissance</label>
                                <input type="date"
                                       name="date_of_birth"
                                       id="date_of_birth"
                                       value="{{ old('date_of_birth', auth()->user()->date_of_birth?->format('Y-m-d')) }}"
                                       class="form-control @error('date_of_birth') is-invalid @enderror">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Téléphone</label>
                                <input type="tel"
                                       name="phone"
                                       id="phone"
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="bio" class="form-label fw-semibold">Biographie</label>
                                <textarea name="bio"
                                          id="bio"
                                          rows="4"
                                          class="form-control @error('bio') is-invalid @enderror"
                                          placeholder="Parlez-nous de vous...">{{ old('bio', auth()->user()->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Compte et sécurité --}}
                            <div class="col-12 mt-2">
                                <h5 class="fw-semibold mb-3 pb-2 border-bottom">
                                    <i class="fas fa-shield-alt text-success me-2"></i>
                                    Compte et sécurité
                                </h5>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Nouveau mot de passe</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror">
                                <div class="form-text">Laisser vide pour conserver le mot de passe actuel.</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Confirmer le mot de passe
                                </label>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control">
                            </div>

                            {{-- Préférences --}}
                            <div class="col-12 mt-2">
                                <h5 class="fw-semibold mb-3 pb-2 border-bottom">
                                    <i class="fas fa-cog text-info me-2"></i>
                                    Préférences
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <label for="locale" class="form-label fw-semibold">Langue</label>
                                <select name="locale" id="locale" class="form-select">
                                    <option value="fr" {{ old('locale', auth()->user()->locale) === 'fr' ? 'selected' : '' }}>
                                        🇫🇷 Français
                                    </option>
                                    <option value="en" {{ old('locale', auth()->user()->locale) === 'en' ? 'selected' : '' }}>
                                        🇺🇸 English
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="timezone" class="form-label fw-semibold">Fuseau horaire</label>
                                <select name="timezone" id="timezone" class="form-select">
                                    <option value="Europe/Paris" {{ old('timezone', auth()->user()->timezone) === 'Europe/Paris' ? 'selected' : '' }}>
                                        Paris (GMT+1)
                                    </option>
                                    <option value="Europe/London" {{ old('timezone', auth()->user()->timezone) === 'Europe/London' ? 'selected' : '' }}>
                                        London (GMT)
                                    </option>
                                    <option value="America/New_York" {{ old('timezone', auth()->user()->timezone) === 'America/New_York' ? 'selected' : '' }}>
                                        New York (GMT-5)
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="avatar" class="form-label fw-semibold">Avatar (URL)</label>
                                <input type="text"
                                       name="avatar"
                                       id="avatar"
                                       value="{{ old('avatar', auth()->user()->avatar) }}"
                                       class="form-control @error('avatar') is-invalid @enderror"
                                       placeholder="https://example.com/avatar.jpg">
                                <div class="form-text">URL de votre photo de profil.</div>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Footer formulaire --}}
                    <div class="card-footer bg-light p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="fw-semibold mb-3">Informations du compte</h6>
                                <div class="small text-muted">
                                    <div class="mb-1">
                                        <strong>Rôle :</strong>
                                        {{ auth()->user()->role?->display_name ?? auth()->user()->role?->name ?? 'Utilisateur' }}
                                    </div>
                                    <div class="mb-1">
                                        <strong>Membre depuis :</strong>
                                        {{ auth()->user()->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="mb-1">
                                        <strong>Statut :</strong>
                                        <span class="badge bg-{{ auth()->user()->status === 'active' ? 'success' : 'danger' }}-subtle text-{{ auth()->user()->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst(auth()->user()->status) }}
                                        </span>
                                    </div>
                                    @if(auth()->user()->last_login_at)
                                        <div>
                                            <strong>Dernière connexion :</strong>
                                            {{ auth()->user()->last_login_at->format('d/m/Y à H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
