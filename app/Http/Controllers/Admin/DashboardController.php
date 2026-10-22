<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

/**
 * Tableau de bord de l'administration (/admin/dashboard) :
 * une carte par module, rangées par rubrique comme le menu du haut, avec le nombre d'éléments.
 *
 * Pour ajouter un module : une ligne dans MODULES
 *   [libellé, icône Font Awesome, route, modèle à compter (ou null), [singulier, pluriel]]
 */
class DashboardController extends Controller
{
    private const MODULES = [
        'Contenus' => [
            'icon' => 'fa-water',
            'modules' => [
                ['Articles',          'fa-newspaper',     'admin.posts.index',            Models\Post::class,         ['article', 'articles']],
                ['Fiches pratiques',  'fa-file-alt',      'admin.fiches.index',           Models\Fiche::class,        ['fiche', 'fiches']],
                ['Pages',             'fa-file',          'admin.pages.index',            Models\Page::class,         ['page', 'pages']],
                ['Partenaires',       'fa-handshake',     'admin.partners.index',         Models\Partner::class,      ['partenaire', 'partenaires']],
                ['Boutique',          'fa-shopping-bag',  'admin.shop.products.index',    Models\ShopProduct::class,  ['produit', 'produits']],
                ['L\'équipe du club', 'fa-users',         'admin.team.members.index',     Models\TeamMember::class,   ['membre', 'membres']],
                ['Liens utiles',      'fa-link',          'admin.links.items.index',      Models\Link::class,        ['lien', 'liens']],
            ],
        ],
        'Multimédia' => [
            'icon' => 'fa-photo-video',
            'modules' => [
                ['Vidéos',            'fa-video',         'admin.videos.index',           Models\Video::class,        ['vidéo', 'vidéos']],
                ['Images',            'fa-image',         'admin.media.index',            Models\Media::class,        ['image', 'images']],
                ['Galeries photo',    'fa-images',        'admin.photo-galleries.index',  Models\PhotoGallery::class, ['galerie', 'galeries']],
                ['Bannières',         'fa-panorama',      'admin.banners.index',          Models\Banner::class,       ['bannière', 'bannières']],
                ['Documents',         'fa-file-pdf',      'admin.downloadables.index',    Models\Downloadable::class, ['document', 'documents']],
            ],
        ],
        'Sportif' => [
            'icon' => 'fa-swimmer',
            'modules' => [
                ['Compétitions',      'fa-trophy',          'admin.competitions.index',     Models\Competition::class,     ['compétition', 'compétitions']],
                ['Créneaux',          'fa-calendar-alt',    'admin.planning.slots.index',   Models\PlanningSlot::class,    ['créneau', 'créneaux']],
                ['Groupes',           'fa-layer-group',     'admin.planning.groups.index',  Models\PlanningGroup::class,   ['groupe', 'groupes']],
                ['Nageurs',           'fa-person-swimming', 'admin.planning.swimmers.index', Models\PlanningSwimmer::class, ['nageur', 'nageurs']],
                ['Licences FFN',      'fa-id-card',         'admin.ffn-licenses.index',     Models\FfnLicense::class,      ['licencié', 'licenciés']],
            ],
        ],
        'Système' => [
            'icon' => 'fa-cogs',
            'modules' => [
                ['Utilisateurs',        'fa-users-cog',  'admin.users.index',          Models\User::class,        ['compte', 'comptes']],
                ['Fiches utilisateurs', 'fa-id-badge',   'admin.user-profiles.index',  Models\UserProfile::class, ['fiche', 'fiches']],
                ['Rôles',               'fa-user-tag',   'admin.roles.index',          Models\Role::class,        ['rôle', 'rôles']],
                ['Permissions',         'fa-key',        'admin.permissions.index',    Models\Permission::class,  ['permission', 'permissions']],
                ['Plan du site',        'fa-sitemap',    'admin.sitemap.index',        Models\SitemapUrl::class,  ['adresse', 'adresses']],
            ],
        ],
        'Gestion' => [
            'icon' => 'fa-sitemap',
            'modules' => [
                ['Services',          'fa-building',       'admin.services.index',   Models\Service::class,   ['service', 'services']],
                ['Structures',        'fa-layer-group',    'admin.structures.index', Models\Structure::class, ['structure', 'structures']],
                ['Espaces',           'fa-th-large',       'admin.espaces.index',    Models\Espace::class,    ['espace', 'espaces']],
                ['Zones',             'fa-map-marker-alt', 'admin.zones.index',      Models\Zone::class,      ['zone', 'zones']],
            ],
        ],
    ];

    public function __invoke(Request $request): View
    {
        $groups = [];

        foreach (self::MODULES as $title => $group) {
            $cards = [];
            foreach ($group['modules'] as [$label, $icon, $route, $model, $nouns]) {
                if (! Route::has($route)) {
                    continue;   // module absent de cette installation
                }
                $count = $this->count($model);
                $cards[] = [
                    'label' => $label,
                    'icon' => $icon,
                    'url' => route($route),
                    'count' => $count,
                    'count_label' => $count === null ? null : $count . ' ' . ($count > 1 ? $nouns[1] : $nouns[0]),
                ];
            }
            if ($cards) {
                $groups[] = ['title' => $title, 'icon' => $group['icon'], 'cards' => $cards];
            }
        }

        return view('admin.dashboard', compact('groups'));
    }

    /** Nombre d'éléments, ou null si le module n'est pas encore installé (table absente). */
    private function count(?string $model): ?int
    {
        if (! $model || ! class_exists($model)) {
            return null;
        }

        try {
            return $model::count();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
