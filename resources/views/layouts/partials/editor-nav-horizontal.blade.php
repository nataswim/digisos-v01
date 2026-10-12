<nav class="navbar navbar-expand-lg" style="border-bottom: 10px solid #4aabca;border-top: 10px solid #4aabca;background-image: linear-gradient(161deg, rgb(255 255 255) 85%, rgb(74 171 202) 70px);background-attachment: scroll;background-position: bottom;">
    <div class="container-lg">
        <button class="navbar-toggler border-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('editor.dashboard') ? 'active fw-bold text-primary' : '' }}"
                       href="{{ route('editor.dashboard') }}">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>

                {{-- Contenus (Articles, Fiches, Pages) --}}
                <li class="nav-item dropdown admin-menu">
                    @php
                        $contenusActive = request()->routeIs(
                            'editor.posts.*',
                            'editor.fiches.*',
                            'editor.pages.*'
                        );
                    @endphp
                    <a class="nav-link dropdown-toggle {{ $contenusActive ? 'active fw-bold text-white' : '' }}"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="fas fa-file-alt me-1"></i>Contenus
                    </a>
                    <ul class="dropdown-menu">
                        {{-- Articles --}}
                        <li><h6 class="dropdown-header"><i class="fas fa-newspaper me-2"></i>Articles</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.posts.*') ? 'active' : '' }}"
                               href="{{ route('editor.posts.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des articles
                                @php $postsCount = App\Models\Post::count(); @endphp
                                @if($postsCount > 0)
                                    <span class="badge bg-info ms-2">{{ $postsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('editor.posts.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouvel article
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        {{-- Fiches --}}
                        <li><h6 class="dropdown-header"><i class="fas fa-file-invoice me-2"></i>Fiches</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.fiches.*') ? 'active' : '' }}"
                               href="{{ route('editor.fiches.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des fiches
                                @php $fichesCount = App\Models\Fiche::count(); @endphp
                                @if($fichesCount > 0)
                                    <span class="badge bg-success ms-2">{{ $fichesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('editor.fiches.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouvelle fiche
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        {{-- Pages --}}
                        <li><h6 class="dropdown-header"><i class="fas fa-copy me-2"></i>Pages</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.pages.*') ? 'active' : '' }}"
                               href="{{ route('editor.pages.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des pages
                                @php $pagesCount = App\Models\Page::count(); @endphp
                                @if($pagesCount > 0)
                                    <span class="badge bg-primary ms-2">{{ $pagesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('editor.pages.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouvelle page
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Multimédia (Vidéos, Galeries, Médiathèque) --}}
                <li class="nav-item dropdown admin-menu">
                    @php
                        $multimediaActive = request()->routeIs(
                            'editor.videos.*',
                            'editor.photo-galleries.*',
                            'editor.media.*'
                        );
                    @endphp
                    <a class="nav-link dropdown-toggle {{ $multimediaActive ? 'active fw-bold text-white' : '' }}"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="fas fa-photo-video me-1"></i>Multimédia
                    </a>
                    <ul class="dropdown-menu">
                        {{-- Vidéos --}}
                        <li><h6 class="dropdown-header"><i class="fas fa-video me-2"></i>Vidéos</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.videos.*') ? 'active' : '' }}"
                               href="{{ route('editor.videos.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des vidéos
                                @php $videosCount = App\Models\Video::count(); @endphp
                                @if($videosCount > 0)
                                    <span class="badge bg-danger ms-2">{{ $videosCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('editor.videos.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouvelle vidéo
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        {{-- Galeries Photos --}}
                        <li><h6 class="dropdown-header"><i class="fas fa-images me-2"></i>Galeries Photos</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.photo-galleries.*') ? 'active' : '' }}"
                               href="{{ route('editor.photo-galleries.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Liste des galeries
                                @php $galleriesCount = App\Models\PhotoGallery::count(); @endphp
                                @if($galleriesCount > 0)
                                    <span class="badge bg-warning ms-2">{{ $galleriesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('editor.photo-galleries.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouvelle galerie
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        {{-- Médiathèque --}}
                        <li><h6 class="dropdown-header"><i class="fas fa-folder-open me-2"></i>Médiathèque</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.media.index') ? 'active' : '' }}"
                               href="{{ route('editor.media.index') }}">
                                <i class="fas fa-images fa-fw me-2"></i>Tous les médias
                                @php $mediaCount = App\Models\Media::count(); @endphp
                                @if($mediaCount > 0)
                                    <span class="badge bg-secondary ms-2">{{ $mediaCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- SPORTIF : planning des entraînements et compétitions -->
                <li class="nav-item dropdown admin-menu">
                    @php $sportifActive = request()->routeIs('editor.planning.*', 'editor.competitions.*'); @endphp
                    <a class="nav-link dropdown-toggle {{ $sportifActive ? 'active fw-bold text-white' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-person-swimming me-1"></i>Sportif
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Compétitions -->
                        <li><h6 class="dropdown-header"><i class="fas fa-trophy me-2"></i>Compétitions</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.competitions.index', 'editor.competitions.edit') ? 'active' : '' }}" href="{{ route('editor.competitions.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i>Calendrier
                                @php $competitionsCount = App\Models\Competition::whereDate('ends_on', '>=', today())->count(); @endphp
                                @if($competitionsCount > 0)
                                    <span class="badge bg-primary ms-2" title="À venir">{{ $competitionsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.competitions.create') ? 'active' : '' }}" href="{{ route('editor.competitions.create') }}">
                                <i class="fas fa-plus fa-fw me-2"></i>Nouvelle compétition
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Planning -->
                        <li><h6 class="dropdown-header"><i class="fas fa-calendar-week me-2"></i>Planning des entraînements</h6></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.planning.slots.*') ? 'active' : '' }}" href="{{ route('editor.planning.slots.index') }}">
                                <i class="fas fa-calendar-alt fa-fw me-2"></i>Créneaux
                                @php $planningSlotsCount = App\Models\PlanningSlot::count(); @endphp
                                @if($planningSlotsCount > 0)
                                    <span class="badge bg-primary ms-2">{{ $planningSlotsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.planning.groups.*') ? 'active' : '' }}" href="{{ route('editor.planning.groups.index') }}">
                                <i class="fas fa-layer-group fa-fw me-2"></i>Groupes
                                @php $planningGroupsCount = App\Models\PlanningGroup::count(); @endphp
                                @if($planningGroupsCount > 0)
                                    <span class="badge bg-info ms-2">{{ $planningGroupsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('editor.planning.swimmers.*') ? 'active' : '' }}" href="{{ route('editor.planning.swimmers.index') }}">
                                <i class="fas fa-person-swimming fa-fw me-2"></i>Nageurs
                                @php $planningSwimmersCount = App\Models\PlanningSwimmer::count(); @endphp
                                @if($planningSwimmersCount > 0)
                                    <span class="badge bg-success ms-2">{{ $planningSwimmersCount }}</span>
                                @endif
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

                {{-- Statistiques --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('editor.stats') ? 'active fw-bold text-primary' : '' }}"
                       href="{{ route('editor.stats') }}">
                        <i class="fas fa-chart-bar me-1"></i>Statistiques
                    </a>
                </li>

            </ul>

            {{-- Menu utilisateur --}}
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-danger text-white btn-sm dropdown-toggle d-flex align-items-center"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
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
                            <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                <i class="fas fa-globe me-2"></i>Voir le site
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
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
