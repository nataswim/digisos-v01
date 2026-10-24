<?php

namespace Tests\Feature;

use App\Models\FfnLicense;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Planning → Nageurs : ajout depuis les licences FFN (admin seulement) et photo du nageur.
 * Données fictives uniquement (IUF 9000001…).
 * Lancer : php artisan test --filter=SwimmersFromLicensesTest
 */
class SwimmersFromLicensesTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug, ?string $email = null): User
    {
        $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'display_name' => $slug, 'level' => 0]);
        $user = User::create([
            'name' => "Test {$slug}", 'email' => $email ?? "{$slug}@example.test",
            'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function license(string $iuf, string $first, string $last, string $birth, array $extra = []): FfnLicense
    {
        return FfnLicense::create($extra + [
            'season' => 2026, 'iuf' => $iuf, 'first_name' => $first, 'last_name' => $last,
            'birth_date' => $birth, 'gender' => 'F', 'license_type' => 'Compétition',
        ]);
    }

    public function test_admin_adds_swimmers_from_licenses(): void
    {
        $admin = $this->userWithRole('admin');
        $parent = $this->userWithRole('user', 'famille.durand@example.test');
        $groupe = PlanningGroup::create(['name' => 'Avenirs', 'section' => 'École de natation']);

        $alice = $this->license('9000001', 'Alice', 'Durand', '2015-03-02', ['email' => 'famille.durand@example.test']);
        $lea = $this->license('9000002', 'Léa', 'Martin', '2014-06-10');
        $deja = $this->license('9000003', 'Paul', 'Bernard', '2013-01-01', ['gender' => 'M']);
        $autre = $this->license('9000004', 'Hugo', 'Roux', '2012-01-01', ['gender' => 'M', 'license_type' => 'Natation Pour Tous']);

        // Fiche nageur saisie à la main avant l'import : même nom, même année → reprise
        $leaFiche = PlanningSwimmer::create(['first_name' => 'Lea', 'last_name' => 'MARTIN', 'birth_year' => 2014, 'is_active' => true]);
        // Licencié déjà relié : absent de la liste
        $paul = PlanningSwimmer::create(['first_name' => 'Paul', 'last_name' => 'Bernard', 'is_active' => true]);
        $deja->update(['planning_swimmer_id' => $paul->id]);

        $this->actingAs($admin)->get('/admin/planning/nageurs')->assertOk()->assertSee('Ajouter depuis les licences FFN');

        $this->actingAs($admin)->get('/admin/planning/nageurs/depuis-licences')->assertOk()
            ->assertSee('Alice DURAND')
            ->assertSee('Léa MARTIN')
            ->assertSee('Reprendre « Lea MARTIN »')
            ->assertSee('Hugo ROUX')
            ->assertDontSee('Paul BERNARD');

        $this->actingAs($admin)->get('/admin/planning/nageurs/depuis-licences?type=Compétition')->assertOk()
            ->assertSee('Alice DURAND')->assertDontSee('Hugo ROUX');

        $this->actingAs($admin)->post('/admin/planning/nageurs/depuis-licences', [
            'licenses' => [$alice->id, $lea->id], 'groups' => [$groupe->id], 'link_accounts' => '1',
        ])->assertRedirect()->assertSessionHas('success', '1 fiche(s) nageur créée(s), 1 fiche(s) existante(s) reliée(s) à leur licence. 1 compte(s) du site rattaché(s).');

        // Alice : nouvelle fiche, groupe, compte de la famille
        $aliceFiche = PlanningSwimmer::where('license_number', '9000001')->firstOrFail();
        $this->assertSame(['Alice', 'Durand', 2015, 'F'], [$aliceFiche->first_name, $aliceFiche->last_name, $aliceFiche->birth_year, $aliceFiche->gender]);
        $this->assertSame($parent->id, $aliceFiche->user_id);
        $this->assertTrue($aliceFiche->groups->contains($groupe));
        $this->assertSame($aliceFiche->id, $alice->fresh()->planning_swimmer_id);

        // Léa : fiche existante reprise et complétée, pas de doublon
        $this->assertSame(3, PlanningSwimmer::count());
        $this->assertSame('9000002', $leaFiche->fresh()->license_number);
        $this->assertSame($leaFiche->id, $lea->fresh()->planning_swimmer_id);

        // Les licenciés traités ne sont plus proposés
        $this->actingAs($admin)->get('/admin/planning/nageurs/depuis-licences')->assertDontSee('Alice DURAND')->assertSee('Hugo ROUX');

        // Rien de coché : erreur
        $this->actingAs($admin)->post('/admin/planning/nageurs/depuis-licences', ['licenses' => []])
            ->assertSessionHasErrors('licenses');
    }

    public function test_editors_cannot_use_licenses(): void
    {
        $editor = $this->userWithRole('editor');
        $this->license('9000001', 'Alice', 'Durand', '2015-03-02');

        $this->actingAs($editor)->get('/admin/planning/nageurs/depuis-licences')->assertForbidden();
        $this->actingAs($editor)->get('/editor/planning/nageurs')->assertOk()->assertDontSee('Ajouter depuis les licences FFN');
    }

    public function test_swimmer_photo(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/admin/planning/nageurs', [
            'first_name' => 'Alice', 'last_name' => 'Durand', 'photo' => '/storage/media/alice.jpg', 'is_active' => '1',
        ])->assertRedirect();
        $alice = PlanningSwimmer::firstOrFail();
        $this->assertSame('/storage/media/alice.jpg', $alice->photo);

        $this->actingAs($admin)->get('/admin/planning/nageurs')->assertSee('/storage/media/alice.jpg', false);
        $this->actingAs($admin)->get("/admin/planning/nageurs/{$alice->id}/edit")->assertSee('/storage/media/alice.jpg', false);

        // Pas de photo sur la page publique du planning
        $this->get('/groupes-et-horaires')->assertOk()->assertDontSee('/storage/media/alice.jpg', false);

        $this->actingAs($admin)->post('/admin/planning/nageurs', [
            'first_name' => 'Bob', 'last_name' => 'X', 'photo' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('photo');
    }
}
