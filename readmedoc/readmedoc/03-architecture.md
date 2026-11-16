# 03 · Architecture

Application **Laravel 12** classique (MVC). Ce fichier dit où trouver chaque chose.

## Les grandes zones du site

| Zone | Adresse | Layout | Qui |
|------|---------|--------|-----|
| Site public | `/`, `/posts`, `/competitions`, `/groupes-et-horaires`, `/records`… | `layouts.public` | tout le monde |
| Administration | `/admin/…` | `layouts.admin` | administrateurs |
| Espace éditeur | `/editor/…` | `layouts.editor` | éditeurs (et admins) |
| Espace coachs | `/coach/…` | `layouts.coach` | coachs (et admins) |
| Espace adhérent | `/user/…` | `layouts.member` | adhérents (familles, nageurs) |
| Espace visiteur | `/visitor/…` | `layouts.member` | comptes non encore validés |

Les layouts `admin`, `editor`, `coach` et `member` héritent tous de **`layouts/espace.blade.php`** (menu de gauche, barre du haut). Voir [05-espaces-connectes.md](05-espaces-connectes.md).

## Routes (`routes/`)

| Fichier | Contenu |
|---------|---------|
| `web.php` | site public, groupes `admin.*`, `editor.*`, `user.*`, `visitor.*`, liens communs de la messagerie |
| `coach.php` | groupe `coach.*` (séances, émargement, performances, records, documents…) |
| `auth.php` | connexion, inscription, mot de passe oublié (Breeze) |
| `planning.php`, `competitions.php`, `members.php`, `messages.php`, `partners.php`, `shop.php`, `team.php`, `links.php`, `ffn-licenses.php` | modules **partagés** : le même fichier est inclus dans plusieurs groupes |

**Principe des modules partagés** : `routes/planning.php` est inclus dans les groupes admin, editor et coach. On obtient donc `admin.planning.slots.index`, `editor.planning.slots.index` et `coach.planning.slots.index`, servis par **le même contrôleur**. Le contrôleur sait dans quel espace il est grâce à une méthode `area()` :

```php
private function area(): string
{
    return match (true) {
        request()->routeIs('editor.*') => 'editor',
        request()->routeIs('coach.*') => 'coach',
        default => 'admin',
    };
}
```

et les vues utilisent `@extends($layout)` et `route($area . '.module.page')`.

Lister les routes d'un module : `php artisan route:list --name=members`

## Code PHP (`app/`)

| Dossier | Rôle |
|---------|------|
| `Http/Controllers/` | contrôleurs publics et historiques (articles, pages, fiches…) |
| `Http/Controllers/Admin/` | administration ; sous-dossiers `Competitions/`, `Members/`, `Planning/` |
| `Http/Controllers/Coach/` | espace coachs |
| `Http/Controllers/User/` | espace adhérent |
| `Http/Controllers/Messages/` | messagerie (équipe, adhérent, liens communs) |
| `Http/Requests/` | validations de formulaires (ex. `Auth/RegisterRequest` : profils à l'inscription) |
| `Models/` | modèles Eloquent (≈ 70) |
| `Services/` | logique métier réutilisable : lecteurs d'imports (`FfnLicenseCsvParser`, `FfnRecordParser`, `ExtranatPerformanceParser`, `MemberSheetParser`), `PerformanceTracker`, `PresenceStats`, `MessageService`, `CommuneLocator`, `SitemapService` |
| `Support/` | outils : `EspaceMenu` (menus de gauche), `SwimTime`, `RichText`, `HtmlSanitizer`, `ClubGroupes` |
| `Notifications/` | e-mails : convocation à une compétition, nouveau message |
| `Listeners/` | `RecordUserLogin` (date de dernière connexion) — découvert automatiquement |
| `Console/Commands/` | `GenerateSitemap`, `GenerateCategorySlugs` |
| `Providers/AppServiceProvider.php` | autorisations (`Gate`) : `access-admin`, `access-editor`, `access-coach` |

## Vues (`resources/views/`)

| Dossier | Contenu |
|---------|---------|
| `layouts/` | `espace.blade.php` (cadre des espaces), `public.blade.php`, et les enveloppes `admin`, `editor`, `coach`, `member` |
| `layouts/partials/` | en-tête et pied du site public, `espace-head` (CSS + police), tuiles et blocs des tableaux de bord |
| `admin/` | pages d'administration, dont `members/`, `competitions/`, `planning/` (partagées avec editor/coach) |
| `coach/`, `user/`, `visitor/`, `editor/` | pages propres à chaque espace |
| `messages/` | messagerie (`staff/`, `member/`, morceaux communs) |
| `components/` | composants Blade : `banner`, `member/header`, `member/avatar`, `public/hero`… |
| `public/` | pages du site public |
| `emails/` | gabarits d'e-mails |

## Styles et scripts (`public/`)

| Fichier | Rôle |
|---------|------|
| `css/app.css`, `js/app.js` | thème public compilé (Laravel Mix, Bootstrap 5.3) |
| `css/espaces.css` | composants des espaces connectés (boutons, tableaux, formulaires…) |
| `css/espace-shell.css` | cadre « extraNat » : menu de gauche, barre du haut, cartes, tuiles |
| `js/quill-advanced.js` | éditeur de texte riche (articles, pages, fiches) |
| `js/media-selector.js` | choix d'une image dans la médiathèque |

Les CSS des espaces sont chargés avec `?v=filemtime` : pas besoin de vider le cache navigateur après une modification.

## Configuration (`config/`)

- `config/club.php` : informations du club (coordonnées, dons, etc.).
- `config/mail.php` : `mail.contact_to` = destinataire du formulaire de contact.
- `.env` : secrets et réglages propres à chaque machine (jamais versionné).

## Base de données

- Migrations : `database/migrations/` (une par module, datées).
- Seeders : `database/seeders/` — voir [README-SEEDERS.md](README-SEEDERS.md).
- Montants en **centimes** (`*_cents`), temps de natation en **centièmes** (`time_cs`).
- Saison au format `2026-2027`, débutant le 1er septembre (`Membership::currentSeason()`).
