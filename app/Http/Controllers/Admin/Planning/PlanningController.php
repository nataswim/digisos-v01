<?php

namespace App\Http\Controllers\Admin\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Base commune du module Planning.
 *
 * Les mêmes contrôleurs servent l'espace admin (routes « admin.planning.* », gabarit layouts.admin)
 * et l'espace éditeur (routes « editor.planning.* », gabarit layouts.editor) :
 * les vues reçoivent $area et $layout pour construire leurs liens.
 */
abstract class PlanningController extends Controller
{
    protected function area(): string
    {
        return request()->routeIs('editor.*') ? 'editor' : 'admin';
    }

    /** Nom de route complet : planningRoute('groups.index') → « admin.planning.groups.index » */
    protected function planningRoute(string $name): string
    {
        return $this->area() . '.planning.' . $name;
    }

    protected function planningView(string $view, array $data = []): View
    {
        return view('admin.planning.' . $view, $data + [
            'area' => $this->area(),
            'layout' => 'layouts.' . $this->area(),
        ]);
    }
}
