# Digital'SOS - Seeders Documentation

## 📦 Seeders Générés

### Phase 1 : Fondations
1. ✅ **RolesTableSeeder.php** - 4 rôles (admin, editor, user, visitor)
2. ✅ **PermissionsTableSeeder.php** - 30 permissions CRUD
3. ✅ **RolePermissionTableSeeder.php** - Attribution permissions aux rôles
4. ✅ **UsersTableSeeder.php** - 8 utilisateurs (2 par rôle)

### Phase 2 : Taxonomie
5. ✅ **CategoriesTableSeeder.php** - 3 catégories posts
6. ✅ **TagsTableSeeder.php** - 3 tags
7. ✅ **FichesCategoriesSeeder.php** - 3 catégories fiches
8. ✅ **FichesSousCategoriesSeeder.php** - 3 sous-catégories fiches
9. ✅ **PagesCategoriesSeeder.php** - 3 catégories pages
10. ✅ **VideoCategoriesSeeder.php** - 3 catégories vidéos
11. ✅ **DownloadCategoriesSeeder.php** - 3 catégories téléchargements

### Phase 3 : Contenu
12. ✅ **PostsTableSeeder.php** - 3 posts avec contenu riche
13. ✅ **TaggablesTableSeeder.php** - 6 associations tags-posts
14. ✅ **FichesSeeder.php** - 3 fiches techniques
15. ✅ **PagesSeeder.php** - 3 pages statiques
16. ✅ **VideosSeeder.php** - 3 vidéos YouTube/Vimeo
17. ✅ **DownloadablesSeeder.php** - 3 fichiers téléchargeables

### Orchestrateur
18. ✅ **DatabaseSeeder.php** - Seeder principal

---

## 🚀 Installation

### 1. Copier les seeders
```bash
# Copier tous les fichiers .php dans database/seeders/
cp *.php /chemin/vers/votre-projet/database/seeders/
```

### 2. Vérifier les dépendances
Assurez-vous que tous vos models existent :
- User, Role, Permission
- Post, Category, Tag
- Fiche, FichesCategory, FichesSousCategory
- Page, PagesCategory
- Video, VideoCategory
- Downloadable, DownloadCategory

---

## 📝 Utilisation

### Option 1 : Seeding complet (recommandé)
```bash
# Réinitialiser et peupler toute la base
php artisan migrate:fresh --seed

# OU si migrations déjà faites
php artisan db:seed
```

### Option 2 : Seeding par phase
```bash
# Phase 1 uniquement (Utilisateurs)
php artisan db:seed --class=RolesTableSeeder
php artisan db:seed --class=PermissionsTableSeeder
php artisan db:seed --class=RolePermissionTableSeeder
php artisan db:seed --class=UsersTableSeeder

# Phase 2 uniquement (Taxonomie)
php artisan db:seed --class=CategoriesTableSeeder
php artisan db:seed --class=TagsTableSeeder
# ... etc

# Phase 3 uniquement (Contenu)
php artisan db:seed --class=PostsTableSeeder
php artisan db:seed --class=FichesSeeder
# ... etc
```

### Option 3 : Seeding individuel
```bash
# Un seul seeder
php artisan db:seed --class=UsersTableSeeder
```

---

## 👥 Comptes de Test Créés

| Email | Mot de passe | Rôle | Description |
|-------|--------------|------|-------------|
| hassan@digitalsos.fr | password | Admin | Directeur fondateur |
| sophie.martin@digitalsos.fr | password | Admin | Administratrice système |
| jean.dupont@digitalsos.fr | password | Editor | Rédacteur en chef |
| marie.laurent@digitalsos.fr | password | Editor | Coordinatrice contenu |
| pierre.dubois@example.com | password | User | Coach sportif |
| claire.bernard@example.com | password | User | Athlète confirmée |
| lucas.petit@example.com | password | Visitor | Nouveau membre |
| emma.rousseau@example.com | password | Visitor | Découverte plateforme |

⚠️ **IMPORTANT** : Changez ces mots de passe en production !

---

## 📊 Données Créées

### Utilisateurs
- **8 utilisateurs** répartis sur 4 rôles
- Profils complets (bio, téléphone, avatar)
- Traçabilité login

### Permissions
- **30 permissions** organisées en 10 groupes
- CRUD complet par module

### Contenu
- **3 posts** avec catégories et tags
- **3 fiches** avec catégories et sous-catégories
- **3 pages** institutionnelles
- **3 vidéos** YouTube/Vimeo
- **3 téléchargements** (PDF, ZIP)

### Taxonomie
- **3 catégories** pour chaque type de contenu
- **3 sous-catégories** pour fiches
- **3 tags** transversaux

---

## 🔍 Vérification

### Tester la connexion
```bash
# Dans tinker
php artisan tinker

# Vérifier un utilisateur
User::where('email', 'hassan@digitalsos.fr')->first()

# Vérifier les permissions d'un rôle
Role::where('slug', 'admin')->first()->permissions->count()

# Compter les posts
Post::count()
```

### Accéder à l'interface
1. Connectez-vous avec `hassan@digitalsos.fr` / `password`
2. Vérifiez les modules admin :
   - /admin/users
   - /admin/posts
   - /admin/fiches
   - /admin/pages
   - /admin/videos
   - /admin/downloadables

---

## ⚙️ Personnalisation

### Modifier les données
Éditez directement les tableaux `$users`, `$posts`, etc. dans chaque seeder.

### Ajouter plus de contenu
Dupliquez les blocs existants dans les tableaux et modifiez les valeurs.

### Désactiver un seeder
Commentez la ligne correspondante dans `DatabaseSeeder.php` :
```php
// $this->call([
//     PostsTableSeeder::class, // ← Désactivé
// ]);
```

---

## 🐛 Troubleshooting

### Erreur "Class not found"
```bash
composer dump-autoload
```

### Erreur "Foreign key constraint"
Vérifiez l'ordre d'exécution dans `DatabaseSeeder.php`. Les dépendances doivent être créées en premier.

### Données en double
```bash
# Réinitialiser complètement
php artisan migrate:fresh --seed
```

### Images manquantes
Les chemins d'images sont fictifs. Remplacez par vos vrais fichiers :
```php
'image' => 'posts/mon-image.jpg'
```

---

## 📚 Structure Recommandée

```
database/seeders/
├── DatabaseSeeder.php (orchestrateur)
├── RolesTableSeeder.php
├── PermissionsTableSeeder.php
├── RolePermissionTableSeeder.php
├── UsersTableSeeder.php
├── CategoriesTableSeeder.php
├── TagsTableSeeder.php
├── FichesCategoriesSeeder.php
├── FichesSousCategoriesSeeder.php
├── PagesCategoriesSeeder.php
├── VideoCategoriesSeeder.php
├── DownloadCategoriesSeeder.php
├── PostsTableSeeder.php
├── TaggablesTableSeeder.php
├── FichesSeeder.php
├── PagesSeeder.php
├── VideosSeeder.php
└── DownloadablesSeeder.php
```

---

## 🎯 Prochaines Étapes

1. ✅ Tester tous les seeders en local
2. ⚙️ Personnaliser le contenu selon vos besoins
3. 🎨 Ajouter les vraies images
4. 🔐 Changer les mots de passe par défaut
5. 📦 Déployer en staging/production

---

## 📞 Support

Pour toute question sur les seeders :
- 📧 Email : votre-email@example.com
- 📚 Documentation Laravel : https://laravel.com/docs/seeding

---

**Généré pour Digital'SOS - Système de gestion sportive M2PC**  
*Version : 1.0 - Février 2026*
