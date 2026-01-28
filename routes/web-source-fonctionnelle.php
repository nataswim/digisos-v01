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
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\DownloadCategoryController;
use App\Http\Controllers\DownloadableController;
use App\Http\Controllers\ExercicePublicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\FichesCategoryController;
use App\Http\Controllers\PublicFicheController;
use App\Http\Controllers\PublicWorkoutController;
use App\Http\Controllers\WorkoutCategoryController;
use App\Http\Controllers\WorkoutSectionController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\User\NotebookController;
use App\Http\Controllers\User\TrainingController;
use App\Http\Controllers\AITextController;
use App\Http\Controllers\Services\AITextService;
use App\Http\Controllers\User\NotebookItemController;
use App\Http\Controllers\User\NotebookNoteController;
use App\Http\Controllers\User\NotebookExportController;
use App\Http\Controllers\User\NotebookFavoriteController;
use App\Http\Controllers\User\NotebookReorderController;
use App\Http\Controllers\User\NotebookContentController;
use App\Http\Controllers\User\NotebookBulkActionController;
use App\Http\Controllers\User\NotebookApiController;
use App\Http\Controllers\User\NotebookCategoryController;
use App\Http\Controllers\User\NotebookTypeController;
use App\Http\Controllers\User\NotebookShareController;
use App\Http\Controllers\User\NotebookPermissionController;
use App\Http\Controllers\User\NotebookCollaborationController;
use App\Http\Controllers\User\NotebookVersionController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\EbookFileController;
use App\Http\Controllers\PublicVideoController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\FichesSousCategoryController;
use App\Http\Controllers\Admin\ExerciceCategoryController;
use App\Http\Controllers\Admin\ExerciceSousCategoryController;
use App\Http\Controllers\AnatomyController;
use App\Http\Controllers\User\CalendarController;
use App\Http\Controllers\Admin\VideoLibraryController;
use App\Http\Controllers\Admin\CatalogueModuleController;
use App\Http\Controllers\Admin\CatalogueSectionController;
use App\Http\Controllers\Admin\CatalogueUnitController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\SearchController;




// Page principale avec la carte
Route::get('/equipements', [EquipementController::class, 'index'])->name('equipements.index');

// Routes API pour AJAX
Route::prefix('api/equipements')->group(function () {
    Route::get('/search', [EquipementController::class, 'search'])->name('api.equipements.search');
    Route::get('/stats', [EquipementController::class, 'stats'])->name('api.equipements.stats');
    Route::get('/{id}', [EquipementController::class, 'show'])->name('api.equipements.show');
});

// Recherche globale
Route::get('/recherche', [SearchController::class, 'index'])->name('search');

// ========== ROUTES WORKOUTS PUBLIQUES ==========
Route::prefix('workouts')->name('public.workouts.')->group(function () {
    Route::get('/', [PublicWorkoutController::class, 'index'])->name('index');
    Route::get('/{section}', [PublicWorkoutController::class, 'section'])->name('section');
    Route::get('/{section}/{category}', [PublicWorkoutController::class, 'category'])->name('category');
    Route::get('/{section}/{category}/{workout}', [PublicWorkoutController::class, 'show'])->name('show');
});


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


// Routes publiques
Route::view('/', 'public.home')->name('home');
Route::view('/about', 'public.about')->name('about');
Route::view('/accessibilite', 'public.accessibility')->name('accessibility');
Route::get('/cookies', [PublicController::class, 'cookies'])->name('cookies');
Route::get('/fonctionnalites', [PublicController::class, 'features'])->name('features');
Route::get('/mentions-legales', [PublicController::class, 'legal'])->name('legal');
Route::get('/politique-confidentialite', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/plans-inscription', [PublicController::class, 'pricing'])->name('pricing');
Route::get('/guide-utilisation', [PublicController::class, 'guide'])->name('guide');
Route::get('/guide-planification', [PublicController::class, 'guideplanif'])->name('guideplanif');
Route::get('/guide-carnet', [PublicController::class, 'guidecarnet'])->name('guidecarnet');


Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactSend'])->name('contact.send');

// Routes publiques pour les eBooks (avant les routes auth)
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
    Route::get('/{category}/sous-categorie/{sousCategory}', [PublicFicheController::class, 'sousCategory'])
        ->name('sous-category');
    
    // Fiche - Affichage d'une fiche
    Route::get('/{category}/{fiche}', [PublicFicheController::class, 'show'])->name('show');
});

// Carte anatomique
Route::get('/anatomie', [App\Http\Controllers\AnatomyController::class, 'index'])->name('anatomy.index');

// Routes publiques pour les exercices
Route::prefix('exercices')->name('exercices.')->group(function () {
    Route::get('/', [ExercicePublicController::class, 'index'])->name('index');
    Route::get('/recherche', [ExercicePublicController::class, 'search'])->name('search');
    
    // Route catégorie
    Route::get('/categorie/{category}', [ExercicePublicController::class, 'category'])->name('category');
    
    // Route sous-catégorie
    Route::get('/categorie/{category}/sous-categorie/{sousCategory}', [ExercicePublicController::class, 'sousCategory'])
        ->name('sous-category');
    
    // Route show (doit être en dernier pour éviter les conflits)
    Route::get('/{exercice}', [ExercicePublicController::class, 'show'])->name('show');
});


// Routes publiques pour les plans d'entraînement 
Route::prefix('plans')->name('plans.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PlanPublicController::class, 'index'])->name('index');
    Route::get('/{plan}', [\App\Http\Controllers\PlanPublicController::class, 'show'])->name('show');
});

// ========== ROUTES FICHES PUBLIQUES ==========
Route::prefix('fiches')->name('public.fiches.')->group(function () {
    Route::get('/', [PublicFicheController::class, 'index'])->name('index');
    Route::get('/{category}', [PublicFicheController::class, 'category'])->name('category');
    Route::get('/{category}/{fiche}', [PublicFicheController::class, 'show'])->name('show');
});


// ========== ROUTES CATALOGUE PUBLIC ==========
Route::prefix('catalogue')->name('public.catalogue.')->group(function () {
    // Index - Liste des sections
    Route::get('/', [\App\Http\Controllers\CatalogueController::class, 'index'])->name('index');
    
    // Section - Liste des modules
    Route::get('/{section}', [\App\Http\Controllers\CatalogueController::class, 'section'])->name('section');
    
    // Module - Liste des unités
    Route::get('/{section}/{module}', [\App\Http\Controllers\CatalogueController::class, 'module'])->name('module');
    
    // Unité - Affichage du contenu
    Route::get('/{section}/{module}/{unit}', [\App\Http\Controllers\CatalogueController::class, 'unit'])->name('unit');
});







// Routes outils
Route::get('/outils/calculateur-imc', [ToolController::class, 'bmiCalculator'])->name('tools.bmi');
Route::get('/outils/calculateur-masse-grasse', [ToolController::class, 'bodyFatCalculator'])->name('tools.masse-grasse');
Route::get('/outils/calculateur-calories', [ToolController::class, 'calorieCalculator'])->name('tools.calories');
Route::get('/outils/chronometre-natation', [ToolController::class, 'swimmingChronometer'])->name('tools.chronometre');
Route::get('/outils/chronometre-pro', [ToolController::class, 'chronometerPro'])->name('tools.chronometre-pro');
Route::get('/outils/calculateur-vnc', [ToolController::class, 'criticalSwimSpeed'])->name('tools.vnc');
Route::get('/outils/calculateur-fitness', [ToolController::class, 'fitnessCalculator'])->name('tools.fitness');
Route::get('/outils/coherence-cardiaque', [ToolController::class, 'heartCoherence'])->name('tools.coherence-cardiaque');
Route::get('/outils/calculateur-hydratation', [ToolController::class, 'hydrationCalculator'])->name('tools.hydratation');
Route::get('/outils/carte-interactive', [ToolController::class, 'interactiveMap'])->name('tools.carte-interactive');
Route::get('/outils/convertisseur-kcal-macros', [ToolController::class, 'kcalMacroConverter'])->name('tools.kcal-macros');
Route::get('/outils/calculateur-rm-charge-maximale', [ToolController::class, 'onermCalculator'])->name('tools.onermcalculator');
Route::get('/outils/planificateur-course-a-pieds', [ToolController::class, 'runningPlanner'])->name('tools.running-planner');
Route::get('/outils/predicteur-natation', [ToolController::class, 'swimmingPredictor'])->name('tools.swimming-predictor');
Route::get('/outils/planificateur-natation', [ToolController::class, 'swimmingPlanner'])->name('tools.swimming-planner');
Route::get('/outils/zones-cardiaques', [ToolController::class, 'heartRateZones'])->name('tools.heart-rate-zones');
Route::get('/outils/calculateur-tdee', [ToolController::class, 'tdeeCalculator'])->name('tools.tdee-calculator');
Route::get('/outils/planificateur-triathlon', [ToolController::class, 'triathlonPlanner'])->name('tools.triathlon-planner');
Route::get('/outils/efficacite-technique-natation', [ToolController::class, 'swimmingEfficiency'])->name('tools.swimming-efficiency');

// Routes outils par categories
Route::get('/outils', [ToolController::class, 'index'])->name('tools.index');
Route::get('/outils/categorie/sante-composition-corporelle', [ToolController::class, 'healthBodyComposition'])->name('tools.category.health');
Route::get('/outils/categorie/nutrition-energie', [ToolController::class, 'nutritionEnergy'])->name('tools.category.nutrition');
Route::get('/outils/categorie/performance-cardiaque', [ToolController::class, 'cardiacPerformance'])->name('tools.category.cardiac');
Route::get('/outils/categorie/sports-aquatiques-natation', [ToolController::class, 'aquaticSports'])->name('tools.category.swimming');
Route::get('/outils/categorie/course-endurance', [ToolController::class, 'runningEndurance'])->name('tools.category.running');
Route::get('/outils/categorie/force-musculation', [ToolController::class, 'strengthTraining'])->name('tools.category.strength');
Route::get('/outils/categorie/outils-pratiques', [ToolController::class, 'practicalTools'])->name('tools.category.practical');
Route::get('/outils/categorie/outils-developpement', [ToolController::class, 'developmentTools'])->name('tools.category.development');


// ========== ROUTES CATÉGORIES ET ARTICLES ==========
Route::get('/categories', [PublicController::class, 'categories'])->name('public.index');
Route::get('/categories/{category:slug}', [PublicController::class, 'category'])->name('public.category');

Route::get('/articles', [PublicController::class, 'index'])->name('public.index');
Route::get('/articles/{post:slug}', [PublicController::class, 'show'])->name('public.show');





require __DIR__.'/auth.php';


// ROUTES DE PAIEMENTS UTILISATEURS (HORS GROUPE ADMIN)
Route::middleware(['auth'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('payments.create-intent');
    Route::get('/payments/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
});




// Dashboard avec redirection intelligente
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('user.dashboard');
})->name('dashboard');

// Espace Utilisateur
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::view('/dashboard', 'user.dashboard')->name('dashboard');
    Route::view('/', 'user.index')->name('index');
    Route::view('/show', 'user.show')->name('show');
    Route::view('profile/edit', 'user.profile.edit')->name('profile.edit');
    
    // NOUVELLE ROUTE pour traiter la mise A jour du profil
    Route::put('profile', [ProfileController::class, 'updateUserProfile'])->name('profile.update');
// Dans le groupe Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    
// Calendar routes
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');
        Route::get('/create', [CalendarController::class, 'create'])->name('create'); // NOUVEAU
        Route::post('/', [CalendarController::class, 'store'])->name('store');
        Route::get('/{event}', [CalendarController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [CalendarController::class, 'edit'])->name('edit'); // NOUVEAU
        Route::put('/{event}', [CalendarController::class, 'update'])->name('update');
        Route::delete('/{event}', [CalendarController::class, 'destroy'])->name('destroy');
        
        // Finalisation de l'événement
        Route::post('/{event}/complete', [CalendarController::class, 'complete'])->name('complete');
        
        // Annuler un événement
        Route::post('/{event}/cancel', [CalendarController::class, 'cancel'])->name('cancel');
        
        // Badge compteur semaine
        Route::get('/api/week-count', [CalendarController::class, 'weekCount'])->name('week-count');
        
        // API pour contenus linkables
        Route::get('/get-linkable/{type}', [CalendarController::class, 'getLinkable'])->name('get-linkable');
        
        // Créer depuis workout/plan
        Route::get('/from-workout/{workout}', [CalendarController::class, 'createFromWorkout'])->name('from-workout');
        Route::get('/from-plan/{plan}', [CalendarController::class, 'createFromPlan'])->name('from-plan');

// ========== NOUVELLES ROUTES API ==========
        Route::prefix('api')->name('api.')->group(function () {
            // Workout sections, categories et workouts
            Route::get('workout-sections', [CalendarController::class, 'getWorkoutSections'])->name('workout-sections');
            Route::get('workout-categories/{section}', [CalendarController::class, 'getWorkoutCategories'])->name('workout-categories');
            Route::get('workouts/{category}', [CalendarController::class, 'getWorkouts'])->name('workouts');
            
            // Exercice categories et exercices
            Route::get('exercice-categories', [CalendarController::class, 'getExerciceCategories'])->name('exercice-categories');
            Route::get('exercices/{category?}', [CalendarController::class, 'getExercices'])->name('exercices');
        });


    });


    // ========== ROUTES MES CARNETS ==========
    Route::prefix('notebooks')->name('notebooks.')->group(function () {
        Route::get('/api/by-type', [\App\Http\Controllers\User\NotebookController::class, 'getByType'])->name('api.by-type');
        Route::get('/', [\App\Http\Controllers\User\NotebookController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\User\NotebookController::class, 'store'])->name('store');
        Route::get('/{notebook}', [\App\Http\Controllers\User\NotebookController::class, 'show'])->name('show');
        Route::put('/{notebook}', [\App\Http\Controllers\User\NotebookController::class, 'update'])->name('update');
        Route::delete('/{notebook}', [\App\Http\Controllers\User\NotebookController::class, 'destroy'])->name('destroy');

        // Actions sur les contenus
        Route::post('/items/add', [\App\Http\Controllers\User\NotebookController::class, 'addItem'])->name('items.add');
        Route::delete('/items/{item}', [\App\Http\Controllers\User\NotebookController::class, 'removeItem'])->name('items.remove');
        Route::patch('/items/{item}/note', [\App\Http\Controllers\User\NotebookController::class, 'updateNote'])->name('items.update-note');
        Route::post('/{notebook}/reorder', [\App\Http\Controllers\User\NotebookController::class, 'reorder'])->name('reorder');
        
        // Export et favoris
        Route::get('/{notebook}/export-pdf', [\App\Http\Controllers\User\NotebookController::class, 'exportPdf'])->name('export-pdf');
        Route::post('/{notebook}/toggle-favorite', [\App\Http\Controllers\User\NotebookController::class, 'toggleFavorite'])->name('toggle-favorite');
    });

    
// Ajoutez ces routes dans le groupe user existant, après la route profile.update :

// ========== ROUTES ENTRAÎNEMENT UTILISATEUR ==========

// Plans d'entraînement
Route::get('training', [\App\Http\Controllers\User\TrainingController::class, 'index'])->name('training.index');
Route::get('training/mes-plans', [\App\Http\Controllers\User\TrainingController::class, 'mesPlans'])->name('training.mes-plans');
Route::get('training/plans/{plan}', [\App\Http\Controllers\User\TrainingController::class, 'show'])->name('training.show');
Route::get('training/plans/{plan}/cycles/{cycle}', [\App\Http\Controllers\User\TrainingController::class, 'cycle'])->name('training.cycle');
Route::get('training/plans/{plan}/cycles/{cycle}/seances/{seance}', [\App\Http\Controllers\User\TrainingController::class, 'seance'])->name('training.seance');
Route::get('training/exercices/{exercice}', [\App\Http\Controllers\User\TrainingController::class, 'exercice'])->name('training.exercice');

// Actions utilisateur sur les plans
Route::post('training/plans/{plan}/commencer', [\App\Http\Controllers\User\TrainingController::class, 'commencer'])->name('training.commencer');
Route::patch('training/plans/{plan}/statut', [\App\Http\Controllers\User\TrainingController::class, 'updateStatut'])->name('training.update-statut');


});

// Espace Administration
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');
    
// Gestion des sections de workout
    Route::resource('workout-sections', WorkoutSectionController::class)->parameters([
        'workout-sections' => 'workoutSection'
    ]);
    
    // Gestion des catégories de workout
    Route::resource('workout-categories', WorkoutCategoryController::class)->parameters([
        'workout-categories' => 'workoutCategory'
    ]);
    
    // Gestion des workouts
    Route::resource('workouts', WorkoutController::class);

    Route::resource('categories', CategoryController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('posts', PostController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('tags', TagController::class);
    Route::resource('users', UserController::class);
    // Route pour la mise à jour rapide du rôle via AJAX
Route::patch('users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');

    Route::get('profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

Route::post('/admin/users/{user}/promote', [UserController::class, 'promote'])->name('admin.users.promote');
    Route::post('/admin/users/{user}/demote', [UserController::class, 'demote'])->name('admin.users.demote');

// Gestion des catégories de fiches
   // Gestion des catégories de fiches
    Route::resource('fiches-categories', FichesCategoryController::class)->parameters([
        'fiches-categories' => 'fichesCategory'
    ]);
    
    // Gestion des sous-catégories de fiches (AVANT la resource fiches)
    Route::resource('fiches-sous-categories', FichesSousCategoryController::class)->parameters([
        'fiches-sous-categories' => 'fichesSousCategory'
    ]);
    
    
    // API pour récupérer les sous-catégories d'une catégorie (pour select dynamique)
    Route::get('api/fiches-sous-categories/by-category', [FichesSousCategoryController::class, 'apiByCategory'])
        ->name('fiches-sous-categories.api.by-category');
    

// 🇫🇷 Route pour l'assignation groupée de catégories aux fiches
Route::post('fiches/bulk-assign-categories', [FicheController::class, 'bulkAssignCategories'])
    ->name('fiches.bulk-assign-categories');

    // Gestion des fiches
    Route::resource('fiches', FicheController::class)->parameters([
        'fiches' => 'fiche'
    ]);

// Gestion des fichiers eBooks
    Route::prefix('ebook-files')->name('ebook-files.')->group(function () {
        Route::get('/', [EbookFileController::class, 'index'])->name('index');
        Route::post('/', [EbookFileController::class, 'store'])->name('store');
        Route::get('/{ebookFile}', [EbookFileController::class, 'show'])->name('show');
        Route::put('/{ebookFile}', [EbookFileController::class, 'update'])->name('update');
        Route::delete('/{ebookFile}', [EbookFileController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [EbookFileController::class, 'bulkAction'])->name('bulk-action');
        
        // API pour modal
        Route::get('/api/files', [EbookFileController::class, 'api'])->name('api');
    });

// Routes AI Text Optimizer
Route::prefix('aitext')->name('aitext.')->group(function () {
    Route::get('/settings', [AITextController::class, 'settings'])->name('settings');
    Route::post('/save', [AITextController::class, 'saveSettings'])->name('save');
    Route::post('/test', [AITextController::class, 'testConnection'])->name('test');
    Route::post('/process', [AITextController::class, 'process'])->name('process');
    Route::post('/optimize-title', [AITextController::class, 'optimizeTitle'])->name('optimize-title');
    Route::post('/optimize-slug', [AITextController::class, 'optimizeSlug'])->name('optimize-slug');
});


// ========== ROUTES MeDIAS ==========
    
    // Gestion principale des medias
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');
    Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    
    // API pour selection des medias (utilisee dans les modals)
    Route::get('media-api', [MediaController::class, 'api'])->name('media.api');
        Route::get('media-categories-api', [MediaController::class, 'categoriesApi'])->name('media.categories.api');

    
    // Gestion des categories de medias
    Route::get('media-categories', [MediaController::class, 'categories'])->name('media.categories');
    Route::post('media-categories', [MediaController::class, 'storeCategory'])->name('media.categories.store');
    Route::delete('media-categories/{category}', [MediaController::class, 'destroyCategory'])->name('media.categories.destroy');

    Route::post('media/bulk-action', [MediaController::class, 'bulkAction'])->name('media.bulk-action');

// Routes pour la bibliothèque vidéo (admin uniquement)
Route::prefix('video-library')->name('video-library.')->group(function () {
    Route::post('browse', [App\Http\Controllers\Admin\VideoLibraryController::class, 'browse'])
        ->name('browse');
    Route::post('import', [App\Http\Controllers\Admin\VideoLibraryController::class, 'import'])
        ->name('import');
    Route::post('upload', [App\Http\Controllers\Admin\VideoLibraryController::class, 'upload'])
        ->name('upload');
    Route::post('create-folder', [App\Http\Controllers\Admin\VideoLibraryController::class, 'createFolder'])
        ->name('create-folder');
    Route::delete('delete-file', [App\Http\Controllers\Admin\VideoLibraryController::class, 'deleteFile'])
        ->name('delete-file');
});

// ========== ROUTES ENTRAÎNEMENT ADMIN ==========

// Gestion des exercices
Route::resource('training/exercices', \App\Http\Controllers\Admin\ExerciceController::class)
    ->names('training.exercices');


Route::post('training/exercices/bulk-assign-categories', [\App\Http\Controllers\Admin\ExerciceController::class, 'bulkAssignCategories'])
    ->name('training.exercices.bulk-assign-categories');

// Gestion des séries
Route::resource('training/series', \App\Http\Controllers\Admin\SerieController::class)
    ->names('training.series');

// Gestion des séances
Route::resource('training/seances', \App\Http\Controllers\Admin\SeanceController::class)
    ->names('training.seances');

// Gestion des cycles
Route::resource('training/cycles', \App\Http\Controllers\Admin\CycleController::class)
    ->names('training.cycles');

// Gestion des plans
Route::resource('training/plans', \App\Http\Controllers\Admin\PlanController::class)
    ->names('training.plans');

// Gestion des assignations de plans
Route::get('training/plans/{plan}/assignations', [\App\Http\Controllers\Admin\PlanController::class, 'assignations'])
    ->name('training.plans.assignations');
Route::post('training/plans/{plan}/assign-user', [\App\Http\Controllers\Admin\PlanController::class, 'assignUser'])
    ->name('training.plans.assign-user');
Route::delete('training/plans/{plan}/unassign-user/{user}', [\App\Http\Controllers\Admin\PlanController::class, 'unassignUser'])
    ->name('training.plans.unassign-user');
Route::patch('training/plans/{plan}/update-assignation/{user}', [\App\Http\Controllers\Admin\PlanController::class, 'updateAssignation'])
    ->name('training.plans.update-assignation');

    // Gestion des catégories d'exercices
    Route::resource('exercice-categories', \App\Http\Controllers\Admin\ExerciceCategoryController::class)
        ->parameters(['exercice-categories' => 'exerciceCategory']);
    
    // Gestion des sous-catégories d'exercices
    Route::resource('exercice-sous-categories', \App\Http\Controllers\Admin\ExerciceSousCategoryController::class)
        ->parameters(['exercice-sous-categories' => 'exerciceSousCategory']);
    
    // API pour récupérer les sous-catégories d'une catégorie (pour select dynamique)
    Route::get('api/exercice-sous-categories/by-category', [\App\Http\Controllers\Admin\ExerciceSousCategoryController::class, 'apiByCategory'])
        ->name('exercice-sous-categories.api.by-category');


    // ========== ROUTES VIDÉOS ADMIN ==========
    Route::resource('video-categories', \App\Http\Controllers\Admin\VideoCategoryController::class)
        ->parameters(['video-categories' => 'videoCategory']);
    
    Route::resource('videos', \App\Http\Controllers\Admin\VideoController::class);
    
    Route::post('videos/fetch-metadata', [\App\Http\Controllers\Admin\VideoController::class, 'fetchMetadata'])
        ->name('videos.fetch-metadata');



    // ROUTES ADMIN PAIEMENTS
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::post('payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

    // Gestion des categories de telechargement
    Route::resource('download-categories', DownloadCategoryController::class);
    Route::get('download-categories-stats', [DownloadCategoryController::class, 'stats'])->name('download-categories.stats');
    
    // Gestion des telechargements
    Route::resource('downloadables', DownloadableController::class);
    Route::post('downloadables/{downloadable}/duplicate', [DownloadableController::class, 'duplicate'])->name('downloadables.duplicate');
    Route::get('downloadables-stats', [DownloadableController::class, 'stats'])->name('downloadables.stats');
    Route::post('downloadables/bulk-action', [DownloadableController::class, 'bulkAction'])->name('downloadables.bulk-action');

// ========== ROUTES SITEMAP ==========
    Route::prefix('sitemap')->name('sitemap.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SitemapController::class, 'index'])->name('index');
        Route::post('/discover', [\App\Http\Controllers\Admin\SitemapController::class, 'discover'])->name('discover');
        Route::post('/generate', [\App\Http\Controllers\Admin\SitemapController::class, 'generate'])->name('generate');
        Route::post('/store', [\App\Http\Controllers\Admin\SitemapController::class, 'store'])->name('store');
        Route::patch('/{sitemapUrl}', [\App\Http\Controllers\Admin\SitemapController::class, 'update'])->name('update');
        Route::post('/bulk-approve', [\App\Http\Controllers\Admin\SitemapController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/{sitemapUrl}/toggle', [\App\Http\Controllers\Admin\SitemapController::class, 'toggleApproval'])->name('toggle');
        Route::delete('/{sitemapUrl}', [\App\Http\Controllers\Admin\SitemapController::class, 'destroy'])->name('destroy');
        Route::post('/clean', [\App\Http\Controllers\Admin\SitemapController::class, 'clean'])->name('clean');
    });

    // ========== ROUTES CATALOGUE ==========
    // Gestion des sections du catalogue
    Route::resource('catalogue-sections', \App\Http\Controllers\Admin\CatalogueSectionController::class)
        ->parameters(['catalogue-sections' => 'catalogueSection']);
    
    // Gestion des modules du catalogue
    Route::resource('catalogue-modules', \App\Http\Controllers\Admin\CatalogueModuleController::class)
        ->parameters(['catalogue-modules' => 'catalogueModule']);
    
    // Gestion des unités du catalogue
    Route::resource('catalogue-units', \App\Http\Controllers\Admin\CatalogueUnitController::class)
        ->parameters(['catalogue-units' => 'catalogueUnit']);
    
    // API pour les selects dynamiques
    Route::get('api/catalogue-modules/by-section', [\App\Http\Controllers\Admin\CatalogueUnitController::class, 'apiModulesBySection'])
        ->name('catalogue-units.api.modules-by-section');
    
    Route::get('api/catalogue-content/by-type', [\App\Http\Controllers\Admin\CatalogueUnitController::class, 'apiContentByType'])
        ->name('catalogue-units.api.content-by-type');
        
 // Routes pour la gestion des contenus multiples
Route::prefix('catalogue-units/{catalogueUnit}')->name('catalogue-units.')->group(function () {
    Route::get('/contents', [CatalogueUnitController::class, 'contents'])->name('contents');
    Route::post('/contents', [CatalogueUnitController::class, 'addContent'])->name('add-content');
    Route::post('/contents/add-multiple', [CatalogueUnitController::class, 'addMultipleContents'])->name('add-multiple-contents'); // NOUVELLE ROUTE
    Route::delete('/contents/{content}', [CatalogueUnitController::class, 'removeContent'])->name('remove-content');
    Route::post('/contents/reorder', [CatalogueUnitController::class, 'reorderContents'])->name('reorder-contents');
});

});