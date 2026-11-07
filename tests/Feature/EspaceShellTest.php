<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Services\MessageService;
use App\Support\EspaceMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Cadre des espaces connectés (style « extraNat ») : menu de gauche, barre du haut,
 * menus par espace, lien actif, compteur de messages, changement d'espace.
 * Lancer : php artisan test --filter=EspaceShellTest
 */
class EspaceShellTest extends TestCase
{
    use RefreshDatabase;

    private function account(string $role): User
    {
        $r = Role::firstOrCreate(['slug' => $role], ['name' => ucfirst($role), 'display_name' => $role, 'level' => 0]);
        $u = User::create(['name' => "Test {$role}", 'first_name' => 'Test', 'last_name' => ucfirst($role), 'email' => "{$role}@example.test",
            'password' => bcrypt('secret-password'), 'role_id' => $r->id, 'status' => 'active']);
        $u->forceFill(['email_verified_at' => now()])->save();

        return $u;
    }

    public function test_each_area_uses_the_sidebar_layout(): void
    {
        foreach ([
            ['admin', 'admin.dashboard', 'Administration', 'Licences FFN'],
            ['editor', 'editor.dashboard', 'Espace éditeur', 'Médiathèque'],
            ['coach', 'coach.dashboard', 'Espace coachs', 'Suivi des nageurs'],
            ['user', 'user.dashboard', 'Espace adhérent', 'Mes performances'],
            ['visitor', 'visitor.dashboard', 'Espace visiteur', "S'inscrire au club"],
        ] as [$role, $route, $label, $menuItem]) {
            $u = $this->account($role);
            $this->actingAs($u)->get(route($route))->assertOk()
                ->assertSee('esp-sidebar', false)
                ->assertSee('esp-topbar', false)
                ->assertSee($label)
                ->assertSee($menuItem)
                ->assertSee($u->email)
                ->assertSee('aria-current="page"', false);
        }
    }

    public function test_member_pages_no_longer_use_the_public_footer(): void
    {
        $this->actingAs($this->account('user'))->get(route('user.competitions.index'))->assertOk()
            ->assertSee('esp-sidebar', false)
            ->assertDontSee('cookie-consent', false);
    }

    public function test_active_section_is_expanded(): void
    {
        $this->actingAs($this->account('admin'))->get(route('admin.members.index'))->assertOk()
            ->assertSee('esp-nav-group is-active', false)
            ->assertSee('esp-nav-sublink active', false);
    }

    public function test_unread_badge_and_area_switch(): void
    {
        $admin = $this->account('admin');
        $member = $this->account('user');
        app(MessageService::class)->send($admin, collect([$member]), 'Sujet', 'Message fictif.');

        $this->actingAs($member)->get(route('user.dashboard'))->assertSee('esp-badge', false)->assertDontSee("Changer d'espace", false);
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertSee("Changer d'espace", false)
            ->assertSee(route('coach.dashboard'), false)->assertSee(route('editor.dashboard'), false);
    }

    public function test_menu_ignores_missing_routes(): void
    {
        $this->actingAs($this->account('admin'));
        foreach (array_keys(EspaceMenu::AREAS) as $area) {
            foreach (EspaceMenu::build($area) as $group) {
                $this->assertNotEmpty($group['items'], $area);
            }
        }
    }
}
