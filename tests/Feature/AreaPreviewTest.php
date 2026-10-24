<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AreaPreviewController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Système → « Aperçu des espaces » : page réservée à l'admin, et chaque page listée s'ouvre pour l'admin.
 * Lancer : php artisan test --filter=AreaPreviewTest
 */
class AreaPreviewTest extends TestCase
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

    public function test_admin_sees_links_and_every_page_opens(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->get('/admin/apercu-des-espaces')->assertOk()
            ->assertSee('Espace adhérent')->assertSee('Espace éditeur')->assertSee('Espace visiteur')
            ->assertSee('/user/dashboard', false)->assertSee('/editor/dashboard', false);

        $this->actingAs($admin)->get('/admin/dashboard')->assertSee('Aperçu des espaces');

        // public.videos.index : requête « HAVING » non acceptée par SQLite (fonctionne sous MySQL)
        $echecs = [];
        foreach (AreaPreviewController::AREAS as $area) {
            foreach ($area['links'] as [$label, $route]) {
                if (Route::has($route) && $route !== 'public.videos.index') {
                    $status = $this->actingAs($admin)->get(route($route))->status();
                    if ($status !== 200) {
                        $echecs[] = "{$route} ({$status})";
                    }
                }
            }
        }
        $this->assertSame([], $echecs);
    }

    public function test_reserved_to_admin(): void
    {
        $this->actingAs($this->userWithRole('editor'))->get('/admin/apercu-des-espaces')->assertForbidden();
    }
}
