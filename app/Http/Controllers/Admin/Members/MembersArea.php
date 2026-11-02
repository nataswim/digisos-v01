<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Base du module Adhérents : les mêmes contrôleurs servent l'administration (admin.members.*, gabarit admin)
 * et l'espace éditeur (editor.members.*, gabarit éditeur). Accès : administrateurs et éditeurs.
 */
abstract class MembersArea extends Controller
{
    protected function area(): string
    {
        return request()->routeIs('editor.*') ? 'editor' : 'admin';
    }

    /** membersRoute('index') → « admin.members.index » */
    protected function membersRoute(string $name): string
    {
        return $this->area() . '.members.' . $name;
    }

    protected function membersView(string $view, array $data = []): View
    {
        return view('admin.members.' . $view, $data + [
            'area' => $this->area(),
            'layout' => 'layouts.' . $this->area(),
        ]);
    }
}
