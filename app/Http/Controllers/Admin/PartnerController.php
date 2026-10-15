<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Support\RichText;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Gestion des partenaires (module Partenaires).
 *
 * Le même contrôleur sert l'espace admin (routes « admin.partners.* », gabarit layouts.admin)
 * et l'espace éditeur (routes « editor.partners.* », gabarit layouts.editor),
 * comme le module Planning : les vues reçoivent $area et $layout.
 */
class PartnerController extends Controller
{
    public function index(Request $request): View
    {
        $level = array_key_exists($request->query('niveau'), Partner::LEVELS) ? $request->query('niveau') : null;

        $partners = Partner::query()
            ->when($level, fn ($q) => $q->where('level', $level))
            ->ordered()
            ->get();

        $counts = Partner::selectRaw('level, count(*) as total')->groupBy('level')->pluck('total', 'level');

        return $this->view('index', compact('partners', 'level', 'counts'));
    }

    public function create(): View
    {
        return $this->view('form', [
            'partner' => new Partner([
                'level' => 'bronze',
                'is_active' => true,
                'sort_order' => (int) Partner::max('sort_order') + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $partner = new Partner($this->validated($request));
        $this->handleLogo($request, $partner);
        $partner->save();

        return redirect()->route($this->route('index'))
            ->with('success', "Partenaire « {$partner->name} » ajouté.");
    }

    public function edit(Partner $partner): View
    {
        return $this->view('form', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $partner->fill($this->validated($request, $partner));
        $this->handleLogo($request, $partner);
        $partner->save();

        return redirect()->route($this->route('index'))
            ->with('success', "Partenaire « {$partner->name} » mis à jour.");
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $name = $partner->name;
        $partner->delete();   // le logo est supprimé par le modèle

        return redirect()->route($this->route('index'))
            ->with('success', "Partenaire « {$name} » supprimé.");
    }

    // ---------------------------------------------------------------- Outils

    private function validated(Request $request, ?Partner $partner = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'level' => ['required', Rule::in(array_keys(Partner::LEVELS))],
            'category' => ['nullable', Rule::in(array_keys(Partner::CATEGORIES))],
            'summary' => ['nullable', 'string', 'max:255'],
            'presentation' => ['nullable', 'string', 'max:50000'],
            'actions' => ['nullable', 'string', 'max:20000'],
            'website' => ['nullable', 'string', 'max:255', 'url:http,https'],
            'partner_since' => ['nullable', 'integer', 'min:1954', 'max:' . (now()->year + 1)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
            // Image de la médiathèque (ou adresse d'image) …
            'logo' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/)#i'],
            // … ou fichier envoyé depuis l'ordinateur. Pas de SVG : un SVG peut contenir du script
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ], [
            'website.url' => 'Le site internet doit être une adresse complète, commençant par https://',
            'partner_since.min' => 'L\'année ne peut pas être antérieure à la création du club (1954).',
            'logo.regex' => 'Le logo doit être choisi dans la médiathèque ou être une adresse commençant par https://',
            'logo_file.image' => 'Le logo doit être une image (JPG, PNG, WebP ou GIF).',
            'logo_file.mimes' => 'Le logo doit être une image JPG, PNG, WebP ou GIF.',
            'logo_file.max' => 'Le logo ne doit pas dépasser 2 Mo.',
        ]);

        unset($data['logo'], $data['logo_file']);
        // Éditeur vide (« <p><br></p> ») = champ vide
        foreach (['presentation', 'actions'] as $field) {
            if (RichText::isEmpty($data[$field] ?? null)) {
                $data[$field] = null;
            }
        }
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    /**
     * Logo : fichier envoyé depuis l'ordinateur (prioritaire), sinon image choisie dans la médiathèque,
     * ou case « retirer le logo ». L'ancien fichier envoyé pour ce partenaire est supprimé ;
     * une image de la médiathèque, elle, n'est jamais supprimée.
     */
    private function handleLogo(Request $request, Partner $partner): void
    {
        $ancien = $partner->getOriginal('logo');

        if ($request->hasFile('logo_file')) {
            $nouveau = '/storage/' . $request->file('logo_file')->store(Partner::LOGO_DIR, 'public');
        } elseif ($request->boolean('remove_logo')) {
            $nouveau = null;
        } else {
            $nouveau = trim((string) $request->input('logo')) ?: null;
        }

        if ($ancien && $ancien !== $nouveau) {
            $partner->deleteLogoFile($ancien);
        }

        $partner->logo = $nouveau;
    }

    private function area(): string
    {
        return request()->routeIs('editor.*') ? 'editor' : 'admin';
    }

    private function route(string $name): string
    {
        return $this->area() . '.partners.' . $name;
    }

    private function view(string $view, array $data = []): View
    {
        return view('admin.partners.' . $view, $data + [
            'area' => $this->area(),
            'layout' => 'layouts.' . $this->area(),
        ]);
    }
}
