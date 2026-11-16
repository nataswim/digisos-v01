# 11 · Tests automatiques

Les tests (PHPUnit) vérifient le site sans toucher à la vraie base : ils utilisent une base **SQLite en mémoire**, recréée à chaque test (`RefreshDatabase`), et les e-mails ne partent pas (`MAIL_MAILER=array`, voir `phpunit.xml`).

## Lancer

```bash
php artisan test                               # toute la suite
php artisan test --filter=MembersModuleTest    # un fichier
php artisan test --filter=lists_by_birth_year  # un seul test (partie du nom)
```

À faire **avant chaque `git push`**, au minimum le test du module modifié.

## État connu

- **10 tests Breeze** (dossier `tests/Feature/Auth` et `ProfileTest`) échouent depuis l'origine du projet : ils supposent l'inscription et le profil d'origine de Laravel Breeze, modifiés depuis. Ces échecs sont normaux ; tout **autre** échec est à corriger.
- Un avertissement « Deprecated … Implicitly marking parameter as nullable » vient d'une signature `string $x = null` : écrire `?string $x = null`.

## Fichiers de test par module

| Module | Fichier (`tests/Feature/`) |
|--------|---------------------------|
| Cadre des espaces (menu de gauche) | `EspaceShellTest` |
| Tableaux de bord | `AdminDashboardTest`, `EditorDashboardTest`, `MemberDashboardTest`, `VisitorDashboardTest`, `CoachSpaceTest` |
| Utilisateurs (dernière connexion, nageurs liés) | `AdminUsersListTest` |
| Adhérents | `MembersModuleTest` |
| Messagerie | `MessagingTest` |
| Épreuves | `SwimEventsTest` |
| Performances, import extraNat | `PerformanceModuleTest`, `PerformanceImportTest` |
| Records | `RecordsModuleTest` |
| Séances | `TrainingModuleTest` |
| Émargement, assiduité | `PresenceModuleTest` |
| Licences → nageurs | `SwimmersFromLicensesTest` |
| Contenus et pages | `PartnersModuleTest`, `ShopModuleTest`, `TeamModuleTest`, `LinksModuleTest`, `SupportPagesTest`, `SitemapModuleTest`, `EbookFilesTest` |
| Aperçu des espaces | `AreaPreviewTest` |

## Écrire un test : règles du projet

- **Données fictives uniquement** : noms inventés, adresses `@example.test`, IUF `9000001`…, villes « Villefictive ». Jamais de vraies données d'adhérents ou de licences.
- Créer les comptes avec un rôle : voir la méthode `account()` ou `userWithRole()` des tests existants (mot de passe `secret-password`).
- **Services externes** simulés : `Http::fake([...])` + `Http::preventStrayRequests()` (ex. geo.api.gouv.fr dans `MembersModuleTest`), `Notification::fake()`, `Storage::fake('local')`.
- `assertSee('texte')` échappe le HTML : pour un texte avec apostrophe dans du code Blade brut, ou pour du HTML, utiliser `assertSee('…', false)`.
- **Dates en SQLite** : une colonne `date` est stockée avec l'heure (`2026-12-12 00:00:00`) : comparer avec `whereDate(...)`, pas `where('performed_on', '2026-12-12')`.
- Vérifier aussi les **interdits** : une famille ne doit pas voir la fiche ou la conversation d'une autre (`assertNotFound()` / `assertForbidden()`).
