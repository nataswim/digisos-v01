<?php

namespace Tests\Feature;

use App\Models\PlanningSwimmer;
use App\Models\QualificationGrid;
use App\Models\Role;
use App\Models\SwimPerformance;
use App\Models\User;
use App\Services\QualificationGridParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Module « Grilles de qualification ». Données fictives uniquement.
 * Lancer : php artisan test --filter=QualificationGridsTest
 */
class QualificationGridsTest extends TestCase
{
    use RefreshDatabase;

    /** Copie de PDF fictive : en-tête, épreuve entre les lignes Dames et Messieurs, formats de temps irréguliers. */
    private const PDF = "Grille fictive\n"
        ."Catégories     A1 (2014)               A2 (2013 et -)\n"
        ."BASSIN   25m    50m    25m   50m\n"
        ."   Dames   00:34.63   00:35.33   00.32.65   0:33,35\n"
        ."        50 nage libre\n"
        ."   Messieurs   00:33.97   00:34.67   31.68   00:32.38\n"
        ."   Dames                 11:25.03     11:37.93\n"
        ."        800 nage libre\n"
        ."   Messieurs             11:21.16     1;37.06\n"
        ."   Dames   03:10.36   03:13.76   03:.03.25   3.07.62\n"
        ."        200 4 nages\n"
        ."   Messieurs   03:09.11   03:13.21   03:03.25   03:07.35\n";

    private function account(string $role): User
    {
        $r = Role::firstOrCreate(['slug' => $role], ['name' => ucfirst($role), 'display_name' => $role, 'level' => 0]);
        $u = User::create(['name' => "Test {$role}", 'email' => "{$role}@example.test", 'password' => bcrypt('secret-password'), 'role_id' => $r->id, 'status' => 'active']);
        $u->forceFill(['email_verified_at' => now()])->save();

        return $u;
    }

    public function test_le_lecteur_comprend_les_formats_de_temps_irreguliers(): void
    {
        $p = new QualificationGridParser;
        $this->assertSame(2896, $p->time('00:28,96'));
        $this->assertSame(2896, $p->time('00.28.96'));
        $this->assertSame(14871, $p->time('2.28.71'));
        $this->assertSame(8596, $p->time('1;25.96'));
        $this->assertSame(3582, $p->time('35.82'));
        $this->assertSame(18325, $p->time('03:.03.25'));
        $this->assertNull($p->time('2014'));
        $this->assertNull($p->time('U12'));
        $this->assertSame('50 NL', $p->event('50 nage libre'));
        $this->assertSame('200 4N', $p->event('200 4 nages'));
        $this->assertSame('100 Pap', $p->event('100 Pap.'));
        $this->assertNull($p->event('4x50 nage libre'));
    }

    public function test_le_lecteur_lit_une_copie_de_pdf(): void
    {
        $r = (new QualificationGridParser)->parse(self::PDF);

        $this->assertSame([['label' => 'A1', 'from' => 2014, 'to' => 2014], ['label' => 'A2', 'from' => null, 'to' => 2013]], $r['categories']);
        $this->assertCount(6, $r['rows']);
        $nl = collect($r['rows'])->firstWhere(fn ($x) => $x['event'] === '50 NL' && $x['gender'] === 'F');
        $this->assertSame([3463, 3533, 3265, 3335], $nl['cells']);
        // 800 NL : cases vides de la première catégorie, temps placés à droite
        $huit = collect($r['rows'])->firstWhere(fn ($x) => $x['event'] === '800 NL' && $x['gender'] === 'M');
        $this->assertSame([null, null, 68116, 9706], $huit['cells']);
        $this->assertNotEmpty($r['warnings']);
    }

    public function test_un_tableur_garde_les_cases_vides(): void
    {
        $texte = "50 NL\tDames\t\t35.33\t32.65\t33.35\n\tMessieurs\t33.97\t\t31.68\t32.38\n";
        $r = (new QualificationGridParser)->parse($texte, 2);
        $this->assertSame([null, 3533, 3265, 3335], $r['rows'][0]['cells']);
        $this->assertSame([3397, null, 3168, 3238], $r['rows'][1]['cells']);
    }

    public function test_seul_l_administrateur_gere_les_grilles(): void
    {
        $this->actingAs($this->account('coach'))->get(route('admin.qualifications.index'))->assertForbidden();
        $this->actingAs($this->account('editor'))->get(route('admin.qualifications.index'))->assertForbidden();
        $this->actingAs($this->account('admin'))->get(route('admin.qualifications.index'))->assertOk();
    }

    public function test_creation_import_et_saisie_des_temps(): void
    {
        $this->actingAs($this->account('admin'));

        $this->post(route('admin.qualifications.store'), [
            'name' => 'Grille fictive départementale',
            'description' => 'Exemple',
            'period_start' => '2026-09-01',
            'period_end' => '2027-03-15',
            'is_published' => '1',
            'categories' => [['label' => 'A1', 'from' => 2014, 'to' => 2014]],
        ])->assertRedirect();

        $grid = QualificationGrid::firstOrFail();
        $this->assertSame('grille-fictive-departementale', $grid->slug);
        $this->assertCount(1, $grid->categories);

        // Aperçu : rien n'est enregistré
        $this->post(route('admin.qualifications.import.store', $grid), ['text' => self::PDF, 'pools' => '25-50', 'mode' => 'merge'])
            ->assertOk()->assertSee('Aperçu')->assertSee('nouvelle');
        $this->assertSame(0, $grid->times()->count());

        // Import : A1 existante réutilisée, A2 créée
        $this->post(route('admin.qualifications.import.store', $grid), ['text' => self::PDF, 'pools' => '25-50', 'mode' => 'merge', 'confirm' => '1'])
            ->assertRedirect(route('admin.qualifications.times', $grid));
        $grid->refresh();
        $this->assertSame(['A1', 'A2'], $grid->categories->pluck('label')->all());
        $this->assertSame(20, $grid->times()->count());

        // Page de saisie
        $this->get(route('admin.qualifications.times', $grid))->assertOk()->assertSee('34.63');

        // Saisie manuelle : un temps invalide est refusé
        $a1 = $grid->categories->first();
        $this->put(route('admin.qualifications.times.update', $grid), ['times' => [$a1->id => ['50 NL' => ['F' => [25 => 'abc']]]]])
            ->assertSessionHasErrors();
        $this->put(route('admin.qualifications.times.update', $grid), ['times' => [$a1->id => ['50 NL' => ['F' => [25 => '0:34,10']]]]])
            ->assertRedirect();
        $this->assertSame(1, $grid->times()->count());
        $this->assertSame(3410, $grid->times()->first()->time_cs);
    }

    public function test_page_publique_sans_nom_de_nageur_et_brouillon_invisible(): void
    {
        $grid = QualificationGrid::create(['name' => 'Grille publique fictive', 'is_published' => true]);
        $cat = $grid->categories()->create(['label' => 'A1', 'birth_year_from' => 2014, 'birth_year_to' => 2014]);
        $grid->times()->create(['qualification_category_id' => $cat->id, 'event' => '50 NL', 'gender' => 'F', 'pool' => 25, 'time_cs' => 3463]);
        $brouillon = QualificationGrid::create(['name' => 'Brouillon fictif', 'is_published' => false]);

        PlanningSwimmer::create(['first_name' => 'Ondine', 'last_name' => 'FICTIF', 'birth_year' => 2014, 'gender' => 'F', 'is_active' => true]);

        $this->get(route('qualifications.index'))->assertOk()->assertSee('Grille publique fictive')->assertDontSee('Brouillon fictif');
        $this->get(route('qualifications.show', $grid->slug))->assertOk()
            ->assertSee('34.63')->assertSee('50 nage libre')->assertSee('data-filtre="cat"', false)
            ->assertDontSee('FICTIF');
        $this->get(route('qualifications.show', $brouillon->slug))->assertNotFound();
    }

    public function test_liste_des_qualifies_pour_les_coachs(): void
    {
        $grid = QualificationGrid::create(['name' => 'Grille fictive', 'period_start' => '2026-09-01', 'period_end' => '2027-03-15']);
        $cat = $grid->categories()->create(['label' => 'A1', 'birth_year_from' => 2014, 'birth_year_to' => 2014]);
        foreach ([['50 NL', 'F', 25, 3500], ['100 Dos', 'F', 25, 8000], ['50 NL', 'M', 25, 3300]] as [$e, $g, $p, $cs]) {
            $grid->times()->create(['qualification_category_id' => $cat->id, 'event' => $e, 'gender' => $g, 'pool' => $p, 'time_cs' => $cs]);
        }

        $ondine = PlanningSwimmer::create(['first_name' => 'Ondine', 'last_name' => 'FICTIF', 'birth_year' => 2014, 'gender' => 'F', 'is_active' => true]);
        $marin = PlanningSwimmer::create(['first_name' => 'Marin', 'last_name' => 'EXEMPLE', 'birth_year' => 2014, 'gender' => 'M', 'is_active' => true]);
        $hors = PlanningSwimmer::create(['first_name' => 'Hors', 'last_name' => 'CATEGORIE', 'birth_year' => 2010, 'gender' => 'F', 'is_active' => true]);

        $perf = fn ($s, $event, $cs, $date) => SwimPerformance::create([
            'planning_swimmer_id' => $s->id, 'context' => 'meeting', 'meet_name' => 'Meeting fictif', 'performed_on' => $date,
            'pool' => 25, 'event' => $event, 'round' => 'serie', 'time_cs' => $cs, 'status' => 'valid',
        ]);
        $perf($ondine, '50 NL', 3500, '2026-10-10');      // égal à la grille : qualifiée
        $perf($ondine, '100 Dos', 8150, '2026-10-10');    // 1,9 % au-dessus : proche
        $perf($marin, '50 NL', 3200, '2026-06-01');       // hors période : ne compte pas
        $perf($marin, '50 NL', 3350, '2026-11-01');       // dans la période, trop lent
        $perf($hors, '50 NL', 2800, '2026-10-10');        // pas de catégorie

        $this->get(route('coach.qualifications.show', $grid))->assertRedirect();   // invité
        $this->actingAs($this->account('user'))->get(route('coach.qualifications.show', $grid))->assertForbidden();

        $this->actingAs($this->account('coach'));
        $this->get(route('coach.qualifications.show', $grid))->assertOk()
            ->assertSee('FICTIF')->assertDontSee('EXEMPLE')->assertDontSee('CATEGORIE');

        $page = $this->get(route('coach.qualifications.show', ['grid' => $grid, 'affichage' => 'tous']))->assertOk()
            ->assertSee('EXEMPLE')->assertSee('+1.50');
        $lignes = $page->viewData('lignes');
        $this->assertSame(1, $lignes->firstWhere(fn ($l) => $l['swimmer']->id === $ondine->id)['qualified']);
        $this->assertSame(1, $lignes->firstWhere(fn ($l) => $l['swimmer']->id === $ondine->id)['near']);
        $this->assertSame(0, $lignes->firstWhere(fn ($l) => $l['swimmer']->id === $marin->id)['qualified']);
    }
}
