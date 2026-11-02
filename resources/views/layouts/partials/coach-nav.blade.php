{{-- Menu de l'espace coachs (coachs et administrateurs) --}}
@php
    $menu = [
        ['label' => 'Tableau de bord', 'icon' => 'fa-home', 'route' => 'coach.dashboard', 'active' => ['coach.dashboard']],
        ['label' => 'Séances', 'icon' => 'fa-dumbbell', 'active' => ['coach.training.*', 'coach.exercises.*', 'coach.training-lists.*'], 'items' => [
            ['Séances de la semaine', 'fa-calendar-week', 'coach.training.index', 'coach.training.index'],
            ['Nouvelle séance natation', 'fa-person-swimming', 'coach.training.create', '-', ['type' => 'natation']],
            ['Nouvelle séance PPG', 'fa-dumbbell', 'coach.training.create', '-', ['type' => 'ppg']],
            ['Nouvelle séance maison', 'fa-house', 'coach.training.create', '-', ['type' => 'maison']],
            ['Nouvelle fiche école de natation', 'fa-children', 'coach.training.create', '-', ['type' => 'ecole']],
            ['Nouvelle fiche divers', 'fa-shapes', 'coach.training.create', '-', ['type' => 'divers']],
            ["Bibliothèque d'exercices", 'fa-book-medical', 'coach.exercises.index', 'coach.exercises.*'],
            ['Listes (nages, matériel…)', 'fa-list', 'coach.training-lists.edit', 'coach.training-lists.*'],
        ]],
        ['label' => 'Présences', 'icon' => 'fa-clipboard-check', 'active' => ['coach.presence.*', 'coach.assiduity.*'], 'items' => [
            ['Émargement', 'fa-clipboard-check', 'coach.presence.index', 'coach.presence.*'],
            ['Assiduité', 'fa-chart-column', 'coach.assiduity.index', 'coach.assiduity.*'],
        ]],
        ['label' => 'Planning', 'icon' => 'fa-calendar-week', 'active' => ['coach.planning.*'], 'items' => [
            ['Créneaux', 'fa-clock', 'coach.planning.slots.index', 'coach.planning.slots.*'],
            ['Groupes', 'fa-layer-group', 'coach.planning.groups.index', 'coach.planning.groups.*'],
            ['Nageurs', 'fa-person-swimming', 'coach.planning.swimmers.index', 'coach.planning.swimmers.*'],
        ]],
        ['label' => 'Compétitions', 'icon' => 'fa-trophy', 'active' => ['coach.competitions.*', 'coach.records.*'], 'items' => [
            ['Toutes les compétitions', 'fa-list', 'coach.competitions.index', 'coach.competitions.index'],
            ['Nouvelle compétition', 'fa-plus', 'coach.competitions.create', 'coach.competitions.create'],
            ['Records', 'fa-medal', 'coach.records.index', 'coach.records.index'],
            ['Importer des records FFN', 'fa-file-import', 'coach.records.import-form', 'coach.records.import*'],
        ]],
        ['label' => 'Suivi', 'icon' => 'fa-clipboard-list', 'active' => ['coach.notes.*', 'coach.performances.*'], 'items' => [
            ['Performances', 'fa-stopwatch', 'coach.performances.index', 'coach.performances.*'],
            ['Saisir des temps', 'fa-plus', 'coach.performances.create', 'coach.performances.create'],
            ['Classement du club', 'fa-ranking-star', 'coach.performances.ranking', 'coach.performances.ranking'],
            ['Notes de suivi', 'fa-clipboard-list', 'coach.notes.index', 'coach.notes.*'],
        ]],
        ['label' => 'Documents', 'icon' => 'fa-folder-open', 'route' => 'coach.documents.index', 'active' => ['coach.documents.*']],
    ];
@endphp
<nav class="navbar navbar-expand-lg espace-nav espace-nav--coach" aria-label="Menu de l'espace coachs">
    <div class="container-lg">
        <a class="navbar-brand espace-brand" href="{{ route('coach.dashboard') }}">
            <img src="{{ asset('assets/images/logo/cnbb-logo-800.png') }}" alt="" class="espace-brand-logo">
            <span class="espace-brand-text">CNBB <span class="espace-brand-area">Espace coachs</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#coachNavbar"
                aria-controls="coachNavbar" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="coachNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @foreach ($menu as $entry)
                    @php $isActive = request()->routeIs(...$entry['active']); @endphp
                    @if (isset($entry['items']))
                        <li class="nav-item dropdown admin-menu">
                            <a class="nav-link dropdown-toggle {{ $isActive ? 'active' : '' }}" href="#" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas {{ $entry['icon'] }} me-1" aria-hidden="true"></i>{{ $entry['label'] }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($entry['items'] as $sub)
                                    @php [$label, $icon, $route, $pattern] = $sub; $params = $sub[4] ?? []; @endphp
                                    @continue(! Route::has($route))
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs($pattern) ? 'active' : '' }}" href="{{ route($route, $params) }}"
                                           @if (request()->routeIs($pattern)) aria-current="page" @endif>
                                            <i class="fas {{ $icon }} fa-fw me-2" aria-hidden="true"></i>{{ $label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ route($entry['route']) }}"
                               @if ($isActive) aria-current="page" @endif>
                                <i class="fas {{ $entry['icon'] }} me-1" aria-hidden="true"></i>{{ $entry['label'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- Menu utilisateur --}}
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-sm espace-user-btn dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="espace-avatar me-2" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="d-none d-xl-inline">{{ auth()->user()->first_name ?: auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if (auth()->user()->hasRole('admin'))
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cogs me-2" aria-hidden="true"></i>Administration
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2" aria-hidden="true"></i>Mon profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('home') }}" target="_blank" rel="noopener">
                                <i class="fas fa-globe me-2" aria-hidden="true"></i>Voir le site
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
            </div>
        </div>
    </div>
</nav>
