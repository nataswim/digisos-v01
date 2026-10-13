<?php

/*
|--------------------------------------------------------------------------
| Module Partenaires (administration)
|--------------------------------------------------------------------------
| Fichier inclus DEUX fois dans routes/web.php, comme routes/planning.php :
|   - dans le groupe « admin »  → routes admin.partners.*  (URL /admin/partenaires/…)
|   - dans le groupe « editor » → routes editor.partners.* (URL /editor/partenaires/…)
| Les droits d'accès sont ceux du groupe qui l'inclut.
*/

use App\Http\Controllers\Admin\PartnerController;
use Illuminate\Support\Facades\Route;

Route::resource('partenaires', PartnerController::class)
    ->except(['show'])
    ->parameters(['partenaires' => 'partner'])
    ->names('partners');
