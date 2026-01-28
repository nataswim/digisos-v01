@php
$chiffre1 = mt_rand(55, 188);
$chiffre2 = mt_rand(79, 123);
@endphp


<footer class="guest-footer mt-5" style="border-top: 20px solid #51b7d5;border-left: 20px solid #f8f9f3;border-right: 20px solid #ffffff;border-bottom: 20px solid #378094;">

    <!-- Contenu principal du footer -->
    <div class="py-5">
        <div class="container-lg">
            <div class="row g-4">
                
                <!-- A propos -->
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-star text-primary"></i>
                        </div>
                        <h5 class="mb-0">Digital'SOS</h5>
                    </div>
                    <p class="opacity-75 mb-4">
                        Nous partageons nos connaissances avec la communauté du sport.
                    </p>
                    <div class="opacity-75">
                        <i class="fas fa-shield-alt me-2"></i>
                        <small>Vos données sont protégées. Aucun spam.</small>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3">Navigation</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <i class="fas fa-home me-2"></i>Accueil
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('about') }}" class="text-decoration-none">
                                <i class="fas fa-info-circle me-2"></i>À propos
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('features') }}" class="text-decoration-none">
                                <i class="fas fa-star me-2"></i>Fonctionnalités
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('pricing') }}" class="text-decoration-none">
                                <i class="fas fa-tag me-2"></i>Plans d'inscription
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('guide') }}" class="text-decoration-none">
                                <i class="fas fa-book-open me-2"></i>Guide d'utilisation
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('contact') }}" class="text-decoration-none">
                                <i class="fas fa-envelope me-2"></i>Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Ressources -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3">Ressources</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('posts.public.index') }}" class="text-decoration-none">
                                <i class="fas fa-newspaper me-2"></i>Articles & Dossiers
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('public.fiches.index') }}" class="text-decoration-none">
                                <i class="fas fa-file-alt me-2"></i>Fiches thématiques
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('public.videos.index') }}" class="text-decoration-none">
                                <i class="fas fa-video me-2"></i>Vidéos
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('ebook.index') }}" class="text-decoration-none">
                                <i class="fas fa-download me-2"></i>Documents à télécharger
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Informations légales -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3">Informations</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('legal') }}" class="text-decoration-none">
                                <i class="fas fa-gavel me-2"></i>Mentions légales
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('privacy') }}" class="text-decoration-none">
                                <i class="fas fa-shield-alt me-2"></i>Politique de confidentialité
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('cookies') }}" class="text-decoration-none">
                                <i class="fas fa-cookie-bite me-2"></i>Politique de cookies
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('accessibility') }}" class="text-decoration-none">
                                <i class="fas fa-universal-access me-2"></i>Accessibilité
                            </a>
                        </li>
                    </ul>

                    <!-- Réseaux sociaux -->
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-3">Suivez-nous</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/Sports.Ressources/" class="btn btn-light btn-lg" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/med_hassan_el_haouat/" class="btn btn-light btn-lg" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/@stevemarshvedravokivish2069" class="btn btn-light btn-lg" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de copyright -->
<div class="shadow-lg" style="background-color: #ffffff !important;margin: 20px 10px;border-radius: 5px;padding: 10px 5px;">
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
                            class="text-decoration-none hover-opacity-100 fw-semibold">
                            MyCreaNet Agency
                        </a>
                    </p>
                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="d-flex flex-wrap justify-content-md-end gap-3">
                        <a href="{{ route('privacy') }}" class="text-decoration-none">
                            Politique de confidentialité
                        </a>
                        <a href="{{ route('cookies') }}" class="text-decoration-none">
                            Cookies
                        </a>
                        <a href="{{ route('legal') }}" class="text-decoration-none">
                            Mentions légales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>