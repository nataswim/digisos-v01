<nav class="navbar navbar-expand-sm espace-subnav" aria-label="Mon espace">
    <div class="container-lg">
        <ul class="navbar-nav d-flex flex-row gap-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                   href="{{ route('user.dashboard') }}">
                    <i class="fas fa-home me-1"></i>Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.user-profile.show') ? 'active' : '' }}"
                   href="{{ route('user.user-profile.show') }}">
                    <i class="fas fa-id-card me-1"></i>Ma fiche
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.profile.edit') ? 'active' : '' }}"
                   href="{{ route('user.profile.edit') }}">
                    <i class="fas fa-user me-1"></i>Mon profil
                </a>
            </li>
        </ul>
    </div>
</nav>
