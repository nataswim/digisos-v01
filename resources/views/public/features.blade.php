@extends('layouts.public')

@section('title', 'Ce que vous trouverez sur le site')
@section('meta_description', 'Actualités, fiches pratiques, vidéos, documents à télécharger, galeries photo, installations et espace personnel : tour d\'horizon du site du CNBB, club de natation de Bressuire.')

@section('content')

@php
    // Les rubriques du site
    $rubriques = [
        [
            'icon'    => 'fa-newspaper',
            'couleur' => 'primary',
            'texte'   => 'text-white',
            'titre'   => 'Vie du club',
            'resume'  => 'Les actualités au fil de la saison',
            'detail'  => 'Résultats de compétitions, événements, stages, informations pratiques et annonces du bureau. Les articles sont classés par catégories et par mots-clés.',
            'lien'    => 'Suivre la vie du club',
            'url'     => route('posts.public.index'),
            'badges'  => ['Résultats', 'Événements', 'Infos'],
        ],
        [
            'icon'    => 'fa-clipboard-list',
            'couleur' => 'success',
            'texte'   => 'text-white',
            'titre'   => 'Infos pratiques',
            'resume'  => 'Les fiches d\'information du club',
            'detail'  => 'Organisation des entraînements, matériel, compétitions, démarches, conseils techniques : des fiches courtes, rangées par catégories et sous-catégories.',
            'lien'    => 'Consulter les fiches',
            'url'     => route('public.fiches.index'),
            'badges'  => ['Technique', 'Matériel', 'Démarches'],
        ],
        [
            'icon'    => 'fa-play-circle',
            'couleur' => 'info',
            'texte'   => 'text-white',
            'titre'   => 'Au fil de l\'eau',
            'resume'  => 'Les vidéos du club',
            'detail'  => 'Démonstrations techniques, éducatifs et reportages du club, à regarder directement sur le site, sur ordinateur comme sur téléphone.',
            'lien'    => 'Voir les vidéos',
            'url'     => route('public.videos.index'),
            'badges'  => ['Technique', 'Reportages'],
        ],
        [
            'icon'    => 'fa-file-download',
            'couleur' => 'warning',
            'texte'   => 'text-dark',
            'titre'   => 'Ressources',
            'resume'  => 'Les documents à télécharger',
            'detail'  => 'Formulaires, guides et documents du club au format PDF. Choisissez une catégorie, ouvrez le document, puis téléchargez-le.',
            'lien'    => 'Parcourir les documents',
            'url'     => route('ebook.index'),
            'badges'  => ['PDF', 'Formulaires', 'Guides'],
        ],
        [
            'icon'    => 'fa-images',
            'couleur' => 'danger',
            'texte'   => 'text-white',
            'titre'   => 'Galeries photo',
            'resume'  => 'Les temps forts en images',
            'detail'  => 'Les albums des compétitions, des stages et des moments de convivialité. Cliquez sur une photo pour l\'afficher en grand.',
            'lien'    => 'Voir les galeries',
            'url'     => route('galleries.index'),
            'badges'  => ['Compétitions', 'Stages', 'Vie du club'],
        ],
        [
            'icon'    => 'fa-swimming-pool',
            'couleur' => 'secondary',
            'texte'   => 'text-white',
            'titre'   => 'Installations',
            'resume'  => 'Où l\'on nage',
            'detail'  => 'La présentation des lieux de pratique : structures, espaces et bassins. Pratique pour se repérer avant un premier entraînement.',
            'lien'    => 'Découvrir les installations',
            'url'     => route('public.installations.index'),
            'badges'  => ['Bassins', 'Accès'],
        ],
    ];

    // Par où commencer, selon la personne qui visite
    $profils = [
        [
            'icon'    => 'fa-user-plus',
            'couleur' => 'primary',
            'titre'   => 'Vous souhaitez rejoindre le club',
            'texte'   => 'Consultez les conditions, les tarifs de la saison et la liste des pièces du dossier d\'inscription.',
            'lien'    => 'Inscription au club',
            'url'     => route('pricing'),
        ],
        [
            'icon'    => 'fa-user-friends',
            'couleur' => 'success',
            'titre'   => 'Vous êtes parent',
            'texte'   => 'Suivez les actualités et les résultats, retrouvez les photos des compétitions et les documents à remplir.',
            'lien'    => 'Vie du club',
            'url'     => route('posts.public.index'),
        ],
        [
            'icon'    => 'fa-swimmer',
            'couleur' => 'info',
            'titre'   => 'Vous êtes nageur',
            'texte'   => 'Révisez la technique avec les fiches et les vidéos, et retrouvez votre fiche personnelle dans votre espace.',
            'lien'    => 'Infos pratiques',
            'url'     => route('public.fiches.index'),
        ],
        [
            'icon'    => 'fa-hands-helping',
            'couleur' => 'warning',
            'titre'   => 'Vous voulez donner un coup de main',
            'texte'   => 'Le club vit grâce à ses bénévoles : officiels en compétition, membres du Comité directeur, accompagnateurs.',
            'lien'    => 'Contacter le club',
            'url'     => route('contact'),
        ],
    ];
@endphp


<x-public.hero
    title="Ce que vous trouverez sur le site"
    eyebrow="Le site du club"
    title-class="display-4"
    lead="Le site du CNBB rassemble les informations utiles aux nageurs, aux parents et à toutes les personnes qui s'intéressent au club : actualités, infos pratiques, vidéos, documents et espace personnel.">
    <a href="#rubriques" class="btn btn-primary btn-lg text-white">
        <i class="fas fa-compass me-2" aria-hidden="true"></i>Les rubriques
    </a>
    <a href="{{ route('guide') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-life-ring me-2" aria-hidden="true"></i>Guide d'utilisation
    </a>
</x-public.hero>


<!-- Les rubriques -->
<section id="rubriques" class="anchor-section py-5 bg-light">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="fw-bold display-6">Six rubriques pour suivre le club</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                La plupart des contenus sont en accès libre. Ceux qui portent un cadenas sont réservés aux personnes connectées.
            </p>
        </header>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($rubriques as $rubrique)
                <div class="col">
                    <a href="{{ $rubrique['url'] }}" class="text-decoration-none d-block h-100">
                        <article class="card h-100 shadow-sm border-0 bg-white category-card">
                            <div class="card-header bg-{{ $rubrique['couleur'] }} {{ $rubrique['texte'] }}">
                                <div class="d-flex align-items-center">
                                    <i class="fas {{ $rubrique['icon'] }} me-3 category-card-icon" aria-hidden="true"></i>
                                    <div class="flex-grow-1">
                                        <h3 class="h5 mb-1">{{ $rubrique['titre'] }}</h3>
                                        <p class="mb-0 opacity-75 small">{{ $rubrique['resume'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <p class="card-text text-muted flex-grow-1">{{ $rubrique['detail'] }}</p>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <span class="text-primary fw-bold">
                                        {{ $rubrique['lien'] }} <i class="fas fa-arrow-right ms-1 small" aria-hidden="true"></i>
                                    </span>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @foreach ($rubrique['badges'] as $badge)
                                            <span class="badge bg-light text-dark border">{{ $badge }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </article>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Selon votre profil -->
<section id="profils" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="fw-bold display-6">Par où commencer ?</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                Quatre points d'entrée, selon ce qui vous amène
            </p>
        </header>

        <div class="row g-4">
            @foreach ($profils as $profil)
                <div class="col-md-6 col-lg-3">
                    <article class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="bg-{{ $profil['couleur'] }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4 mx-auto"
                                 style="width: 80px; height: 80px;">
                                <i class="fas {{ $profil['icon'] }} text-{{ $profil['couleur'] }} fa-2x" aria-hidden="true"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-3">{{ $profil['titre'] }}</h3>
                            <p class="text-muted flex-grow-1">{{ $profil['texte'] }}</p>
                            <a href="{{ $profil['url'] }}" class="btn btn-outline-primary btn-sm stretched-link">
                                {{ $profil['lien'] }}
                            </a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Espace personnel -->
<section id="espace" class="anchor-section py-5 bg-light">
    <div class="container-lg">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Un espace personnel pour chaque inscrit</h2>
                <p class="text-muted mb-4">
                    Créer un compte sur le site est gratuit et prend une minute. Attention : le compte du site
                    ne remplace pas l'adhésion au club, qui se fait sur dossier.
                </p>
                <ul class="list-unstyled mb-4">
                    <li class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-success me-3 mt-1" aria-hidden="true"></i>
                        <span>Un tableau de bord qui rassemble vos raccourcis</span>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-success me-3 mt-1" aria-hidden="true"></i>
                        <span>L'accès aux contenus marqués « Membre »</span>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-success me-3 mt-1" aria-hidden="true"></i>
                        <span>Pour les adhérents, une fiche personnelle tenue par le club</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="fas fa-check-circle text-success me-3 mt-1" aria-hidden="true"></i>
                        <span>Un site lisible sur ordinateur, tablette et téléphone, même au bord du bassin</span>
                    </li>
                </ul>
                <div class="d-flex flex-wrap gap-2">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary text-white">
                            <i class="fas fa-user-plus me-2" aria-hidden="true"></i>Créer mon compte
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-2" aria-hidden="true"></i>Me connecter
                        </a>
                    @endguest
                    <a href="{{ route('guide') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-life-ring me-2" aria-hidden="true"></i>Lire le guide d'utilisation
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="h5 fw-bold mb-4">
                            <i class="fas fa-search text-primary me-2" aria-hidden="true"></i>Une recherche sur tout le site
                        </h3>
                        <p class="text-muted">
                            Un mot suffit — « crawl », « stage », « inscription » — pour retrouver les articles,
                            les fiches et les vidéos qui en parlent.
                        </p>
                        <a href="{{ route('search') }}" class="btn btn-primary text-white">
                            <i class="fas fa-search me-2" aria-hidden="true"></i>Lancer une recherche
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Contact -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container-lg py-3">
        <h2 class="mb-3 fw-bold">Une question, une idée pour le site ?</h2>
        <p class="lead mb-4 mx-auto" style="max-width: 700px;">
            Dites-le-nous : vos remarques nous aident à améliorer le site.
        </p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                <i class="fas fa-envelope me-2" aria-hidden="true"></i>Nous contacter
            </a>
            <a href="{{ route('pricing') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>S'inscrire au club
            </a>
        </div>
    </div>
</section>

@endsection


@push('styles')
<style>
    /* Cartes des rubriques */
    .category-card {
        overflow: hidden;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12) !important;
    }

    .category-card .card-header {
        border-bottom: 3px solid rgba(255, 255, 255, 0.2);
        padding: 1.25rem;
    }

    .category-card-icon {
        font-size: 2rem;
    }

    .category-card .badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.65rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .category-card-icon {
            font-size: 1.5rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .category-card {
            transition: none;
        }

        .category-card:hover {
            transform: none;
        }
    }
</style>
@endpush
