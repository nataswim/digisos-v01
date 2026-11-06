<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\CompetitionEvent;
use App\Models\Role;
use App\Models\SwimEvent;
use App\Models\User;
use Database\Seeders\SwimEventsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Référentiel des épreuves : 25 m, Divers (2 nages), relais 10×50 NL et 4×200 NL,
 * page Compétitions → Épreuves (ajout, modification, désactivation, suppression), grille du programme.
 * Lancer : php artisan test --filter=SwimEventsTest
 */
class SwimEventsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin', 'display_name' => 'admin', 'level' => 0]);
        $user = User::create(['name' => 'Test admin', 'email' => 'admin@example.test', 'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active']);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    public function test_migration_adds_the_new_events_in_order(): void
    {
        foreach (['25-nl' => '25 NL', '25-dos' => '25 Dos', '25-brasse' => '25 Brasse', '25-pap' => '25 Pap',
            '50-divers' => '50 Divers', '100-divers' => '100 Divers', '10x50-nl' => '10×50 NL', '4x200-nl' => '4×200 NL'] as $code => $label) {
            $this->assertSame($label, SwimEvent::where('code', $code)->value('label'), $code);
        }
        $this->assertTrue(SwimEvent::where('code', '10x50-nl')->value('is_relay'));
        $this->assertFalse(SwimEvent::where('code', '50-divers')->value('is_relay'));

        $dos = SwimEvent::where('stroke', 'dos')->ordered()->pluck('distance')->all();
        $this->assertSame([25, 50, 100, 200], $dos);
        $this->assertLessThan(SwimEvent::where('code', '50-nl')->value('sort_order'), SwimEvent::where('code', '25-nl')->value('sort_order'));
    }

    public function test_seeder_is_idempotent_and_keeps_admin_changes(): void
    {
        SwimEvent::where('code', '50-divers')->update(['label' => '50 m 2 nages', 'is_active' => false]);
        $avant = SwimEvent::count();
        (new SwimEventsSeeder())->run();

        $this->assertSame($avant, SwimEvent::count());
        $this->assertSame('50 m 2 nages', SwimEvent::where('code', '50-divers')->value('label'));
        $this->assertFalse(SwimEvent::where('code', '50-divers')->value('is_active'));
    }

    public function test_events_page_add_update_and_delete(): void
    {
        $this->actingAs($this->admin());
        $this->get(route('admin.competitions.events.index'))->assertOk()->assertSee('10×50 NL')->assertSee('Divers (2 nages)');

        $this->post(route('admin.competitions.events.store'), ['type' => 'relais', 'legs' => 8, 'distance' => 25, 'stroke' => 'nl'])->assertRedirect();
        $relais = SwimEvent::where('code', '8x25-nl')->firstOrFail();
        $this->assertSame('8×25 NL', $relais->label);
        $this->assertTrue($relais->is_relay);

        $this->post(route('admin.competitions.events.store'), ['type' => 'individuel', 'distance' => 50, 'stroke' => 'divers'])
            ->assertSessionHasErrors('distance');   // existe déjà

        $this->post(route('admin.competitions.events.store'), ['type' => 'individuel', 'distance' => 75, 'stroke' => 'dos', 'label' => '75 Dos (animation)'])->assertRedirect();
        $e75 = SwimEvent::where('code', '75-dos')->firstOrFail();
        $this->assertSame('75 Dos (animation)', $e75->label);
        $this->assertGreaterThan(SwimEvent::where('code', '50-dos')->value('sort_order'), $e75->sort_order);
        $this->assertLessThan(SwimEvent::where('code', '100-dos')->value('sort_order'), $e75->sort_order);

        $this->put(route('admin.competitions.events.update', $relais), ['label' => '8×25 NL (animation)', 'sort_order' => 1500, 'is_active' => '0'])->assertRedirect();
        $this->assertFalse($relais->fresh()->is_active);
        $this->assertSame('8×25 NL (animation)', $relais->fresh()->label);

        $this->delete(route('admin.competitions.events.destroy', $e75))->assertRedirect();
        $this->assertNull(SwimEvent::find($e75->id));
    }

    public function test_used_event_is_deactivated_not_deleted_and_shows_in_program(): void
    {
        $this->actingAs($this->admin());
        $c = Competition::create(['title' => 'Meeting fictif', 'season' => '2026-2027', 'level' => 'departemental', 'kind' => 'individuel', 'starts_on' => now()->addMonth()->toDateString(), 'ends_on' => now()->addMonth()->toDateString(), 'venue' => 'Piscine fictive', 'city' => 'Villefictive', 'pool_length' => 25, 'is_published' => true]);
        $session = $c->sessions()->create(['label' => 'Réunion 1', 'day' => now()->addMonth()->toDateString()]);
        $relais = SwimEvent::where('code', '10x50-nl')->first();
        $vingtCinq = SwimEvent::where('code', '25-pap')->first();

        $this->get(route('admin.competitions.program.edit', $c))->assertOk()->assertSee('10×50 NL')->assertSee('25 Pap')->assertSee('50 Divers')->assertSee('4×200 NL');

        $this->put(route('admin.competitions.program.update', $c), ['grid' => [$session->id => [
            $relais->id => ['F' => ['series'], 'M' => ['series']],
            $vingtCinq->id => ['F' => ['series']],
        ]]])->assertRedirect();
        $this->assertSame(3, CompetitionEvent::where('competition_session_id', $session->id)->count());

        $this->delete(route('admin.competitions.events.destroy', $relais))->assertRedirect();
        $this->assertFalse($relais->fresh()->is_active);
    }
}
