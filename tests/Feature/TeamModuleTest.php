<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\TeamMember;
use App\Models\TeamSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Module « L'équipe du club » : page publique, gestion réservée aux administrateurs.
 * Lancer : php artisan test --filter=TeamModuleTest
 */
class TeamModuleTest extends TestCase
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

    private function section(string $slug): TeamSection
    {
        return TeamSection::where('slug', $slug)->firstOrFail();
    }

    public function test_default_sections_are_created(): void
    {
        $this->assertSame(
            ['Comité directeur', 'Entraîneurs', 'Bénévoles', 'Officiels'],
            TeamSection::ordered()->pluck('name')->all()
        );
    }

    public function test_public_page(): void
    {
        $this->get('/equipe-du-club')->assertOk()->assertSee('La présentation de l\'équipe arrive bientôt', false);

        $comite = $this->section('comite-directeur');
        $officiels = $this->section('officiels');

        $claire = TeamMember::create([
            'first_name' => 'Claire', 'last_name' => 'Martin', 'photo' => '/storage/media/claire.jpg',
            'facebook_url' => 'https://www.facebook.com/claire.martin', 'linkedin_url' => 'https://www.linkedin.com/in/cmartin',
        ]);
        $claire->sections()->attach($comite, ['position' => 'Présidente', 'sort_order' => 1]);
        $claire->sections()->attach($officiels, ['position' => 'Juge-arbitre', 'sort_order' => 0]);

        $paul = TeamMember::create(['first_name' => 'Paul', 'last_name' => 'Bernard']);
        $paul->sections()->attach($comite, ['position' => 'Trésorier', 'sort_order' => 2]);

        $masque = TeamMember::create(['first_name' => 'Jean', 'last_name' => 'Caché', 'is_active' => false]);
        $masque->sections()->attach($comite, ['position' => 'Secrétaire']);

        $response = $this->get('/equipe-du-club')->assertOk();
        $response->assertSeeInOrder(['Comité directeur', 'Claire', 'Présidente', 'Paul', 'Trésorier', 'Officiels', 'Claire', 'Juge-arbitre']);
        $response->assertSee('/storage/media/claire.jpg', false)
            ->assertSee('https://www.facebook.com/claire.martin', false)
            ->assertSee('https://www.linkedin.com/in/cmartin', false)
            ->assertSee('PB')                       // initiales à la place de la photo
            ->assertDontSee('Caché')                // membre masqué
            ->assertDontSee('id="entraineurs"', false);   // section sans membre : absente

        // Section masquée : absente de la page
        $officiels->update(['is_active' => false]);
        $this->get('/equipe-du-club')->assertDontSee('Juge-arbitre');

        // Lien dans le menu « Le Club »
        $this->get('/equipe-du-club')->assertSee(route('team.index'), false);
    }

    public function test_admin_manages_members_and_sections(): void
    {
        $admin = $this->userWithRole('admin');
        $comite = $this->section('comite-directeur');
        $benevoles = $this->section('benevoles');

        $this->actingAs($admin)->get('/admin/equipe/membres')->assertOk()->assertSee('Comité directeur');
        $this->actingAs($admin)->get('/admin/equipe/membres/create?section=' . $benevoles->id)->assertOk()
            ->assertSee('value="' . $benevoles->id . '" selected', false);

        $this->actingAs($admin)->post('/admin/equipe/membres', [
            'first_name' => 'Sophie', 'last_name' => 'Durand', 'photo' => '/storage/media/sophie.jpg',
            'instagram_url' => 'https://www.instagram.com/sophie', 'website_url' => '',
            'is_active' => '1',
            'assignments' => [
                ['section_id' => $comite->id, 'position' => 'Secrétaire', 'sort_order' => 3],
                ['section_id' => $benevoles->id, 'position' => '', 'sort_order' => ''],
            ],
        ])->assertRedirect('/admin/equipe/membres');

        $sophie = TeamMember::where('last_name', 'Durand')->firstOrFail();
        $this->assertTrue($sophie->is_active);
        $this->assertSame(['Secrétaire', null], $sophie->sections->pluck('pivot.position')->all());

        // Modification : retirée des bénévoles, fonction changée
        $this->actingAs($admin)->get("/admin/equipe/membres/{$sophie->id}/edit")->assertOk()->assertSee('Secrétaire');
        $this->actingAs($admin)->put("/admin/equipe/membres/{$sophie->id}", [
            'first_name' => 'Sophie', 'last_name' => 'Durand',
            'assignments' => [['section_id' => $comite->id, 'position' => 'Vice-présidente', 'sort_order' => 1]],
        ])->assertRedirect('/admin/equipe/membres');
        $sophie->refresh()->load('sections');
        $this->assertFalse($sophie->is_active);
        $this->assertSame(['Vice-présidente'], $sophie->sections->pluck('pivot.position')->all());

        // Nouvelle section, renommée ; suppression d'une section : le membre reste
        $this->actingAs($admin)->post('/admin/equipe/sections', ['name' => 'Commission sportive', 'is_active' => '1'])
            ->assertRedirect('/admin/equipe/sections');
        $commission = TeamSection::where('slug', 'commission-sportive')->firstOrFail();
        $this->actingAs($admin)->put("/admin/equipe/sections/{$commission->id}", ['name' => 'Commission technique', 'is_active' => '1']);
        $this->assertSame('commission-technique', $commission->fresh()->slug);

        $this->actingAs($admin)->delete("/admin/equipe/sections/{$comite->id}")->assertRedirect('/admin/equipe/sections');
        $this->assertModelExists($sophie);
        $this->assertSame(0, $sophie->sections()->count());
        $this->actingAs($admin)->get('/admin/equipe/membres')->assertSee('dans aucune section');

        $this->actingAs($admin)->delete("/admin/equipe/membres/{$sophie->id}")->assertRedirect('/admin/equipe/membres');
        $this->assertModelMissing($sophie);
    }

    public function test_validation(): void
    {
        $admin = $this->userWithRole('admin');
        $comite = $this->section('comite-directeur');

        $this->actingAs($admin)->post('/admin/equipe/membres', [
            'first_name' => '', 'last_name' => 'X', 'facebook_url' => 'facebook.com/x',
            'photo' => 'javascript:alert(1)', 'assignments' => [],
        ])->assertSessionHasErrors(['first_name', 'facebook_url', 'photo', 'assignments']);

        $this->actingAs($admin)->post('/admin/equipe/membres', [
            'first_name' => 'A', 'last_name' => 'B',
            'assignments' => [['section_id' => $comite->id], ['section_id' => $comite->id]],
        ])->assertSessionHasErrors('assignments');

        $this->assertSame(0, TeamMember::count());
    }

    public function test_only_admins_manage_the_team(): void
    {
        $this->get('/admin/equipe/membres')->assertRedirect('/login');

        foreach (['editor', 'user'] as $slug) {
            $this->actingAs($this->userWithRole($slug))->get('/admin/equipe/membres')->assertForbidden();
        }
    }
}
