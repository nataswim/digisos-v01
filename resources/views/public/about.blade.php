@extends('layouts.public')

@section('title', 'Le club — Présentation et historique')

@section('content')

@php
    $anneeCreation = 1954;
    $ageClub       = now()->year - $anneeCreation;

    // Les publics accueillis
    $publics = [
        [
            'icon'    => 'fa-child',
            'couleur' => 'info',
            'titre'   => 'École de natation',
            'texte'   => 'Les plus jeunes préparent les épreuves mises en place par la Fédération : Sauv\'nage, Pass\'sports de l\'eau et Pass\'compétition.',
        ],
        [
            'icon'    => 'fa-swimmer',
            'couleur' => 'primary',
            'titre'   => 'Natation course',
            'texte'   => 'C\'est le cœur de l\'activité du club : l\'entraînement et la compétition, du niveau départemental au niveau régional.',
        ],
        [
            'icon'    => 'fa-user-graduate',
            'couleur' => 'success',
            'titre'   => 'Jeunes et étudiants',
            'texte'   => 'Les jeunes nageurs et les étudiants s\'entraînent au club. Les jeunes sont aussi formés à l\'encadrement des plus petits.',
        ],
        [
            'icon'    => 'fa-users',
            'couleur' => 'warning',
            'titre'   => 'Adultes',
            'texte'   => 'Le club accueille les adultes sachant nager qui souhaitent pratiquer et progresser dans un cadre associatif.',
        ],
    ];

    // Les grandes dates
    $historique = [
        [
            'annee'   => '1954',
            'couleur' => 'primary',
            'titre'   => 'Naissance du Club Nautique Bressuirais',
            'texte'   => 'Le 22 mars, M. Robert TROUVE, entraîneur (M.N.S.), crée le club avec le Dr GELOT comme président, deux ans après la construction de la piscine municipale en 1952 (aujourd\'hui les bassins extérieurs). Le club est inscrit au Journal officiel le 12 mai sous le n° 843. Ses couleurs sont le vert et le blanc. La saison ne dure alors que quatre mois, de juin à septembre.',
        ],
        [
            'annee'   => '1963',
            'couleur' => 'success',
            'titre'   => 'Une école de sauvetage',
            'texte'   => 'Création d\'une école de sauvetage. Le club fête ses dix ans avec une équipe allemande.',
        ],
        [
            'annee'   => '1964',
            'couleur' => 'info',
            'titre'   => 'Reconnaissance d\'utilité publique',
            'texte'   => 'Le club est reconnu d\'utilité publique. Quatorze nageurs participent au stage régional espoir organisé à Bressuire.',
        ],
        [
            'annee'   => '1987',
            'couleur' => 'primary',
            'titre'   => 'Le CNB devient le CNBB',
            'texte'   => 'Le club prend le nom de Cercle des Nageurs du Bocage Bressuirais : les nageurs des cantons de Bressuire, Cerizay et Moncoutant font désormais partie de la même entité. La construction des bassins d\'hiver permet au club de retrouver le chemin des bassins du département et de la région.',
        ],
        [
            'annee'   => '1989',
            'couleur' => 'success',
            'titre'   => 'De nouvelles activités',
            'texte'   => 'En 1989-1990, le club crée l\'aquagym pour les adultes et les seniors. Les 12 heures de natation deviennent un moment fort de la saison.',
        ],
        [
            'annee'   => '1994',
            'couleur' => 'warning',
            'titre'   => 'Les 40 ans',
            'texte'   => 'Le club compte 504 adhérents. Une fête est organisée sur les bassins extérieurs.',
        ],
        [
            'annee'   => '1998',
            'couleur' => 'info',
            'titre'   => '580 adhérents',
            'texte'   => 'Le club atteint les 580 adhérents.',
        ],
        [
            'annee'   => '2001',
            'couleur' => 'primary',
            'titre'   => 'Natation synchronisée',
            'texte'   => 'Création de la section natation synchronisée avec Emmanuelle BABIN. Elle prend fin en 2011, les animatrices ne pouvant plus assurer les entraînements.',
        ],
        [
            'annee'   => '2004',
            'couleur' => 'success',
            'titre'   => 'Les 50 ans du CNBB',
            'texte'   => 'Deux jours d\'animation sur les bassins extérieurs : démonstration de natation synchronisée avec le club de Paris et par la section du club, spectacle de plongeons, match de water-polo entre Saint-Jean-d\'Angély et Angoulême, compétition réunissant les nageurs du département et les anciens du club. Le clou de la soirée : le feu d\'artifice.',
        ],
        [
            'annee'   => '2009',
            'couleur' => 'warning',
            'titre'   => 'La piscine devient Cœur d\'O',
            'texte'   => 'De nouveaux bassins ludiques ouvrent. Les activités d\'aquaform et de bébés nageurs (créée en 1996) sont reprises par la ville : le club perd de fait un grand nombre d\'adhérents.',
        ],
        [
            'annee'   => '2013',
            'couleur' => 'info',
            'titre'   => '1er Aquathlon du Bocage',
            'texte'   => 'Le 12 juillet, le CNBB met en place le premier Aquathlon du Bocage.',
        ],
        [
            'annee'   => '2016',
            'couleur' => 'primary',
            'titre'   => 'Un nouvel élan',
            'texte'   => 'Sur la saison 2015-2016, neuf nageurs accèdent au niveau régional et le club repasse la barre des 200 licenciés. L\'école de natation reprend son rythme, la formation des jeunes à l\'encadrement est relancée et deux sections voient le jour : le sport adapté, pour les jeunes en situation de handicap, et le triathlon.',
        ],
    ];

    // Les présidents depuis la création
    $presidents = [
        ['periode' => '1954 – 1967', 'nom' => 'Dr GELOT',                   'duree' => '13 ans'],
        ['periode' => '1968 – 1971', 'nom' => 'Dr GALLUCHON',               'duree' => '3 ans'],
        ['periode' => '1972 – 1977', 'nom' => 'M. MARILLEAU',               'duree' => '5 ans'],
        ['periode' => '1978 – 1996', 'nom' => 'M. TROUVE',                  'duree' => '18 ans'],
        ['periode' => '1996 – 2000', 'nom' => 'Dr Pascal VILLEMONTEIX',     'duree' => '4 ans'],
        ['periode' => '2000 – 2002', 'nom' => 'M. Jacky MORILLEAU',         'duree' => '2 ans'],
        ['periode' => '2002 – 2004', 'nom' => 'M. François PROUST',         'duree' => '2 ans'],
        ['periode' => '2004 – 2011', 'nom' => 'Mme Laurence FUZEAU',        'duree' => '8 ans'],
        ['periode' => '2011 – 2014', 'nom' => 'Mme Christine GIRET',        'duree' => '3 ans'],
        ['periode' => '2014',        'nom' => 'M. Jean-Claude THOURAINE',   'duree' => '6 mois'],
        ['periode' => '2014 – 2018', 'nom' => 'Nathalie AUDEBAULT',         'duree' => ''],
        ['periode' => 'Depuis 2018', 'nom' => 'Sébastien CHEVALIER',        'duree' => 'En cours'],
    ];
@endphp


<!-- Hero Section avec Video Background -->
<section class="position-relative text-white overflow-hidden">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="hero-video">
        <source src="{{ asset('assets/images/team/CNBB-natation-2.mp4') }}" type="video/mp4">
    </video>
    <!-- Contenu -->
    <div class="container-lg py-5 position-relative hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-4 animate-slide-up">
                    <h1 class="text-white display-3 fw-bold mb-0">Cercle des Nageurs du Bocage Bressuirais</h1>
                </div>
                 <p class="text-uppercase fw-semibold mb-2 opacity-75">Le club</p>
                <p class="lead mb-0">
                    Fondé en 1954, le CNBB reste l'un des clubs les plus dynamiques des Deux-Sèvres.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Présentation -->
<section id="presentation" class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <header class="text-center mb-5">
                    <h2 class="display-5 fw-bold mb-4">Présentation</h2>
                    <p class="lead text-muted">
                        Association sportive affiliée à la Fédération Française de Natation,
                        le CNBB est essentiellement spécialisé dans la natation course.
                    </p>
                </header>

                <div class="row g-4">
                    @foreach ($publics as $public)
                        <div class="col-md-6 col-lg-3">
                            <article class="card border-0 shadow-sm h-100 text-center">
                                <div class="card-body p-4">
                                    <div class="bg-{{ $public['couleur'] }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                         style="width: 80px; height: 80px;">
                                        <i class="fas {{ $public['icon'] }} text-{{ $public['couleur'] }} fa-2x"></i>
                                    </div>
                                    <h3 class="h5 fw-bold mb-3">{{ $public['titre'] }}</h3>
                                    <p class="text-muted mb-0">{{ $public['texte'] }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Chiffres clés -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row g-3 justify-content-center">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <div class="display-5 fw-bold text-primary mb-2">{{ $anneeCreation }}</div>
                    <small class="text-muted">Année de création</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <div class="display-5 fw-bold text-success mb-2">{{ $ageClub }} ans</div>
                    <small class="text-muted">D'histoire au bord des bassins</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <div class="display-5 fw-bold text-warning mb-2">{{ count($presidents) }}</div>
                    <small class="text-muted">Présidents depuis la création</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <div class="display-5 fw-bold text-info mb-2">FFN</div>
                    <small class="text-muted">Club affilié à la Fédération Française de Natation</small>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Historique -->
<section id="historique" class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <header class="text-center mb-5">
                    <h2 class="display-6 fw-bold mb-3">Historique</h2>
                    <p class="lead text-muted">Les grandes dates du club, de 1954 à aujourd'hui</p>
                </header>

                <div class="club-timeline">
                    @foreach ($historique as $etape)
                        <article class="d-flex align-items-start mb-4">
                            <div class="club-timeline-annee bg-{{ $etape['couleur'] }} text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                <strong>{{ $etape['annee'] }}</strong>
                            </div>
                            <div>
                                <h3 class="h5 fw-bold mb-1">{{ $etape['titre'] }}</h3>
                                <p class="text-muted mb-0">{{ $etape['texte'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Présidents -->
<section id="presidents" class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <header class="text-center mb-5">
                    <h2 class="display-6 fw-bold mb-3">Les présidents depuis la création du club</h2>
                    <p class="lead text-muted">Celles et ceux qui ont porté le club depuis 1954</p>
                </header>

                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Période</th>
                                    <th scope="col">Président(e)</th>
                                    <th scope="col" class="text-end pe-4">Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($presidents as $president)
                                    <tr>
                                        <td class="ps-4 text-nowrap fw-semibold">{{ $president['periode'] }}</td>
                                        <td>{{ $president['nom'] }}</td>
                                        <td class="text-end pe-4 text-muted">{{ $president['duree'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Coordonnées -->
<section id="coordonnees" class="py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Nous trouver, nous joindre</h2>
        </header>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-map-marker-alt text-primary fa-2x mb-3"></i>
                        <h3 class="h5 fw-bold mb-2">Adresse</h3>
                        <address class="text-muted mb-0">
                            Cercle des Nageurs du Bocage Bressuirais<br>
                            40 boulevard de la République<br>
                            79300 Bressuire
                        </address>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-envelope text-success fa-2x mb-3"></i>
                        <h3 class="h5 fw-bold mb-2">E-mail</h3>
                        <p class="mb-0">
                            <a href="mailto:cnbb079@gmail.com" class="text-decoration-none">cnbb079@gmail.com</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-phone text-info fa-2x mb-3"></i>
                        <h3 class="h5 fw-bold mb-2">Téléphone</h3>
                        <p class="mb-0">
                            <a href="tel:+33602350843" class="text-decoration-none">06 02 35 08 43</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Rejoindre le club -->
<section class="py-5 bg-primary text-white">
    <div class="container-lg text-center">
        <h2 class="display-6 fw-bold mb-3">Envie de nager avec nous ?</h2>
        <p class="lead mb-4">Retrouvez les tarifs et les modalités d'inscription de la saison.</p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('pricing') }}" class="btn btn-light btn-lg">
                <i class="fas fa-clipboard-check me-2"></i>S'inscrire au club
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-envelope me-2"></i>Nous contacter
            </a>
        </div>
    </div>
</section>

@endsection


@push('styles')
<style>
    /* Pastille de l'année dans la frise */
    .club-timeline-annee {
        width: 68px;
        height: 68px;
        font-size: 0.95rem;
    }

    /* Trait vertical reliant les dates */
    .club-timeline {
        position: relative;
    }

    .club-timeline::before {
        content: "";
        position: absolute;
        top: 34px;
        bottom: 34px;
        left: 33px;
        width: 2px;
        background-color: rgba(0, 0, 0, 0.1);
    }

    .club-timeline article {
        position: relative;
    }

    .club-timeline article:last-child {
        margin-bottom: 0 !important;
    }

    .hero-video {
position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
    border-top: 20px solid #ffffff;
    border-bottom: 20px solid #ffffff;
    border-left: 20px solid #efa525;
    border-right: 20px solid #efa525;
}

.hero-content {
    z-index: 3;
}
</style>
@endpush