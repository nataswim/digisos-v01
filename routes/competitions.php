<?php

/*
|--------------------------------------------------------------------------
| Module Compétitions (administration)
|--------------------------------------------------------------------------
| Inclus dans le groupe « admin » (routes admin.competitions.*) et dans le
| groupe « editor » (routes editor.competitions.*) de routes/web.php.
*/

use App\Http\Controllers\Admin\Competitions\CompetitionController;
use App\Http\Controllers\Admin\Competitions\CompetitionEntryController;
use App\Http\Controllers\Admin\Competitions\CompetitionProgramController;
use Illuminate\Support\Facades\Route;

Route::post('competitions/{competition}/dupliquer', [CompetitionController::class, 'duplicate'])
    ->name('competitions.duplicate');

// Programme structuré : grille des épreuves par réunion (Dames / Messieurs, séries, finales A, B, C)
Route::controller(CompetitionProgramController::class)
    ->prefix('competitions/{competition}/programme')
    ->name('competitions.program.')
    ->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::patch('/reunions/{session}/ordre', 'reorder')->whereNumber('session')->name('reorder');
    });

// V2 — Convocations, réponses des familles, covoiturage et engagements
Route::controller(CompetitionEntryController::class)
    ->prefix('competitions/{competition}/convocations')
    ->name('competitions.entries.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::patch('/statuts', 'updateStatuses')->name('statuses');
        Route::post('/prevenir', 'notify')->name('notify');
        Route::get('/export.csv', 'export')->name('export');
        Route::get('/{attendance}', 'edit')->whereNumber('attendance')->name('edit');
        Route::put('/{attendance}', 'update')->whereNumber('attendance')->name('update');
        Route::delete('/{attendance}', 'destroy')->whereNumber('attendance')->name('destroy');
    });

Route::resource('competitions', CompetitionController::class)->except(['show']);
