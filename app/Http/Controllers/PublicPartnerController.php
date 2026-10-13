<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Partner;
use Illuminate\Contracts\View\View;

/**
 * Pages publiques du module Partenaires :
 *   /partenaires          logos regroupés par niveau (principal, or, argent, bronze)
 *   /partenaires/{slug}   fiche détaillée d'un partenaire
 */
class PublicPartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partner::active()->ordered()->get();

        // Un bloc par niveau, dans l'ordre de Partner::LEVELS ; les niveaux vides ne sont pas affichés
        $blocks = collect(Partner::LEVELS)
            ->map(fn ($info, $level) => ['info' => $info, 'partners' => $partners->where('level', $level)->values()])
            ->filter(fn ($block) => $block['partners']->isNotEmpty());

        return view('public.partners.index', [
            'blocks' => $blocks,
            'total' => $partners->count(),
            'becomePartnerUrl' => $this->becomePartnerUrl(),
        ]);
    }

    public function show(Partner $partner): View
    {
        abort_unless($partner->is_active, 404);

        $others = Partner::active()
            ->where('level', $partner->level)
            ->whereKeyNot($partner->id)
            ->ordered()
            ->limit(6)
            ->get();

        return view('public.partners.show', [
            'partner' => $partner,
            'others' => $others,
            'becomePartnerUrl' => $this->becomePartnerUrl(),
        ]);
    }

    /**
     * Page « Devenir partenaire » créée par PagesOrganisationSeeder.
     * Tant qu'elle n'est pas publiée, le lien mène au formulaire de contact.
     */
    private function becomePartnerUrl(): string
    {
        $page = Page::with('category')->where('slug', 'devenir-partenaire')->where('is_published', true)->first();

        if ($page && $page->category) {
            return route('public.pages.show', [$page->category->slug, $page->slug]);
        }

        return route('contact');
    }
}
