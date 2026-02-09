# digisos-v01
tree -L 8 -I 'vendor|node_modules|storage|bootstrap/cache' > structure.txt


# 1. Ajouter tous les fichiers modifiés à l'index
git add .
git commit -m "Correction"
git push origin main







#  Installation : 
# 1. Récupération du projetClonage du dépôt depuis GitHub vers le répertoire local :
git clone https://github.com/votre-utilisateur/digisos-v01.git
cd digisos-v01
# 2. Installation des dépendances
Le projet utilise à la fois Node.js (pour le frontend) et PHP/Laravel (pour le backend).
Backend (PHP) : 
composer install 
npm install 
# 3. Configuration de l'environnement Laravel 
cp .env.example .env
Générer la clé d'application : 
php artisan key:generate
# 4. Configuration de la base de données 
 Utiliser 127.0.0.1 au lieu de localhost
# 5. Initialisation de la structure (Migrations)
php artisan migrate
# 6. Lancement de l'application 
npm run dev
# Voir
php artisan serve
# Git
Vérifier l'URL du dépôt 
git remote -v
Voir la version (tags)
git tag

# npm run dev (pour le mode développement) 
# npm run build (pour une version fixe)

# Best Laravel Packages
https://laraveldaily.com/packages