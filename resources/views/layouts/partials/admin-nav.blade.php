<nav class="sidebar bg-dark text-white d-flex flex-column" style="width: 280px; min-height: 100vh;">
    <div class="p-4 border-bottom border-secondary">
        <div class="d-flex align-items-center justify-content-center">
            <img src="{{ asset('assets/images/team/nataswim_app_logo_4.png') }}"
                alt="Digital'SOS application"
                class="img-fluid"
                style="height: 80px; width: auto; filter: brightness(0) invert(1);">
        </div>
        <div class="text-center mt-3">
            <h5 class="mb-0 fw-bold">Administration</h5>
            <small class="text-light opacity-75">{{ config('app.name') }}</small>
        </div>
    </div>

    <div class="flex-fill py-3" style="overflow-y: auto;">
        <div class="px-3 mb-2">
            <small class="text-uppercase text-light opacity-50 fw-semibold section-title">Principal</small>
        </div>

        <ul class="nav nav-pills flex-column px-3">
            <li class="nav-item mb-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link text-white d-flex align-items-center rounded {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : '' }}">
                    <i class="fas fa-tachometer-alt fa-fw me-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <div class="px-3 mb-2 mt-4">
            <small class="text-uppercase text-light opacity-50 fw-semibold section-title">Contenu</small>
        </div>

        <ul class="nav nav-pills flex-column px-3">
            <li class="nav-item mb-1">
                <a href="{{ route('admin.media.index') }}"
                    class="nav-link text-white d-flex align-items-center rounded {{ request()->routeIs('admin.media.*') && !request()->routeIs('admin.media.categories') ? 'active bg-primary' : '' }}">
                    <i class="fas fa-images fa-fw me-3"></i>
                    <span>Médias</span>
                    @php $mediaCount = App\Models\Media::count(); @endphp
                    @if($mediaCount > 0)
                    <span class="badge bg-success ms-auto">{{ $mediaCount }}</span>
                    @endif
                </a>
            </li>
             <li class="nav-item mb-1">
                <a href="{{ route('admin.sitemap.index') }}"
                    class="nav-link text-white d-flex align-items-center rounded {{ request()->routeIs('admin.sitemap.*') ? 'active bg-primary' : '' }}">
                    <i class="fas fa-sitemap fa-fw me-3"></i>
                    <span>Sitemap XML</span>
                    @php
                    $sitemapCount = \App\Models\SitemapUrl::approved()->count();
                    $totalUrls = \App\Models\SitemapUrl::count();
                    @endphp
                    @if($sitemapCount > 0)
                    <span class="badge bg-success ms-auto" title="URLs Approuvées">{{ $sitemapCount }}</span>
                    @elseif($totalUrls > 0)
                    <span class="badge bg-warning ms-auto" title="Total URLs">{{ $totalUrls }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item mb-1">
                @php $articlesActive = request()->routeIs('admin.posts.*', 'admin.categories.*', 'admin.tags.*', 'admin.aitext.*'); @endphp
                <a class="nav-link text-white d-flex align-items-center rounded collapsed {{ $articlesActive ? 'active bg-primary' : '' }}"
                    data-bs-toggle="collapse" href="#articlesMenu" role="button"
                    aria-expanded="{{ $articlesActive ? 'true' : 'false' }}"
                    aria-controls="articlesMenu">
                    <i class="fas fa-newspaper fa-fw me-3"></i>
                    <span>Articles</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7em;"></i>
                </a>
                <div class="collapse {{ $articlesActive ? 'show' : '' }}" id="articlesMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.posts.*') && !request()->routeIs('admin.posts.create') ? 'active bg-secondary' : '' }}" href="{{ route('admin.posts.index') }}">
                                <i class="fas fa-list fa-fw me-2"></i> Liste
                                <span class="badge bg-info ms-auto">{{ App\Models\Post::count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.categories.*') && !request()->routeIs('admin.fiches-categories.*') && !request()->routeIs('admin.workout-categories.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.categories.index') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i> Catégories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.tags.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.tags.index') }}">
                                <i class="fas fa-tags fa-fw me-2"></i> Tags
                            </a>
                        </li>
                        @if(auth()->user()->hasRole('admin'))
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.aitext.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.aitext.settings') }}">
                                <i class="fas fa-robot fa-fw me-2"></i> AI Text Optimizer
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>


            <li class="nav-item mb-1">
                @php $fichesActive = request()->routeIs('admin.fiches.*', 'admin.fiches-categories.*'); @endphp
                <a class="nav-link text-white d-flex align-items-center rounded collapsed {{ $fichesActive ? 'active bg-primary' : '' }}"
                    data-bs-toggle="collapse" href="#fichesMenu" role="button"
                    aria-expanded="{{ $fichesActive ? 'true' : 'false' }}"
                    aria-controls="fichesMenu">
                    <i class="fas fa-file-alt fa-fw me-3"></i>
                    <span>Fiches</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7em;"></i>
                </a>
                <div class="collapse {{ $fichesActive ? 'show' : '' }}" id="fichesMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.fiches.index', 'admin.fiches.create', 'admin.fiches.edit', 'admin.fiches.show') ? 'active bg-secondary' : '' }}" href="{{ route('admin.fiches.index') }}">
                                <i class="fas fa-list-alt fa-fw me-2"></i> Fiches
                                @php $fichesCount = App\Models\Fiche::count(); @endphp
                                @if($fichesCount > 0)
                                <span class="badge bg-success ms-auto">{{ $fichesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.fiches-categories.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.fiches-categories.index') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i> Catégories
                                @php $fichesCategoriesCount = App\Models\FichesCategory::count(); @endphp
                                @if($fichesCategoriesCount > 0)
                                <span class="badge bg-info ms-auto">{{ $fichesCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-1">
                @php $videosActive = request()->routeIs('admin.videos.*', 'admin.video-categories.*'); @endphp
                <a class="nav-link text-white d-flex align-items-center rounded collapsed {{ $videosActive ? 'active bg-primary' : '' }}"
                    data-bs-toggle="collapse" href="#videosMenu" role="button"
                    aria-expanded="{{ $videosActive ? 'true' : 'false' }}"
                    aria-controls="videosMenu">
                    <i class="fas fa-video fa-fw me-3"></i>
                    <span>Vidéos</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7em;"></i>
                </a>
                <div class="collapse {{ $videosActive ? 'show' : '' }}" id="videosMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.videos.index', 'admin.videos.create', 'admin.videos.edit', 'admin.videos.show') ? 'active bg-secondary' : '' }}" href="{{ route('admin.videos.index') }}">
                                <i class="fas fa-play-circle fa-fw me-2"></i> Vidéos
                                @php $videosCount = App\Models\Video::count(); @endphp
                                @if($videosCount > 0)
                                <span class="badge bg-danger ms-auto">{{ $videosCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.video-categories.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.video-categories.index') }}">
                                <i class="fas fa-folder-open fa-fw me-2"></i> Catégories
                                @php $videoCategoriesCount = App\Models\VideoCategory::count(); @endphp
                                @if($videoCategoriesCount > 0)
                                <span class="badge bg-info ms-auto">{{ $videoCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-1">
                @php $workoutsActive = request()->routeIs('admin.workouts.*', 'admin.workout-categories.*', 'admin.workout-sections.*'); @endphp
                <a class="nav-link text-white d-flex align-items-center rounded collapsed {{ $workoutsActive ? 'active bg-primary' : '' }}"
                    data-bs-toggle="collapse" href="#workoutsMenu" role="button"
                    aria-expanded="{{ $workoutsActive ? 'true' : 'false' }}"
                    aria-controls="workoutsMenu">
                    <i class="fas fa-dumbbell fa-fw me-3"></i>
                    <span>Workouts</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7em;"></i>
                </a>
                <div class="collapse {{ $workoutsActive ? 'show' : '' }}" id="workoutsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.workouts.index', 'admin.workouts.create', 'admin.workouts.edit', 'admin.workouts.show') ? 'active bg-secondary' : '' }}" href="{{ route('admin.workouts.index') }}">
                                <i class="fas fa-running fa-fw me-2"></i> Workouts
                                @php $workoutsCount = App\Models\Workout::count(); @endphp
                                @if($workoutsCount > 0)
                                <span class="badge bg-warning ms-auto">{{ $workoutsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.workout-categories.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.workout-categories.index') }}">
                                <i class="fas fa-folder fa-fw me-2"></i> Catégories
                                @php $workoutCategoriesCount = App\Models\WorkoutCategory::count(); @endphp
                                @if($workoutCategoriesCount > 0)
                                <span class="badge bg-info ms-auto">{{ $workoutCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.workout-sections.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.workout-sections.index') }}">
                                <i class="fas fa-layer-group fa-fw me-2"></i> Sections
                                @php $workoutSectionsCount = App\Models\WorkoutSection::count(); @endphp
                                @if($workoutSectionsCount > 0)
                                <span class="badge bg-secondary ms-auto">{{ $workoutSectionsCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <div class="px-3 mb-2 mt-4">
            <small class="text-uppercase text-light opacity-50 fw-semibold section-title">Musculation</small>
        </div>

        <ul class="nav nav-pills flex-column px-3">
            <li class="nav-item mb-1">
                @php $trainingActive = request()->routeIs('admin.training.*'); @endphp
                <a class="nav-link text-white d-flex align-items-center rounded collapsed {{ $trainingActive ? 'active bg-primary' : '' }}"
                    data-bs-toggle="collapse" href="#trainingMenu" role="button"
                    aria-expanded="{{ $trainingActive ? 'true' : 'false' }}"
                    aria-controls="trainingMenu">
                    <i class="fas fa-calendar-alt fa-fw me-3"></i>
                    <span>Gestion</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7em;"></i>
                </a>
                <div class="collapse {{ $trainingActive ? 'show' : '' }}" id="trainingMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.training.plans.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.training.plans.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-file-invoice fa-fw me-2"></i>
                                <span>Plans</span>
                                <span class="badge bg-info ms-auto">{{ App\Models\Plan::count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.training.cycles.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.training.cycles.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-sync-alt fa-fw me-2"></i>
                                <span>Cycles</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.training.seances.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.training.seances.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-stopwatch fa-fw me-2"></i>
                                <span>Séances</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.training.series.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.training.series.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-list-ol fa-fw me-2"></i>
                                <span>Séries</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.training.exercices.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.training.exercices.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-running fa-fw me-2"></i>
                                <span>Exercices</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item mb-1">
                            <a href="{{ route('admin.exercice-categories.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.exercice-categories.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-folder fa-fw me-2"></i>
                                <span>Catégories</span>
                                @php $exerciceCategoriesCount = App\Models\ExerciceCategory::count(); @endphp
                                @if($exerciceCategoriesCount > 0)
                                <span class="badge bg-info ms-auto">{{ $exerciceCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.exercice-sous-categories.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.exercice-sous-categories.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-layer-group fa-fw me-2"></i>
                                <span>Sous-catégories</span>
                                @php $exerciceSousCategoriesCount = App\Models\ExerciceSousCategory::count(); @endphp
                                @if($exerciceSousCategoriesCount > 0)
                                <span class="badge bg-secondary ms-auto">{{ $exerciceSousCategoriesCount }}</span>
                                @endif
                            </a>
                        </li>
        </ul>


        <div class="px-3 mb-2 mt-4">
            <small class="text-uppercase text-light opacity-50 fw-semibold section-title">Administration</small>
        </div>

        <ul class="nav nav-pills flex-column px-3">
            <li class="nav-item mb-1">
                @php $adminUsersActive = request()->routeIs('admin.users.*', 'admin.payments.*', 'admin.roles.*', 'admin.permissions.*'); @endphp
                <a class="nav-link text-white d-flex align-items-center rounded collapsed {{ $adminUsersActive ? 'active bg-primary' : '' }}"
                    data-bs-toggle="collapse" href="#adminMenu" role="button"
                    aria-expanded="{{ $adminUsersActive ? 'true' : 'false' }}"
                    aria-controls="adminMenu">
                    <i class="fas fa-user-cog fa-fw me-3"></i>
                    <span> Utilisateurs</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7em;"></i>
                </a>

                <div class="collapse {{ $adminUsersActive ? 'show' : '' }}" id="adminMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-users fa-fw me-2"></i>
                                <span>Utilisateurs</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.payments.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.payments.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-credit-card fa-fw me-2"></i>
                                <span>Paiements</span>
                            </a>
                        </li>
                        <div class="px-3 mt-3">
                            <small class="text-uppercase text-light opacity-50 fw-semibold" style="font-size: 0.7rem;">Accès & Sécurité</small>
                        </div>
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.roles.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.roles.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-user-shield fa-fw me-2"></i>
                                <span>Rôles</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.permissions.index') }}"
                                class="nav-link text-white rounded py-2 d-flex align-items-center {{ request()->routeIs('admin.permissions.*') ? 'active bg-secondary' : '' }}">
                                <i class="fas fa-key fa-fw me-2"></i>
                                <span>Permissions</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>


        <div class="px-3 mb-2 mt-4">
            <small class="text-uppercase text-light opacity-50 fw-semibold section-title">Téléchargements</small>
        </div>

        <ul class="nav nav-pills flex-column px-3">
            <li class="nav-item">
                @php $ebooksActive = request()->routeIs('admin.downloadables.*', 'admin.download-categories.*', 'admin.ebook-files.*'); @endphp
                <a class="nav-link text-white d-flex align-items-center rounded collapsed {{ $ebooksActive ? 'active bg-primary' : '' }}"
                    data-bs-toggle="collapse" href="#ebooksMenu"
                    aria-expanded="{{ $ebooksActive ? 'true' : 'false' }}"
                    aria-controls="ebooksMenu">
                    <i class="fas fa-book fa-fw me-3"></i>
                    <span>eBooks</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7em;"></i>
                </a>

                <div class="collapse {{ $ebooksActive ? 'show' : '' }}" id="ebooksMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.downloadables.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.downloadables.index') }}">
                                <i class="fas fa-download fa-fw me-2"></i> Téléchargements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.download-categories.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.download-categories.index') }}">
                                <i class="fas fa-folder fa-fw me-2"></i> Catégories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white rounded py-2 {{ request()->routeIs('admin.ebook-files.*') ? 'active bg-secondary' : '' }}" href="{{ route('admin.ebook-files.index') }}">
                                <i class="fas fa-file-archive fa-fw me-2"></i> Fichiers eBook
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
<div class="px-3 mb-2 mt-4">
            <small class="text-uppercase text-light opacity-50 fw-semibold section-title">Liens externes</small>
        </div>

        <ul class="nav nav-pills flex-column px-3 mb-3">
            <li class="nav-item mb-1">
                <a href="https://www.nataswim.fr/diffusion/admin/" 
                   target="_blank"
                   rel="noopener noreferrer"
                   class="nav-link text-white d-flex align-items-center rounded">
                    <i class="fas fa-envelope fa-fw me-3"></i>
                    <span>Newsletter</span>
                    <i class="fas fa-external-link-alt ms-auto" style="font-size: 0.7em;"></i>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="https://www.nataswim.fr/analytics/index.php" 
                   target="_blank"
                   rel="noopener noreferrer"
                   class="nav-link text-white d-flex align-items-center rounded">
                    <i class="fas fa-chart-line fa-fw me-3"></i>
                    <span>Analytics</span>
                    <i class="fas fa-external-link-alt ms-auto" style="font-size: 0.7em;"></i>
                </a>
            </li>
        </ul>

        
    <div class="p-3 border-top border-secondary">
        <div class="d-flex align-items-center">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                style="width: 35px; height: 35px; font-size: 14px;">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-fill">
                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                <small class="text-light opacity-75">Administrateur</small>
            </div>
        </div>
    </div>
</nav>