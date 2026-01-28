@extends('layouts.public')

@section('title', 'Contactez-nous')

@section('content')

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Contactez-nous</h1>
                <p class="lead mb-0">
                    N'hésitez pas à nous envoyer vos messages. Nous vous répondrons dans les plus brefs délais.
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                     style="width: 200px; height: 200px;">
                    <i class="fas fa-envelope" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenu Principal -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row g-5">
            <!-- Informations de contact -->
            <div class="col-lg-4">
                <h2 class="h3 mb-4">Coordonnées</h2>

                <!-- Siège -->
                <article class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="h5 mb-1">Siège</h3>
                                <p class="text-muted mb-0">45 Avenue Albert Camus<br>75200 Paris, France</p>
                            </div>
                        </div>

                        

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="h5 mb-1">Email</h3>
                                <p class="text-muted mb-0">natation.swimming@gmail.com</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="flex-shrink-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="h5 mb-1">Horaires</h3>
                                <p class="text-muted mb-0">
                                    Lundi - Samedi : 9h00 - 18h00<br>
                                    Dimanche : Fermé
                                </p>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Carte Google Maps -->
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="ratio ratio-4x3">
                        <iframe 
                            title="Localisation DIGITALSOS" 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1176.5239436813358!2d-0.25712794780144704!3d46.63821066641348!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48076dc9d435764f%3A0xa7eaa3369cacbf3e!2sMycreanet%20DIGITAL%20SERVICES%20-%20AGENCE%20WEB%20HUMAINE%20ET%20RESPONSABLE%20-%20CONSULTANT%20E%20COMMERCE!5e0!3m2!1sfr!2sfr!4v1741162976443!5m2!1sfr!2sfr" 
                            style="border: 0;" 
                            allowfullscreen 
                            loading="lazy"
                        ></iframe>
                    </div>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="col-lg-8">
                <h2 class="h3 mb-4">Envoyez-nous un message</h2>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        {{-- Message de succès --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                            </div>
                        @endif

                        {{-- Message d'erreur global --}}
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Erreur :</strong> Veuillez corriger les erreurs ci-dessous.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                {{-- Prénom --}}
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">
                                        Prénom <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('first_name') is-invalid @enderror" 
                                        id="first_name" 
                                        name="first_name" 
                                        value="{{ old('first_name') }}"
                                        required
                                    >
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nom --}}
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">
                                        Nom <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('last_name') is-invalid @enderror" 
                                        id="last_name" 
                                        name="last_name" 
                                        value="{{ old('last_name') }}"
                                        required
                                    >
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}"
                                        required
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Téléphone --}}
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone (optionnel)</label>
                                    <input 
                                        type="tel" 
                                        class="form-control @error('phone') is-invalid @enderror" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone') }}"
                                    >
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Sujet --}}
                                <div class="col-12">
                                    <label for="subject" class="form-label">
                                        Sujet <span class="text-danger">*</span>
                                    </label>
                                    <select 
                                        class="form-select @error('subject') is-invalid @enderror" 
                                        id="subject" 
                                        name="subject"
                                        required
                                    >
                                        <option value="">Choisissez un sujet</option>
                                        <option value="information" {{ old('subject') == 'information' ? 'selected' : '' }}>
                                            Demande d'information
                                        </option>
                                        <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>
                                            Support technique
                                        </option>
                                        <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>
                                            Partenariat
                                        </option>
                                        <option value="billing" {{ old('subject') == 'billing' ? 'selected' : '' }}>
                                            Facturation
                                        </option>
                                        <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>
                                            Autre
                                        </option>
                                    </select>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Message --}}
                                <div class="col-12">
                                    <label for="message" class="form-label">
                                        Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea 
                                        class="form-control @error('message') is-invalid @enderror" 
                                        id="message" 
                                        name="message" 
                                        rows="5"
                                        required
                                    >{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimum 20 caractères</div>
                                </div>

                                {{-- Bouton d'envoi --}}
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Envoyer le message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection