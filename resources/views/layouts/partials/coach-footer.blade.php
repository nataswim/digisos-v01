<footer class="espace-footer espace-footer--coach mt-5">
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3"><i class="fas fa-calendar-week me-2" aria-hidden="true"></i>Planning</h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('coach.planning.slots.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Créneaux</a></li>
                    <li><a href="{{ route('coach.planning.groups.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Groupes</a></li>
                    <li><a href="{{ route('coach.planning.swimmers.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Nageurs</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3"><i class="fas fa-trophy me-2" aria-hidden="true"></i>Compétitions</h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('coach.competitions.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Compétitions et convocations</a></li>
                    <li><a href="{{ route('coach.records.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Records</a></li>
                    <li><a href="{{ route('competitions.index') }}" class="footer-link" target="_blank" rel="noopener"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Calendrier public</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading mb-3"><i class="fas fa-user-shield me-2" aria-hidden="true"></i>Réservé aux coachs</h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('coach.training.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Séances</a></li>
                    <li><a href="{{ route('coach.exercises.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Bibliothèque d'exercices</a></li>
                    <li><a href="{{ route('coach.presence.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Émargement</a></li>
                    <li><a href="{{ route('coach.assiduity.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Assiduité</a></li>
                    <li><a href="{{ route('coach.performances.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Performances</a></li>
                    <li><a href="{{ route('coach.notes.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Suivi des nageurs</a></li>
                    <li><a href="{{ route('coach.documents.index') }}" class="footer-link"><i class="fas fa-angle-right me-2" aria-hidden="true"></i>Documents</a></li>
                </ul>
            </div>
        </div>

        <div class="row mt-4 pt-4 espace-footer-bottom">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-1">© {{ date('Y') }} <strong>{{ config('app.name') }}</strong> — Espace coachs</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">
                    <i class="fas fa-globe me-1" aria-hidden="true"></i>Voir le site
                </a>
            </div>
        </div>
    </div>
</footer>
