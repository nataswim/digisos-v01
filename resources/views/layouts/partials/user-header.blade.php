<nav class="navbar navbar-expand-lg shadow-sm sticky-top" style="border-left: 20px solid #ffffff;border-right: 20px solid #ffffff;border-bottom: 10px solid #63d0c7;border-top: 10px solid #63d0c7;background-color: #ffffff;">
    <div class="container-lg">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">
            <img src="{{ asset('assets/images/team/nataswim_app_logo_2.png') }}"
                alt="nataswim application pour tous"
                class="img-fluid"
                style="height: 80px; width: auto;">
        </a>

        <!-- Toggle mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="userNav">
            <!-- Navigation principale -->
            <ul class="navbar-nav me-auto ms-lg-4">


                {{-- Contenu Public (Dropdown multi-niveaux) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3 py-2 text-dark"
                        href="#"
                        id="contentDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false" style="font-weight: 600;background-color: #ffffff;box-shadow: 0 2px 1px 0 rgba(0, 0, 0, 0.2), 0 8px 5px 0 rgba(0, 0, 0, 0.19);border: 1px solid #b0c4c6;margin: 5px;">
                        <i class="fas fa-water me-2"></i>Contenus
                    </a>
                    <ul class="dropdown-menu dropdown-menu-large shadow-lg border-0" aria-labelledby="contentDropdown">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('home') }}">
                                <i class="fas fa-home text-success me-2"></i>Accueil site
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="dropdown-header fw-bold text-primary">
                            <i class="fas fa-newspaper me-1"></i>Articles & Fiches
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('posts.public.index') }}">
                                <i class="fas fa-newspaper text-info me-2"></i>Articles dossiers
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('public.fiches.index') }}">
                                <i class="fas fa-file-alt text-teal me-2"></i>Fiches techniques
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-header fw-bold text-primary">
                            <i class="fas fa-video me-1"></i>Contenus visuels
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('public.videos.index') }}">
                                <i class="fas fa-video text-danger me-2"></i>Vidéos
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-header fw-bold text-primary">
                            <i class="fas fa-heartbeat me-1"></i>Entraînement
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('public.workouts.index') }}">
                                <i class="fas fa-clipboard-check text-warning me-2"></i>Séances & plans
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('exercices.index') }}">
                                <i class="fas fa-dumbbell text-success me-2"></i>Exercices musculation
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-header fw-bold text-primary">
                            <i class="fas fa-tools me-1"></i>Ressources
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('ebook.index') }}">
                                <i class="fas fa-download text-danger me-2"></i>Documents & eBooks
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('tools.index') }}">
                                <i class="fas fa-calculator text-info me-2"></i>Outils & calculateurs
                            </a>
                        </li>
                    </ul>
                </li>


                {{-- Mes Carnets --}}
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 {{ request()->routeIs('user.notebooks.*') ? 'active bg-primary text-white' : 'text-dark' }}"
                        href="{{ route('user.notebooks.index') }}" style="font-weight: 600;background-color: #ffffff;box-shadow: 0 2px 1px 0 rgba(0, 0, 0, 0.2), 0 8px 5px 0 rgba(0, 0, 0, 0.19);border: 1px solid #b0c4c6;margin: 5px;">
                        <i class="fas fa-water me-2"></i>Carnets
                    </a>
                </li>
    <!-- NOUVEAU : Lien Calendrier avec Badge -->
    @if(auth()->user()->hasRole('user') || auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.calendar.index') }}" style="font-weight: 600;background-color: #ffffff;box-shadow: 0 2px 1px 0 rgba(0, 0, 0, 0.2), 0 8px 5px 0 rgba(0, 0, 0, 0.19);border: 1px solid #b0c4c6;margin: 5px;">
            <i class="fas fa-water me-2"></i>Planification
            <x-calendar-badge />
        </a>
    </li>
    @endif
                {{-- Mon Espace --}}
                <li class="nav-item" >
                    <a class="nav-link px-3 py-2 {{ request()->routeIs('user.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}"
                        href="{{ route('user.dashboard') }}" style="font-weight: 600;background-color: #ffffff;box-shadow: 0 2px 1px 0 rgba(0, 0, 0, 0.2), 0 8px 5px 0 rgba(0, 0, 0, 0.19);border: 1px solid #b0c4c6;margin: 5px;">
                        <i class="fas fa-water me-2"></i>Profil
                    </a>
                </li>


                {{-- Paiements (Dropdown) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3 py-2 {{ request()->routeIs('payments.*') ? 'active bg-primary text-white' : 'text-dark' }}"
                        href="#"
                        id="paymentsDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false" style="font-weight: 600;background-color: #ffffff;box-shadow: 0 2px 1px 0 rgba(0, 0, 0, 0.2), 0 8px 5px 0 rgba(0, 0, 0, 0.19);border: 1px solid #b0c4c6;margin: 5px;">
                        <i class="fas fa-water me-2"></i>Mon Compte
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0" aria-labelledby="paymentsDropdown">
                        @if(auth()->user()->hasRole('visitor'))
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('payments.index') }}">
                                <i class="fas fa-crown text-warning me-2"></i>Passer Premium
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('payments.history') ? 'active' : '' }}"
                                href="{{ route('payments.history') }}">
                                <i class="fas fa-history text-info me-2"></i>Historique abonnements
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            <!-- Section utilisateur (droite) -->
            <div class="d-flex align-items-center">
                @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger me-3 px-4">
                    <i class="fas fa-cog me-1"></i>Administration
                </a>
                @endif

                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center"
                        type="button"
                        id="userDropdown"
                        data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                            style="width: 32px; height: 32px; font-size: 14px;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="d-none d-md-inline">{{ auth()->user()->first_name ?: auth()->user()->name }}</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">
                        <li class="dropdown-header">
                            <strong>{{ auth()->user()->name }}</strong>
                            <br>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item py-2" href="{{ route('user.dashboard') }}">
                                <i class="fas fa-tachometer-alt text-primary me-2"></i>Mon tableau de bord
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('user.profile.edit') }}">
                                <i class="fas fa-user text-info me-2"></i>Mon profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('user.notebooks.index') }}">
                                <i class="fas fa-book text-success me-2"></i>Mes carnets
                            </a>
                        </li>

                        @if(auth()->user()->hasRole('admin'))
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cog text-danger me-2"></i>Administration
                            </a>
                        </li>
                        @endif

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- CSS personnalisé pour les menus multi-niveaux --}}
@push('styles')
<style>
    /* Dropdown menu large pour le contenu */
    .dropdown-menu-large {
        min-width: 320px;
    }

    /* Style des headers de dropdown */
    .dropdown-header {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem 0.5rem;
    }

    /* Amélioration des items de dropdown */
    .dropdown-item {
        transition: all 0.2s ease;
        border-radius: 0.25rem;
        margin: 0.1rem 0.5rem;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

    .dropdown-item.active {
        background-color: #0d6efd;
        color: white;
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    /* Animation des dropdowns */
    .dropdown-menu {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Style des liens actifs dans la navbar */
    .nav-link.active {
        font-weight: 600;
        border-radius: 0.375rem;
    }

    .nav-link {
        transition: all 0.2s ease;
        border-radius: 0.375rem;
        font-size: 110%;
    }

    .nav-link:hover:not(.active) {
        background-color: rgba(13, 110, 253, 0.1) !important;
        color: #0d6efd !important;
    }

    /* Couleurs personnalisées pour les icônes */
    .text-teal {
        color: #20c997 !important;
    }

    /* Amélioration du bouton utilisateur */
    .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }

    .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .dropdown-menu-large {
            min-width: 100%;
        }

        .nav-link {
            border-radius: 0.25rem;
            margin: 0.25rem 0;
        }

        .dropdown-menu {
            border: none;
            box-shadow: none;
            padding-left: 1rem;
        }

        .dropdown-item {
            padding-left: 2rem;
        }
    }

    /* Scroll smooth pour mobile */
    @media (max-width: 991px) {
        .navbar-collapse {
            max-height: 70vh;
            overflow-y: auto;
        }
    }
</style>
@endpush