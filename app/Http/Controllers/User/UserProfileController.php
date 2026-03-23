<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    // -------------------------------------------------------------------------
    // Affichage de la fiche de l'utilisateur connecté (lecture seule)
    // -------------------------------------------------------------------------

    public function show(Request $request): View
    {
        $user = $request->user()->load('role', 'userProfile.items');

        // La fiche peut ne pas encore exister si l'admin ne l'a pas créée
        $profile = $user->userProfile;

        return view('user.user-profiles.show', compact('user', 'profile'));
    }
}
