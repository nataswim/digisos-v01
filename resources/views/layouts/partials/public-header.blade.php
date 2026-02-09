{{-- Navigation principale --}}
<nav class="navbar navbar-expand-lg" style="border-left: 20px solid #ffffff;border-bottom: 10px solid #4097b5;border-top: 10px solid #4097b5;background-image: linear-gradient(24deg, rgb(255 255 255) 85%, rgb(64 151 181) 70px);background-attachment: fixed;background-position: top;padding: 0px;">

<div class="container-lg">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/logo/digital-sos-logo4.png') }}"
                alt="Digital'SOS application"
                class="img-fluid"
                style="height: 80px; width: auto;">
        </a>

        <!-- Toggle mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Navigation principale -->
            <ul class="navbar-nav me-auto ms-lg-4">

                <li class="nav-item" style="font-weight: 600;">
                    <a class="nav-link navurl px-1 py-2 {{ request()->routeIs('posts.public.*') ? 'active bg-white text-white' : 'text-primary' }}"
                        href="{{ route('posts.public.index') }}">
                        <i class="fas fa-water me-2"></i>Actualités
                    </a>
                </li>
                
                <li class="nav-item" style="font-weight: 600;">
                    <a class="nav-link navurl px-1 py-2 {{ request()->routeIs('public.fiches.*') ? 'active bg-white text-white' : 'text-primary' }}"
                        href="{{ route('public.fiches.index') }}">
                        <i class="fas fa-water me-2"></i>Fiches pratiques
                    </a>
                </li>

                <li class="nav-item" style="font-weight: 600;">
                    <a class="nav-link navurl px-1 py-2 {{ request()->routeIs('public.videos.*') ? 'active bg-white text-white' : 'text-primary' }}"
                        href="{{ route('public.videos.index') }}">
                        <i class="fas fa-water me-2"></i>Tutoriels
                    </a>
                </li>

                <li class="nav-item" style="font-weight: 600;">
                    <a class="nav-link navurl px-1 py-2 {{ request()->routeIs('ebook.*') ? 'active bg-white text-white' : 'text-primary' }}"
                        href="{{ route('ebook.index') }}">
                        <i class="fas fa-water me-2"></i>Documents
                    </a>
                </li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('public.pages.index') }}"><i class="fas fa-water me-2"></i>Pages</a>
</li>
            </ul>

            <!-- Section utilisateur -->
            <div class="d-flex align-items-center">
                @auth
                <div class="dropdown">
                    <button class="btn btn-sm btn-warning text-white dropdown-toggle d-flex align-items-center"
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
                        @if(auth()->user()->hasRole('admin'))
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cog text-danger me-2"></i>Administration
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt text-dark me-2"></i>Mon tableau de bord
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user text-info me-2"></i>Mon profil
                            </a>
                        </li>
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
                @else
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-warning d-flex align-items-center px-2 text-white">
                        <i class="fas fa-user-plus"></i> Connexion
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>