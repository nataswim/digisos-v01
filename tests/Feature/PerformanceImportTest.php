<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\SwimPerformance;
use App\Models\User;
use App\Services\ExtranatPerformanceParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Import des performances depuis extraNat (copier-coller de la page « Performances »).
 * Données fictives uniquement. Lancer : php artisan test --filter=PerformanceImportTest
 */
class PerformanceImportTest extends TestCase
{
    use RefreshDatabase;

    /** Page fictive au format extraNat : 2 nageurs, bassins 25 et 50 m, un relais, un doublon. */
    private const PAGE = "Rechercher les performances d'une nageuse ou d'un nageur\n"
        . "swimming\nFICTIF Ondine (2012)  FRA [9000001]\nCette nageuse possède 6 performances dans la base de données FFN\n\n"
        . "50 Nage Libre Dames\n\nBassin : 25 mètres\n\n"
        . "50 NL\t00:31.20\t(14 ans)\t850 pts\t\nVILLEFICTIVE\n\n(FRA)\n\nFRANCE\n12/12/2026\t[DEP]\t\tCLUB FICTIF\t\n"
        . "50 NL\t00:32.05\t(13 ans)\t800 pts\t\nSAINT-JEAN-SUR-MER\n\n(FRA)\n\nFRANCE\n05/10/2025\t[REG]\t\tAUTRE CLUB\t\n"
        . "100 Dos Dames\n\nBassin : 25 mètres\n\n"
        . "100 Dos\t01:20.55\t(14 ans)\t700 pts\t\nVILLEFICTIVE\n\n(FRA)\n\nFRANCE\n12/12/2026\t[DEP]\t\tCLUB FICTIF\t\n"
        . "4x50 Nage Libre Dames\n\nBassin : 25 mètres\n\n"
        . "4x50 NL\t02:05.00\t\nVILLEFICTIVE\n\n(FRA)\n\nFRANCE\n12/12/2026\t[DEP]\t\tCLUB FICTIF\t\n"
        . "200 4 Nages Dames\n\nBassin : 50 mètres\n\n"
        . "200 4 N.\t03:01.10\t(14 ans)\t600 pts\t\nAUTREVILLE\n\n(FRA)\n\nFRANCE\n03/07/2026\t[NAT]\t\tCLUB FICTIF\t\n"
        . "50 Brasse Dames\n\nBassin : 25 mètres\n\n"
        . "50 Bra.\t00:45.00\t(14 ans)\t500 pts\t\nVILLEFICTIVE\n\n(FRA)\n\nFRANCE\n12/12/2026\t[DEP]\t\tCLUB FICTIF\t\n"
        . "EXEMPLE-DUPONT Jean Marc (2010)  FRA [9000002]\n"
        . "100 Papillon Messieurs\n\nBassin : 25 mètres\n\n"
        . "100 Pap.\t01:10.00\t(16 ans)\t900 pts\t\nVILLEFICTIVE\n\n(FRA)\n\nFRANCE\n12/12/2026\t[DEP]\t\tCLUB FICTIF\t\n";

    private function account(string $role): User
    {
        $r = Role::firstOrCreate(['slug' => $role], ['name' => ucfirst($role), 'display_name' => $role, 'level' => 0]);
        $u = User::create(['name' => "Test {$role}", 'email' => "{$role}@example.test", 'password' => bcrypt('secret-password'), 'role_id' => $r->id, 'status' => 'active']);
        $u->forceFill(['email_verified_at' => now()])->save();

        return $u;
    }

    public function test_parser_reads_the_extranat_format(): void
    {
        $r = (new ExtranatPerformanceParser)->parse(self::PAGE);
        $this->assertCount(2, $r['swimmers']);
        [$a, $b] = $r['swimmers'];

        $this->assertSame(['FICTIF', 'Ondine', 2012, '9000001'], [$a['last_name'], $a['first_name'], $a['birth_year'], $a['iuf']]);
        $this->assertSame(['EXEMPLE-DUPONT', 'Jean Marc'], [$b['last_name'], $b['first_name']]);
        $this->assertCount(5, $a['performances']);   // le relais est ignoré
        $this->assertSame(1, $r['ignored']);

        $p = $a['performances'][0];
        $this->assertSame(['50 NL', 25, 3120, 'Villefictive', '2026-12-12', 'CLUB FICTIF'], [$p['event'], $p['pool'], $p['time_cs'], $p['location'], $p['date'], $p['club']]);
        $this->assertSame('Saint-Jean-sur-Mer', $a['performances'][1]['location']);
        $this->assertSame(['200 4N', 50], [$a['performances'][3]['event'], $a['performances'][3]['pool']]);
        $this->assertSame('50 Brasse', $a['performances'][4]['event']);
        $this->assertSame('100 Pap', $b['performances'][0]['event']);
    }

    public function test_only_admin_can_import(): void
    {
        $coach = $this->account('coach');
        $this->actingAs($coach)->get(route('coach.performances.import'))->assertForbidden();
        $this->actingAs($coach)->post(route('coach.performances.import.store'), ['text' => self::PAGE])->assertForbidden();
        $this->actingAs($this->account('admin'))->get(route('coach.performances.import'))->assertOk();
    }

    public function test_preview_then_import_without_duplicates(): void
    {
        $admin = $this->account('admin');
        $ondine = PlanningSwimmer::create(['first_name' => 'Ondine', 'last_name' => 'Fictif', 'birth_year' => 2012, 'gender' => 'F', 'license_number' => '9000001', 'is_active' => true]);
        $jean = PlanningSwimmer::create(['first_name' => 'Jean Marc', 'last_name' => 'Exemple-Dupont', 'birth_year' => 2010, 'gender' => 'M', 'is_active' => true]);
        $compet = Competition::create(['title' => 'Interclubs fictifs', 'season' => '2026-2027', 'level' => 'departemental', 'kind' => 'individuel',
            'starts_on' => '2026-12-12', 'ends_on' => '2026-12-12', 'venue' => 'Piscine', 'city' => 'Villefictive', 'pool_length' => 25, 'is_published' => true]);
        // Déjà saisi à la main : ne doit pas être ré-importé
        SwimPerformance::create(['planning_swimmer_id' => $ondine->id, 'context' => 'competition', 'competition_id' => $compet->id, 'performed_on' => '2026-12-12',
            'pool' => 25, 'event' => '100 Dos', 'round' => 'serie', 'time_cs' => 8055, 'status' => 'valid']);

        $this->actingAs($admin)->post(route('coach.performances.import.store'), ['text' => self::PAGE])->assertOk()
            ->assertSee('FICTIF Ondine')->assertSee('4 nouvelle(s)')->assertSee('1 déjà enregistrée(s)')
            ->assertSee('Interclubs fictifs')->assertSee('Importer 5 performance(s)');
        $this->assertSame(1, SwimPerformance::count());

        $this->post(route('coach.performances.import.store'), ['text' => self::PAGE, 'confirm' => 1])->assertRedirect(route('coach.performances.index'));
        $this->assertSame(6, SwimPerformance::count());

        $p = SwimPerformance::where('planning_swimmer_id', $ondine->id)->where('event', '50 NL')->whereDate('performed_on', '2026-12-12')->first();
        $this->assertSame([$compet->id, 'competition', 'serie', 3120, 'Import extraNat'], [$p->competition_id, $p->context, $p->round, $p->time_cs, $p->note]);
        $hors = SwimPerformance::where('planning_swimmer_id', $ondine->id)->whereDate('performed_on', '2025-10-05')->first();
        $this->assertSame(['meeting', 'Compétition à Saint-Jean-sur-Mer', 'Saint-Jean-sur-Mer'], [$hors->context, $hors->meet_name, $hors->location]);
        $this->assertSame(1, SwimPerformance::where('planning_swimmer_id', $jean->id)->count());   // reconnu par nom + année

        // Deuxième import de la même page : rien de nouveau
        $this->post(route('coach.performances.import.store'), ['text' => self::PAGE, 'confirm' => 1]);
        $this->assertSame(6, SwimPerformance::count());
    }

    public function test_unknown_swimmer_can_be_assigned_or_skipped(): void
    {
        $admin = $this->account('admin');
        $autre = PlanningSwimmer::create(['first_name' => 'Nom', 'last_name' => 'Différent', 'birth_year' => 2012, 'gender' => 'F', 'is_active' => true]);

        $this->actingAs($admin)->post(route('coach.performances.import.store'), ['text' => self::PAGE])->assertOk()->assertSee('Nageur non trouvé');
        $this->post(route('coach.performances.import.store'), ['text' => self::PAGE, 'assign' => [0 => $autre->id, 1 => ''], 'confirm' => 1])->assertRedirect();
        $this->assertSame(5, SwimPerformance::where('planning_swimmer_id', $autre->id)->count());
        $this->assertSame(5, SwimPerformance::count());
    }

    public function test_warns_when_iuf_matches_another_name(): void
    {
        PlanningSwimmer::create(['first_name' => 'Autre', 'last_name' => 'Personne', 'birth_year' => 2015, 'gender' => 'F', 'license_number' => '9000001', 'is_active' => true]);
        $this->actingAs($this->account('admin'))->post(route('coach.performances.import.store'), ['text' => self::PAGE])->assertOk()
            ->assertSee('ne correspond pas au nom');
    }
}
