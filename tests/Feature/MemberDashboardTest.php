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

    public function test_dashboard_shows_group_slots_and_member_file(): void
    {
        $user = $this->family();
        $groupe = PlanningGroup::create(['name' => 'Groupe Avenir', 'slug' => 'groupe-avenir', 'section' => 'Jeunesse', 'is_active' => true]);
        \App\Models\PlanningSlot::create(['planning_group_id' => $groupe->id, 'period' => 'scolaire', 'day_of_week' => 3, 'starts_at' => '17:30', 'ends_at' => '18:30', 'location' => 'Piscine fictive', 'is_active' => true]);
        \App\Models\PlanningSlot::create(['planning_group_id' => $groupe->id, 'period' => 'vacances', 'day_of_week' => 2, 'starts_at' => '10:00', 'ends_at' => '11:00', 'is_active' => true]);
        $nageur = PlanningSwimmer::create(['first_name' => 'Jade', 'last_name' => 'Fictif', 'birth_year' => 2014, 'gender' => 'F', 'user_id' => $user->id, 'is_active' => true]);
        $nageur->groups()->attach($groupe);

        $season = \App\Models\Membership::currentSeason();
        $cat = \App\Models\MemberCategory::where('code', 'RELEVE')->first();
        $fiche = \App\Models\Member::create(['last_name' => 'Fictif', 'first_name' => 'Jade', 'gender' => 'F', 'birth_date' => '2014-03-15', 'city' => 'Villefictive',
            'phone1' => '0600000000', 'image_rights' => false, 'planning_swimmer_id' => $nageur->id,
            'guardians' => [['name' => 'Parent Fictif', 'relation' => 'mère', 'phone' => '0611111111']]]);
        $a = $fiche->memberships()->create(['season' => $season, 'member_category_id' => $cat->id, 'price_cents' => 19000, 'status' => 'en_cours', 'file_items' => ['fiche', 'photo']]);
        \App\Models\MembershipPayment::create(['membership_id' => $a->id, 'method' => 'cheque', 'amount_cents' => 10000, 'cashed_on' => now()->toDateString()]);

        // Fiche d'une autre famille, même prénom : ne doit jamais apparaître
        \App\Models\Member::create(['last_name' => 'Etranger', 'first_name' => 'Jade', 'birth_date' => '2014-01-01', 'city' => 'Ailleurs-Test']);
        // Fiche adulte rattachée au compte, sans nageur
        \App\Models\Member::create(['last_name' => 'Fictif', 'first_name' => 'Camille', 'birth_date' => '1985-01-01', 'user_id' => $user->id]);

        $this->actingAs($user)->get(route('user.dashboard'))->assertOk()
            ->assertSee(route('schedule') . '#groupe-groupe-avenir', false)
            ->assertSee('Mercredi')->assertSee('Piscine fictive')
            ->assertSee('vacances scolaires (1 créneau)')
            ->assertSee('Fiche adhérent · saison ' . $season)
            ->assertSee($cat->label)->assertSee('Dossier en cours')
            ->assertSee('Fiche sanitaire')            // pièce manquante
            ->assertSee('reste 90,00')
            ->assertSee('refusé')                     // droit à l'image
            ->assertSee('Parent Fictif')
            ->assertSee('FICTIF Camille')             // carte de la fiche adulte
            ->assertDontSee('Ailleurs-Test')->assertDontSee('ETRANGER');
    }

    public function test_file_items_helpers_accept_both_formats(): void
    {
        $m = new \App\Models\Membership(['file_items' => ['fiche', 'photo']]);
        $this->assertSame(['fiche', 'photo'], $m->providedFileItems());
        $this->assertArrayHasKey('sanitaire', $m->missingFileItems());

        $tout = new \App\Models\Membership(['file_items' => array_keys(\App\Models\Membership::FILE_ITEMS)]);
        $tout->syncFileComplete();
        $this->assertTrue($tout->file_complete);

        $ancien = new \App\Models\Membership(['file_items' => ['fiche' => true, 'photo' => false]]);
        $this->assertSame(['fiche'], $ancien->providedFileItems());
    }

    public function test_dashboard_has_coach_pages_links(): void
    {
        $this->actingAs($this->family())->get(route('user.dashboard'))->assertOk()
            ->assertSee('Pages de votre entraîneur')
            ->assertSee('espace-tuiles--entraineur', false)
            ->assertSee('href="https://nataswim.fr/articles"', false)
            ->assertSee('https://nataswim.fr/fiches', false)->assertSee('https://nataswim.fr/videos', false)
            ->assertSee('https://nataswim.fr/workouts', false)->assertSee('https://nataswim.fr/exercices', false)
            ->assertSee('https://nataswim.fr/outils', false)
            ->assertSee('target="_blank" rel="noopener"', false);
    }
}
