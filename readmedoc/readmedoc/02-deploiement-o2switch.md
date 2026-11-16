# 02 · Déploiement sur o2switch

Site en ligne : https://natation-bressuire.fr (mis en ligne le 11/10/2026)

## Où est le site

- Hébergement mutualisé **o2switch** (cPanel), compte partagé avec d'autres sites : **ne jamais changer un réglage global** (version PHP, clés SSH, tâches cron) sans vérifier les autres sites.
- Projet cloné dans `~/natation-bressuire.fr` ; la racine du domaine pointe sur `natation-bressuire.fr/public`.
- Dépôt GitHub `nataswim/digisos-v01`, branche `main`. Le serveur tire le code avec une **clé de déploiement en lecture seule** (alias SSH défini dans `~/.ssh/config`).
- PHP **8.4** (réglé dans cPanel → « Sélectionner une version de PHP »). `composer.json` fixe `config.platform.php = 8.3.35` pour rester installable en 8.3 : **ne pas retirer ce réglage**.

## Cycle de mise à jour

```
Mac (code + tests)  →  git push  →  GitHub  →  git pull sur le serveur
```

Sur le serveur (Terminal cPanel ou SSH) :

```bash
cd ~/natation-bressuire.fr
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
php artisan up
```

Selon la livraison, ajouter :

| Situation | Commande |
|-----------|----------|
| Nouvelles permissions | `php artisan db:seed --class=PermissionsTableSeeder --force` puis `--class=RolePermissionTableSeeder --force` |
| Nouvelles épreuves de natation | incluses dans la migration (pas de seeder à lancer) |
| Vues qui ne se mettent pas à jour | `php artisan view:clear` |
| Nouveaux écouteurs d'événements | `php artisan event:clear && php artisan optimize` |
| Modification du `.env` | `php artisan optimize` |
| Images envoyées invisibles | `php artisan storage:link` |

Après une mise à jour de CSS/JS : rechargement forcé du navigateur (**Cmd + Maj + R**).

## Avant une migration : sauvegarder la base

cPanel → **phpMyAdmin** → choisir la base → **Exporter** → Exécuter
(ou cPanel → Sauvegarde → « Télécharger une sauvegarde de base de données MySQL »).

## E-mails

Configuration SMTP dans le `.env` du serveur (`MAIL_*`). Points d'attention :

- `MAIL_HOST` doit être le **nom du serveur o2switch** (celui du certificat), pas `mail.natation-bressuire.fr`.
- Port 465, `MAIL_SCHEME=smtps`.
- Mot de passe entre guillemets doubles (apostrophes s'il contient `$` ou `"`).
- Sans lignes `MAIL_*`, Laravel écrit les e-mails dans `storage/logs/laravel.log` au lieu de les envoyer.

Test rapide :

```bash
php artisan tinker --execute="Mail::raw('Test du site CNBB', fn (\$m) => \$m->to(config('mail.contact_to'))->subject('Test envoi'));"
```

## Services externes utilisés

| Service | Utilisé par | Remarque |
|---------|-------------|----------|
| geo.api.gouv.fr | carte des communes (tableau de bord adhérents) | gratuit, sans clé ; positions gardées en cache 1 an |
| OpenStreetMap (tuiles) | même carte | |
| cdnjs (Chart.js, Leaflet, Font Awesome, Sortable) | graphiques, carte, icônes, glisser-déposer | |
| Google Fonts (Manrope) | style des espaces connectés | |
| cdn.quilljs.com | éditeur de texte riche | |

## À ne pas faire en production

- `php artisan migrate:fresh` ou `db:seed` sans `--class` : **efface ou remplace les données**.
- Les seeders qui vident leur table (`PlanningSeeder`, `PagesCategoriesSeeder`, `PagesSeeder`) : seulement sur une base sans données saisies à la main, après sauvegarde.
- Modifier un fichier du projet directement sur le serveur (le prochain `git pull` échouerait).
- Versionner des copies de sauvegarde (`*0.js`, `*00.blade.php`, `error_log`).

## En cas de problème

- Le site affiche une erreur 500 : `tail -n 50 storage/logs/laravel.log`
- Le site reste en maintenance : `php artisan up`
- `git pull` refuse (« local changes ») : `git status`, puis `git stash` si les modifications locales ne sont pas voulues.
- `git pull` : « Connection refused » sur le port 22 : utiliser `ssh.github.com` port 443 dans l'alias SSH, ou ré-autoriser GitHub dans cPanel → Autorisation SSH.

Voir aussi [12-depannage.md](12-depannage.md).
