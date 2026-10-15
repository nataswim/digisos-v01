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

    public function test_admin_can_create_update_and_delete_partner_with_uploaded_logo(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->get('/admin/partenaires')->assertOk()->assertSee('Partenaires du club');
        $this->actingAs($admin)->get('/admin/partenaires/create')->assertOk()
            ->assertSee('Nouveau partenaire')
            ->assertSee('presentation-editor', false)
            ->assertSee("openMediaSelector('logo', 'logo-preview')", false);

        $this->actingAs($admin)->post('/admin/partenaires', [
            'name' => 'Imprimerie Moderne',
            'level' => 'argent',
            'category' => 'fournisseur',
            'website' => 'https://imprimerie.example.fr',
            'partner_since' => 2021,
            'is_active' => '1',
            'logo_file' => UploadedFile::fake()->image('logo.png', 300, 120),
        ])->assertRedirect('/admin/partenaires');

        $partner = Partner::firstWhere('name', 'Imprimerie Moderne');
        $this->assertStringStartsWith('/storage/partners/', $partner->logo);
        $ancienFichier = substr($partner->logo, strlen('/storage/'));
        Storage::disk('public')->assertExists($ancienFichier);

        $this->actingAs($admin)->get("/admin/partenaires/{$partner->slug}/edit")->assertOk()->assertSee('Imprimerie Moderne');

        // Remplacement par une image de la médiathèque : l'ancien fichier envoyé est supprimé
        $this->actingAs($admin)->put("/admin/partenaires/{$partner->slug}", [
            'name' => 'Imprimerie Moderne',
            'level' => 'or',
            'is_active' => '1',
            'logo' => 'http://localhost/storage/media/logo-imprimerie.png',
        ])->assertRedirect('/admin/partenaires');

        $partner->refresh();
        $this->assertSame('or', $partner->level);
        $this->assertSame('http://localhost/storage/media/logo-imprimerie.png', $partner->logo);
        Storage::disk('public')->assertMissing($ancienFichier);

        // Retirer le logo : l'image de la médiathèque, elle, n'est jamais supprimée
        Storage::disk('public')->put('media/logo-imprimerie.png', 'x');
        $this->actingAs($admin)->put("/admin/partenaires/{$partner->slug}", [
            'name' => 'Imprimerie Moderne', 'level' => 'or', 'is_active' => '1', 'remove_logo' => '1',
        ]);
        $this->assertNull($partner->fresh()->logo);
        Storage::disk('public')->assertExists('media/logo-imprimerie.png');

        $this->actingAs($admin)->delete("/admin/partenaires/{$partner->slug}")->assertRedirect('/admin/partenaires');
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);
    }

    public function test_logo_chosen_in_media_library_is_shown_publicly(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/admin/partenaires', [
            'name' => 'Optique Vision', 'level' => 'or', 'is_active' => '1',
            'logo' => '/storage/media/optique.png',
        ])->assertRedirect('/admin/partenaires');

        $this->get('/partenaires')->assertSee('/storage/media/optique.png', false);
        $this->get('/partenaires/optique-vision')->assertSee('/storage/media/optique.png', false);
    }

    public function test_editor_text_is_kept_formatted_and_cleaned(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/admin/partenaires', [
            'name' => 'Garage Martin', 'level' => 'or', 'is_active' => '1',
            'presentation' => '<h2>Un garage local</h2><p>Depuis <strong>1990</strong> à Bressuire.</p>'
                . '<script>alert(1)</script><p><a href="javascript:alert(2)">piège</a> <img src="x.png" onerror="alert(3)"></p>',
            'actions' => '<ul><li>Lots pour le meeting</li><li>Transport des nageurs</li></ul>',
        ])->assertRedirect('/admin/partenaires');

        $partner = Partner::firstWhere('name', 'Garage Martin');
        $this->assertStringContainsString('<strong>1990</strong>', $partner->presentation);
        $this->assertStringNotContainsString('<script', $partner->presentation);
        $this->assertStringNotContainsString('javascript:', $partner->presentation);
        $this->assertStringNotContainsString('onerror', $partner->presentation);

        $this->get('/partenaires/garage-martin')
            ->assertOk()
            ->assertSee('<h2>Un garage local</h2>', false)
            ->assertSee('<li>Transport des nageurs</li>', false)
            ->assertDontSee('alert(1)', false);
    }

    public function test_old_plain_text_is_still_displayed(): void
    {
        Partner::create([
            'name' => 'Ancien Texte', 'level' => 'bronze',
            'presentation' => "Premier paragraphe.\n\nDeuxième paragraphe.",
            'actions' => "- Lots pour le meeting\nFinancement des bonnets",
        ]);

        $this->get('/partenaires/ancien-texte')
            ->assertSee('<p>Deuxième paragraphe.</p>', false)
            ->assertSee('<li>Financement des bonnets</li>', false);
    }

    public function test_empty_editor_is_saved_as_empty(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/admin/partenaires', [
            'name' => 'Mairie', 'level' => 'principal', 'is_active' => '1',
            'presentation' => '<p><br></p>', 'actions' => '<p><br></p>',
        ]);

        $partner = Partner::firstWhere('name', 'Mairie');
        $this->assertNull($partner->presentation);
        $this->assertNull($partner->actions);
    }

    public function test_validation_rejects_bad_website_year_and_svg(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/admin/partenaires', [
            'name' => 'Test',
            'level' => 'or',
            'website' => 'javascript:alert(1)',
            'partner_since' => 1900,
            'logo' => 'javascript:alert(1)',
            'logo_file' => UploadedFile::fake()->create('logo.svg', 10, 'image/svg+xml'),
        ])->assertSessionHasErrors(['website', 'partner_since', 'logo', 'logo_file']);

        $this->assertDatabaseCount('partners', 0);
    }

    public function test_editor_can_manage_partners_in_editor_area(): void
    {
        $editor = $this->userWithRole('editor');

        $this->actingAs($editor)->get('/editor/partenaires')->assertOk();
        // La médiathèque est accessible depuis l'espace éditeur (sélecteur d'image)
        $this->actingAs($editor)->getJson('/editor/media-api')->assertOk();
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
