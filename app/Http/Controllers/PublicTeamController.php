<?php

namespace App\Http\Controllers;

use App\Models\TeamSection;
use Illuminate\Contracts\View\View;

/**
 * Page publique « L'équipe du club » (/equipe-du-club) : toutes les sections sur une seule page.
 */
class PublicTeamController extends Controller
{
    public function index(): View
    {
        $sections = TeamSection::active()
            ->ordered()
            ->with(['members' => fn ($q) => $q->where('team_members.is_active', true)])
            ->get()
            ->filter(fn ($section) => $section->members->isNotEmpty())
            ->values();

        return view('public.team.index', compact('sections'));
    }
}
