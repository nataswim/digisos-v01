<?php

namespace Tests\Feature;

use App\Models\Fiche;
use App\Models\FichesCategory;
use App\Models\FichesSousCategory;
use App\Models\Page;
use App\Models\PagesCategory;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Espace visiteur : menu public, statut visiteur, accès rapides, 4 derniers contenus publics.
 * Lancer : php artisan test --filter=VisitorDashboardTest
 */
class VisitorDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function visitor(): User
    {
        $role = Role::firstOrCreate(['slug' => 'visitor'], ['name' => 'Visitor', 'display_name' => 'visitor', 'level' => 0]);
        $user = User::create([
            'name' => 'Camille Test', 'email' => 'visiteur@example.test',
            'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    public function test_visitor_dashboard(): void
    {
        foreach (range(1, 5) as $i) {
            Post::create(['name' => "Article {$i}", 'slug' => "article-{$i}", 'content' => 'Texte',
                'status' => 'published', 'visibility' => 'public', 'published_at' => now()->subDays(10 - $i)]);
        }
        Post::create(['name' => 'Article adhérents', 'slug' => 'article-adherents', 'content' => 'Texte',
            'status' => 'published', 'visibility' => 'authenticated', 'published_at' => now()]);

        $cat = FichesCategory::create(['name' => 'Infos', 'slug' => 'infos', 'is_active' => true]);
        $sous = FichesSousCategory::create(['name' => 'Bassin', 'slug' => 'bassin', 'fiches_category_id' => $cat->id, 'is_active' => true]);
        Fiche::create(['title' => 'Fiche piscine', 'short_description' => 'Accès au bassin', 'long_description' => 'Texte', 'slug' => 'fiche-piscine', 'is_published' => true, 'published_at' => now()->subDay(),
            'visibility' => 'public', 'fiches_category_id' => $cat->id, 'fiches_sous_category_id' => $sous->id]);
        Fiche::create(['title' => 'Fiche brouillon', 'short_description' => 'x', 'long_description' => 'x', 'slug' => 'fiche-brouillon', 'is_published' => false, 'visibility' => 'public',
            'fiches_category_id' => $cat->id, 'fiches_sous_category_id' => $sous->id]);

        $pcat = PagesCategory::create(['name' => 'Le club', 'slug' => 'le-club', 'is_active' => true]);
        Page::create(['title' => 'Histoire du club', 'short_description' => 'Depuis 1954', 'long_description' => 'Texte', 'slug' => 'histoire', 'is_published' => true, 'published_at' => now()->subDay(),
            'visibility' => 'public', 'pages_category_id' => $pcat->id]);

        $this->actingAs($this->visitor())->get('/visitor/dashboard')->assertOk()
            ->assertSee('Vous avez le statut « visiteur »', false)
            ->assertSee("accéder à l'espace membre du club")
            ->assertSee('Nos partenaires')->assertSee('Devenir mécène')->assertSee('Faire un don')->assertSee('Calendrier')
            ->assertSee('Article 5')->assertSee('Article 2')->assertDontSee('Article 1<')
            ->assertDontSee('Article adhérents')
            ->assertSee('Fiche piscine')->assertSee('/fiches/infos/bassin/fiche-piscine', false)->assertDontSee('Fiche brouillon')
            ->assertSee('Histoire du club')->assertSee('/pages/le-club/histoire', false)
            ->assertSee(route('shop.index'), false);   // menu public
    }

    public function test_empty_site(): void
    {
        $this->actingAs($this->visitor())->get('/visitor/dashboard')->assertOk()->assertSee('Faire un don');
    }
}
