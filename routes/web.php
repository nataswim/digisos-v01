<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController as AdminProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\DownloadCategoryController;
use App\Http\Controllers\DownloadableController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\FichesCategoryController;
use App\Http\Controllers\PublicFicheController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\EbookFileController;
use App\Http\Controllers\PublicVideoController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\VideoCategoryController;
use App\Http\Controllers\FichesSousCategoryController;
use App\Http\Controllers\Admin\VideoLibraryController;
use App\Http\Controllers\SearchController;

// ========== RECHERCHE GLOBALE ==========
Route::get('/recherche', [SearchController::class, 'index'])->name('search');

// ========== ROUTE PUBLIQUE SITEMAP XML ==========
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    
    if (!file_exists($path)) {
        abort(404, 'Sitemap non trouvé. Veuillez le générer depuis l\'administration.');
    }
    
    return response()
        ->file($path, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600'
        ]);
})->name('sitemap.xml');

// ========== ROUTES VIDÉOS PUBLIQUES ==========
Route::prefix('videos')->name('public.videos.')->group(function () {
    Route::get('/', [PublicVideoController::class, 'index'])->name('index');
    Route::get('/category/{category}', [PublicVideoController::class, 'category'])->name('category');
    Route::get('/{video}', [PublicVideoController::class, 'show'])->name('show');
});

// ========== ROUTES PUBLIQUES ==========
Route::view('/', 'public.home')->name('home');
Route::view('/about', 'public.about')->name('about');
Route::view('/accessibilite', 'public.accessibility')->name('accessibility');
Route::get('/cookies', [PublicController::class, 'cookies'])->name('cookies');
Route::get('/fonctionnalites', [PublicController::class, 'features'])->name('features');
Route::get('/mentions-legales', [PublicController::class, 'legal'])->name('legal');
Route::get('/politique-confidentialite', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/plans-inscription', [PublicController::class, 'pricing'])->name('pricing');
Route::get('/guide-utilisation', [PublicController::class, 'guide'])->name('guide');

Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactSend'])->name('contact.send');

// ========== ROUTES PUBLIQUES EBOOKS ==========
Route::prefix('ebook')->name('ebook.')->group(function () {
    Route::get('/', [EbookController::class, 'index'])->name('index');
    Route::get('/recherche', [EbookController::class, 'search'])->name('search');
    Route::get('/{category}', [EbookController::class, 'category'])->name('category');
    Route::get('/{category}/{downloadable}', [EbookController::class, 'show'])->name('show');
    Route::get('/{category}/{downloadable}/telecharger', [EbookController::class, 'download'])->name('download');
});

// ========== ROUTES FICHES PUBLIQUES ==========
Route::prefix('fiches')->name('public.fiches.')->group(function () {
    // Index - Liste des catégories
    Route::get('/', [PublicFicheController::class, 'index'])->name('index');
    
    // Catégorie - Liste des fiches d'une catégorie
    Route::get('/{category}', [PublicFicheController::class, 'category'])->name('category');
    
    // Sous-catégorie - Liste des fiches d'une sous-catégorie
    Route::get('/{category}/{sousCategory}', [PublicFicheController::class, 'sousCategory'])->name('sous-category');
    
    // Fiche individuelle
    Route::get('/{category}/{sousCategory}/{fiche}', [PublicFicheController::class, 'show'])->name('show');
});

// ========== ROUTES POSTS PUBLIQUES ==========
Route::get('/posts', [PostController::class, 'indexPublic'])->name('posts.public.index');
Route::get('/posts/tag/{tag}', [PostController::class, 'byTag'])->name('posts.public.tag');
Route::get('/posts/category/{category}', [PostController::class, 'byCategory'])->name('posts.public.category');
Route::get('/posts/{post}', [PostController::class, 'showPublic'])->name('posts.public.show');

// ========== ROUTES AUTHENTIFICATION ==========
require __DIR__.'/auth.php';

// ========== DASHBOARD ==========
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ========== ROUTES PROFIL UTILISATEUR ==========
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========== ROUTES ADMIN ==========
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // ========== GESTION CMS ==========
    
    // Posts
    Route::resource('posts', PostController::class);
    Route::post('posts/bulk-action', [PostController::class, 'bulkAction'])->name('posts.bulk-action');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    Route::post('categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
    
    // Tags
    Route::resource('tags', TagController::class);
    Route::post('tags/bulk-action', [TagController::class, 'bulkAction'])->name('tags.bulk-action');

    // ========== GESTION UTILISATEURS ==========
    
    // Users
    Route::resource('users', UserController::class);
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    
    // Roles
    Route::resource('roles', RoleController::class);
    
    // Permissions
    Route::resource('permissions', PermissionController::class);

    // ========== GESTION MÉDIATHÈQUE ==========
    
    // Media
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');
    Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    
    // API pour sélection des médias (utilisée dans les modals)
    Route::get('media-api', [MediaController::class, 'api'])->name('media.api');
    Route::get('media-categories-api', [MediaController::class, 'categoriesApi'])->name('media.categories.api');
    
    // Gestion des catégories de médias
    Route::get('media-categories', [MediaController::class, 'categories'])->name('media.categories');
    Route::post('media-categories', [MediaController::class, 'storeCategory'])->name('media.categories.store');
    Route::delete('media-categories/{category}', [MediaController::class, 'destroyCategory'])->name('media.categories.destroy');
    
    Route::post('media/bulk-action', [MediaController::class, 'bulkAction'])->name('media.bulk-action');

    // ========== BIBLIOTHÈQUE VIDÉO ==========
    
    Route::prefix('video-library')->name('video-library.')->group(function () {
        Route::post('browse', [VideoLibraryController::class, 'browse'])->name('browse');
        Route::post('import', [VideoLibraryController::class, 'import'])->name('import');
        Route::post('upload', [VideoLibraryController::class, 'upload'])->name('upload');
        Route::post('create-folder', [VideoLibraryController::class, 'createFolder'])->name('create-folder');
        Route::delete('delete-file', [VideoLibraryController::class, 'deleteFile'])->name('delete-file');
    });

    // ========== GESTION VIDÉOS ==========
    
    Route::resource('video-categories', VideoCategoryController::class)
        ->parameters(['video-categories' => 'videoCategory']);
    
    Route::resource('videos', VideoController::class);
    
    Route::post('videos/fetch-metadata', [VideoController::class, 'fetchMetadata'])
        ->name('videos.fetch-metadata');

    // ========== GESTION TÉLÉCHARGEMENTS ==========
    
    // Catégories de téléchargement
    Route::resource('download-categories', DownloadCategoryController::class);
    Route::get('download-categories-stats', [DownloadCategoryController::class, 'stats'])->name('download-categories.stats');
    
    // Téléchargements
    Route::resource('downloadables', DownloadableController::class);
    Route::post('downloadables/{downloadable}/duplicate', [DownloadableController::class, 'duplicate'])->name('downloadables.duplicate');
    Route::get('downloadables-stats', [DownloadableController::class, 'stats'])->name('downloadables.stats');
    Route::post('downloadables/bulk-action', [DownloadableController::class, 'bulkAction'])->name('downloadables.bulk-action');

    // ========== GESTION FICHES DOCUMENTATION ==========
    
    Route::resource('fiches', FicheController::class);
    Route::post('fiches/bulk-action', [FicheController::class, 'bulkAction'])->name('fiches.bulk-action');
    Route::post('fiches/bulk-assign-categories', [FicheController::class, 'bulkAssignCategories'])->name('fiches.bulk-assign-categories');
    
    Route::resource('fiches-categories', FichesCategoryController::class);
    
    Route::resource('fiches-sous-categories', FichesSousCategoryController::class);
// API pour récupérer les sous-catégories par catégorie
Route::get('fiches-sous-categories/api/by-category', [FichesSousCategoryController::class, 'apiByCategory'])
    ->name('fiches-sous-categories.api.by-category');
    
    // ========== GESTION SITEMAP ==========
    
    Route::prefix('sitemap')->name('sitemap.')->group(function () {
        Route::get('/', [SitemapController::class, 'index'])->name('index');
        Route::post('/discover', [SitemapController::class, 'discover'])->name('discover');
        Route::post('/generate', [SitemapController::class, 'generate'])->name('generate');
        Route::post('/store', [SitemapController::class, 'store'])->name('store');
        Route::patch('/{sitemapUrl}', [SitemapController::class, 'update'])->name('update');
        Route::post('/bulk-approve', [SitemapController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/{sitemapUrl}/toggle', [SitemapController::class, 'toggleApproval'])->name('toggle');
        Route::delete('/{sitemapUrl}', [SitemapController::class, 'destroy'])->name('destroy');
        Route::post('/clean', [SitemapController::class, 'clean'])->name('clean');
    });

});