# 04 · Rôles et accès

Chaque compte a **un rôle** (table `roles`, champ `users.role_id`) et un statut (`active`, …). Un compte inactif n'accède à aucun espace.

## Les 5 rôles

| Rôle (`slug`) | Pour qui | Espaces accessibles |
|---------------|----------|---------------------|
| `admin` | bureau, responsables du site | tout : administration, éditeur, coachs, adhérent |
| `editor` | bénévoles chargés du contenu | espace éditeur (+ adhérent) |
| `coach` | entraîneurs | espace coachs (+ adhérent) |
| `user` | **adhérents validés** : nageurs, parents | espace adhérent |
| `visitor` | compte créé sur le site, **pas encore validé** | espace visiteur |

Les règles sont dans `app/Providers/AppServiceProvider.php` :

```php
Gate::define('access-admin',  fn ($u) => actif + rôle admin);
Gate::define('access-editor', fn ($u) => actif + rôle admin ou editor);
Gate::define('access-coach',  fn ($u) => actif + rôle admin ou coach);
```

et appliquées aux groupes de routes (`->middleware('can:access-editor')`).

## Inscription et validation

1. La personne crée son compte sur `/register` et coche son profil (adhérent nageur, école de natation, Maîtres, partenaire, bénévole).
2. Le compte est créé avec le rôle **visitor** : il ne voit rien des nageurs.
3. Un administrateur **vérifie l'adhésion** puis passe le rôle à `user` (Administration → Utilisateurs).
4. Pour un nageur mineur, le compte est celui d'un parent : on **rattache l'enfant** au compte dans Planning → Nageurs (champ « compte du site »). Plusieurs enfants = un seul compte.

## Qui peut faire quoi (modules)

| Module | Admin | Éditeur | Coach | Adhérent |
|--------|:-----:|:-------:|:-----:|:--------:|
| Contenus (articles, pages, fiches, vidéos, galeries) | ✔ | ✔ | — | lecture |
| Planning (créneaux, groupes, nageurs) | ✔ | ✔ | ✔ | ses créneaux |
| Compétitions, programme, convocations | ✔ | ✔ | ✔ | ses convocations (répondre) |
| Épreuves (référentiel) | ✔ | ✔ | ✔ | — |
| Séances, émargement, assiduité, notes de suivi | ✔ | — | ✔ | ses séances / son assiduité |
| Performances (saisie) | ✔ | — | ✔ | ses performances |
| Performances : **import extraNat** | ✔ | — | — | — |
| Records du club | ✔ | — | ✔ | lecture (site public) |
| **Adhérents** (fiches, règlements, import) | ✔ | ✔ | — | sa propre fiche (tableau de bord) |
| Licences FFN | ✔ | — | — | — |
| Messagerie | toutes les conversations, suppression | ses conversations | ses conversations | ses conversations ; écrire à un coach |
| Utilisateurs, rôles, permissions | ✔ | — | — | — |

## Permissions détaillées

Une table `permissions` (≈ 130 lignes) et `role_permission` existent, remplies par `PermissionsTableSeeder` et `RolePermissionTableSeeder`. Après l'ajout d'un module qui en déclare :

```bash
php artisan db:seed --class=PermissionsTableSeeder --force
php artisan db:seed --class=RolePermissionTableSeeder --force
```

## Données personnelles

- Les données des adhérents (dont des **mineurs**) ne sont visibles que par l'admin et l'éditeur, et par la famille pour ses propres nageurs.
- Les photos d'enfants nécessitent l'**accord parental** (champ « droit à l'image » de la fiche adhérent).
- Aucune vraie donnée dans les tests, la doc ou les échanges : données fictives uniquement.
- Supprimer les comptes de test `@example.com` / `@example.test` en production.
