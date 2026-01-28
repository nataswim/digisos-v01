@extends('layouts.public')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 30px; margin-bottom: 30px;">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        

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

                            <!-- SECTION 3 : Profil utilisateur -->
                            <div class="mb-5">
                                <h5 class="fw-bold mb-3 pb-2 border-bottom text-primary">
                                    <i class="fas fa-address-card me-2"></i>Votre profil
                                </h5>

                                <!-- Profil utilisateur (Choix multiples) -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Sélectionnez votre profil <span class="text-danger">*</span>
                                    </label>
                                    <small class="text-muted d-block mb-3">
                                        Sélectionnez une ou plusieurs options qui vous correspondent
                                    </small>

                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="row g-2">
                                                <!-- Option 1 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Entraîneur / Coach (sportif, personnel, mental, etc.)"
                                                            id="bio1" {{ in_array('Entraîneur / Coach (sportif, personnel, mental, etc.)', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio1">
                                                            <i class="fas fa-dumbbell text-primary me-2"></i>
                                                            Je suis Entraîneur / Coach (sportif, personnel, mental, etc.)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 2 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Enseignant / Professeur (EPS, universitaire, scolaire)"
                                                            id="bio2" {{ in_array('Enseignant / Professeur (EPS, universitaire, scolaire)', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio2">
                                                            <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                                                            Je suis Enseignant / Professeur (EPS, universitaire, scolaire)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 3 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Dirigeant / Manager (club, association, fédération, structure privée)"
                                                            id="bio3" {{ in_array('Dirigeant / Manager (club, association, fédération, structure privée)', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio3">
                                                            <i class="fas fa-users-cog text-info me-2"></i>
                                                            Je suis Dirigeant / Manager (club, association, fédération, structure privée)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 4 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Sportif / Athlète (professionnel ou amateur de haut niveau)"
                                                            id="bio4" {{ in_array('Sportif / Athlète (professionnel ou amateur de haut niveau)', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio4">
                                                            <i class="fas fa-medal text-warning me-2"></i>
                                                            Je suis Sportif / Athlète (professionnel ou amateur de haut niveau)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 5 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Pratiquant régulier / Amateur"
                                                            id="bio5" {{ in_array('Pratiquant régulier / Amateur', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio5">
                                                            <i class="fas fa-running text-secondary me-2"></i>
                                                            Je suis Pratiquant régulier / Amateur
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 6 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Étudiant / Stagiaire (formation initiale ou continue)"
                                                            id="bio6" {{ in_array('Étudiant / Stagiaire (formation initiale ou continue)', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio6">
                                                            <i class="fas fa-graduation-cap text-primary me-2"></i>
                                                            Je suis Étudiant / Stagiaire (formation initiale ou continue)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 7 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Professionnel en formation continue"
                                                            id="bio7" {{ in_array('Professionnel en formation continue', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio7">
                                                            <i class="fas fa-user-graduate text-success me-2"></i>
                                                            Je suis Professionnel en formation continue
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 8 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Chercheur / Consultant / Expert (dans le domaine du sport ou de la performance)"
                                                            id="bio8" {{ in_array('Chercheur / Consultant / Expert (dans le domaine du sport ou de la performance)', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio8">
                                                            <i class="fas fa-microscope text-info me-2"></i>
                                                            Je suis Chercheur / Consultant / Expert (dans le domaine du sport ou de la performance)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 9 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="À la recherche de ressources (veille, connaissances, outils professionnels)"
                                                            id="bio9" {{ in_array('À la recherche de ressources (veille, connaissances, outils professionnels)', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio9">
                                                            <i class="fas fa-search text-warning me-2"></i>
                                                            Je suis à la recherche de ressources (veille, connaissances, outils professionnels)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 10 -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input bio-checkbox" type="checkbox"
                                                            name="bio_options[]" value="Professionnel de santé (kinésithérapeute, préparateur physique, médecin du sport, nutritionniste)"
                                                            id="bio10" {{ in_array('Professionnel de santé (kinésithérapeute, préparateur physique, médecin du sport, nutritionniste)', old('bio_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio10">
                                                            <i class="fas fa-heartbeat text-danger me-2"></i>
                                                            Je suis Professionnel de santé (kinésithérapeute, préparateur physique, médecin du sport, nutritionniste)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Option 11 : Autre -->
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="bio_other_checkbox" value="1"
                                                            id="bio_other_checkbox"
                                                            {{ old('bio_other_text') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bio_other_checkbox">
                                                            <i class="fas fa-edit text-muted me-2"></i>
                                                            Autre (à préciser)
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Champ texte "Autre" -->
                                                <div class="col-12" id="bio_other_container" style="display: none;">
                                                    <input type="text" name="bio_other_text" id="bio_other_text"
                                                        class="form-control mt-2"
                                                        placeholder="Précisez votre profil..."
                                                        maxlength="100"
                                                        value="{{ old('bio_other_text') }}">
                                                    <small class="text-muted">Maximum 100 caractères</small>
                                                </div>
                                            </div>

                                            @error('bio_options')
                                            <div class="text-danger mt-2 small">{{ $message }}</div>
                                            @enderror

                                            @error('bio_other_text')
                                            <div class="text-danger mt-2 small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Vous devez sélectionner au moins une option
                                    </small>
                                </div>

                                <!-- Champ caché pour stocker la bio finale -->
                                <input type="hidden" name="bio" id="bio_hidden">
                            </div>

                            <!-- Conditions d'utilisation -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="terms" id="terms"
                                        class="form-check-input" required>
                                    <label for="terms" class="form-check-label">
                                        J'accepte les <a href="{{ route('privacy') }}" target="_blank">conditions d'utilisation</a>
                                        et la <a href="{{ route('privacy') }}" target="_blank">politique de confidentialité</a>
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Bouton d'inscription -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-success btn-lg">
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






<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du champ "Autre" pour la bio
        const bioOtherCheckbox = document.getElementById('bio_other_checkbox');
        const bioOtherContainer = document.getElementById('bio_other_container');
        const bioOtherText = document.getElementById('bio_other_text');

        if (bioOtherCheckbox) {
            bioOtherCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    bioOtherContainer.style.display = 'block';
                    bioOtherText.setAttribute('required', 'required');
                } else {
                    bioOtherContainer.style.display = 'none';
                    bioOtherText.removeAttribute('required');
                    bioOtherText.value = '';
                }
            });

            // Initialiser l'affichage au chargement
            if (bioOtherCheckbox.checked) {
                bioOtherContainer.style.display = 'block';
                bioOtherText.setAttribute('required', 'required');
            }
        }

        // Avant la soumission du formulaire, construire la bio finale
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const selectedOptions = [];

            // Récupérer toutes les cases cochées
            document.querySelectorAll('.bio-checkbox:checked').forEach(checkbox => {
                selectedOptions.push(checkbox.value);
            });

            // Ajouter "Autre" si rempli
            if (bioOtherCheckbox.checked && bioOtherText.value.trim()) {
                selectedOptions.push('Autre : ' + bioOtherText.value.trim());
            }

            // Construire la chaîne finale et l'injecter dans le champ caché
            const bioFinal = selectedOptions.join(' | ');
            document.getElementById('bio_hidden').value = bioFinal;

            // Validation finale
            if (!bioFinal) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins une option de profil.');
                return false;
            }
        });
    });
</script>
@endsection