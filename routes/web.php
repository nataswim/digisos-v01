<?php

use Illuminate\Support\Facades\Route;

// ========== CONTROLLERS PUBLICS ==========
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicFicheController;
use App\Http\Controllers\PublicVideoController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\PublicGalleryController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PostController;

// ========== CONTROLLERS PROFIL & STATS ==========
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatsController;

// ========== CONTROLLERS ADMIN ==========
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\DownloadCategoryController;
use App\Http\Controllers\DownloadableController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\FichesCategoryController;
use App\Http\Controllers\FichesSousCategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PagesCategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\VideoCategoryController;
use App\Http\Controllers\Admin\VideoLibraryController;
use App\Http\Controllers\Admin\PhotoGalleryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\ProfileItemController;

// ========== CONTROLLERS ÉDITEUR ==========
use App\Http\Controllers\Editor\EditorDashboardController;
use App\Http\Controllers\Editor\EditorPostController;
use App\Http\Controllers\Editor\EditorFicheController;
use App\Http\Controllers\Editor\EditorVideoController;
use App\Http\Controllers\Editor\EditorMediaController;
use App\Http\Controllers\Editor\EditorStatsController;
use App\Http\Controllers\Editor\EditorPageController;
use App\Http\Controllers\Editor\EditorPhotoGalleryController;

// ========== CONTROLLERS DASHBOARDS RÔLES ==========
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Visitor\VisitorDashboardController;
use App\Http\Controllers\User\UserProfileController as UserUserProfileController;

// =============================================================================
// ROUTES PUBLIQUES
// =============================================================================

// ========== RECHERCHE GLOBALE ==========
Route::get('/recherche', [SearchController::class, 'index'])->name('search');

// ========== SITEMAP XML ==========
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');

    if (!file_exists($path)) {
        abort(404, 'Sitemap non trouvé. Veuillez le générer depuis l\'administration.');
    }

    return response()->file($path, [
        'Content-Type'  => 'application/xml; charset=UTF-8',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('sitemap.xml');

// ========== PAGES STATIQUES ==========
Route::view('/', 'public.home')->name('home');
Route::view('/about', 'public.about')->name('about');
Route::view('/accessibilite', 'public.accessibility')->name('accessibility');
Route::get('/cookies',                   [PublicController::class, 'cookies'])->name('cookies');
Route::get('/fonctionnalites',           [PublicController::class, 'features'])->name('features');
Route::get('/mentions-legales',          [PublicController::class, 'legal'])->name('legal');
Route::get('/politique-confidentialite', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/plans-inscription',         [PublicController::class, 'pricing'])->name('pricing');
Route::get('/guide-utilisation',         [PublicController::class, 'guide'])->name('guide');
Route::get('/contact',                   [PublicController::class, 'contact'])->name('contact');
Route::post('/contact',                  [PublicController::class, 'contactSend'])->name('contact.send');

// ========== POSTS PUBLICS ==========
Route::get('/posts',                     [PostController::class, 'indexPublic'])->name('posts.public.index');
Route::get('/posts/tag/{tag}',           [PostController::class, 'byTag'])->name('posts.public.tag');
Route::get('/posts/category/{category}', [PostController::class, 'byCategory'])->name('posts.public.category');
Route::get('/posts/{post}',              [PostController::class, 'showPublic'])->name('posts.public.show');

// ========== VIDÉOS PUBLIQUES ==========
Route::prefix('videos')->name('public.videos.')->group(function () {
    Route::get('/',                    [PublicVideoController::class, 'index'])->name('index');
    Route::get('/category/{category}', [PublicVideoController::class, 'category'])->name('category');
    Route::get('/{video}',             [PublicVideoController::class, 'show'])->name('show');
});

// ========== PAGES PUBLIQUES ==========
Route::prefix('pages')->name('public.pages.')->group(function () {
    Route::get('/',                  [PublicPageController::class, 'index'])->name('index');
    Route::get('/{category}',        [PublicPageController::class, 'category'])->name('category');
    Route::get('/{category}/{page}', [PublicPageController::class, 'show'])->name('show');
});

// ========== EBOOKS PUBLICS ==========
Route::prefix('ebook')->name('ebook.')->group(function () {
    Route::get('/',                                      [EbookController::class, 'index'])->name('index');
    Route::get('/recherche',                             [EbookController::class, 'search'])->name('search');
    Route::get('/{category}',                            [EbookController::class, 'category'])->name('category');
    Route::get('/{category}/{downloadable}',             [EbookController::class, 'show'])->name('show');
    Route::get('/{category}/{downloadable}/telecharger', [EbookController::class, 'download'])->name('download');
});

// ========== FICHES PUBLIQUES ==========
Route::prefix('fiches')->name('public.fiches.')->group(function () {
    Route::get('/',                                  [PublicFicheController::class, 'index'])->name('index');
    Route::get('/{category}',                        [PublicFicheController::class, 'category'])->name('category');
    Route::get('/{category}/{sousCategory}',         [PublicFicheController::class, 'sousCategory'])->name('sous-category');
    Route::get('/{category}/{sousCategory}/{fiche}', [PublicFicheController::class, 'show'])->name('show');
});

// ========== GALERIES PUBLIQUES ==========
Route::prefix('galeries')->name('galleries.')->group(function () {
    Route::get('/',               [PublicGalleryController::class, 'index'])->name('index');
    Route::get('/{photoGallery}', [PublicGalleryController::class, 'show'])->name('show');
});

// =============================================================================
// ROUTES AUTHENTIFICATION
// =============================================================================

require __DIR__.'/auth.php';

// =============================================================================
// ESPACE VISITOR
// =============================================================================

Route::middleware(['auth', 'verified'])->prefix('visitor')->name('visitor.')->group(function () {
    Route::get('/dashboard', VisitorDashboardController::class)->name('dashboard');
});

// =============================================================================
// ESPACE USER
// =============================================================================

Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', UserDashboardController::class)->name('dashboard');
        // ========== FICHE PROFIL ENRICHI (lecture seule) ==========
    Route::get('/ma-fiche', [UserUserProfileController::class, 'show'])
         ->name('user-profile.show');
             // Profil utilisateur (édition des infos de base)
    Route::get('/profile/edit', [ProfileController::class, 'editUser'])->name('profile.edit');
    Route::patch('/profile',    [ProfileController::class, 'updateUserProfile'])->name('profile.update');

    // Fiche de profil enrichi (lecture seule)
    Route::get('/ma-fiche', [\App\Http\Controllers\User\UserProfileController::class, 'show'])
         ->name('user-profile.show');
});

// =============================================================================
// PROFIL UTILISATEUR
// =============================================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================================================================
// STATISTIQUES (User, Editor, Admin)
// =============================================================================

Route::middleware(['auth', 'verified'])->prefix('stats')->name('stats.')->group(function () {
    Route::get('/', [StatsController::class, 'index'])->name('index');
    Route::get('/api', [StatsController::class, 'api'])->name('api');
});

// =============================================================================
// ESPACE ADMIN
// =============================================================================

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // ========== DASHBOARD ==========
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

    // ========== POSTS ==========
    Route::post('posts/bulk-action', [PostController::class, 'bulkAction'])->name('posts.bulk-action');
    Route::resource('posts', PostController::class);

    // ========== CATÉGORIES ==========
    Route::post('categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
    Route::resource('categories', CategoryController::class);

    // ========== TAGS ==========
    Route::post('tags/bulk-action', [TagController::class, 'bulkAction'])->name('tags.bulk-action');
    Route::resource('tags', TagController::class);

    // ========== PAGES STATIQUES ==========
    Route::post('pages/bulk-action',            [PageController::class, 'bulkAction'])->name('pages.bulk-action');
    Route::post('pages/bulk-assign-categories', [PageController::class, 'bulkAssignCategories'])->name('pages.bulk-assign-categories');
    Route::resource('pages', PageController::class)->parameters(['pages' => 'page']);
    Route::resource('pages-categories', PagesCategoryController::class);

    // ========== BANNIÈRES ==========
    Route::resource('banners', BannerController::class);
    Route::prefix('banners/{banner}/slides')->name('banners.slides.')->group(function () {
        Route::post('/',          [BannerController::class, 'storeSlide'])->name('store');
        Route::put('/{slide}',    [BannerController::class, 'updateSlide'])->name('update');
        Route::delete('/{slide}', [BannerController::class, 'destroySlide'])->name('destroy');
        Route::post('/reorder',   [BannerController::class, 'reorderSlides'])->name('reorder');
    });

    // ========== UTILISATEURS ==========
    Route::post('users/bulk-action',         [UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::patch('users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
        // ========== FICHES UTILISATEURS ==========
    Route::resource('user-profiles', UserProfileController::class)
         ->parameters(['user-profiles' => 'user'])
         ->except(['index']);

    Route::get('user-profiles', [UserProfileController::class, 'index'])
         ->name('user-profiles.index');

    // Items de contenu (blocs titre + description) — gestion admin
    Route::prefix('user-profiles/{user}/items')->name('user-profiles.items.')->group(function () {
        Route::get('/create',        [ProfileItemController::class, 'create'])->name('create');
        Route::post('/',             [ProfileItemController::class, 'store'])->name('store');
        Route::get('/{item}/edit',   [ProfileItemController::class, 'edit'])->name('edit');
        Route::put('/{item}',        [ProfileItemController::class, 'update'])->name('update');
        Route::delete('/{item}',     [ProfileItemController::class, 'destroy'])->name('destroy');
    });

    // ========== MÉDIATHÈQUE ==========
    Route::get('media-api',            [MediaController::class, 'api'])->name('media.api');
    Route::get('media-categories-api', [MediaController::class, 'categoriesApi'])->name('media.categories.api');
    Route::post('media/bulk-action',   [MediaController::class, 'bulkAction'])->name('media.bulk-action');
    Route::get('media',                [MediaController::class, 'index'])->name('media.index');
    Route::post('media',               [MediaController::class, 'store'])->name('media.store');
    Route::get('media/{media}',        [MediaController::class, 'show'])->name('media.show');
    Route::put('media/{media}',        [MediaController::class, 'update'])->name('media.update');
    Route::delete('media/{media}',     [MediaController::class, 'destroy'])->name('media.destroy');
    Route::get('media-categories',               [MediaController::class, 'categories'])->name('media.categories');
    Route::post('media-categories',              [MediaController::class, 'storeCategory'])->name('media.categories.store');
    Route::delete('media-categories/{category}', [MediaController::class, 'destroyCategory'])->name('media.categories.destroy');

    // ========== BIBLIOTHÈQUE VIDÉO ==========
    Route::prefix('video-library')->name('video-library.')->group(function () {
        Route::post('browse',        [VideoLibraryController::class, 'browse'])->name('browse');
        Route::post('import',        [VideoLibraryController::class, 'import'])->name('import');
        Route::post('upload',        [VideoLibraryController::class, 'upload'])->name('upload');
        Route::post('create-folder', [VideoLibraryController::class, 'createFolder'])->name('create-folder');
        Route::delete('delete-file', [VideoLibraryController::class, 'deleteFile'])->name('delete-file');
    });

    // ========== VIDÉOS ==========
    Route::resource('video-categories', VideoCategoryController::class)
         ->parameters(['video-categories' => 'videoCategory']);
    Route::post('videos/fetch-metadata', [VideoController::class, 'fetchMetadata'])->name('videos.fetch-metadata');
    Route::resource('videos', VideoController::class);

    // ========== TÉLÉCHARGEMENTS ==========
    Route::resource('download-categories', DownloadCategoryController::class);
    Route::get('download-categories-stats', [DownloadCategoryController::class, 'stats'])->name('download-categories.stats');
    Route::post('downloadables/{downloadable}/duplicate', [DownloadableController::class, 'duplicate'])->name('downloadables.duplicate');
    Route::get('downloadables-stats',        [DownloadableController::class, 'stats'])->name('downloadables.stats');
    Route::post('downloadables/bulk-action', [DownloadableController::class, 'bulkAction'])->name('downloadables.bulk-action');
    Route::resource('downloadables', DownloadableController::class);

    // ========== FICHES ==========
    Route::post('fiches/bulk-action',            [FicheController::class, 'bulkAction'])->name('fiches.bulk-action');
    Route::post('fiches/bulk-assign-categories', [FicheController::class, 'bulkAssignCategories'])->name('fiches.bulk-assign-categories');
    Route::resource('fiches', FicheController::class)->parameters(['fiches' => 'fiche']);
    Route::resource('fiches-categories', FichesCategoryController::class);
    Route::get('fiches-sous-categories/api/by-category', [FichesSousCategoryController::class, 'apiByCategory'])
         ->name('fiches-sous-categories.api.by-category');
    Route::resource('fiches-sous-categories', FichesSousCategoryController::class);

    // ========== SITEMAP ==========
    Route::prefix('sitemap')->name('sitemap.')->group(function () {
        Route::get('/',                     [SitemapController::class, 'index'])->name('index');
        Route::post('/discover',            [SitemapController::class, 'discover'])->name('discover');
        Route::post('/generate',            [SitemapController::class, 'generate'])->name('generate');
        Route::post('/store',               [SitemapController::class, 'store'])->name('store');
        Route::patch('/{sitemapUrl}',       [SitemapController::class, 'update'])->name('update');
        Route::post('/bulk-approve',        [SitemapController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/{sitemapUrl}/toggle', [SitemapController::class, 'toggleApproval'])->name('toggle');
        Route::delete('/{sitemapUrl}',      [SitemapController::class, 'destroy'])->name('destroy');
        Route::post('/clean',               [SitemapController::class, 'clean'])->name('clean');
    });

    // ========== GALERIES PHOTO ==========
    Route::post('photo-galleries/bulk-action', [PhotoGalleryController::class, 'bulkAction'])
         ->name('photo-galleries.bulk-action');
    Route::post('photo-galleries/{photoGallery}/duplicate', [PhotoGalleryController::class, 'duplicate'])
         ->name('photo-galleries.duplicate');
    Route::resource('photo-galleries', PhotoGalleryController::class)
         ->parameters(['photo-galleries' => 'photoGallery']);
});

// =============================================================================
// ESPACE ÉDITEUR
// =============================================================================

Route::middleware(['auth', 'verified'])->prefix('editor')->name('editor.')->group(function () {

    // ========== DASHBOARD ==========
    Route::get('/dashboard', [EditorDashboardController::class, 'index'])->name('dashboard');

    // ========== API MÉDIAS ==========
    Route::get('media-api',            [MediaController::class, 'api'])->name('media.api');
    Route::get('media-categories-api', [MediaController::class, 'categoriesApi'])->name('media.categories.api');

    // ========== POSTS ==========
    Route::post('posts/{post}/submit-validation', [EditorPostController::class, 'submitForValidation'])
         ->name('posts.submit-validation');
    Route::resource('posts', EditorPostController::class);

    // ========== FICHES ==========
    Route::resource('fiches', EditorFicheController::class)
         ->parameters(['fiches' => 'fiche']);
    Route::get('fiches-sous-categories/api/by-category', [FichesSousCategoryController::class, 'apiByCategory'])
         ->name('fiches-sous-categories.api.by-category');

    // ========== VIDÉOS ==========
    Route::post('videos/fetch-metadata', [VideoController::class, 'fetchMetadata'])
         ->name('videos.fetch-metadata');
    Route::resource('videos', EditorVideoController::class)
         ->parameters(['videos' => 'video']);

    // ========== PAGES ==========
    Route::post('pages/bulk-assign-categories', [EditorPageController::class, 'bulkAssignCategories'])
         ->name('pages.bulk-assign-categories');
    Route::resource('pages', EditorPageController::class)
         ->parameters(['pages' => 'page']);

    // ========== GALERIES PHOTOS ==========
    Route::post('photo-galleries/{photoGallery}/duplicate', [EditorPhotoGalleryController::class, 'duplicate'])
         ->name('photo-galleries.duplicate');
    Route::post('photo-galleries/bulk-action', [EditorPhotoGalleryController::class, 'bulkAction'])
         ->name('photo-galleries.bulk-action');
    Route::resource('photo-galleries', EditorPhotoGalleryController::class)
         ->parameters(['photo-galleries' => 'photoGallery']);

    // ========== MÉDIAS ==========
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/',           [EditorMediaController::class, 'index'])->name('index');
        Route::post('/',          [EditorMediaController::class, 'store'])->name('store');
        Route::get('/{media}',    [EditorMediaController::class, 'show'])->name('show');
        Route::put('/{media}',    [EditorMediaController::class, 'update'])->name('update');
        Route::delete('/{media}', [EditorMediaController::class, 'destroy'])->name('destroy');
    });

    // ========== STATISTIQUES ÉDITEUR ==========
    Route::get('/stats', [EditorStatsController::class, 'index'])->name('stats');
});