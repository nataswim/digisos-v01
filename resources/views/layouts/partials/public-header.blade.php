{{--
    Menu principal du site public.
    Styles : resources/scss/layout/_public.scss (.public-navbar) et themes/_custom.scss (.navurl)

    Pour modifier le menu, il suffit de changer le tableau $menu ci-dessous :
      - une entrée avec « route » est un lien simple ;
      - une entrée avec « children » est un sous-menu déroulant ;
      - dans un sous-menu, ['divider' => true] ajoute un trait de séparation.
    « active » indique les routes pour lesquelles l'entrée est surlignée.
--}}
@php
    // « color » : couleur de fond au survol de la rubrique et de ses liens (assez foncée pour un texte blanc lisible)
    $menu = [
        [
            'label'    => 'Vie du club',
            'id'       => 'vieDuClubDropdown',
            'color'    => '#24679a',
            'children' => [
                ['label' => 'Actualités',       'icon' => 'fa-newspaper',   'route' => 'posts.public.index',  'active' => ['posts.public.*']],
                ['label' => 'Infos pratiques',  'icon' => 'fa-info-circle', 'route' => 'public.fiches.index', 'active' => ['public.fiches.*']],
                ['label' => 'Ressources',       'icon' => 'fa-download',    'route' => 'ebook.index',         'active' => ['ebook.*']],
                ['divider' => true],
                ['label' => 'Au fil de l\'eau', 'icon' => 'fa-play-circle', 'route' => 'public.videos.index', 'active' => ['public.videos.*']],
                ['label' => 'Galeries photo',   'icon' => 'fa-images',      'route' => 'galleries.index',     'active' => ['galleries.*']],
                ['label' => 'Revue de presse',  'icon' => 'fa-newspaper',   'route' => 'press',               'active' => ['press']],
                ['divider' => true],
                ['label' => 'Présentation et historique', 'icon' => 'fa-water',         'route' => 'about',                      'active' => ['about']],
                ['label' => 'Informations du club',       'icon' => 'fa-file-alt',      'route' => 'public.pages.index',         'active' => ['public.pages.*']],
                ['label' => 'L\'équipe du club',          'icon' => 'fa-users',         'route' => 'team.index',                 'active' => ['team.*']],
                ['label' => 'Installations',              'icon' => 'fa-swimming-pool', 'route' => 'public.installations.index', 'active' => ['public.installations.*']],
                ['label' => 'Contact',                    'icon' => 'fa-envelope',      'route' => 'contact',                    'active' => ['contact']],
            ],
        ],

        [
            'label'    => 'Compétition',
            'id'       => 'competitionDropdown',
            'color'    => '#b84d0e',
            'children' => [
                ['label' => 'Calendrier des compétitions', 'icon' => 'fa-trophy',      'route' => 'competitions.index',   'active' => ['competitions.*']],
                ['label' => 'Grilles de qualification',    'icon' => 'fa-stopwatch',   'route' => 'qualifications.index', 'active' => ['qualifications.*']],
                ['label' => 'Records',                     'icon' => 'fa-medal',       'route' => 'records.index',        'active' => ['records.*']],
                ['label' => 'Liens utiles',                'icon' => 'fa-link',        'route' => 'links.index',          'active' => ['links.*']],
                ['divider' => true],
                ['label' => 'Outils & calculateurs',       'icon' => 'fa-calculator',  'route' => 'tools.index',          'active' => ['tools.*']],
            ],
        ],

        [
            'label'    => 'Créneaux',
            'id'       => 'creneauxDropdown',
            'color'    => '#157a5a',
            'children' => [
                ['label' => 'Groupes et horaires', 'icon' => 'fa-calendar-alt', 'route' => 'schedule', 'active' => ['schedule']],
                ['label' => 'Le parcours du nageur', 'icon' => 'fa-route', 'route' => 'parcours', 'active' => ['parcours']],
                ['label' => 'S\'inscrire au club', 'icon' => 'fa-user-plus',    'route' => 'pricing',  'active' => ['pricing']],
            ],
        ],

        [
            'label'    => 'Partenaires',
            'id'       => 'partenairesDropdown',
            'color'    => '#7d3c98',
            'children' => [
                ['label' => 'Nos partenaires', 'icon' => 'fa-handshake',          'route' => 'partners.index',   'active' => ['partners.*']],
                ['label' => 'Devenir mécène',  'icon' => 'fa-hand-holding-heart', 'route' => 'support.mecenat',  'active' => ['support.mecenat']],
                ['label' => 'Faire un don',    'icon' => 'fa-heart',              'route' => 'support.donation', 'active' => ['support.donation']],
            ],
        ],
        ['label' => 'Nous rejoindre', 'route' => 'pricing',    'active' => ['pricing'], 'color' => '#b02a37'],
        ['label' => 'Boutique',       'route' => 'shop.index', 'active' => ['shop.*'],  'color' => '#1c2111'],
    ];
@endphp

<style>
    /* Survol du menu : fond de la couleur de la rubrique (--menu-color), texte blanc */
    .public-navbar .nav-item > .navurl { transition: background-color .15s, color .15s; }
    .public-navbar .nav-item > .navurl:hover,
    .public-navbar .nav-item > .navurl:focus-visible,
    .public-navbar .nav-item > .navurl.show {
        background-image: none !important;
        background-color: var(--menu-color) !important;
        color: #fff !important;
    }
    .public-navbar .nav-item .dropdown-item { border-left: 4px solid transparent; transition: background-color .15s, color .15s; }
    .public-navbar .nav-item .dropdown-item:hover,
    .public-navbar .nav-item .dropdown-item:focus-visible {
        background-color: var(--menu-color);
        border-left-color: #efa525;
        color: #fff;
    }
    .public-navbar .nav-item .dropdown-item:hover i,
    .public-navbar .nav-item .dropdown-item:focus-visible i { color: #fff; }
</style>

<nav class="navbar navbar-expand-xl public-navbar" aria-label="Menu principal">
    <div class="container-lg">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/logo/logo_cnbb_natation.png') }}"
                 alt="CNBB — accueil du site"
                 class="img-fluid" style=" background: #f0f1f0; padding: 5px; border-radius: 20px; ">
        </a>

        <!-- Bouton du menu sur mobile -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Rubriques -->
            <ul class="navbar-nav me-auto ms-xl-4">
                @foreach ($menu as $item)
                    @if (isset($item['children']))
                        @php
                            // Le sous-menu est surligné quand l'une de ses pages est affichée
                            $groupActive = collect($item['children'])
                                ->filter(fn ($child) => isset($child['active']))
                                ->contains(fn ($child) => request()->routeIs(...$child['active']));
                        @endphp
                        <li class="nav-item dropdown" style="--menu-color: {{ $item['color'] ?? '#24679a' }};">
                            <a class="nav-link navurl dropdown-toggle {{ $groupActive ? 'active' : '' }}"
                               href="#" id="{{ $item['id'] }}" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-water me-2" aria-hidden="true"></i>{{ $item['label'] }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="{{ $item['id'] }}">
                                @foreach ($item['children'] as $child)
                                    @if (! empty($child['divider']))
                                        <li><hr class="dropdown-divider"></li>
                                    @else
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs(...$child['active']) ? 'active' : '' }}"
                                               href="{{ route($child['route']) }}"
                                               @if (request()->routeIs(...$child['active'])) aria-current="page" @endif>
                                                <i class="fas {{ $child['icon'] }} me-2" aria-hidden="true"></i>{{ $child['label'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item" style="--menu-color: {{ $item['color'] ?? '#24679a' }};">
                            <a class="nav-link navurl {{ request()->routeIs(...$item['active']) ? 'active' : '' }}"
                               href="{{ route($item['route']) }}"
                               @if (request()->routeIs(...$item['active'])) aria-current="page" @endif>
                                <i class="fas fa-water me-2" aria-hidden="true"></i>{{ $item['label'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <!-- Actions : recherche, compte -->
            <div class="public-navbar-actions d-flex align-items-center gap-2">
                <a href="{{ route('search') }}" class="btn btn-sm btn-light border" title="Rechercher sur le site">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span class="visually-hidden">Rechercher sur le site</span>
                </a>

                @auth
                    @php
                        try { $messagesNonLus = \App\Models\Conversation::unreadCountFor(auth()->user()); } catch (\Throwable $e) { $messagesNonLus = 0; }
                    @endphp
                    <div class="dropdown">
                        <button class="btn btn-sm btn-warning text-white dropdown-toggle d-flex align-items-center position-relative"
                                type="button" id="userDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="public-navbar-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="d-none d-md-inline">{{ auth()->user()->first_name ?: auth()->user()->name }}</span>
                            <span class="visually-hidden">Mon compte</span>
                            @if ($messagesNonLus)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $messagesNonLus }}<span class="visually-hidden"> message(s) non lu(s)</span></span>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="userDropdown">
                            @if (auth()->user()->hasRole('admin'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cog text-danger me-2" aria-hidden="true"></i>Administration
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ get_dashboard_route() }}">
                                    <i class="fas fa-tachometer-alt text-dark me-2" aria-hidden="true"></i>Mon tableau de bord
                                </a>
                            </li>
                            @if (! auth()->user()->hasRole('visitor'))
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('user.messages.index') }}">
                                        <i class="fas fa-envelope text-primary me-2" aria-hidden="true"></i>Mes messages
                                        @if ($messagesNonLus)<span class="badge rounded-pill bg-danger ms-auto">{{ $messagesNonLus }}<span class="visually-hidden"> non lu(s)</span></span>@endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.competitions.index') }}">
                                        <i class="fas fa-trophy text-warning me-2" aria-hidden="true"></i>Mes compétitions
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user text-info me-2" aria-hidden="true"></i>Mon profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i>Se déconnecter
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-warning text-white">
                        <i class="fas fa-sign-in-alt me-1" aria-hidden="true"></i>Connexion
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>