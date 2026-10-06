@extends('layouts.public')

@section('title', 'Guide d\'utilisation')

@section('content')

@php
    // Lien "Mon espace" adapté au rôle de la personne connectée
    $dashboardRoute = null;

    if (auth()->check()) {
        $dashboardRoute = match (auth()->user()->role?->slug) {
            'admin'  => route('admin.dashboard'),
            'editor' => route('editor.dashboard'),
            'user'   => route('user.dashboard'),
            default  => route('visitor.dashboard'),
        };
    }

    // Sommaire de la page
    $sommaire = [
        ['id' => 'premiers-pas', 'icon' => 'fa-shoe-prints',      'label' => 'Premiers pas'],
        ['id' => 'acces',        'icon' => 'fa-user-check',       'label' => 'Visiteur ou membre'],
        ['id' => 'rubriques',    'icon' => 'fa-compass',          'label' => 'Les rubriques'],
        ['id' => 'mon-espace',   'icon' => 'fa-user-circle',      'label' => 'Mon espace'],
        ['id' => 'recherche',    'icon' => 'fa-search',           'label' => 'Recherche'],
        ['id' => 'faq',          'icon' => 'fa-question-circle',  'label' => 'Questions fréquentes'],
    ];

    // Étapes pour bien démarrer
    $etapes = [
        [
            'couleur' => 'primary',
            'titre'   => 'Explorez librement',
            'texte'   => 'Les actualités, les fiches pratiques, les vidéos, les galeries photo et la présentation des installations sont consultables sans inscription.',
        ],
        [
            'couleur' => 'success',
            'titre'   => 'Créez votre compte',
            'texte'   => 'Cliquez sur « Inscription », indiquez votre nom, votre adresse e-mail et choisissez un mot de passe. Cela prend moins d\'une minute.',
        ],
        [
            'couleur' => 'warning',
            'titre'   => 'Confirmez votre e-mail',
            'texte'   => 'Un message vous est envoyé avec un lien de confirmation. Cliquez dessus pour activer votre compte. Pensez à vérifier vos courriers indésirables.',
        ],
        [
            'couleur' => 'info',
            'titre'   => 'Accédez à votre espace',
            'texte'   => 'Une fois connecté, vous arrivez sur votre tableau de bord. Si vous êtes adhérent, le club active votre accès aux contenus réservés aux membres.',
        ],
    ];

    // Rubriques du site
    $rubriques = [
        [
            'icon'    => 'fa-newspaper',
            'couleur' => 'primary',
            'titre'   => 'Actualités',
            'texte'   => 'La vie du club : résultats de compétitions, événements, informations pratiques et annonces. Les articles sont classés par catégories et par mots-clés.',
            'url'     => route('posts.public.index'),
            'lien'    => 'Lire les actualités',
        ],
        [
            'icon'    => 'fa-clipboard-list',
            'couleur' => 'success',
            'titre'   => 'Fiches pratiques',
            'texte'   => 'Des fiches claires pour progresser : technique des nages, conseils d\'entraînement, matériel, démarches. Elles sont rangées par catégories et sous-catégories.',
            'url'     => route('public.fiches.index'),
            'lien'    => 'Consulter les fiches',
        ],
        [
            'icon'    => 'fa-play-circle',
            'couleur' => 'danger',
            'titre'   => 'Vidéos',
            'texte'   => 'Démonstrations techniques, tutoriels et reportages du club, à regarder directement sur le site, sur ordinateur comme sur téléphone.',
            'url'     => route('public.videos.index'),
            'lien'    => 'Voir les vidéos',
        ],
        [
            'icon'    => 'fa-book-open',
            'couleur' => 'warning',
            'titre'   => 'Documents à télécharger',
            'texte'   => 'Guides, formulaires et plans d\'entraînement au format PDF. Choisissez une catégorie, ouvrez le document puis cliquez sur « Télécharger ».',
            'url'     => route('ebook.index'),
            'lien'    => 'Parcourir les documents',
        ],
        [
            'icon'    => 'fa-images',
            'couleur' => 'info',
            'titre'   => 'Galeries photo',
            'texte'   => 'Les albums des compétitions, des stages et des temps forts de la saison. Cliquez sur une photo pour l\'afficher en grand.',
            'url'     => route('galleries.index'),
            'lien'    => 'Voir les galeries',
        ],
        [
            'icon'    => 'fa-swimming-pool',
            'couleur' => 'primary',
            'titre'   => 'Installations',
            'texte'   => 'La présentation des lieux de pratique : services, structures, espaces et bassins. Idéal pour repérer où se déroule votre activité.',
            'url'     => route('public.installations.index'),
            'lien'    => 'Découvrir les installations',
        ],
        [
            'icon'    => 'fa-file-alt',
            'couleur' => 'secondary',
            'titre'   => 'Pages d\'information',
            'texte'   => 'Les informations durables du club : présentation, fonctionnement, règlement, aide. Elles sont regroupées par thème.',
            'url'     => route('public.pages.index'),
            'lien'    => 'Voir les pages',
        ],
        [
            'icon'    => 'fa-envelope',
            'couleur' => 'success',
            'titre'   => 'Contact',
            'texte'   => 'Une question sur le club, une inscription ou le site ? Écrivez-nous avec le formulaire, nous vous répondons par e-mail.',
            'url'     => route('contact'),
            'lien'    => 'Nous écrire',
        ],
    ];

    // Questions fréquentes
    $faq = [
        [
            'q' => 'Comment s\'inscrire au club ?',
            'r' => 'Créer un compte sur le site ne vaut pas adhésion au club. L\'adhésion se fait avec un dossier d\'inscription à déposer dans la boîte aux lettres du club. Les tarifs, les conditions et la liste des pièces à fournir sont détaillés sur la page « Inscription ».',
        ],
        [
            'q' => 'J\'ai oublié mon mot de passe, que faire ?',
            'r' => 'Sur la page de connexion, cliquez sur « Mot de passe oublié ». Saisissez votre adresse e-mail : vous recevez un lien pour choisir un nouveau mot de passe. Ce lien est valable pendant une durée limitée.',
        ],
        [
            'q' => 'Je n\'ai pas reçu l\'e-mail de confirmation.',
            'r' => 'Vérifiez d\'abord votre dossier « Courrier indésirable » (spam). Si vous ne trouvez rien, connectez-vous avec vos identifiants : le site vous propose de renvoyer l\'e-mail de confirmation.',
        ],
        [
            'q' => 'Pourquoi certains contenus sont-ils verrouillés ?',
            'r' => 'Une partie des fiches, vidéos et documents est réservée aux membres du club. Si vous êtes adhérent et que ces contenus restent verrouillés après votre inscription sur le site, contactez le club pour faire activer votre accès membre.',
        ],
        [
            'q' => 'Faut-il un compte pour consulter le site ?',
            'r' => 'Non. Les actualités, les contenus publics, les galeries et la présentation des installations sont accessibles à tous. Le compte sert à accéder à votre espace personnel et, pour les adhérents, aux contenus réservés.',
        ],
        [
            'q' => 'Comment modifier mes informations personnelles ?',
            'r' => 'Connectez-vous, ouvrez votre espace personnel puis la rubrique « Profil ». Vous pouvez y mettre à jour votre nom, votre adresse e-mail et votre mot de passe.',
        ],
        [
            'q' => 'Un document ne se télécharge pas.',
            'r' => 'Vérifiez que vous êtes bien connecté : certains documents sont réservés aux membres. Si le problème persiste, essayez avec un autre navigateur, puis signalez-le-nous en précisant le nom du document.',
        ],
        [
            'q' => 'Le site fonctionne-t-il sur téléphone et tablette ?',
            'r' => 'Oui. Le site s\'adapte automatiquement à la taille de votre écran. Sur téléphone, le menu s\'ouvre avec le bouton situé en haut de la page.',
        ],
        [
            'q' => 'Comment supprimer mon compte ou mes données ?',
            'r' => 'Envoyez-nous votre demande avec le formulaire de contact, depuis l\'adresse e-mail liée à votre compte. Vous pouvez aussi consulter notre politique de confidentialité pour connaître vos droits.',
        ],
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
                    <h1 class="text-white display-3 fw-bold mb-0">Guide d'utilisation</h1>
                </div>
                 <p class="text-uppercase fw-semibold mb-2 opacity-75">Le club</p>
                <p class="lead mb-0">
                    Nageur, parent, adhérent ou simple curieux : cette page vous explique comment trouver une information,
                    créer votre compte et profiter de votre espace personnel sur le site du
                    Cercle des Nageurs du Bocage Bressuirais (CNBB).
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Me connecter
                        </a>
                    @else
                        <a href="{{ $dashboardRoute }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-circle me-2"></i>Accéder à mon espace
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Sommaire -->
<nav class="bg-white border-bottom py-3" aria-label="Sommaire du guide">
    <div class="container-lg">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            @foreach ($sommaire as $item)
                <a href="#{{ $item['id'] }}" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="fas {{ $item['icon'] }} me-1"></i>{{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</nav>


<!-- Premiers pas -->
<section id="premiers-pas" class="guide-section py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Premiers pas</h2>
            <p class="lead text-muted">Quatre étapes pour bien démarrer sur le site</p>
        </header>

        <div class="row g-4">
            @foreach ($etapes as $index => $etape)
                <div class="col-md-6 col-lg-3">
                    <article class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="guide-step bg-{{ $etape['couleur'] }} text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4">
                                {{ $index + 1 }}
                            </div>
                            <h3 class="h5 fw-bold mb-3">{{ $etape['titre'] }}</h3>
                            <p class="text-muted mb-0">{{ $etape['texte'] }}</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Visiteur ou membre -->
<section id="acces" class="guide-section py-5 bg-light">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Visiteur ou membre : à quoi avez-vous accès ?</h2>
            <p class="lead text-muted">Le contenu affiché dépend de votre situation</p>
        </header>

        <div class="row g-4">
            <div class="col-lg-4">
                <article class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-eye text-secondary fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-1">Sans compte</h3>
                        <p class="text-muted small mb-3">Vous naviguez librement</p>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Actualités du club</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Fiches, vidéos et pages publiques</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Galeries photo et installations</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Recherche et formulaire de contact</li>
                            <li class="mb-2 text-muted"><i class="fas fa-lock me-2"></i>Pas d'espace personnel</li>
                            <li class="text-muted"><i class="fas fa-lock me-2"></i>Pas de contenus réservés</li>
                        </ul>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <article class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-user text-info fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-1">Compte visiteur</h3>
                        <p class="text-muted small mb-3">Vous venez de vous inscrire sur le site</p>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tout ce qui est accessible sans compte</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Un tableau de bord personnel</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>La gestion de votre profil et de votre mot de passe</li>
                            <li class="text-muted"><i class="fas fa-lock me-2"></i>Contenus réservés aux membres non inclus</li>
                        </ul>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <article class="card border-0 shadow h-100 border-top border-primary border-4">
                    <div class="card-body p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-swimmer text-primary fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-1">Compte membre</h3>
                        <p class="text-muted small mb-3">Vous êtes adhérent et le club a activé votre accès</p>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tout ce qui est accessible aux visiteurs</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Fiches, vidéos et pages réservées aux membres</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Documents à télécharger réservés</li>
                            <li><i class="fas fa-check text-success me-2"></i>Votre fiche personnelle tenue par le club</li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>

        <div class="alert alert-info border-0 shadow-sm mt-4 mb-0 d-flex align-items-start" role="note">
            <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
            <div>
                <strong>Vous êtes adhérent mais vos contenus restent verrouillés ?</strong>
                L'accès membre est activé par le club après votre inscription sur le site.
                <a href="{{ route('contact') }}" class="alert-link">Contactez-nous</a> en précisant le nom du nageur concerné.
                Pas encore adhérent ? Les tarifs et le dossier à fournir sont sur la page
                <a href="{{ route('pricing') }}" class="alert-link">Inscription</a>.
            </div>
        </div>
    </div>
</section>


<!-- Les rubriques -->
<section id="rubriques" class="guide-section py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Les rubriques du site</h2>
            <p class="lead text-muted">Où trouver ce que vous cherchez</p>
        </header>

        <div class="row g-4">
            @foreach ($rubriques as $rubrique)
                <div class="col-md-6 col-lg-3">
                    <article class="card guide-card border-0 shadow-sm h-100">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="bg-{{ $rubrique['couleur'] }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 64px; height: 64px;">
                                <i class="fas {{ $rubrique['icon'] }} text-{{ $rubrique['couleur'] }} fa-lg"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-2">{{ $rubrique['titre'] }}</h3>
                            <p class="text-muted small flex-grow-1">{{ $rubrique['texte'] }}</p>
                            <a href="{{ $rubrique['url'] }}" class="fw-semibold text-decoration-none stretched-link">
                                {{ $rubrique['lien'] }} <i class="fas fa-arrow-right ms-1 small"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Mon espace -->
<section id="mon-espace" class="guide-section py-5 bg-light">
    <div class="container-lg">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Votre espace personnel</h2>
                <p class="text-muted mb-4">
                    Dès que vous êtes connecté, un espace vous est réservé. Vous y accédez à tout moment
                    depuis le menu situé en haut de la page.
                </p>

                <article class="d-flex align-items-start mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width: 56px; height: 56px;">
                        <i class="fas fa-tachometer-alt fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-1">Tableau de bord</h3>
                        <p class="text-muted mb-0">
                            Votre page d'accueil personnelle : elle rassemble les raccourcis vers les rubriques et les derniers contenus publiés.
                        </p>
                    </div>
                </article>

                <article class="d-flex align-items-start mb-4">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width: 56px; height: 56px;">
                        <i class="fas fa-id-card fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-1">Profil</h3>
                        <p class="text-muted mb-0">
                            Mettez à jour votre nom, votre adresse e-mail et votre mot de passe. Gardez une adresse e-mail valide :
                            c'est elle qui sert à récupérer votre compte.
                        </p>
                    </div>
                </article>

                <article class="d-flex align-items-start mb-4">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width: 56px; height: 56px;">
                        <i class="fas fa-address-book fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-1">Ma fiche <span class="badge bg-primary align-middle ms-1">Membres</span></h3>
                        <p class="text-muted mb-0">
                            Une fiche personnelle renseignée par le club, que vous consultez en lecture seule.
                            Une information à corriger ? Signalez-la à votre entraîneur ou au secrétariat.
                        </p>
                    </div>
                </article>

                <article class="d-flex align-items-start">
                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width: 56px; height: 56px;">
                        <i class="fas fa-sign-out-alt fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-1">Déconnexion</h3>
                        <p class="text-muted mb-0">
                            Sur un ordinateur partagé (famille, travail, médiathèque), pensez à vous déconnecter après votre visite.
                        </p>
                    </div>
                </article>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="h5 fw-bold mb-4">
                            <i class="fas fa-shield-alt text-primary me-2"></i>Bien protéger votre compte
                        </h3>
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex mb-3">
                                <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                <span>Choisissez un mot de passe long, que vous n'utilisez sur aucun autre site.</span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                <span>Ne communiquez jamais votre mot de passe : le club ne vous le demandera pas.</span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                <span>Pour un enfant mineur, le compte est créé et suivi par un parent ou un responsable légal.</span>
                            </li>
                            <li class="d-flex">
                                <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                <span>Un doute sur un e-mail reçu au nom du club ? Ne cliquez pas et contactez-nous.</span>
                            </li>
                        </ul>

                        @guest
                            <a href="{{ route('password.request') }}" class="btn btn-outline-primary">
                                <i class="fas fa-key me-2"></i>Mot de passe oublié
                            </a>
                        @else
                            <a href="{{ $dashboardRoute }}" class="btn btn-primary">
                                <i class="fas fa-user-circle me-2"></i>Accéder à mon espace
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Recherche -->
<section id="recherche" class="guide-section py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <header class="text-center mb-5">
                    <h2 class="display-6 fw-bold mb-3">Trouver rapidement une information</h2>
                    <p class="lead text-muted">La recherche explore toutes les rubriques en une seule fois</p>
                </header>

                <div class="row g-4">
                    <div class="col-md-4">
                        <article class="text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 70px; height: 70px;">
                                <i class="fas fa-search text-primary fa-2x"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-2">Utilisez des mots simples</h3>
                            <p class="text-muted mb-0">Un ou deux mots suffisent : « crawl », « inscription », « stage », « bonnet ».</p>
                        </article>
                    </div>

                    <div class="col-md-4">
                        <article class="text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 70px; height: 70px;">
                                <i class="fas fa-layer-group text-success fa-2x"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-2">Parcourez par catégorie</h3>
                            <p class="text-muted mb-0">Chaque rubrique est classée par thème. Cliquez sur une catégorie pour n'afficher que ce qui vous intéresse.</p>
                        </article>
                    </div>

                    <div class="col-md-4">
                        <article class="text-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 70px; height: 70px;">
                                <i class="fas fa-share-alt text-warning fa-2x"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-2">Partagez une page</h3>
                            <p class="text-muted mb-0">Les boutons de partage, présents sur les articles, permettent d'envoyer une page à un proche en un clic.</p>
                        </article>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('search') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-search me-2"></i>Lancer une recherche
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Questions fréquentes -->
<section id="faq" class="guide-section py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <header class="text-center mb-5">
                    <h2 class="display-6 fw-bold mb-3">Questions fréquentes</h2>
                    <p class="lead text-muted">Les réponses aux demandes les plus courantes</p>
                </header>

                <div class="accordion shadow-sm" id="guideFaq">
                    @foreach ($faq as $index => $item)
                        <div class="accordion-item border-0 border-bottom">
                            <h3 class="accordion-header" id="faq-titre-{{ $index }}">
                                <button class="accordion-button fw-semibold {{ $index === 0 ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faq-reponse-{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="faq-reponse-{{ $index }}">
                                    {{ $item['q'] }}
                                </button>
                            </h3>
                            <div id="faq-reponse-{{ $index }}"
                                 class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                 aria-labelledby="faq-titre-{{ $index }}"
                                 data-bs-parent="#guideFaq">
                                <div class="accordion-body text-muted">
                                    {{ $item['r'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="text-center text-muted small mt-4 mb-0">
                    Pour en savoir plus sur vos données :
                    <a href="{{ route('privacy') }}">politique de confidentialité</a>,
                    <a href="{{ route('cookies') }}">cookies</a>,
                    <a href="{{ route('accessibility') }}">accessibilité</a> et
                    <a href="{{ route('legal') }}">mentions légales</a>.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- Besoin d'aide -->
<section class="py-5 bg-primary text-white">
    <div class="container-lg text-center">
        <h2 class="display-6 fw-bold mb-3">Vous n'avez pas trouvé votre réponse ?</h2>
        <p class="lead mb-2">L'équipe du CNBB vous répond dans les meilleurs délais.</p>
        <p class="mb-4">
            <a href="mailto:cnbb079@gmail.com" class="text-white">cnbb079@gmail.com</a>
            <span class="mx-2">·</span>
            <a href="tel:+33602350843" class="text-white">06 02 35 08 43</a>
            <span class="mx-2">·</span>
            40 boulevard de la République, 79300 Bressuire
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                <i class="fas fa-envelope me-2"></i>Contacter le club
            </a>
            <a href="{{ route('pricing') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-clipboard-check me-2"></i>S'inscrire au club
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
    /* Décalage des ancres pour ne pas passer sous le menu fixe */
    .guide-section {
        scroll-margin-top: 90px;
    }

    /* Pastilles numérotées des étapes */
    .guide-step {
        width: 64px;
        height: 64px;
        font-size: 1.6rem;
        font-weight: 700;
    }

    /* Cartes des rubriques */
    .guide-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .guide-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12) !important;
    }

    /* Accordéon : question ouverte mise en valeur sans fond bleu */
    #guideFaq .accordion-button:not(.collapsed) {
        background-color: #ffffff;
        color: inherit;
        box-shadow: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .guide-card {
            transition: none;
        }

        .guide-card:hover {
            transform: none;
        }
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