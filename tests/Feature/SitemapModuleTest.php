<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Post;
use App\Models\Role;
use App\Models\SitemapUrl;
use App\Models\User;
use App\Services\SitemapService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Plan du site : découverte des pages publiques, approbation, génération de /sitemap.xml.
 * Lancer : php artisan test --filter=SitemapModuleTest
 */
class SitemapModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        @unlink(SitemapService::filePath());
    }

    protected function tearDown(): void
    {
        @unlink(SitemapService::filePath());
        parent::tearDown();
    }

    private function admin(string $slug = 'admin'): User
    {
        $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'display_name' => $slug, 'level' => 0]);
        $user = User::create([
            'name' => "Test {$slug}", 'email' => "{$slug}@example.test",
            'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function article(string $name, string $visibility = 'public', string $status = 'published'): Post
    {
        return Post::create([
            'name' => $name, 'slug' => \Illuminate\Support\Str::slug($name), 'content' => 'Texte',
            'status' => $status, 'visibility' => $visibility, 'published_at' => now()->subDay(),
        ]);
    }

    public function test_discovery_finds_public_pages_only(): void
    {
        $this->article('Résultats du meeting');
        $this->article('Infos réservées', 'authenticated');
        $this->article('Brouillon', 'public', 'draft');
        Partner::create(['name' => 'Garage Martin', 'level' => 'or']);
        Partner::create(['name' => 'Ancien partenaire', 'level' => 'bronze', 'is_active' => false]);

        // Anciennes adresses du site d'origine : doivent disparaître
        SitemapUrl::create(['url' => 'https://nataswim.fr/workouts', 'type' => 'static', 'priority' => 0.8, 'changefreq' => 'weekly', 'is_approved' => true]);
        // Adresse ajoutée à la main : gardée
        SitemapUrl::create(['url' => 'https://example.org/page', 'type' => 'manual', 'priority' => 0.5, 'changefreq' => 'weekly', 'is_approved' => true]);

        $stats = app(SitemapService::class)->discoverUrls();

        $this->assertSame([], $stats['errors']);
        $this->assertSame(1, $stats['removed']);
        $urls = SitemapUrl::pluck('is_approved', 'url');

        $this->assertTrue($urls[route('home')]);                                  // page fixe : approuvée d'office
        $this->assertTrue($urls[route('team.index')]);
        $this->assertFalse($urls[route('posts.public.show', 'resultats-du-meeting')]);   // contenu : à approuver
        $this->assertArrayHasKey(route('partners.show', 'garage-martin'), $urls->all());
        $this->assertArrayNotHasKey(route('posts.public.show', 'infos-reservees'), $urls->all());
        $this->assertArrayNotHasKey(route('posts.public.show', 'brouillon'), $urls->all());
        $this->assertArrayNotHasKey(route('partners.show', 'ancien-partenaire'), $urls->all());
        $this->assertArrayNotHasKey('https://nataswim.fr/workouts', $urls->all());
        $this->assertArrayHasKey('https://example.org/page', $urls->all());

        // Une approbation donnée est conservée à la découverte suivante
        $article = SitemapUrl::where('url', route('posts.public.show', 'resultats-du-meeting'))->first();
        $article->update(['is_approved' => true, 'priority' => 0.4]);
        $again = app(SitemapService::class)->discoverUrls();
        $this->assertSame(0, $again['new']);
        $this->assertTrue($article->fresh()->is_approved);
        $this->assertSame('0.4', (string) $article->fresh()->priority);

        // Article dépublié : retiré à la découverte suivante
        Post::where('slug', 'resultats-du-meeting')->update(['status' => 'draft']);
        app(SitemapService::class)->discoverUrls();
        $this->assertModelMissing($article);
    }

    public function test_admin_discovers_and_generates_the_sitemap(): void
    {
        $admin = $this->admin();
        $this->article('Résultats du meeting');

        $this->get('/sitemap.xml')->assertNotFound();   // rien d'approuvé pour l'instant

        $this->actingAs($admin)->get('/admin/sitemap')->assertOk()->assertSee('Découvrir');
        $this->actingAs($admin)->post('/admin/sitemap/discover')->assertRedirect('/admin/sitemap')
            ->assertSessionHas('success');
        $this->actingAs($admin)->get('/admin/sitemap?source=posts')->assertOk()
            ->assertSee(route('posts.public.show', 'resultats-du-meeting'), false)
            ->assertSee('Articles');

        // Avant génération : /sitemap.xml est construit à partir des adresses approuvées (pages fixes)
        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>' . route('home') . '</loc>', false)
            ->assertDontSee('resultats-du-meeting');

        // Approbation de l'article puis génération du fichier
        $article = SitemapUrl::where('source', 'posts')->firstOrFail();
        $this->actingAs($admin)->post("/admin/sitemap/{$article->id}/toggle")->assertRedirect('/admin/sitemap');
        $this->actingAs($admin)->post('/admin/sitemap/generate')->assertRedirect('/admin/sitemap')->assertSessionHas('success');

        $this->assertFileExists(SitemapService::filePath());
        $xml = file_get_contents(SitemapService::filePath());
        $this->assertStringContainsString('<loc>' . route('posts.public.show', 'resultats-du-meeting') . '</loc>', $xml);
        $this->assertNotFalse(simplexml_load_string($xml));

        // Tri : une colonne inconnue est ignorée
        $this->actingAs($admin)->get('/admin/sitemap?order_by=password')->assertOk();
    }

    public function test_only_admins_manage_the_sitemap(): void
    {
        $this->get('/admin/sitemap')->assertRedirect('/login');
        $this->actingAs($this->admin('editor'))->post('/admin/sitemap/discover')->assertForbidden();
    }
}
