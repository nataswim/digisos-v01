<?php

namespace Tests\Feature;

use App\Models\PlanningGroup;
use App\Models\PlanningSlot;
use App\Models\PlanningSwimmer;
use App\Models\PresenceRecord;
use App\Models\PresenceSession;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Émargement et assiduité : appel d'une séance, séance supplémentaire, statistiques,
 * tableau mensuel, fréquentation par créneau, consultation par les familles.
 * Données fictives uniquement.
 * Lancer : php artisan test --filter=PresenceModuleTest
 */
class PresenceModuleTest extends TestCase
{
    use RefreshDatabase;

    private PlanningGroup $groupe;
    private PlanningSlot $creneau;
    private PlanningSwimmer $alice;
    private PlanningSwimmer $hugo;
    private PlanningSwimmer $lea;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-11-10 18:05:00'));   // un mardi

        $this->groupe = PlanningGroup::create(['name' => 'Avenirs', 'section' => 'École de natation', 'is_active' => true]);
        $this->creneau = PlanningSlot::create(['planning_group_id' => $this->groupe->id, 'period' => 'scolaire', 'day_of_week' => 2,
            'starts_at' => '18:00', 'ends_at' => '19:00', 'location' => 'Piscine', 'is_active' => true]);
        $this->alice = PlanningSwimmer::create(['first_name' => 'Alice', 'last_name' => 'Durand', 'is_active' => true]);
        $this->hugo = PlanningSwimmer::create(['first_name' => 'Hugo', 'last_name' => 'Roux', 'is_active' => true]);
        $this->lea = PlanningSwimmer::create(['first_name' => 'Léa', 'last_name' => 'Martin', 'is_active' => true]);   // autre groupe
        $this->groupe->swimmers()->attach([$this->alice->id, $this->hugo->id]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function userWithRole(string $slug): User
    {
        $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'display_name' => $slug, 'level' => 0]);
        $user = User::create([
            'name' => "Test {$slug}", 'email' => "{$slug}@example.test",
            'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    public function test_roll_call(): void
    {
        $coach = $this->userWithRole('coach');

        $this->actingAs($coach)->get('/coach/emargement')->assertOk()
            ->assertSee('Avenirs')->assertSee("Faire l'appel", false);

        $this->actingAs($coach)->post('/coach/emargement/ouvrir', ['slot' => $this->creneau->id, 'date' => '2026-11-10'])->assertRedirect();
        $session = PresenceSession::firstOrFail();
        $this->assertSame($this->groupe->id, $session->planning_group_id);

        // Ouvrir deux fois : même séance
        $this->actingAs($coach)->post('/coach/emargement/ouvrir', ['slot' => $this->creneau->id, 'date' => '2026-11-10']);
        $this->assertSame(1, PresenceSession::count());

        $this->actingAs($coach)->get("/coach/emargement/seances/{$session->id}")->assertOk()
            ->assertSee('Alice DURAND')->assertSee('Hugo ROUX')->assertDontSee('Léa MARTIN</div>', false);

        $this->actingAs($coach)->put("/coach/emargement/seances/{$session->id}", [
            'status' => [$this->alice->id => 'present', $this->hugo->id => 'excuse', $this->lea->id => 'present'],
            'comment' => [$this->hugo->id => 'Malade'],
        ])->assertRedirect()->assertSessionHas('success', 'Émargement enregistré (2 nageurs).');

        $alice = PresenceRecord::where('planning_swimmer_id', $this->alice->id)->firstOrFail();
        $this->assertSame('present', $alice->status);
        $this->assertSame('18:05', substr($alice->arrived_at, 0, 5));   // heure d'arrivée automatique
        $this->assertSame('Malade', PresenceRecord::where('planning_swimmer_id', $this->hugo->id)->value('comment'));
        // Léa n'est pas dans le groupe : ignorée tant qu'elle n'est pas ajoutée
        $this->assertFalse(PresenceRecord::where('planning_swimmer_id', $this->lea->id)->exists());

        // Ajout hors groupe
        $this->actingAs($coach)->post("/coach/emargement/seances/{$session->id}/nageur", ['swimmer' => $this->lea->id])->assertRedirect();
        $this->assertSame('present', PresenceRecord::where('planning_swimmer_id', $this->lea->id)->value('status'));
        $this->actingAs($coach)->get("/coach/emargement/seances/{$session->id}")->assertSee('Hors groupe');

        // Statut effacé : plus émargé ; l'heure d'arrivée connue est conservée
        $this->actingAs($coach)->put("/coach/emargement/seances/{$session->id}", [
            'status' => [$this->alice->id => 'present', $this->hugo->id => '', $this->lea->id => 'absent'],
        ]);
        $this->assertFalse(PresenceRecord::where('planning_swimmer_id', $this->hugo->id)->exists());
        $this->assertNull(PresenceRecord::where('planning_swimmer_id', $this->lea->id)->value('arrived_at'));
        $this->assertSame('18:05', substr(PresenceRecord::where('planning_swimmer_id', $this->alice->id)->value('arrived_at'), 0, 5));

        $this->actingAs($coach)->get('/coach/emargement')->assertSee('Émargée');

        // Statut inconnu refusé
        $this->actingAs($coach)->put("/coach/emargement/seances/{$session->id}", ['status' => [$this->alice->id => 'retard']])
            ->assertSessionHasErrors('status.' . $this->alice->id);
    }

    public function test_extra_session_and_delete(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/coach/emargement/seance-supplementaire', [
            'title' => 'Stage de Toussaint', 'date' => '2026-11-09', 'planning_group_id' => $this->groupe->id, 'starts_at' => '10:00', 'ends_at' => '12:00',
        ])->assertRedirect();
        $session = PresenceSession::where('title', 'Stage de Toussaint')->firstOrFail();
        $this->assertNull($session->planning_slot_id);

        $this->actingAs($admin)->get('/coach/emargement?date=2026-11-09')->assertSee('Stage de Toussaint')->assertSee('Supplémentaire');

        // Séance passée : pas d'heure d'arrivée automatique
        $this->actingAs($admin)->put("/coach/emargement/seances/{$session->id}", ['status' => [$this->alice->id => 'present']]);
        $this->assertNull(PresenceRecord::firstOrFail()->arrived_at);

        $this->actingAs($admin)->delete("/coach/emargement/seances/{$session->id}")->assertRedirect('/coach/emargement?date=2026-11-09');
        $this->assertSame(0, PresenceRecord::count());

        $this->actingAs($admin)->post('/coach/emargement/seance-supplementaire', ['date' => '2026-11-09'])->assertSessionHasErrors('title');
    }

    public function test_statistics(): void
    {
        $coach = $this->userWithRole('coach');
        // 4 mardis : Alice P P A E, Hugo P A A (non émargé le 4e)
        $statuts = [
            '2026-10-20' => ['present', 'present'],
            '2026-10-27' => ['present', 'absent'],
            '2026-11-03' => ['absent', 'absent'],
            '2026-11-10' => ['excuse', null],
        ];
        foreach ($statuts as $date => [$a, $h]) {
            $s = PresenceSession::forSlot($this->creneau, Carbon::parse($date));
            PresenceRecord::create(['presence_session_id' => $s->id, 'planning_swimmer_id' => $this->alice->id, 'status' => $a]);
            if ($h) {
                PresenceRecord::create(['presence_session_id' => $s->id, 'planning_swimmer_id' => $this->hugo->id, 'status' => $h]);
            }
        }

        // Par nageur (saison) : Alice 2/4 = 50 %, Hugo 1/3 = 33 %
        $this->actingAs($coach)->get('/coach/assiduite')->assertOk()
            ->assertSee('Alice DURAND')->assertSee('50 %')->assertSee('33 %');

        // Ce mois-ci (novembre) : Alice 0/2, Hugo 0/1
        $this->actingAs($coach)->get('/coach/assiduite?periode=mois')->assertSee('0 %')->assertDontSee('50 %');

        // Par créneau : 4 séances, 3 présents → 0,8 en moyenne ; taux 3/7 = 43 %
        $this->actingAs($coach)->get('/coach/assiduite?vue=creneaux')->assertOk()
            ->assertSee('Mardi 18 h – 19 h')->assertSee('0,8')->assertSee('43 %');

        // Fiche nageur
        $this->actingAs($coach)->get("/coach/assiduite/nageur/{$this->alice->id}")->assertOk()
            ->assertSee('50 %')->assertSee('Excusé');

        // Tableau mensuel d'octobre : 2 séances
        $this->actingAs($coach)->get("/coach/assiduite/groupe/{$this->groupe->id}?mois=2026-10")->assertOk()
            ->assertSee('Octobre 2026')->assertSee('2 / 2')->assertSee('1 / 2');
    }

    public function test_access_and_families(): void
    {
        $parent = $this->userWithRole('user');
        $this->alice->update(['user_id' => $parent->id]);
        $s = PresenceSession::forSlot($this->creneau, Carbon::parse('2026-11-03'));
        PresenceRecord::create(['presence_session_id' => $s->id, 'planning_swimmer_id' => $this->alice->id, 'status' => 'present']);
        PresenceRecord::create(['presence_session_id' => $s->id, 'planning_swimmer_id' => $this->hugo->id, 'status' => 'absent']);

        // Familles : consultation de leurs nageurs uniquement
        $this->actingAs($parent)->get('/user/assiduite')->assertOk()
            ->assertSee('Alice DURAND')->assertSee('100 %')->assertDontSee('Hugo ROUX');
        $this->actingAs($parent)->get('/user/dashboard')->assertSee('/user/assiduite', false);

        foreach (['/coach/emargement', '/coach/assiduite', "/coach/emargement/seances/{$s->id}"] as $url) {
            $this->actingAs($parent)->get($url)->assertForbidden();
        }
        $this->actingAs($this->userWithRole('editor'))->get('/coach/emargement')->assertForbidden();

        $this->actingAs($this->userWithRole('visitor'))->get('/user/assiduite')->assertRedirect('/visitor/dashboard');
    }
}
