<?php

/*
|--------------------------------------------------------------------------
| Licences FFN (import extraNat) — RÉSERVÉ AUX ADMINISTRATEURS
|--------------------------------------------------------------------------
| Inclus uniquement dans le groupe « admin » de routes/web.php
| (middleware auth, verified, can:access-admin) : routes admin.ffn-licenses.*
| Ne pas inclure dans le groupe « editor » : le fichier contient des données personnelles.
*/

use App\Http\Controllers\Admin\FfnLicenseController;
use Illuminate\Support\Facades\Route;

Route::controller(FfnLicenseController::class)
    ->prefix('licences-ffn')
    ->name('ffn-licenses.')
    ->group(function () {
        Route::get('/', 'index')->name('index');

        // Import en trois étapes : dépôt → prévisualisation → confirmation
        Route::get('/importer', 'create')->name('import.create');
        Route::post('/importer', 'upload')->middleware('throttle:10,1')->name('import.upload');
        Route::get('/importer/{token}', 'review')->whereUuid('token')->name('import.review');
        Route::post('/importer/{token}', 'store')->whereUuid('token')->name('import.store');
        Route::delete('/importer/{token}', 'cancel')->whereUuid('token')->name('import.cancel');

        Route::delete('/saison/{season}', 'destroySeason')->whereNumber('season')->name('season.destroy');
        Route::get('/{license}', 'show')->whereNumber('license')->name('show');
    });
