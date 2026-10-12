<?php

/*
|--------------------------------------------------------------------------
| Module Planning (administration)
|--------------------------------------------------------------------------
| Fichier inclus DEUX fois dans routes/web.php :
|   - dans le groupe « admin »  → routes admin.planning.*  (URL /admin/planning/…)
|   - dans le groupe « editor » → routes editor.planning.* (URL /editor/planning/…)
| Les droits d'accès sont ceux du groupe qui l'inclut.
*/

use App\Http\Controllers\Admin\Planning\PlanningGroupController;
use App\Http\Controllers\Admin\Planning\PlanningSlotController;
use App\Http\Controllers\Admin\Planning\PlanningSwimmerController;
use Illuminate\Support\Facades\Route;

Route::prefix('planning')->name('planning.')->group(function () {
    Route::post('creneaux/{slot}/dupliquer', [PlanningSlotController::class, 'duplicate'])->name('slots.duplicate');

    Route::resource('creneaux', PlanningSlotController::class)
        ->except(['show'])
        ->parameters(['creneaux' => 'slot'])
        ->names('slots');

    Route::resource('groupes', PlanningGroupController::class)
        ->except(['show'])
        ->parameters(['groupes' => 'group'])
        ->names('groups');

    Route::resource('nageurs', PlanningSwimmerController::class)
        ->except(['show'])
        ->parameters(['nageurs' => 'swimmer'])
        ->names('swimmers');
});
