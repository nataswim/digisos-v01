<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserProfileController extends Controller
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
    // Liste de tous les utilisateurs avec l'état de leur fiche
    // -------------------------------------------------------------------------

    public function index(): View
    {
        $this->checkAdminAccess();

        $users = User::with('userProfile', 'role')
                     ->orderBy('name')
                     ->paginate(20);

        return view('admin.user-profiles.index', compact('users'));
    }

    // -------------------------------------------------------------------------
    // Affichage de la fiche d'un utilisateur (avec ses items)
    // -------------------------------------------------------------------------

    public function show(User $user): View
    {
        $this->checkAdminAccess();

        $user->load('role', 'userProfile.items');

        return view('admin.user-profiles.show', compact('user'));
    }

    // -------------------------------------------------------------------------
    // Formulaire de création d'une fiche pour un utilisateur
    // -------------------------------------------------------------------------

    public function create(Request $request): View
    {
        $this->checkAdminAccess();

        // Si un user_id est passé en query string, on pré-sélectionne l'utilisateur
        $selectedUser = null;

        if ($request->filled('user_id')) {
            $selectedUser = User::findOrFail($request->integer('user_id'));

            // Rediriger vers l'édition si une fiche existe déjà
            if ($selectedUser->userProfile) {
                return redirect()
                    ->route('admin.user-profiles.edit', $selectedUser)
                    ->with('warning', 'Cet utilisateur possède déjà une fiche. Vous pouvez la modifier.');
            }
        }

        $users = User::whereDoesntHave('userProfile')
                     ->orderBy('name')
                     ->get();

        return view('admin.user-profiles.create', compact('users', 'selectedUser'));
    }

    // -------------------------------------------------------------------------
    // Enregistrement d'une nouvelle fiche
    // -------------------------------------------------------------------------

    public function store(Request $request): RedirectResponse
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'user_id'      => ['required', 'integer', 'exists:users,id', 'unique:user_profiles,user_id'],
            'job_title'    => ['nullable', 'string', 'max:150'],
            'address'      => ['nullable', 'string', 'max:255'],
            'website'      => ['nullable', 'url', 'max:255'],
            'admin_notes'  => ['nullable', 'string', 'max:5000'],
            'is_visible'   => ['boolean'],
        ], [
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'user_id.unique'   => 'Cet utilisateur possède déjà une fiche.',
            'user_id.exists'   => 'Utilisateur introuvable.',
            'website.url'      => 'Le site web doit être une URL valide (ex: https://exemple.fr).',
        ]);

        $validated['is_visible'] = $request->boolean('is_visible', true);

        $profile = UserProfile::create($validated);

        return redirect()
            ->route('admin.user-profiles.show', $profile->user)
            ->with('success', 'Fiche créée avec succès.');
    }

    // -------------------------------------------------------------------------
    // Formulaire d'édition d'une fiche existante
    // -------------------------------------------------------------------------

    public function edit(User $user): View
    {
        $this->checkAdminAccess();

        // Créer automatiquement une fiche vide si elle n'existe pas encore
        $profile = $user->userProfile ?? UserProfile::create([
            'user_id'    => $user->id,
            'is_visible' => true,
        ]);

        $user->load('role');

        return view('admin.user-profiles.edit', compact('user', 'profile'));
    }

    // -------------------------------------------------------------------------
    // Mise à jour d'une fiche existante
    // -------------------------------------------------------------------------

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'job_title'   => ['nullable', 'string', 'max:150'],
            'address'     => ['nullable', 'string', 'max:255'],
            'website'     => ['nullable', 'url', 'max:255'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
            'is_visible'  => ['boolean'],
        ], [
            'website.url' => 'Le site web doit être une URL valide (ex: https://exemple.fr).',
        ]);

        $validated['is_visible'] = $request->boolean('is_visible', true);

        // Créer ou mettre à jour la fiche
        $user->userProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()
            ->route('admin.user-profiles.show', $user)
            ->with('success', 'Fiche mise à jour avec succès.');
    }

    // -------------------------------------------------------------------------
    // Suppression d'une fiche (et de ses items par cascade)
    // -------------------------------------------------------------------------

    public function destroy(User $user): RedirectResponse
    {
        $this->checkAdminAccess();

        $user->userProfile?->delete();

        return redirect()
            ->route('admin.user-profiles.index')
            ->with('success', 'Fiche supprimée avec succès.');
    }
}
