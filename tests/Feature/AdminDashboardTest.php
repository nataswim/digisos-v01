<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tableau de bord de l'administration : cartes vers les modules, rangées par rubrique.
 * Lancer : php artisan test --filter=AdminDashboardTest
 */
class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_lists_modules_by_section(): void
    {
        $role = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin', 'display_name' => 'admin', 'level' => 0]);
        $admin = User::create([
            'name' => 'Test admin', 'email' => 'admin@example.test',
            'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();

        $textile = ShopCategory::create(['name' => 'Textile']);
        ShopProduct::create(['shop_category_id' => $textile->id, 'name' => 'Sweat']);
        ShopProduct::create(['shop_category_id' => $textile->id, 'name' => 'Bonnet']);

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk()
            ->assertSeeInOrder(['Contenus', 'Articles', 'Boutique', '2 produits', 'Multimédia', 'Vidéos', 'Sportif', 'Compétitions', 'Système', 'Utilisateurs', '1 compte', 'Gestion', 'Zones'])
            ->assertSee(route('admin.shop.products.index'), false)
            ->assertSee(route('admin.team.members.index'), false)
            ->assertSee(route('admin.ffn-licenses.index'), false);
    }
}
