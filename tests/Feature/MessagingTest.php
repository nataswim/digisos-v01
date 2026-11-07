<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\CompetitionAttendance;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\MessageAnnouncement;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Messagerie : conversations privées équipe ↔ un utilisateur, envois groupés (une conversation
 * par destinataire), confidentialité, non-lus, « lu par », pièces jointes, e-mails, suppression.
 * Données fictives. Lancer : php artisan test --filter=MessagingTest
 */
class MessagingTest extends TestCase
{
    use RefreshDatabase;

    private int $n = 0;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        Storage::fake('local');
    }

    private function account(string $role, string $first = null): User
    {
        $r = Role::firstOrCreate(['slug' => $role], ['name' => ucfirst($role), 'display_name' => $role, 'level' => 0]);
        $this->n++;
        $u = User::create(['name' => ($first ?? ucfirst($role)) . " Test{$this->n}", 'first_name' => $first ?? ucfirst($role), 'last_name' => "Test{$this->n}",
            'email' => "{$role}{$this->n}@example.test", 'password' => bcrypt('secret-password'), 'role_id' => $r->id, 'status' => 'active']);
        $u->forceFill(['email_verified_at' => now()])->save();

        return $u;
    }

    /** Groupe fictif : 2 familles avec compte + 1 nageur sans compte. */
    private function group(User ...$families): PlanningGroup
    {
        $g = PlanningGroup::create(['name' => 'Groupe Test', 'slug' => 'groupe-test', 'section' => 'Jeunesse', 'is_active' => true]);
        foreach ($families as $i => $f) {
            $s = PlanningSwimmer::create(['first_name' => "Enfant{$i}", 'last_name' => 'Fictif', 'birth_year' => 2014, 'gender' => 'F', 'user_id' => $f->id, 'is_active' => true]);
            $g->swimmers()->attach($s);
        }
        $g->swimmers()->attach(PlanningSwimmer::create(['first_name' => 'Sans', 'last_name' => 'Compte', 'birth_year' => 2014, 'gender' => 'M', 'is_active' => true]));

        return $g;
    }

    public function test_access_by_role(): void
    {
        $this->actingAs($this->account('admin'))->get(route('admin.messages.index'))->assertOk();
        $this->actingAs($this->account('editor'))->get(route('editor.messages.index'))->assertOk();
        $this->actingAs($this->account('coach'))->get(route('coach.messages.index'))->assertOk();
        $user = $this->account('user');
        $this->actingAs($user)->get(route('coach.messages.index'))->assertForbidden();
        $this->actingAs($user)->get(route('editor.messages.index'))->assertForbidden();
        $this->actingAs($user)->get(route('user.messages.index'))->assertOk();
    }

    public function test_group_send_creates_one_private_conversation_per_family(): void
    {
        $coach = $this->account('coach');
        [$f1, $f2] = [$this->account('user', 'Alpha'), $this->account('user', 'Beta')];
        $g = $this->group($f1, $f2);

        $this->actingAs($coach)->postJson(route('coach.messages.preview'), ['groups' => [$g->id]])
            ->assertOk()->assertJsonPath('count', 2)->assertJsonPath('missing.0', 'Sans COMPTE');

        $this->post(route('coach.messages.store'), ['groups' => [$g->id], 'subject' => 'Horaires des vacances', 'body' => 'Message fictif aux familles.'])
            ->assertRedirect();

        $a = MessageAnnouncement::firstOrFail();
        $this->assertSame(2, $a->recipients_count);
        $this->assertSame(2, Conversation::count());
        Notification::assertSentTo([$f1, $f2], NewMessageNotification::class);
        Notification::assertNotSentTo($coach, NewMessageNotification::class);

        $c1 = Conversation::where('user_id', $f1->id)->first();
        $c2 = Conversation::where('user_id', $f2->id)->first();

        // La famille 1 répond : seule la famille 1 et le coach voient la réponse.
        $this->actingAs($f1)->post(route('user.messages.reply', $c1), ['body' => 'Réponse privée de la famille Alpha.'])->assertRedirect();
        $this->actingAs($f2)->get(route('user.messages.show', $c2))->assertOk()->assertDontSee('Réponse privée de la famille Alpha.');
        $this->actingAs($f2)->get(route('user.messages.show', $c1))->assertNotFound();
        $this->actingAs($f2)->post(route('user.messages.reply', $c1), ['body' => 'intrus'])->assertNotFound();
        $this->actingAs($f2)->get(route('user.messages.index'))->assertSee('Horaires des vacances');
        $this->actingAs($coach)->get(route('coach.messages.show', $c1))->assertOk()->assertSee('Réponse privée de la famille Alpha.');

        // « Lu par » : f1 a lu (il a répondu), f2 a ouvert sa conversation.
        $this->actingAs($coach)->get(route('coach.messages.announcement', $a))->assertOk()->assertSee('2 / 2')->assertSee('A répondu');
    }

    public function test_competition_recipients_without_answer(): void
    {
        $admin = $this->account('admin');
        [$f1, $f2] = [$this->account('user'), $this->account('user')];
        $c = Competition::create(['title' => 'Meeting fictif', 'season' => '2026-2027', 'level' => 'departemental', 'kind' => 'individuel',
            'starts_on' => now()->addDays(10)->toDateString(), 'ends_on' => now()->addDays(10)->toDateString(), 'venue' => 'Piscine', 'city' => 'Villefictive', 'pool_length' => 25, 'is_published' => true]);
        foreach ([[$f1, 'convoque'], [$f2, 'confirme']] as [$f, $st]) {
            $s = PlanningSwimmer::create(['first_name' => 'N', 'last_name' => 'Fictif', 'birth_year' => 2013, 'gender' => 'M', 'user_id' => $f->id, 'is_active' => true]);
            CompetitionAttendance::create(['competition_id' => $c->id, 'planning_swimmer_id' => $s->id, 'status' => $st]);
        }

        $this->actingAs($admin)->postJson(route('admin.messages.preview'), ['competition_id' => $c->id, 'competition_scope' => 'sans-reponse'])->assertJsonPath('count', 1);
        $this->postJson(route('admin.messages.preview'), ['competition_id' => $c->id, 'competition_scope' => 'tous'])->assertJsonPath('count', 2);
        $this->post(route('admin.messages.store'), ['competition_id' => $c->id, 'competition_scope' => 'sans-reponse', 'subject' => 'Relance', 'body' => 'Merci de répondre.'])->assertRedirect();
        $this->assertSame([$f1->id], Conversation::pluck('user_id')->all());
    }

    public function test_all_members_and_all_coaches_exclude_sender(): void
    {
        $admin = $this->account('admin');
        $this->account('user'); $this->account('user'); $this->account('coach'); $this->account('visitor');
        $this->actingAs($admin)->postJson(route('admin.messages.preview'), ['all_members' => 1])->assertJsonPath('count', 2);
        $this->postJson(route('admin.messages.preview'), ['all_members' => 1, 'all_coaches' => 1])->assertJsonPath('count', 3);
        $this->post(route('admin.messages.store'), ['subject' => 'Vide', 'body' => 'x'])->assertSessionHasErrors('users');
    }

    public function test_member_writes_to_a_coach_and_privacy_between_staff(): void
    {
        $member = $this->account('user');
        $coach = $this->account('coach');
        $autreCoach = $this->account('coach');
        $editor = $this->account('editor');
        $admin = $this->account('admin');

        $this->actingAs($member)->get(route('user.messages.create'))->assertOk()->assertSee($coach->first_name)->assertDontSee($editor->last_name);
        $this->post(route('user.messages.store'), ['staff_id' => $editor->id, 'subject' => 'x', 'body' => 'y'])->assertSessionHasErrors('staff_id');
        $this->post(route('user.messages.store'), ['staff_id' => $coach->id, 'subject' => 'Absence mardi', 'body' => 'Message fictif.'])->assertRedirect();

        $c = Conversation::firstOrFail();
        Notification::assertSentTo($coach, NewMessageNotification::class);
        $this->assertSame(1, Conversation::unreadCountFor($coach));
        $this->assertSame(0, Conversation::unreadCountFor($member));

        $this->actingAs($autreCoach)->get(route('coach.messages.show', $c))->assertForbidden();
        $this->actingAs($editor)->get(route('editor.messages.show', $c))->assertForbidden();
        $this->actingAs($autreCoach)->get(route('coach.messages.index'))->assertDontSee('Absence mardi');

        $this->actingAs($admin)->get(route('admin.messages.index', ['vue' => 'toutes']))->assertSee('Absence mardi');
        $this->get(route('admin.messages.show', $c))->assertOk();
        $this->assertSame(1, Conversation::unreadCountFor($coach->fresh()));   // l'admin qui lit ne marque pas lu

        $this->actingAs($coach)->get(route('coach.messages.show', $c))->assertOk()->assertSee('Message fictif.');
        $this->assertSame(0, Conversation::unreadCountFor($coach));
        $this->post(route('coach.messages.reply', $c), ['body' => 'Bien noté.'])->assertRedirect();
        $this->assertSame(1, Conversation::unreadCountFor($member));
        $this->actingAs($member)->get(route('user.dashboard'))->assertOk()->assertSee('1 message non lu');
    }

    public function test_reply_sends_only_one_email_until_read(): void
    {
        $member = $this->account('user');
        $coach = $this->account('coach');
        $this->actingAs($coach)->post(route('coach.messages.store'), ['users' => [$member->id], 'subject' => 'Bonjour', 'body' => 'Premier.']);
        $c = Conversation::firstOrFail();
        $this->post(route('coach.messages.reply', $c), ['body' => 'Deuxième.']);
        $this->post(route('coach.messages.reply', $c), ['body' => 'Troisième.']);
        Notification::assertSentToTimes($member, NewMessageNotification::class, 1);

        $this->actingAs($member)->get(route('user.messages.show', $c));
        $this->actingAs($coach)->post(route('coach.messages.reply', $c), ['body' => 'Quatrième.']);
        Notification::assertSentToTimes($member, NewMessageNotification::class, 2);
    }

    public function test_attachment_is_private(): void
    {
        $member = $this->account('user');
        $autre = $this->account('user');
        $coach = $this->account('coach');

        $this->actingAs($coach)->post(route('coach.messages.store'), ['users' => [$member->id], 'subject' => 'Document', 'body' => 'Ci-joint.',
            'attachment' => UploadedFile::fake()->create('convocation.pdf', 120, 'application/pdf')])->assertRedirect();
        $m = ConversationMessage::firstOrFail();
        $this->assertTrue($m->hasAttachment());
        Storage::disk('local')->assertExists($m->attachment_path);

        $this->actingAs($member)->get(route('messages.attachment', $m))->assertOk();
        $this->actingAs($autre)->get(route('messages.attachment', $m))->assertNotFound();

        $this->actingAs($coach)->post(route('coach.messages.store'), ['users' => [$member->id], 'subject' => 'x', 'body' => 'y',
            'attachment' => UploadedFile::fake()->create('script.exe', 10)])->assertSessionHasErrors('attachment');
    }

    public function test_open_link_redirects_to_the_right_area(): void
    {
        $member = $this->account('user');
        $coach = $this->account('coach');
        $this->actingAs($coach)->post(route('coach.messages.store'), ['users' => [$member->id], 'subject' => 'Lien', 'body' => 'x']);
        $c = Conversation::firstOrFail();

        $this->actingAs($member)->get(route('messages.open', $c))->assertRedirect(route('user.messages.show', $c));
        $this->actingAs($coach)->get(route('messages.open', $c))->assertRedirect(route('coach.messages.show', $c));
        $this->actingAs($this->account('user'))->get(route('messages.open', $c))->assertNotFound();
    }

    public function test_only_admin_deletes(): void
    {
        $coach = $this->account('coach');
        [$f1, $f2] = [$this->account('user'), $this->account('user')];
        $this->actingAs($coach)->post(route('coach.messages.store'), ['users' => [$f1->id, $f2->id], 'subject' => 'Groupé', 'body' => 'x',
            'attachment' => UploadedFile::fake()->create('doc.pdf', 50, 'application/pdf')]);
        $a = MessageAnnouncement::firstOrFail();
        $c = Conversation::first();

        $this->delete(route('coach.messages.destroy', $c))->assertForbidden();
        $this->delete(route('coach.messages.announcement.destroy', $a))->assertForbidden();

        $admin = $this->account('admin');
        $this->actingAs($admin)->delete(route('admin.messages.destroy', $c))->assertRedirect();
        $this->assertSame(1, Conversation::count());
        $chemin = ConversationMessage::first()->attachment_path;
        Storage::disk('local')->assertExists($chemin);   // encore utilisé par l'autre conversation

        $this->delete(route('admin.messages.announcement.destroy', $a))->assertRedirect();
        $this->assertSame(0, Conversation::count());
        $this->assertSame(0, MessageAnnouncement::count());
        Storage::disk('local')->assertMissing($chemin);
    }

    public function test_visitor_cannot_start_a_conversation(): void
    {
        $visitor = $this->account('visitor');
        $this->account('coach');
        $this->actingAs($visitor)->get(route('user.messages.create'))->assertForbidden();
    }

    public function test_person_search(): void
    {
        $coach = $this->account('coach');
        $f = $this->account('user', 'Gaspard');
        PlanningSwimmer::create(['first_name' => 'Ondine', 'last_name' => 'Fictif', 'birth_year' => 2015, 'gender' => 'F', 'user_id' => $f->id, 'is_active' => true]);
        $this->actingAs($coach)->getJson(route('coach.messages.search', ['q' => 'gasp']))->assertOk()->assertJsonPath('0.id', $f->id);
        $this->getJson(route('coach.messages.search', ['q' => 'ondine']))->assertJsonPath('0.swimmers', 'Ondine');
    }
}
