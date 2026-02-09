<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;

/**
 * 🇬🇧 User Management Controller
 * 🇫🇷 Contrôleur de gestion des utilisateurs
 * 
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * 🇬🇧 Check if current user has admin role
     * 🇫🇷 Vérifier si l'utilisateur actuel a le rôle admin
     * 
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function checkAdminAccess(): void
    {
        $user = Auth::user();
        
        // 🇬🇧 Check if user is authenticated / 🇫🇷 Vérifier si l'utilisateur est authentifié
        if (!$user) {
            abort(403, 'Authentification requise');
        }
        
        // 🇬🇧 Check if user has admin role / 🇫🇷 Vérifier si l'utilisateur a le rôle admin
        if (!$user->role || $user->role->slug !== 'admin') {
            abort(403, 'Accès non autorisé - Rôle administrateur requis');
        }
    }

    /**
     * 🇬🇧 Display users list
     * 🇫🇷 Afficher la liste des utilisateurs
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $search = $request->input('search');
        $query = User::with('role');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $roles = Role::orderBy('level', 'asc')->get(); // 🇬🇧 For role dropdown / 🇫🇷 Pour le menu déroulant des rôles

        return view('admin.users.index', compact('users', 'search', 'roles'));
    }

    /**
     * 🇬🇧 Show create user form
     * 🇫🇷 Afficher le formulaire de création d'utilisateur
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->checkAdminAccess();

        $roles = Role::orderBy('level', 'asc')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * 🇬🇧 Store new user
     * 🇫🇷 Enregistrer un nouvel utilisateur
     * 
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $this->checkAdminAccess();

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // 🇬🇧 Set default values / 🇫🇷 Définir des valeurs par défaut
        $data = array_merge([
            'locale' => 'fr',
            'timezone' => 'Europe/Paris',
            'status' => 'active',
        ], $data);

        // 🇬🇧 Clean empty values / 🇫🇷 Nettoyer les valeurs vides
        if (empty($data['locale'])) {
            $data['locale'] = 'fr';
        }

        if (empty($data['timezone'])) {
            $data['timezone'] = 'Europe/Paris';
        }

        if (empty($data['status'])) {
            $data['status'] = 'active';
        }

        // 🇬🇧 Assign default role if not specified / 🇫🇷 Assigner le rôle par défaut si non spécifié
        if (empty($data['role_id'])) {
            $defaultRole = Role::where('is_default', true)->first();
            $data['role_id'] = $defaultRole?->id;
        }

        // 🇬🇧 Clean optional empty fields / 🇫🇷 Nettoyer les champs optionnels vides
        $optionalFields = ['username', 'first_name', 'last_name', 'avatar', 'bio', 'phone', 'date_of_birth'];
        foreach ($optionalFields as $field) {
            if (isset($data[$field]) && empty($data[$field])) {
                $data[$field] = null;
            }
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * 🇬🇧 Show user details
     * 🇫🇷 Afficher les détails d'un utilisateur
     * 
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $this->checkAdminAccess();

        $user->load('role');
        return view('admin.users.show', compact('user'));
    }

    /**
     * 🇬🇧 Show edit user form
     * 🇫🇷 Afficher le formulaire d'édition d'utilisateur
     * 
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $this->checkAdminAccess();

        $roles = Role::orderBy('level', 'asc')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * 🇬🇧 Update user
     * 🇫🇷 Mettre à jour un utilisateur
     * 
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->checkAdminAccess();

        $data = $request->validated();

        // 🇬🇧 Password handling / 🇫🇷 Gestion du mot de passe
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // 🇬🇧 Set default values / 🇫🇷 Définir des valeurs par défaut
        if (empty($data['locale'])) {
            $data['locale'] = 'fr';
        }

        if (empty($data['timezone'])) {
            $data['timezone'] = 'Europe/Paris';
        }

        if (empty($data['status'])) {
            $data['status'] = 'active';
        }

        // 🇬🇧 Clean optional empty fields / 🇫🇷 Nettoyer les champs optionnels vides
        $optionalFields = ['username', 'first_name', 'last_name', 'avatar', 'bio', 'phone', 'date_of_birth'];
        foreach ($optionalFields as $field) {
            if (isset($data[$field]) && empty($data[$field])) {
                $data[$field] = null;
            }
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * 🇬🇧 Delete user
     * 🇫🇷 Supprimer un utilisateur
     * 
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->checkAdminAccess();

        // 🇬🇧 Prevent admin from deleting themselves / 🇫🇷 Empêcher l'admin de se supprimer
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * 🇬🇧 Update user role (AJAX or form)
     * 🇫🇷 Mettre à jour le rôle d'un utilisateur (AJAX ou formulaire)
     * 
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, User $user)
    {
        $this->checkAdminAccess();

        // 🇬🇧 Validation / 🇫🇷 Validation
        $validated = $request->validate([
            'role_id' => 'nullable|exists:roles,id'
        ]);

        // 🇬🇧 Prevent admin from changing their own role / 🇫🇷 Empêcher l'admin de changer son propre rôle
        if ($user->id === Auth::id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas modifier votre propre rôle.'
                ], 403);
            }

            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        try {
            // 🇬🇧 Update role / 🇫🇷 Mise à jour du rôle
            $user->update([
                'role_id' => $validated['role_id']
            ]);

            // 🇬🇧 Reload role relationship / 🇫🇷 Recharger la relation role
            $user->load('role');

            // 🇬🇧 AJAX response / 🇫🇷 Réponse AJAX
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rôle mis à jour avec succès.',
                    'role' => $user->role ? [
                        'id' => $user->role->id,
                        'display_name' => $user->role->display_name,
                        'slug' => $user->role->slug,
                        'level' => $user->role->level
                    ] : null
                ]);
            }

            // 🇬🇧 Classic redirect / 🇫🇷 Redirection classique
            $roleName = $user->role?->display_name ?? 'Aucun rôle';
            return redirect()->route('admin.users.index')
                ->with('success', "Le rôle de {$user->name} a été mis à jour : {$roleName}");
                
        } catch (\Exception $e) {
            // 🇬🇧 Error handling / 🇫🇷 Gestion des erreurs
            \Log::error('Erreur updateRole: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la mise à jour.',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du rôle.');
        }
    }

    /**
     * 🇬🇧 Bulk actions on users
     * 🇫🇷 Actions groupées sur les utilisateurs
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkAction(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $validated['user_ids'];
        $action = $validated['action'];

        // 🇬🇧 Prevent admin from affecting themselves / 🇫🇷 Empêcher l'admin de s'affecter lui-même
        $userIds = array_filter($userIds, fn($id) => $id != Auth::id());

        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                ->with('warning', 'Aucun utilisateur sélectionné ou vous ne pouvez pas vous affecter vous-même.');
        }

        switch ($action) {
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