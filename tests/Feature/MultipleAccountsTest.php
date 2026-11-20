<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\CompetitionAttendance;
use App\Models\Conversation;
use App\Models\Member;
use App\Models\MemberLog;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\User;
use App\Notifications\CompetitionConvocationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Plusieurs comptes du site par nageur (le nageur, sa mère, son père…) : rattachement depuis la fiche
 * adhérent ou la fiche nageur, accès aux pages famille, réponses aux convocations, e-mails et messages.
 * Données fictives. Lancer : php artisan test --filter=MultipleAccountsTest
 */
class MultipleAccountsTest extends TestCase
{
    use RefreshDatabase;

    private int $n = 0;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        Http::fake(['geo.api.gouv.fr/*' => Http::response([])]);
    }

    private function account(string $role, string $first): User
    {
        $r = Role::firstOrCreate(['slug' => $role], ['name' => ucfirst($role), 'display_name' => $role, 'level' => 0]);
        $this->n++;
        $u = User::create(['name' => "{$first} Fictif", 'first_name' => $first, 'last_name' => 'Fictif',
            'email' => mb_strtolower($first) . "{$this->n}@example.test", 'password' => bcrypt('secret-password'), 'role_id' => $r->id, 'status' => 'active']);
        $u->forceFill(['email_verified_at' => now()])->save();

        return $u;
    }

    /** Adhérente mineure fictive avec sa fiche nageur et deux responsables légaux. */
    private function jade(User $mere, User $pere): Member
    {
        $nageur = PlanningSwimmer::create(['first_name' => 'Jade', 'last_name' => 'Fictif', 'birth_year' => 2014, 'gender' => 'F', 'is_active' => true]);

        return Member::create(['last_name' => 'Fictif', 'first_name' => 'Jade', 'birth_date' => '2014-03-01', 'city' => 'Villefictive',
            'planning_swimmer_id' => $nageur->id, 'guardians' => [
                ['name' => 'Marie Fictif', 'relation' => 'Mère', 'phone' => '', 'email' => $mere->email],
                ['name' => 'Paul Fictif', 'relation' => 'Père', 'phone' => '', 'email' => $pere->email],
                ['name' => 'Tante Fictif', 'relation' => 'Tante', 'phone' => '', 'email' => 'tante-sans-compte@example.test'],
            ]]);
    }

    public function test_l_admin_rattache_plusieurs_comptes_depuis_la_fiche_adherent(): void
    {
        $admin = $this->account('admin', 'Admin');
        $mere = $this->account('user', 'Marie');
        $pere = $this->account('user', 'Paul');
        $ado = $this->account('user', 'Jade');
        $jade = $this->jade($mere, $pere);

        $this->actingAs($admin)->post(route('admin.members.link-user', $jade), ['email' => $ado->email, 'relation' => 'Nageur'])
            ->assertSessionHas('success');

        // Les responsables légaux en un clic : la tante n'a pas de compte et est signalée
        $this->post(route('admin.members.link-guardians', $jade))
            ->assertSessionHas('success', fn ($m) => str_contains($m, '2 compte(s) rattaché(s)') && str_contains($m, 'tante-sans-compte@example.test'));

        $comptes = $jade->swimmer->users()->get();
        $this->assertSame(['Jade', 'Marie', 'Paul'], $comptes->pluck('first_name')->sort()->values()->all());
        $this->assertSame('Mère', $comptes->firstWhere('id', $mere->id)->pivot->relation);
        $this->assertSame('Père', $comptes->firstWhere('id', $pere->id)->pivot->relation);

        $this->get(route('admin.members.show', $jade))->assertOk()
            ->assertSee($mere->email)->assertSee($pere->email)->assertSee('Mère')->assertSee('Père')
            ->assertDontSee('Rattacher les responsables légaux')
            ->assertSee('Responsable(s) sans compte sur le site')->assertSee('Tante Fictif (Tante)');
        $this->assertSame(3, MemberLog::where('member_id', $jade->id)->where('subject', 'Comptes du site')->count());

        // Compte inconnu : refusé, rien n'est rattaché
        $this->post(route('admin.members.link-user', $jade), ['email' => 'inconnu@example.test'])->assertSessionHas('error');
        $this->assertSame(3, $jade->swimmer->users()->count());

        // Détacher un seul compte
        $this->delete(route('admin.members.unlink-user', [$jade, $pere]))->assertSessionHas('success');
        $this->assertEqualsCanonicalizing([$ado->id, $mere->id], $jade->swimmer->users()->pluck('users.id')->all());
    }

    public function test_la_fiche_nageur_gere_plusieurs_comptes(): void
    {
        $admin = $this->account('admin', 'Admin');
        $mere = $this->account('user', 'Marie');
        $pere = $this->account('user', 'Paul');
        $s = PlanningSwimmer::create(['first_name' => 'Hugo', 'last_name' => 'Fictif', 'birth_year' => 2013, 'gender' => 'M', 'is_active' => true]);

        $this->actingAs($admin)->get(route('admin.planning.swimmers.edit', $s))->assertOk()->assertSee('Comptes du site');
        $this->put(route('admin.planning.swimmers.update', $s), [
            'first_name' => 'Hugo', 'last_name' => 'Fictif', 'birth_year' => 2013, 'gender' => 'M', 'is_active' => '1',
            'accounts' => [['user_id' => $mere->id, 'relation' => 'Mère'], ['user_id' => $pere->id, 'relation' => 'Père'], ['user_id' => '', 'relation' => '']],
        ])->assertRedirect();

        $this->assertSame(2, $s->users()->count());
        $this->get(route('admin.planning.swimmers.index'))->assertSee($mere->email)->assertSee($pere->email);

        // Une ligne retirée détache le compte
        $this->put(route('admin.planning.swimmers.update', $s), [
            'first_name' => 'Hugo', 'last_name' => 'Fictif', 'is_active' => '1', 'accounts' => [['user_id' => $mere->id, 'relation' => 'Mère']],
        ]);
        $this->assertSame([$mere->id], $s->users()->pluck('users.id')->all());
    }

    public function test_chaque_compte_rattache_voit_le_nageur_et_peut_repondre(): void
    {
        $mere = $this->account('user', 'Marie');
        $pere = $this->account('user', 'Paul');
        $voisin = $this->account('user', 'Voisin');
        $jade = $this->jade($mere, $pere);
        $jade->swimmer->linkAccount($mere, 'Mère');
        $jade->swimmer->linkAccount($pere, 'Père');

        $c = Competition::create(['title' => 'Meeting fictif', 'season' => '2026-2027', 'level' => 'departemental', 'kind' => 'individuel',
            'starts_on' => now()->addDays(10)->toDateString(), 'ends_on' => now()->addDays(10)->toDateString(), 'venue' => 'Piscine', 'city' => 'Villefictive', 'pool_length' => 25, 'is_published' => true]);
        $convocation = CompetitionAttendance::create(['competition_id' => $c->id, 'planning_swimmer_id' => $jade->planning_swimmer_id, 'status' => 'convoque']);

        foreach ([$mere, $pere] as $parent) {
            $this->actingAs($parent)->get(route('user.dashboard'))->assertOk()->assertSee('Jade');
            $this->get(route('user.competitions.index'))->assertOk()->assertSee('Meeting fictif');
        }

        // La mère répond, le père corrige : la dernière réponse compte et on sait qui a répondu
        $this->actingAs($mere)->put(route('user.competitions.respond', $convocation), ['status' => 'confirme'])->assertRedirect();
        $this->actingAs($pere)->put(route('user.competitions.respond', $convocation), ['status' => 'absent'])->assertRedirect();
        $this->assertSame(['absent', $pere->id], [$convocation->fresh()->status, $convocation->fresh()->responded_by]);
        $this->actingAs($mere)->get(route('user.competitions.index'))->assertSee('par Paul Fictif');

        // Un compte non rattaché ne voit rien et ne peut pas répondre
        $this->actingAs($voisin)->put(route('user.competitions.respond', $convocation), ['status' => 'confirme'])->assertForbidden();
        $this->get(route('user.competitions.index'))->assertDontSee('Meeting fictif');
    }

    public function test_e_mails_de_convocation_et_messages_vont_a_tous_les_comptes(): void
    {
        $admin = $this->account('admin', 'Admin');
        $mere = $this->account('user', 'Marie');
        $pere = $this->account('user', 'Paul');
        $jade = $this->jade($mere, $pere);
        $jade->swimmer->linkAccount($mere, 'Mère');
        $jade->swimmer->linkAccount($pere, 'Père');
        $groupe = PlanningGroup::create(['name' => 'Groupe Test', 'slug' => 'groupe-test', 'section' => 'Jeunesse', 'is_active' => true]);
        $groupe->swimmers()->attach($jade->planning_swimmer_id);

        $c = Competition::create(['title' => 'Meeting fictif', 'season' => '2026-2027', 'level' => 'departemental', 'kind' => 'individuel',
            'starts_on' => now()->addDays(10)->toDateString(), 'ends_on' => now()->addDays(10)->toDateString(), 'venue' => 'Piscine', 'city' => 'Villefictive', 'pool_length' => 25, 'is_published' => true]);
        CompetitionAttendance::create(['competition_id' => $c->id, 'planning_swimmer_id' => $jade->planning_swimmer_id, 'status' => 'convoque']);

        $this->actingAs($admin)->post(route('admin.competitions.entries.notify', $c))->assertSessionHas('success', '2 e-mails envoyés.');
        Notification::assertSentTo([$mere, $pere], CompetitionConvocationNotification::class);

        $this->postJson(route('admin.messages.preview'), ['groups' => [$groupe->id]])->assertJsonPath('count', 2);
        $this->post(route('admin.messages.store'), ['groups' => [$groupe->id], 'subject' => 'Horaires', 'body' => 'Message fictif.'])->assertRedirect();
        $this->assertEqualsCanonicalizing([$mere->id, $pere->id], Conversation::pluck('user_id')->all());
    }
}
