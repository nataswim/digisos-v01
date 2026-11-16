# 07 · Modules sportifs

Accès : admin et coachs (et éditeurs pour planning et compétitions). Les familles voient la partie qui concerne leurs nageurs dans l'espace adhérent.

## Planning des entraînements

| | |
|---|---|
| Pages | `/{admin,editor,coach}/planning/…` : créneaux, groupes, nageurs |
| Public | `/groupes-et-horaires` (ancre par groupe : `#groupe-{slug}`, période : `?periode=vacances`) |
| Modèles | `PlanningGroup` (section, couleur), `PlanningSlot` (période `scolaire`/`vacances`, jour, horaires, lieu), `PlanningSwimmer` |
| Routes | `routes/planning.php` (partagé) |

- **Sections** et leurs couleurs : École de natation, Jeunesse, Relève, Ligue, Maîtres (`PlanningGroup::SECTIONS`). Un groupe sans couleur prend celle de sa section.
- **Nageur** (`PlanningSwimmer`) : année de naissance, sexe, licence/IUF, photo, groupes, et **compte du site** (`user_id`) = la famille qui voit ses informations.
- `PlanningSeeder` **vide les tables** du planning : jamais en production sur des données saisies.

## Compétitions

| | |
|---|---|
| Pages | `/{admin,editor,coach}/competitions` |
| Public | `/competitions` (calendrier, fiche de chaque compétition) |
| Adhérent | `/user/mes-competitions` (convocations, réponse, covoiturage) |
| Routes | `routes/competitions.php` (partagé) |

- **Réunions** (`CompetitionSession`) et **programme des épreuves** : grille épreuve × Dames / Messieurs / Mixte × tour (`/competitions/{slug}/programme`).
- **Épreuves** (référentiel `swim_events`) : `/competitions-epreuves`. 25 m de chaque nage, 50 → 1500 NL, dos, brasse, papillon, 4 nages, « Divers (2 nages) », relais (4×50, 4×100, 4×200, 10×50 NL, 4 nages). On peut **ajouter, renommer, réordonner, désactiver** ; une épreuve déjà utilisée est désactivée au lieu d'être supprimée.
- **Convocations** (`/competitions/{slug}/convocations`) : statuts `convoque` (en attente), `confirme`, `absent`, `forfait` ; engagements par épreuve. E-mail `CompetitionConvocationNotification` à la famille.
- La famille confirme ou excuse le nageur depuis son espace ; le tableau de bord adhérent affiche les convocations **en haut, en rouge**.

## Records du club

| | |
|---|---|
| Pages | `/coach/records` (+ `/coach/records/importer`) |
| Public | `/records` |
| Import | copier-coller depuis extraNat, lu par `Services/FfnRecordParser.php` (accepte une cellule par ligne) |

## Performances des nageurs

| | |
|---|---|
| Pages | `/coach/performances` : derniers temps, `saisie`, `bilan`, `classement`, `nageur/{id}` |
| Import extraNat | `/coach/performances/importer` — **administrateurs uniquement** |
| Adhérent | `/user/performances` |
| Calculs | `Services/PerformanceTracker.php` (meilleur temps personnel, écart, %, finale vs série) |

- Table `swim_performances` : nageur, compétition ou meeting extérieur ou contre-la-montre, date, bassin 25/50, épreuve, tour, temps en centièmes (`time_cs`), statut (valide, DSQ, abandon, forfait).
- **Import extraNat** (`Services/ExtranatPerformanceParser.php`) : coller la page « Performances » d'un ou plusieurs nageurs → aperçu → import.
  - Nageur reconnu par l'**IUF**, sinon nom + prénom + année ; alerte rouge si l'IUF correspond à une fiche d'un autre nom.
  - Tout l'historique, tour « Série », points FFN et niveau ignorés, relais ignorés.
  - Doublon (même nageur, épreuve, bassin, date, temps) jamais ré-importé.
  - Rattachement automatique à une compétition du calendrier de même date et même ville.

## Séances et entraînements

| | |
|---|---|
| Pages | `/coach/seances` (+ `exercices`, `listes`) |
| Adhérent | `/user/entrainements` |

Types : natation, préparation physique (PPG), séance à la maison, école de natation, divers. Bibliothèque d'exercices, listes réglables (nages, matériel…), publication, duplication.

## Émargement et assiduité

| | |
|---|---|
| Pages | `/coach/emargement` (présences par créneau, séance supplémentaire), `/coach/assiduite` (par nageur, par groupe) |
| Adhérent | `/user/assiduite` |
| Calculs | `Services/PresenceStats.php` |

## Suivi des nageurs et documents coachs

- `/coach/suivi` : notes de suivi par nageur (réservées aux coachs).
- `/coach/documents` : documents partagés entre coachs.

## Licences FFN

| | |
|---|---|
| Pages | `/admin/licences-ffn` (+ `/importer`) — administrateurs |
| Import | fichier CSV exporté d'extraNat, lu par `Services/FfnLicenseCsvParser.php`, avec écran de vérification avant enregistrement |

- Les licences servent à retrouver l'IUF d'un nageur et à pré-remplir les fiches adhérent.
- Le fichier contient des **données de mineurs** : ne jamais le copier dans le dépôt, les tests ou la documentation (tests : IUF fictifs `9000001`, `9000002`…).
