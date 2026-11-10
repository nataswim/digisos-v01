<?php

namespace App\Support;

use App\Models\Conversation;
use Illuminate\Support\Facades\Route;

/**
 * Menus de gauche des espaces connectés (administration, éditeur, coachs, adhérent, visiteur).
 *
 * Structure : groupes (titre facultatif, ex. « CONTENUS ») → entrées.
 *   - lien direct     : [libellé, icône, route, motifs de routes actives, paramètres facultatifs]
 *   - entrée dépliante: ['label', 'icon', 'links' => [liens…], 'external' => true pour un nouvel onglet]
 * Les liens dont la route n'existe pas sont ignorés ; une entrée ou un groupe vide disparaît.
 * Le compteur rouge de « Messagerie » est ajouté automatiquement (messages non lus).
 * Mise en page : layouts/espace.blade.php ; styles : public/css/espace-shell.css.
 */
class EspaceMenu
{
    public const AREAS = [
        'admin' => ['label' => 'Administration', 'icon' => 'fa-cogs', 'home' => 'admin.dashboard'],
        'editor' => ['label' => 'Espace éditeur', 'icon' => 'fa-pen-nib', 'home' => 'editor.dashboard'],
        'coach' => ['label' => 'Espace coachs', 'icon' => 'fa-chalkboard-teacher', 'home' => 'coach.dashboard'],
        'member' => ['label' => 'Espace adhérent', 'icon' => 'fa-swimmer', 'home' => 'user.dashboard'],
        'visitor' => ['label' => 'Espace visiteur', 'icon' => 'fa-user', 'home' => 'visitor.dashboard'],
    ];

    /** Routes de la messagerie (compteur de non-lus) */
    private const MESSAGE_ROUTES = ['admin.messages.index', 'editor.messages.index', 'coach.messages.index', 'user.messages.index'];

    public static function definition(string $area): array
    {
        return match ($area) {
            'admin' => self::admin(),
            'editor' => self::editor(),
            'coach' => self::coach(),
            'visitor' => self::visitor(),
            default => self::member(),
        };
    }

    /** Menu prêt à afficher : URL, états actifs, compteurs, groupes vides retirés. */
    public static function build(string $area): array
    {
        $unread = null;
        $groups = [];
        $n = 0;

        foreach (self::definition($area) as $group) {
            $items = [];
            foreach ($group['items'] as $item) {
                if (isset($item['links'])) {
                    $links = array_values(array_filter(array_map(fn ($l) => self::link($l, $item['external'] ?? false), $item['links'])));
                    if (! $links) {
                        continue;
                    }
                    $items[] = [
                        'id' => 'esp-menu-' . (++$n),
                        'label' => $item['label'],
                        'icon' => $item['icon'],
                        'links' => $links,
                        'active' => collect($links)->contains('active', true),
                    ];
                    continue;
                }
                if ($link = self::link($item)) {
                    if (in_array($item[2], self::MESSAGE_ROUTES, true)) {
                        $unread ??= rescue(fn () => Conversation::unreadCountFor(auth()->user()), 0, false);
                        $link['badge'] = $unread ?: null;
                    }
                    $items[] = $link;
                }
            }
            if ($items) {
                $groups[] = ['heading' => $group['heading'] ?? null, 'items' => $items];
            }
        }

        return $groups;
    }

    private static function link(array $l, bool $external = false): ?array
    {
        [$label, $icon, $route, $active] = $l + [3 => []];
        if (! Route::has($route)) {
            return null;
        }

        return [
            'label' => $label,
            'icon' => $icon,
            'url' => route($route, $l[4] ?? []),
            'active' => ! $external && $active && request()->routeIs(...(array) $active)
                && (empty($l[4]) || collect($l[4])->every(fn ($v, $k) => request()->query($k) == $v)),
            'external' => $external,
        ];
    }

    private static function admin(): array
    {
        return [
            ['items' => [
                ['Tableau de bord', 'fa-house', 'admin.dashboard', ['admin.dashboard']],
                ['Messagerie', 'fa-envelope', 'admin.messages.index', ['admin.messages.*']],
            ]],
            ['heading' => 'Contenus', 'items' => [
                ['label' => 'Articles', 'icon' => 'fa-newspaper', 'links' => [
                    ['Liste des articles', 'fa-list', 'admin.posts.index', ['admin.posts.*']],
                    ['Catégories', 'fa-folder-open', 'admin.categories.index', ['admin.categories.*']],
                    ['Tags', 'fa-tags', 'admin.tags.index', ['admin.tags.*']],
                ]],
                ['label' => 'Fiches pratiques', 'icon' => 'fa-file-alt', 'links' => [
                    ['Liste des fiches', 'fa-list', 'admin.fiches.index', ['admin.fiches.*']],
                    ['Catégories', 'fa-folder-open', 'admin.fiches-categories.index', ['admin.fiches-categories.*']],
                    ['Sous-catégories', 'fa-folder-tree', 'admin.fiches-sous-categories.index', ['admin.fiches-sous-categories.*']],
                ]],
                ['label' => 'Pages', 'icon' => 'fa-file', 'links' => [
                    ['Liste des pages', 'fa-list', 'admin.pages.index', ['admin.pages.*']],
                    ['Catégories', 'fa-folder-open', 'admin.pages-categories.index', ['admin.pages-categories.*']],
                ]],
                ['label' => 'Partenaires', 'icon' => 'fa-handshake', 'links' => [
                    ['Liste des partenaires', 'fa-list', 'admin.partners.index', ['admin.partners.index', 'admin.partners.edit']],
                    ['Nouveau partenaire', 'fa-plus', 'admin.partners.create', ['admin.partners.create']],
                ]],
                ['label' => 'Boutique', 'icon' => 'fa-shopping-bag', 'links' => [
                    ['Produits', 'fa-list', 'admin.shop.products.index', ['admin.shop.products.index', 'admin.shop.products.edit']],
                    ['Catégories', 'fa-folder-open', 'admin.shop.categories.index', ['admin.shop.categories.*']],
                    ['Nouveau produit', 'fa-plus', 'admin.shop.products.create', ['admin.shop.products.create']],
                ]],
                ['label' => "L'équipe du club", 'icon' => 'fa-users', 'links' => [
                    ['Membres', 'fa-id-badge', 'admin.team.members.index', ['admin.team.members.index', 'admin.team.members.edit']],
                    ['Sections', 'fa-layer-group', 'admin.team.sections.index', ['admin.team.sections.*']],
                    ['Nouveau membre', 'fa-plus', 'admin.team.members.create', ['admin.team.members.create']],
                ]],
                ['label' => 'Liens utiles', 'icon' => 'fa-link', 'links' => [
                    ['Liens', 'fa-list', 'admin.links.items.index', ['admin.links.items.index', 'admin.links.items.edit']],
                    ['Catégories', 'fa-folder-open', 'admin.links.categories.index', ['admin.links.categories.*']],
                    ['Nouveau lien', 'fa-plus', 'admin.links.items.create', ['admin.links.items.create']],
                ]],
            ]],
            ['heading' => 'Multimédia', 'items' => [
                ['label' => 'Vidéos', 'icon' => 'fa-video', 'links' => [
                    ['Liste des vidéos', 'fa-list', 'admin.videos.index', ['admin.videos.*']],
                    ['Catégories', 'fa-folder-open', 'admin.video-categories.index', ['admin.video-categories.*']],
                ]],
                ['label' => 'Images', 'icon' => 'fa-image', 'links' => [
                    ['Médiathèque', 'fa-list', 'admin.media.index', ['admin.media.index', 'admin.media.show']],
                    ['Catégories', 'fa-folder-open', 'admin.media.categories', ['admin.media.categories']],
                ]],
                ['label' => 'Galeries et bannières', 'icon' => 'fa-images', 'links' => [
                    ['Galeries photo', 'fa-layer-group', 'admin.photo-galleries.index', ['admin.photo-galleries.*']],
                    ['Bannières', 'fa-panorama', 'admin.banners.index', ['admin.banners.*']],
                ]],
                ['label' => 'Documents', 'icon' => 'fa-file-pdf', 'links' => [
                    ['Liste des documents', 'fa-list', 'admin.downloadables.index', ['admin.downloadables.*']],
                    ['Catégories', 'fa-folder', 'admin.download-categories.index', ['admin.download-categories.*']],
                    ['Fichiers (PDF, ePub…)', 'fa-file-upload', 'admin.ebook-files.index', ['admin.ebook-files.*']],
                ]],
            ]],
            ['heading' => 'Sportif', 'items' => [
                ['label' => 'Compétitions', 'icon' => 'fa-trophy', 'links' => [
                    ['Calendrier', 'fa-list', 'admin.competitions.index', ['admin.competitions.index', 'admin.competitions.edit', 'admin.competitions.entries.*', 'admin.competitions.program.*']],
                    ['Nouvelle compétition', 'fa-plus', 'admin.competitions.create', ['admin.competitions.create']],
                    ['Épreuves', 'fa-swimmer', 'admin.competitions.events.index', ['admin.competitions.events.*']],
                    ['Records', 'fa-medal', 'coach.records.index', []],
                    ['Performances des nageurs', 'fa-stopwatch', 'coach.performances.index', []],
                ]],
                ['label' => 'Planning', 'icon' => 'fa-calendar-week', 'links' => [
                    ['Créneaux', 'fa-calendar-alt', 'admin.planning.slots.index', ['admin.planning.slots.*']],
                    ['Groupes', 'fa-layer-group', 'admin.planning.groups.index', ['admin.planning.groups.*']],
                    ['Nageurs', 'fa-person-swimming', 'admin.planning.swimmers.index', ['admin.planning.swimmers.*']],
                ]],
                ['label' => 'Adhérents', 'icon' => 'fa-address-book', 'links' => [
                    ['Liste des adhérents', 'fa-address-book', 'admin.members.index', ['admin.members.index', 'admin.members.show', 'admin.members.edit', 'admin.members.create']],
                    ['Tableau de bord', 'fa-chart-pie', 'admin.members.stats', ['admin.members.stats']],
                    ['Par année de naissance', 'fa-cake-candles', 'admin.members.lists.year', ['admin.members.lists.year']],
                    ['Par commune', 'fa-map-marker-alt', 'admin.members.lists.city', ['admin.members.lists.city']],
                    ['Importer le tableau', 'fa-file-import', 'admin.members.import', ['admin.members.import*']],
                    ['Catégories et tarifs', 'fa-tags', 'admin.members.categories.index', ['admin.members.categories.*']],
                ]],
                ['label' => 'Licences FFN', 'icon' => 'fa-id-card', 'links' => [
                    ['Licenciés', 'fa-id-card', 'admin.ffn-licenses.index', ['admin.ffn-licenses.index', 'admin.ffn-licenses.show']],
                    ['Importer depuis extraNat', 'fa-file-import', 'admin.ffn-licenses.import.create', ['admin.ffn-licenses.import.*']],
                ]],
                ['label' => 'Espace coachs', 'icon' => 'fa-chalkboard-teacher', 'links' => [
                    ['Tableau de bord coachs', 'fa-chalkboard-teacher', 'coach.dashboard', []],
                    ['Séances', 'fa-dumbbell', 'coach.training.index', []],
                    ["Bibliothèque d'exercices", 'fa-book-medical', 'coach.exercises.index', []],
                    ['Émargement', 'fa-clipboard-check', 'coach.presence.index', []],
                    ['Assiduité', 'fa-chart-column', 'coach.assiduity.index', []],
                    ['Suivi des nageurs', 'fa-clipboard-list', 'coach.notes.index', []],
                    ['Documents des coachs', 'fa-folder-open', 'coach.documents.index', []],
                ]],
            ]],
            ['heading' => 'Gestion', 'items' => [
                ['label' => 'Installations', 'icon' => 'fa-swimming-pool', 'links' => [
                    ['Services', 'fa-building', 'admin.services.index', ['admin.services.*']],
                    ['Structures', 'fa-layer-group', 'admin.structures.index', ['admin.structures.*']],
                    ['Espaces', 'fa-th-large', 'admin.espaces.index', ['admin.espaces.*']],
                    ['Zones', 'fa-map-marker-alt', 'admin.zones.index', ['admin.zones.*']],
                ]],
                ['label' => 'Comptes et accès', 'icon' => 'fa-users-cog', 'links' => [
                    ['Utilisateurs', 'fa-users', 'admin.users.index', ['admin.users.*']],
                    ['Fiches utilisateurs', 'fa-id-card', 'admin.user-profiles.index', ['admin.user-profiles.*']],
                    ['Rôles', 'fa-user-tag', 'admin.roles.index', ['admin.roles.*']],
                    ['Permissions', 'fa-key', 'admin.permissions.index', ['admin.permissions.*']],
                ]],
                ['Plan du site', 'fa-sitemap', 'admin.sitemap.index', ['admin.sitemap.*']],
                ['Aperçu des espaces', 'fa-eye', 'admin.area-preview', ['admin.area-preview']],
            ]],
            ['heading' => 'Site public', 'items' => [
                ['label' => 'Pages publiques', 'icon' => 'fa-globe', 'external' => true, 'links' => [
                    ['Accueil du site', 'fa-house', 'home'],
                    ['Calendrier public', 'fa-trophy', 'competitions.index'],
                    ['Planning public', 'fa-calendar-week', 'schedule'],
                    ['Boutique', 'fa-shopping-bag', 'shop.index'],
                ]],
            ]],
        ];
    }

    private static function editor(): array
    {
        return [
            ['items' => [
                ['Tableau de bord', 'fa-house', 'editor.dashboard', ['editor.dashboard']],
                ['Messages', 'fa-envelope', 'editor.messages.index', ['editor.messages.*']],
                ['Statistiques', 'fa-chart-bar', 'editor.stats', ['editor.stats']],
            ]],
            ['heading' => 'Contenus', 'items' => [
                ['label' => 'Articles', 'icon' => 'fa-newspaper', 'links' => [
                    ['Liste des articles', 'fa-list', 'editor.posts.index', ['editor.posts.index', 'editor.posts.edit', 'editor.posts.show']],
                    ['Nouvel article', 'fa-plus', 'editor.posts.create', ['editor.posts.create']],
                ]],
                ['label' => 'Fiches', 'icon' => 'fa-file-invoice', 'links' => [
                    ['Liste des fiches', 'fa-list', 'editor.fiches.index', ['editor.fiches.index', 'editor.fiches.edit', 'editor.fiches.show']],
                    ['Nouvelle fiche', 'fa-plus', 'editor.fiches.create', ['editor.fiches.create']],
                ]],
                ['label' => 'Pages', 'icon' => 'fa-copy', 'links' => [
                    ['Liste des pages', 'fa-list', 'editor.pages.index', ['editor.pages.index', 'editor.pages.edit', 'editor.pages.show']],
                    ['Nouvelle page', 'fa-plus', 'editor.pages.create', ['editor.pages.create']],
                ]],
                ['label' => 'Partenaires', 'icon' => 'fa-handshake', 'links' => [
                    ['Liste des partenaires', 'fa-list', 'editor.partners.index', ['editor.partners.index', 'editor.partners.edit']],
                    ['Nouveau partenaire', 'fa-plus', 'editor.partners.create', ['editor.partners.create']],
                ]],
            ]],
            ['heading' => 'Multimédia', 'items' => [
                ['label' => 'Vidéos', 'icon' => 'fa-video', 'links' => [
                    ['Liste des vidéos', 'fa-list', 'editor.videos.index', ['editor.videos.index', 'editor.videos.edit', 'editor.videos.show']],
                    ['Nouvelle vidéo', 'fa-plus', 'editor.videos.create', ['editor.videos.create']],
                ]],
                ['label' => 'Galeries photos', 'icon' => 'fa-images', 'links' => [
                    ['Liste des galeries', 'fa-list', 'editor.photo-galleries.index', ['editor.photo-galleries.index', 'editor.photo-galleries.edit', 'editor.photo-galleries.show']],
                    ['Nouvelle galerie', 'fa-plus', 'editor.photo-galleries.create', ['editor.photo-galleries.create']],
                ]],
                ['Médiathèque', 'fa-folder-open', 'editor.media.index', ['editor.media.*']],
            ]],
            ['heading' => 'Sportif', 'items' => [
                ['label' => 'Compétitions', 'icon' => 'fa-trophy', 'links' => [
                    ['Calendrier', 'fa-list', 'editor.competitions.index', ['editor.competitions.index', 'editor.competitions.edit', 'editor.competitions.entries.*', 'editor.competitions.program.*']],
                    ['Nouvelle compétition', 'fa-plus', 'editor.competitions.create', ['editor.competitions.create']],
                    ['Épreuves', 'fa-swimmer', 'editor.competitions.events.index', ['editor.competitions.events.*']],
                ]],
                ['label' => 'Planning', 'icon' => 'fa-calendar-week', 'links' => [
                    ['Créneaux', 'fa-calendar-alt', 'editor.planning.slots.index', ['editor.planning.slots.*']],
                    ['Groupes', 'fa-layer-group', 'editor.planning.groups.index', ['editor.planning.groups.*']],
                    ['Nageurs', 'fa-person-swimming', 'editor.planning.swimmers.index', ['editor.planning.swimmers.*']],
                ]],
                ['label' => 'Adhérents', 'icon' => 'fa-address-book', 'links' => [
                    ['Liste des adhérents', 'fa-address-book', 'editor.members.index', ['editor.members.index', 'editor.members.show', 'editor.members.edit', 'editor.members.create']],
                    ['Tableau de bord', 'fa-chart-pie', 'editor.members.stats', ['editor.members.stats']],
                    ['Par année de naissance', 'fa-cake-candles', 'editor.members.lists.year', ['editor.members.lists.year']],
                    ['Par commune', 'fa-map-marker-alt', 'editor.members.lists.city', ['editor.members.lists.city']],
                    ['Importer le tableau', 'fa-file-import', 'editor.members.import', ['editor.members.import*']],
                    ['Catégories et tarifs', 'fa-tags', 'editor.members.categories.index', ['editor.members.categories.*']],
                ]],
            ]],
            ['heading' => 'Site public', 'items' => [
                ['label' => 'Pages publiques', 'icon' => 'fa-globe', 'external' => true, 'links' => [
                    ['Accueil du site', 'fa-house', 'home'],
                    ['Calendrier public', 'fa-trophy', 'competitions.index'],
                    ['Planning public', 'fa-calendar-week', 'schedule'],
                    ['Boutique', 'fa-shopping-bag', 'shop.index'],
                ]],
            ]],
        ];
    }

    private static function coach(): array
    {
        return [
            ['items' => [
                ['Tableau de bord', 'fa-house', 'coach.dashboard', ['coach.dashboard']],
                ['Messages', 'fa-envelope', 'coach.messages.index', ['coach.messages.*']],
            ]],
            ['heading' => 'Entraînement', 'items' => [
                ['label' => 'Séances', 'icon' => 'fa-dumbbell', 'links' => [
                    ['Séances de la semaine', 'fa-calendar-week', 'coach.training.index', ['coach.training.index', 'coach.training.show', 'coach.training.edit']],
                    ['Nouvelle séance natation', 'fa-person-swimming', 'coach.training.create', ['coach.training.create'], ['type' => 'natation']],
                    ['Nouvelle séance PPG', 'fa-dumbbell', 'coach.training.create', ['coach.training.create'], ['type' => 'ppg']],
                    ['Nouvelle séance maison', 'fa-house', 'coach.training.create', ['coach.training.create'], ['type' => 'maison']],
                    ['Nouvelle fiche école de natation', 'fa-children', 'coach.training.create', ['coach.training.create'], ['type' => 'ecole']],
                    ['Nouvelle fiche divers', 'fa-shapes', 'coach.training.create', ['coach.training.create'], ['type' => 'divers']],
                    ["Bibliothèque d'exercices", 'fa-book-medical', 'coach.exercises.index', ['coach.exercises.*']],
                    ['Listes (nages, matériel…)', 'fa-list', 'coach.training-lists.edit', ['coach.training-lists.*']],
                ]],
                ['label' => 'Présences', 'icon' => 'fa-clipboard-check', 'links' => [
                    ['Émargement', 'fa-clipboard-check', 'coach.presence.index', ['coach.presence.*']],
                    ['Assiduité', 'fa-chart-column', 'coach.assiduity.index', ['coach.assiduity.*']],
                ]],
                ['label' => 'Planning', 'icon' => 'fa-calendar-week', 'links' => [
                    ['Créneaux', 'fa-clock', 'coach.planning.slots.index', ['coach.planning.slots.*']],
                    ['Groupes', 'fa-layer-group', 'coach.planning.groups.index', ['coach.planning.groups.*']],
                    ['Nageurs', 'fa-person-swimming', 'coach.planning.swimmers.index', ['coach.planning.swimmers.*']],
                ]],
            ]],
            ['heading' => 'Compétition', 'items' => [
                ['label' => 'Compétitions', 'icon' => 'fa-trophy', 'links' => [
                    ['Toutes les compétitions', 'fa-list', 'coach.competitions.index', ['coach.competitions.index', 'coach.competitions.edit', 'coach.competitions.entries.*', 'coach.competitions.program.*']],
                    ['Nouvelle compétition', 'fa-plus', 'coach.competitions.create', ['coach.competitions.create']],
                    ['Épreuves', 'fa-swimmer', 'coach.competitions.events.index', ['coach.competitions.events.*']],
                    ['Records', 'fa-medal', 'coach.records.index', ['coach.records.index', 'coach.records.edit', 'coach.records.create']],
                    ['Importer des records FFN', 'fa-file-import', 'coach.records.import-form', ['coach.records.import*']],
                ]],
                ['label' => 'Suivi des nageurs', 'icon' => 'fa-clipboard-list', 'links' => [
                    ['Performances', 'fa-stopwatch', 'coach.performances.index', ['coach.performances.index', 'coach.performances.show', 'coach.performances.edit']],
                    ['Saisir des temps', 'fa-plus', 'coach.performances.create', ['coach.performances.create']],
                    ['Classement du club', 'fa-ranking-star', 'coach.performances.ranking', ['coach.performances.ranking']],
                    ['Notes de suivi', 'fa-clipboard-list', 'coach.notes.index', ['coach.notes.*']],
                ]],
            ]],
            ['heading' => 'Ressources', 'items' => [
                ['Documents', 'fa-folder-open', 'coach.documents.index', ['coach.documents.*']],
            ]],
        ];
    }

    private static function member(): array
    {
        return [
            ['items' => [
                ['Tableau de bord', 'fa-house', 'user.dashboard', ['user.dashboard']],
                ['Mes messages', 'fa-envelope', 'user.messages.index', ['user.messages.*']],
            ]],
            ['heading' => 'Mon nageur', 'items' => [
                ['Mes compétitions', 'fa-trophy', 'user.competitions.index', ['user.competitions.*']],
                ['Mes entraînements', 'fa-dumbbell', 'user.training.index', ['user.training.*']],
                ['Mes performances', 'fa-stopwatch', 'user.performances.index', ['user.performances.*']],
                ['Assiduité', 'fa-clipboard-check', 'user.presence.index', ['user.presence.*']],
            ]],
            ['heading' => 'Mon compte', 'items' => [
                ['Ma fiche', 'fa-id-card', 'user.user-profile.show', ['user.user-profile.*']],
                ['Modifier mon profil', 'fa-user-pen', 'user.profile.edit', ['user.profile.*']],
            ]],
            ['heading' => 'Le club', 'items' => [
                ['label' => 'Sur le site', 'icon' => 'fa-globe', 'external' => true, 'links' => [
                    ['Actualités', 'fa-newspaper', 'posts.public.index'],
                    ['Calendrier des compétitions', 'fa-trophy', 'competitions.index'],
                    ['Groupes et horaires', 'fa-calendar-week', 'schedule'],
                    ['Records du club', 'fa-medal', 'records.index'],
                    ['Boutique', 'fa-shopping-bag', 'shop.index'],
                ]],
            ]],
        ];
    }

    private static function visitor(): array
    {
        return [
            ['items' => [
                ['Tableau de bord', 'fa-house', 'visitor.dashboard', ['visitor.dashboard']],
                ['Mes messages', 'fa-envelope', 'user.messages.index', ['user.messages.*']],
                ['Mon profil', 'fa-user-pen', 'profile.edit', ['profile.*']],
            ]],
            ['heading' => 'Le club', 'items' => [
                ["S'inscrire au club", 'fa-person-swimming', 'pricing', []],
                ['Groupes et horaires', 'fa-calendar-week', 'schedule', []],
                ['Calendrier des compétitions', 'fa-trophy', 'competitions.index', []],
                ['Actualités', 'fa-newspaper', 'posts.public.index', []],
                ['Boutique du club', 'fa-shopping-bag', 'shop.index', []],
            ]],
        ];
    }
}
