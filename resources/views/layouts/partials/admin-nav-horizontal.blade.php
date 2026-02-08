<nav class="navbar navbar-expand-lg" style="border-left: 20px solid #ffffff;border-right: 20px solid #ffffff;border-bottom: 10px solid #4ccac6;border-top: 10px solid #4ccac6;background-image: linear-gradient(198deg, rgb(255 255 255) 85%, rgb(96 203 198) 70px);background-attachment: scroll;background-position: bottom;"> 


    <div class="container-lg">


        <!-- Bouton burger pour mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu principal -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold text-primary' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>

                <!-- Articles (dropdown) -->
                <li class="nav-item dropdown">
                    @php $articlesActive = request()->routeIs('admin.posts.*', 'admin.categories.*', 'admin.tags.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $articlesActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Articles
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">
                                <i class="fas fa-newspaper fa-fw me-2"></i>Liste
                                <span class="badge bg-info ms-2">{{ App\Models\Post::count() }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i>Catégories
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
                                <i class="fas fa-tags fa-fw me-2"></i>Tags
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Fiches (dropdown) -->
                <li class="nav-item dropdown">
                    @php $fichesActive = request()->routeIs('admin.fiches.*', 'admin.fiches-categories.*', 'admin.fiches-sous-categories.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $fichesActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Fiches
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.fiches.index', 'admin.fiches.create', 'admin.fiches.edit', 'admin.fiches.show') ? 'active' : '' }}" href="{{ route('admin.fiches.index') }}">
                                <i class="fas fa-file-alt fa-fw me-2"></i>Fiches
                                @php $fichesCount = App\Models\Fiche::count(); @endphp
                                @if($fichesCount > 0)
                                <span class="badge bg-success ms-2">{{ $fichesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.fiches-categories.*') ? 'active' : '' }}" href="{{ route('admin.fiches-categories.index') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i>Catégories
                                @php $fichesCategoriesCount = App\Models\FichesCategory::count(); @endphp
                                @if($fichesCategoriesCount > 0)
                                <span class="badge bg-info ms-2">{{ $fichesCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.fiches-sous-categories.*') ? 'active' : '' }}" href="{{ route('admin.fiches-sous-categories.index') }}">
                                <i class="fas fa-folder-tree fa-fw me-2"></i>Sous-catégories
                                @php $fichesSousCategoriesCount = App\Models\FichesSousCategory::count(); @endphp
                                @if($fichesSousCategoriesCount > 0)
                                <span class="badge bg-secondary ms-2">{{ $fichesSousCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Vidéos (dropdown) -->
                <li class="nav-item dropdown">
                    @php $videosActive = request()->routeIs('admin.videos.*', 'admin.video-categories.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $videosActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Vidéos
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.videos.index', 'admin.videos.create', 'admin.videos.edit', 'admin.videos.show') ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">
                                <i class="fas fa-video fa-fw me-2"></i>Vidéos
                                @php $videosCount = App\Models\Video::count(); @endphp
                                @if($videosCount > 0)
                                <span class="badge bg-danger ms-2">{{ $videosCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.video-categories.*') ? 'active' : '' }}" href="{{ route('admin.video-categories.index') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i>Catégories
                                @php $videoCategoriesCount = App\Models\VideoCategory::count(); @endphp
                                @if($videoCategoriesCount > 0)
                                <span class="badge bg-info ms-2">{{ $videoCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Téléchargements (dropdown) -->
                <li class="nav-item dropdown">
                    @php $downloadsActive = request()->routeIs('admin.downloadables.*', 'admin.download-categories.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $downloadsActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Téléchargements
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.downloadables.*') ? 'active' : '' }}" href="{{ route('admin.downloadables.index') }}">
                                <i class="fas fa-download fa-fw me-2"></i>Téléchargements
                                @php $downloadablesCount = App\Models\Downloadable::count(); @endphp
                                @if($downloadablesCount > 0)
                                <span class="badge bg-success ms-2">{{ $downloadablesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.download-categories.*') ? 'active' : '' }}" href="{{ route('admin.download-categories.index') }}">
                                <i class="fas fa-folder fa-fw me-2"></i>Catégories
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Médias (dropdown) -->
                <li class="nav-item dropdown">
                    @php $mediaActive = request()->routeIs('admin.media.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $mediaActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Médias
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.media.index') ? 'active' : '' }}" href="{{ route('admin.media.index') }}">
                                <i class="fas fa-images fa-fw me-2"></i>Images
                                @php $mediaCount = App\Models\Media::count(); @endphp
                                @if($mediaCount > 0)
                                <span class="badge bg-success ms-2">{{ $mediaCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.media.categories') ? 'active' : '' }}" href="{{ route('admin.media.categories') }}">
                                <i class="fas fa-folder fa-fw me-2"></i>Catégories
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Utilisateurs (dropdown) -->
                <li class="nav-item dropdown">
                    @php $usersActive = request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $usersActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Utilisateurs
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users fa-fw me-2"></i>Utilisateurs
                                @php $usersCount = App\Models\User::count(); @endphp
                                @if($usersCount > 0)
                                <span class="badge bg-primary ms-2">{{ $usersCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                <i class="fas fa-user-tag fa-fw me-2"></i>Rôles
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}">
                                <i class="fas fa-key fa-fw me-2"></i>Permissions
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Système (dropdown) -->
                <li class="nav-item dropdown">
                    @php $systemActive = request()->routeIs('admin.sitemap.*', 'admin.settings.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $systemActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Système
                    </a>
                    <ul class="dropdown-menu">
                        <li class="nav-item">
    <a class="nav-link" href="{{ route('admin.pages.index') }}">
        <i class="fas fa-file-alt"></i> Pages
    </a>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.pages-categories.index') }}">
        <i class="fas fa-folder"></i> Catégories Pages
    </a>
</li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.sitemap.*') ? 'active' : '' }}" href="{{ route('admin.sitemap.index') }}">
                                <i class="fas fa-sitemap fa-fw me-2"></i>Sitemap
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            <!-- Actions utilisateur (droite) -->
            <div class="d-flex align-items-center gap-2">

                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm position-relative rounded-circle" 
                            style="width: 36px; height: 36px;" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            3
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="width: 320px;">
                        <div class="p-3 border-bottom">
                            <h6 class="mb-0">Notifications</h6>
                        </div>
                        <div class="p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 35px; height: 35px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-semibold">Nouvel utilisateur</div>
                                    <small class="text-muted">Il y a 5 minutes</small>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Menu utilisateur -->
                <div class="dropdown">
                    <button class="btn btn-primary text-white btn-sm dropdown-toggle d-flex align-items-center" 
                            data-bs-toggle="dropdown">
                        <div class="bg-light text-danger rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width: 28px; height: 28px; font-size: 12px;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="d-none d-xl-inline">{{ auth()->user()->name }}</span>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i>Mon profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('home') }}">
                                <i class="fas fa-home me-2"></i>Voir le site
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
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