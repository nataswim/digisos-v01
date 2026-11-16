# Documentation du site CNBB (digisos-v01)

Site du **Cercle des Nageurs du Bocage Bressuirais** : https://natation-bressuire.fr
Laravel 12 · PHP 8.3/8.4 · MySQL · Bootstrap 5 · hébergement o2switch.

Ce dossier est le **pense-bête de l'application**. Un fichier par sujet :

| # | Fichier | À lire pour… |
|---|---------|--------------|
| 01 | [Installation locale](01-installation-locale.md) | installer le projet sur le Mac (MAMP), lancer le site en local |
| 02 | [Déploiement o2switch](02-deploiement-o2switch.md) | mettre à jour le site en ligne, cycle Mac → GitHub → serveur |
| 03 | [Architecture](03-architecture.md) | comprendre où est quoi : routes, contrôleurs, vues, CSS/JS |
| 04 | [Rôles et accès](04-roles-acces.md) | qui voit quoi : admin, éditeur, coach, adhérent, visiteur |
| 05 | [Espaces connectés](05-espaces-connectes.md) | cadre « extraNat » (menu de gauche), tableaux de bord |
| 06 | [Contenus et site public](06-contenus.md) | articles, pages, fiches, vidéos, médias, bannières, boutique… |
| 07 | [Sportif](07-sportif.md) | planning, compétitions, épreuves, convocations, records, performances, séances, émargement, licences FFN |
| 08 | [Adhérents](08-adherents.md) | fiches, adhésions, règlements, import du tableau, statistiques |
| 09 | [Messagerie](09-messagerie.md) | conversations privées, envois groupés, e-mails |
| 10 | [Données et seeders](README-SEEDERS.md) | remplir la base, comptes de test, ce qu'il ne faut pas lancer en production |
| 11 | [Tests](11-tests.md) | lancer et écrire les tests |
| 12 | [Dépannage](12-depannage.md) | erreurs déjà rencontrées et leur solution |
| — | [Bannières (détail)](README_BANNERS.md) | carrousels : utilisation et intégration |

## Les 5 commandes à retenir

```bash
# Sur le Mac, après une modification
git add -A && git commit -m "Message clair" && git push

# Sur le serveur o2switch, pour mettre en ligne
cd ~/natation-bressuire.fr
php artisan down && git pull && composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan optimize && php artisan up
```

Arborescence du projet (sans vendor, node_modules, storage) :

```bash
tree -L 8 -I 'vendor|node_modules|storage|bootstrap/cache' > structure.txt
```

## Règles d'or

1. **On ne modifie jamais le code directement sur le serveur** : tout passe par le Mac, puis GitHub, puis `git pull`.
2. **Jamais de vraies données d'adhérents** (noms, adresses, licences de mineurs…) dans le code, les tests, la documentation ou les captures. Données fictives uniquement (`@example.test`, IUF `9000001`…).
3. **Jamais de mot de passe ni de `.env`** dans le dépôt : il est public.
4. **Sauvegarder la base** (phpMyAdmin → Exporter) avant toute migration en production.
5. **Lancer les tests** (`php artisan test`) avant de pousser.
