@extends('layouts.public')

@section('title', 'Plans d\'inscription')

@section('content')

<!-- Hero Section -->

<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <h1 class="display-4 fw-bold mb-4">Plans - Inscription</h1>
                </div>
                <p class="lead mb-0">
                    Choisissez la durée qui vous convient et accédez à la totalité des services
                </p>
            </div>
        </div>
    </div>
</section>



<!-- Plans d'inscription -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <p class="lead mb-4">
                    Choisissez la durée qui vous convient : 12 / 6 / 3 mois, et accédez à la totalité des services.
                </p>
            </div>
        </div>

        <div class="row g-4 justify-content-center mb-5">
            <!-- Plan 12 mois -->
            <div class="col-lg-4 col-md-6">
                <article class="card h-100 border-primary shadow">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <span class="badge bg-white text-primary">Meilleure valeur</span>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-swimmer text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h2 class="card-title h3 mb-2">12 mois</h2>
                        <p class="text-muted mb-3">
                            Accès complet à tous les services pendant une année complète.
                        </p>
                        <div class="mb-3">
                            <span class="text-muted me-2" style="font-weight: bold;"> 8€ par mois = 96€</span>
                        </div>
                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>Paiement unique non récurrent</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>Pas de Renouvellement Automatique</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>Accès illimité à toutes les ressources</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>Support prioritaire</span>
                            </li>
                        </ul>
                        <!-- Plan 12 mois -->
                        <a href="https://buy.stripe.com/dRm28r5AOfEDaHn0JxgnK02"
                            class="btn btn-success btn-lg text-white w-100"
                            target="_blank">
                            <i class="fas fa-credit-card me-2"></i>S'inscrire pour 12 mois
                        </a>

                        <div class="text-primary mb-3" style="font-size: 2.5rem;">
                            <i class="fab fa-paypal"></i>
                        </div>
                        <p class="text-muted mb-0">
                            S'inscrire avec Paypal.
                        </p>
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                            <input type="hidden" name="cmd" value="_s-xclick" />
                            <input type="hidden" name="hosted_button_id" value="WXXYSM9EF42ZN" />
                            <input type="hidden" name="currency_code" value="EUR" />
                            <input type="image" src="https://www.paypalobjects.com/fr_FR/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" title="PayPal, votre réflexe sécurité pour payer en ligne." alt="Acheter" />
                        </form>
                    </div>
                </article>
            </div>

            <!-- Plan 6 mois -->
            <div class="col-lg-4 col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-tie text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                        <h2 class="card-title h3 mb-2">6 mois</h2>
                        <p class="text-muted mb-3">
                            Solution intermédiaire avec tous les services pendant 6 mois.
                        </p>
                        <div class="mb-3">
                            <span class="text-muted me-2" style="font-weight: bold;"> 11€ par mois = 66€</span>
                        </div>
                        <ul class="list-unstyled text-start mb-4">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>Paiement unique non récurrent</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>Pas de Renouvellement Automatique</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>Accès illimité à toutes les ressources</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>Support standard</span>
                            </li>
                        </ul>
                        <!-- Plan 6 mois -->
                        <a href="https://buy.stripe.com/6oU9AT7IW8cbeXD1NBgnK01"
                            class="btn btn-outline-primary btn-lg w-100"
                            target="_blank">
                            <i class="fas fa-credit-card me-2"></i>S'inscrire pour 6 mois
                        </a>

                        <div class="text-primary mb-3" style="font-size: 2.5rem;">
                            <i class="fab fa-paypal"></i>
                        </div>
                        <p class="text-muted mb-0">
                            S'inscrire avec Paypal.
                        </p>
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                            <input type="hidden" name="cmd" value="_s-xclick" />
                            <input type="hidden" name="hosted_button_id" value="8UFX4YSD4G68G" />
                            <input type="hidden" name="currency_code" value="EUR" />
                            <input type="image" src="https://www.paypalobjects.com/fr_FR/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" title="PayPal, votre réflexe sécurité pour payer en ligne." alt="Acheter" />
                        </form>

                    </div>
                </article>
            </div>

            <!-- Plan 3 mois -->
            <div class="col-lg-4 col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-swimmer text-danger" style="font-size: 2.5rem;"></i>
                        </div>
                        <span class="text-muted me-2" style="font-weight: bold;"> 3 mois</h2>
                            <p class="text-muted mb-3">
                                Formule découverte avec tous les services pendant 3 mois.
                            </p>
                            <div class="mb-3">
                                <span class="text-muted me-2">15€ par mois = 45€</span>
                            </div>
                            <ul class="list-unstyled text-start mb-4">
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <span>Paiement unique non récurrent</span>
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <span>Pas de Renouvellement Automatique</span>
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <span>Accès illimité à toutes les ressources</span>
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <span>Support standard</span>
                                </li>
                            </ul>
                            <!-- Plan 3 mois -->
                            <a href="https://buy.stripe.com/6oUeVd9R478716NgIvgnK00"
                                class="btn btn-outline-primary btn-lg w-100"
                                target="_blank">
                                <i class="fas fa-credit-card me-2"></i>S'inscrire pour 3 mois
                            </a>
                            <div class="text-primary mb-3" style="font-size: 2.5rem;">
                                <i class="fab fa-paypal"></i>
                            </div>
                            <p class="text-muted mb-0">
                                S'inscrire avec Paypal.
                            </p>
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                <input type="hidden" name="cmd" value="_s-xclick" />
                                <input type="hidden" name="hosted_button_id" value="FVB4LYV557SLY" />
                                <input type="hidden" name="currency_code" value="EUR" />
                                <input type="image" src="https://www.paypalobjects.com/fr_FR/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" title="PayPal, votre réflexe sécurité pour payer en ligne." alt="Acheter" />
                            </form>
                    </div>
                </article>
            </div>
        </div>
    </div>

        <!-- Bandeau Prix -->
        <div class="alert alert-warning border-0 shadow-sm text-center">
            <div class="row align-items-center">
                <div class="col mx-auto">
                    <p class="mb-3">
                        <strong>Pour les inscriptions premium de groupes, clubs ou centres de formation, veuillez <a href="{{ route('contact') }}">
                                Nous contacter <i class="fas fa-envelope me-2"></i> </a>.</strong>
                    </p>
                </div>
            </div>
        </div>
          <div class="alert alert-success border-0 shadow-sm text-center">
            <div class="row align-items-center">
                <div class="col mx-auto">
                    <p class="mb-3">
       Si vous avez déjà participé à nos <strong>camps, stages, formations ou webinaires, </strong> veuillez valider votre compte sur la plateforme. Les liens d'accès vous ont été envoyés suite à votre inscription.
                    </p>
                </div>
            </div>
        </div>


</section>

<!-- Pourquoi Nataswim -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="text-primary mb-3" style="font-size: 2.5rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3">Gagner du temps </h3>
                        <p class="text-muted mb-0">
                            Des ressources conçues pour optimiser votre progression et améliorer rapidement vos performances.
                        </p>
                    </div>
                </article>
            </div>

            <div class="col-lg-4 col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="text-primary mb-3" style="font-size: 2.5rem;">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3">Souplesse et liberté d'utilisation</h3>
                        <p class="text-muted mb-0">
                            Accédez à nos contenus quand vous voulez, où vous voulez, selon votre propre rythme.
                        </p>
                    </div>
                </article>
            </div>

            <div class="col-lg-4 col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="text-primary mb-3" style="font-size: 2.5rem;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3">Qualité </h3>
                        <p class="text-muted mb-0">
                            Des contenus élaborés par des professionnels reconnus dans le domaine sportif.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>



<!-- CTA -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container-lg">
        <h2 class="mb-4 fw-bold">Des questions ?</h2>
        <p class="lead mb-4">
            N'hésitez pas à nous contacter ! Nous sommes là pour y répondre.
        </p>
        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
            Contactez notre équipe !
        </a>
    </div>
</section>

<!-- Table des matières -->
<section class="py-4 bg-light border-bottom sticky-top" style="top: 70px; z-index: 100;">
    <div class="container-lg">
        <nav class="d-flex flex-wrap justify-content-center gap-2">
            <a href="#comparatif" class="btn btn-outline-primary btn-sm">📊 Comparatif</a>
            <a href="#carnets" class="btn btn-outline-primary btn-sm">📚 Carnets</a>
            <a href="#videos" class="btn btn-outline-primary btn-sm">🎥 Vidéos</a>
            <a href="#exercices" class="btn btn-outline-primary btn-sm">🏋️ Exercices</a>
            <a href="#plans" class="btn btn-outline-primary btn-sm">📅 Plans</a>
            <a href="#ebooks" class="btn btn-outline-primary btn-sm">📖 eBooks</a>
            <a href="#fiches" class="btn btn-outline-primary btn-sm">📋 Fiches</a>
            <a href="#outils" class="btn btn-outline-primary btn-sm">🔧 Outils</a>
        </nav>
    </div>
</section>

<!-- Tableau Comparatif Visiteur vs Premium -->
<section id="comparatif" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-balance-scale text-primary" style="font-size: 3rem;"></i>
            <h2 class="mt-3">Visiteur vs Premium : Quelle différence ?</h2>
            <p class="text-muted">Comparez les accès et débloquez tout le potentiel de Nataswim</p>
        </div>

        <div class="row g-4 mb-5">
            <!-- Colonne Visiteur -->
            <div class="col-lg-6">
                <div class="card h-100 border-danger">
                    <div class="card-header bg-danger text-white text-center p-4">
                        <i class="fas fa-user fa-2x mb-2"></i>
                        <h3 class="h4 mb-0">Compte Visiteur</h3>
                        <p class="mb-0 small">Gratuit - Accès limité</p>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-times-circle text-danger me-3"></i>
                                <span>Accès <strong>limité</strong> aux articles</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-times-circle text-danger me-3"></i>
                                <span>Quelques vidéos gratuites seulement</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-times-circle text-danger me-3"></i>
                                <span>Aperçu des plans d'entraînement</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-times-circle text-danger me-3"></i>
                                <span>Extraits d'eBooks uniquement</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-times-circle text-danger me-3"></i>
                                <span>Fiches techniques limitées</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-times-circle text-danger me-3"></i>
                                <span><strong>Pas de carnets personnalisés</strong></span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Outils gratuits (calculateurs)</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Colonne Premium -->
            <div class="col-lg-6">
                <div class="card h-100 border-success shadow-lg position-relative">
                    <div class="position-absolute top-0 start-50 translate-middle">
                        <span class="badge bg-warning text-dark px-4 py-2 fs-6">
                            <i class="fas fa-star me-1"></i>Recommandé
                        </span>
                    </div>
                    <div class="card-header bg-success text-white text-center p-4">
                        <i class="fas fa-crown fa-2x mb-2"></i>
                        <h3 class="h4 mb-0">Compte Premium</h3>
                        <p class="mb-0 small">À partir de 5€/mois</p>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span><strong>Accès illimité</strong> à tous les articles</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span><strong>Bibliothèque vidéos complète</strong></span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Plans d'entraînement complets</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Téléchargement illimité d'eBooks</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Toutes les fiches techniques</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span><strong>Carnets personnalisés illimités</strong></span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Mises à jour et nouveaux contenus</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Support prioritaire</span>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>




        <div class="mb-5">

            <h2 class="mt-3">Comment obtenir un compte Premium </h2>
            <p class="text-muted">1. Créez un compte utilisateur.
            </p>

            <p class="text-muted">
                2. Connectez-vous à votre espace avec votre adresse e-mail et votre mot de passe. </p>

            <p class="text-muted"> 3. Sélectionnez une formule premium et valider.</p>

            <p class="text-muted"> 4. Vous débloquerez ainsi l'accès à l'intégralité du contenu réservé aux membres premium.</p>


        </div>
    </div>
</section>


@endsection
@push('styles')
<style>
    

    .bg-gradient-light {
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
    }

    

    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }

    .card {
        border-radius: 1rem;
    }

    .badge {
        font-weight: 500;
    }
</style>
@endpush