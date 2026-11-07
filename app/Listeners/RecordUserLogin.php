<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

/**
 * Enregistre chaque connexion : date (colonne « Dernière connexion » de Administration → Utilisateurs),
 * adresse IP et nombre de connexions. Mise à jour directe en base, sans modifier « updated_at ».
 * Écouteur découvert automatiquement par Laravel (dossier app/Listeners).
 */
class RecordUserLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        if (! $user || ! isset($user->id)) {
            return;
        }

        try {
            DB::table('users')->where('id', $user->id)->update([
                'last_login_at' => now(),
                'last_login_ip' => request()?->ip(),
                'login_count' => DB::raw('COALESCE(login_count, 0) + 1'),
            ]);
        } catch (\Throwable $e) {
            report($e);   // une erreur ici ne doit jamais empêcher la connexion
        }
    }
}
