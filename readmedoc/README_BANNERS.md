# 🎨 Système de Bannières / Carrousel

> Système complet de gestion de bannières carrousel Bootstrap 5 avec éditeur drag-and-drop, intégration médiathèque, et cache automatique.

```blade
<section class="testimonials">
    @include('components.banner', ['slug' => 'temoignages'])
</section>
```


---

## 📋 Table des matières

- [Vue d'ensemble](#-vue-densemble)
- [Installation](#-installation)
- [Utilisation Admin](#-utilisation-admin)
- [Intégration Public](#-intégration-public)
- [Structure des fichiers](#-structure-des-fichiers)
- [API / Méthodes](#-api--méthodes)
- [Exemples](#-exemples)
- [Troubleshooting](#-troubleshooting)

---

## 🎯 Vue d'ensemble

Le système de bannières permet de créer des carrousels Bootstrap 5 entièrement personnalisables depuis l'interface d'administration. Chaque bannière possède :

- **Un slug unique** pour l'intégration dans les vues Blade
- **Des slides illimités** avec texte, images, boutons CTA
- **Drag & drop** pour réordonner les slides
- **Intégration médiathèque** pour sélectionner les images
- **Cache automatique** (10 minutes) avec invalidation sur modification
- **Paramètres avancés** : hauteur, transition, autoplay, indicateurs, contrôles

### Fonctionnalités principales

✅ Création/édition de bannières multi-slides  
✅ Gestion drag-and-drop de l'ordre des slides  
✅ Sélection d'images depuis la médiathèque  
✅ Personnalisation texte : titre, sous-titre, corps, CTA  
✅ Overlay configurable (opacité 0-100%)  
✅ Position texte (gauche/centre/droite)  
✅ Boutons CTA avec styles Bootstrap  
✅ Activation/désactivation par slide  
✅ Cache Redis/File avec invalidation auto  
✅ Responsive & accessible (Bootstrap 5)

---

## 📦 Installation

### Étape 1 : Migrations

```bash
# Copier les migrations
cp 2026_02_10_000001_create_banners_table.php database/migrations/
cp 2026_02_10_000002_create_banner_slides_table.php database/migrations/

# Exécuter les migrations
php artisan migrate
```

### Étape 2 : Models

```bash
cp Banner.php app/Models/Banner.php
cp BannerSlide.php app/Models/BannerSlide.php
```

### Étape 3 : Controller

```bash
cp BannerController.php app/Http/Controllers/BannerController.php
```

### Étape 4 : Vues Admin

```bash
# Créer les dossiers
mkdir -p resources/views/admin/banners/partials

# Copier les vues
cp banners_index.blade.php      resources/views/admin/banners/index.blade.php
cp banners_create.blade.php     resources/views/admin/banners/create.blade.php
cp banners_edit.blade.php       resources/views/admin/banners/edit.blade.php
cp banners_form.blade.php       resources/views/admin/banners/partials/form.blade.php
cp banners_slide_form.blade.php resources/views/admin/banners/partials/slide-form.blade.php
```

### Étape 5 : Composant Public

```bash
cp banner.blade.php resources/views/components/banner.blade.php
```

### Étape 6 : Routes

Ajouter dans `routes/web.php` **à l'intérieur du groupe `admin.*`** :

```php
// ========== GESTION BANNIÈRES ==========
Route::resource('banners', BannerController::class);

Route::prefix('banners/{banner}/slides')->name('banners.slides.')->group(function () {
    Route::post('/',          [BannerController::class, 'storeSlide'])->name('store');
    Route::put('/{slide}',    [BannerController::class, 'updateSlide'])->name('update');
    Route::delete('/{slide}', [BannerController::class, 'destroySlide'])->name('destroy');
    Route::post('/reorder',   [BannerController::class, 'reorderSlides'])->name('reorder');
});
```

**Import nécessaire** (en haut du fichier) :

```php
use App\Http\Controllers\BannerController;
```

### Étape 7 : Navigation Admin

Ajouter dans `resources/views/layouts/partials/admin-nav-horizontal.blade.php` :

```blade
{{-- Dans le dropdown "Contenu" --}}
<li>
    <a class="dropdown-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" 
       href="{{ route('admin.banners.index') }}">
        <i class="fas fa-layer-group fa-fw me-2"></i>Bannières
        @php $bannersCount = App\Models\Banner::where('is_active', true)->count(); @endphp
        @if($bannersCount > 0)
            <span class="badge bg-primary ms-2">{{ $bannersCount }}</span>
        @endif
    </a>
</li>
```

### Étape 8 : Vider les caches

```bash
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🛠️ Utilisation Admin

### Accès

`http://votre-site.com/admin/banners`

### Créer une bannière

1. **Cliquer** sur "Nouvelle bannière"
2. **Renseigner** :
   - Nom (ex: "Bannière Accueil")
   - Slug (auto-généré, ex: `banniere-accueil`)
   - Description (optionnelle)
   - Hauteur (400-1200px)
   - Transition : `slide` ou `fade`
   - Autoplay (délai 1-30s)
   - Indicateurs / Contrôles / Pause au survol
3. **Enregistrer**

### Ajouter des slides

1. **Aller** sur l'onglet "Slides"
2. **Remplir** le formulaire :
   - **Image** : Cliquer "Médiathèque" ou saisir une URL
   - **Alt image** : Description pour l'accessibilité
   - **Titre** : Titre principal du slide
   - **Sous-titre** : Accroche (optionnel)
   - **Corps** : Texte descriptif (optionnel)
   - **CTA** : Label, URL, cible, style du bouton
   - **Couleur texte** : Sélecteur de couleur
   - **Position texte** : Gauche / Centre / Droite
   - **Overlay** : Opacité 0-100% (fond noir semi-transparent)
   - **Actif** : Cocher pour publier le slide
3. **Ajouter** le slide

### Réordonner les slides

- **Glisser-déposer** via la poignée <i class="fas fa-grip-vertical"></i>
- L'ordre est sauvegardé automatiquement en AJAX

### Modifier un slide

- **Cliquer** sur <i class="fas fa-edit"></i> pour ouvrir le formulaire d'édition inline
- **Modifier** les champs
- **Enregistrer**

### Supprimer un slide

- **Cliquer** sur <i class="fas fa-trash"></i>
- **Confirmer** la suppression

---

## 🌐 Intégration Public

### Syntaxe

Intégrer une bannière dans n'importe quelle vue Blade :

```blade
@include('components.banner', ['slug' => 'banniere-accueil'])
```

### Exemples d'intégration

**Dans `resources/views/public/home.blade.php` :**

```blade
@extends('layouts.public')

@section('content')
    {{-- Bannière principale --}}
    @include('components.banner', ['slug' => 'banniere-accueil'])

    {{-- Contenu de la page --}}
    <div class="container">
        <h1>Bienvenue</h1>
        <!-- ... -->
    </div>
@endsection
```

**Dans `resources/views/public/about.blade.php` :**

```blade
@extends('layouts.public')

@section('content')
    @include('components.banner', ['slug' => 'banniere-a-propos'])
    
    <div class="container">
        <h2>À propos de nous</h2>
        <!-- ... -->
    </div>
@endsection
```

### Code d'intégration visible dans l'admin

Le code exact est affiché en haut de la page d'édition de chaque bannière :

```
📋 Intégration : @include('components.banner', ['slug' => 'votre-slug'])
```

---

## 📁 Structure des fichiers

```
app/
├── Http/Controllers/
│   └── BannerController.php       # CRUD + gestion slides + réordonnancement
├── Models/
    ├── Banner.php                 # Model bannière + cache + relations
    └── BannerSlide.php            # Model slide + accesseurs + scopes

database/migrations/
├── 2026_02_10_000001_create_banners_table.php
└── 2026_02_10_000002_create_banner_slides_table.php

resources/views/
├── admin/banners/
│   ├── index.blade.php            # Liste des bannières
│   ├── create.blade.php           # Création bannière
│   ├── edit.blade.php             # Édition + onglets Paramètres/Slides
│   └── partials/
│       ├── form.blade.php         # Formulaire bannière
│       └── slide-form.blade.php   # Formulaire ajout slide
└── components/
    └── banner.blade.php           # Composant public (rendu carousel)

routes/
└── web.php                        # Routes admin.banners.*
```

---

## 🔧 API / Méthodes

### Model `Banner`

```php
// Trouver une bannière par slug (avec cache 10min)
$banner = Banner::findBySlug('banniere-accueil');

// Relations
$banner->slides;          // Tous les slides (triés par sort_order)
$banner->activeSlides;    // Slides actifs uniquement

// Cache
$banner->clearCache();    // Vider le cache (appelé auto sur save/delete)
```

### Model `BannerSlide`

```php
// Accesseurs
$slide->image_url;              // URL complète (fallback si vide)
$slide->overlay_css_opacity;    // 0.0-1.0 (calculé depuis 0-100)
$slide->text_align_class;       // 'text-start', 'text-center', 'text-end'
$slide->has_cta;                // true si CTA configuré

// Relation
$slide->banner;
```

### Controller Routes

| Méthode | Route | Action |
|---------|-------|--------|
| GET | `/admin/banners` | Liste |
| GET | `/admin/banners/create` | Formulaire création |
| POST | `/admin/banners` | Enregistrer |
| GET | `/admin/banners/{id}/edit` | Édition |
| PUT | `/admin/banners/{id}` | Mise à jour |
| DELETE | `/admin/banners/{id}` | Suppression |
| POST | `/admin/banners/{id}/slides` | Ajouter slide |
| PUT | `/admin/banners/{id}/slides/{slideId}` | Modifier slide |
| DELETE | `/admin/banners/{id}/slides/{slideId}` | Supprimer slide |
| POST | `/admin/banners/{id}/slides/reorder` | Réordonner (AJAX) |

---

## 💡 Exemples

### Exemple 1 : Bannière simple (1 slide)

**Admin :**
- Nom : "Hero Homepage"
- Slug : `hero-homepage`
- Hauteur : 600px
- 1 slide avec image, titre, CTA

**Intégration :**
```blade
@include('components.banner', ['slug' => 'hero-homepage'])
```

### Exemple 2 : Carrousel produits (3 slides)

**Admin :**
- Nom : "Carrousel Produits"
- Slug : `carrousel-produits`
- Autoplay : 5s
- Transition : slide
- 3 slides (produit A, B, C) avec réordonnancement drag-drop

**Intégration :**
```blade
@include('components.banner', ['slug' => 'carrousel-produits'])
```

### Exemple 3 : Bannière témoignages

**Admin :**
- Nom : "Témoignages Clients"
- Slug : `temoignages`
- Transition : fade
- Overlay : 60%
- Position texte : centre
- 5 slides témoignages

**Intégration :**
```blade
<section class="testimonials">
    @include('components.banner', ['slug' => 'temoignages'])
</section>
```

---

## 🐛 Troubleshooting

### ❌ Erreur "View [admin.banners.index] not found"

**Cause :** Fichiers Blade non copiés au bon endroit.

**Solution :**
```bash
ls resources/views/admin/banners/
# Doit afficher : index.blade.php  create.blade.php  edit.blade.php  partials/

php artisan view:clear
```

---

### ❌ Erreur "selector.open is not a function"

**Cause :** Mauvaise méthode appelée pour le sélecteur de médias.

**Solution :** Vérifier que les vues utilisent :
```js
openMediaSelector('slide_image_X', 'slideImagePreview_X')
```

Et **PAS** :
```js
selector.open()  // ❌ INCORRECT
```

Fichier corrigé : `banners_edit.blade.php` + `banners_slide_form.blade.php`

---

### ❌ Images de la médiathèque ne s'affichent pas

**Cause :** Lien symbolique `storage` manquant.

**Solution :**
```bash
php artisan storage:link
```

Vérifier que `/storage` pointe vers `/storage/app/public`.

---

### ❌ Le drag-and-drop ne fonctionne pas

**Cause :** SortableJS non chargé.

**Solution :** Vérifier que `edit.blade.php` contient :
```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
```

---

### ❌ Bannière non affichée sur la page publique

**Cause 1 :** Slug incorrect.

**Solution :** Vérifier le slug exact dans l'admin (`http://127.0.0.1:8000/admin/banners`).

**Cause 2 :** Bannière inactive ou sans slides actifs.

**Solution :** Activer la bannière et au moins 1 slide.

**Cause 3 :** Cache non vidé.

**Solution :**
```bash
php artisan cache:clear
```

---

### ❌ Réordonnancement AJAX échoue

**Cause :** Token CSRF manquant.

**Solution :** Vérifier que `<meta name="csrf-token">` est présent dans `<head>` du layout admin :
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

### ❌ Les slides ne s'affichent pas dans le bon ordre

**Cause :** Cache non invalidé.

**Solution :**
```bash
# Manuellement
php artisan cache:clear

# Automatiquement (déjà configuré)
# Le cache est vidé à chaque save/delete via Banner::clearCache()
```

---

## 📊 Base de données

### Table `banners`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | bigint | ID auto-incrémenté |
| `name` | varchar(191) | Nom de la bannière |
| `slug` | varchar(191) | Slug unique |
| `description` | text | Description (optionnel) |
| `height` | int | Hauteur en px (400-1200) |
| `transition` | enum | `slide` ou `fade` |
| `autoplay` | boolean | Activer autoplay |
| `autoplay_delay` | int | Délai autoplay (ms) |
| `show_indicators` | boolean | Afficher indicateurs |
| `show_controls` | boolean | Afficher contrôles |
| `pause_on_hover` | boolean | Pause au survol |
| `is_active` | boolean | Bannière active |
| `created_by` | bigint | ID utilisateur créateur |
| `updated_by` | bigint | ID utilisateur modificateur |
| `deleted_by` | bigint | ID utilisateur suppresseur |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |
| `deleted_at` | timestamp | Soft delete |

### Table `banner_slides`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | bigint | ID auto-incrémenté |
| `banner_id` | bigint | FK → banners |
| `image` | varchar(500) | URL image |
| `image_alt` | varchar(255) | Texte alternatif |
| `title` | varchar(255) | Titre |
| `subtitle` | varchar(255) | Sous-titre |
| `body` | text | Corps de texte |
| `cta_label` | varchar(100) | Label bouton |
| `cta_url` | varchar(500) | URL bouton |
| `cta_target` | varchar(20) | `_self` ou `_blank` |
| `cta_style` | varchar(50) | Classe Bootstrap |
| `text_color` | varchar(20) | Couleur hex |
| `text_position` | varchar(20) | `left`, `center`, `right` |
| `overlay_opacity` | int | 0-100 |
| `sort_order` | int | Ordre d'affichage |
| `is_active` | boolean | Slide actif |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

## 🚀 Performances

### Cache

- **Durée** : 10 minutes (configurable dans `Banner::findBySlug()`)
- **Clé** : `banner:{slug}`
- **Invalidation** : Automatique sur `save()` / `delete()` via event `saved` et `deleted`

### Optimisations

- **Eager loading** : `Banner::with('slides')` pour éviter le problème N+1
- **Scopes** : `Banner::active()`, `BannerSlide::active()`
- **Indexes** : `slug` (unique), `is_active`, `banner_id`, `sort_order`

---

## 📝 Changelog

### v1.0.0 (2026-02-11)

- ✅ Création système complet
- ✅ CRUD bannières + slides
- ✅ Drag-and-drop réordonnancement
- ✅ Intégration médiathèque
- ✅ Cache automatique
- ✅ Composant public Bootstrap 5
- ✅ Documentation complète

---

## 📞 Support

En cas de problème, vérifier dans l'ordre :

1. **Migrations** exécutées (`php artisan migrate:status`)
2. **Routes** chargées (`php artisan route:list | grep banner`)
3. **Vues** présentes (`ls resources/views/admin/banners/`)
4. **Cache** vidé (`php artisan cache:clear && php artisan view:clear`)
5. **Console navigateur** (erreurs JS)
6. **Logs Laravel** (`storage/logs/laravel.log`)

---

**Fait avec ❤️ pour Digital'SOS**
