@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', 'Politique de Confidentialité')
@section('meta_description', 'Découvrez comment nous protégeons vos données personnelles et respectons votre vie privée conformément au RGPD.')

{{-- Open Graph / Facebook --}}
@section('og_type', 'website')
@section('og_title', 'Politique de Confidentialité - ' . config('app.name'))
@section('og_description', 'Protection de votre vie privée et sécurité de vos données conformément au RGPD')
@section('og_url', route('privacy'))

@section('content')

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-shield-alt me-3 fs-1"></i>
                    <h1 class="display-5 fw-bold mb-0">Politique de Confidentialité</h1>
                </div>
                <p class="lead mb-3">
                    Protection de votre vie privée et sécurité de vos données
                </p>
                <p class="mb-0 opacity-75">
                    Dernière mise à jour : {{ now()->format('d/m/Y') }}
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="h5 text-primary mb-3">Résumé de notre politique</h3>
                    <p class="small text-dark mb-3">
                        Chez Nataswim, nous prenons la protection de vos données personnelles très au sérieux. 
                        Cette politique explique comment nous collectons, utilisons et protégeons vos informations.
                    </p>
                    <p class="small text-dark mb-0">
                        Nous respectons pleinement le Règlement Général sur la Protection des Données (RGPD).
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Introduction -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="h3 fw-bold mb-4">1. Introduction</h2>
                <p class="mb-4">
                    La présente politique de confidentialité s'applique à tous les utilisateurs de l'application et du 
                    site web Nataswim (ci-après "nos Services"). En utilisant nos Services, vous acceptez les pratiques 
                    décrites dans cette politique.
                </p>
                <p class="mb-5">
                    Si vous n'êtes pas d'accord avec cette politique, veuillez ne pas utiliser nos Services. Si vous avez 
                    des questions, n'hésitez pas à nous contacter aux coordonnées indiquées à la fin de ce document.
                </p>

                <div class="card p-4 mb-5 border-0 bg-light shadow-sm">
                    <h3 class="h5 fw-bold mb-3">2. Responsable du traitement</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Raison sociale :</strong> SNS</p>
                            <p class="mb-0"><strong>Adresse :</strong> 45 Avenue Albert Camus, 75200 Paris, France</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Email :</strong> natation.swimming@gmail.com</p>
                            <p class="mb-0"><strong>Contact DPO :</strong> natation.swimming@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Données collectées -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <div class="d-inline-flex align-items-center mb-3">
                        <div class="bg-primary p-2 rounded-circle me-3">
                            <i class="fas fa-database text-white fs-4"></i>
                        </div>
                        <h2 class="h3 fw-bold mb-0">3. Données personnelles que nous collectons</h2>
                    </div>
                    <p class="text-muted mb-0">
                        Selon votre utilisation de nos Services, nous pouvons collecter les types de données suivants
                    </p>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-id-card text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données d'identification</h4>
                                <p class="small text-muted mb-0">Nom, prénom, email, identifiants de connexion</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-running text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données de profil</h4>
                                <p class="small text-muted mb-0">Âge, sexe, niveau, objectifs d'entraînement</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-chart-line text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données d'activité</h4>
                                <p class="small text-muted mb-0">Performances, fréquence, progression</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-heartbeat text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données de santé</h4>
                                <p class="small text-muted mb-0">Fréquence cardiaque, calories (si fournies)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-tools text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données techniques</h4>
                                <p class="small text-muted mb-0">Adresse IP, navigateur, appareil utilisé</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-server text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données d'utilisation</h4>
                                <p class="small text-muted mb-0">Pages visitées, fonctionnalités utilisées</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-credit-card text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données de transaction</h4>
                                <p class="small text-muted mb-0">Historique des achats, abonnements</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-comment text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données de communication</h4>
                                <p class="small text-muted mb-0">Messages, demandes de support</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mb-5">
                    <div class="d-flex">
                        <i class="fas fa-heartbeat text-primary me-3 fs-4 flex-shrink-0 mt-1"></i>
                        <div>
                            <h4 class="h6 fw-bold">Remarque importante concernant les données de santé</h4>
                            <p class="mb-0 small">
                                Certaines données collectées peuvent être considérées comme des données de santé selon le RGPD. 
                                Nous ne traitons ces données qu'avec votre consentement explicite et uniquement dans le but de 
                                vous fournir nos Services.
                            </p>
                        </div>
                    </div>
                </div>

                <h2 class="h3 fw-bold mb-4">4. Comment nous collectons vos données</h2>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <article class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Directement auprès de vous</h4>
                                </div>
                                <p class="small text-muted mb-0">
                                    Lorsque vous créez un compte, complétez votre profil, utilisez nos Services
                                </p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6">
                        <article class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-tools text-success"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Automatiquement</h4>
                                </div>
                                <p class="small text-muted mb-0">
                                    Via des cookies et technologies similaires lorsque vous utilisez notre site web
                                </p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6">
                        <article class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-heartbeat text-warning"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Via des appareils connectés</h4>
                                </div>
                                <p class="small text-muted mb-0">
                                    Que vous choisissez de synchroniser avec notre application (montres connectées)
                                </p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6">
                        <article class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-handshake text-white"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Via des tiers</h4>
                                </div>
                                <p class="small text-muted mb-0">
                                    Lorsque vous vous connectez via des services d'authentification tiers
                                </p>
                            </div>
                        </article>
                    </div>
                </div>

                <p class="mb-5">
                    Pour plus d'informations sur notre utilisation des cookies, veuillez consulter notre 
                    <a href="{{ route('cookies') }}" class="text-decoration-none">Politique de Cookies</a>.
                </p>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-balance-scale text-warning fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">5. Bases légales du traitement</h3>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body p-3">
                                        <h5 class="h6 fw-bold mb-2">Exécution du contrat</h5>
                                        <p class="small mb-0">
                                            Le traitement est nécessaire pour l'exécution de nos Services auxquels vous avez souscrit
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body p-3">
                                        <h5 class="h6 fw-bold mb-2">Consentement</h5>
                                        <p class="small mb-0">
                                            Vous nous avez donné votre consentement spécifique pour le traitement
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body p-3">
                                        <h5 class="h6 fw-bold mb-2">Obligations légales</h5>
                                        <p class="small mb-0">
                                            Le traitement est nécessaire pour respecter nos obligations légales
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body p-3">
                                        <h5 class="h6 fw-bold mb-2">Intérêts légitimes</h5>
                                        <p class="small mb-0">
                                            Le traitement est nécessaire aux fins de nos intérêts légitimes
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-cogs text-primary fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">6. Comment nous utilisons vos données</h3>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                                    <p class="small mb-0">Fournir nos Services : inscription, authentification, accès aux fonctionnalités</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                                    <p class="small mb-0">Personnaliser votre expérience : contenus et programmes adaptés</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                                    <p class="small mb-0">Améliorer nos Services : analyser l'utilisation, résoudre les problèmes</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                                    <p class="small mb-0">Communiquer avec vous : informations, assistance, réponses</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                                    <p class="small mb-0">Marketing : vous informer sur nos offres (avec votre consentement)</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                                    <p class="small mb-0">Assurer la sécurité : détecter les fraudes, protéger nos systèmes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-exchange-alt text-info fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">7. Partage de vos données</h3>
                        </div>
                        <p class="mb-3">
                            Nous pouvons partager vos données personnelles avec les catégories de destinataires suivantes :
                        </p>
                        <ul class="mb-3">
                            <li class="mb-2"><strong>Prestataires de services :</strong> Entreprises qui nous aident à fournir nos Services</li>
                            <li class="mb-2"><strong>Partenaires commerciaux :</strong> Dans le cadre de fonctionnalités spécifiques (avec votre consentement)</li>
                            <li class="mb-2"><strong>Autorités publiques :</strong> Lorsque la loi l'exige ou pour protéger nos droits</li>
                        </ul>
                        <p class="mb-2">
                            <strong>Nous ne vendons pas vos données personnelles à des tiers.</strong>
                        </p>
                        <p class="mb-0 small text-muted">
                            Tous nos prestataires de services et partenaires sont tenus de respecter la confidentialité et la 
                            sécurité de vos données conformément au RGPD.
                        </p>
                    </div>
                </article>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-globe-europe text-success fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">8. Transferts internationaux de données</h3>
                        </div>
                        <p class="mb-3">
                            Vos données personnelles peuvent être transférées et traitées dans des pays en dehors de l'Espace 
                            Économique Européen (EEE), notamment pour l'hébergement et le support technique.
                        </p>
                        <p class="mb-3">
                            Lorsque nous transférons vos données hors de l'EEE, nous nous assurons qu'elles bénéficient d'un 
                            niveau de protection approprié en utilisant :
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                                    <p class="small mb-0">Des pays bénéficiant d'une décision d'adéquation de la Commission européenne</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                                    <p class="small mb-0">Des clauses contractuelles types approuvées par la Commission</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-user-clock text-warning fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">9. Conservation des Données</h3>
                        </div>
                        <p class="mb-3">
                            Nous conservons vos données personnelles uniquement pendant la durée nécessaire à la réalisation 
                            des finalités pour lesquelles elles ont été collectées, ou pour nous conformer à nos obligations légales.
                        </p>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type de données</th>
                                        <th>Durée de conservation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Données de compte</strong></td>
                                        <td>Pendant la durée de votre compte, puis 3 ans après sa fermeture</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Données d'activité</strong></td>
                                        <td>Pendant la durée de votre compte, puis en version anonymisée</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Données de transaction</strong></td>
                                        <td>10 ans pour les obligations comptables et fiscales</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Données de communication</strong></td>
                                        <td>3 ans après le dernier contact</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Données techniques</strong></td>
                                        <td>13 mois maximum</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mb-0 small">
                            À l'issue de ces périodes, vos données sont soit supprimées, soit anonymisées de manière irréversible.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Vos Droits -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <div class="d-inline-flex align-items-center mb-3">
                        <div class="bg-primary p-2 rounded-circle me-3">
                            <i class="fas fa-user-shield text-white fs-4"></i>
                        </div>
                        <h2 class="h3 fw-bold mb-0">10. Vos Droits</h2>
                    </div>
                    <p class="text-muted mb-0">
                        Conformément au RGPD, vous disposez des droits suivants concernant vos données personnelles
                    </p>
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-user-shield text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Droit d'accès</h4>
                                </div>
                                <p class="text-muted small mb-0">Obtenir une copie de vos données</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-clipboard-list text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Droit de rectification</h4>
                                </div>
                                <p class="text-muted small mb-0">Corriger des données inexactes</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-database text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Droit à l'effacement</h4>
                                </div>
                                <p class="text-muted small mb-0">Faire supprimer vos données</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-cogs text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Droit à la limitation</h4>
                                </div>
                                <p class="text-muted small mb-0">Restreindre le traitement</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-exchange-alt text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Droit à la portabilité</h4>
                                </div>
                                <p class="text-muted small mb-0">Recevoir vos données</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-balance-scale text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Droit d'opposition</h4>
                                </div>
                                <p class="text-muted small mb-0">S'opposer au traitement</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-bell text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Retrait du consentement</h4>
                                </div>
                                <p class="text-muted small mb-0">Retirer votre consentement</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-handshake text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Non-décision automatisée</h4>
                                </div>
                                <p class="text-muted small mb-0">Décision humaine garantie</p>
                            </div>
                        </article>
                    </div>
                </div>

                <div class="bg-light p-4 rounded shadow-sm mb-5">
                    <p class="mb-3">
                        Pour exercer ces droits, veuillez nous contacter aux coordonnées indiquées à la section 
                        "Nous contacter" ci-dessous.
                    </p>
                    <p class="mb-3 small">
                        Nous répondrons à votre demande dans un délai d'un mois, qui peut être prolongé de deux mois 
                        supplémentaires si nécessaire, compte tenu de la complexité et du nombre de demandes.
                    </p>
                    <p class="mb-0 small">
                        Si vous n'êtes pas satisfait de notre réponse, vous avez le droit d'introduire une réclamation 
                        auprès de la CNIL (www.cnil.fr) ou de toute autre autorité de protection des données compétente.
                    </p>
                </div>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-lock text-success fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">11. Sécurité des données</h3>
                        </div>
                        <p class="mb-3">
                            Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger 
                            vos données personnelles :
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="fas fa-key text-success"></i>
                                    </div>
                                    <p class="mb-0 small">Chiffrement des données sensibles</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="fas fa-user-shield text-success"></i>
                                    </div>
                                    <p class="mb-0 small">Contrôles d'accès stricts</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="fas fa-database text-success"></i>
                                    </div>
                                    <p class="mb-0 small">Sauvegardes régulières</p>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <p class="mb-0 small">
                                <strong>Note :</strong> Bien que nous mettions en œuvre les meilleures pratiques de sécurité, 
                                aucun système n'est totalement sécurisé. Nous ne pouvons donc pas garantir la sécurité absolue 
                                de vos données.
                            </p>
                        </div>
                    </div>
                </article>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-child text-warning fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">12. Protection des mineurs</h3>
                        </div>
                        <p class="mb-3">
                            Nos Services ne sont pas destinés aux personnes de moins de 16 ans, et nous ne collectons pas 
                            sciemment des données personnelles auprès de mineurs de moins de 16 ans.
                        </p>
                        <p class="mb-0 small">
                            Si vous êtes un parent ou un tuteur et que vous pensez que votre enfant nous a fourni des données 
                            personnelles, veuillez nous contacter immédiatement. Si nous découvrons que nous avons collecté 
                            des données personnelles auprès d'un enfant sans vérification du consentement parental, nous 
                            prendrons des mesures pour supprimer ces informations de nos serveurs.
                        </p>
                    </div>
                </article>

                <article class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-bell text-primary fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">13. Modifications de cette politique</h3>
                        </div>
                        <p class="mb-3">
                            Nous pouvons mettre à jour cette politique de confidentialité de temps à autre pour refléter des 
                            changements dans nos pratiques ou pour d'autres raisons opérationnelles, légales ou réglementaires.
                        </p>
                        <p class="mb-3">
                            En cas de modifications substantielles, nous vous en informerons par email ou par une notification 
                            sur notre site avant que les modifications ne prennent effet.
                        </p>
                        <p class="mb-0 small">
                            La date de la dernière mise à jour est indiquée en haut de cette politique. Nous vous encourageons 
                            à consulter régulièrement cette politique pour rester informé.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Contact -->
<section class="py-5 bg-primary text-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="mb-4">
                    <i class="fas fa-envelope fs-1"></i>
                </div>
                <h2 class="h3 fw-bold mb-4">14. Nous Contacter</h2>
                <p class="mb-4">
                    Si vous avez des questions, des préoccupations ou des demandes concernant cette politique de 
                    confidentialité ou le traitement de vos données personnelles, veuillez nous contacter :
                </p>
                <div class="bg-white text-dark p-4 rounded shadow mb-4">
                    <div class="row">
                        <div class="col-md-6 text-start">
                            <p class="mb-2 small"><strong>Responsable :</strong> DPO Nataswim</p>
                            <p class="mb-0 small"><strong>Email :</strong> natation.swimming@gmail.com</p>
                        </div>
                        <div class="col-md-6 text-start">
                            <p class="mb-2 small"><strong>Adresse :</strong> 45 Avenue Albert Camus, 75200 Paris</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>
                    Contactez-nous
                </a>
            </div>
        </div>
    </div>
</section>

@endsection