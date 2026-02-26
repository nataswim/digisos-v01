<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Fiche;
use App\Models\Page;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $stats = [
            'users'     => User::count(),
            'posts'     => Post::count(),
            'fiches'    => Fiche::count(),
            'pages'     => Page::count(),
            'medias'    => Media::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentPosts = Post::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentPosts'));
    }
}
