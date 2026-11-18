<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolsPagesTest extends TestCase
{
    use RefreshDatabase;

    private const TOOLS = [
        'imc', 'masse-grasse', 'calories', 'tdee', 'kcal-macros', 'hydratation',
        'chronometre', 'chronometre-pro', 'vnc', 'predicteur', 'planificateur-natation', 'efficacite-technique',
        'zones-cardiaques', 'coherence-cardiaque', 'planificateur-course', 'charge-maximale', 'condition-physique',
        'carte-interactive', 'planificateur-triathlon',
    ];

    public function test_la_page_des_outils_liste_tous_les_outils(): void
    {
        $page = $this->get(route('tools.index'))->assertOk()
            ->assertSee('Outils &amp; Calculateurs', false)
            ->assertSee('https://nataswim.fr/outils', false)
            ->assertSee('https://nataswim.fr/about', false)
            ->assertSee('hassan-elhaouat-2010.png', false)
            ->assertDontSee('public-navbar', false)
            ->assertDontSee('<video', false);

        foreach (self::TOOLS as $tool) {
            $page->assertSee(route('tools.'.$tool), false);
        }
    }

    public function test_chaque_outil_s_affiche_avec_le_credit_et_sans_video(): void
    {
        foreach (self::TOOLS as $tool) {
            $this->get(route('tools.'.$tool))->assertOk()
                ->assertSee('Hassan EL HAOUAT')
                ->assertSee('Hassan EL HAOUAT (SNS)')
                ->assertSee('(2015 - 2017)')
                ->assertSee('hassan-elhaouat-2002.png', false)
                ->assertDontSee('public-navbar', false)
                ->assertDontSee('Tous droits réservés')
                ->assertSee('https://nataswim.fr/outils', false)
                ->assertSee(route('tools.index'), false)
                ->assertDontSee('<video', false);
        }
    }

    public function test_le_menu_public_propose_les_outils(): void
    {
        $this->get(route('home'))->assertOk()->assertSee(route('tools.index'), false);
    }
}
