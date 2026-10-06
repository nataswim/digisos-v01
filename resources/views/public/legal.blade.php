@extends('layouts.public')

@section('title', 'Mentions légales')
@section('meta_description', 'Mentions légales du site du Cercle des Nageurs du Bocage Bressuirais (CNBB) : éditeur, directeur de la publication, hébergeur, propriété intellectuelle et droit à l\'image.')

@section('content')

@php
    // Date de dernière modification du texte : à changer à la main quand le contenu évolue.
    $miseAJour = '6 octobre 2026';

    // À COMPLÉTER : la loi (LCEN, art. 6) impose le nom, l'adresse et le téléphone de l'hébergeur
    // réellement utilisé. Indiquez un seul hébergeur ; les lignes vides ne sont pas affichées.
    $hebergeur = [
        'nom'       => 'O2Switch - HOSTINGER',
        'adresse'   => null,
        'telephone' => null,
        'site'      => null,
    ];
@endphp


<!-- En-tête -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-gavel me-3" aria-hidden="true"></i>Mentions légales
                </h1>
                <p class="lead mb-3">
                    Les informations légales du site du Cercle des Nageurs du Bocage Bressuirais, conformément
                    à la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique.
                </p>
                <p class="mb-0 opacity-75">Dernière mise à jour : {{ $miseAJour }}</p>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                     style="width: 200px; height: 200px;">
                    <i class="fas fa-balance-scale" style="font-size: 5rem;" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Éditeur du site -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-swimmer text-info fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Éditeur du site</h2>
                        </div>
                        <div class="card p-4 bg-light border-0">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Cercle des Nageurs du Bocage Bressuirais (CNBB)</strong></p>
                                    <p class="mb-1">Association sportive, affiliée à la Fédération Française de Natation</p>
                                    <p class="mb-1">Fondée le 22 mars 1954 — Journal officiel du 12 mai 1954, n° 843</p>
                                    <address class="mb-0">
                                        40 boulevard de la République<br>
                                        79300 Bressuire
                                    </address>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <strong>E-mail :</strong>
                                        <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Téléphone :</strong>
                                        <a href="tel:+33602350843">06 02 35 08 43</a>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Directeur de la publication :</strong>
                                        Sébastien CHEVALIER, président du club
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Conception -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-laptop-code text-primary fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Conception et réalisation</h2>
                        </div>
                        <div class="card p-4 bg-light border-0">
                            <p class="mb-1"><strong>SNS</strong> — Med H EL HAOUAT</p>
                            <p class="mb-0">
                                <strong>Contact technique :</strong>
                                <a href="mailto:natation.swimming@gmail.com">natation.swimming@gmail.com</a>
                            </p>
                        </div>
                    </div>
                </article>

                <!-- Hébergeur -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-server text-success fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Hébergeur</h2>
                        </div>
                        <div class="card p-4 bg-light border-0">
                            <p class="mb-1"><strong>{{ $hebergeur['nom'] }}</strong></p>
                            @if ($hebergeur['adresse'])
                                <p class="mb-1">{{ $hebergeur['adresse'] }}</p>
                            @endif
                            @if ($hebergeur['telephone'])
                                <p class="mb-1"><strong>Téléphone :</strong> {{ $hebergeur['telephone'] }}</p>
                            @endif
                            @if ($hebergeur['site'])
                                <p class="mb-0"><strong>Site :</strong> {{ $hebergeur['site'] }}</p>
                            @endif
                        </div>
                    </div>
                </article>

                <!-- Propriété intellectuelle -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-copyright text-warning fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Propriété intellectuelle</h2>
                        </div>
                        <p class="mb-3">
                            Les textes, logos, photographies, vidéos et documents publiés sur ce site appartiennent
                            au CNBB ou à leurs auteurs, et sont protégés par le droit de la propriété intellectuelle.
                        </p>
                        <p class="mb-0">
                            Vous pouvez partager un lien vers une page du site librement. En revanche, la reprise
                            d'un contenu (texte, photo, vidéo, document) sur un autre support nécessite l'accord
                            préalable du club : <a href="{{ route('contact') }}">contactez-nous</a>.
                        </p>
                    </div>
                </article>

                <!-- Droit à l'image -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-camera text-danger fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Photos et vidéos : droit à l'image</h2>
                        </div>
                        <p class="mb-3">
                            Le site présente des photos et des vidéos des entraînements, des compétitions et de la vie du club.
                        </p>
                        <p class="mb-0">
                            Si vous, ou votre enfant, apparaissez sur une image et souhaitez son retrait, écrivez-nous à
                            <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a> en indiquant la page concernée :
                            l'image sera retirée dans les meilleurs délais.
                        </p>
                    </div>
                </article>

                <!-- Liens -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-link text-primary fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Liens vers d'autres sites</h2>
                        </div>
                        <p class="mb-0">
                            Le site peut contenir des liens vers d'autres sites (fédération, comités, partenaires,
                            résultats de compétitions). Le CNBB ne maîtrise pas le contenu de ces sites et ne peut
                            en être tenu responsable.
                        </p>
                    </div>
                </article>

                <!-- Responsabilité -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-exclamation-triangle text-secondary fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Exactitude des informations</h2>
                        </div>
                        <p class="mb-3">
                            Le club s'efforce de tenir à jour les informations publiées (horaires, tarifs, dates
                            de compétitions, modalités d'inscription). Elles peuvent toutefois évoluer en cours de saison :
                            en cas de doute, la réponse du club fait foi.
                        </p>
                        <p class="mb-0">
                            Le CNBB ne peut être tenu responsable d'une interruption du site, d'une erreur ou d'une omission,
                            ni des dommages résultant de l'intrusion frauduleuse d'un tiers.
                        </p>
                    </div>
                </article>

                <!-- Données personnelles -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-user-shield text-info fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Données personnelles et cookies</h2>
                        </div>
                        <p class="mb-0">
                            La collecte et l'utilisation de vos données sont expliquées dans notre
                            <a href="{{ route('privacy') }}">politique de confidentialité</a>
                            et notre <a href="{{ route('cookies') }}">politique de cookies</a>.
                        </p>
                    </div>
                </article>

                <!-- Accessibilité -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-universal-access text-primary fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Accessibilité</h2>
                        </div>
                        <p class="mb-0">
                            Nos engagements et les limites connues du site sont décrits dans notre
                            <a href="{{ route('accessibility') }}">déclaration d'accessibilité</a>.
                        </p>
                    </div>
                </article>

                <!-- Droit applicable -->
                <article class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-gavel text-secondary fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">Droit applicable</h2>
                        </div>
                        <p class="mb-0">
                            Les présentes mentions légales sont régies par le droit français. Elles peuvent être
                            modifiées à tout moment ; la date de dernière mise à jour figure en haut de cette page.
                        </p>
                    </div>
                </article>

            </div>
        </div>
    </div>
</section>


<!-- Contact -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container-lg py-3">
        <h2 class="mb-4 fw-bold">Une question ?</h2>
        <p class="lead mb-4 mx-auto" style="max-width: 700px;">
            Pour toute question sur ces mentions légales ou sur le site, écrivez-nous.
        </p>
        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
            <i class="fas fa-envelope me-2" aria-hidden="true"></i>Contacter le club
        </a>
    </div>
</section>

@endsection
