# 10 · Données de départ (seeders)

Les seeders (`database/seeders/`) remplissent une base **vide** avec des données de démonstration. Ils servent en local et pour une première installation.

> ⚠️ **En production, ne jamais lancer `php artisan db:seed` sans `--class`, ni `migrate:fresh --seed`.**
> La plupart des seeders **vident leur table** avant de la remplir : comptes, articles, pages, planning… seraient remplacés par les données de démonstration.

## Lancer

```bash
# Local : base neuve complète
php artisan migrate:fresh --seed

# Un seul seeder
php artisan db:seed --class=NomDuSeeder
# en production, ajouter --force (et faire une sauvegarde avant)
```

Ordre utilisé par `DatabaseSeeder` :

1. **Comptes** : `RolesTableSeeder`, `PermissionsTableSeeder`, `RolePermissionTableSeeder`, `UsersTableSeeder`, `UserProfilesSeeder`
2. **Classements** : `CategoriesTableSeeder`, `TagsTableSeeder`, `FichesCategoriesSeeder`, `FichesSousCategoriesSeeder`, `PagesCategoriesSeeder`, `VideoCategoriesSeeder`, `DownloadCategoriesSeeder`
3. **Contenus** : `PostsTableSeeder`, `TaggablesTableSeeder`, `FichesSeeder`, `PagesSeeder`, `PagesOrganisationSeeder`, `GroupesPagesSeeder`, `PlanningSeeder`, `SwimEventsSeeder`, `VideosSeeder`, `DownloadablesSeeder`

Un récapitulatif (nombre de lignes par table) s'affiche à la fin.

## Ce qu'on peut relancer en production

| Seeder | En production | Pourquoi |
|--------|:-------------:|----------|
| `PermissionsTableSeeder` puis `RolePermissionTableSeeder` | ✅ (avec `--force`, les deux à la suite) | recrée la liste des permissions et leurs attributions après l'ajout d'un module |
| `SwimEventsSeeder` | ✅ | n'ajoute que les épreuves manquantes, garde les libellés, l'ordre et l'état choisis dans l'admin |
| `DownloadablesSeeder` | ⚠️ seulement si les PDF sont déposés dans `storage/app/private/downloads/` | |
| `RolesTableSeeder` | ❌ | vide la table des rôles : les comptes perdraient leur rôle |
| `UsersTableSeeder` | ❌ | **vide la table des comptes** |
| `PlanningSeeder` | ❌ sauf planning vide | vide les tables du planning |
| `PagesCategoriesSeeder`, `PagesSeeder` | ❌ sauf aucune page saisie à la main | vident leurs tables |
| Seeders de contenus (articles, fiches, vidéos, catégories, tags…) | ❌ | remplacent le contenu réel |

Toujours **sauvegarder la base** avant (phpMyAdmin → Exporter).

## Comptes de démonstration

Créés par `UsersTableSeeder`, avec des adresses **fictives** :

| Rôle | Adresse |
|------|---------|
| admin | `SEED_ADMIN_EMAIL` du `.env`, sinon `admin@example.com` |
| editor | `communication@example.com` |
| user | `adherent@example.com`, `competiteur@example.com` |
| visitor | `visiteur@example.com`, `nouveau@example.com` (adresse non confirmée) |

- Mot de passe commun : `SEED_PASSWORD` du `.env`, sinon `password` (un avertissement s'affiche).
- Sur un serveur en ligne : définir `SEED_ADMIN_EMAIL`, `SEED_ADMIN_NAME`, `SEED_PASSWORD` **avant** le seeding, puis **retirer ces lignes du `.env`** et **supprimer les comptes de test**.
- Le rôle `coach` existe mais aucun compte coach n'est créé : passer un compte en `coach` depuis Administration → Utilisateurs.

## Comptes utilisés par les tests

Les tests automatiques créent leurs propres comptes fictifs (`admin@example.test`, `coach@example.test`…, mot de passe `secret-password`) dans une base temporaire : rien n'est écrit dans la vraie base. Voir [11-tests.md](11-tests.md).

## Dépannage

| Problème | Solution |
|----------|----------|
| « Class … not found » | `composer dump-autoload` |
| Erreur de clé étrangère | respecter l'ordre ci-dessus (rôles avant comptes, catégories avant contenus) |
| Données en double en local | `php artisan migrate:fresh --seed` (**local uniquement**) |
| Images manquantes | les chemins des seeders sont des exemples : remplacer par de vraies images via la médiathèque |
