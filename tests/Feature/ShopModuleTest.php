<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Module Boutique vitrine : pages publiques, gestion réservée aux administrateurs.
 * Lancer : php artisan test --filter=ShopModuleTest
 */
class ShopModuleTest extends TestCase
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

    private function catalogue(): array
    {
        $textile = ShopCategory::create(['name' => 'Textile', 'sort_order' => 1]);
        $materiel = ShopCategory::create(['name' => 'Matériel', 'sort_order' => 2]);
        $cachee = ShopCategory::create(['name' => 'Archives', 'is_active' => false]);

        $bonnet = ShopProduct::create([
            'shop_category_id' => $materiel->id, 'name' => 'Bonnet silicone CNBB', 'summary' => 'Aux couleurs du club',
            'price_min' => 12, 'image' => '/storage/media/bonnet.png',
            'gallery' => ['/storage/media/bonnet-dos.png'],
            'order_url' => 'https://www.helloasso.com/associations/cnbb/boutiques/bonnet',
        ]);
        $sweat = ShopProduct::create([
            'shop_category_id' => $textile->id, 'name' => 'Sweat du club', 'price_min' => 30, 'price_max' => 35.5,
            'price_note' => 'selon la taille', 'order_url' => 'https://fournisseur.example.fr/cnbb', 'badge' => 'nouveau',
        ]);
        ShopProduct::create(['shop_category_id' => $textile->id, 'name' => 'Tee-shirt masqué', 'price_min' => 10, 'is_active' => false]);
        ShopProduct::create(['shop_category_id' => $cachee->id, 'name' => 'Ancien maillot', 'price_min' => 5]);
        ShopProduct::create(['shop_category_id' => $textile->id, 'name' => 'Serviette épuisée', 'price_min' => 20, 'badge' => 'epuise', 'order_url' => 'https://fournisseur.example.fr/serviette']);

        return compact('textile', 'materiel', 'cachee', 'bonnet', 'sweat');
    }

    public function test_price_labels(): void
    {
        $this->assertSame("12\u{00A0}€", (new ShopProduct(['price_min' => 12]))->price_label);
        $this->assertSame("12,50\u{00A0}€", (new ShopProduct(['price_min' => 12.5]))->price_label);
        $this->assertSame("10 à 15\u{00A0}€", (new ShopProduct(['price_min' => 10, 'price_max' => 15]))->price_label);
        $this->assertSame("15\u{00A0}€", (new ShopProduct(['price_max' => 15]))->price_label);
        $this->assertNull((new ShopProduct())->price_label);
    }

    public function test_public_pages_show_only_visible_products(): void
    {
        ['bonnet' => $bonnet] = $this->catalogue();

        // Accueil : les catégories, puis seulement les produits « Nouveau » ou à la une
        $this->get('/boutique')->assertOk()
            ->assertSeeInOrder(['Nos catégories', 'Textile', 'Matériel', 'Nouveautés et produits à la une', 'Sweat du club'])
            ->assertSee("30 à 35,50\u{00A0}€", false)
            ->assertDontSee('Bonnet silicone CNBB')   // ni nouveau ni à la une
            ->assertDontSee('Serviette épuisée')
            ->assertDontSee('Tee-shirt masqué')
            ->assertDontSee('Ancien maillot')
            ->assertDontSee('Archives');

        $bonnet->update(['is_featured' => true]);
        $this->get('/boutique')->assertSee('Bonnet silicone CNBB')->assertSee('À la une');

        // Page d'une catégorie : tous ses produits affichés
        $this->get('/boutique/textile')->assertOk()
            ->assertSee('Sweat du club')
            ->assertSee('Serviette épuisée')
            ->assertDontSee('https://fournisseur.example.fr/serviette', false) // épuisé : pas de bouton
            ->assertDontSee('Bonnet silicone CNBB');
        $this->get('/boutique/materiel')->assertSee('https://www.helloasso.com/associations/cnbb/boutiques/bonnet', false);
        $this->get('/boutique/archives')->assertNotFound();

        $this->get('/boutique/materiel/bonnet-silicone-cnbb')->assertOk()
            ->assertSee('Bonnet silicone CNBB')
            ->assertSee('/storage/media/bonnet-dos.png', false)
            ->assertSee('helloasso.com');

        // Produit masqué, ou demandé dans une autre catégorie : introuvable
        $this->get('/boutique/textile/tee-shirt-masque')->assertNotFound();
        $this->get('/boutique/textile/bonnet-silicone-cnbb')->assertNotFound();
    }

    public function test_ticker_before_footer_on_public_pages(): void
    {
        // Sans produit nouveau ni à la une : pas de bandeau
        $this->get('/contact')->assertOk()->assertDontSee('À découvrir à la boutique');

        ['bonnet' => $bonnet] = $this->catalogue();

        // « Sweat du club » a le badge Nouveau ; le bonnet n'est pas encore à la une
        $this->get('/contact')->assertOk()
            ->assertSee('À découvrir à la boutique')
            ->assertSee('Sweat du club')
            ->assertSee('/boutique/textile/sweat-du-club', false)
            ->assertDontSee('Bonnet silicone CNBB')
            ->assertDontSee('Serviette épuisée');

        $bonnet->update(['is_featured' => true]);
        $this->get('/contact')->assertSee('Bonnet silicone CNBB');

        // Pas de bandeau sur les pages de la boutique, qui montrent déjà ces produits
        $this->get('/boutique')->assertDontSee('À découvrir à la boutique');
        $this->get('/boutique/materiel/bonnet-silicone-cnbb')->assertDontSee('À découvrir à la boutique');
    }

    public function test_empty_shop(): void
    {
        $this->get('/boutique')->assertOk()->assertSee('La boutique ouvre bientôt');
    }

    public function test_admin_manages_categories_and_products(): void
    {
        $admin = $this->userWithRole('admin');

        // Sans catégorie, l'ajout d'un produit renvoie vers la création d'une catégorie
        $this->actingAs($admin)->get('/admin/boutique/produits/create')->assertRedirect('/admin/boutique/categories/create');

        $this->actingAs($admin)->post('/admin/boutique/categories', ['name' => 'Textile', 'is_active' => '1'])
            ->assertRedirect('/admin/boutique/categories');
        $category = ShopCategory::firstWhere('name', 'Textile');

        $this->actingAs($admin)->get('/admin/boutique/produits/create')->assertOk()
            ->assertSee('description-editor', false)
            ->assertSee("openMediaSelector('image', 'image-preview')", false);

        $this->actingAs($admin)->post('/admin/boutique/produits', [
            'shop_category_id' => $category->id,
            'name' => 'Sweat du club',
            'price_min' => '30',
            'price_max' => '35,50',
            'image' => '/storage/media/sweat.png',
            'gallery' => ['/storage/media/sweat-dos.png', '', '/storage/media/sweat-dos.png'],
            'order_url' => 'https://www.helloasso.com/cnbb',
            'description' => '<p>Coton <strong>bio</strong></p><script>alert(1)</script>',
            'is_active' => '1',
        ])->assertRedirect('/admin/boutique/produits');

        $product = ShopProduct::firstWhere('name', 'Sweat du club');
        $this->assertEquals(35.5, (float) $product->price_max);
        $this->assertSame(['/storage/media/sweat-dos.png'], $product->gallery);
        $this->assertStringContainsString('<strong>bio</strong>', $product->description);
        $this->assertStringNotContainsString('<script', $product->description);

        $this->actingAs($admin)->get("/admin/boutique/produits/{$product->slug}/edit")->assertOk()->assertSee('Sweat du club');

        $this->actingAs($admin)->put("/admin/boutique/produits/{$product->slug}", [
            'shop_category_id' => $category->id, 'name' => 'Sweat du club', 'price_min' => '32', 'is_active' => '1',
        ])->assertRedirect('/admin/boutique/produits');
        $this->assertEquals(32, (float) $product->fresh()->price_min);
        $this->assertNull($product->fresh()->price_max);

        // Catégorie non vide : suppression refusée
        $this->actingAs($admin)->delete("/admin/boutique/categories/{$category->slug}");
        $this->assertDatabaseHas('shop_categories', ['id' => $category->id]);

        $this->actingAs($admin)->delete("/admin/boutique/produits/{$product->slug}")->assertRedirect('/admin/boutique/produits');
        $this->actingAs($admin)->delete("/admin/boutique/categories/{$category->slug}");
        $this->assertDatabaseMissing('shop_categories', ['id' => $category->id]);
    }

    public function test_validation(): void
    {
        $admin = $this->userWithRole('admin');
        $category = ShopCategory::create(['name' => 'Textile']);

        $this->actingAs($admin)->post('/admin/boutique/produits', [
            'shop_category_id' => $category->id, 'name' => 'Test',
            'price_min' => '20', 'price_max' => '10',
            'order_url' => 'javascript:alert(1)',
            'image' => 'javascript:alert(2)',
        ])->assertSessionHasErrors(['price_max', 'order_url', 'image']);

        $this->assertDatabaseCount('shop_products', 0);
    }

    public function test_only_admins_manage_the_shop(): void
    {
        $this->get('/admin/boutique/produits')->assertRedirect('/login');

        foreach (['editor', 'user', 'visitor'] as $slug) {
            $this->actingAs($this->userWithRole($slug))->get('/admin/boutique/produits')->assertForbidden();
        }
    }
}
