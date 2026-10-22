<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\LinkCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Module « Liens utiles » : page publique, gestion réservée aux administrateurs.
 * Lancer : php artisan test --filter=LinksModuleTest
 */
class LinksModuleTest extends TestCase
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

    public function test_public_page(): void
    {
        $this->get('/liens-utiles')->assertOk()->assertSee('Les liens utiles arrivent bientôt');

        $federations = LinkCategory::create(['name' => 'Fédérations', 'sort_order' => 1, 'description' => 'Les sites officiels.']);
        $sante = LinkCategory::create(['name' => 'Santé', 'sort_order' => 2]);
        $cachee = LinkCategory::create(['name' => 'Archives', 'is_active' => false]);

        Link::create(['link_category_id' => $federations->id, 'title' => 'FFN', 'url' => 'https://www.ffnatation.fr',
            'description' => 'Résultats et calendrier.', 'keywords' => 'résultats,  licence , Résultats']);
        Link::create(['link_category_id' => $federations->id, 'title' => 'Lien masqué', 'url' => 'https://example.org', 'is_active' => false]);
        Link::create(['link_category_id' => $sante->id, 'title' => 'Manger Bouger', 'url' => 'https://www.mangerbouger.fr', 'image' => '/storage/media/mb.png']);
        Link::create(['link_category_id' => $cachee->id, 'title' => 'Ancien site', 'url' => 'https://old.example.org']);

        $this->get('/liens-utiles')->assertOk()
            ->assertSeeInOrder(['Fédérations', 'Les sites officiels.', 'FFN', 'ffnatation.fr', 'Résultats et calendrier.', 'résultats', 'licence', 'Santé', 'Manger Bouger'])
            ->assertSee('https://www.ffnatation.fr/favicon.ico', false)   // sans image : icône du site
            ->assertSee('/storage/media/mb.png', false)
            ->assertSee('target="_blank" rel="noopener"', false)
            ->assertDontSee('Lien masqué')
            ->assertDontSee('Ancien site');

        // Mots-clés rangés : doublons retirés, espaces nettoyés
        $this->assertSame('résultats, licence', Link::where('title', 'FFN')->value('keywords'));

        // Lien dans le menu « Vie du club »
        $this->get('/')->assertSee(route('links.index'), false);
    }

    public function test_admin_manages_categories_and_links(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->get('/admin/liens-utiles/liens/create')->assertRedirect('/admin/liens-utiles/categories/create');

        $this->actingAs($admin)->post('/admin/liens-utiles/categories', ['name' => 'Fédérations', 'is_active' => '1'])
            ->assertRedirect('/admin/liens-utiles/categories');
        $category = LinkCategory::where('slug', 'federations')->firstOrFail();

        // Adresse saisie sans https:// : complétée
        $this->actingAs($admin)->post('/admin/liens-utiles/liens', [
            'link_category_id' => $category->id, 'title' => 'FFN', 'url' => 'www.ffnatation.fr',
            'description' => 'Site officiel', 'keywords' => 'FFN, licence', 'is_active' => '1',
        ])->assertRedirect();
        $link = Link::firstOrFail();
        $this->assertSame('https://www.ffnatation.fr', $link->url);

        $this->actingAs($admin)->get('/admin/liens-utiles/liens')->assertOk()->assertSee('FFN')->assertSee('ffnatation.fr');
        $this->actingAs($admin)->get('/admin/liens-utiles/liens?q=licence')->assertOk()->assertSee('ffnatation.fr');
        $this->actingAs($admin)->get('/admin/liens-utiles/liens?q=introuvable')->assertOk()->assertSee('Aucun lien ne correspond');

        $this->actingAs($admin)->put("/admin/liens-utiles/liens/{$link->id}", [
            'link_category_id' => $category->id, 'title' => 'Fédération Française de Natation', 'url' => 'https://www.ffnatation.fr',
        ])->assertRedirect();
        $this->assertSame('Fédération Française de Natation', $link->fresh()->title);
        $this->assertFalse($link->fresh()->is_active);

        // Catégorie non vide : pas de suppression
        $this->actingAs($admin)->delete("/admin/liens-utiles/categories/{$category->id}")->assertSessionHas('error');
        $this->assertModelExists($category);

        $this->actingAs($admin)->delete("/admin/liens-utiles/liens/{$link->id}")->assertRedirect('/admin/liens-utiles/liens');
        $this->actingAs($admin)->delete("/admin/liens-utiles/categories/{$category->id}")->assertSessionHas('success');
        $this->assertModelMissing($category);
    }

    public function test_validation(): void
    {
        $admin = $this->userWithRole('admin');
        $category = LinkCategory::create(['name' => 'Divers']);

        $this->actingAs($admin)->post('/admin/liens-utiles/liens', [
            'link_category_id' => $category->id, 'title' => '', 'url' => 'javascript:alert(1)', 'image' => 'javascript:x',
        ])->assertSessionHasErrors(['title', 'url', 'image']);

        $this->assertSame(0, Link::count());
    }

    public function test_only_admins_manage_links(): void
    {
        $this->get('/admin/liens-utiles/liens')->assertRedirect('/login');

        foreach (['editor', 'user'] as $slug) {
            $this->actingAs($this->userWithRole($slug))->get('/admin/liens-utiles/liens')->assertForbidden();
        }
    }
}
