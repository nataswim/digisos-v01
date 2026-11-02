<?php

/*
|--------------------------------------------------------------------------
| Module Adhérents (administrateurs et éditeurs)
|--------------------------------------------------------------------------
| Inclus dans le groupe « admin » (routes admin.members.*) et dans le groupe « editor » (editor.members.*).
*/

use App\Http\Controllers\Admin\Members\MemberCategoryController;
use App\Http\Controllers\Admin\Members\MemberController;
use App\Http\Controllers\Admin\Members\MemberImportController;
use App\Http\Controllers\Admin\Members\MembershipPaymentController;
use App\Http\Controllers\Admin\Members\MemberStatsController;
use Illuminate\Support\Facades\Route;

Route::prefix('adherents')->name('members.')->group(function () {
    Route::get('tableau-de-bord', MemberStatsController::class)->name('stats');
    Route::get('export', [MemberController::class, 'export'])->name('export');
    Route::post('nouvelle-saison', [MemberController::class, 'prepareSeason'])->name('prepare-season');

    Route::get('import', [MemberImportController::class, 'create'])->name('import');
    Route::post('import', [MemberImportController::class, 'store'])->name('import.store');

    Route::get('categories', [MemberCategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [MemberCategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [MemberCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [MemberCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('adhesions/{membership}/reglements', [MembershipPaymentController::class, 'store'])->name('payments.store');
    Route::patch('reglements/{payment}', [MembershipPaymentController::class, 'update'])->name('payments.update');
    Route::delete('reglements/{payment}', [MembershipPaymentController::class, 'destroy'])->name('payments.destroy');

    Route::get('/', [MemberController::class, 'index'])->name('index');
    Route::get('nouveau', [MemberController::class, 'create'])->name('create');
    Route::post('/', [MemberController::class, 'store'])->name('store');
    Route::get('{member}', [MemberController::class, 'show'])->whereNumber('member')->name('show');
    Route::get('{member}/modifier', [MemberController::class, 'edit'])->whereNumber('member')->name('edit');
    Route::put('{member}', [MemberController::class, 'update'])->whereNumber('member')->name('update');
    Route::delete('{member}', [MemberController::class, 'destroy'])->whereNumber('member')->name('destroy');
    Route::delete('{member}/adhesions/{membership}', [MemberController::class, 'destroyMembership'])->whereNumber('member')->name('memberships.destroy');
    Route::get('{member}/adhesions/{membership}/attestation', [MemberController::class, 'attestation'])->whereNumber('member')->name('attestation');
    Route::post('{member}/nageur', [MemberController::class, 'linkSwimmer'])->whereNumber('member')->name('link-swimmer');
    Route::post('{member}/compte', [MemberController::class, 'linkUser'])->whereNumber('member')->name('link-user');
    Route::post('{member}/depuis-licence', [MemberController::class, 'fillFromLicense'])->whereNumber('member')->name('fill-license');
});
