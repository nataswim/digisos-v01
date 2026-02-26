<nav class="navbar navbar-expand-lg" style="border-bottom: 10px solid #5fcac6;border-top: 10px solid #5fcac6;background-image: linear-gradient(161deg, rgb(255 255 255) 85%, rgb(96 203 198) 70px);background-attachment: scroll;background-position: bottom;">

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

                <!-- CONTENUS (dropdown) -->
                <li class="nav-item dropdown">
                    @php 
                        $contenusActive = request()->routeIs(
                            'admin.posts.*', 
                            'admin.categories.*', 
                            'admin.tags.*',
                            'admin.fiches.*', 
                            'admin.fiches-categories.*', 
                            'admin.fiches-sous-categories.*',
                            'admin.pages.*',
                            'admin.pages-categories.*'
                        ); 
                    @endphp
                    <a class="nav-link dropdown-toggle {{ $contenusActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-water me-1"></i>Contenus
                    </a>
                    <ul class="dropdown-menu">
                        
                        <!-- Articles -->
                        <li><h6 class="dropdown-header"><i class="fas fa-newspaper me-2"></i>Articles</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des articles
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
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- Fiches -->
                        <li><h6 class="dropdown-header"><i class="fas fa-file-alt me-2"></i>Fiches</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.fiches.index', 'admin.fiches.create', 'admin.fiches.edit', 'admin.fiches.show') ? 'active' : '' }}" href="{{ route('admin.fiches.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des fiches
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
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- Pages -->
                        <li><h6 class="dropdown-header"><i class="fas fa-file me-2"></i>Pages</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des pages
                                @php $pagesCount = App\Models\Page::count(); @endphp
                                @if($pagesCount > 0)
                                    <span class="badge bg-primary ms-2">{{ $pagesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.pages-categories.*') ? 'active' : '' }}" href="{{ route('admin.pages-categories.index') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i>Catégories
                                @php $pagesCategoriesCount = App\Models\PagesCategory::count(); @endphp
                                @if($pagesCategoriesCount > 0)
                                    <span class="badge bg-info ms-2">{{ $pagesCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                        
                    </ul>
                </li>

                <!-- MULTIMÉDIA (dropdown) -->
                <li class="nav-item dropdown">
                    @php 
                        $multimediaActive = request()->routeIs(
                            'admin.videos.*', 
                            'admin.video-categories.*',
                            'admin.media.*',
                            'admin.photo-galleries.*',
                            'admin.banners.*',
                            'admin.downloadables.*', 
                            'admin.download-categories.*'
                        ); 
                    @endphp
                    <a class="nav-link dropdown-toggle {{ $multimediaActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-water me-1"></i>Multimédia
                    </a>
                    <ul class="dropdown-menu">
                        
                        <!-- Vidéos -->
                        <li><h6 class="dropdown-header"><i class="fas fa-video me-2"></i>Vidéos</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.videos.index', 'admin.videos.create', 'admin.videos.edit', 'admin.videos.show') ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des vidéos
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
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- Images -->
                        <li><h6 class="dropdown-header"><i class="fas fa-image me-2"></i>Images</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.media.index', 'admin.media.show') ? 'active' : '' }}" href="{{ route('admin.media.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des images
                                @php $mediaCount = App\Models\Media::count(); @endphp
                                @if($mediaCount > 0)
                                    <span class="badge bg-warning ms-2">{{ $mediaCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.media.categories') ? 'active' : '' }}" href="{{ route('admin.media.categories') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i>Catégories
                                @php $mediaCategoriesCount = App\Models\MediaCategory::count(); @endphp
                                @if($mediaCategoriesCount > 0)
                                    <span class="badge bg-info ms-2">{{ $mediaCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- Galeries photo -->
                        <li><h6 class="dropdown-header"><i class="fas fa-images me-2"></i>Galeries photo</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.photo-galleries.*') ? 'active' : '' }}" href="{{ route('admin.photo-galleries.index') }}">
                                <i class="fas fa-layer-group fa-fw me-2"></i>Galeries
                                @php $photoGalleriesCount = App\Models\PhotoGallery::count(); @endphp
                                @if($photoGalleriesCount > 0)
                                    <span class="badge bg-purple ms-2">{{ $photoGalleriesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
                                <i class="fas fa-panorama fa-fw me-2"></i>Bannières
                                @php $bannersCount = App\Models\Banner::where('is_active', true)->count(); @endphp
                                @if($bannersCount > 0)
                                    <span class="badge bg-primary ms-2">{{ $bannersCount }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- Documents -->
                        <li><h6 class="dropdown-header"><i class="fas fa-file-pdf me-2"></i>Documents</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.downloadables.*') ? 'active' : '' }}" href="{{ route('admin.downloadables.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des documents
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

                <!-- SYSTÈME (dropdown) -->
                <li class="nav-item dropdown">
                    @php 
                        $systemActive = request()->routeIs(
                            'admin.users.*', 
                            'admin.roles.*', 
                            'admin.permissions.*',
                            'admin.sitemap.*'
                        ); 
                    @endphp
                    <a class="nav-link dropdown-toggle {{ $systemActive ? 'active fw-bold text-primary' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-water me-1"></i>Système
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
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.sitemap.*') ? 'active' : '' }}" href="{{ route('admin.sitemap.index') }}">
                                <i class="fas fa-sitemap fa-fw me-2"></i>Sitemap
                            </a>
                        </li>
                        
                    </ul>
                </li>

                <!-- GESTION (vide - réservé pour futur développement) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-water me-1"></i>Gestion
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <span class="dropdown-item-text text-muted fst-italic">
                                <i class="fas fa-info-circle me-2"></i>Section en développement
                            </span>
                        </li>
                    </ul>
                </li>

                <!-- OUTILS (vide - réservé pour futur développement) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       <i class="fas fa-water me-1"></i>Outils
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <span class="dropdown-item-text text-muted fst-italic">
                                <i class="fas fa-info-circle me-2"></i>Section en développement
                            </span>
                        </li>
                    </ul>
                </li>

            </ul>

            <!-- Actions utilisateur (droite) -->
            <div class="d-flex align-items-center gap-2">
                <!-- Menu utilisateur -->
                <div class="dropdown">
                    <button class="btn btn-warning text-white btn-sm dropdown-toggle d-flex align-items-center"
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