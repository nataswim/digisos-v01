@php
$chiffre1 = mt_rand(55, 188);
$chiffre2 = mt_rand(79, 123);
@endphp


<footer class="guest-footer mt-5" style="border-top: 40px solid #f0a833;border-left: 20px solid #1c2010;border-right: 20px solid #e40066;border-bottom: 60px solid #f6f6f6;background-image: linear-gradient(129deg, #f6f6f6 85%, #1c2111 0);background-attachment: fixed;background-position: top;border-radius: 40px;">

    <!-- Contenu principal du footer -->
    <div class="py-5">
        <div class="container-lg">
            <div class="row g-4">
                
                <!-- A propos -->
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center mb-4">
                    
                        
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/logo/Logo-CNBB-Natation-9.png') }}"
                             alt="Club de natation Bressuire"
                             class="hero-logo img-fluid" style="max-width: 200px;">
                    </a>
                
                    </div>
                    <p class="opacity-75 mb-4">
                        Digitalisez la gouvernance de votre structure sportive.
                    </p>
                    <div class="opacity-75">
                        <i class="fas fa-shield-alt me-2"></i>
                        <small>Système de pilotage des équipements sportifs</small>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3" style="border-bottom: 2px solid #2f80b8;background-image: linear-gradient(141deg, #ffffff 85%, #2f80b8 0);padding: 10px 5px;color: #2e7072;">Navigation</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-home me-2 text-secondary"></i>Accueil
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('about') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-info-circle me-2 text-secondary"></i>À propos
                            </a>
                        </li>

                        {{-- Galeries Photo --}}
<li class="mb-2">
    <a class="text-decoration-none text-dark" href="{{ route('galleries.index') }}">
        <i class="fas fa-images me-2 text-secondary"></i> Galeries
    </a>
</li>
                        <li class="mb-2">
                            <a href="{{ route('guide') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-book-open me-2 text-secondary"></i>Utilisation
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('contact') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-envelope me-2 text-secondary"></i>Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Ressources -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3" style="border-bottom: 2px solid #2f80b8;background-image: linear-gradient(141deg, #ffffff 85%, #2f80b8 0);padding: 10px 5px;color: #2e7072;">Ressources</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('posts.public.index') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-newspaper me-2 text-secondary"></i>Articles & Dossiers
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('public.fiches.index') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-file-alt me-2 text-secondary"></i>Fiches thématiques
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('public.videos.index') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-video me-2 text-secondary"></i>Vidéos
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('ebook.index') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-download me-2 text-secondary"></i>Documents à télécharger
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Informations légales -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3" style="border-bottom: 2px solid #2f80b8;background-image: linear-gradient(141deg, #ffffff 85%, #2f80b8 0);padding: 10px 5px;color: #2e7072;">Informations</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('legal') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-gavel me-2 text-secondary"></i>Mentions légales
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('privacy') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-shield-alt me-2 text-secondary"></i>Politique de confidentialité
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('cookies') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-cookie-bite me-2 text-secondary"></i>Politique de cookies
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('accessibility') }}" class="text-decoration-none text-dark">
                                <i class="fas fa-universal-access me-2 text-secondary"></i>Accessibilité
                            </a>
                        </li>
                    </ul>

                    <!-- Réseaux sociaux -->
                    <div class="mt-4">
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/Sports.Ressources/" class="btn bg-primary btn-lg text-white" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/med_hassan_el_haouat/" class="btn bg-primary btn-lg text-white" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/@stevemarshvedravokivish2069" class="btn bg-primary btn-lg text-white" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de copyright -->
<div style="background-color: #E50066 !important;padding: 20px 5px;color: #ffffff;background: linear-gradient(202deg, #E50066 85%, #1c2111 0);background-attachment: fixed;">
            <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 opacity-75">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                    </p>
                    <p class="mb-0 opacity-75 mt-2">
                        Conception et développement
                        <a href="https://mycreanet.fr/realisations-projets/"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-dark hover-opacity-100 fw-bold">
                            MyCreaNet Agency
                        </a>
                    </p>
                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="d-flex flex-wrap justify-content-md-end gap-3">
                        <a href="{{ route('privacy') }}" class="text-white">
                            Politique de confidentialité
                        </a>
                        <a href="{{ route('cookies') }}" class="text-white">
                            Cookies
                        </a>
                        <a href="{{ route('legal') }}" class="text-white">
                            Mentions légales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>