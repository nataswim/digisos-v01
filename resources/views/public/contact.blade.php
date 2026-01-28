@extends('layouts.public')

@section('title', 'Contactez-nous')

@section('content')

<!-- Hero Section avec Video Background -->
<section class="position-relative text-white py-5 overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 1;">
        <source src="{{ asset('assets/images/team/nataswim.mp4') }}" type="video/mp4">
    </video>
    
    <!-- Overlay sombre pour meilleure lisibilité -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" style="z-index: 2;"></div>
    
    <!-- Contenu -->
    <div class="container-lg position-relative" style="z-index: 3;">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Contactez-nous</h1>
                <p class="lead mb-0">
                    N'hésitez pas à nous envoyer vos messages. Nous vous répondrons dans les plus brefs délais.
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3" 
                     style="width: 200px; height: 200px;">
                    <img 
                        src="{{ asset('assets/images/team/nataswim_app_logo_0.png') }}" 
                        alt="Nataswim Logo" 
                        class="img-fluid"
                        style="max-width: 160px; max-height: 160px;"
                    >
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formulaire de contact -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <h2 class="h3 mb-4 text-center">Envoyez-nous un message</h2>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
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
                                        rows="6"
                                        required
                                    >{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimum 20 caractères</div>
                                </div>

                                {{-- Bouton d'envoi --}}
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto">
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

<!-- Section Localisation -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">                
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <!-- Carte Google Maps -->
                        <div class="ratio ratio-16x9">
                            <iframe 
                                title="Localisation DIGITALSOS - 46 Rte de la Pyramide, 75012 Paris" 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.8583384719945!2d2.404621!3d48.843611!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6729d8cf8e8c9%3A0x1234567890abcdef!2s46%20Route%20de%20la%20Pyramide%2C%2075012%20Paris!5e0!3m2!1sfr!2sfr!4v1733049600000!5m2!1sfr!2sfr" 
                                style="border: 0;" 
                                allowfullscreen 
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection