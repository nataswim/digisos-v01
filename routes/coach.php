<?php

/*
|--------------------------------------------------------------------------
| Espace coachs (coachs et administrateurs) — URL /coach/…, routes coach.*
|--------------------------------------------------------------------------
| Planning et compétitions : mêmes contrôleurs que l'admin et l'éditeur
| (fichiers routes/planning.php et routes/competitions.php inclus ici).
*/

use App\Http\Controllers\Coach\AssiduityController;
use App\Http\Controllers\Coach\CoachDashboardController;
use App\Http\Controllers\Coach\CoachDocumentController;
use App\Http\Controllers\Coach\PerformanceController;
use App\Http\Controllers\Coach\PresenceController;
use App\Http\Controllers\Coach\SwimmerNoteController;
use App\Http\Controllers\Coach\SwimRecordController;
use App\Http\Controllers\Coach\TrainingExerciseController;
use App\Http\Controllers\Coach\TrainingListController;
use App\Http\Controllers\Coach\TrainingSessionController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'can:access-coach'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/', fn () => redirect()->route('coach.dashboard'));
    Route::get('/dashboard', CoachDashboardController::class)->name('dashboard');

    // Médiathèque (sélecteur d'images des formulaires)
    Route::get('media-api', [MediaController::class, 'api'])->name('media.api');
    Route::get('media-categories-api', [MediaController::class, 'categoriesApi'])->name('media.categories.api');

    // Planning : créneaux, groupes, nageurs (routes coach.planning.*)
    require __DIR__.'/planning.php';

    // Compétitions et convocations (routes coach.competitions.*)
    require __DIR__.'/competitions.php';

    // Documents réservés aux coachs
    Route::get('documents/{document}/telecharger', [CoachDocumentController::class, 'download'])->name('documents.download');
    Route::resource('documents', CoachDocumentController::class)->except(['show']);

    // Séances & entraînements
    Route::get('seances/listes', [TrainingListController::class, 'edit'])->name('training-lists.edit');
    Route::put('seances/listes', [TrainingListController::class, 'update'])->name('training-lists.update');
    Route::resource('seances/exercices', TrainingExerciseController::class)
        ->except(['show'])->parameters(['exercices' => 'exercise'])->names('exercises');
    Route::patch('seances/{training}/publication', [TrainingSessionController::class, 'toggle'])->name('training.toggle');
    Route::post('seances/{training}/dupliquer', [TrainingSessionController::class, 'duplicate'])->name('training.duplicate');
    Route::resource('seances', TrainingSessionController::class)->parameters(['seances' => 'training'])->names('training');

    // Suivi des performances
    Route::prefix('performances')->name('performances.')->controller(PerformanceController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('saisie', 'create')->name('create');
        // Import depuis extraNat (administrateurs uniquement, vérifié dans le contrôleur)
        Route::get('importer', [\App\Http\Controllers\Coach\PerformanceImportController::class, 'create'])->name('import');
        Route::post('importer', [\App\Http\Controllers\Coach\PerformanceImportController::class, 'store'])->middleware('throttle:30,1')->name('import.store');
        Route::post('saisie', 'store')->name('store');
        Route::get('bilan', 'report')->name('report');
        Route::get('classement', 'ranking')->name('ranking');
        Route::get('nageur/{swimmer}', 'swimmer')->name('swimmer');
        Route::get('{performance}/modifier', 'edit')->name('edit');
        Route::put('{performance}', 'update')->name('update');
        Route::delete('{performance}', 'destroy')->name('destroy');
    });

    // Records (club et officiels)
    Route::get('records/importer', [SwimRecordController::class, 'importForm'])->name('records.import-form');
    Route::post('records/importer', [SwimRecordController::class, 'import'])->name('records.import');
    Route::resource('records', SwimRecordController::class)->except(['show'])->parameters(['records' => 'record']);

    // Émargement des séances
    Route::get('emargement', [PresenceController::class, 'index'])->name('presence.index');
    Route::post('emargement/ouvrir', [PresenceController::class, 'open'])->name('presence.open');
    Route::get('emargement/seance-supplementaire', [PresenceController::class, 'create'])->name('presence.create');
    Route::post('emargement/seance-supplementaire', [PresenceController::class, 'store'])->name('presence.store');
    Route::get('emargement/seances/{session}', [PresenceController::class, 'show'])->name('presence.show');
    Route::put('emargement/seances/{session}', [PresenceController::class, 'update'])->name('presence.update');
    Route::post('emargement/seances/{session}/nageur', [PresenceController::class, 'addSwimmer'])->name('presence.add-swimmer');
    Route::delete('emargement/seances/{session}', [PresenceController::class, 'destroy'])->name('presence.destroy');

    // Assiduité
    Route::get('assiduite', [AssiduityController::class, 'index'])->name('assiduity.index');
    Route::get('assiduite/nageur/{swimmer}', [AssiduityController::class, 'swimmer'])->name('assiduity.swimmer');
    Route::get('assiduite/groupe/{group}', [AssiduityController::class, 'group'])->name('assiduity.group');

    // Suivi des nageurs
    Route::get('suivi', [SwimmerNoteController::class, 'index'])->name('notes.index');
    Route::get('suivi/nageur/{swimmer}', [SwimmerNoteController::class, 'show'])->name('notes.show');
    Route::post('suivi/nageur/{swimmer}', [SwimmerNoteController::class, 'store'])->name('notes.store');
    Route::get('suivi/notes/{note}/modifier', [SwimmerNoteController::class, 'edit'])->name('notes.edit');
    Route::put('suivi/notes/{note}', [SwimmerNoteController::class, 'update'])->name('notes.update');
    Route::delete('suivi/notes/{note}', [SwimmerNoteController::class, 'destroy'])->name('notes.destroy');

    // Messagerie (mêmes pages que l'admin et l'éditeur)
    require __DIR__.'/messages.php';
});
