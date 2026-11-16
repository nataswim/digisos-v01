# 01 · Installation locale (Mac + MAMP)

Dossier du projet sur le Mac : `/Users/mac/Documents/digisos-v01/digisos-v01`

## Prérequis

- PHP 8.3 ou 8.4 (celui de MAMP ou Homebrew), Composer 2
- MySQL : **MAMP**, port **8889** (démarrer MAMP avant toute commande qui touche la base)
- Node.js + npm (uniquement pour recompiler les CSS/JS du thème, voir plus bas)
- Git, avec accès au dépôt `nataswim/digisos-v01`

## Première installation

```bash
git clone https://github.com/nataswim/digisos-v01.git
cd digisos-v01

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Dans `.env`, régler la base (utiliser `127.0.0.1`, pas `localhost`) :

```dotenv
APP_URL=http://127.0.0.1:8000
APP_LOCALE=fr
APP_TIMEZONE=Europe/Paris

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=digisos
DB_USERNAME=root
DB_PASSWORD=root

MAIL_MAILER=log        # en local, les e-mails sont écrits dans storage/logs/laravel.log
```

Puis :

```bash
php artisan migrate
php artisan db:seed          # données de démonstration, voir README-SEEDERS.md
php artisan storage:link     # images et fichiers envoyés (lien public/storage)
php artisan serve            # http://127.0.0.1:8000
```

## Installer une livraison (archive .zip)

Les évolutions sont souvent livrées sous forme d'archive à décompresser **à la racine du projet** :

```bash
cd /Users/mac/Documents/digisos-v01/digisos-v01
git pull                                   # partir de la dernière version
unzip -o ~/Downloads/nom-de-l-archive.zip  # -o : remplace les fichiers existants
composer dump-autoload -o                  # nouvelles classes PHP
php artisan migrate                        # si l'archive contient une migration
php artisan view:clear
php artisan test --filter=NomDuTest        # test indiqué avec la livraison
git add -A && git commit -m "…" && git push
```

- Utiliser `unzip` dans le Terminal, **pas le Finder** (qui crée un dossier à part au lieu de remplacer les fichiers).
- Si le dossier Téléchargements contient `archive (1).zip`, prendre le plus récent.

## CSS et JavaScript

- Les styles des **espaces connectés** sont des fichiers CSS simples, sans compilation :
  `public/css/espaces.css` (composants) et `public/css/espace-shell.css` (cadre : menu de gauche, barre du haut).
- Le thème public est compilé avec **Laravel Mix** (`webpack.mix.js`) :
  ```bash
  npm run dev     # développement
  npm run prod    # version à mettre en ligne (fichiers de public/css et public/js à versionner)
  ```
  Il n'y a pas de Node sur le serveur : on compile sur le Mac et on pousse les fichiers compilés.

## Commandes Git utiles

```bash
git remote -v      # adresse du dépôt
git status         # fichiers modifiés
git log --oneline -5
git tag            # versions
```
