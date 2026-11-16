# Site du CNBB — natation-bressuire.fr

Application Laravel 12 du **Cercle des Nageurs du Bocage Bressuirais** : site public, administration, espaces éditeur, coachs, adhérents et visiteurs.

📚 **Toute la documentation est dans [`readmedoc/`](readmedoc/README.md)** : installation, déploiement o2switch, architecture, rôles, modules, tests et dépannage.

```bash
# Installation locale (résumé)
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed && php artisan storage:link
php artisan serve
```

Ne jamais versionner le `.env`, des mots de passe ni de vraies données d'adhérents : ce dépôt est public.
