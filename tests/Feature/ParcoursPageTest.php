<?php

namespace Tests\Feature;

use App\Models\PlanningGroup;
use App\Models\PlanningSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParcoursPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_page_affiche_les_cinq_etapes_et_les_groupes(): void
    {
        $this->get(route('parcours'))
            ->assertOk()
            ->assertSee('Le parcours du nageur')
            ->assertSee('Je découvre')
            ->assertSee('Je m&#039;approprie', false)
            ->assertSee('J&#039;excelle', false)
            ->assertSee('Initiation Bleu')
            ->assertSee('Pré-ligue')
            ->assertSee('Trouver mon groupe')
            ->assertSee('ffnatation.fr/PDLTA-main', false);
    }

    public function test_les_creneaux_viennent_du_module_planning(): void
    {
        $groupe = PlanningGroup::create([
            'name' => 'Relève 1 (9 à 12 ans)',
            'section' => 'Relève',
            'is_active' => true,
        ]);
        PlanningSlot::create([
            'planning_group_id' => $groupe->id,
            'period' => 'scolaire',
            'day_of_week' => 3,
            'starts_at' => '17:00',
            'ends_at' => '18:00',
            'is_active' => true,
        ]);

        $this->get(route('parcours'))
            ->assertOk()
            ->assertSee('#groupe-'.$groupe->slug, false)
            ->assertSee('17 h');
    }

    public function test_la_page_est_dans_le_plan_du_site_et_le_menu(): void
    {
        $this->get(route('home'))->assertSee(route('parcours'), false);
    }
}
