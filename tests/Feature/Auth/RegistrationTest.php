<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Inscription réservée aux adhérents : choix du lien avec le club, compte « visiteur » en attente de validation.
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $extra = []): array
    {
        return $extra + [
            'name' => 'Camille Durand',
            'email' => 'camille.durand@example.test',
            'password' => 'Mot-de-passe-2026',
            'password_confirmation' => 'Mot-de-passe-2026',
            'username' => 'camille_durand',
            'first_name' => 'Camille',
            'last_name' => 'Durand',
            'date_of_birth' => '1985-04-12',
            'phone' => '0612345678',
            'profiles' => ['ecole', 'benevole'],
            'terms' => '1',
        ];
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get('/register')->assertOk()
            ->assertSee("L'inscription et l'utilisation des outils du site sont réservées aux adhérents du club.", false)
            ->assertSee('Adhérent nageur')->assertSee('École de natation')->assertSee('Maîtres')
            ->assertSee('Partenaire')->assertSee('Bénévole')
            ->assertDontSee('Chercheur / Consultant');
    }

    public function test_new_users_can_register(): void
    {
        Role::create(['name' => 'Visiteur', 'slug' => 'visitor', 'display_name' => 'Visiteur', 'level' => 0, 'is_default' => true]);

        $this->post('/register', $this->payload())->assertRedirect('/visitor/dashboard');

        $this->assertAuthenticated();
        $user = User::where('email', 'camille.durand@example.test')->firstOrFail();
        $this->assertSame('École de natation | Bénévole', $user->bio);
        $this->assertSame('visitor', $user->role->slug);
    }

    public function test_a_profile_is_required(): void
    {
        Role::create(['name' => 'Visiteur', 'slug' => 'visitor', 'display_name' => 'Visiteur', 'level' => 0, 'is_default' => true]);

        $this->post('/register', $this->payload(['profiles' => []]))->assertSessionHasErrors('profiles');
        $this->post('/register', $this->payload(['profiles' => ['pirate']]))->assertSessionHasErrors('profiles.0');
        $this->assertGuest();
    }
}
