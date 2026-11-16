# 05 · Espaces connectés (cadre « extraNat »)

Depuis novembre 2026, les 5 espaces (admin, éditeur, coachs, adhérent, visiteur) partagent le même cadre, inspiré de la page d'accueil extraNat :

- **menu vertical à gauche** (blanc) : logo, rubriques en petites capitales, entrées dépliantes, page en cours surlignée, compteur rouge des messages non lus ;
- **barre du haut** : nom de l'espace, enveloppe des messages, compte (initiales, e-mail, rôle) avec « Changer d'espace », « Voir le site », « Se déconnecter » ;
- **contenu** sur fond gris clair, cartes blanches arrondies, police **Manrope** ;
- sur mobile (< 992 px) : le menu devient un volet ouvert par le bouton ☰.

## Fichiers

| Fichier | Rôle |
|---------|------|
| `resources/views/layouts/espace.blade.php` | le cadre (HTML du menu, de la barre, des messages flash) |
| `resources/views/layouts/admin.blade.php`, `editor`, `coach` | une ligne : `@extends('layouts.espace', ['espaceArea' => 'admin'])` |
| `resources/views/layouts/member.blade.php` (et `user.blade.php`) | adhérent ou visiteur selon le rôle |
| `app/Support/EspaceMenu.php` | **contenu des menus** des 5 espaces |
| `resources/views/layouts/partials/espace-head.blade.php` | police + `espaces.css` + `espace-shell.css` |
| `public/css/espaces.css` | composants (boutons, tableaux, formulaires, badges) |
| `public/css/espace-shell.css` | cadre : menu, barre du haut, cartes, tuiles bleues `.esp-tile` |

Les anciens menus horizontaux (`admin-nav-horizontal`, `editor-nav-horizontal`, `coach-nav`, `user-header`…) ne sont plus utilisés ; ils sont restés dans le dossier.

## Ajouter un lien dans un menu

Tout se passe dans `app/Support/EspaceMenu.php`, méthode de l'espace (`admin()`, `editor()`, `coach()`, `member()`, `visitor()`) :

```php
// Lien direct : [libellé, icône Font Awesome, nom de route, routes « actives »]
['Messagerie', 'fa-envelope', 'admin.messages.index', ['admin.messages.*']],

// Entrée dépliante
['label' => 'Adhérents', 'icon' => 'fa-address-book', 'links' => [
    ['Liste des adhérents', 'fa-address-book', 'admin.members.index', ['admin.members.index', 'admin.members.show']],
    ['Par commune', 'fa-map-marker-alt', 'admin.members.lists.city', ['admin.members.lists.city']],
]],

// Liens externes (nouvel onglet) : 'external' => true sur l'entrée dépliante
```

- Une route qui n'existe pas est **ignorée** (pas d'erreur).
- Le compteur de messages non lus s'ajoute tout seul sur les liens de messagerie.

## Pièges de style connus

- Une règle du cadre (`.espace--shell .card-header`, avec `!important`) l'emporte souvent sur une règle de page. Pour colorer un en-tête de carte : sélecteur plus précis, par ex. `.espace--shell .card.convoc-card > .convoc-head`.
- Le thème public recolore `.btn-check:checked + .btn-outline-*` : utiliser une classe propre avec `!important`.
- Dans les pages adhérent, `.d-flex.align-items-center.gap-2` s'empile sous 768 px (thème public) : utiliser une classe propre.
- Un élément `visually-hidden` dans un `.table-responsive` peut créer un défilement horizontal de toute la page : ajouter `position-relative` au `.table-responsive`.

## Tableaux de bord

| Espace | Contrôleur | Vue |
|--------|-----------|-----|
| Admin | `Admin/DashboardController` | `admin/dashboard.blade.php` |
| Éditeur | — | `editor/dashboard.blade.php` |
| Coachs | `Coach/CoachDashboardController` | `coach/dashboard.blade.php` |
| Adhérent | `User/UserDashboardController` | `user/dashboard.blade.php` |
| Visiteur | `Visitor/VisitorDashboardController` | `visitor/dashboard.blade.php` |

**Tableau de bord adhérent**, de haut en bas :

1. **Mes convocations** (carte rouge, trophée qui tourne, pastille « N à répondre ») ;
2. messages non lus ;
3. une **carte par nageur** rattaché au compte : photo, groupe (lien vers ses horaires sur `/groupes-et-horaires#groupe-…`), créneaux, licence FFN, **fiche adhérent** de la saison (catégorie, statut, pièces manquantes, certificat, droit à l'image, cotisation et reste à payer, coordonnées) ;
4. statut du compte, tuiles « Mon espace » ;
5. **Pages de votre entraîneur** : tuiles turquoise `#00ACC0` vers nataswim.fr (articles, fiches, vidéos, séances, musculation, outils) ;
6. le club, derniers contenus.

Les tuiles viennent de `layouts/partials/espace-accueil-tuiles.blade.php` (paramètres : `titre`, `intro`, `variante`, `tuiles` avec `route` ou `url` externe).

La fiche adhérent affichée est celle **liée au nageur** (`members.planning_swimmer_id`) ou celle **rattachée au compte** (`members.user_id`) avec le même prénom. Jamais celle d'une autre famille.

## Aperçu des espaces

Administration → Système → **Aperçu des espaces** (`/admin/apercu-des-espaces`) : liens vers les pages de chaque espace pour vérifier leur apparence.
