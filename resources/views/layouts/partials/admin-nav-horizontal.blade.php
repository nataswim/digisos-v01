{{--
    Menu de l'administration — trois niveaux :
      1. rubrique (barre du haut)          ex. « Contenus »
      2. module (menu déroulant)           ex. « Articles »  → au survol (ou au clic / toucher)…
      3. pages du module (sous-menu)       ex. « Liste des articles », « Catégories », « Tags »

    Pour modifier le menu : changer le tableau $menu ci-dessous.
      - rubrique : label, icon, et soit route (lien direct), soit sections ;
      - section  : label, icon, links (external => true : liens ouverts dans un nouvel onglet) ;
      - lien     : [libellé, icône, route, routes actives (motifs), compteur facultatif (fonction)].
    Les liens dont la route n'existe pas sont ignorés ; une section vide disparaît.
    Styles : public/css/espaces.css (§ « Menu de l'administration »).
--}}
@php
    $menu = [
        [
            'label' => 'Tableau de bord', 'icon' => 'fa-home',
            'route' => 'admin.dashboard', 'active' => ['admin.dashboard'],
        ],
        [
            'label' => 'Contenus', 'icon' => 'fa-pen-nib',
            'sections' => [
                ['label' => 'Articles', 'icon' => 'fa-newspaper', 'links' => [
                    ['Liste des articles', 'fa-list', 'admin.posts.index', ['admin.posts.*'], fn () => \App\Models\Post::count()],
                    ['Catégories', 'fa-folder-open', 'admin.categories.index', ['admin.categories.*']],
                    ['Tags', 'fa-tags', 'admin.tags.index', ['admin.tags.*']],
                ]],
                ['label' => 'Fiches pratiques', 'icon' => 'fa-file-alt', 'links' => [
                    ['Liste des fiches', 'fa-list', 'admin.fiches.index', ['admin.fiches.*'], fn () => \App\Models\Fiche::count()],
                    ['Catégories', 'fa-folder-open', 'admin.fiches-categories.index', ['admin.fiches-categories.*'], fn () => \App\Models\FichesCategory::count()],
                    ['Sous-catégories', 'fa-folder-tree', 'admin.fiches-sous-categories.index', ['admin.fiches-sous-categories.*'], fn () => \App\Models\FichesSousCategory::count()],
                ]],
                ['label' => 'Pages', 'icon' => 'fa-file', 'links' => [
                    ['Liste des pages', 'fa-list', 'admin.pages.index', ['admin.pages.*'], fn () => \App\Models\Page::count()],
                    ['Catégories', 'fa-folder-open', 'admin.pages-categories.index', ['admin.pages-categories.*'], fn () => \App\Models\PagesCategory::count()],
                ]],
                ['label' => 'Partenaires', 'icon' => 'fa-handshake', 'links' => [
                    ['Liste des partenaires', 'fa-list', 'admin.partners.index', ['admin.partners.index', 'admin.partners.edit'], fn () => \App\Models\Partner::count()],
                    ['Nouveau partenaire', 'fa-plus', 'admin.partners.create', ['admin.partners.create']],
                ]],
                ['label' => 'Boutique', 'icon' => 'fa-shopping-bag', 'links' => [
                    ['Produits', 'fa-list', 'admin.shop.products.index', ['admin.shop.products.index', 'admin.shop.products.edit'], fn () => \App\Models\ShopProduct::count()],
                    ['Catégories', 'fa-folder-open', 'admin.shop.categories.index', ['admin.shop.categories.*']],
                    ['Nouveau produit', 'fa-plus', 'admin.shop.products.create', ['admin.shop.products.create']],
                ]],
                ['label' => 'L\'équipe du club', 'icon' => 'fa-users', 'links' => [
                    ['Membres', 'fa-id-badge', 'admin.team.members.index', ['admin.team.members.index', 'admin.team.members.edit'], fn () => \App\Models\TeamMember::count()],
                    ['Sections', 'fa-layer-group', 'admin.team.sections.index', ['admin.team.sections.*']],
                    ['Nouveau membre', 'fa-plus', 'admin.team.members.create', ['admin.team.members.create']],
                ]],
                ['label' => 'Liens utiles', 'icon' => 'fa-link', 'links' => [
                    ['Liens', 'fa-list', 'admin.links.items.index', ['admin.links.items.index', 'admin.links.items.edit'], fn () => \App\Models\Link::count()],
                    ['Catégories', 'fa-folder-open', 'admin.links.categories.index', ['admin.links.categories.*']],
                    ['Nouveau lien', 'fa-plus', 'admin.links.items.create', ['admin.links.items.create']],
                ]],
            ],
        ],
        [
            'label' => 'Multimédia', 'icon' => 'fa-photo-video',
            'sections' => [
                ['label' => 'Vidéos', 'icon' => 'fa-video', 'links' => [
                    ['Liste des vidéos', 'fa-list', 'admin.videos.index', ['admin.videos.*'], fn () => \App\Models\Video::count()],
                    ['Catégories', 'fa-folder-open', 'admin.video-categories.index', ['admin.video-categories.*'], fn () => \App\Models\VideoCategory::count()],
                ]],
                ['label' => 'Images', 'icon' => 'fa-image', 'links' => [
                    ['Médiathèque', 'fa-list', 'admin.media.index', ['admin.media.index', 'admin.media.show'], fn () => \App\Models\Media::count()],
                    ['Catégories', 'fa-folder-open', 'admin.media.categories', ['admin.media.categories'], fn () => \App\Models\MediaCategory::count()],
                ]],
                ['label' => 'Galeries et bannières', 'icon' => 'fa-images', 'links' => [
                    ['Galeries photo', 'fa-layer-group', 'admin.photo-galleries.index', ['admin.photo-galleries.*'], fn () => \App\Models\PhotoGallery::count()],
                    ['Bannières', 'fa-panorama', 'admin.banners.index', ['admin.banners.*'], fn () => \App\Models\Banner::where('is_active', true)->count()],
                ]],
                ['label' => 'Documents', 'icon' => 'fa-file-pdf', 'links' => [
                    ['Liste des documents', 'fa-list', 'admin.downloadables.index', ['admin.downloadables.*'], fn () => \App\Models\Downloadable::count()],
                    ['Catégories', 'fa-folder', 'admin.download-categories.index', ['admin.download-categories.*']],
                ]],
            ],
        ],
        [
            'label' => 'Sportif', 'icon' => 'fa-person-swimming',
            'sections' => [
                ['label' => 'Compétitions', 'icon' => 'fa-trophy', 'links' => [
                    ['Calendrier', 'fa-list', 'admin.competitions.index', ['admin.competitions.index', 'admin.competitions.edit', 'admin.competitions.entries.*', 'admin.competitions.program.*'], fn () => \App\Models\Competition::whereDate('ends_on', '>=', today())->count()],
                    ['Nouvelle compétition', 'fa-plus', 'admin.competitions.create', ['admin.competitions.create']],
                ]],
                ['label' => 'Planning des entraînements', 'icon' => 'fa-calendar-week', 'links' => [
                    ['Créneaux', 'fa-calendar-alt', 'admin.planning.slots.index', ['admin.planning.slots.*'], fn () => \App\Models\PlanningSlot::count()],
                    ['Groupes', 'fa-layer-group', 'admin.planning.groups.index', ['admin.planning.groups.*'], fn () => \App\Models\PlanningGroup::count()],
                    ['Nageurs', 'fa-person-swimming', 'admin.planning.swimmers.index', ['admin.planning.swimmers.*'], fn () => \App\Models\PlanningSwimmer::count()],
                ]],
                ['label' => 'Licences FFN', 'icon' => 'fa-id-card', 'links' => [
                    ['Licenciés', 'fa-id-card', 'admin.ffn-licenses.index', ['admin.ffn-licenses.index', 'admin.ffn-licenses.show']],
                    ['Importer depuis extraNat', 'fa-file-import', 'admin.ffn-licenses.import.create', ['admin.ffn-licenses.import.*']],
                ]],
                ['label' => 'Pages publiques', 'icon' => 'fa-external-link-alt', 'external' => true, 'links' => [
                    ['Calendrier public', 'fa-external-link-alt', 'competitions.index', []],
                    ['Planning public', 'fa-external-link-alt', 'schedule', []],
                ]],
            ],
        ],
        [
            'label' => 'Système', 'icon' => 'fa-cogs',
            'sections' => [
                ['label' => 'Comptes et accès', 'icon' => 'fa-users-cog', 'links' => [
                    ['Utilisateurs', 'fa-users', 'admin.users.index', ['admin.users.*'], fn () => \App\Models\User::count()],
                    ['Fiches utilisateurs', 'fa-id-card', 'admin.user-profiles.index', ['admin.user-profiles.*']],
                    ['Rôles', 'fa-user-tag', 'admin.roles.index', ['admin.roles.*']],
                    ['Permissions', 'fa-key', 'admin.permissions.index', ['admin.permissions.*']],
                ]],
                ['label' => 'Référencement', 'icon' => 'fa-sitemap', 'links' => [
                    ['Plan du site', 'fa-sitemap', 'admin.sitemap.index', ['admin.sitemap.*']],
                ]],
            ],
        ],
        [
            'label' => 'Gestion', 'icon' => 'fa-building',
            'sections' => [
                ['label' => 'Installations', 'icon' => 'fa-swimming-pool', 'links' => [
                    ['Services', 'fa-building', 'admin.services.index', ['admin.services.*'], fn () => \App\Models\Service::count()],
                    ['Structures', 'fa-layer-group', 'admin.structures.index', ['admin.structures.*'], fn () => \App\Models\Structure::count()],
                    ['Espaces', 'fa-th-large', 'admin.espaces.index', ['admin.espaces.*'], fn () => \App\Models\Espace::count()],
                    ['Zones', 'fa-map-marker-alt', 'admin.zones.index', ['admin.zones.*'], fn () => \App\Models\Zone::count()],
                ]],
            ],
        ],
    ];

    // Préparation : liens existants, états actifs, compteurs (un compteur en erreur est simplement masqué)
    $compter = function ($fn) {
        if (! $fn) {
            return null;
        }
        try {
            return (int) $fn();
        } catch (\Throwable $e) {
            return null;
        }
    };

    foreach ($menu as $i => $rubrique) {
        if (isset($rubrique['route'])) {
            $menu[$i]['is_active'] = request()->routeIs(...$rubrique['active']);
            continue;
        }
        $rubriqueActive = false;
        foreach ($rubrique['sections'] as $j => $section) {
            $liens = [];
            foreach ($section['links'] as $lien) {
                [$libelle, $icone, $route, $actifs] = $lien;
                if (! \Illuminate\Support\Facades\Route::has($route)) {
                    continue;
                }
                $actif = $actifs && request()->routeIs(...$actifs);
                $liens[] = [
                    'label' => $libelle,
                    'icon' => $icone,
                    'url' => route($route),
                    'active' => $actif,
                    'count' => $compter($lien[4] ?? null),
                ];
            }
            $menu[$i]['sections'][$j]['links'] = $liens;
            $menu[$i]['sections'][$j]['is_active'] = collect($liens)->contains('active', true);
            $rubriqueActive = $rubriqueActive || $menu[$i]['sections'][$j]['is_active'];
        }
        $menu[$i]['sections'] = array_values(array_filter($menu[$i]['sections'], fn ($s) => count($s['links']) > 0));
        $menu[$i]['is_active'] = $rubriqueActive;
    }
@endphp

<nav class="navbar navbar-expand-lg espace-nav espace-nav--admin" aria-label="Menu de l'administration">
    <div class="container-lg">

        <!-- Repère de l'espace -->
        <a class="navbar-brand espace-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo/cnbb-logo-800.png') }}" alt="" class="espace-brand-logo">
            <span class="espace-brand-text">CNBB <span class="espace-brand-area">Administration</span></span>
        </a>

        <!-- Bouton du menu sur mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 espace-admin-menu">
                @foreach ($menu as $i => $rubrique)
                    @if (isset($rubrique['route']))
                        {{-- Niveau 1 : lien direct --}}
                        <li class="nav-item">
                            <a class="nav-link {{ $rubrique['is_active'] ? 'active' : '' }}" href="{{ route($rubrique['route']) }}"
                               @if ($rubrique['is_active']) aria-current="page" @endif>
                                <i class="fas {{ $rubrique['icon'] }} me-2" aria-hidden="true"></i>{{ $rubrique['label'] }}
                            </a>
                        </li>
                    @elseif (count($rubrique['sections']))
                        {{-- Niveau 1 : rubrique → niveau 2 : modules --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $rubrique['is_active'] ? 'active' : '' }}" href="#" role="button"
                               data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" id="menu-rubrique-{{ $i }}">
                                <i class="fas {{ $rubrique['icon'] }} me-2" aria-hidden="true"></i>{{ $rubrique['label'] }}
                            </a>
                            <ul class="dropdown-menu espace-menu-l2" aria-labelledby="menu-rubrique-{{ $i }}">
                                @foreach ($rubrique['sections'] as $j => $section)
                                    {{-- Niveau 2 : module → niveau 3 : pages (au survol, au clic ou au toucher) --}}
                                    <li class="espace-submenu {{ $section['is_active'] ? 'is-current' : '' }}">
                                        <button type="button" class="dropdown-item espace-submenu-toggle"
                                                aria-expanded="false" aria-controls="menu-{{ $i }}-{{ $j }}">
                                            <i class="fas {{ $section['icon'] }} fa-fw me-2" aria-hidden="true"></i>
                                            <span class="flex-grow-1">{{ $section['label'] }}</span>
                                            <i class="fas fa-chevron-right espace-submenu-caret" aria-hidden="true"></i>
                                        </button>
                                        <ul class="espace-menu-l3" id="menu-{{ $i }}-{{ $j }}">
                                            <li class="espace-menu-l3-title" aria-hidden="true">
                                                <i class="fas {{ $section['icon'] }} me-2"></i>{{ $section['label'] }}
                                            </li>
                                            @foreach ($section['links'] as $lien)
                                                <li>
                                                    <a class="dropdown-item {{ $lien['active'] ? 'active' : '' }}" href="{{ $lien['url'] }}"
                                                       @if ($lien['active']) aria-current="page" @endif
                                                       @if (! empty($section['external'])) target="_blank" rel="noopener" @endif>
                                                        <i class="fas {{ $lien['icon'] }} fa-fw me-2" aria-hidden="true"></i>
                                                        <span class="flex-grow-1">{{ $lien['label'] }}</span>
                                                        @if ($lien['count'])
                                                            <span class="espace-menu-count">{{ $lien['count'] }}</span>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>

            <!-- Compte de l'administrateur (droite) -->
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-sm espace-user-btn dropdown-toggle d-flex align-items-center"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="espace-avatar me-2" aria-hidden="true">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
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

@once
@push('scripts')
<script>
    /*
     * Menu de l'administration : sous-menus de niveau 3.
     * - Ordinateur : ouverture au survol ; le clic ouvre / ferme aussi (tablettes).
     * - Mobile : clic = déplier sous le module (accordéon).
     * - Clavier : Entrée / Espace ouvre, flèche droite ouvre et entre, flèche gauche / Échap referme.
     */
    document.addEventListener('DOMContentLoaded', function () {
        var menu = document.querySelector('.espace-admin-menu');
        if (!menu) return;

        var grandEcran = function () { return window.innerWidth >= 992; };

        function fermerTout(sauf) {
            menu.querySelectorAll('.espace-submenu.is-open').forEach(function (li) {
                if (li !== sauf) {
                    li.classList.remove('is-open', 'open-left');
                    li.querySelector('.espace-submenu-toggle').setAttribute('aria-expanded', 'false');
                }
            });
        }

        function ouvrir(li, ouvert) {
            fermerTout(li);
            li.classList.toggle('is-open', ouvert);
            li.querySelector('.espace-submenu-toggle').setAttribute('aria-expanded', ouvert ? 'true' : 'false');

            // Sous-menu trop près du bord droit de l'écran : il s'ouvre à gauche
            li.classList.remove('open-left');
            if (ouvert && grandEcran()) {
                var r = li.querySelector('.espace-menu-l3').getBoundingClientRect();
                if (r.right > window.innerWidth - 8) li.classList.add('open-left');
            }
        }

        menu.querySelectorAll('.espace-submenu').forEach(function (li) {
            var bouton = li.querySelector('.espace-submenu-toggle');

            bouton.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                ouvrir(li, !li.classList.contains('is-open'));
            });

            li.addEventListener('mouseenter', function () { if (grandEcran()) ouvrir(li, true); });
            li.addEventListener('mouseleave', function () { if (grandEcran()) ouvrir(li, false); });

            bouton.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    ouvrir(li, true);
                    var premier = li.querySelector('.espace-menu-l3 a');
                    if (premier) premier.focus();
                }
            });

            li.querySelector('.espace-menu-l3').addEventListener('keydown', function (e) {
                if (e.key === 'ArrowLeft' || e.key === 'Escape') {
                    e.preventDefault();
                    e.stopPropagation();
                    ouvrir(li, false);
                    bouton.focus();
                }
            });
        });

        // Un menu déroulant qui se ferme referme aussi ses sous-menus ;
        // sur mobile, il s'ouvre avec le module de la page en cours déjà déplié
        menu.querySelectorAll('.dropdown').forEach(function (dd) {
            dd.addEventListener('hidden.bs.dropdown', function () { fermerTout(null); });
            dd.addEventListener('shown.bs.dropdown', function () {
                if (!grandEcran()) {
                    var courant = dd.querySelector('.espace-submenu.is-current');
                    if (courant) ouvrir(courant, true);
                }
            });
        });
    });
</script>
@endpush
@endonce
