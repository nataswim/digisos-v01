@extends('layouts.public')

@section('title', 'Politique de cookies')
@section('meta_description', 'Les cookies utilisés sur le site du CNBB, club de natation de Bressuire : cookies nécessaires au fonctionnement, contenus externes et réglages de votre navigateur.')

@section('content')

@php
    // Date de dernière modification du texte : à changer à la main quand le contenu évolue.
    $miseAJour = '6 octobre 2026';

    // Mesure d'audience : active uniquement si un identifiant Google Analytics est configuré
    // (même réglage que dans layouts/public.blade.php).
    $gaId = config('services.google_analytics.id');

    // Durée de la session, lue dans la configuration du site
    $dureeSession = (int) config('session.lifetime', 120);
    $dureeSessionTexte = $dureeSession >= 120
        ? round($dureeSession / 60) . ' heures d\'inactivité'
        : $dureeSession . ' minutes d\'inactivité';

    // Cookies déposés par le site lui-même
    $cookies = [
        [
            'nom'   => config('session.cookie', 'session'),
            'role'  => 'Maintient votre session : il permet au site de vous reconnaître d\'une page à l\'autre une fois connecté.',
            'duree' => $dureeSessionTexte,
        ],
        [
            'nom'   => 'XSRF-TOKEN',
            'role'  => 'Protège les formulaires (connexion, contact) contre les envois frauduleux.',
            'duree' => $dureeSessionTexte,
        ],
        [
            'nom'   => 'remember_web_…',
            'role'  => 'Déposé uniquement si vous cochez « Se souvenir de moi » : il vous évite de vous reconnecter à chaque visite.',
            'duree' => 'Environ 13 mois, ou jusqu\'à votre déconnexion',
        ],
    ];
@endphp


<!-- En-tête -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-cookie-bite me-3" aria-hidden="true"></i>Politique de cookies
                </h1>
                <p class="lead mb-3">
                    Quels cookies le site du club utilise, à quoi ils servent et comment les gérer.
                </p>
                <p class="mb-0 opacity-75">Dernière mise à jour : {{ $miseAJour }}</p>
            </div>
            <div class="col-lg-5">
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="h5 text-primary mb-3">L'essentiel</h2>
                    <ul class="list-unstyled mb-0 text-dark small">
                        <li class="d-flex mb-2">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0" aria-hidden="true"></i>
                            <span>Sans action de votre part, le site ne dépose que les cookies nécessaires à son fonctionnement.</span>
                        </li>
                        <li class="d-flex mb-2">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0" aria-hidden="true"></i>
                            @if ($gaId)
                                <span>Aucun cookie publicitaire. La mesure d'audience n'est activée que si vous l'acceptez.</span>
                            @else
                                <span>Aucun cookie publicitaire, aucun suivi de votre navigation.</span>
                            @endif
                        </li>
                        <li class="d-flex">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0" aria-hidden="true"></i>
                            <span>Certains contenus sont fournis par des services externes (police d'écriture, carte, vidéos).</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Définition -->
                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-cookie-bite text-primary fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">1. Qu'est-ce qu'un cookie ?</h2>
                        </div>
                        <p class="mb-0">
                            Un cookie est un petit fichier texte enregistré par votre navigateur lorsque vous visitez un site.
                            Il permet au site de retenir une information d'une page à l'autre, par exemple que vous êtes connecté.
                            Certains cookies disparaissent à la fermeture du navigateur, d'autres restent plus longtemps.
                        </p>
                    </div>
                </article>

                <!-- Cookies du site -->
                <h2 class="h3 fw-bold mb-3">2. Les cookies déposés par le site</h2>
                <p class="text-muted mb-4">
                    Ces cookies sont strictement nécessaires au fonctionnement du site. À ce titre, la loi n'impose pas
                    de recueillir votre consentement.
                </p>

                <div class="card border-0 shadow-sm mb-5">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Nom</th>
                                    <th scope="col">À quoi sert-il ?</th>
                                    <th scope="col" class="pe-4">Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cookies as $cookie)
                                    <tr>
                                        <td class="ps-4"><code>{{ $cookie['nom'] }}</code></td>
                                        <td>{{ $cookie['role'] }}</td>
                                        <td class="pe-4 text-muted">{{ $cookie['duree'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mesure d'audience et publicité -->
                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-user-shield text-success fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">3. Mesure d'audience et publicité</h2>
                        </div>

                        <p class="mb-3">Le site ne dépose aucun cookie publicitaire.</p>

                        @if ($gaId)
                            <p class="mb-3">
                                Pour connaître le nombre de visites et les pages les plus consultées, le club utilise
                                <strong>Google Analytics</strong>. Cet outil n'est chargé que si vous cliquez sur « Accepter »
                                dans le bandeau affiché à votre première visite. Si vous refusez, rien n'est déposé
                                et le site fonctionne de la même façon.
                            </p>
                            <ul class="mb-3">
                                <li class="mb-2"><strong>Cookies déposés :</strong> <code>_ga</code> et <code>_ga_…</code>, conservés 13 mois au maximum.</li>
                                <li class="mb-2"><strong>Données transmises à Google :</strong> pages consultées, type d'appareil et de navigateur, adresse IP (anonymisée).</li>
                                <li><strong>Votre choix :</strong> il est mémorisé 6 mois, puis vous est redemandé.</li>
                            </ul>
                            <a href="{{ route('cookies') }}" class="btn btn-outline-primary btn-sm" data-cookie-settings>
                                <i class="fas fa-sliders-h me-2" aria-hidden="true"></i>Modifier mon choix
                            </a>
                        @else
                            <p class="mb-0">
                                Il n'utilise pas non plus d'outil de mesure d'audience. Si le club décidait d'en utiliser un,
                                cette page serait mise à jour et votre accord vous serait demandé au préalable.
                            </p>
                        @endif
                    </div>
                </article>

                <!-- Contenus externes -->
                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-external-link-alt text-warning fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">4. Les contenus fournis par d'autres services</h2>
                        </div>
                        <p class="mb-3">
                            Certains contenus sont hébergés ailleurs. Lorsque votre navigateur les charge,
                            le service concerné reçoit des informations techniques (adresse IP, navigateur) et peut,
                            pour la carte et les vidéos, déposer ses propres cookies. Le club n'a pas la main sur ces cookies.
                        </p>
                        <ul class="mb-3">
                            <li class="mb-2">
                                <strong>Google Fonts</strong> : la police d'écriture du site est chargée depuis les serveurs
                                de Google, sur toutes les pages. Aucun cookie n'est déposé, mais votre adresse IP est transmise.
                            </li>
                            <li class="mb-2">
                                <strong>Google Maps</strong> : la carte de la page
                                <a href="{{ route('contact') }}">Contact</a>.
                            </li>
                            <li>
                                <strong>Plateformes vidéo</strong> (YouTube, par exemple) : les vidéos intégrées
                                dans la rubrique <a href="{{ route('public.videos.index') }}">Vidéos</a> et dans certains articles.
                            </li>
                        </ul>
                        <p class="mb-0 small text-muted">
                            Pour en savoir plus, consultez la politique de confidentialité de chacun de ces services.
                        </p>
                    </div>
                </article>

                <!-- Gérer les cookies -->
                <article class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-cogs text-primary fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">5. Gérer les cookies dans votre navigateur</h2>
                        </div>
                        <p class="mb-3">
                            Vous pouvez à tout moment consulter, supprimer ou bloquer les cookies depuis les réglages
                            de votre navigateur (Chrome, Firefox, Safari, Edge…), généralement dans la rubrique
                            « Confidentialité » ou « Vie privée ».
                        </p>
                        <div class="alert alert-info mb-0">
                            <strong>À savoir :</strong> si vous bloquez les cookies du site, la consultation des pages publiques
                            reste possible, mais vous ne pourrez plus vous connecter à votre espace ni envoyer le formulaire de contact.
                        </div>
                    </div>
                </article>

                <!-- Données personnelles -->
                <article class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-shield-alt text-info fs-3" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 mb-0">6. Et vos données personnelles ?</h2>
                        </div>
                        <p class="mb-0">
                            Les données liées à votre compte et à vos messages, leur durée de conservation et vos droits
                            sont détaillés dans notre <a href="{{ route('privacy') }}">politique de confidentialité</a>.
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
                <i class="fas fa-envelope fs-1 mb-4" aria-hidden="true"></i>
                <h2 class="h3 fw-bold mb-3">Une question sur les cookies ?</h2>
                <p class="mb-4">
                    Écrivez-nous à <a href="mailto:cnbb079@gmail.com" class="text-white">cnbb079@gmail.com</a>.
                    Vous pouvez aussi vous adresser à la CNIL : <a href="https://www.cnil.fr" class="text-white" target="_blank" rel="noopener">www.cnil.fr</a>.
                </p>
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2" aria-hidden="true"></i>Contacter le club
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
