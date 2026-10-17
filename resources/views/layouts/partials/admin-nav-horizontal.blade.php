<nav class="navbar navbar-expand-lg" style="border-bottom: 20px solid #fbfbf5;border-top: 20px solid #fbfbf5;background-image: linear-gradient(18deg, #4babca 85%, #f0a932 70px);background-attachment: scroll;background-position: bottom;border-left: 20px solid #4babca;">

    <div class="container-lg">

        <!-- Bouton burger pour mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu principal -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link admin-menu {{ request()->routeIs('admin.dashboard') ? 'active fw-bold text-white' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>

                <!-- CONTENUS (dropdown) -->
                <li class="nav-item dropdown admin-menu">
                    @php
                        $contenusActive = request()->routeIs(
                            'admin.posts.*',
                            'admin.categories.*',
                            'admin.tags.*',
                            'admin.fiches.*',
                            'admin.fiches-categories.*',
                            'admin.fiches-sous-categories.*',
                            'admin.pages.*',
                            'admin.pages-categories.*',
                            'admin.partners.*',
                            'admin.shop.*'
                        );
                    @endphp
                    <a class="nav-link dropdown-toggle {{ $contenusActive ? 'active fw-bold text-white' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

                        <li><hr class="dropdown-divider"></li>

                        <!-- Partenaires -->
                        <li><h6 class="dropdown-header"><i class="fas fa-handshake me-2"></i>Partenaires</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.partners.index', 'admin.partners.edit') ? 'active' : '' }}" href="{{ route('admin.partners.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des partenaires
                                @php $partnersCount = App\Models\Partner::count(); @endphp
                                @if($partnersCount > 0)
                                    <span class="badge bg-warning text-dark ms-2">{{ $partnersCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.partners.create') ? 'active' : '' }}" href="{{ route('admin.partners.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouveau partenaire
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Boutique vitrine (administrateurs uniquement) -->
                        <li><h6 class="dropdown-header"><i class="fas fa-shopping-bag me-2"></i>Boutique</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.shop.products.index', 'admin.shop.products.edit') ? 'active' : '' }}" href="{{ route('admin.shop.products.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Produits
                                @php $shopProductsCount = App\Models\ShopProduct::count(); @endphp
                                @if($shopProductsCount > 0)
                                    <span class="badge bg-success ms-2">{{ $shopProductsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.shop.categories.*') ? 'active' : '' }}" href="{{ route('admin.shop.categories.index') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i>Catégories
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.shop.products.create') ? 'active' : '' }}" href="{{ route('admin.shop.products.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouveau produit
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- MULTIMÉDIA (dropdown) -->
                <li class="nav-item dropdown admin-menu">
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
                    <a class="nav-link dropdown-toggle {{ $multimediaActive ? 'active fw-bold text-white' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

                <!-- SPORTIF : planning des entraînements et compétitions -->
                <li class="nav-item dropdown admin-menu">
                    @php $sportifActive = request()->routeIs('admin.planning.*', 'admin.competitions.*', 'admin.ffn-licenses.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $sportifActive ? 'active fw-bold text-white' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-person-swimming me-1"></i>Sportif
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Compétitions -->
                        <li><h6 class="dropdown-header"><i class="fas fa-trophy me-2"></i>Compétitions</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.competitions.index', 'admin.competitions.edit') ? 'active' : '' }}" href="{{ route('admin.competitions.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Calendrier
                                @php $competitionsCount = App\Models\Competition::whereDate('ends_on', '>=', today())->count(); @endphp
                                @if($competitionsCount > 0)
                                    <span class="badge bg-primary ms-2" title="À venir">{{ $competitionsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.competitions.create') ? 'active' : '' }}" href="{{ route('admin.competitions.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouvelle compétition
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Planning -->
                        <li><h6 class="dropdown-header"><i class="fas fa-calendar-week me-2"></i>Planning des entraînements</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.planning.slots.*') ? 'active' : '' }}" href="{{ route('admin.planning.slots.index') }}">
                                <i class="fas fa-calendar-alt fa-fw me-2"></i>Créneaux
                                @php $planningSlotsCount = App\Models\PlanningSlot::count(); @endphp
                                @if($planningSlotsCount > 0)
                                    <span class="badge bg-primary ms-2">{{ $planningSlotsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.planning.groups.*') ? 'active' : '' }}" href="{{ route('admin.planning.groups.index') }}">
                                <i class="fas fa-layer-group fa-fw me-2"></i>Groupes
                                @php $planningGroupsCount = App\Models\PlanningGroup::count(); @endphp
                                @if($planningGroupsCount > 0)
                                    <span class="badge bg-info ms-2">{{ $planningGroupsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.planning.swimmers.*') ? 'active' : '' }}" href="{{ route('admin.planning.swimmers.index') }}">
                                <i class="fas fa-person-swimming fa-fw me-2"></i>Nageurs
                                @php $planningSwimmersCount = App\Models\PlanningSwimmer::count(); @endphp
                                @if($planningSwimmersCount > 0)
                                    <span class="badge bg-success ms-2">{{ $planningSwimmersCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Licences FFN (admin uniquement) -->
                        <li><h6 class="dropdown-header"><i class="fas fa-id-card me-2"></i>Licences FFN</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.ffn-licenses.index', 'admin.ffn-licenses.show') ? 'active' : '' }}" href="{{ route('admin.ffn-licenses.index') }}">
                                <i class="fas fa-id-card fa-fw me-2"></i>Licenciés
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.ffn-licenses.import.*') ? 'active' : '' }}" href="{{ route('admin.ffn-licenses.import.create') }}">
                                <i class="fas fa-file-import fa-fw me-2"></i>Importer depuis extraNat
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item" href="{{ route('competitions.index') }}" target="_blank" rel="noopener">
                                <i class="fas fa-external-link-alt fa-fw me-2"></i>Calendrier public
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('schedule') }}" target="_blank" rel="noopener">
                                <i class="fas fa-external-link-alt fa-fw me-2"></i>Planning public
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- SYSTÈME (dropdown) -->
                <li class="nav-item dropdown admin-menu">
                    @php
                        $systemActive = request()->routeIs(
                            'admin.users.*',
                            'admin.user-profiles.*',
                            'admin.roles.*',
                            'admin.permissions.*',
                            'admin.sitemap.*'
                        );
                    @endphp
                    <a class="nav-link dropdown-toggle {{ $systemActive ? 'active fw-bold text-white' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <a class="dropdown-item {{ request()->routeIs('admin.user-profiles.*') ? 'active' : '' }}" href="{{ route('admin.user-profiles.index') }}">
                                <i class="fas fa-id-card fa-fw me-2"></i>Fiches utilisateurs
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

                <!-- GESTION — Architecture Spatiale M2PC -->
                <li class="nav-item dropdown admin-menu">
                    @php
                        $gestionActive = request()->routeIs(
                            'admin.services.*',
                            'admin.structures.*',
                            'admin.espaces.*',
                            'admin.zones.*'
                        );
                    @endphp
                    <a class="nav-link dropdown-toggle {{ $gestionActive ? 'active fw-bold text-white' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sitemap me-1"></i>Gestion
                    </a>
                    <ul class="dropdown-menu">

                        <!-- Architecture Spatiale M2PC -->
                        <li><h6 class="dropdown-header"><i class="fas fa-sitemap me-2"></i>Architecture Spatiale</h6></li>

                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
                                <i class="fas fa-building fa-fw me-2"></i>Services
                                @php $servicesCount = App\Models\Service::count(); @endphp
                                @if($servicesCount > 0)
                                    <span class="badge bg-primary ms-2">{{ $servicesCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.structures.*') ? 'active' : '' }}" href="{{ route('admin.structures.index') }}">
                                <i class="fas fa-layer-group fa-fw me-2"></i>Structures
                                @php $structuresCount = App\Models\Structure::count(); @endphp
                                @if($structuresCount > 0)
                                    <span class="badge bg-success ms-2">{{ $structuresCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.espaces.*') ? 'active' : '' }}" href="{{ route('admin.espaces.index') }}">
                                <i class="fas fa-th-large fa-fw me-2"></i>Espaces
                                @php $espacesCount = App\Models\Espace::count(); @endphp
                                @if($espacesCount > 0)
                                    <span class="badge bg-warning ms-2 text-dark">{{ $espacesCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.zones.*') ? 'active' : '' }}" href="{{ route('admin.zones.index') }}">
                                <i class="fas fa-map-marker-alt fa-fw me-2"></i>Zones
                                @php $zonesCount = App\Models\Zone::count(); @endphp
                                @if($zonesCount > 0)
                                    <span class="badge bg-danger ms-2">{{ $zonesCount }}</span>
                                @endif
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- OUTILS (vide - réservé pour futur développement) -->
                <li class="nav-item dropdown admin-menu">
                    <a class="nav-link dropdown-toggle admin-menu" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-light text-danger rounded-circle d-flex align-items-center justify-content-center me-2"
                             style="width: 28px; height: 28px; font-size: 12px;">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
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
