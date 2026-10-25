@extends('layouts.public')

@section('title', 'Créer mon compte')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 30px; margin-bottom: 30px;">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/logo/cnbb-logo-800.png') }}" alt="" style="max-width: 160px" class="d-block mx-auto mb-3">
                            <h1 class="h3 fw-bold mb-1">Créer mon compte</h1>
                            <p class="text-muted mb-0">Cercle des Nageurs du Bocage Bressuirais</p>
                        </div>

                        <div class="register-notice mb-4" role="note">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            <div>
                                <p class="fw-bold mb-1">L'inscription et l'utilisation des outils du site sont réservées aux adhérents du club.</p>
                                <p class="mb-1">
                                    Après la création de votre compte, un administrateur vérifie votre adhésion avant de vous donner accès
                                    à l'espace adhérent (séances, convocations, assiduité, documents…).
                                </p>
                                <p class="mb-0 small">
                                    Pour un nageur mineur, le compte est créé par un parent ; l'entraîneur ou le secrétariat y rattache ensuite l'enfant.
                                    Pas encore adhérent ? Consultez <a href="{{ route('pricing') }}">les inscriptions au club</a> ou <a href="{{ route('contact') }}">contactez-nous</a>.
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf

                            <!-- SECTION 1 : Informations de compte -->
                            <div class="mb-5">
                                <h5 class="fw-bold mb-3 pb-2 border-bottom text-primary">
                                    <i class="fas fa-user-circle me-2"></i>Informations de compte
                                </h5>

                                <!-- Nom complet -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Nom complet <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name') }}"
                                            class="form-control border-start-0 @error('name') is-invalid @enderror"
                                            placeholder="Jean Dupont" required autofocus>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        Adresse email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input type="email" name="email" id="email"
                                            value="{{ old('email') }}"
                                            class="form-control border-start-0 @error('email') is-invalid @enderror"
                                            placeholder="jean@example.com" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Mot de passe -->
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold">
                                            Mot de passe <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="password" id="password"
                                                class="form-control border-start-0 @error('password') is-invalid @enderror"
                                                placeholder="••••••••" required>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold">
                                            Confirmer le mot de passe <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="password_confirmation"
                                                id="password_confirmation"
                                                class="form-control border-start-0"
                                                placeholder="••••••••" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2 : Informations personnelles -->
                            <div class="mb-5">
                                <h5 class="fw-bold mb-3 pb-2 border-bottom text-primary">
                                    <i class="fas fa-id-card me-2"></i>Informations personnelles
                                </h5>

                                <!-- Nom d'utilisateur -->
                                <div class="mb-3">
                                    <label for="username" class="form-label fw-semibold">
                                        Nom d'utilisateur <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-at text-muted"></i>
                                        </span>
                                        <input type="text" name="username" id="username"
                                            value="{{ old('username') }}"
                                            class="form-control border-start-0 @error('username') is-invalid @enderror"
                                            placeholder="jean_dupont" required>
                                        @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Sans espaces ni caractères spéciaux (tirets et underscores autorisés)</small>
                                </div>

                                <!-- Prénom et Nom -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label fw-semibold">
                                            Prénom <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-user text-muted"></i>
                                            </span>
                                            <input type="text" name="first_name" id="first_name"
                                                value="{{ old('first_name') }}"
                                                class="form-control border-start-0 @error('first_name') is-invalid @enderror"
                                                placeholder="Jean" required>
                                            @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label fw-semibold">
                                            Nom <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-user text-muted"></i>
                                            </span>
                                            <input type="text" name="last_name" id="last_name"
                                                value="{{ old('last_name') }}"
                                                class="form-control border-start-0 @error('last_name') is-invalid @enderror"
                                                placeholder="Dupont" required>
                                            @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Date de naissance -->
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label fw-semibold">
                                        Date de naissance <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-calendar text-muted"></i>
                                        </span>
                                        <input type="date" name="date_of_birth" id="date_of_birth"
                                            value="{{ old('date_of_birth') }}"
                                            class="form-control border-start-0 @error('date_of_birth') is-invalid @enderror"
                                            required>
                                        @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Vous devez avoir au moins 17 ans</small>
                                </div>

                                <!-- Téléphone -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-semibold">
                                        Numéro de téléphone <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-phone text-muted"></i>
                                        </span>
                                        <input type="tel" name="phone" id="phone"
                                            value="{{ old('phone') }}"
                                            class="form-control border-start-0 @error('phone') is-invalid @enderror"
                                            placeholder="0612345678 ou +33612345678" required>
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Format français ou international</small>
                                </div>
                            </div>

                            <!-- SECTION 3 : Lien avec le club -->
                            <fieldset class="mb-5">
                                <legend class="h5 fw-bold mb-3 pb-2 border-bottom text-primary w-100">
                                    <i class="fas fa-address-card me-2" aria-hidden="true"></i>Votre lien avec le club
                                </legend>
                                <p class="fw-semibold mb-1">Vous êtes… <span class="text-danger" aria-hidden="true">*</span></p>
                                <p class="text-muted small mb-3" id="profils-aide">Cochez une ou plusieurs cases.</p>

                                <div class="row g-2" aria-describedby="profils-aide">
                                    @foreach (\App\Http\Requests\Auth\RegisterRequest::PROFILES as $key => [$label, $detail, $icon])
                                        <div class="col-sm-6">
                                            <input class="btn-check" type="checkbox" name="profiles[]" value="{{ $key }}" id="profil-{{ $key }}" autocomplete="off"
                                                   @checked(in_array($key, old('profiles', []), true))>
                                            <label class="register-profile w-100" for="profil-{{ $key }}">
                                                <i class="fas {{ $icon }}" aria-hidden="true"></i>
                                                <span>
                                                    <span class="fw-bold d-block">{{ $label }}</span>
                                                    <span class="small text-muted">{{ $detail }}</span>
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('profiles')
                                    <div class="text-danger mt-2 small" role="alert">{{ $message }}</div>
                                @enderror
                            </fieldset>

                            <!-- Conditions d'utilisation -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="terms" id="terms"
                                        class="form-check-input" required>
                                    <label for="terms" class="form-check-label">
                                        Je certifie être adhérent du club (ou parent d'un adhérent) et j'accepte les
                                        <a href="{{ route('legal') }}" target="_blank" rel="noopener">conditions d'utilisation</a>
                                        et la <a href="{{ route('privacy') }}" target="_blank" rel="noopener">politique de confidentialité</a>
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Bouton d'inscription -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-success btn-lg text-white">
                                    <i class="fas fa-user-plus me-2"></i>Créer mon compte
                                </button>
                            </div>

                            <!-- Lien de connexion -->
                            <div class="text-center">
                                <span class="text-muted">Déjà membre ?</span>
                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                                    Se connecter
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .register-notice { display: flex; gap: 1rem; padding: 1.1rem 1.25rem; border-radius: 14px; background: #F0F9FF; border-left: 5px solid #0090D4; color: #1d293d; }
    .register-notice > i { font-size: 1.4rem; color: #0090D4; margin-top: .15rem; }
    .register-profile { display: flex; gap: .85rem; align-items: center; padding: .85rem 1rem; border: 2px solid #e2e8f0; border-radius: 12px; background: #fff; cursor: pointer; transition: border-color .15s, background .15s; }
    .register-profile > i { width: 2.4rem; height: 2.4rem; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: #E0F2FE; color: #0090D4; }
    .register-profile:hover { border-color: #90a1b9; }
    .btn-check:checked + .register-profile { border-color: #0090D4; background: #F0F9FF; }
    .btn-check:checked + .register-profile > i { background: #0090D4; color: #fff; }
    .btn-check:focus-visible + .register-profile { outline: 3px solid #0090D4; outline-offset: 2px; }
</style>
@endpush
