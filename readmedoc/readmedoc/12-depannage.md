# 12 · Dépannage — erreurs déjà rencontrées

Premier réflexe : lire la fin du journal.

```bash
tail -n 50 storage/logs/laravel.log
```

Et vider les caches :

```bash
php artisan optimize:clear     # config, routes, vues, événements
php artisan optimize           # en production, reconstruire ensuite
```

## Installation d'une livraison

| Symptôme | Cause | Solution |
|----------|-------|----------|
| `Target class [App\…\XController] does not exist` / `Class "…" not found` | nouvelle classe non connue de Composer, ou fichier absent | `ls` du fichier ; s'il manque : `unzip -o` de l'archive ; puis `composer dump-autoload -o` et `php artisan optimize:clear` |
| Les routes existent mais l'ancienne page s'affiche | vues ou routes en cache | `php artisan view:clear` / `php artisan route:clear` |
| `Route [admin.xxx.index] not defined` | route non chargée (fichier de routes non inclus, cache) | `php artisan route:list --name=xxx` ; `php artisan route:clear` |
| Nouvel écouteur (ex. dernière connexion) sans effet | liste des événements en cache | `php artisan event:clear && php artisan optimize` |
| `SQLSTATE … table … doesn't exist` | migration non lancée | `php artisan migrate` (`--force` en production) |
| Archive décompressée dans un dossier à part | ouverte avec le Finder | `unzip -o ~/Downloads/archive.zip` depuis la racine du projet |

## Git

| Symptôme | Solution |
|----------|----------|
| `git: 'pus' is not a git command` | faute de frappe : `git push` (le commit, lui, est déjà fait) |
| `git pull` sur le serveur : « Your local changes would be overwritten » | quelqu'un a modifié un fichier sur le serveur : `git status`, puis `git stash` (ou `git checkout -- fichier`) |
| `ssh: connect to host github.com port 22: Connection refused` | utiliser `ssh.github.com` port 443 dans l'alias SSH, ou ré-autoriser GitHub dans cPanel → Autorisation SSH |
| `fetch` refusé (403) | dépôt privé sans accès : vérifier la clé / les droits |

## Affichage

| Symptôme | Cause | Solution |
|----------|-------|----------|
| Style pas à jour | cache du navigateur | **Cmd + Maj + R** |
| Repères `___TABLE_PLACEHOLDER_n___` dans l'éditeur | ancien `quill-advanced.js` en cache | Cmd + Maj + R ; ne pas enregistrer le contenu ; recoller les tableaux via `</>` |
| Images envoyées invisibles | lien `public/storage` absent | `php artisan storage:link` |
| Une couleur de carte ne s'applique pas | une règle du cadre (`espace-shell.css`, `!important`) est plus précise | sélecteur plus précis (voir [05-espaces-connectes.md](05-espaces-connectes.md)) |
| Page plus large que l'écran sur mobile | élément `visually-hidden` dans un tableau défilant, ou `d-flex gap-2` du thème | `position-relative` sur `.table-responsive` ; classe propre |
| Pied de page à droite du contenu | une `</div>` en trop dans la vue | compter les `<div>` / `</div>` de la section `content` |
| Bannière absente | slug, bannière ou slide inactifs, cache | vérifier dans l'admin ; `php artisan cache:clear` |
| Carte des communes vide | geo.api.gouv.fr injoignable | réessayer plus tard ; la liste des communes reste affichée |

## Blade

- `texte@if (...)` collé sans espace : le `@if` n'est pas compilé → ajouter un espace ou utiliser `{{ '' }}`.
- `@push('scripts')` doit être fermé par `@endpush` (une page de formulaire de documents avait une balise non fermée).
- `@once … @endonce` pour un style poussé par un composant utilisé plusieurs fois.

## E-mails

| Symptôme | Solution |
|----------|----------|
| « Message envoyé » mais rien reçu | `.env` sans `MAIL_*` → les e-mails sont dans `storage/logs/laravel.log` ; configurer le SMTP puis `php artisan optimize` |
| Erreur de certificat SMTP | `MAIL_HOST` doit être le nom du serveur o2switch, pas `mail.natation-bressuire.fr` |
| Tester | `php artisan tinker --execute="Mail::raw('Test', fn (\$m) => \$m->to(config('mail.contact_to'))->subject('Test'));"` |

## Données

- **« Dossier complet »** ne se cochait pas automatiquement : format des pièces corrigé (`Membership::providedFileItems()`), novembre 2026.
- **« Jamais connecté »** pour tous les comptes : la date n'était pas enregistrée ; corrigé par `app/Listeners/RecordUserLogin.php` (les connexions antérieures restent inconnues).
- **Tranches d'âge dans le désordre** : tri alphabétique ; corrigé par une liste ordonnée (`MemberStatsController::AGE_BRACKETS`).
- **Carbon 3** : `diffInYears()` renvoie un nombre décimal → `(int) floor(abs(...))`.

## Si rien ne marche

1. `php artisan migrate:status` — migrations exécutées ?
2. `php artisan route:list --name=…` — route chargée ?
3. `ls resources/views/…` — vue présente ?
4. `php artisan optimize:clear`
5. Console du navigateur (F12) — erreur JavaScript ?
6. `storage/logs/laravel.log`
