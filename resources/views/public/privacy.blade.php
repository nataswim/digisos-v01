@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', 'Politique de confidentialité')
@section('meta_description', 'Comment le Cercle des Nageurs du Bocage Bressuirais (CNBB) collecte, utilise et protège vos données personnelles sur son site, conformément au RGPD.')

{{-- Open Graph / Facebook --}}
@section('og_type', 'website')
@section('og_title', 'Politique de confidentialité - ' . config('app.name'))
@section('og_description', 'Vos données personnelles sur le site du CNBB : ce que nous collectons, pourquoi, et comment exercer vos droits.')
@section('og_url', route('privacy'))

@section('content')

@php
    // Date de dernière modification du texte : à changer à la main quand le contenu évolue.
    $miseAJour = '6 octobre 2026';

    // Ce que le site enregistre
    $donnees = [
        [
            'icon'  => 'fa-id-card',
            'titre' => 'Votre compte',
            'texte' => 'Nom, adresse e-mail et mot de passe (enregistré sous forme chiffrée). Vous pouvez compléter votre profil : prénom, nom, téléphone, date de naissance, photo, présentation.',
        ],
        [
            'icon'  => 'fa-address-book',
            'titre' => 'Votre fiche d\'adhérent',
            'texte' => 'Si vous êtes adhérent, le club peut tenir une fiche personnelle que vous consultez depuis votre espace. Elle est renseignée par le club, pas par vous.',
        ],
        [
            'icon'  => 'fa-envelope',
            'titre' => 'Vos messages',
            'texte' => 'Prénom, nom, adresse e-mail, téléphone (facultatif), sujet et contenu du message envoyé avec le formulaire de contact.',
        ],
        [
            'icon'  => 'fa-server',
            'titre' => 'Des données techniques',
            'texte' => 'Date et adresse IP de votre dernière connexion, nombre de connexions, journal des documents téléchargés, nombre de vues des pages.',
        ],
    ];

    // Pourquoi, et sur quel fondement
    $finalites = [
        ['Créer et gérer votre compte, vous donner accès à votre espace',     'Mesures prises à votre demande (création du compte)'],
        ['Réserver certains contenus aux personnes connectées ou aux adhérents', 'Intérêt légitime du club'],
        ['Tenir la fiche personnelle des adhérents',                           'Gestion de l\'adhésion au club'],
        ['Répondre aux messages du formulaire de contact',                     'Intérêt légitime : répondre à votre demande'],
        ['Assurer la sécurité du site et détecter les usages frauduleux',      'Intérêt légitime du club'],
    ];

    // Durées de conservation
    $durees = [
        ['Compte et profil',                 'Tant que le compte est actif. Supprimé à votre demande, ou au plus tard 3 ans après votre dernière connexion.'],
        ['Fiche d\'adhérent',                'Pendant la durée de l\'adhésion, puis jusqu\'à la fin de la saison suivante.'],
        ['Messages du formulaire de contact', '1 an après le dernier échange.'],
        ['Données techniques de connexion',  '12 mois au maximum.'],
    ];

    // Vos droits
    $droits = [
        ['fa-eye',          'Accès',         'Savoir quelles données le club détient sur vous et en obtenir une copie.'],
        ['fa-pen',          'Rectification', 'Faire corriger une information inexacte ou incomplète.'],
        ['fa-trash-alt',    'Effacement',    'Demander la suppression de votre compte et de vos données.'],
        ['fa-pause-circle', 'Limitation',    'Demander que vos données ne soient plus utilisées, le temps d\'un examen.'],
        ['fa-ban',          'Opposition',    'Vous opposer à un traitement fondé sur l\'intérêt légitime du club.'],
        ['fa-exchange-alt', 'Portabilité',   'Récupérer les données que vous avez fournies dans un format réutilisable.'],
    ];
@endphp


<!-- En-tête -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-shield-alt me-3" aria-hidden="true"></i>Politique de confidentialité
                </h1>
                <p class="lead mb-3">
                    Ce que le site du club enregistre à votre sujet, pourquoi, et comment exercer vos droits.
                </p>
                <p class="mb-0 opacity-75">Dernière mise à jour : {{ $miseAJour }}</p>
            </div>
            <div class="col-lg-5">
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="h5 text-primary mb-3">L'essentiel</h2>
                    <ul class="list-unstyled mb-0 text-dark small">
                        <li class="d-flex mb-2">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0" aria-hidden="true"></i>
                            <span>Le site ne collecte que les données utiles à votre compte et à vos demandes.</span>
                        </li>
                        <li class="d-flex mb-2">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0" aria-hidden="true"></i>
                            <span>Vos données ne sont ni vendues, ni louées, ni utilisées pour de la publicité.</span>
                        </li>
                        <li class="d-flex mb-2">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0" aria-hidden="true"></i>
                            <span>Les pages publiques se consultent sans compte et sans rien nous communiquer.</span>
                        </li>
                        <li class="d-flex">
                            <i class="fas fa-check text-success mt-1 me-2 flex-shrink-0" aria-hidden="true"></i>
                            <span>Vous pouvez demander l'accès, la correction ou la suppression de vos données à tout moment.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Responsable et champ d'application -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="h3 fw-bold mb-4">1. Qui est responsable de vos données ?</h2>
                <div class="card p-4 mb-5 border-0 bg-light shadow-sm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Cercle des Nageurs du Bocage Bressuirais (CNBB)</strong></p>
                            <address class="mb-0">
                                40 boulevard de la République<br>
                                79300 Bressuire
                            </address>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong>E-mail :</strong> <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>
                            </p>
                            <p class="mb-0">
                                <strong>Téléphone :</strong> <a href="tel:+33602350843">06 02 35 08 43</a>
                            </p>
                        </div>
                    </div>
                </div>

                <h2 class="h3 fw-bold mb-4">2. À quoi s'applique cette politique ?</h2>
                <p class="mb-3">
                    Cette politique concerne le site internet du club : la consultation des pages, votre compte,
                    votre espace personnel et le formulaire de contact.
                </p>
                <p class="mb-0">
                    Le dossier d'adhésion (fiche d'inscription, documents médicaux, règlement) est remis au club
                    sur papier. Il sert à gérer votre adhésion et votre licence auprès de la Fédération Française
                    de Natation ; il n'est pas publié sur le site. Pour toute question à son sujet, contactez le club.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- Données collectées -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="h3 fw-bold mb-2">3. Quelles données le site enregistre-t-il ?</h2>
                <p class="text-muted mb-4">
                    Seules les données décrites ci-dessous sont enregistrées, et uniquement si vous créez un compte ou nous écrivez.
                </p>

                <div class="row g-4 mb-5">
                    @foreach ($donnees as $donnee)
                        <div class="col-md-6">
                            <article class="card shadow-sm border-0 h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="fas {{ $donnee['icon'] }} text-primary" aria-hidden="true"></i>
                                        </div>
                                        <h3 class="h6 fw-bold mb-0">{{ $donnee['titre'] }}</h3>
                                    </div>
                                    <p class="small text-muted mb-0">{{ $donnee['texte'] }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <h2 class="h3 fw-bold mb-4">4. Pourquoi ces données sont-elles utilisées ?</h2>
                <div class="card border-0 shadow-sm mb-5">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Utilisation</th>
                                    <th scope="col" class="pe-4">Fondement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($finalites as [$utilisation, $fondement])
                                    <tr>
                                        <td class="ps-4">{{ $utilisation }}</td>
                                        <td class="pe-4 text-muted">{{ $fondement }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <h2 class="h3 fw-bold mb-4">5. Qui a accès à vos données ?</h2>
                <ul class="mb-3">
                    <li class="mb-2">
                        <strong>Les responsables du club habilités</strong> (administrateurs et rédacteurs du site),
                        pour gérer les comptes, les fiches et répondre aux messages.
                    </li>
                    <li class="mb-2">
                        <strong>Les prestataires techniques du site</strong> (hébergement, maintenance), indiqués dans les
                        <a href="{{ route('legal') }}">mentions légales</a>, uniquement pour faire fonctionner le site.
                    </li>
                </ul>
                <p class="mb-0">
                    <strong>Vos données ne sont ni vendues, ni louées, ni transmises à des fins publicitaires.</strong>
                </p>
            </div>
        </div>
    </div>
</section>


<!-- Photos, mineurs, durées -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-camera text-danger fs-4" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="h5 mb-0">6. Photos et vidéos</h2>
                                </div>
                                <p class="mb-0">
                                    Le site publie des images des entraînements et des compétitions. Si vous, ou votre enfant,
                                    apparaissez sur une image et souhaitez son retrait, écrivez-nous en indiquant la page
                                    concernée : elle sera retirée dans les meilleurs délais.
                                </p>
                            </div>
                        </article>
                    </div>

                    <div class="col-md-6">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-child text-warning fs-4" aria-hidden="true"></i>
                                    </div>
                                    <h2 class="h5 mb-0">7. Enfants et adolescents</h2>
                                </div>
                                <p class="mb-0">
                                    Le club accueille de jeunes nageurs. Pour un enfant de moins de 15 ans, le compte sur le site
                                    doit être créé et suivi par un parent ou un responsable légal, qui peut à tout moment
                                    demander sa suppression.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>

                <h2 class="h3 fw-bold mb-4">8. Combien de temps vos données sont-elles conservées ?</h2>
                <div class="card border-0 shadow-sm mb-5">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Données</th>
                                    <th scope="col" class="pe-4">Durée de conservation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($durees as [$type, $duree])
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $type }}</td>
                                        <td class="pe-4">{{ $duree }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <h2 class="h3 fw-bold mb-4">9. Comment vos données sont-elles protégées ?</h2>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-key text-success me-3 mt-1" aria-hidden="true"></i>
                            <p class="mb-0 small">Les mots de passe sont enregistrés sous forme chiffrée : personne au club ne peut les lire.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-user-lock text-success me-3 mt-1" aria-hidden="true"></i>
                            <p class="mb-0 small">L'administration du site est réservée aux personnes habilitées par le club.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-sync-alt text-success me-3 mt-1" aria-hidden="true"></i>
                            <p class="mb-0 small">Le site et ses composants sont mis à jour pour corriger les failles connues.</p>
                        </div>
                    </div>
                </div>
                <p class="small text-muted mb-0">
                    Aucun système n'est infaillible : si un incident devait toucher vos données, le club vous en informerait
                    et le signalerait à la CNIL lorsque la loi l'exige.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- Vos droits -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="h3 fw-bold mb-2">10. Vos droits</h2>
                <p class="text-muted mb-4">Le RGPD vous donne les droits suivants sur vos données personnelles.</p>

                <div class="row g-3 mb-4">
                    @foreach ($droits as [$icone, $nom, $explication])
                        <div class="col-md-6 col-lg-4">
                            <article class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas {{ $icone }} text-primary" aria-hidden="true"></i>
                                        </div>
                                        <h3 class="h6 mb-0">{{ $nom }}</h3>
                                    </div>
                                    <p class="text-muted small mb-0">{{ $explication }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white p-4 rounded shadow-sm mb-5">
                    <p class="mb-3">
                        Pour exercer un de ces droits, écrivez à <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>,
                        de préférence depuis l'adresse e-mail liée à votre compte. Le club vous répond dans un délai
                        d'un mois.
                    </p>
                    <p class="mb-0 small">
                        Si la réponse ne vous satisfait pas, vous pouvez adresser une réclamation à la CNIL
                        (Commission nationale de l'informatique et des libertés) :
                        <a href="https://www.cnil.fr" target="_blank" rel="noopener">www.cnil.fr</a>.
                    </p>
                </div>

                <h2 class="h3 fw-bold mb-4">11. Cookies et contenus externes</h2>
                <p class="mb-5">
                    Le site dépose uniquement les cookies nécessaires à son fonctionnement ; une éventuelle mesure d'audience
                    n'est activée qu'avec votre accord. Certains contenus sont fournis par d'autres services
                    (police d'écriture Google Fonts, carte Google Maps, vidéos). Le détail figure dans notre
                    <a href="{{ route('cookies') }}">politique de cookies</a>.
                </p>

                <h2 class="h3 fw-bold mb-4">12. Évolution de cette politique</h2>
                <p class="mb-0">
                    Cette page peut être mise à jour, par exemple si le site propose de nouveaux services.
                    La date de dernière mise à jour figure en haut de la page.
                </p>
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
                <h2 class="h3 fw-bold mb-3">Une question sur vos données ?</h2>
                <p class="mb-4">
                    <a href="mailto:cnbb079@gmail.com" class="text-white">cnbb079@gmail.com</a>
                    <span class="mx-2" aria-hidden="true">·</span>
                    <a href="tel:+33602350843" class="text-white">06 02 35 08 43</a>
                    <span class="mx-2" aria-hidden="true">·</span>
                    40 boulevard de la République, 79300 Bressuire
                </p>
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2" aria-hidden="true"></i>Contacter le club
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
