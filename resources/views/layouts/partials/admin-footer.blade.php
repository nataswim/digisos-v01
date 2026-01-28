
<footer class="admin-footer mt-5" style="border-top: 20px solid #51b7d5;border-left: 20px solid #f8f9f3;border-right: 20px solid #ffffff;border-bottom: 20px solid #378094;">
    
    <div class="container-fluid py-4">
        <div class="row g-4">
            <!-- Colonne 1 : Sitemap -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading mb-3">
                    <i class="fas fa-sitemap me-2"></i>Sitemap
                </h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('admin.sitemap.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Gestion Sitemap
                    </a></li>
                    <li><a href="{{ route('admin.posts.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Articles SEO
                    </a></li>
                    <li><a href="{{ route('admin.fiches.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Fiches techniques
                    </a></li>
                    <li><a href="{{ route('admin.videos.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Vidéos
                    </a></li>
                </ul>
            </div>

            <!-- Colonne 2 : Utilisateurs -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading mb-3">
                    <i class="fas fa-users me-2"></i>Utilisateurs
                </h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('admin.users.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Gestion utilisateurs
                    </a></li>
                    <li><a href="{{ route('admin.roles.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Rôles & permissions
                    </a></li>
                    <li><a href="{{ route('profile.edit') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Mon profil
                    </a></li>
                </ul>
            </div>

            <!-- Colonne 3 : eBooks -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading mb-3">
                    <i class="fas fa-book me-2"></i>eBooks & Ressources
                </h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('admin.downloadables.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Téléchargements
                    </a></li>
                    <li><a href="{{ route('admin.download-categories.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Catégories
                    </a></li>
                    <li><a href="{{ route('admin.media.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Médiathèque
                    </a></li>
                </ul>
            </div>

            <!-- Colonne 4 : Liens externes -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading mb-3">
                    <i class="fas fa-external-link-alt me-2"></i>Outils
                </h6>
                <ul class="footer-links list-unstyled">
                    
                    <!-- Outils SEO & Web -->
                    <li><a href="https://search.google.com/search-console" target="_blank" rel="noopener noreferrer" class="footer-link">
                        <i class="fab fa-google me-2"></i>Search Console <i class="fas fa-external-link-alt ms-1 small"></i>
                    </a></li>
                    <li><a href="https://analytics.google.com/" target="_blank" rel="noopener noreferrer" class="footer-link">
                        <i class="fas fa-chart-bar me-2"></i>Google Analytics <i class="fas fa-external-link-alt ms-1 small"></i>
                    </a></li>
                </ul>
            </div>
        </div>



        <!-- Copyright et informations -->
        <div class="row mt-4 pt-4 border-top" style=" background-color: #ffffff !important; padding-bottom: 20px; ">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-1 text-muted">
                    <i class="fas fa-copyright me-1"></i>
                    {{ date('Y') }} <strong>{{ config('app.name') }}</strong> - Administration
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="footer-meta d-flex justify-content-center justify-content-md-end align-items-center gap-3 flex-wrap">
                    <span class="text-muted small">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                    </span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-warning text-white">
                        <i class="fas fa-eye me-1"></i>Voir le site
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>