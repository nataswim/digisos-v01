{{--
    Pied de page du site public.
    Styles : resources/scss/layout/_public.scss (.public-footer, .public-footer-bar)
--}}
@php
    // Réseaux sociaux du club : renseignez l'adresse complète de chaque page.
    // Une ligne laissée vide n'est pas affichée.
    $reseaux = [
        ['nom' => 'Facebook',  'icon' => 'fa-facebook-f', 'url' => ''],
        ['nom' => 'Instagram', 'icon' => 'fa-instagram',  'url' => ''],
        ['nom' => 'YouTube',   'icon' => 'fa-youtube',    'url' => ''],
    ];
    $reseaux = array_filter($reseaux, fn ($reseau) => $reseau['url'] !== '');

    $liensClub = [
        ['label' => 'Présentation et historique', 'icon' => 'fa-water',           'route' => 'about'],
        ['label' => 'S\'inscrire au club',        'icon' => 'fa-clipboard-check', 'route' => 'pricing'],
        ['label' => 'Informations du club',       'icon' => 'fa-file-alt',        'route' => 'public.pages.index'],
        ['label' => 'Installations',              'icon' => 'fa-swimming-pool',   'route' => 'public.installations.index'],
        ['label' => 'Galeries photo',             'icon' => 'fa-images',          'route' => 'galleries.index'],
        ['label' => 'Contact',                    'icon' => 'fa-envelope',        'route' => 'contact'],
    ];

    $liensRubriques = [
        ['label' => 'Vie du club',       'icon' => 'fa-newspaper',      'route' => 'posts.public.index'],
        ['label' => 'Infos pratiques',   'icon' => 'fa-clipboard-list', 'route' => 'public.fiches.index'],
        ['label' => 'Au fil de l\'eau',  'icon' => 'fa-play-circle',    'route' => 'public.videos.index'],
        ['label' => 'Ressources',        'icon' => 'fa-file-download',  'route' => 'ebook.index'],
        ['label' => 'Rechercher',        'icon' => 'fa-search',         'route' => 'search'],
        ['label' => 'Guide d\'utilisation', 'icon' => 'fa-life-ring',   'route' => 'guide'],
    ];

    $liensLegaux = [
        ['label' => 'Mentions légales',             'icon' => 'fa-gavel',            'route' => 'legal'],
        ['label' => 'Politique de confidentialité', 'icon' => 'fa-shield-alt',       'route' => 'privacy'],
        ['label' => 'Politique de cookies',         'icon' => 'fa-cookie-bite',      'route' => 'cookies'],
        ['label' => 'Accessibilité',                'icon' => 'fa-universal-access', 'route' => 'accessibility'],
    ];
@endphp

<footer class="guest-footer public-footer mt-5">

    <div class="py-5">
        <div class="container-lg">
            <div class="row g-4">

                <!-- Le club -->
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('home') }}" class="d-inline-block mb-3">
                        <img src="{{ asset('assets/images/logo/Logo-CNBB-Natation-9.png') }}"
                             alt="CNBB — Cercle des Nageurs du Bocage Bressuirais"
                             class="public-footer-logo img-fluid" loading="lazy">
                    </a>
                    <p class="mb-3">
                        Club de natation de Bressuire depuis 1954, affilié à la Fédération Française de Natation.
                    </p>
                    <address class="mb-0">
                        <span class="d-block mb-1">
                            <i class="fas fa-map-marker-alt me-2 text-secondary" aria-hidden="true"></i>40 boulevard de la République, 79300 Bressuire
                        </span>
                        <span class="d-block mb-1">
                            <i class="fas fa-phone me-2 text-secondary" aria-hidden="true"></i><a href="tel:+33602350843" class="text-dark text-decoration-none">06 02 35 08 43</a>
                        </span>
                        <span class="d-block">
                            <i class="fas fa-envelope me-2 text-secondary" aria-hidden="true"></i><a href="mailto:cnbb079@gmail.com" class="text-dark text-decoration-none">cnbb079@gmail.com</a>
                        </span>
                    </address>
                </div>

                <!-- Le Club -->
                <nav class="col-lg-3 col-md-6" aria-labelledby="footer-club">
                    <h2 id="footer-club" class="public-footer-title">Le Club</h2>
                    <ul class="public-footer-links">
                        @foreach ($liensClub as $lien)
                            <li>
                                <a href="{{ route($lien['route']) }}">
                                    <i class="fas {{ $lien['icon'] }}" aria-hidden="true"></i>{{ $lien['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <!-- Rubriques -->
                <nav class="col-lg-3 col-md-6" aria-labelledby="footer-rubriques">
                    <h2 id="footer-rubriques" class="public-footer-title">Rubriques</h2>
                    <ul class="public-footer-links">
                        @foreach ($liensRubriques as $lien)
                            <li>
                                <a href="{{ route($lien['route']) }}">
                                    <i class="fas {{ $lien['icon'] }}" aria-hidden="true"></i>{{ $lien['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <!-- Informations légales -->
                <nav class="col-lg-3 col-md-6" aria-labelledby="footer-infos">
                    <h2 id="footer-infos" class="public-footer-title">Informations</h2>
                    <ul class="public-footer-links">
                        @foreach ($liensLegaux as $lien)
                            <li>
                                <a href="{{ route($lien['route']) }}">
                                    <i class="fas {{ $lien['icon'] }}" aria-hidden="true"></i>{{ $lien['label'] }}
                                </a>
                            </li>
                        @endforeach

                        @if (! empty($gaId))
                            <li>
                                <a href="{{ route('cookies') }}" data-cookie-settings>
                                    <i class="fas fa-sliders-h" aria-hidden="true"></i>Gérer les cookies
                                </a>
                            </li>
                        @endif
                    </ul>

                    @if (count($reseaux) > 0)
                        <div class="d-flex gap-2 mt-4">
                            @foreach ($reseaux as $reseau)
                                <a href="{{ $reseau['url'] }}" class="btn btn-primary btn-lg text-white"
                                   target="_blank" rel="noopener noreferrer"
                                   aria-label="{{ $reseau['nom'] }} du club (nouvel onglet)">
                                    <i class="fab {{ $reseau['icon'] }}" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </nav>
            </div>
        </div>
    </div>

    <!-- Barre de copyright -->
    <div class="public-footer-bar">
        <div class="container-lg">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} Cercle des Nageurs du Bocage Bressuirais. Tous droits réservés.
                    </p>
                    <p class="mb-0 mt-2 small">
                        Conception et développement
                        <a href="https://mycreanet.fr/realisations-projets/" target="_blank" rel="noopener noreferrer" class="fw-bold">
                            MyCreaNet Agency
                        </a>
                    </p>
                </div>

                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="d-flex flex-wrap justify-content-md-end gap-3">
                        <a href="{{ route('privacy') }}">Politique de confidentialité</a>
                        <a href="{{ route('cookies') }}">Cookies</a>
                        <a href="{{ route('legal') }}">Mentions légales</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
