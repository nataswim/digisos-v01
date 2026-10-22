<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pages « Devenir mécène » et « Faire un don ».
 * Lancer : php artisan test --filter=SupportPagesTest
 */
class SupportPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_mecenat_page(): void
    {
        $this->get('/devenir-mecene')->assertOk()
            ->assertSeeInOrder(['Devenir mécène', '60 %', 'Trois façons d', 'L\'avantage fiscal', '20 000 €', 'Mécénat ou partenariat', 'Comment devenir mécène'], false)
            ->assertSee(route('support.donation'), false)
            ->assertSee(route('partners.index'), false)
            ->assertSee('Cercle des Nageurs du Bocage Bressuirais');
    }

    public function test_donation_page(): void
    {
        $this->get('/faire-un-don')->assertOk()
            ->assertSeeInOrder(['Faire un don', '66 %', '34 €', 'case 7UF', 'La cotisation n', 'Comment faire un don'], false)
            ->assertSee(route('support.mecenat'), false);
    }

    public function test_bank_details_come_from_configuration(): void
    {
        // Sans IBAN : RIB sur demande
        config(['club.dons.iban' => null]);
        $this->get('/faire-un-don')->assertSee('Demander le RIB')->assertDontSee('IBAN :');

        config(['club.dons.iban' => 'FR76 1234 5678 9012 3456 7890 123', 'club.dons.bic' => 'TESTFRPP']);
        $this->get('/faire-un-don')->assertSee('FR76 1234 5678 9012 3456 7890 123')->assertSee('TESTFRPP')->assertDontSee('Demander le RIB');
    }

    public function test_pages_are_in_the_menu(): void
    {
        $this->get('/')->assertOk()
            ->assertSee(route('support.mecenat'), false)
            ->assertSee(route('support.donation'), false)
            ->assertSee(route('partners.index'), false);
    }
}
