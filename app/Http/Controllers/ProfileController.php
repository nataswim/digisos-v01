<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    // -------------------------------------------------------------------------
    // Contrôle d'accès Admin / Editor
    // -------------------------------------------------------------------------

    private function checkAdminAccess(): void
    {
        if (! auth()->user()->hasRole('admin') && ! auth()->user()->hasRole('editor')) {
            abort(403, 'Accès non autorisé.');
        }
    }

    // -------------------------------------------------------------------------
    // MÉTHODES ADMIN / EDITOR — inchangées
    // -------------------------------------------------------------------------

    public function show(): View
    {
        $this->checkAdminAccess();

        $user = Auth::user()->load('role');

        return view('admin.profile.show', compact('user'));
    }

    public function edit(): View
    {
        $this->checkAdminAccess();

        $user = Auth::user()->load('role');

        return view('admin.profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->checkAdminAccess();

        $user = Auth::user();
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->checkAdminAccess();

        $user = Auth::user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Votre compte a été supprimé.');
    }

    // -------------------------------------------------------------------------
    // MÉTHODES USER — espace utilisateur
    // -------------------------------------------------------------------------

    /**
     * Affiche le formulaire d'édition du profil pour un utilisateur standard.
     */
    public function editUser(): View
    {
        $user = Auth::user()->load('role');

        return view('user.profile.edit', compact('user'));
    }

    /**
     * Met à jour les informations de base de l'utilisateur connecté.
     */
    public function updateUserProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'first_name'    => ['nullable', 'string', 'max:255'],
            'last_name'     => ['nullable', 'string', 'max:255'],
            'username'      => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'phone'         => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'bio'           => ['nullable', 'string', 'max:1000'],
            'avatar'        => ['nullable', 'url', 'max:500'],
            'locale'        => ['required', 'in:fr,en'],
            'timezone'      => ['required', 'string'],
            'password'      => ['nullable', 'min:8', 'confirmed'],
        ]);

        $data = $request->only([
            'name', 'email', 'first_name', 'last_name', 'username',
            'phone', 'date_of_birth', 'bio', 'avatar', 'locale', 'timezone',
        ]);

        if (! empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        // Normaliser les champs optionnels vides en null
        foreach (['username', 'first_name', 'last_name', 'avatar', 'bio', 'phone', 'date_of_birth'] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        $user->update($data);

        return redirect()->route('user.profile.edit')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}