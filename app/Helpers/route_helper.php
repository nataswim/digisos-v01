<?php

/**
 * RouteHelper - Helpers pour les routes et redirections
 * 
 * @file app/Helpers/route_helper.php
 */

if (!function_exists('get_dashboard_route')) {
    function get_dashboard_route(): string
    {
        if (!auth()->check()) {
            return route('home');
        }

        $user = auth()->user();

        if (!$user->role) {
            return route('home');
        }

        if ($user->hasRole('admin')) {
            return route('admin.dashboard');
        }

        if ($user->hasRole('editor')) {
            return route('editor.dashboard');
        }

        if ($user->hasRole('user')) {
            return route('user.dashboard');
        }

        return route('visitor.dashboard');
    }
}

if (!function_exists('redirect_to_dashboard')) {
    function redirect_to_dashboard()
    {
        return redirect(get_dashboard_route());
    }
}

if (!function_exists('get_dashboard_route_name')) {
    function get_dashboard_route_name(): string
    {
        if (!auth()->check()) {
            return 'home';
        }

        $user = auth()->user();

        if (!$user->role) {
            return 'home';
        }

        if ($user->hasRole('admin')) {
            return 'admin.dashboard';
        }

        if ($user->hasRole('editor')) {
            return 'editor.dashboard';
        }

        if ($user->hasRole('user')) {
            return 'user.dashboard';
        }

        return 'visitor.dashboard';
    }
}

if (!function_exists('can_access_admin')) {
    function can_access_admin(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        return $user->hasRole('admin') || $user->hasRole('editor');
    }
}

if (!function_exists('get_user_role_badge')) {
    function get_user_role_badge($user = null): string
    {
        $user = $user ?? auth()->user();

        if (!$user || !$user->role) {
            return '<span class="badge bg-secondary">Sans rôle</span>';
        }

        $colors = [
            'admin' => 'danger',
            'editor' => 'primary',
            'user' => 'success',
            'visitor' => 'warning',
        ];

        $slug = $user->role->slug;
        $color = $colors[$slug] ?? 'secondary';
        $name = $user->role->display_name ?? ucfirst($slug);

        return sprintf('<span class="badge bg-%s">%s</span>', $color, $name);
    }
}