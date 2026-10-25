<?php

namespace Tests\Feature;

use App\Models\CoachDocument;
use App\Models\Competition;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\SwimmerNote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Espace coachs : accès (coachs et admins), planning et compétitions partagés,
 * documents réservés (fichiers privés), notes de suivi des nageurs.
 * Données fictives uniquement.
 * Lancer : php artisan test --filter=CoachSpaceTest
 */
class CoachSpaceTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug, string $name = null): User
    {
        $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'display_name' => $slug, 'level' => 0]);
        $user = User::create([
            'name' => $name ?? "Test {$slug}", 'email' => ($name ? \Illuminate\Support\Str::slug($name) : $slug) . '@example.test',
            'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    public function test_coach_role_is_created_by_migration(): void
    {
        $this->assertDatabaseHas('roles', ['slug' => 'coach', 'name' => 'Coach']);
    }

    public function test_access_is_limited_to_coaches_and_admins(): void
    {
        $this->actingAs($this->userWithRole('coach'))->get('/coach/dashboard')->assertOk()->assertSee('Espace coachs');
        $this->actingAs($this->userWithRole('admin'))->get('/coach/dashboard')->assertOk();

        foreach (['editor', 'user', 'visitor'] as $slug) {
            $this->actingAs($this->userWithRole($slug))->get('/coach/dashboard')->assertForbidden();
            $this->actingAs(User::where('email', "{$slug}@example.test")->first())->get('/coach/suivi')->assertForbidden();
        }
        auth()->logout();
        $this->get('/coach/dashboard')->assertRedirect('/login');
    }

    public function test_coach_is_sent_to_coach_space_after_login(): void
    {
        $this->userWithRole('coach');
        $this->post('/login', ['email' => 'coach@example.test', 'password' => 'secret-password'])
            ->assertRedirect('/coach/dashboard');
    }

    public function test_coach_manages_planning_and_competitions(): void
    {
        $coach = $this->userWithRole('coach');

        foreach (['/coach/planning/creneaux', '/coach/planning/groupes', '/coach/planning/nageurs', '/coach/competitions', '/coach/competitions/create'] as $url) {
            $this->actingAs($coach)->get($url)->assertOk();
        }

        // Les liens des pages restent dans l'espace coachs
        $this->actingAs($coach)->get('/coach/planning/groupes')->assertSee('/coach/planning/groupes/create', false);

        $this->actingAs($coach)->post('/coach/planning/groupes', ['name' => 'Avenirs', 'section' => 'École de natation', 'is_active' => '1'])
            ->assertRedirect('/coach/planning/groupes');
        $this->assertDatabaseHas('planning_groups', ['name' => 'Avenirs']);

        // Import depuis les licences FFN : administrateurs seulement
        $this->actingAs($coach)->get('/coach/planning/nageurs')->assertDontSee('Ajouter depuis les licences FFN');
        $this->actingAs($coach)->get('/admin/planning/nageurs/depuis-licences')->assertForbidden();

        $competition = Competition::create(['title' => 'Meeting test', 'level' => 'departemental', 'kind' => 'individuel', 'is_published' => true, 'venue' => 'Piscine', 'city' => 'Bressuire', 'starts_on' => now()->addDays(20), 'ends_on' => now()->addDays(20)]);
        $cle = $competition->getRouteKey();
        foreach (["/coach/competitions/{$cle}/convocations", "/coach/competitions/{$cle}/edit", "/coach/competitions/{$cle}/programme",
                  '/coach/planning/nageurs/create', '/coach/planning/creneaux/create', '/coach/planning/groupes/create'] as $url) {
            $this->actingAs($coach)->get($url)->assertOk();
        }
        $this->actingAs($coach)->get('/coach/dashboard')->assertSee('Meeting test');
    }

    public function test_documents_are_private(): void
    {
        Storage::fake('local');
        $coach = $this->userWithRole('coach');

        $this->actingAs($coach)->post('/coach/documents', [
            'title' => 'Plan de saison', 'category' => 'entrainement',
            'file' => UploadedFile::fake()->create('plan-saison.pdf', 120, 'application/pdf'),
        ])->assertRedirect('/coach/documents');

        $document = CoachDocument::firstOrFail();
        Storage::disk('local')->assertExists($document->file_path);
        $this->assertStringStartsWith('coach-documents/', $document->file_path);
        $this->assertSame($coach->id, $document->uploaded_by);

        $this->actingAs($coach)->get('/coach/documents')->assertSee('Plan de saison')->assertSee('PDF');
        $this->actingAs($coach)->get("/coach/documents/{$document->id}/telecharger")->assertOk()->assertDownload('plan-saison.pdf');
        $this->actingAs($this->userWithRole('user'))->get("/coach/documents/{$document->id}/telecharger")->assertForbidden();

        // Lien externe, sans fichier
        $this->actingAs($coach)->post('/coach/documents', ['title' => 'Vidéo technique', 'url' => 'https://example.org/video'])->assertRedirect();
        $lien = CoachDocument::where('title', 'Vidéo technique')->firstOrFail();
        $this->actingAs($coach)->get("/coach/documents/{$lien->id}/telecharger")->assertRedirect('https://example.org/video');

        // Ni fichier ni lien : refusé ; fichier exécutable : refusé
        $this->actingAs($coach)->post('/coach/documents', ['title' => 'Vide'])->assertSessionHasErrors('url');
        $this->actingAs($coach)->post('/coach/documents', ['title' => 'Script', 'file' => UploadedFile::fake()->create('x.php', 1, 'text/x-php')])
            ->assertSessionHasErrors('file');

        // Suppression : le fichier part avec la fiche
        $chemin = $document->file_path;
        $this->actingAs($coach)->delete("/coach/documents/{$document->id}")->assertRedirect('/coach/documents');
        Storage::disk('local')->assertMissing($chemin);
    }

    public function test_swimmer_notes(): void
    {
        $coach = $this->userWithRole('coach');
        $autreCoach = $this->userWithRole('coach', 'Autre Coach');
        $admin = $this->userWithRole('admin');

        $groupe = PlanningGroup::create(['name' => 'Avenirs', 'section' => 'École de natation']);
        $alice = PlanningSwimmer::create(['first_name' => 'Alice', 'last_name' => 'Durand', 'birth_year' => 2015, 'is_active' => true]);
        $alice->groups()->attach($groupe);
        PlanningSwimmer::create(['first_name' => 'Hugo', 'last_name' => 'Roux', 'is_active' => true]);

        $this->actingAs($coach)->get('/coach/suivi')->assertOk()->assertSee('Alice DURAND')->assertSee('Hugo ROUX');
        $this->actingAs($coach)->get("/coach/suivi?groupe={$groupe->id}")->assertSee('Alice DURAND')->assertDontSee('Hugo ROUX');

        $this->actingAs($coach)->post("/coach/suivi/nageur/{$alice->id}", [
            'noted_on' => today()->toDateString(), 'category' => 'technique', 'content' => 'Bon travail sur les virages.',
        ])->assertRedirect("/coach/suivi/nageur/{$alice->id}");

        $note = SwimmerNote::firstOrFail();
        $this->assertSame($coach->id, $note->user_id);

        $this->actingAs($autreCoach)->get("/coach/suivi/nageur/{$alice->id}")->assertOk()
            ->assertSee('Bon travail sur les virages.')->assertDontSee("/coach/suivi/notes/{$note->id}/modifier", false);

        // Seuls l'auteur et les administrateurs modifient ou suppriment
        $this->actingAs($autreCoach)->put("/coach/suivi/notes/{$note->id}", ['noted_on' => today()->toDateString(), 'category' => 'autre', 'content' => 'x'])->assertForbidden();
        $this->actingAs($autreCoach)->delete("/coach/suivi/notes/{$note->id}")->assertForbidden();

        $this->actingAs($coach)->put("/coach/suivi/notes/{$note->id}", [
            'noted_on' => today()->toDateString(), 'category' => 'objectifs', 'content' => 'Objectif : 50 m dos.',
        ])->assertRedirect();
        $this->assertSame('objectifs', $note->fresh()->category);

        $this->actingAs($admin)->delete("/coach/suivi/notes/{$note->id}")->assertRedirect();
        $this->assertSame(0, SwimmerNote::count());

        // Date future et note vide : refusées
        $this->actingAs($coach)->post("/coach/suivi/nageur/{$alice->id}", ['noted_on' => today()->addWeek()->toDateString(), 'category' => 'technique', 'content' => ''])
            ->assertSessionHasErrors(['noted_on', 'content']);

        // Les notes n'apparaissent pas sur le site public
        SwimmerNote::create(['planning_swimmer_id' => $alice->id, 'user_id' => $coach->id, 'noted_on' => today(), 'category' => 'technique', 'content' => 'Note confidentielle']);
        $this->actingAs($this->userWithRole('user'))->get('/groupes-et-horaires')->assertOk()->assertDontSee('Note confidentielle');
    }

    public function test_admin_links(): void
    {
        $admin = $this->userWithRole('admin');
        $this->actingAs($admin)->get('/admin/dashboard')->assertSee('Espace coachs')->assertSee('/coach/dashboard', false);
        $this->actingAs($admin)->get('/admin/apercu-des-espaces')->assertSee('/coach/suivi', false);
    }
}
