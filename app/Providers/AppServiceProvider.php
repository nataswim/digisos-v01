<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       // Configuration de la pagination Bootstrap 5
        Paginator::defaultView('pagination::bootstrap-5');
        Paginator::defaultSimpleView('pagination::simple-bootstrap-5');

Gate::define('access-admin', fn (User $user) =>
        $user->status === 'active' && $user->hasRole('admin')
    );

    Gate::define('access-editor', fn (User $user) =>
        $user->status === 'active' && in_array($user->role?->slug, ['admin', 'editor'], true)
    );

    }
}
