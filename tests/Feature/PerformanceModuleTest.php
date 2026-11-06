<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\CompetitionAttendance;
use App\Models\CompetitionEntry;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\SwimEvent;
use App\Models\SwimPerformance;
use App\Models\User;
use App\Services\PerformanceTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Suivi des performances : saisie (compétition du calendrier, meeting, contre-la-montre), MPP,
 * écarts et %, série vs finale, fiche nageur, bilan, classement, espace familles. Données fictives.
 * Lancer : php artisan test --filter=PerformanceModuleTest
 */
class PerformanceModuleTest extends TestCase
{
    use RefreshDatabase;

    private PlanningSwimmer $alice;
    private PlanningSwimmer $hugo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->alice = PlanningSwimmer::create(['first_name' => 'Alice', 'last_name' => 'Durand', 'birth_year' => 2012, 'gender' => 'F', 'is_active' => true]);
        $this->hugo = PlanningSwimmer::create(['first_name' => 'Hugo', 'last_name' => 'Roux', 'birth_year' => 2011, 'gender' => 'M', 'is_active' => true]);
    }

    private function userWithRole(string $slug): User
    {
        $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'display_name' => $slug, 'level' => 0]);
        $user = User::create(['name' => "Test {$slug}", 'email' => "{$slug}@example.test", 'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active']);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function perf(PlanningSwimmer $s, string $date, string $event, int $cs, string $round = 'serie', array $extra = []): SwimPerformance
    {
        return SwimPerformance::create($extra + ['planning_swimmer_id' => $s->id, 'context' => 'meeting', 'meet_name' => 'Meeting ' . $date,
            'performed_on' => $date, 'pool' => 25, 'event' => $event, 'round' => $round, 'time_cs' => $cs, 'status' => 'valid']);
    }

    public function test_tracker_computes_personal_bests_gaps_and_finals(): void
    {
        $a = $this->perf($this->alice, '2026-09-20', '50 NL', 3500);                 // 1er temps
        $b = $this->perf($this->alice, '2026-10-04', '50 NL', 3450);                 // record perso −0.50 (−1,4 %)
        $c = $this->perf($this->alice, '2026-10-18', '50 NL', 3520);                 // série : +0.70 (+2,0 %)
        $d = $this->perf($this->alice, '2026-10-18', '50 NL', 3400, 'finale');       // finale : record perso, −1.20 vs série
        $e = $this->perf($this->alice, '2026-10-25', '50 NL', 3600, 'serie', ['pool' => 50]); // autre bassin : 1er temps
        $f = SwimPerformance::create(['planning_swimmer_id' => $this->alice->id, 'context' => 'meeting', 'performed_on' => '2026-10-26', 'pool' => 25, 'event' => '50 NL', 'round' => 'serie', 'status' => 'dsq']);

        $infos = (new PerformanceTracker())->analyse(SwimPerformance::all());
        $this->assertTrue($infos[$a->id]['first']);
        $this->assertSame([-50, -1.4, true], [$infos[$b->id]['gap'], $infos[$b->id]['pct'], $infos[$b->id]['pb']]);
        $this->assertSame([70, 2.0, false], [$infos[$c->id]['gap'], $infos[$c->id]['pct'], $infos[$c->id]['pb']]);
        $this->assertSame([-50, true, -120, -3.4], [$infos[$d->id]['gap'], $infos[$d->id]['pb'], $infos[$d->id]['vs_serie'], $infos[$d->id]['vs_serie_pct']]);
        $this->assertTrue($infos[$e->id]['first']);
        $this->assertNull($infos[$f->id]['gap']);

        $bests = (new PerformanceTracker())->bests(SwimPerformance::all());
        $this->assertSame($d->id, $bests[25]['50 NL']->id);
        $this->assertSame($e->id, $bests[50]['50 NL']->id);
        $this->assertSame('−1,4 %', PerformanceTracker::pctLabel(-1.4));
    }

    public function test_entry_grid_for_calendar_competition(): void
    {
        $coach = $this->userWithRole('coach');
        $competition = Competition::create(['title' => 'Meeting du Bocage', 'level' => 'departemental', 'kind' => 'individuel', 'venue' => 'Cœur d\'O', 'city' => 'Bressuire',
            'starts_on' => '2026-10-31', 'ends_on' => '2026-10-31', 'pool_length' => 25, 'is_published' => true]);
        $attendance = CompetitionAttendance::create(['competition_id' => $competition->id, 'planning_swimmer_id' => $this->alice->id, 'status' => 'confirme']);
        $event = SwimEvent::updateOrCreate(['code' => '100-dos'], ['stroke' => 'dos', 'distance' => 100, 'is_relay' => false, 'label' => '100 Dos', 'sort_order' => 1, 'is_active' => true]);
        CompetitionEntry::create(['competition_attendance_id' => $attendance->id, 'swim_event_id' => $event->id]);
        $this->perf($this->alice, '2026-10-01', '100 Dos', 8000);

        // Grille pré-remplie : nageur convoqué + épreuve engagée, MPP connue pour le calcul en direct
        $this->actingAs($coach)->get("/coach/performances/saisie?competition={$competition->id}")->assertOk()
            ->assertSee('Meeting du Bocage')->assertSee('<option value="100 Dos" selected', false)
            ->assertSee('"' . $this->alice->id . '|100 Dos|25":8000', false);

        $this->actingAs($coach)->post('/coach/performances/saisie', [
            'context' => 'competition', 'competition_id' => $competition->id, 'performed_on' => '2026-10-31', 'pool' => 25, 'location' => 'Bressuire',
            'rows' => [
                ['swimmer' => $this->alice->id, 'event' => '100 Dos', 'round' => 'serie', 'time' => '1:19.50', 'status' => 'valid'],
                ['swimmer' => $this->alice->id, 'event' => '100 Dos', 'round' => 'finale', 'time' => '1:18.90', 'status' => 'valid'],
                ['swimmer' => $this->hugo->id, 'event' => '25 NL', 'round' => 'serie', 'time' => '', 'status' => 'dsq'],
                ['swimmer' => '', 'event' => '', 'time' => ''],                         // ligne vide
            ],
        ])->assertRedirect("/coach/performances/bilan?competition={$competition->id}");

        $this->assertSame(4, SwimPerformance::count());
        $this->assertSame(7950, SwimPerformance::where('round', 'serie')->where('event', '100 Dos')->latest('id')->value('time_cs'));

        $this->actingAs($coach)->get("/coach/performances/bilan?competition={$competition->id}")->assertOk()
            ->assertSee('Bilan : Meeting du Bocage')->assertSee('★ Record perso')->assertSee('vs série −0.60')
            ->assertSee('Disqualifié');
    }

    public function test_entry_errors_and_training(): void
    {
        $coach = $this->userWithRole('coach');
        $base = ['context' => 'training', 'meet_name' => 'CLM mardi', 'performed_on' => '2026-10-27', 'pool' => 50];

        $this->actingAs($coach)->post('/coach/performances/saisie', $base + ['rows' => [['swimmer' => $this->alice->id, 'event' => '50 NL', 'round' => 'clm', 'time' => 'vite']]])
            ->assertSessionHasErrors('rows.0.time');
        $this->actingAs($coach)->post('/coach/performances/saisie', $base + ['rows' => [['swimmer' => $this->alice->id, 'event' => '25 NL', 'round' => 'clm', 'time' => '15.00']]])
            ->assertSessionHasErrors('rows.0.event');
        $this->actingAs($coach)->post('/coach/performances/saisie', $base + ['rows' => [['swimmer' => '', 'time' => '']]])
            ->assertSessionHasErrors('rows');
        $this->actingAs($coach)->post('/coach/performances/saisie', ['performed_on' => today()->addDay()->toDateString()] + $base + ['rows' => [['swimmer' => $this->alice->id, 'event' => '50 NL', 'time' => '33.00']]])
            ->assertSessionHasErrors('performed_on');

        $this->actingAs($coach)->post('/coach/performances/saisie', $base + ['rows' => [['swimmer' => $this->alice->id, 'event' => '50 NL', 'round' => 'clm', 'time' => '33.10']]])
            ->assertRedirect('/coach/performances/bilan?date=2026-10-27&meeting=CLM%20mardi');
        $perf = SwimPerformance::firstOrFail();
        $this->assertSame(['training', 3310, 50], [$perf->context, $perf->time_cs, $perf->pool]);

        // Modification et suppression
        $this->actingAs($coach)->put("/coach/performances/{$perf->id}", ['performed_on' => '2026-10-27', 'pool' => 50, 'event' => '50 NL', 'round' => 'clm', 'time' => '32.95', 'status' => 'valid', 'meet_name' => 'CLM mardi'])
            ->assertRedirect("/coach/performances/nageur/{$this->alice->id}");
        $this->assertSame(3295, $perf->fresh()->time_cs);
        $this->actingAs($coach)->delete("/coach/performances/{$perf->id}")->assertRedirect();
        $this->assertSame(0, SwimPerformance::count());
    }

    public function test_swimmer_page_ranking_and_families(): void
    {
        $coach = $this->userWithRole('coach');
        $parent = $this->userWithRole('user');
        $this->alice->update(['user_id' => $parent->id]);

        $this->perf($this->alice, '2026-09-20', '50 NL', 3500);
        $this->perf($this->alice, '2026-10-04', '50 NL', 3450);
        $this->perf($this->hugo, '2026-10-04', '50 NL', 3300);

        $this->actingAs($coach)->get("/coach/performances/nageur/{$this->alice->id}")->assertOk()
            ->assertSee('Meilleures performances')->assertSee('34.50')->assertSee('<svg', false)->assertSee('★ Record perso −0.50', false);

        $this->actingAs($coach)->get('/coach/performances/classement?epreuve=50+NL&bassin=25')->assertOk()
            ->assertSeeInOrder(['Hugo ROUX', '33.00', 'Alice DURAND', '34.50', '+1.50']);
        $this->actingAs($coach)->get('/coach/performances/classement?epreuve=50+NL&bassin=25&sexe=F')->assertDontSee('Hugo ROUX');
        $this->actingAs($coach)->get('/coach/performances')->assertOk()->assertSee('Alice DURAND');

        // Famille : uniquement ses nageurs
        $this->actingAs($parent)->get('/user/performances')->assertOk()->assertSee('Alice DURAND')->assertSee('34.50')->assertDontSee('Hugo');
        $this->actingAs($parent)->get("/user/performances?nageur={$this->hugo->id}")->assertOk()->assertDontSee('33.00');
        $this->actingAs($parent)->get('/user/dashboard')->assertSee('/user/performances', false);

        // Accès réservé
        $this->actingAs($parent)->get('/coach/performances')->assertForbidden();
        $this->actingAs($this->userWithRole('visitor'))->get('/user/performances')->assertRedirect('/visitor/dashboard');
    }
}
