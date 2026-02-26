<footer class="mt-5" style="border-top: 20px solid #ffffff;border-bottom: 20px solid #ffffff;background: linear-gradient(129deg, #e9f7fa 85%, #4aabca 0);background-attachment: fixed;background-position: top;">
    
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3" style="background-color: #4aabca;display: block;padding: 10px 5px;color: white;">
                    <i class="fas fa-newspaper me-2"></i>Articles
                </h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('editor.posts.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Liste des articles
                    </a></li>
                    <li><a href="{{ route('editor.posts.create') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Nouvel article
                    </a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3" style="background-color: #4aabca;display: block;padding: 10px 5px;color: white;">
                    <i class="fas fa-file-alt me-2"></i>Fiches
                </h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('editor.fiches.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Liste des fiches
                    </a></li>
                    <li><a href="{{ route('editor.fiches.create') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Nouvelle fiche
                    </a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3" style="background-color: #4aabca;display: block;padding: 10px 5px;color: white;">
                    <i class="fas fa-video me-2"></i>Multimédia
                </h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('editor.videos.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Vidéos
                    </a></li>
                    <li><a href="{{ route('editor.media.index') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Médiathèque
                    </a></li>
                    <li><a href="{{ route('editor.stats') }}" class="footer-link">
                        <i class="fas fa-angle-right me-2"></i>Statistiques
                    </a></li>
                </ul>
            </div>
        </div>

        <div class="row mt-4 pt-4 border-top" style="background-color: #5fcac6 !important;padding-bottom: 20px;color: #000000;background: linear-gradient(131deg, #4babca 85%, #e9f7fa 0);background-attachment: fixed;">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-1 text-white">
                    <i class="fas fa-copyright me-1"></i>
                    {{ date('Y') }} <strong>{{ config('app.name') }}</strong> - Espace Éditeur
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="footer-meta d-flex justify-content-center justify-content-md-end align-items-center gap-3 flex-wrap">
                    <span class="text-dark small">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                    </span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-warning text-dark">
                        <i class="fas fa-eye me-1"></i>Voir
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>