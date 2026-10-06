@extends('layouts.public')

@section('title', 'Déclaration d\'accessibilité')
@section('meta_description', 'Accessibilité du site du CNBB, club de natation de Bressuire : ce que nous faisons, les limites connues et comment nous signaler une difficulté.')

@section('content')

@php
    // Date d'établissement de la déclaration : à changer à la main lors d'une révision.
    $dateDeclaration = '6 octobre 2026';

    // Ce que nous faisons
    $engagements = [
        [
            'icon'    => 'fa-sitemap',
            'couleur' => 'info',
            'titre'   => 'Structure et navigation',
            'points'  => [
                'Des titres hiérarchisés pour se repérer avec un lecteur d\'écran',
                'Un menu identique sur toutes les pages',
                'Des liens dont le texte indique la destination',
                'Un lien « Aller au contenu » en début de page et un fil d\'Ariane sur les pages intérieures',
            ],
        ],
        [
            'icon'    => 'fa-eye',
            'couleur' => 'warning',
            'titre'   => 'Lisibilité',
            'points'  => [
                'Un affichage qui s\'adapte au téléphone, à la tablette et à l\'ordinateur',
                'Un texte qui reste lisible lorsque vous agrandissez la page',
                'Aucune information transmise par la seule couleur',
                'Aucun contenu clignotant',
            ],
        ],
        [
            'icon'    => 'fa-film',
            'couleur' => 'success',
            'titre'   => 'Images et vidéos',
            'points'  => [
                'Les vidéos d\'en-tête sont décoratives, sans son, et ignorées par les lecteurs d\'écran',
                'Elles sont masquées si votre appareil est réglé sur « réduire les animations »',
                'Les icônes décoratives sont ignorées par les lecteurs d\'écran',
            ],
        ],
        [
            'icon'    => 'fa-keyboard',
            'couleur' => 'danger',
            'titre'   => 'Formulaires',
            'points'  => [
                'Chaque champ possède une étiquette explicite',
                'Les champs obligatoires sont signalés',
                'Les erreurs sont expliquées à côté du champ concerné',
                'Les formulaires s\'utilisent entièrement au clavier',
            ],
        ],
    ];

    // Limites connues
    $limites = [
        'Les vidéos de la rubrique Vidéos ne disposent pas toutes de sous-titres ou d\'une transcription.',
        'Certains documents à télécharger (PDF) ne sont pas structurés pour les lecteurs d\'écran.',
        'Des photos publiées dans les articles et les galeries peuvent ne pas avoir de description.',
        'La carte de la page Contact est fournie par Google Maps : son accessibilité ne dépend pas du club. L\'adresse du club est écrite en toutes lettres juste au-dessus.',
        'Les contenus sont rédigés par plusieurs personnes : la qualité des titres et des descriptions peut varier d\'une page à l\'autre.',
    ];
@endphp


<!-- En-tête -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-universal-access me-3" aria-hidden="true"></i>Déclaration d'accessibilité
                </h1>
                <p class="lead mb-0">
                    Le Cercle des Nageurs du Bocage Bressuirais souhaite que son site soit utilisable par tous,
                    quels que soient le matériel utilisé et la situation de handicap.
                </p>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                     style="width: 200px; height: 200px;">
                    <i class="fas fa-hands-helping" style="font-size: 5rem;" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- État de conformité -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="fw-bold mb-4">Où en est le site ?</h2>
                <p class="lead text-muted mb-4">
                    Nous nous efforçons de suivre les bonnes pratiques des règles internationales d'accessibilité
                    (WCAG 2.1, niveau AA).
                </p>
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-0" role="note">
                    <i class="fas fa-info-circle fa-lg me-3 mt-1" aria-hidden="true"></i>
                    <div>
                        <strong>Le site n'a pas encore fait l'objet d'un audit de conformité.</strong>
                        Nous ne pouvons donc pas garantir que toutes les pages sont pleinement accessibles.
                        Les limites que nous connaissons sont listées plus bas, et vos signalements nous aident à progresser.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Ce que nous faisons -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="fw-bold mb-3">Ce que nous faisons</h2>
            <p class="lead text-muted">Les points auxquels nous veillons en construisant les pages</p>
        </header>

        <div class="row g-4">
            @foreach ($engagements as $engagement)
                <div class="col-md-6">
                    <article class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-{{ $engagement['couleur'] }} bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas {{ $engagement['icon'] }} text-{{ $engagement['couleur'] }} fa-2x" aria-hidden="true"></i>
                                </div>
                                <h3 class="h5 mb-0">{{ $engagement['titre'] }}</h3>
                            </div>
                            <ul class="mb-0">
                                @foreach ($engagement['points'] as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Limites connues et conseils -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row g-4">
            <div class="col-lg-7">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-exclamation-triangle text-warning fs-4" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Les limites que nous connaissons</h2>
                        </div>
                        <ul class="mb-0">
                            @foreach ($limites as $limite)
                                <li class="mb-2">{{ $limite }}</li>
                            @endforeach
                        </ul>
                    </div>
                </article>
            </div>

            <div class="col-lg-5">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-lightbulb text-success fs-4" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Quelques réglages utiles</h2>
                        </div>
                        <ul class="mb-0">
                            <li class="mb-2">
                                <strong>Agrandir le texte :</strong> touches <kbd>Ctrl</kbd> et <kbd>+</kbd>
                                (<kbd>Cmd</kbd> et <kbd>+</kbd> sur Mac).
                            </li>
                            <li class="mb-2">
                                <strong>Naviguer au clavier :</strong> touche <kbd>Tab</kbd> pour passer d'un lien au suivant,
                                <kbd>Entrée</kbd> pour valider.
                            </li>
                            <li>
                                <strong>Arrêter les animations :</strong> activez « réduire les animations » dans les réglages
                                d'accessibilité de votre appareil.
                            </li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>


<!-- Signaler une difficulté -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container-lg py-3">
        <h2 class="mb-4 fw-bold">Une page vous pose problème ?</h2>
        <p class="lead mb-3 mx-auto" style="max-width: 700px;">
            Dites-nous quelle page et quelle difficulté vous rencontrez : nous vous transmettrons l'information
            sous une autre forme et nous corrigerons le site dès que possible.
        </p>
        <p class="mb-4">
            <a href="mailto:cnbb079@gmail.com" class="text-white">cnbb079@gmail.com</a>
            <span class="mx-2" aria-hidden="true">·</span>
            <a href="tel:+33602350843" class="text-white">06 02 35 08 43</a>
        </p>
        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
            <i class="fas fa-hands-helping me-2" aria-hidden="true"></i>Signaler un problème d'accessibilité
        </a>
        <p class="mt-4 mb-0 small opacity-75">
            Déclaration établie le {{ $dateDeclaration }}.
        </p>
    </div>
</section>

@endsection
