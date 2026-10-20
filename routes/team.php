<?php

/*
|--------------------------------------------------------------------------
| Module « L'équipe du club » (administration)
|--------------------------------------------------------------------------
| Inclus dans le groupe « admin » de routes/web.php → routes admin.team.*
| (URL /admin/equipe/…). Réservé aux administrateurs.
*/

use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TeamSectionController;
use Illuminate\Support\Facades\Route;

Route::prefix('equipe')->name('team.')->group(function () {
    Route::resource('membres', TeamMemberController::class)
        ->except(['show'])
        ->parameters(['membres' => 'member'])
        ->names('members');

    Route::resource('sections', TeamSectionController::class)
        ->except(['show'])
        ->parameters(['sections' => 'section'])
        ->names('sections');
});
