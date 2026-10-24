<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Espace adhérent (/user/dashboard) : menu public, statut membre, mon espace, accès rapides,
 * derniers contenus y compris ceux réservés aux adhérents. Un visiteur est renvoyé vers son espace.
 * Lancer : php artisan test --filter=MemberDashboardTest
 */
class MemberDashboardTest extends TestCase
{
    use RefreshDatabase;

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

    private function article(string $name, string $visibility): void
    {
        Post::create(['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name), 'content' => 'Texte',
            'status' => 'published', 'visibility' => $visibility, 'published_at' => now()->subDay()]);
    }

    public function test_member_dashboard(): void
    {
        $this->article('Article public', 'public');
        $this->article('Article adhérents', 'authenticated');

        $this->actingAs($this->userWithRole('user'))->get('/user/dashboard')->assertOk()
            ->assertSee('Vous êtes membre du club.')
            ->assertSee('Ma fiche')->assertSee('Mes compétitions')->assertSee('/user/mes-competitions', false)
            ->assertSee('Nos partenaires')->assertSee('Devenir mécène')->assertSee('Faire un don')->assertSee('Calendrier')
            ->assertSee('Article public')->assertSee('Article adhérents')
            ->assertSee(route('shop.index'), false);   // menu public
    }

    public function test_visitor_is_sent_to_visitor_space(): void
    {
        $this->article('Article adhérents', 'authenticated');

        $visiteur = $this->userWithRole('visitor');
        $this->actingAs($visiteur)->get('/user/dashboard')->assertRedirect('/visitor/dashboard');
        $this->actingAs($visiteur)->get('/visitor/dashboard')->assertOk()->assertDontSee('Article adhérents');
    }
}
