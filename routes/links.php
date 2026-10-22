<?php

/*
|--------------------------------------------------------------------------
| Module « Liens utiles » (administration)
|--------------------------------------------------------------------------
| Inclus dans le groupe « admin » de routes/web.php → routes admin.links.*
| (URL /admin/liens-utiles/…). Réservé aux administrateurs.
*/

use App\Http\Controllers\Admin\LinkCategoryController;
use App\Http\Controllers\Admin\LinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('liens-utiles')->name('links.')->group(function () {
    Route::resource('liens', LinkController::class)
        ->except(['show'])
        ->parameters(['liens' => 'link'])
        ->names('items');

    Route::resource('categories', LinkCategoryController::class)
        ->except(['show'])
        ->parameters(['categories' => 'category'])
        ->names('categories');
});
