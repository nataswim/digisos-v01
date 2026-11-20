<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Page publique « Inscription » (/plans-inscription). */
class PricingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_page_inscription_presente_les_activites_et_les_liens(): void
    {
        $this->get(route('pricing'))->assertOk()
            ->assertDontSee('<video', false)
            ->assertSee('Adhésion pour une saison')
            ->assertSeeInOrder(['École de natation', 'Jeunes', '8 à 25 ans', 'Étudiants stagiaires', 'Sous conditions', 'Maîtres', '25 ans et plus', 'Maîtres ligue Arc Atlantique'])
            ->assertSee("Adhésion selon l'activité", false)
            ->assertDontSee('Remises famille')
            ->assertDontSee('Réinscription tardive')
            ->assertDontSee('saison complète')
            ->assertSee(url('/fiches/inscription-adhesion'), false)
            ->assertSee(url('/ebook/documents-inscription'), false)
            ->assertSee('La boutique du club')
            ->assertSee(route('shop.index'), false)
            ->assertSee(route('contact'), false);
    }

    public function test_les_autres_pages_gardent_la_video_d_en_tete(): void
    {
        $this->get(route('about'))->assertOk()->assertSee('<video', false);
    }

    public function test_la_page_inscription_renvoie_vers_les_informations_du_club(): void
    {
        $cat = \App\Models\PagesCategory::create(['name' => 'Nos groupes', 'slug' => 'nos-groupes', 'description' => 'Le parcours du nageur et chaque groupe du club.', 'is_active' => true, 'sort_order' => 1]);
        \App\Models\PagesCategory::create(['name' => 'Rubrique masquée', 'slug' => 'masquee', 'is_active' => false, 'sort_order' => 2]);
        foreach ([true, true, false] as $i => $publie) {
            \App\Models\Page::create(['title' => 'Page fictive ' . $i, 'slug' => 'page-fictive-' . $i, 'short_description' => 'Résumé fictif.', 'long_description' => 'Contenu fictif.', 'pages_category_id' => $cat->id, 'is_published' => $publie]);
        }

        $this->get(route('pricing'))->assertOk()
            ->assertSee('Informations du club')
            ->assertSee('Les informations, par thème')
            ->assertSee('Nos groupes')->assertSee('Le parcours du nageur et chaque groupe du club.')
            ->assertSee('2 pages')
            ->assertSee(route('public.pages.category', $cat), false)
            ->assertSee(route('public.pages.index'), false)
            ->assertDontSee('Rubrique masquée');
    }
}
