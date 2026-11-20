@extends('layouts.public')

@section('title', 'Inscription au CNBB : tarifs, pièces du dossier et modalités de règlement')
@section('meta_description', 'Inscription au CNBB pour la saison  : tarifs de l\'école de natation, des jeunes, des étudiants et des adultes, remises famille, pièces du dossier et modalités de règlement.')

@section('content')

@php
    $saison = '2026-2027';

    // Activités proposées : le montant de l'adhésion dépend de l'activité (détail dans les fiches)
    $tarifs = [
        ['icon' => 'fa-child',         'couleur' => 'info',      'titre' => 'École de natation',            'detail' => 'Apprentissage et perfectionnement des nages'],
        ['icon' => 'fa-swimmer',       'couleur' => 'primary',   'titre' => 'Jeunes',                       'detail' => '8 à 25 ans'],
        ['icon' => 'fa-user-graduate', 'couleur' => 'success',   'titre' => 'Étudiants stagiaires',         'detail' => 'Sous conditions'],
        ['icon' => 'fa-users',         'couleur' => 'warning',   'titre' => 'Maîtres',                      'detail' => '25 ans et plus'],
        ['icon' => 'fa-trophy',        'couleur' => 'danger',    'titre' => 'Maîtres ligue Arc Atlantique', 'detail' => 'Maîtres engagés avec la ligue Arc Atlantique'],
    ];

    // Fiches « Inscription & adhésion »
    $fiches = [
        ['titre' => "Le dossier d'inscription : les pièces à fournir", 'texte' => "La liste complète des pièces à rassembler et l'endroit où déposer le dossier."],
        ['titre' => 'Tarifs et réductions de la saison',              'texte' => 'Le montant de la cotisation selon le groupe, les réductions familles et la majoration de retard.'],
        ['titre' => 'Certificat médical et attestation de santé',     'texte' => 'Quand fournir un certificat médical, et quand une simple attestation suffit.'],
        ['titre' => 'Les moyens de paiement',                         'texte' => 'Espèces, chèques en plusieurs fois, virement, coupons sport et chèques-vacances ANCV.'],
    ];

    // Documents d'inscription à télécharger
    $documents = [
        'Charte du club (Charte du Nageur)',
        "Fiche d'inscription",
        'Fiche sanitaire de liaison',
        "Modalités d'inscription",
        'Questionnaire de santé',
        'Règlement intérieur',
    ];

    // Pièces du dossier
    $pieces = [
        [
            'titre' => 'La fiche d\'inscription',
            'texte' => 'Dûment remplie. Pour les compétiteurs, ajoutez la fiche sanitaire de liaison.',
        ],
        [
            'titre' => 'Le règlement de l\'adhésion',
            'texte' => 'En espèces, par chèque(s) ou par virement. Le détail est indiqué plus bas.',
        ],
        [
            'titre' => 'Le certificat médical',
            'texte' => 'Obligatoire lors de la première adhésion. Il est à renouveler tous les 3 ans pour les adultes. Pour les mineurs, une attestation CERFA 15699-01 le remplace.',
        ],
        [
            'titre' => 'Une photo d\'identité',
            'texte' => 'Uniquement pour les nouveaux adhérents.',
        ],
        [
            'titre' => 'Un chèque de caution de 5 €',
            'texte' => 'Pour le badge d\'entrée. Il n\'est débité qu\'en cas de perte de la carte.',
        ],
    ];
@endphp


<x-public.hero
    :title="'Inscription CNBB '"
    :video="false"
    eyebrow="Le club"
    title-class="display-5"
    lead="Tarifs, conditions et pièces à fournir pour rejoindre le Cercle des Nageurs du Bocage Bressuirais.">
    <a href="#tarifs" class="btn btn-primary btn-lg">
        <i class="fas fa-euro-sign me-2" aria-hidden="true"></i>Voir les tarifs
    </a>
    <a href="#dossier" class="btn btn-secondary btn-lg">
        <i class="fas fa-folder-open me-2" aria-hidden="true"></i>Préparer mon dossier
    </a>
</x-public.hero>


<!-- Conditions d'accès -->
<section id="conditions" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Avant de vous inscrire</h2>
            <p class="lead text-muted">Qui peut rejoindre le club ?</p>
        </header>

        <div class="row g-4">
            <div class="col-md-4">
                <article class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="tarif-icone rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="--tarif: var(--bs-primary); width: 80px; height: 80px;">
                            <i class="fas fa-swimmer fa-2x" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3">Savoir nager 25 mètres</h3>
                        <p class="text-muted mb-0">
                            L'inscription est réservée aux adultes et aux enfants sachant nager au moins 25 m.
                        </p>
                    </div>
                </article>
            </div>

            <div class="col-md-4">
                <article class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="tarif-icone rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="--tarif: var(--bs-success); width: 80px; height: 80px;">
                            <i class="fas fa-stopwatch fa-2x" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3">Une évaluation du niveau</h3>
                        <p class="text-muted mb-0">
                            Lors de la première adhésion, le club se réserve le droit d'évaluer le niveau de pratique.
                            Pour l'école de natation, un entraîneur évalue les enfants dès début septembre afin de confirmer
                            ou non l'inscription et de les orienter vers le groupe le plus adapté.
                        </p>
                    </div>
                </article>
            </div>

            <div class="col-md-4">
                <article class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="tarif-icone rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="--tarif: var(--bs-info); width: 80px; height: 80px;">
                            <i class="fas fa-life-ring fa-2x" aria-hidden="true"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3">Pour apprendre à nager</h3>
                        <p class="text-muted mb-0">
                            Le centre aquatique Cœur d'O propose des créneaux d'apprentissage aux enfants et aux adultes.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>


<!-- Tarifs -->
<section id="tarifs" class="anchor-section py-5 bg-light">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Tarifs</h2>
            <p class="lead text-muted">Adhésion pour une saison</p>
        </header>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-4">
            @foreach ($tarifs as $tarif)
                <div class="col">
                    <article class="card border-0 shadow-sm h-100 text-center tarif-card" style="--tarif: var(--bs-{{ $tarif['couleur'] }});">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="tarif-icone rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto">
                                <i class="fas {{ $tarif['icon'] }} fa-2x" aria-hidden="true"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-2">{{ $tarif['titre'] }}</h3>
                            <p class="text-muted small flex-grow-1">{{ $tarif['detail'] }}</p>
                            <p class="fw-semibold mb-0 tarif-prix">Adhésion selon l'activité</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="row g-4 mt-1">
            {{-- Fiches Inscription & adhésion --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 border-top border-primary border-4">
                    <div class="card-body p-4 d-flex flex-column">
                        <h3 class="h5 fw-bold mb-3">
                            <i class="fas fa-book-open text-primary me-2" aria-hidden="true"></i>Inscription &amp; adhésion : les fiches
                        </h3>
                        <ul class="list-unstyled mb-4 flex-grow-1">
                            @foreach ($fiches as $fiche)
                                <li class="d-flex mb-3">
                                    <i class="fas fa-file-alt text-primary me-3 mt-1" aria-hidden="true"></i>
                                    <span><strong>{{ $fiche['titre'] }}</strong><br><span class="text-muted small">{{ $fiche['texte'] }}</span></span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ url('/fiches/inscription-adhesion') }}" class="btn btn-primary align-self-start">
                            <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Lire les fiches Inscription &amp; adhésion
                        </a>
                    </div>
                </div>
            </div>

            {{-- Documents à télécharger --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 border-top border-success border-4">
                    <div class="card-body p-4 d-flex flex-column">
                        <h3 class="h5 fw-bold mb-3">
                            <i class="fas fa-file-pdf text-danger me-2" aria-hidden="true"></i>Documents d'inscription (PDF)
                        </h3>
                        <ul class="list-unstyled mb-4 flex-grow-1">
                            @foreach ($documents as $document)
                                <li class="d-flex mb-2">
                                    <i class="fas fa-file-pdf text-danger me-3 mt-1" aria-hidden="true"></i>
                                    <span>{{ $document }} <span class="text-muted small">— saison {{ $saison }}</span></span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ url('/ebook/documents-inscription') }}" class="btn btn-success align-self-start">
                            <i class="fas fa-download me-2" aria-hidden="true"></i>Télécharger les documents
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Dossier d'inscription -->
<section id="dossier" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Votre dossier d'inscription</h2>
            <p class="lead text-muted">Les pièces à réunir pour un dossier complet</p>
        </header>

        <div class="row g-4 g-lg-5">
            <div class="col-lg-7">
                @foreach ($pieces as $index => $piece)
                    <article class="d-flex align-items-start mb-4">
                        <div class="inscription-numero bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <h3 class="h5 fw-bold mb-1">{{ $piece['titre'] }}</h3>
                            <p class="text-muted mb-0">{{ $piece['texte'] }}</p>
                        </div>
                    </article>
                @endforeach

                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-start mb-0" role="alert">
                    <i class="fas fa-ban fa-lg me-3 mt-1"></i>
                    <div>
                        <strong>Tout dossier incomplet sera refusé</strong> et entraînera le refus de l'accès au bassin.
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold mb-3">
                            <i class="fas fa-credit-card text-primary me-2"></i>Régler l'adhésion
                        </h3>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex mb-3">
                                <i class="fas fa-money-bill-wave text-success me-3 mt-1"></i>
                                <span><strong>En espèces.</strong></span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="fas fa-money-check text-success me-3 mt-1"></i>
                                <span>
                                    <strong>Par chèque,</strong> en 1, 2, 3 ou 4 fois.
                                    Les chèques sont encaissables au 15/09, 15/10, 15/11 et 15/12.
                                </span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="fas fa-university text-success me-3 mt-1"></i>
                                <span>
                                    <strong>Par virement,</strong> en précisant le nom et le prénom de l'adhérent.
                                </span>
                            </li>
                            <li class="d-flex">
                                <i class="fas fa-ticket-alt text-success me-3 mt-1"></i>
                                <span>
                                    <strong>Coupons sport ou chèques ANCV :</strong> justificatif à nous joindre
                                    avant le 30 octobre (chèque de caution qui sera encaissé).
                                </span>
                            </li>
                        </ul>
                        <p class="text-muted small mt-3 mb-0">
                            En cas de difficultés, contactez-nous par e-mail :
                            <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>
                        </p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm border-top border-primary border-4">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold mb-3">
                            <i class="fas fa-inbox text-primary me-2"></i>Où déposer le dossier ?
                        </h3>
                        <p class="mb-0">
                            Les dossiers complets sont à déposer dans la <strong>boîte aux lettres du club</strong>,
                            derrière l'abribus en bas de Cœur d'O.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Bon à savoir -->
<section id="bon-a-savoir" class="anchor-section py-5 bg-light">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Bon à savoir</h2>
        </header>

        <div class="row g-4">
            <div class="col-md-6">
                <article class="d-flex align-items-start">
                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar-alt fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-2">Congés scolaires</h3>
                        <p class="text-muted mb-0">
                            Les activités du club sont interrompues durant les vacances scolaires.
                            Les éventuels stages organisés pendant ces périodes font l'objet d'informations.
                        </p>
                    </div>
                </article>
            </div>

            <div class="col-md-6">
                <article class="d-flex align-items-start">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-info fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-2">Limite des interventions du club</h3>
                        <p class="text-muted mb-2">
                            Le club ne peut être tenu responsable du fonctionnement des installations mises à disposition
                            par l'Agglo 2B, ni des vols pouvant être commis dans l'enceinte de ces installations.
                        </p>
                        <p class="text-muted mb-0">
                            Au cours de l'année, les bassins peuvent être mis à disposition par l'Agglo 2B pour des manifestations,
                            ou fermés pour problèmes techniques ou sanitaires, entretien ou réparations. Le club ne peut être tenu
                            pour responsable de la suppression d'activité, et aucune remise de quote-part de cotisation ne sera accordée.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>


<!-- Appel aux parents -->
<section id="benevoles" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm tarif-fond" style="--tarif: var(--bs-success);">
                    <div class="card-body p-4 p-lg-5 text-center">
                        <i class="fas fa-hands-helping text-success fa-3x mb-3"></i>
                        <h2 class="h3 fw-bold mb-3">Parents, ce message vous concerne</h2>
                        <p class="mb-4">
                            Vos enfants aiment nager, vous êtes fiers d'eux, vous aimez les accompagner lors des compétitions…
                            et vous pouvez y participer ! Devenez officiel ou investissez-vous dans le Comité directeur :
                            c'est très facile. N'hésitez pas à vous renseigner.
                        </p>
                        <a href="{{ route('contact') }}" class="btn btn-success">
                            <i class="fas fa-envelope me-2"></i>Je me renseigne
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Informations du club -->
<section id="informations" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <p class="text-uppercase fw-semibold text-primary small mb-2">Le Club</p>
            <h2 class="display-6 fw-bold mb-3">Informations du club</h2>
            <p class="lead text-muted mx-auto" style="max-width: 52rem;">
                Retrouvez ici toutes les informations pratiques nécessaires au bon déroulement de votre saison au club.
                Que vous soyez nouvel adhérent ou nageur confirmé, cette rubrique rassemble les éléments essentiels pour vous guider.
            </p>
        </header>

        @if ($categories->isNotEmpty())
            <h3 class="h5 fw-bold mb-3">Les informations, par thème</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
                @foreach ($categories as $categorie)
                    <div class="col">
                        <a href="{{ route('public.pages.category', $categorie) }}" class="card border-0 shadow-sm h-100 text-decoration-none info-theme">
                            <div class="card-body p-4">
                                <span class="badge rounded-pill bg-light text-primary border mb-2">
                                    {{ $categorie->published_pages_count }} {{ $categorie->published_pages_count > 1 ? 'pages' : 'page' }}
                                </span>
                                <h4 class="h5 fw-bold text-dark mb-2">
                                    {{ $categorie->name }}<i class="fas fa-arrow-right ms-2 small text-primary" aria-hidden="true"></i>
                                </h4>
                                @if ($categorie->description)
                                    <p class="text-muted small mb-0">{{ $categorie->description }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="text-center">
            <a href="{{ route('public.pages.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-info-circle me-2" aria-hidden="true"></i>Toutes les informations du club
            </a>
        </div>
    </div>
</section>


<!-- Boutique du club -->
<section id="boutique" class="anchor-section py-5 bg-light">
    <div class="container-lg">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4 align-items-center">
                    <div class="col-md-auto text-center">
                        <div class="tarif-icone rounded-circle d-inline-flex align-items-center justify-content-center" style="--tarif: var(--bs-primary);">
                            <i class="fas fa-shopping-bag fa-2x" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="col-md">
                        <h2 class="h3 fw-bold mb-2">La boutique du club</h2>
                        <p class="text-muted mb-0">
                            Textile, matériel et accessoires aux couleurs du CNBB. Les prix sont indicatifs :
                            la commande se fait sur le site indiqué pour chaque produit.
                        </p>
                    </div>
                    <div class="col-md-auto">
                        <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-store me-2" aria-hidden="true"></i>Voir la boutique
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Contact -->
<section class="py-5 bg-primary text-white">
    <div class="container-lg text-center">
        <h2 class="display-6 fw-bold mb-3">Une question sur votre inscription ?</h2>
        <p class="lead mb-4">
            <a href="mailto:cnbb079@gmail.com" class="text-white">cnbb079@gmail.com</a>
            <span class="mx-2">·</span>
            <a href="tel:+33602350843" class="text-white">06 02 35 08 43</a>
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                <i class="fas fa-envelope me-2"></i>Contacter le club
            </a>
            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-water me-2"></i>Découvrir le club
            </a>
        </div>
    </div>
</section>

@endsection


@push('styles')
<style>
    /* Pastilles numérotées des pièces du dossier */
    .inscription-numero {
        width: 48px;
        height: 48px;
        font-size: 1.2rem;
        font-weight: 700;
    }

    /* Pastilles des activités et de la boutique : fond clair teinté de la couleur de l'activité */
    .tarif-icone {
        width: 70px;
        height: 70px;
        color: var(--tarif);
        background: color-mix(in srgb, var(--tarif) 12%, #fff);
    }
    .tarif-card { border-top: 4px solid var(--tarif) !important; }
    .tarif-prix { color: var(--tarif); }
    .info-theme { border-top: 4px solid var(--bs-primary) !important; transition: transform .2s, box-shadow .2s; }
    .info-theme:hover, .info-theme:focus { transform: translateY(-3px); box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08) !important; }
    .tarif-fond { background: color-mix(in srgb, var(--tarif) 10%, #fff) !important; }
</style>
@endpush