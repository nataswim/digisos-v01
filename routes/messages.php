<?php

/*
|--------------------------------------------------------------------------
| Messagerie de l'équipe du club — inclus dans les groupes admin, editor et coach
|--------------------------------------------------------------------------
| URL /{admin|editor|coach}/messagerie/…, noms {admin|editor|coach}.messages.*
*/

use App\Http\Controllers\Messages\StaffMessageController;
use Illuminate\Support\Facades\Route;

Route::controller(StaffMessageController::class)->prefix('messagerie')->name('messages.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/nouveau', 'create')->name('create');
    Route::get('/destinataires/recherche', 'search')->middleware('throttle:60,1')->name('search');
    Route::post('/destinataires/apercu', 'preview')->middleware('throttle:60,1')->name('preview');
    Route::post('/', 'store')->middleware('throttle:20,1')->name('store');
    Route::get('/annonces/{announcement}', 'announcement')->whereNumber('announcement')->name('announcement');
    Route::delete('/annonces/{announcement}', 'destroyAnnouncement')->whereNumber('announcement')->name('announcement.destroy');
    Route::get('/{conversation}', 'show')->whereNumber('conversation')->name('show');
    Route::post('/{conversation}', 'reply')->whereNumber('conversation')->middleware('throttle:30,1')->name('reply');
    Route::delete('/{conversation}', 'destroy')->whereNumber('conversation')->name('destroy');
});
