<footer class="mt-5"
        style="border-top:20px solid #ffffff;border-bottom:20px solid #ffffff;background:linear-gradient(129deg, #e9f7fa 85%, #4aabca 0);background-attachment:fixed;background-position:top;">

    <div class="container-fluid py-4">
        <div class="row g-4">

            {{-- Contenus --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3"
                    style="background-color:#4aabca;display:block;padding:10px 5px;color:white;">
                    <i class="fas fa-book-open me-2"></i>Contenus
                </h6>
                <ul class="footer-links list-unstyled">
                    <li>
                        <a href="{{ route('posts.public.index') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Actualités
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public.fiches.index') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Fiches pratiques
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public.pages.index') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Pages
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Ressources --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3"
                    style="background-color:#4aabca;display:block;padding:10px 5px;color:white;">
                    <i class="fas fa-photo-video me-2"></i>Ressources
                </h6>
                <ul class="footer-links list-unstyled">
                    <li>
                        <a href="{{ route('public.videos.index') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Tutoriels vidéo
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ebook.index') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Documents
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('galleries.index') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Galeries photos
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Mon espace --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3"
                    style="background-color:#4aabca;display:block;padding:10px 5px;color:white;">
                    <i class="fas fa-user me-2"></i>Mon espace
                </h6>
                <ul class="footer-links list-unstyled">
                    <li>
                        <a href="{{ route('user.dashboard') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Tableau de bord
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.user-profile.show') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Ma fiche
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.profile.edit') }}" class="footer-link">
                            <i class="fas fa-angle-right me-2"></i>Mon profil
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Barre basse --}}
        <div class="row mt-4 pt-4 border-top"
             style="background-color:#5fcac6 !important;padding-bottom:20px;color:#000000;background:linear-gradient(131deg, #4babca 85%, #e9f7fa 0);background-attachment:fixed;">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-1 text-white">
                    <i class="fas fa-copyright me-1"></i>
                    {{ date('Y') }} <strong>{{ config('app.name') }}</strong> — Espace Utilisateur
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-3 flex-wrap">
                    <span class="text-dark small">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                    </span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-warning text-dark">
                        <i class="fas fa-eye me-1"></i>Site public
                    </a>
                </div>
            </div>
        </div>

    </div>
</footer>
