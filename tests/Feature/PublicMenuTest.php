<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Menu public : rubriques Vie du club, Compétition, Nous rejoindre. Lancer : php artisan test --filter=PublicMenuTest */
class PublicMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_menu_public_a_la_nouvelle_structure(): void
    {
        $html = $this->get(route('pricing'))->assertOk()->getContent();

        $this->assertStringContainsString('id="competitionDropdown"', $html);
        $this->assertStringContainsString('Nous rejoindre', $html);
        $this->assertStringNotContainsString('id="clubDropdown"', $html);
        $this->assertStringNotContainsString('id="calendrierDropdown"', $html);

        // Les pages de l'ancien « Le Club » sont dans Vie du club, les outils dans Compétition
        $vie = substr($html, strpos($html, 'id="vieDuClubDropdown"'), strpos($html, 'id="competitionDropdown"') - strpos($html, 'id="vieDuClubDropdown"'));
        foreach (['about', 'public.pages.index', 'team.index', 'public.installations.index', 'contact'] as $r) {
            $this->assertStringContainsString('href="' . route($r) . '"', $vie);
        }
        $this->assertStringNotContainsString('href="' . route('tools.index') . '"', $vie);
        $compet = substr($html, strpos($html, 'id="competitionDropdown"'), strpos($html, 'id="creneauxDropdown"') - strpos($html, 'id="competitionDropdown"'));
        $this->assertStringContainsString('href="' . route('tools.index') . '"', $compet);
        $this->assertStringContainsString('--menu-color', $html);
    }
}
