<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SwimRecord;
use App\Models\User;
use App\Services\FfnRecordParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Records : saisie des records du club, import d'un tableau extraNat (aperçu puis confirmation),
 * page publique avec écarts. Données fictives.
 * Lancer : php artisan test --filter=RecordsModuleTest
 */
class RecordsModuleTest extends TestCase
{
    use RefreshDatabase;

    private const TABLEAU = "Records de France\nDames\n50 NL\t00:24.10\tDUPONT Emma (2001/24 ans) FRA\tCN EXEMPLE\t07/12/2025\tPOITIERS (FRA)\n"
        . "100 Dos 00:58.20 MARTIN Léa (1999/26 ans) FRA DAUPHINS EXEMPLE 17/12/2023 NANTES (FRA)\n"
        . "4x50 NL\t01:42.00\tCN EXEMPLE\t12/12/2024\tCHARTRES (FRA)\nDUPONT Emma (2001/23 ans) FRA\nMARTIN Léa (1999/25 ans) FRA\n"
        . "Messieurs\n1500 NL 14:40.33 PETIT Hugo (2000/25 ans) FRA CN EXEMPLE 01/02/2025 TOURS (FRA)\nLégende";

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

    public function test_time_helpers_and_parser(): void
    {
        $this->assertSame(2341, SwimRecord::parseTime('00:23.41'));
        $this->assertSame(6532, SwimRecord::parseTime('1:05.32'));
        $this->assertSame(6532, SwimRecord::parseTime('1\'05"32'));
        $this->assertSame(2850, SwimRecord::parseTime('28,5'));
        $this->assertNull(SwimRecord::parseTime('vite'));
        $this->assertSame('2:01.67', SwimRecord::formatTime(12167));
        $this->assertSame('+2.35', SwimRecord::formatGap(235));

        $parsed = (new FfnRecordParser())->parse(self::TABLEAU);
        $this->assertCount(4, $parsed['records']);
        [$nl, $dos, $relais, $m1500] = $parsed['records'];
        $this->assertSame(['F', '50 NL', 2410, ['DUPONT Emma (2001)'], 2001, 'CN EXEMPLE', '2025-12-07', 'POITIERS (FRA)'],
            [$nl['sex'], $nl['event'], $nl['time_cs'], $nl['swimmers'], $nl['birth_year'], $nl['club'], $nl['record_date'], $nl['location']]);
        $this->assertSame('100 Dos', $dos['event']);
        $this->assertSame(['4x50 NL', ['DUPONT Emma (2001)', 'MARTIN Léa (1999)'], 'CN EXEMPLE'], [$relais['event'], $relais['swimmers'], $relais['club']]);
        $this->assertSame(['M', '1500 NL', 88033], [$m1500['sex'], $m1500['event'], $m1500['time_cs']]);
    }

    public function test_parser_reads_one_cell_per_line_and_whole_page(): void
    {
        // Copie « Ctrl + A » : menu et filtres autour, une case par ligne
        $page = "Accueil\nNatation\nRecords\nDames\nMessieurs\nRecords de France\nà la date du 25/10/2026\nDames\n50 NL\n00:24.10\nDUPONT Emma (2001/24 ans) FRA\nCN EXEMPLE\n07/12/2025\nPOITIERS (FRA)\n"
            . "4 x 50 NL\n01:42.00\nDUPONT Emma (2001/23 ans) FRA\nCN EXEMPLE\n12/12/2024\nCHARTRES (FRA)\nMARTIN Léa (1999/25 ans) FRA\nROUX Anna (2002/22 ans) FRA\n"
            . "Messieurs\n50 Pap\n00:23.00\nPETIT Hugo (2000/25 ans) FRA\nCN EXEMPLE\n01/02/2025\nTOURS (FRA)\nLégende\nMentions légales";

        $records = (new FfnRecordParser())->parse($page)['records'];
        $this->assertSame(['F|50 NL', 'F|4x50 NL', 'M|50 Pap'], array_map(fn ($r) => $r['sex'] . '|' . $r['event'], $records));
        $this->assertSame(['DUPONT Emma (2001)', 'MARTIN Léa (1999)', 'ROUX Anna (2002)'], $records[1]['swimmers']);
        $this->assertSame(['CN EXEMPLE', 'CHARTRES (FRA)'], [$records[1]['club'], $records[1]['location']]);
        $this->assertSame('TOURS (FRA)', $records[2]['location']);
    }

    public function test_coach_imports_official_records(): void
    {
        $coach = $this->userWithRole('coach');
        $params = ['level' => 'france', 'pool' => 25, 'category' => 'all', 'text' => self::TABLEAU];

        // Aperçu : rien n'est enregistré
        $this->actingAs($coach)->post('/coach/records/importer', $params)->assertOk()
            ->assertSee('Aperçu : 4 record(s) lu(s)')->assertSee('DUPONT Emma (2001)');
        $this->assertSame(0, SwimRecord::count());

        // Un ancien record absent du tableau est retiré quand on remplace la liste
        SwimRecord::create(['level' => 'france', 'pool' => 25, 'category' => 'all', 'sex' => 'M', 'event' => '50 Pap', 'time_cs' => 2300, 'swimmers' => 'ANCIEN Record']);
        $this->actingAs($coach)->post('/coach/records/importer', $params + ['confirm' => '1', 'replace' => '1'])
            ->assertRedirect('/coach/records?niveau=france&bassin=25&categorie=all');

        $this->assertSame(4, SwimRecord::where('level', 'france')->count());
        $this->assertFalse(SwimRecord::where('swimmers', 'ANCIEN Record')->exists());
        $relais = SwimRecord::where('event', '4x50 NL')->firstOrFail();
        $this->assertSame(['DUPONT Emma (2001)', 'MARTIN Léa (1999)'], $relais->swimmer_list);
        $this->assertStringContainsString('idniv=3', $relais->source_url);

        // Ré-import : pas de doublon
        $this->actingAs($coach)->post('/coach/records/importer', $params + ['confirm' => '1', 'replace' => '1']);
        $this->assertSame(4, SwimRecord::count());

        // Tableau illisible
        $this->actingAs($coach)->post('/coach/records/importer', ['text' => 'rien'] + $params)->assertOk()->assertSee('Aucun record reconnu');
    }

    public function test_club_records_and_public_page(): void
    {
        $coach = $this->userWithRole('coach');
        SwimRecord::create(['level' => 'france', 'pool' => 25, 'category' => 'all', 'sex' => 'F', 'event' => '50 NL', 'time_cs' => 2410, 'swimmers' => 'DUPONT Emma (2001)', 'club' => 'CN EXEMPLE']);
        SwimRecord::create(['level' => 'departement', 'pool' => 25, 'category' => 'all', 'sex' => 'F', 'event' => '50 NL', 'time_cs' => 2700, 'swimmers' => 'ROUX Anna']);

        $this->actingAs($coach)->post('/coach/records', [
            'level' => 'club', 'pool' => 25, 'category' => 'all', 'sex' => 'F', 'event' => '50 NL', 'time' => '26.95',
            'swimmers' => 'BERNARD Chloé', 'birth_year' => 2008, 'record_date' => '2025-03-15', 'location' => 'NIORT (FRA)',
        ])->assertRedirect();
        $club = SwimRecord::where('level', 'club')->firstOrFail();
        $this->assertSame(2695, $club->time_cs);

        // Doublon refusé ; temps illisible refusé ; 100 4N en 50 m refusé ; « Mixtes » réservé aux relais
        $base = ['level' => 'club', 'pool' => 25, 'category' => 'all', 'sex' => 'F', 'event' => '50 NL', 'time' => '27.00', 'swimmers' => 'X'];
        $this->actingAs($coach)->post('/coach/records', $base)->assertSessionHasErrors('event');
        $this->actingAs($coach)->post('/coach/records', ['event' => '100 NL', 'time' => 'vite'] + $base)->assertSessionHasErrors('time');
        $this->actingAs($coach)->post('/coach/records', ['event' => '100 4N', 'pool' => 50] + $base)->assertSessionHasErrors('event');
        $this->actingAs($coach)->post('/coach/records', ['event' => '100 NL', 'sex' => 'X'] + $base)->assertSessionHasErrors('sex');

        // Page publique : records du club avec écarts
        $this->get('/records')->assertOk()
            ->assertSee('Records du club')->assertSee('BERNARD Chloé')->assertSee('26.95')
            ->assertSee('Dép. ★ −0.05')->assertSee('France +2.85');
        $this->get('/records?niveau=france&bassin=25')->assertOk()->assertSee('DUPONT Emma (2001)')->assertSee('CN EXEMPLE')->assertSee('extraNat');
        $this->get('/records?bassin=50')->assertOk()->assertSee('Pas encore de records');
        $this->get('/records?niveau=pirate&categorie=99')->assertOk();

        // Modification et suppression
        $this->actingAs($coach)->put("/coach/records/{$club->id}", ['time' => '26.50', 'swimmers' => 'BERNARD Chloé'] + $base)->assertRedirect();
        $this->assertSame(2650, $club->fresh()->time_cs);
        $this->actingAs($coach)->delete("/coach/records/{$club->id}")->assertRedirect();
        $this->assertNull($club->fresh());
    }

    public function test_access(): void
    {
        $admin = $this->userWithRole('admin');
        $this->actingAs($admin)->get('/coach/records')->assertOk();
        $this->actingAs($admin)->get('/coach/records/importer')->assertOk();
        foreach (['editor', 'user'] as $slug) {
            $this->actingAs($this->userWithRole($slug))->get('/coach/records')->assertForbidden();
        }
    }
}
