@extends('layouts.public')

@section('title', 'Déclaration d\'Accessibilité')

@section('content')

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-universal-access me-3"></i>
                    Déclaration d'Accessibilité
                </h1>
                <p class="lead mb-0">
                    Chez DIGITALSOS, nous nous engageons à rendre notre site web accessible à tous les utilisateurs, 
                    quelles que soient leurs capacités ou leur situation de handicap.
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                     style="width: 200px; height: 200px;">
                    <i class="fas fa-wheelchair" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Notre engagement -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="fw-bold mb-4">Notre Engagement</h2>
                <p class="lead text-muted">
                    Nous nous efforçons de respecter les normes WCAG (Web Content Accessibility Guidelines) 2.1 
                    de niveau AA. Ces directives internationales aident à rendre le contenu web plus accessible 
                    aux personnes handicapées.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Navigation et Structure -->
            <div class="col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-code text-info fa-2x"></i>
                            </div>
                            <h3 class="h5 mb-0">Navigation et Structure</h3>
                        </div>
                        <ul class="mb-0">
                            <li>Structure du contenu avec des éléments HTML sémantiques appropriés</li>
                            <li>Navigation cohérente à travers le site</li>
                            <li>Titres hiérarchiques pour faciliter la navigation avec les lecteurs d'écran</li>
                            <li>Liens explicites avec des descriptions claires de leur destination</li>
                        </ul>
                    </div>
                </article>
            </div>

            <!-- Présentation Visuelle -->
            <div class="col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-eye text-warning fa-2x"></i>
                            </div>
                            <h3 class="h5 mb-0">Présentation Visuelle</h3>
                        </div>
                        <ul class="mb-0">
                            <li>Contraste de couleur suffisant entre le texte et l'arrière-plan</li>
                            <li>Design responsive qui s'adapte à différentes tailles d'écran</li>
                            <li>Aucune information transmise uniquement par la couleur</li>
                            <li>Évitement des contenus clignotants qui pourraient causer des crises d'épilepsie</li>
                        </ul>
                    </div>
                </article>
            </div>

            <!-- Contenu Non Textuel -->
            <div class="col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-desktop text-success fa-2x"></i>
                            </div>
                            <h3 class="h5 mb-0">Contenu Non Textuel</h3>
                        </div>
                        <ul class="mb-0">
                            <li>Textes alternatifs pour toutes les images informatives</li>
                            <li>Sous-titres pour les contenus vidéo</li>
                            <li>Transcriptions pour les contenus audio</li>
                            <li>Descriptions détaillées pour les graphiques et les diagrammes complexes</li>
                        </ul>
                    </div>
                </article>
            </div>

            <!-- Formulaires et Interaction -->
            <div class="col-md-6">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-keyboard text-danger fa-2x"></i>
                            </div>
                            <h3 class="h5 mb-0">Formulaires et Interaction</h3>
                        </div>
                        <ul class="mb-0">
                            <li>Étiquettes explicites pour tous les champs de formulaire</li>
                            <li>Messages d'erreur clairs et instructions pour corriger les erreurs</li>
                            <li>Temps suffisant pour lire et utiliser le contenu</li>
                            <li>Possibilité de naviguer et de soumettre les formulaires au clavier</li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Technologies d'assistance -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="fw-bold mb-4">Technologies d'Assistance Compatibles</h2>
                <p class="lead text-muted">
                    Notre site est conçu pour être compatible avec un large éventail de technologies d'assistance
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="row g-4">
                    <!-- Lecteurs d'écran -->
                    <div class="col-md-6">
                        <article class="d-flex">
                            <div class="flex-shrink-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 56px; height: 56px;">
                                <i class="fas fa-headphones-alt"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-2">Lecteurs d'écran</h3>
                                <p class="text-muted mb-0 small">
                                    Notre site est conçu pour fonctionner avec JAWS, NVDA, VoiceOver et TalkBack, 
                                    permettant aux personnes malvoyantes de naviguer facilement.
                                </p>
                            </div>
                        </article>
                    </div>

                    <!-- Logiciels de grossissement -->
                    <div class="col-md-6">
                        <article class="d-flex">
                            <div class="flex-shrink-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 56px; height: 56px;">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-2">Logiciels de grossissement</h3>
                                <p class="text-muted mb-0 small">
                                    Compatibilité avec les logiciels qui agrandissent l'écran, facilitant la lecture 
                                    pour les personnes ayant une vision partielle.
                                </p>
                            </div>
                        </article>
                    </div>

                    <!-- Reconnaissance vocale -->
                    <div class="col-md-6">
                        <article class="d-flex">
                            <div class="flex-shrink-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 56px; height: 56px;">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-2">Reconnaissance vocale</h3>
                                <p class="text-muted mb-0 small">
                                    Support des logiciels de reconnaissance vocale pour permettre aux utilisateurs 
                                    de naviguer sans clavier ni souris.
                                </p>
                            </div>
                        </article>
                    </div>

                    <!-- Alternatives de saisie -->
                    <div class="col-md-6">
                        <article class="d-flex">
                            <div class="flex-shrink-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 56px; height: 56px;">
                                <i class="fas fa-keyboard"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-2">Alternatives de saisie</h3>
                                <p class="text-muted mb-0 small">
                                    Compatibilité avec les alternatives au clavier et à la souris pour les personnes 
                                    à mobilité réduite.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Limitations et améliorations -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="row g-4">
                    <!-- Limitations Connues -->
                    <div class="col-md-6">
                        <article class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
                                    </div>
                                    <h3 class="h5 mb-0">Limitations Connues</h3>
                                </div>
                                <p class="text-muted mb-3">
                                    Malgré nos efforts, certaines parties de notre site peuvent ne pas être pleinement accessibles. 
                                    Nous travaillons activement à améliorer ces aspects :
                                </p>
                                <ul class="mb-0">
                                    <li>Certains contenus tiers intégrés peuvent ne pas respecter les mêmes normes d'accessibilité</li>
                                    <li>Certaines visualisations de données complexes peuvent être difficiles à percevoir pour les utilisateurs de lecteurs d'écran</li>
                                </ul>
                            </div>
                        </article>
                    </div>

                    <!-- Amélioration Continue -->
                    <div class="col-md-6">
                        <article class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-check-circle text-success fs-4"></i>
                                    </div>
                                    <h3 class="h5 mb-0">Amélioration Continue</h3>
                                </div>
                                <p class="text-muted mb-3">
                                    Notre site est régulièrement testé pour l'accessibilité en utilisant :
                                </p>
                                <ul class="mb-3">
                                    <li>Des outils automatisés de vérification d'accessibilité</li>
                                    <li>Des tests d'utilisabilité avec des personnes handicapées</li>
                                    <li>Des évaluations d'experts en accessibilité</li>
                                </ul>
                                <p class="mb-0">
                                    Nous nous engageons à améliorer continuellement l'accessibilité de notre site en fonction 
                                    des retours des utilisateurs et des évolutions des normes.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conformité -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container-lg py-3">
        <h2 class="mb-4 fw-bold">Conformité</h2>
        <p class="lead mb-3 mx-auto" style="max-width: 700px;">
            Cette déclaration d'accessibilité a été établie le {{ now()->format('d/m/Y') }} 
            et est régulièrement mise à jour.
        </p>
        <p class="mx-auto mb-4" style="max-width: 700px;">
            Nous nous engageons à respecter les exigences légales en matière d'accessibilité numérique 
            conformément à la directive européenne 2016/2102 et aux réglementations nationales applicables.
        </p>
        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
            <i class="fas fa-hands-helping me-2"></i>
            Nous signaler un problème d'accessibilité
        </a>
    </div>
</section>

@endsection