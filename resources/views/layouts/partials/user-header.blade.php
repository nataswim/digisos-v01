{{-- Navigation principale --}}
<nav class="navbar navbar-expand-lg espace-nav espace-nav--member" aria-label="Menu de mon espace">

    <div class="container-lg">

        {{-- Logo --}}
        <a class="navbar-brand espace-brand" href="{{ route('home') }}" aria-label="CNBB — retour au site">
            <img src="{{ asset('assets/images/logo/cnbb-logo-800.png') }}" alt="" class="espace-brand-logo">
            <span class="espace-brand-text">CNBB <span class="espace-brand-area">Mon espace</span></span>
        </a>

        {{-- Toggle mobile --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarUser">

            {{-- Navigation principale --}}
            <ul class="navbar-nav me-auto ms-lg-4">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('posts.public.*') ? 'active' : 'text-primary' }}"
                       href="{{ route('posts.public.index') }}">
                        <i class="fas fa-water me-2" aria-hidden="true"></i>Actualités
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.fiches.*') ? 'active' : 'text-primary' }}"
                       href="{{ route('public.fiches.index') }}">
                        <i class="fas fa-water me-2" aria-hidden="true"></i>Fiches pratiques
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.videos.*') ? 'active' : 'text-primary' }}"
                       href="{{ route('public.videos.index') }}">
                        <i class="fas fa-water me-2" aria-hidden="true"></i>Tutoriels
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ebook.*') ? 'active' : 'text-primary' }}"
                       href="{{ route('ebook.index') }}">
                        <i class="fas fa-water me-2" aria-hidden="true"></i>Documents
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.pages.*') ? 'active' : 'text-primary' }}"
                       href="{{ route('public.pages.index') }}">
                        <i class="fas fa-water me-2" aria-hidden="true"></i>Pages
                    </a>
                </li>

            </ul>

            {{-- Section utilisateur --}}
            <div class="d-flex align-items-center">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-sm espace-user-btn dropdown-toggle d-flex align-items-center"
                                type="button"
                                id="userDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <div class="espace-avatar me-2">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-md-inline">
                                {{ auth()->user()->first_name ?: auth()->user()->name }}
                            </span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">

                            {{-- Accès admin si applicable --}}
                            @if(auth()->user()->hasRole('admin'))
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cog text-danger me-2"></i>Administration
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif

                            {{-- Dashboard --}}
                            <li>
                                <a class="dropdown-item py-2 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                                   href="{{ route('user.dashboard') }}">
                                    <i class="fas fa-tachometer-alt text-dark me-2"></i>Mon tableau de bord
                                </a>
                            </li>

                            {{-- Ma fiche --}}
                            <li>
                                <a class="dropdown-item py-2 {{ request()->routeIs('user.user-profile.show') ? 'active' : '' }}"
                                   href="{{ route('user.user-profile.show') }}">
                                    <i class="fas fa-id-card text-primary me-2"></i>Ma fiche
                                </a>
                            </li>

                            {{-- Mon profil --}}
                            <li>
                                <a class="dropdown-item py-2 {{ request()->routeIs('user.profile.edit') ? 'active' : '' }}"
                                   href="{{ route('user.profile.edit') }}">
                                    <i class="fas fa-user text-info me-2"></i>Mon profil
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            {{-- Déconnexion --}}
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </div>
                @else
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary d-flex align-items-center px-3">
                            <i class="fas fa-user-plus me-1"></i>Connexion
                        </a>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</nav>
