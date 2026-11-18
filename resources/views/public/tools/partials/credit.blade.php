{{-- Crédit commun à toutes les pages d'outils --}}
<section class="py-4 border-top" style="background: rgba(248, 249, 250, .95);">
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-lg-8 d-flex align-items-center gap-3">
                <a href="https://nataswim.fr/about" target="_blank" rel="noopener noreferrer" class="flex-shrink-0">
                    <img src="{{ asset('assets/images/team/hassan-elhaouat-2010.png') }}"
                         alt="Hassan EL HAOUAT" width="72" height="72"
                         class="rounded-circle border shadow-sm" style="object-fit: cover;" loading="lazy">
                </a>
                <div>
                    <p class="mb-1">
                        Contenus et outils développés par
                        <a href="https://nataswim.fr/about" target="_blank" rel="noopener noreferrer" class="fw-bold">Hassan EL HAOUAT (SNS)<span class="visually-hidden"> (nouvel onglet)</span></a>
                        (2015 - 2017)
                    </p>
                    <p class="mb-0 small text-muted">
                        Retrouvez l'ensemble des outils sur
                        <a href="https://nataswim.fr/outils" target="_blank" rel="noopener noreferrer">nataswim.fr/outils<span class="visually-hidden"> (nouvel onglet)</span></a>.
                        Ces outils donnent des estimations et ne remplacent pas l'avis d'un professionnel de santé ou de votre entraîneur.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('tools.index') }}" class="btn btn-primary">
                    <i class="fas fa-th me-2" aria-hidden="true"></i>Tous les outils
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-1">
                    <i class="fas fa-home me-1" aria-hidden="true"></i>Site du club
                </a>
            </div>
        </div>
    </div>
</section>
