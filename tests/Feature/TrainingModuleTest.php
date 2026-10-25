<?php

namespace Tests\Feature;

use App\Models\PlanningGroup;
use App\Models\PlanningSlot;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\TrainingExercise;
use App\Models\TrainingList;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Séances & entraînements : création (natation, PPG, maison), calculs, ciblage individuel,
 * publication, espace adhérent, bibliothèque d'exercices, listes. Données fictives uniquement.
 * Lancer : php artisan test --filter=TrainingModuleTest
 */
class TrainingModuleTest extends TestCase
{
    use RefreshDatabase;

    private PlanningGroup $groupe;
    private PlanningSwimmer $alice;
    private PlanningSwimmer $hugo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->groupe = PlanningGroup::create(['name' => 'Avenirs', 'section' => 'École de natation', 'is_active' => true]);
        $this->alice = PlanningSwimmer::create(['first_name' => 'Alice', 'last_name' => 'Durand', 'is_active' => true]);
        $this->hugo = PlanningSwimmer::create(['first_name' => 'Hugo', 'last_name' => 'Roux', 'is_active' => true]);
        $this->groupe->swimmers()->attach([$this->alice->id, $this->hugo->id]);
    }

    private function userWithRole(string $slug, ?string $email = null): User
    {
        $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'display_name' => $slug, 'level' => 0]);
        $user = User::create([
            'name' => "Test {$slug}", 'email' => $email ?? "{$slug}@example.test",
            'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function swimPayload(array $extra = []): array
    {
        return $extra + [
            'type' => 'natation',
            'title' => 'Endurance et virages',
            'session_date' => today()->addDay()->toDateString(),
            'starts_at' => '18:00', 'ends_at' => '19:30',
            'equipment' => ['Plaquettes'],
            'groups' => [$this->groupe->id],
            'swimmers' => [$this->alice->id],          // séance personnalisée : Alice seulement
            'content' => ['blocks' => [
                'n1' => ['kind' => 'Échauffement', 'repeat' => 1, 'lines' => [
                    'a' => ['reps' => 1, 'distance' => 400, 'stroke' => 'Crawl', 'zone' => 'Z1'],
                    'b' => ['reps' => 4, 'distance' => 50, 'stroke' => '4 nages', 'zone' => 'Z2', 'rest' => '15 s'],
                ]],
                'n2' => ['kind' => 'Série principale', 'repeat' => 2, 'lines' => [
                    'c' => ['reps' => 4, 'distance' => 100, 'stroke' => 'Crawl', 'zone' => 'Z4', 'departure' => "1'45"],
                ]],
                'vide' => ['kind' => 'Retour au calme', 'lines' => []],     // bloc vide : ignoré
            ]],
            'action' => 'publish',
        ];
    }

    public function test_coach_creates_and_publishes_swim_session(): void
    {
        $coach = $this->userWithRole('coach');

        $this->actingAs($coach)->get('/coach/seances/create?type=natation')->assertOk()->assertSee('Échauffement');

        $this->actingAs($coach)->post('/coach/seances', $this->swimPayload())->assertRedirect();
        $session = TrainingSession::firstOrFail();

        // 400 + 4×50 + 2×(4×100) = 1 400 m ; 90 min
        $this->assertSame(1400, $session->total_distance);
        $this->assertSame(90, $session->total_duration);
        $this->assertCount(2, $session->blocks());
        $this->assertTrue($session->isPublished());
        $this->assertSame([$this->alice->id], $session->swimmers()->pluck('planning_swimmers.id')->all());

        $this->actingAs($coach)->get("/coach/seances/{$session->id}")->assertOk()
            ->assertSee('1 400 m')->assertSee('Série principale')->assertSee('2 fois')->assertSee("départ 1'45");
        $this->actingAs($coach)->get('/coach/seances?semaine=' . today()->addDay()->startOfWeek()->toDateString())
            ->assertSee('Endurance et virages')->assertSee('Publiée');

        // Publier sans nageur : refusé
        $this->actingAs($coach)->post('/coach/seances', $this->swimPayload(['swimmers' => []]))->assertSessionHasErrors('swimmers');
        // Zone inconnue : refusée
        $payload = $this->swimPayload();
        $payload['content']['blocks']['n1']['lines']['a']['zone'] = 'Z9';
        $this->actingAs($coach)->post('/coach/seances', $payload)->assertSessionHasErrors();
    }

    public function test_members_see_only_their_published_sessions(): void
    {
        $coach = $this->userWithRole('coach');
        $parentAlice = $this->userWithRole('user', 'famille.durand@example.test');
        $parentHugo = $this->userWithRole('user', 'famille.roux@example.test');
        $this->alice->update(['user_id' => $parentAlice->id]);
        $this->hugo->update(['user_id' => $parentHugo->id]);

        $this->actingAs($coach)->post('/coach/seances', $this->swimPayload());
        $session = TrainingSession::firstOrFail();
        $semaine = '?semaine=' . $session->session_date->toDateString();

        $this->actingAs($parentAlice)->get('/user/entrainements' . $semaine)->assertOk()->assertSee('Endurance et virages')->assertSee('Prochaine séance');
        $this->actingAs($parentAlice)->get("/user/entrainements/{$session->id}")->assertOk()->assertSee('1 400 m')->assertSee('Imprimer');

        // Hugo n'est pas coché : séance invisible
        $this->actingAs($parentHugo)->get('/user/entrainements' . $semaine)->assertOk()->assertDontSee('Endurance et virages');
        $this->actingAs($parentHugo)->get("/user/entrainements/{$session->id}")->assertNotFound();

        // Brouillon : invisible
        $this->actingAs($coach)->patch("/coach/seances/{$session->id}/publication")->assertRedirect();
        $this->assertFalse($session->fresh()->isPublished());
        $this->actingAs($parentAlice)->get("/user/entrainements/{$session->id}")->assertNotFound();

        $this->actingAs($parentAlice)->get('/user/dashboard')->assertSee('/user/entrainements', false);
        $this->actingAs($this->userWithRole('visitor'))->get('/user/entrainements')->assertRedirect('/visitor/dashboard');
    }

    public function test_ppg_session_with_exercise_library(): void
    {
        $coach = $this->userWithRole('coach');

        $this->actingAs($coach)->post('/coach/seances/exercices', [
            'name' => 'Planche ventrale', 'category' => 'Gainage', 'image' => '/storage/media/planche.jpg',
            'tips' => 'Dos droit, ventre serré.', 'is_active' => '1',
        ])->assertRedirect('/coach/seances/exercices');
        $planche = TrainingExercise::firstOrFail();
        $squat = TrainingExercise::create(['name' => 'Squat', 'category' => 'Bas du corps', 'is_active' => true]);

        $this->actingAs($coach)->post('/coach/seances/exercices', ['name' => 'X', 'image' => 'javascript:alert(1)'])->assertSessionHasErrors('image');

        $this->actingAs($coach)->post('/coach/seances', [
            'type' => 'maison', 'title' => 'Circuit maison', 'session_date' => today()->addDays(3)->toDateString(), 'rounds' => 2,
            'swimmers' => [$this->hugo->id],
            'content' => ['items' => [
                'a' => ['exercise_id' => $planche->id, 'sets' => 3, 'duration' => 30, 'rest_sets' => 30, 'rest_after' => 60, 'note' => 'Garder le bassin aligné'],
                'b' => ['exercise_id' => $squat->id, 'sets' => 3, 'reps' => 15, 'rest_sets' => 30, 'load' => 'Poids du corps'],
                'c' => ['exercise_id' => ''],     // ligne vide : ignorée
            ]],
            'action' => 'publish',
        ])->assertRedirect();

        $session = TrainingSession::firstOrFail();
        $this->assertCount(2, $session->items());
        // Planche : 3×30 + 2×30 + 60 = 210 s ; squat : 3×(15×3) + 2×30 = 195 s ; ×2 tours = 810 s → 14 min
        $this->assertSame(14, $session->total_duration);

        $this->actingAs($coach)->get("/coach/seances/{$session->id}")->assertOk()
            ->assertSee('Planche ventrale')->assertSee('/storage/media/planche.jpg', false)
            ->assertSee('3 séries × 30 s')->assertSee('3 séries × 15 répétitions')
            ->assertSee('Circuit : 2 tours')->assertSee('Dos droit, ventre serré.')->assertSee('Poids du corps');

        // Exercice utilisé : désactivé au lieu d'être supprimé
        $this->actingAs($coach)->delete("/coach/seances/exercices/{$planche->id}")->assertRedirect();
        $this->assertFalse($planche->fresh()->is_active);
        $libre = TrainingExercise::create(['name' => 'Libre', 'is_active' => true]);
        $this->actingAs($coach)->delete("/coach/seances/exercices/{$libre->id}");
        $this->assertNull($libre->fresh());
    }

    public function test_duplicate_lists_and_access(): void
    {
        $admin = $this->userWithRole('admin');
        $this->actingAs($admin)->post('/coach/seances', $this->swimPayload());
        $session = TrainingSession::firstOrFail();

        $this->actingAs($admin)->post("/coach/seances/{$session->id}/dupliquer")->assertRedirect();
        $copie = TrainingSession::latest('id')->firstOrFail();
        $this->assertNotSame($session->id, $copie->id);
        $this->assertSame('draft', $copie->status);
        $this->assertSame($session->session_date->copy()->addWeek()->toDateString(), $copie->session_date->toDateString());
        $this->assertSame(1400, $copie->total_distance);
        $this->assertSame([$this->alice->id], $copie->swimmers()->pluck('planning_swimmers.id')->all());

        // Listes modifiables (créées par la migration)
        $this->assertContains('Pull-buoy', TrainingList::labels('equipment'));
        $this->actingAs($admin)->get('/coach/seances/listes')->assertOk()->assertSee('Pull-buoy');
        $this->actingAs($admin)->put('/coach/seances/listes', ['lists' => ['equipment' => "Plaquettes\nPalmes\n\nPalmes\nSnorkel"]])->assertRedirect();
        $this->assertSame(['Plaquettes', 'Palmes', 'Snorkel'], TrainingList::labels('equipment'));
        $this->actingAs($admin)->get('/coach/seances/create?type=ppg')->assertOk()->assertSee('Snorkel');

        // Pré-remplissage depuis un créneau
        $slot = PlanningSlot::create(['planning_group_id' => $this->groupe->id, 'period' => 'scolaire', 'day_of_week' => 2, 'starts_at' => '18:00', 'ends_at' => '19:00', 'is_active' => true]);
        $this->actingAs($admin)->get("/coach/seances/create?type=natation&creneau={$slot->id}")->assertOk()->assertSee('value="18:00"', false);

        // Accès : coachs et admins seulement
        foreach (['editor', 'user'] as $slug) {
            $this->actingAs($this->userWithRole($slug))->get('/coach/seances')->assertForbidden();
        }

        $this->actingAs($admin)->delete("/coach/seances/{$copie->id}")->assertRedirect();
        $this->assertNull($copie->fresh());
    }
}
