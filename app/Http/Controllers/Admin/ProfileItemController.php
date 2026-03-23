<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileItemRequest;
use App\Http\Requests\UpdateProfileItemRequest;
use App\Models\ProfileItem;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileItemController extends Controller
{
    // -------------------------------------------------------------------------
    // Contrôle d'accès — pattern du projet
    // -------------------------------------------------------------------------

    private function checkAdminAccess(): void
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé.');
        }
    }

    // -------------------------------------------------------------------------
    // Formulaire de création d'un item rattaché à une fiche
    // -------------------------------------------------------------------------

    public function create(User $user): View
    {
        $this->checkAdminAccess();

        // S'assurer que l'utilisateur possède bien une fiche
        $profile = $user->userProfile ?? abort(404, 'Fiche introuvable pour cet utilisateur.');

        return view('admin.user-profiles.items.create', compact('user', 'profile'));
    }

    // -------------------------------------------------------------------------
    // Enregistrement d'un nouvel item
    // -------------------------------------------------------------------------

    public function store(StoreProfileItemRequest $request, User $user): RedirectResponse
    {
        $this->checkAdminAccess();

        $profile = $user->userProfile ?? abort(404, 'Fiche introuvable pour cet utilisateur.');

        $profile->items()->create($request->validated());

        return redirect()
            ->route('admin.user-profiles.show', $user)
            ->with('success', 'Élément ajouté avec succès.');
    }

    // -------------------------------------------------------------------------
    // Formulaire d'édition d'un item existant
    // -------------------------------------------------------------------------

    public function edit(User $user, ProfileItem $item): View
    {
        $this->checkAdminAccess();

        // Vérifier que l'item appartient bien à la fiche de cet utilisateur
        $profile = $user->userProfile ?? abort(404, 'Fiche introuvable pour cet utilisateur.');

        abort_if(
            $item->user_profile_id !== $profile->id,
            403,
            'Cet élément n\'appartient pas à la fiche de cet utilisateur.'
        );

        return view('admin.user-profiles.items.edit', compact('user', 'profile', 'item'));
    }

    // -------------------------------------------------------------------------
    // Mise à jour d'un item existant
    // -------------------------------------------------------------------------

    public function update(UpdateProfileItemRequest $request, User $user, ProfileItem $item): RedirectResponse
    {
        $this->checkAdminAccess();

        $profile = $user->userProfile ?? abort(404, 'Fiche introuvable pour cet utilisateur.');

        abort_if(
            $item->user_profile_id !== $profile->id,
            403,
            'Cet élément n\'appartient pas à la fiche de cet utilisateur.'
        );

        $item->update($request->validated());

        return redirect()
            ->route('admin.user-profiles.show', $user)
            ->with('success', 'Élément mis à jour avec succès.');
    }

    // -------------------------------------------------------------------------
    // Suppression d'un item
    // -------------------------------------------------------------------------

    public function destroy(User $user, ProfileItem $item): RedirectResponse
    {
        $this->checkAdminAccess();

        $profile = $user->userProfile ?? abort(404, 'Fiche introuvable pour cet utilisateur.');

        abort_if(
            $item->user_profile_id !== $profile->id,
            403,
            'Cet élément n\'appartient pas à la fiche de cet utilisateur.'
        );

        $item->delete();

        return redirect()
            ->route('admin.user-profiles.show', $user)
            ->with('success', 'Élément supprimé avec succès.');
    }
}
