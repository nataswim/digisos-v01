<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    // -------------------------------------------------------------------------
    // Contrôle d'accès
    // -------------------------------------------------------------------------

    private function checkAdminAccess(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Authentification requise.');
        }

        if (! $user->role || $user->role->slug !== 'admin') {
            abort(403, 'Accès non autorisé — Rôle administrateur requis.');
        }
    }

    // -------------------------------------------------------------------------
    // CRUD
    // -------------------------------------------------------------------------

    public function index(Request $request): View
    {
        $this->checkAdminAccess();

        $search = $request->input('search');
        $query  = User::with('role');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $roles = Role::orderBy('level', 'asc')->get();

        return view('admin.users.index', compact('users', 'search', 'roles'));
    }

    public function create(): View
    {
        $this->checkAdminAccess();

        $roles = Role::orderBy('level', 'asc')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->checkAdminAccess();

        $data             = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // Valeurs par défaut
        $data = array_merge([
            'locale'   => 'fr',
            'timezone' => 'Europe/Paris',
            'status'   => 'active',
        ], $data);

        foreach (['locale', 'timezone', 'status'] as $field) {
            if (empty($data[$field])) {
                $data[$field] = match ($field) {
                    'locale'   => 'fr',
                    'timezone' => 'Europe/Paris',
                    default    => 'active',
                };
            }
        }

        // Rôle par défaut si absent
        if (empty($data['role_id'])) {
            $data['role_id'] = Role::where('is_default', true)->first()?->id;
        }

        // Avatar : chaîne vide → null
        if (isset($data['avatar']) && $data['avatar'] === '') {
            $data['avatar'] = null;
        }

        // Nettoyer les champs optionnels vides
        foreach (['username', 'first_name', 'last_name', 'bio', 'phone', 'date_of_birth'] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user): View
    {
        $this->checkAdminAccess();

        $user->load('role');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->checkAdminAccess();

        $roles = Role::orderBy('level', 'asc')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->checkAdminAccess();

        $data = $request->validated();

        // Gestion du mot de passe
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Valeurs par défaut
        foreach (['locale', 'timezone', 'status'] as $field) {
            if (empty($data[$field])) {
                $data[$field] = match ($field) {
                    'locale'   => 'fr',
                    'timezone' => 'Europe/Paris',
                    default    => 'active',
                };
            }
        }

        // Avatar : chaîne vide → null (suppression)
        if (isset($data['avatar']) && $data['avatar'] === '') {
            $data['avatar'] = null;
        }

        // Si le champ avatar n'est pas envoyé du tout → conserver l'existant
        if (! array_key_exists('avatar', $data)) {
            unset($data['avatar']);
        }

        // Nettoyer les champs optionnels vides
        foreach (['username', 'first_name', 'last_name', 'bio', 'phone', 'date_of_birth'] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->checkAdminAccess();

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function updateRole(Request $request, User $user): mixed
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        if ($user->id === Auth::id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas modifier votre propre rôle.',
                ], 403);
            }

            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        try {
            $user->update(['role_id' => $validated['role_id']]);
            $user->load('role');

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rôle mis à jour avec succès.',
                    'role'    => $user->role ? [
                        'id'           => $user->role->id,
                        'display_name' => $user->role->display_name,
                        'slug'         => $user->role->slug,
                        'level'        => $user->role->level,
                    ] : null,
                ]);
            }

            $roleName = $user->role?->display_name ?? 'Aucun rôle';

            return redirect()->route('admin.users.index')
                ->with('success', "Le rôle de {$user->name} a été mis à jour : {$roleName}.");

        } catch (\Exception $e) {
            \Log::error('Erreur updateRole : ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue.',
                    'error'   => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du rôle.');
        }
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'action'     => ['required', 'in:delete,activate,deactivate'],
            'user_ids'   => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $userIds = array_filter($validated['user_ids'], fn ($id) => $id != Auth::id());

        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                ->with('warning', 'Aucun utilisateur sélectionné ou vous ne pouvez pas vous affecter vous-même.');
        }

        switch ($validated['action']) {
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = count($userIds) . ' utilisateur(s) supprimé(s) avec succès.';
                break;

            case 'activate':
                User::whereIn('id', $userIds)->update(['status' => 'active']);
                $message = count($userIds) . ' utilisateur(s) activé(s) avec succès.';
                break;

            case 'deactivate':
                User::whereIn('id', $userIds)->update(['status' => 'inactive']);
                $message = count($userIds) . ' utilisateur(s) désactivé(s) avec succès.';
                break;

            default:
                $message = 'Action non reconnue.';
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
}