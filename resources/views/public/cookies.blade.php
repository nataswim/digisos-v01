@extends('layouts.public')

@section('title', 'Politique de Cookies')

@section('content')

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-cookie-bite me-3 fs-1"></i>
                    <h1 class="display-5 fw-bold mb-0">Politique de Cookies</h1>
                </div>
                <p class="lead mb-3">
                    Transparence et protection de vos données personnelles
                </p>
                <p class="mb-0 opacity-75">
                    Dernière mise à jour : {{ now()->format('d/m/Y') }}
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="h5 text-primary mb-3">Résumé Simplifié</h3>
                    <ul class="list-unstyled mb-0 text-start">
                        <li class="mb-2 d-flex align-items-start">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-dark small">Nous utilisons des cookies pour améliorer votre expérience</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-dark small">Vos données sont traitées dans le respect du RGPD</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-dark small">Vous pouvez gérer vos préférences à tout moment</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-dark small">Nous ne vendons pas vos données à des tiers</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-dark small">Vous avez des droits sur vos données</span>
                        </li>
                    </ul>
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
                    La présente politique de cookies et de traitement des données personnelles décrit la manière dont 
                    DIGITALSOS (ci-après "nous", "notre", "nos") collecte, utilise et partage les informations, y compris 
                    vos données personnelles, lorsque vous utilisez notre site web.
                </p>
                <p class="mb-5">
                    Cette politique vise à vous informer sur vos droits et sur la façon dont vos données sont protégées 
                    conformément au Règlement Général sur la Protection des Données (RGPD) et autres lois applicables sur 
                    la protection des données.
                </p>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-cookie-bite text-primary fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">2. Qu'est-ce qu'un Cookie ?</h3>
                        </div>
                        <p class="mb-3">
                            Un cookie est un petit fichier texte placé sur votre appareil (ordinateur, tablette, smartphone) 
                            lorsque vous visitez un site web. Les cookies permettent au site de reconnaître votre appareil et 
                            de mémoriser certaines informations sur votre visite, comme vos préférences de langue ou vos 
                            informations de connexion.
                        </p>
                        <p class="mb-0">
                            Les cookies peuvent être "persistants" ou "de session". Les cookies persistants restent sur votre 
                            appareil jusqu'à ce qu'ils expirent ou que vous les supprimiez, tandis que les cookies de session 
                            sont supprimés dès que vous fermez votre navigateur.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Types de Cookies -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <div class="d-inline-flex align-items-center mb-3">
                        <div class="bg-primary p-2 rounded-circle me-3">
                            <i class="fas fa-database text-white fs-4"></i>
                        </div>
                        <h2 class="h3 fw-bold mb-0">3. Types de Cookies Utilisés</h2>
                    </div>
                    <p class="text-muted mb-0">
                        Découvrez les différents types de cookies que nous utilisons et leur finalité
                    </p>
                </div>

                <div class="row g-4 mb-5">
                    <!-- Cookies Essentiels -->
                    <div class="col-md-6">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h4 class="h6 fw-bold mb-3">Cookies Essentiels</h4>
                                <p class="mb-2 small">
                                    Nécessaires au fonctionnement du site. Ils permettent d'utiliser les principales 
                                    fonctionnalités, comme l'accès à votre compte.
                                </p>
                                <div class="d-flex align-items-center mt-3">
                                    <i class="fas fa-history text-primary me-2"></i>
                                    <span class="text-muted small">Session / Persistants (1 an max)</span>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Cookies de Fonctionnalité -->
                    <div class="col-md-6">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h4 class="h6 fw-bold mb-3">Cookies de Fonctionnalité</h4>
                                <p class="mb-2 small">
                                    Permettent de mémoriser vos préférences et de personnaliser votre expérience (langue, 
                                    région, paramètres d'affichage).
                                </p>
                                <div class="d-flex align-items-center mt-3">
                                    <i class="fas fa-history text-primary me-2"></i>
                                    <span class="text-muted small">Persistants (1 an max)</span>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Cookies Analytiques -->
                    <div class="col-md-6">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h4 class="h6 fw-bold mb-3">Cookies Analytiques</h4>
                                <p class="mb-2 small">
                                    Nous aident à comprendre comment vous interagissez avec le site, à mesurer l'audience 
                                    et à améliorer le site.
                                </p>
                                <div class="d-flex align-items-center mt-3">
                                    <i class="fas fa-history text-primary me-2"></i>
                                    <span class="text-muted small">Persistants (2 ans max)</span>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Cookies de Marketing -->
                    <div class="col-md-6">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h4 class="h6 fw-bold mb-3">Cookies de Marketing</h4>
                                <p class="mb-2 small">
                                    Utilisés pour suivre les visiteurs sur les sites web et afficher des publicités pertinentes.
                                </p>
                                <div class="d-flex align-items-center mt-3">
                                    <i class="fas fa-history text-primary me-2"></i>
                                    <span class="text-muted small">Persistants (1 an max)</span>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

                <h2 class="h3 fw-bold mb-4">4. Données Personnelles Collectées</h2>
                <p class="mb-3">
                    Nous collectons différents types de données personnelles, selon votre interaction avec notre plateforme :
                </p>
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-user-shield text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données d'identification</h4>
                                <p class="small text-muted mb-0">Nom, prénom, adresse email</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-cogs text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données techniques</h4>
                                <p class="small text-muted mb-0">Adresse IP, navigateur, appareil</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-globe-europe text-primary mb-3 fs-3"></i>
                                <h4 class="h6 fw-bold">Données de navigation</h4>
                                <p class="small text-muted mb-0">Pages visitées, temps passé</p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-clipboard-list text-success fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">5. Finalités du Traitement</h3>
                        </div>
                        <ul class="mb-0">
                            <li class="mb-2">Fournir et améliorer nos services</li>
                            <li class="mb-2">Personnaliser votre expérience utilisateur</li>
                            <li class="mb-2">Analyser l'utilisation de notre site</li>
                            <li class="mb-2">Communiquer avec vous (notifications, assistance)</li>
                            <li class="mb-2">Assurer la sécurité de notre plateforme</li>
                            <li>Répondre à des obligations légales</li>
                        </ul>
                    </div>
                </article>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-balance-scale text-warning fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">6. Base Légale du Traitement</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="fw-semibold mb-1">Exécution d'un contrat</p>
                                <p class="small mb-0">Lorsque le traitement est nécessaire à l'exécution de nos services</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="fw-semibold mb-1">Consentement</p>
                                <p class="small mb-0">Lorsque vous avez donné votre consentement explicite</p>
                            </div>
                            <div class="col-md-6">
                                <p class="fw-semibold mb-1">Intérêt légitime</p>
                                <p class="small mb-0">Lorsque nous avons un intérêt commercial légitime</p>
                            </div>
                            <div class="col-md-6">
                                <p class="fw-semibold mb-1">Obligation légale</p>
                                <p class="small mb-0">Lorsque nous devons nous conformer à une obligation légale</p>
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
                            <h3 class="h5 mb-0">7. Partage de Vos Données</h3>
                        </div>
                        <p class="mb-3">
                            Nous ne partageons pas vos données personnelles avec des tiers sans votre consentement.
                        </p>
                        <p class="mb-0">
                            Nous ne vendons pas vos données personnelles à des tiers. En cas où nous partageons vos données 
                            avec des prestataires, nous nous assurons qu'ils respectent des normes de sécurité et de 
                            confidentialité conformes au RGPD.
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
                        <h2 class="h3 fw-bold mb-0">8. Vos Droits</h2>
                    </div>
                    <p class="text-muted mb-0">
                        En vertu du RGPD, vous disposez des droits suivants concernant vos données personnelles
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
                                <p class="text-muted small mb-0">Obtenir une copie de vos données personnelles</p>
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
                                <p class="text-muted small mb-0">Corriger des données inexactes vous concernant</p>
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
                                <p class="text-muted small mb-0">Demander la suppression de vos données</p>
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
                                <p class="text-muted small mb-0">Restreindre le traitement de vos données</p>
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
                                <p class="text-muted small mb-0">Recevoir vos données dans un format structuré</p>
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
                                <p class="text-muted small mb-0">S'opposer au traitement de vos données</p>
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
                                <p class="text-muted small mb-0">Retirer votre consentement à tout moment</p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-info-circle text-primary"></i>
                                    </div>
                                    <h4 class="h6 mb-0">Droit de réclamation</h4>
                                </div>
                                <p class="text-muted small mb-0">Déposer une plainte auprès de la CNIL</p>
                            </div>
                        </article>
                    </div>
                </div>

                <p class="text-center mb-5">
                    Pour exercer ces droits, veuillez nous contacter aux coordonnées indiquées à la fin de ce document.
                </p>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-cogs text-primary fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">9. Gestion des Cookies</h3>
                        </div>
                        <p class="mb-3">
                            Vous pouvez gérer vos préférences en matière de cookies en modifiant les paramètres de votre 
                            navigateur pour bloquer ou supprimer les cookies.
                        </p>
                        <div class="alert alert-info mb-0">
                            <p class="mb-0">
                                <strong>Note :</strong> La désactivation de certains cookies peut affecter la fonctionnalité 
                                de notre site et limiter votre capacité à utiliser certaines fonctionnalités.
                            </p>
                        </div>
                    </div>
                </article>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-lock text-success fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">10. Sécurité des Données</h3>
                        </div>
                        <p class="mb-3">
                            Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos 
                            données personnelles contre la perte, l'accès non autorisé, la divulgation, l'altération et la 
                            destruction, notamment :
                        </p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-key text-primary me-2"></i>
                                    <p class="mb-0 small">Chiffrement des données sensibles</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-shield text-primary me-2"></i>
                                    <p class="mb-0 small">Contrôles d'accès stricts</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-database text-primary me-2"></i>
                                    <p class="mb-0 small">Formation sur la protection des données</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-user-clock text-warning fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">11. Conservation des Données</h3>
                        </div>
                        <p class="mb-3">
                            Nous conservons vos données personnelles aussi longtemps que nécessaire pour atteindre les finalités 
                            pour lesquelles elles ont été collectées, sauf si une période de conservation plus longue est requise 
                            ou permise par la loi.
                        </p>
                        <p class="mb-3">
                            Les critères utilisés pour déterminer nos délais de conservation incluent :
                        </p>
                        <ul class="mb-0">
                            <li class="mb-2">La durée pendant laquelle nous entretenons une relation avec vous</li>
                            <li class="mb-2">Nos obligations légales</li>
                            <li class="mb-2">Les recommandations des autorités de protection des données</li>
                            <li>Les délais de prescription applicables</li>
                        </ul>
                    </div>
                </article>

                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-globe-europe text-info fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">12. Transferts Internationaux de Données</h3>
                        </div>
                        <p class="mb-3">
                            Vos données personnelles peuvent être transférées et traitées dans des pays autres que celui dans 
                            lequel vous résidez. Ces pays peuvent avoir des lois différentes sur la protection des données.
                        </p>
                        <p class="mb-3">
                            Lorsque nous transférons des données vers des pays en dehors de l'Espace Économique Européen, nous 
                            nous assurons qu'un niveau de protection adéquat est en place, conformément aux exigences du RGPD, 
                            par l'utilisation de :
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <p class="mb-0 small">Décisions d'adéquation de la Commission européenne</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <p class="mb-0 small">Clauses contractuelles types</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <p class="mb-0 small">Règles d'entreprise contraignantes</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <p class="mb-0 small">Autres mécanismes de transfert approuvés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-bell text-primary fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">13. Modifications de la Politique</h3>
                        </div>
                        <p class="mb-3">
                            Nous pouvons mettre à jour cette politique de temps à autre pour refléter les changements dans nos 
                            pratiques ou pour d'autres raisons opérationnelles, légales ou réglementaires. Nous vous encourageons 
                            à consulter régulièrement cette page pour rester informé des changements.
                        </p>
                        <p class="mb-0">
                            En cas de modifications substantielles, nous vous en informerons par email ou par une notification 
                            visible sur notre site.
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
                    Si vous avez des questions, des préoccupations ou des demandes concernant cette politique ou le 
                    traitement de vos données personnelles, veuillez nous contacter.
                </p>
                <p class="mb-4">
                    Si vous n'êtes pas satisfait de notre réponse, vous avez le droit de déposer une plainte auprès de 
                    la Commission Nationale de l'Informatique et des Libertés (CNIL) ou de toute autre autorité de 
                    protection des données compétente.
                </p>
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>
                    Contactez-nous
                </a>
            </div>
        </div>
    </div>
</section>

@endsection