<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

/**
 * Administration → Système → « Aperçu des espaces ».
 *
 * Liens vers les pages de chaque espace (public, visiteur, adhérent, éditeur) pour que
 * l'administrateur vérifie leur apparence. Les pages s'ouvrent dans un nouvel onglet,
 * avec le compte de l'administrateur (ses propres données).
 * Une route absente de l'installation est simplement ignorée.
 */
class AreaPreviewController extends Controller
{
    /** Espaces : titre, icône, couleur (public|member|editor), description, liens [libellé, nom de route]. */
    public const AREAS = [
        'public' => [
            'title' => 'Site public',
            'icon' => 'fa-globe',
            'class' => 'public',
            'text' => 'Ce que voient tous les visiteurs du site, connectés ou non.',
            'links' => [
                ['Accueil', 'home'],
                ['Actualités', 'posts.public.index'],
                ['Calendrier', 'competitions.index'],
                ['Groupes et horaires', 'schedule'],
                ["S'inscrire (tarifs)", 'pricing'],
                ["L'équipe du club", 'team.index'],
                ['Installations', 'public.installations.index'],
                ['Partenaires', 'partners.index'],
                ['Devenir mécène', 'support.mecenat'],
                ['Faire un don', 'support.donation'],
                ['Boutique', 'shop.index'],
                ['Liens utiles', 'links.index'],
                ['Galeries photos', 'galleries.index'],
                ['Vidéos', 'public.videos.index'],
                ['Fiches', 'public.fiches.index'],
                ['Pages', 'public.pages.index'],
                ['Revue de presse', 'press'],
                ['Contact', 'contact'],
                ['Recherche', 'search'],
                ['Mentions légales', 'legal'],
            ],
        ],
        'visitor' => [
            'title' => 'Espace visiteur',
            'icon' => 'fa-user',
            'class' => 'member',
            'text' => 'Compte créé sur le site, sans adhésion au club.',
            'links' => [
                ['Tableau de bord', 'visitor.dashboard'],
                ['Mon profil', 'profile.edit'],
            ],
        ],
        'member' => [
            'title' => 'Espace adhérent',
            'icon' => 'fa-swimmer',
            'class' => 'member',
            'text' => 'Familles et nageurs du club.',
            'links' => [
                ['Tableau de bord', 'user.dashboard'],
                ['Ma fiche', 'user.user-profile.show'],
                ['Mes compétitions', 'user.competitions.index'],
                ['Modifier mon profil', 'user.profile.edit'],
            ],
        ],
        'editor' => [
            'title' => 'Espace éditeur',
            'icon' => 'fa-pen-nib',
            'class' => 'editor',
            'text' => 'Rédacteurs et bénévoles chargés du contenu.',
            'links' => [
                ['Tableau de bord', 'editor.dashboard'],
                ['Articles', 'editor.posts.index'],
                ['Nouvel article', 'editor.posts.create'],
                ['Pages', 'editor.pages.index'],
                ['Fiches', 'editor.fiches.index'],
                ['Vidéos', 'editor.videos.index'],
                ['Galeries photos', 'editor.photo-galleries.index'],
                ['Médiathèque', 'editor.media.index'],
                ['Compétitions', 'editor.competitions.index'],
                ['Partenaires', 'editor.partners.index'],
                ['Planning : créneaux', 'editor.planning.slots.index'],
                ['Planning : groupes', 'editor.planning.groups.index'],
                ['Planning : nageurs', 'editor.planning.swimmers.index'],
                ['Statistiques', 'editor.stats'],
            ],
        ],
    ];

    public function __invoke(): View
    {
        $areas = [];

        foreach (self::AREAS as $key => $area) {
            $links = [];
            foreach ($area['links'] as [$label, $route]) {
                if (Route::has($route)) {
                    $links[] = ['label' => $label, 'url' => route($route), 'path' => '/' . ltrim(parse_url(route($route), PHP_URL_PATH) ?? '', '/')];
                }
            }
            if ($links) {
                $areas[$key] = ['links' => $links] + $area;
            }
        }

        return view('admin.area-preview', compact('areas'));
    }
}
