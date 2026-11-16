# 06 · Contenus et site public

Contenus gérés par les **administrateurs** (`/admin/…`) et, pour la plupart, par les **éditeurs** (`/editor/…`).

## Modules de contenu

| Module | Admin | Public | Remarques |
|--------|-------|--------|-----------|
| Articles | `/admin/posts` (+ catégories, tags) | `/posts` | éditeur riche Quill ; contenus réservés aux adhérents possibles |
| Fiches pratiques | `/admin/fiches` (+ catégories, sous-catégories) | `/fiches` | |
| Pages | `/admin/pages` (+ catégories) | `/pages/…` | règles du club, organisation, groupes… |
| Vidéos | `/admin/videos` (+ catégories) | `/videos` | YouTube / Vimeo |
| Médiathèque | `/admin/media` | — | images choisies dans les formulaires (`media-selector.js`) |
| Galeries photos | `/admin/photo-galleries` | `/galeries` | accord parental pour les photos d'enfants |
| Bannières | `/admin/banners` | composant `banner` | voir [README_BANNERS.md](README_BANNERS.md) |
| Documents | `/admin/downloadables` (+ catégories, fichiers) | `/ebook` | fichiers privés dans `storage/app/private/downloads/` |
| Partenaires | `/admin/partners` | `/partenaires` | bandeau sur l'accueil |
| Boutique | `/admin/boutique` | `/boutique` | vitrine (pas de paiement en ligne) |
| Équipe du club | `/admin/equipe` | `/equipe-du-club` | membres et sections |
| Liens utiles | `/admin/liens-utiles` | `/liens-utiles` | |
| Installations | `/admin/services`, `structures`, `espaces`, `zones` | `/installations` | |
| Plan du site | `/admin/sitemap` | `/sitemap.xml` | `php artisan sitemap:generate [--discover]` |

Pages publiques fixes (`PublicController`) : accueil, contact, mentions légales, politique de confidentialité, cookies, revue de presse, inscriptions (`/plans-inscription`), guide.

## Éditeur de texte (Quill)

- Script : `public/js/quill-advanced.js` (tableaux, code HTML via le bouton `</>`).
- Le HTML enregistré est nettoyé par `app/Support/HtmlSanitizer.php`.
- Si des repères `___TABLE_PLACEHOLDER_n___` apparaissent dans l'éditeur : version ancienne du script en cache → **Cmd + Maj + R**. Ne pas enregistrer un contenu qui affiche ces repères.

## Médias et fichiers

- Images envoyées : `storage/app/public/…`, servies via `public/storage` → `php artisan storage:link` une fois par installation.
- Documents à télécharger : disque privé, servis par un contrôleur (pas d'accès direct).
- Pièces jointes de la messagerie : `storage/app/private/messages/`.

## Bannières (résumé)

Intégration dans une vue :

```blade
@include('components.banner', ['slug' => 'banniere-accueil'])
```

- Une bannière = un `slug` + des slides (image, titre, texte, bouton, couleur, position, voile sombre).
- Ordre des slides par glisser-déposer (SortableJS).
- Cache de 10 minutes, vidé automatiquement à chaque modification.
- Bannière invisible : slug exact ? bannière active ? au moins un slide actif ? `php artisan cache:clear`.

Détail complet : [README_BANNERS.md](README_BANNERS.md).

## Formulaire de contact

Destinataire : `config('mail.contact_to')` (variable `MAIL_CONTACT_TO` du `.env`). Sans configuration SMTP, les messages finissent dans `storage/logs/laravel.log` (voir [02-deploiement-o2switch.md](02-deploiement-o2switch.md)).
