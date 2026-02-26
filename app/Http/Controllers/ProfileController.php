<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    private function checkAdminAccess()
    {
        //if (!auth()->user()->hasRole('admin')) {
if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('editor')) {
            abort(403, 'Acces non autorise');
        }
    }

    // MeTHODES ADMIN (existantes)
    public function show()
    {
        $this->checkAdminAccess();
        
        $user = Auth::user()->load('role');
        return view('admin.profile.show', compact('user'));
    }

    public function edit()
    {
        $this->checkAdminAccess();
        
        $user = Auth::user()->load('role');
        return view('admin.profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->checkAdminAccess();
        
        $user = Auth::user();
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profil mis A jour avec succes.');
    }

    public function destroy(Request $request)
    {
        $this->checkAdminAccess();
        
        $user = Auth::user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Votre compte a ete supprime.');
    }

    // NOUVELLE MeTHODE POUR LES UTILISATEURS NORMAUX
    public function updateUserProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . auth()->id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'url', 'max:500'],
            'locale' => ['required', 'in:fr,en'],
            'timezone' => ['required', 'string'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $data = $request->only([
            'name', 'email', 'first_name', 'last_name', 'username', 
            'phone', 'date_of_birth', 'bio', 'avatar', 'locale', 'timezone'
        ]);

        // Gestion du mot de passe
        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        // Nettoyer les champs optionnels vides
        $optionalFields = ['username', 'first_name', 'last_name', 'avatar', 'bio', 'phone', 'date_of_birth'];
        foreach ($optionalFields as $field) {
            if (isset($data[$field]) && empty($data[$field])) {
                $data[$field] = null;
            }
        }

        $user->update($data);

        return redirect()->route('user.profile.edit')
            ->with('success', 'Profil mis A jour avec succes.');
    }
}