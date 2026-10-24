<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Espace éditeur (/editor/dashboard) : menu public, statut éditeur, outils, brouillons,
 * accès rapides du club et derniers contenus. Réservé aux éditeurs et administrateurs.
 * Lancer : php artisan test --filter=EditorDashboardTest
 */
class EditorDashboardTest extends TestCase
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

    public function test_editor_dashboard(): void
    {
        $editor = $this->userWithRole('editor');
        Post::create(['name' => 'Article adhérents', 'slug' => 'article-adherents', 'content' => 'Texte',
            'status' => 'published', 'visibility' => 'authenticated', 'published_at' => now()->subDay()]);
        Post::create(['name' => 'Mon brouillon', 'slug' => 'mon-brouillon', 'content' => 'Texte',
            'status' => 'draft', 'visibility' => 'public', 'created_by' => $editor->id]);

        $this->actingAs($editor)->get('/editor/dashboard')->assertOk()
            ->assertSee('Vous êtes éditeur du site.')
            ->assertSee("Mes outils d'éditeur")
            ->assertSee('/editor/posts', false)->assertSee('/editor/media', false)->assertSee('/editor/planning/creneaux', false)
            ->assertSee('1 brouillon')->assertSee('Mon brouillon')->assertSee('/editor/posts/mon-brouillon/edit', false)
            ->assertSee('Nos partenaires')->assertSee('Devenir mécène')->assertSee('Faire un don')->assertSee('Calendrier')
            ->assertSee('Article adhérents')
            ->assertSee(route('shop.index'), false);   // menu public
    }

    public function test_reserved_to_editors(): void
    {
        $this->actingAs($this->userWithRole('user'))->get('/editor/dashboard')->assertForbidden();
        $this->actingAs($this->userWithRole('admin'))->get('/editor/dashboard')->assertOk();
    }
}
