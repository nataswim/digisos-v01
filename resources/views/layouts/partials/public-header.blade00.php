{{--
    Menu principal du site public.
    Styles : resources/scss/layout/_public.scss (.public-navbar) et themes/_custom.scss (.navurl)
--}}
@php
    // Rubriques principales
    $menu = [
        ['label' => 'Vie du club',     'route' => 'posts.public.index',  'active' => 'posts.public.*'],
        ['label' => 'Infos pratiques', 'route' => 'public.fiches.index', 'active' => 'public.fiches.*'],
        ['label' => 'Au fil de l\'eau', 'route' => 'public.videos.index', 'active' => 'public.videos.*'],
        ['label' => 'Ressources',      'route' => 'ebook.index',         'active' => 'ebook.*'],
    ];

    // Sous-menu « Le Club »
    $clubMenu = [
        ['label' => 'Présentation et historique', 'icon' => 'fa-water',         'route' => 'about',                      'active' => 'about'],
        ['label' => 'Informations du club',       'icon' => 'fa-file-alt',      'route' => 'public.pages.index',         'active' => 'public.pages.*'],
        ['label' => 'Installations',              'icon' => 'fa-swimming-pool', 'route' => 'public.installations.index', 'active' => 'public.installations.*'],
        ['label' => 'Galeries photo',             'icon' => 'fa-images',        'route' => 'galleries.index',            'active' => 'galleries.*'],
        ['label' => 'Contact',                    'icon' => 'fa-envelope',      'route' => 'contact',                    'active' => 'contact'],
    ];

    $clubActive = request()->routeIs('about', 'public.pages.*', 'public.installations.*', 'galleries.*', 'contact');
@endphp

<nav class="navbar navbar-expand-xl public-navbar" aria-label="Menu principal">
    <div class="container-lg">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/logo/Logo-CNBB-Natation-9.png') }}"
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
                    <li class="nav-item">
                        <a class="nav-link navurl {{ request()->routeIs($item['active']) ? 'active' : '' }}"
                           href="{{ route($item['route']) }}"
                           @if (request()->routeIs($item['active'])) aria-current="page" @endif>
                            <i class="fas fa-water me-2" aria-hidden="true"></i>{{ $item['label'] }}
                        </a>
                    </li>
                @endforeach

                <li class="nav-item dropdown">
                    <a class="nav-link navurl dropdown-toggle {{ $clubActive ? 'active' : '' }}"
                       href="#" id="clubDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-water me-2" aria-hidden="true"></i>Le Club
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="clubDropdown">
                        @foreach ($clubMenu as $item)
                            <li>
                                <a class="dropdown-item {{ request()->routeIs($item['active']) ? 'active' : '' }}"
                                   href="{{ route($item['route']) }}">
                                    <i class="fas {{ $item['icon'] }} me-2" aria-hidden="true"></i>{{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            <!-- Actions : recherche, inscription au club, compte -->
            <div class="public-navbar-actions d-flex align-items-center gap-2">
                <a href="{{ route('search') }}" class="btn btn-sm btn-light border" title="Rechercher sur le site">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span class="visually-hidden">Rechercher sur le site</span>
                </a>

                <a href="{{ route('pricing') }}" class="btn btn-sm btn-primary text-white">
                    <i class="fas fa-clipboard-check me-1" aria-hidden="true"></i>S'inscrire au club
                </a>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-sm btn-warning text-white dropdown-toggle d-flex align-items-center"
                                type="button" id="userDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="public-navbar-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="d-none d-md-inline">{{ auth()->user()->first_name ?: auth()->user()->name }}</span>
                            <span class="visually-hidden">Mon compte</span>
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
