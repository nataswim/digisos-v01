<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MemberCategory;
use App\Models\MemberLog;
use App\Models\Membership;
use App\Models\MembershipPayment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Suivi des adhérents : accès (admin + éditeur uniquement), fiche, montants, règlements,
 * Pass'Sport, import du tableau, nouvelle saison, export, attestation, journal. Données fictives.
 * Lancer : php artisan test --filter=MembersModuleTest
 */
class MembersModuleTest extends TestCase
{
    use RefreshDatabase;

    private const SEASON = '2026-2027';

    protected function setUp(): void
    {
        parent::setUp();
        // Carte des communes : aucune requête réelle vers geo.api.gouv.fr pendant les tests
        Http::fake(['geo.api.gouv.fr/*' => Http::response([
            ['nom' => 'Villefictive', 'codesPostaux' => ['79999'], 'centre' => ['type' => 'Point', 'coordinates' => [-0.49, 46.84]]],
        ])]);
        Http::preventStrayRequests();
    }

    private function userWithRole(string $slug): User
    {
        $role = Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'display_name' => $slug, 'level' => 0]);
        $user = User::create(['name' => "Test {$slug}", 'email' => "{$slug}@example.test", 'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active']);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function member(array $m = [], array $a = []): Member
    {
        $member = Member::create($m + ['last_name' => 'Testeur', 'first_name' => 'Léa', 'gender' => 'F', 'birth_date' => '2014-03-15', 'city' => 'Villefictive', 'email' => 'famille.testeur@example.test']);
        $member->memberships()->create($a + ['season' => self::SEASON, 'member_category_id' => MemberCategory::where('code', 'RELEVE')->value('id'),
            'price_cents' => 19000, 'status' => 'en_cours', 'situation' => 'nouveau']);

        return $member;
    }

    public function test_only_admin_and_editor_can_access(): void
    {
        $this->actingAs($this->userWithRole('admin'))->get(route('admin.members.index'))->assertOk();
        $this->actingAs($this->userWithRole('editor'))->get(route('editor.members.index'))->assertOk();
        $this->actingAs($this->userWithRole('coach'))->get(route('editor.members.index'))->assertForbidden();
        $this->actingAs($this->userWithRole('user'))->get(route('admin.members.index'))->assertForbidden();
        auth()->logout();
        $this->get(route('admin.members.index'))->assertRedirect();
    }

    public function test_categories_are_seeded(): void
    {
        $this->assertSame(17500, MemberCategory::where('code', 'ENF-INITIATION')->value('price_cents'));
        $this->assertTrue(MemberCategory::where('code', 'MAITRES')->exists());
        $this->assertTrue(MemberCategory::where('code', 'BENEVOLE')->exists());
    }

    public function test_create_member_with_membership_and_guardian(): void
    {
        $admin = $this->userWithRole('admin');
        $cat = MemberCategory::where('code', 'RELEVE')->first();

        $this->actingAs($admin)->post(route('admin.members.store'), [
            'season' => self::SEASON, 'last_name' => 'Exemple', 'first_name' => 'Tom', 'gender' => 'M', 'birth_date' => '2015-06-01',
            'email' => 'Parent.Exemple@Example.test', 'image_rights' => '0',
            'guardians' => [['name' => 'Parent Exemple', 'relation' => 'Mère', 'phone' => '0600000000'], ['name' => '']],
            'member_category_id' => $cat->id, 'discount' => '10', 'status' => 'en_cours', 'situation' => 'nouveau',
            'pass_sport' => '1', 'pass_sport_code' => 'PS-TEST-1', 'pass_sport_amount' => '50',
        ])->assertRedirect();

        $m = Member::where('last_name', 'Exemple')->firstOrFail();
        $this->assertSame('parent.exemple@example.test', $m->email);
        $this->assertFalse($m->image_rights);
        $this->assertCount(1, $m->guardians);
        $this->assertTrue($m->isMinor(self::SEASON));

        $a = $m->membershipFor(self::SEASON);
        $this->assertSame(19000, $a->price_cents);
        $this->assertSame(18000, $a->amount_cents);
        $this->assertSame(13000, $a->due_cents);   // 180 − 50 Pass'Sport
        $this->assertTrue(MemberLog::where('member_id', $m->id)->where('action', 'created')->exists());

        $this->get(route('admin.members.show', $m))->assertOk()->assertSee('Exemple')->assertSee('PS-TEST-1');
        $this->get(route('admin.members.edit', $m))->assertOk();
    }

    public function test_payments_reduce_amount_due_and_can_be_split(): void
    {
        $admin = $this->userWithRole('admin');
        $m = $this->member();
        $a = $m->membershipFor(self::SEASON);

        $this->actingAs($admin)->post(route('admin.members.payments.store', $a), [
            'method' => 'cheque', 'amount' => '150', 'count' => 3, 'cashed_on' => '2026-10-05',
        ])->assertRedirect();

        $payments = $a->payments()->orderBy('cashed_on')->get();
        $this->assertCount(3, $payments);
        $this->assertSame([5000, 5000, 5000], $payments->pluck('amount_cents')->all());
        $this->assertSame('2026-12-05', $payments[2]->cashed_on->toDateString());
        $this->assertSame(4000, $a->fresh()->due_cents);
        $this->assertSame(0, $a->fresh()->cashed_cents);

        $this->patch(route('admin.members.payments.update', $payments[0]))->assertRedirect();
        $this->assertSame(5000, $a->fresh()->cashed_cents);

        $this->delete(route('admin.members.payments.destroy', $payments[2]))->assertRedirect();
        $this->assertSame(9000, $a->fresh()->due_cents);
        $this->assertTrue(MemberLog::where('member_id', $m->id)->where('subject', 'like', '%glement%')->exists());
    }

    public function test_update_logs_changes(): void
    {
        $admin = $this->userWithRole('editor');
        $m = $this->member();
        $this->actingAs($admin)->put(route('editor.members.update', $m), [
            'season' => self::SEASON, 'last_name' => 'Testeur', 'first_name' => 'Léa', 'birth_date' => '2014-03-15',
            'city' => 'Autreville', 'status' => 'complet', 'member_category_id' => MemberCategory::where('code', 'RELEVE')->value('id'),
            'file_items' => array_keys(Membership::FILE_ITEMS),
        ])->assertRedirect();

        $this->assertSame('Autreville', $m->fresh()->city);
        $a = $m->membershipFor(self::SEASON);
        $this->assertSame('complet', $a->status);
        $this->assertTrue($a->file_complete);
        $log = MemberLog::where('member_id', $m->id)->where('action', 'updated')->latest('id')->get();
        $this->assertTrue($log->contains(fn ($l) => isset($l->changes['city'])));
    }

    public function test_index_filters_and_totals(): void
    {
        $this->member();
        $this->member(['last_name' => 'Autre', 'first_name' => 'Paul', 'email' => 'autre@example.test', 'image_rights' => false], ['pass_sport' => true, 'pass_sport_cents' => 5000]);

        $this->actingAs($this->userWithRole('admin'));
        $this->get(route('admin.members.index', ['saison' => self::SEASON]))->assertOk()->assertSee('Léa')->assertSee('Paul');
        $this->get(route('admin.members.index', ['saison' => self::SEASON, 'filtre' => 'image']))->assertSee('Paul')->assertDontSee('Léa');
        $this->get(route('admin.members.index', ['saison' => self::SEASON, 'filtre' => 'passsport']))->assertSee('Paul')->assertDontSee('Léa');
        $this->get(route('admin.members.index', ['saison' => self::SEASON, 'q' => 'paul']))->assertSee('Paul')->assertDontSee('Léa');
        $this->get(route('admin.members.stats', ['saison' => self::SEASON]))->assertOk()->assertSee('RELEVE');
    }

    public function test_import_preview_then_confirm(): void
    {
        $admin = $this->userWithRole('admin');
        $this->member();   // Testeur Léa, déjà connue

        $tsv = implode("\n", [
            "Observations\tsaisie pass'sport\tDossier complet\tAttest. Paie.\tmini-coach\tNOM\tPrénom\tSexe\tDate naissance\tAnnée de naissance\tlieu de naissance\tAdresse\tCode postal\tVille\tTéléphone1\tTéléphone 2\tMail\tDroit à l'image\tAnnée Certificat Médical\tSituation\tCatégorie\tTarif\tStatut\tRemise\tMontant\t\t",
            "\tN\tO\tN\tN\tTESTEUR\tLéa\tF\t15/03/2014\t2014\tVillefictive\t1 rue Imaginaire\t79999\tVillefictive\t0600000001\t\tfamille.testeur@example.test\tO\t2025\tDéjà présent\tRELEVE\t190,00 €\t\t\t190,00 €\t\t",
            "test\tO\tN\tN\tO\tNOUVEAU\tSam\tM\t02/09/1990\t1990\t\t\t79999\tVillefictive\t\t\tsam@example.test\tN\t2023\tNouveau\tNOUVELLE-CAT\t120,00 €\t\t20,00 €\t100,00 €\t\t",
        ]);

        $this->actingAs($admin)->post(route('admin.members.import.store'), ['season' => self::SEASON, 'text' => $tsv])
            ->assertOk()->assertSee('Mise à jour')->assertSee('Nouveau')->assertSee('NOUVEAU Sam');
        $this->assertSame(1, Member::count());

        $this->post(route('admin.members.import.store'), ['season' => self::SEASON, 'text' => $tsv, 'confirm' => 1])->assertRedirect();
        $this->assertSame(2, Member::count());

        $lea = Member::where('first_name', 'Léa')->first();
        $this->assertSame('1 rue Imaginaire', $lea->address);
        $this->assertTrue($lea->image_rights);
        $this->assertSame('famille.testeur@example.test', $lea->email);
        $this->assertSame(2025, $lea->membershipFor(self::SEASON)->medical_certificate_year);
        $this->assertSame('renouvellement', $lea->membershipFor(self::SEASON)->situation);

        $sam = Member::where('first_name', 'Sam')->first();
        $a = $sam->membershipFor(self::SEASON);
        $this->assertSame('1990-09-02', $sam->birth_date->toDateString());
        $this->assertSame(12000, $a->price_cents);
        $this->assertSame(10000, $a->amount_cents);
        $this->assertTrue($a->pass_sport);
        $this->assertTrue($a->mini_coach);
        $this->assertSame('NOUVELLE-CAT', $a->category->code);
        $this->assertNotNull($a->certificateAlert());   // adulte, certificat 2023
        $this->assertNull($lea->membershipFor(self::SEASON)->certificateAlert());   // mineure
        $this->assertTrue(MemberLog::where('member_id', $sam->id)->where('action', 'imported')->exists());
    }

    public function test_prepare_next_season_copies_active_members(): void
    {
        $this->member();
        $this->member(['last_name' => 'Parti', 'first_name' => 'Zoé', 'email' => null], ['status' => 'abandon']);

        $this->actingAs($this->userWithRole('admin'))
            ->post(route('admin.members.prepare-season'), ['from' => self::SEASON])->assertRedirect();

        $next = Membership::where('season', '2027-2028')->get();
        $this->assertCount(1, $next);
        $this->assertSame('preinscrit', $next[0]->status);
        $this->assertSame('renouvellement', $next[0]->situation);
        $this->assertSame(0, $next[0]->payments()->count());
    }

    public function test_export_and_attestation(): void
    {
        $m = $this->member(['image_rights' => false]);
        $a = $m->membershipFor(self::SEASON);
        MembershipPayment::create(['membership_id' => $a->id, 'method' => 'virement', 'amount_cents' => 19000, 'cashed_on' => '2026-09-20', 'is_cashed' => true]);

        $this->actingAs($this->userWithRole('admin'));
        $csv = $this->get(route('admin.members.export', ['saison' => self::SEASON]))->assertOk()->streamedContent();
        $this->assertStringContainsString('Testeur', $csv);
        $this->assertStringContainsString('190,00', $csv);
        $img = $this->get(route('admin.members.export', ['saison' => self::SEASON, 'type' => 'image']))->streamedContent();
        $this->assertStringContainsString('Testeur', $img);

        $this->get(route('admin.members.attestation', [$m, $a]))->assertOk()->assertSee('ATTESTATION DE PAIEMENT')->assertSee('190,00');
        $this->assertFalse($a->fresh()->payment_certificate);
        $this->get(route('admin.members.attestation', [$m, $a, 'remise' => 1]))->assertOk();
        $this->assertTrue($a->fresh()->payment_certificate);
    }

    public function test_categories_crud_and_delete_member(): void
    {
        $this->actingAs($this->userWithRole('admin'));
        $this->get(route('admin.members.categories.index'))->assertOk()->assertSee('MAITRES');
        $this->post(route('admin.members.categories.store'), ['code' => 'stage-ete', 'label' => 'Stage été', 'price' => '45', 'is_active' => 1])->assertRedirect();
        $this->assertSame(4500, MemberCategory::where('code', 'STAGE-ETE')->value('price_cents'));

        $used = MemberCategory::where('code', 'RELEVE')->first();
        $m = $this->member();
        $this->delete(route('admin.members.categories.destroy', $used))->assertRedirect();
        $this->assertFalse($used->fresh()->is_active);

        $this->delete(route('admin.members.destroy', $m))->assertRedirect();
        $this->assertSame(0, Member::count());
        $this->assertSame(0, Membership::count());
    }

    public function test_stats_page_has_charts_and_commune_map(): void
    {
        $this->member(['postal_code' => '79999', 'city' => 'Villefictive', 'gender' => 'F']);
        $this->member(['last_name' => 'Autre', 'first_name' => 'Paul', 'gender' => 'M', 'birth_date' => '1980-01-01', 'postal_code' => '', 'city' => '']);

        $this->actingAs($this->userWithRole('admin'))->get(route('admin.members.stats', ['saison' => self::SEASON]))->assertOk()
            ->assertSee('Répartition des adhérents')
            ->assertSee('graph-cat', false)->assertSee('graph-statut', false)->assertSee('graph-situation', false)
            ->assertSee('graph-sexe', false)->assertSee('graph-age', false)
            ->assertSee('Féminin')->assertSee('Masculin')
            ->assertSee('carte-communes', false)
            ->assertSee('"lat":46.84', false)
            ->assertSee('Commune non renseignée')
            ->assertDontSee('1 rue', false);   // aucune adresse transmise à la carte

        // Tranches d'âge détaillées, dans l'ordre : Léa 12 ans, Paul 46 ans au 1er septembre 2026
        $tranches = $this->get(route('admin.members.stats', ['saison' => self::SEASON]))->viewData('byAge');
        $this->assertSame(['8 ans et moins', '9 ans', '10 ans'], $tranches->keys()->take(3)->all());
        $this->assertSame('71 ans et plus', $tranches->keys()->last());
        $this->assertCount(26, $tranches);   // pas de « Âge inconnu » : toutes les dates sont connues
        $this->assertSame(1, $tranches['12 ans']);
        $this->assertSame(1, $tranches['46 à 50 ans']);
        $this->assertSame('12 ans', \App\Http\Controllers\Admin\Members\MemberStatsController::ageBracket(12));
        $this->assertSame('8 ans et moins', \App\Http\Controllers\Admin\Members\MemberStatsController::ageBracket(6));
        $this->assertSame('25 à 30 ans', \App\Http\Controllers\Admin\Members\MemberStatsController::ageBracket(25));
        $this->assertSame('71 ans et plus', \App\Http\Controllers\Admin\Members\MemberStatsController::ageBracket(84));

        // Âges extrêmes : la plus jeune / la plus âgée = Léa ; le plus jeune / le plus âgé = Paul
        $this->get(route('admin.members.stats', ['saison' => self::SEASON]))
            ->assertSee('Âges extrêmes')->assertSee('La plus jeune')->assertSee('Le plus âgé')
            ->assertSee('née le 15/03/2014')->assertSee('né le 01/01/1980')->assertSee('46 ans');

        Http::assertSent(fn ($r) => str_contains($r->url(), 'codePostal=79999') && ! str_contains($r->url(), 'Testeur'));
        Http::assertSentCount(1);   // deuxième affichage : position lue dans le cache
    }
}
