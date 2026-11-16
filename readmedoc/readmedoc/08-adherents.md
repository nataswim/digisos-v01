# 08 · Adhérents

Suivi des adhérents du club, saison par saison. **Accès : administrateurs et éditeurs uniquement** (coach, adhérent, visiteur → 403).

| | |
|---|---|
| Pages | `/admin/adherents`, `/editor/adherents` |
| Routes | `routes/members.php` (noms `admin.members.*` / `editor.members.*`) |
| Contrôleurs | `app/Http/Controllers/Admin/Members/` (base commune `MembersArea`) |
| Vues | `resources/views/admin/members/` |
| Test | `php artisan test --filter=MembersModuleTest` |

## Données

| Table | Contenu |
|-------|---------|
| `members` | identité, coordonnées, droit à l'image, 2 responsables légaux (JSON), liens : fiche nageur (`planning_swimmer_id`), compte du site (`user_id`), IUF |
| `memberships` | une adhésion par **saison** (`2026-2027`) : situation, catégorie, tarif, remise, montant, statut, dossier (pièces), Pass'Sport, attestation, mini-coach, année du certificat médical, observations |
| `membership_payments` | règlements un par un (mode, montant, date, encaissé) |
| `member_categories` | catégories et tarifs (ENF-INITIATION, RELEVE, JEUNES, ADULTES, MAITRES, ENCADREMENT, BENEVOLE, SPECIALE…) |
| `member_logs` | journal des modifications (qui, quand, avant → après) |

- Montant = tarif − remise. **Reste à payer** = montant − Pass'Sport − règlements.
- Statuts : Pré-inscrit, Dossier en cours, Complet, Licencié, Abandon.
- Pièces du dossier : fiche, fiche sanitaire, certificat, photo, règlement, caution. « Complet » quand toutes sont cochées.
- Certificat médical : alerte pour les adultes dont le certificat a 3 ans ou plus ; mineurs = questionnaire de santé.

## Pages du module

| Page | Adresse (`/admin/adherents/…`) |
|------|-------------------------------|
| Liste (filtres, totaux) | `/` |
| Fiche / modifier / nouveau | `/{id}`, `/{id}/modifier`, `/nouveau` |
| Tableau de bord (graphiques Chart.js, carte des communes Leaflet, âges extrêmes) | `/tableau-de-bord` |
| Par année de naissance | `/par-annee-de-naissance` |
| Par commune | `/par-commune` |
| Import du tableau Excel | `/import` |
| Catégories et tarifs | `/categories` |
| Export CSV (et liste « droit à l'image refusé ») | `/export` |
| Préparer la saison suivante | bouton sur la liste |
| Attestation de paiement imprimable | depuis la fiche |

Toutes les pages acceptent `?saison=2026-2027`.

## Import du tableau Excel

Copier le tableau **avec la ligne de titres** (Observations, …, NOM, Prénom, …, Montant) et le coller, ou envoyer un CSV. Lecteur : `Services/MemberSheetParser.php`.

- Formats reconnus : O/N, dates jj/mm/aaaa, montants « 190,00 € », « Déjà présent » = renouvellement.
- Adhérent reconnu par nom + prénom + date de naissance ; **une cellule vide n'efface rien** ; catégories inconnues créées (vérifier leur tarif).
- Aperçu avant enregistrement.
- Pas de dépôt direct de .xlsx (ce serait possible avec PhpSpreadsheet).

## Carte des communes

- Positions : API officielle **geo.api.gouv.fr** (`Services/CommuneLocator.php`) ; seuls le code postal et le nom de la commune sont envoyés.
- Cache 1 an (échec : 1 jour). Si l'API ne répond pas, la page s'affiche quand même, sans attendre.
- Dans les tests, l'API est simulée (`Http::fake`).

## Âges

- Graphique « Par âge » : âge au **1er septembre** de la saison, tranches de 8 ans et moins à 71 ans et plus (`MemberStatsController::AGE_BRACKETS`).
- Page « Par année de naissance » : âge atteint dans l'année du début de saison (calcul des catégories FFN).

## Vue côté famille

Le tableau de bord adhérent affiche la fiche adhérent de la saison (catégorie, statut, pièces manquantes, cotisation, coordonnées), sans les observations internes ni le journal. Voir [05-espaces-connectes.md](05-espaces-connectes.md).

## Confidentialité

- Données d'enfants : ne jamais coller de vraies lignes du tableau dans des échanges, des tests ou la documentation.
- Mentionner ce traitement dans la **politique de confidentialité** (finalité, conservation, accès réservé au bureau).
