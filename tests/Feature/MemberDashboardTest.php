<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\CompetitionAttendance;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\SwimEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Espace adhérent : en haut du tableau de bord, photo, groupe et licence des nageurs du compte
 * + leurs convocations ; pages « Mes … » avec l'en-tête adhérent (photo + nom) au lieu de la vidéo.
 * Données fictives. Lancer : php artisan test --filter=MemberDashboardTest
 */
class MemberDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function family(): User
    {
        $role = Role::firstOrCreate(['slug' => 'user'], ['name' => 'User', 'display_name' => 'user', 'level' => 0]);
        $user = User::create(['name' => 'Famille Test', 'first_name' => 'Camille', 'last_name' => 'Fictif', 'email' => 'famille@example.test', 'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active']);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function competition(string $title, int $days, bool $published = true): Competition
    {
        $d = now()->addDays($days)->toDateString();

        return Competition::create(['title' => $title, 'season' => '2026-2027', 'level' => 'departemental', 'kind' => 'individuel',
            'starts_on' => $d, 'ends_on' => $d, 'venue' => 'Piscine fictive', 'city' => 'Villefictive', 'pool_length' => 25,
            'is_published' => $published, 'response_deadline' => now()->addDays(max(0, $days - 5))->toDateString()]);
    }

    public function test_dashboard_shows_swimmer_card_and_convocations(): void
    {
        $user = $this->family();
        $groupe = PlanningGroup::create(['name' => 'Groupe Avenir', 'slug' => 'groupe-avenir', 'section' => 'Jeunesse', 'color' => '#16a34a', 'is_active' => true]);
        $nageur = PlanningSwimmer::create(['first_name' => 'Jade', 'last_name' => 'Fictif', 'birth_year' => 2014, 'gender' => 'F',
            'license_number' => '9000001', 'photo' => 'storage/photos/jade-test.jpg', 'user_id' => $user->id, 'is_active' => true]);
        $nageur->groups()->attach($groupe);

        $prochaine = $this->competition('Meeting de Noël fictif', 20);
        $a = CompetitionAttendance::create(['competition_id' => $prochaine->id, 'planning_swimmer_id' => $nageur->id, 'status' => 'convoque']);
        $a->entries()->create(['swim_event_id' => SwimEvent::where('code', '50-nl')->value('id')]);
        CompetitionAttendance::create(['competition_id' => $this->competition('Compétition passée fictive', -10)->id, 'planning_swimmer_id' => $nageur->id, 'status' => 'confirme']);
        CompetitionAttendance::create(['competition_id' => $this->competition('Brouillon fictif', 30, false)->id, 'planning_swimmer_id' => $nageur->id, 'status' => 'convoque']);

        $this->actingAs($user)->get(route('user.dashboard'))->assertOk()
            ->assertSee('Jade FICTIF')
            ->assertSee('Groupe Avenir')
            ->assertSee('9000001')
            ->assertSee(asset('storage/photos/jade-test.jpg'), false)
            ->assertSee('Mes convocations')
            ->assertSee('Meeting de Noël fictif')
            ->assertSee('50 NL')
            ->assertSee('En attente de réponse')
            ->assertSee('Répondre')
            ->assertDontSee('Compétition passée fictive')
            ->assertDontSee('Brouillon fictif');
    }

    public function test_dashboard_without_swimmer_has_no_convocation_block(): void
    {
        $this->actingAs($this->family())->get(route('user.dashboard'))->assertOk()->assertDontSee('Mes convocations');
    }

    public function test_member_pages_show_member_header_instead_of_video(): void
    {
        $user = $this->family();
        PlanningSwimmer::create(['first_name' => 'Jade', 'last_name' => 'Fictif', 'birth_year' => 2014, 'gender' => 'F', 'user_id' => $user->id, 'is_active' => true]);

        foreach (['user.competitions.index', 'user.training.index', 'user.presence.index', 'user.performances.index'] as $route) {
            $this->actingAs($user)->get(route($route))->assertOk()
                ->assertSee('member-head', false)
                ->assertSee('Jade FICTIF')
                ->assertDontSee('hero-video', false);
        }
    }

    public function test_member_header_falls_back_to_account_name(): void
    {
        $this->actingAs($this->family())->get(route('user.competitions.index'))->assertOk()
            ->assertSee('Camille FICTIF')->assertSee('CF');
    }
}
