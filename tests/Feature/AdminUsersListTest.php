<?php

namespace Tests\Feature;

use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Administration → Utilisateurs : dernière connexion enregistrée, nageurs liés et groupes,
 * lien de recherche Google, filtre par rôle, recherche par nageur. Données fictives.
 * Lancer : php artisan test --filter=AdminUsersListTest
 */
class AdminUsersListTest extends TestCase
{
    use RefreshDatabase;

    private function account(string $role, string $first, string $last): User
    {
        $r = Role::firstOrCreate(['slug' => $role], ['name' => ucfirst($role), 'display_name' => ucfirst($role), 'level' => 0]);
        $u = User::create(['name' => "{$first} {$last}", 'first_name' => $first, 'last_name' => $last, 'email' => strtolower($first) . '@example.test',
            'password' => bcrypt('secret-password'), 'role_id' => $r->id, 'status' => 'active']);
        $u->forceFill(['email_verified_at' => now()])->save();

        return $u;
    }

    public function test_login_records_last_login(): void
    {
        $u = $this->account('user', 'Camille', 'Fictif');
        $this->assertNull($u->last_login_at);

        $this->post(route('login'), ['email' => 'camille@example.test', 'password' => 'secret-password'])->assertRedirect();
        $u->refresh();
        $this->assertNotNull($u->last_login_at);
        $this->assertSame(1, (int) $u->login_count);
        $this->assertSame('127.0.0.1', $u->last_login_ip);
    }

    public function test_list_shows_swimmers_groups_google_link_and_last_login(): void
    {
        $admin = $this->account('admin', 'Admin', 'Test');
        $famille = $this->account('user', 'Camille', 'Fictif');
        $famille->forceFill(['last_login_at' => '2026-11-05 18:30:00', 'login_count' => 3])->save();
        $this->account('user', 'Dominique', 'Exemple');

        $g = PlanningGroup::create(['name' => 'Avenirs Test', 'slug' => 'avenirs-test', 'section' => 'Jeunesse', 'is_active' => true]);
        $s = PlanningSwimmer::create(['first_name' => 'Ondine', 'last_name' => 'Fictif', 'birth_year' => 2015, 'gender' => 'F', 'user_id' => $famille->id, 'is_active' => true]);
        $s->groups()->attach($g);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk()
            ->assertSee('Nageur(s) lié(s) et groupe')
            ->assertSee('Ondine FICTIF')
            ->assertSee('Avenirs Test')
            ->assertSee('https://www.google.com/search?q=Camille+Fictif', false)
            ->assertSee('05/11/2026')
            ->assertSee('3 connexions')
            ->assertSee('Aucune connexion')
            ->assertDontSee('Jamais connecté');

        $this->get(route('admin.users.index', ['search' => 'ondine']))->assertSee('camille@example.test')->assertDontSee('dominique@example.test');
        $this->get(route('admin.users.index', ['role' => $admin->role_id]))->assertSee('admin@example.test')->assertDontSee('camille@example.test');
    }
}
