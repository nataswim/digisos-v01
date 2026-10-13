<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Module Partenaires : pages publiques, gestion admin / éditeur, logo.
 * Lancer : php artisan test --filter=PartnersModuleTest
 */
class PartnersModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function userWithRole(string $slug): User
    {
        $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'display_name' => $slug, 'level' => 0]);

        $user = User::create([
            'name' => "Test {$slug}",
            'email' => "{$slug}@example.test",
            'password' => bcrypt('secret-password'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    public function test_public_page_groups_partners_by_level_and_hides_inactive(): void
    {
        Partner::create(['name' => 'Banque Principale', 'level' => 'principal', 'summary' => 'Notre premier soutien']);
        Partner::create(['name' => 'Garage Doré', 'level' => 'or']);
        Partner::create(['name' => 'Fleuriste Bronze', 'level' => 'bronze']);
        Partner::create(['name' => 'Ancien Partenaire', 'level' => 'argent', 'is_active' => false]);

        $this->get('/partenaires')
            ->assertOk()
            ->assertSeeInOrder(['Partenaire principal', 'Banque Principale', 'Partenaires Or', 'Garage Doré', 'Partenaires Bronze', 'Fleuriste Bronze'])
            ->assertDontSee('Ancien Partenaire')
            ->assertDontSee('Partenaires Argent');   // niveau vide : bloc non affiché
    }

    public function test_public_page_without_partners_shows_empty_message(): void
    {
        $this->get('/partenaires')->assertOk()->assertSee('arrive bientôt');
    }

    public function test_detail_page_shows_fields_and_inactive_partner_is_404(): void
    {
        $partner = Partner::create([
            'name' => 'Boulangerie du Bocage',
            'level' => 'or',
            'category' => 'sponsor',
            'website' => 'https://www.boulangerie-bocage.fr',
            'partner_since' => 2019,
            'presentation' => "Premier paragraphe.\n\nDeuxième paragraphe.",
            'actions' => "- Lots pour le meeting\nFinancement des bonnets",
        ]);

        $this->get('/partenaires/boulangerie-du-bocage')
            ->assertOk()
            ->assertSee('Boulangerie du Bocage')
            ->assertSee('Partenaires Or')
            ->assertSee('Sponsor')
            ->assertSee('2019')
            ->assertSee('boulangerie-bocage.fr')
            ->assertSee('Deuxième paragraphe.')
            ->assertSee('Lots pour le meeting')
            ->assertSee('Financement des bonnets');

        $partner->update(['is_active' => false]);
        $this->get('/partenaires/boulangerie-du-bocage')->assertNotFound();
    }

    public function test_slug_is_unique(): void
    {
        $a = Partner::create(['name' => 'Club Sport', 'level' => 'bronze']);
        $b = Partner::create(['name' => 'Club Sport', 'level' => 'bronze']);

        $this->assertSame('club-sport', $a->slug);
        $this->assertSame('club-sport-2', $b->slug);
    }

    public function test_admin_can_create_update_and_delete_partner_with_logo(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->get('/admin/partenaires')->assertOk()->assertSee('Partenaires du club');
        $this->actingAs($admin)->get('/admin/partenaires/create')->assertOk()->assertSee('Nouveau partenaire');

        $this->actingAs($admin)->post('/admin/partenaires', [
            'name' => 'Imprimerie Moderne',
            'level' => 'argent',
            'category' => 'fournisseur',
            'website' => 'https://imprimerie.example.fr',
            'partner_since' => 2021,
            'is_active' => '1',
            'logo' => UploadedFile::fake()->image('logo.png', 300, 120),
        ])->assertRedirect('/admin/partenaires');

        $partner = Partner::firstWhere('name', 'Imprimerie Moderne');
        $this->assertNotNull($partner->logo_path);
        Storage::disk('public')->assertExists($partner->logo_path);
        $ancienLogo = $partner->logo_path;

        $this->actingAs($admin)->get("/admin/partenaires/{$partner->slug}/edit")->assertOk()->assertSee('Imprimerie Moderne');

        // Remplacement du logo : l'ancien fichier est supprimé
        $this->actingAs($admin)->put("/admin/partenaires/{$partner->slug}", [
            'name' => 'Imprimerie Moderne',
            'level' => 'or',
            'is_active' => '1',
            'logo' => UploadedFile::fake()->image('nouveau.jpg', 300, 120),
        ])->assertRedirect('/admin/partenaires');

        $partner->refresh();
        $this->assertSame('or', $partner->level);
        Storage::disk('public')->assertMissing($ancienLogo);
        Storage::disk('public')->assertExists($partner->logo_path);

        // Suppression du logo par la case à cocher
        $this->actingAs($admin)->put("/admin/partenaires/{$partner->slug}", [
            'name' => 'Imprimerie Moderne', 'level' => 'or', 'is_active' => '1', 'remove_logo' => '1',
        ]);
        $this->assertNull($partner->fresh()->logo_path);

        $this->actingAs($admin)->delete("/admin/partenaires/{$partner->slug}")->assertRedirect('/admin/partenaires');
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);
    }

    public function test_validation_rejects_bad_website_year_and_svg(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/admin/partenaires', [
            'name' => 'Test',
            'level' => 'or',
            'website' => 'javascript:alert(1)',
            'partner_since' => 1900,
            'logo' => UploadedFile::fake()->create('logo.svg', 10, 'image/svg+xml'),
        ])->assertSessionHasErrors(['website', 'partner_since', 'logo']);

        $this->assertDatabaseCount('partners', 0);
    }

    public function test_editor_can_manage_partners_in_editor_area(): void
    {
        $editor = $this->userWithRole('editor');

        $this->actingAs($editor)->get('/editor/partenaires')->assertOk();
        $this->actingAs($editor)->post('/editor/partenaires', ['name' => 'Mairie', 'level' => 'principal', 'is_active' => '1'])
            ->assertRedirect('/editor/partenaires');

        $this->assertDatabaseHas('partners', ['name' => 'Mairie', 'level' => 'principal']);
        $this->actingAs($editor)->get('/admin/partenaires')->assertForbidden();
    }

    public function test_members_and_visitors_cannot_access_management(): void
    {
        $this->get('/admin/partenaires')->assertRedirect('/login');

        foreach (['user', 'visitor'] as $slug) {
            $member = $this->userWithRole($slug);
            $this->actingAs($member)->get('/admin/partenaires')->assertForbidden();
            $this->actingAs($member)->get('/editor/partenaires')->assertForbidden();
        }
    }
}
