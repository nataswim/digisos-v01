@extends('layouts.public')

@section('title', 'Contacter le club')
@section('meta_description', 'Contactez le Cercle des Nageurs du Bocage Bressuirais (CNBB) : formulaire, e-mail, téléphone et adresse du club de natation de Bressuire.')

@section('content')

@php
    // Sujets du formulaire : liste unique, définie dans PublicController (valeur technique => libellé)
    $sujets = $sujets ?? \App\Http\Controllers\PublicController::CONTACT_SUBJECTS;
@endphp


<x-public.hero
    title="Contactez-nous"
    eyebrow="Le club"
    title-class="display-4"
    lead="Une question sur le club, une inscription, un entraînement ou le site ? Écrivez-nous : le club vous répondra dès que possible.">
    <a href="#formulaire" class="btn btn-primary btn-lg text-white">
        <i class="fas fa-paper-plane me-2" aria-hidden="true"></i>Écrire un message
    </a>
    <a href="tel:+33602350843" class="btn btn-light btn-lg">
        <i class="fas fa-phone me-2" aria-hidden="true"></i>06 02 35 08 43
    </a>

    <x-slot:aside>
        <div class="hero-logo-badge bg-white text-dark rounded-circle d-inline-flex align-items-center justify-content-center p-3 shadow"
             style="width: 200px; height: 200px;">
            <img src="{{ asset('assets/images/logo/Logo-CNBB-Natation-9.png') }}"
                 alt="Logo du Cercle des Nageurs du Bocage Bressuirais"
                 class="img-fluid"
                 style="max-width: 160px; max-height: 160px;">
        </div>
    </x-slot:aside>
</x-public.hero>


<!-- Coordonnées -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-envelope text-primary fa-2x mb-3" aria-hidden="true"></i>
                        <h2 class="h5 fw-bold mb-2">E-mail</h2>
                        <p class="mb-0">
                            <a href="mailto:cnbb079@gmail.com" class="text-decoration-none">cnbb079@gmail.com</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-phone text-success fa-2x mb-3" aria-hidden="true"></i>
                        <h2 class="h5 fw-bold mb-2">Téléphone</h2>
                        <p class="mb-0">
                            <a href="tel:+33602350843" class="text-decoration-none">06 02 35 08 43</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-map-marker-alt text-info fa-2x mb-3" aria-hidden="true"></i>
                        <h2 class="h5 fw-bold mb-2">Adresse</h2>
                        <address class="text-muted mb-0">
                            40 boulevard de la République<br>
                            79300 Bressuire
                        </address>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-inbox text-warning fa-2x mb-3" aria-hidden="true"></i>
                        <h2 class="h5 fw-bold mb-2">Dossier d'inscription</h2>
                        <p class="text-muted mb-0">
                            À déposer dans la boîte aux lettres du club, derrière l'abribus en bas de Cœur d'O.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Formulaire de contact -->
<section id="formulaire" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <h2 class="h3 fw-bold mb-2 text-center">Envoyez-nous un message</h2>
                <p class="text-muted text-center mb-4">
                    Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires.
                </p>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        {{-- Résultat de l'envoi (clés propres au formulaire, pour ne pas doubler les alertes du gabarit) --}}
                        @if (session('contact_success'))
                            <div class="alert alert-success" role="status">
                                <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                                {{ session('contact_success') }}
                            </div>
                        @endif

                        @if (session('contact_error'))
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                                {{ session('contact_error') }}
                            </div>
                        @endif

                        {{-- Message d'erreur global --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                                <strong>Le message n'a pas été envoyé.</strong> Merci de corriger les champs signalés ci-dessous.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf

                            {{-- Piège à robots : champ invisible, à laisser vide --}}
                            <div class="visually-hidden" aria-hidden="true">
                                <label for="website">Ne pas remplir ce champ</label>
                                <input type="text" id="website" name="website" value="" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="row g-3">
                                {{-- Prénom --}}
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">
                                        Prénom <span class="text-danger" aria-hidden="true">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name"
                                           name="first_name"
                                           value="{{ old('first_name') }}"
                                           autocomplete="given-name"
                                           required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nom --}}
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">
                                        Nom <span class="text-danger" aria-hidden="true">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name"
                                           name="last_name"
                                           value="{{ old('last_name') }}"
                                           autocomplete="family-name"
                                           required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- E-mail --}}
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        E-mail <span class="text-danger" aria-hidden="true">*</span>
                                    </label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           autocomplete="email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Téléphone --}}
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone (facultatif)</label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           autocomplete="tel">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Sujet --}}
                                <div class="col-12">
                                    <label for="subject" class="form-label">
                                        Votre demande concerne <span class="text-danger" aria-hidden="true">*</span>
                                    </label>
                                    <select class="form-select @error('subject') is-invalid @enderror"
                                            id="subject"
                                            name="subject"
                                            required>
                                        <option value="">Choisissez un sujet</option>
                                        @foreach ($sujets as $valeur => $libelle)
                                            <option value="{{ $valeur }}" @selected(old('subject') === $valeur)>{{ $libelle }}</option>
                                        @endforeach
                                    </select>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Message --}}
                                <div class="col-12">
                                    <label for="message" class="form-label">
                                        Message <span class="text-danger" aria-hidden="true">*</span>
                                    </label>
                                    <textarea class="form-control @error('message') is-invalid @enderror"
                                              id="message"
                                              name="message"
                                              rows="6"
                                              minlength="20"
                                              maxlength="5000"
                                              aria-describedby="message-aide"
                                              required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="message-aide" class="form-text">
                                        20 caractères minimum. Pour une question sur un nageur, précisez son nom et son groupe.
                                    </div>
                                </div>

                                {{-- Bouton d'envoi --}}
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg text-white">
                                        <i class="fas fa-paper-plane me-2" aria-hidden="true"></i>
                                        Envoyer le message
                                    </button>
                                </div>

                                <div class="col-12">
                                    <p class="text-muted small mb-0">
                                        Les informations saisies servent uniquement à répondre à votre demande.
                                        En savoir plus : <a href="{{ route('privacy') }}">politique de confidentialité</a>.
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Plan d'accès -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="h3 fw-bold mb-4 text-center">Nous trouver</h2>
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="ratio ratio-16x9">
                        <iframe
                            title="Plan d'accès du CNBB — 40 boulevard de la République, 79300 Bressuire"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2402.494965143164!2d-0.49433602408381405!3d46.834599671129475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4807a8106e13d59b%3A0x10765af134435179!2s40%20Bd%20de%20la%20R%C3%A9publique%2C%2079300%20Bressuire!5e1!3m2!1sfr!2sfr!4v1791235462121!5m2!1sfr!2sfr"
                            style="border: 0;"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <p class="text-muted small text-center mt-3 mb-0">
                    Carte fournie par Google Maps.
                    <a href="https://www.google.com/maps/search/?api=1&amp;query=40+boulevard+de+la+R%C3%A9publique+79300+Bressuire"
                       target="_blank" rel="noopener">Ouvrir l'itinéraire dans Google Maps</a>
                    (nouvel onglet).
                </p>
            </div>
        </div>
    </div>
</section>

@endsection


@push('scripts')
{{-- Après un envoi (réussi ou non), ramener le visiteur au niveau du formulaire --}}
@if ($errors->any() || session('contact_success') || session('contact_error'))
<script>
    document.getElementById('formulaire').scrollIntoView();
</script>
@endif
@endpush
