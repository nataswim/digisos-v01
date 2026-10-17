<?php

/*
|--------------------------------------------------------------------------
| Module Boutique vitrine (administration)
|--------------------------------------------------------------------------
| Inclus dans le groupe « admin » de routes/web.php → routes admin.shop.*
| (URL /admin/boutique/…). Réservé aux administrateurs.
*/

use App\Http\Controllers\Admin\ShopCategoryController;
use App\Http\Controllers\Admin\ShopProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('boutique')->name('shop.')->group(function () {
    Route::resource('produits', ShopProductController::class)
        ->except(['show'])
        ->parameters(['produits' => 'product'])
        ->names('products');

    Route::resource('categories', ShopCategoryController::class)
        ->except(['show'])
        ->parameters(['categories' => 'category'])
        ->names('categories');
});
