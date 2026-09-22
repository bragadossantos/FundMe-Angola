<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Beneficiary;
use App\Models\Campaign;
use App\Models\Donation;
use Tests\TestCase;

class DonationSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function createPublishedCampaign(): Campaign
    {
        $user = User::factory()->create();

        $beneficiary = Beneficiary::create([
            'user_id' => $user->id,
            'full_name' => 'Beneficiário Teste',
            'age_range' => '18-35 anos',
            'relation_to_applicant' => 'O próprio',
            'location_province' => 'Luanda',
        ]);

        return Campaign::create([
            'user_id' => $user->id,
            'beneficiary_id' => $beneficiary->id,
            'title' => 'Campanha Teste',
            'slug' => 'campanha-teste-' . uniqid(),
            'short_description' => 'Descrição curta',
            'story' => str_repeat('a', 60),
            'category' => 'cirurgia',
            'target_amount' => 100000,
            'raised_amount' => 0,
            'status' => 'published',
            'location_province' => 'Luanda',
            'published_at' => now(),
        ]);
    }

    /**
     * Regression test for the IDOR where /doacoes/{id}/checkout used the
     * sequential donation id, so anyone could enumerate other donors'
     * name/email/phone/amount. The route now binds on an unguessable token.
     */
    public function test_donation_checkout_cannot_be_reached_by_guessing_the_sequential_id(): void
    {
        $campaign = $this->createPublishedCampaign();

        $this->post(route('donations.store', $campaign), [
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
        ]);

        $donation = Donation::firstOrFail();

        $this->get('/doacoes/' . $donation->id . '/checkout')->assertNotFound();
        $this->get(route('donations.checkout', $donation))->assertOk();
    }

    /**
     * Regression test for the fraud bug where anyone could POST to
     * /doacoes/{id}/confirmar and mark ANY donation as paid without ever
     * paying, since no real payment gateway verifies the transaction. Only
     * the sandbox/demo method may be self-confirmed; real-world methods must
     * go through staff manual reconciliation instead.
     */
    public function test_bank_transfer_donation_cannot_be_self_confirmed_as_paid(): void
    {
        $campaign = $this->createPublishedCampaign();

        $this->post(route('donations.store', $campaign), [
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
        ]);

        $donation = Donation::firstOrFail();

        $this->post(route('donations.confirm', $donation));

        $donation->refresh();
        $this->assertSame('processing', $donation->status);
        $campaign->refresh();
        $this->assertSame(0.0, (float) $campaign->raised_amount);
    }

    public function test_sandbox_donation_can_still_be_self_confirmed_for_demo_purposes(): void
    {
        $campaign = $this->createPublishedCampaign();

        $this->post(route('donations.store', $campaign), [
            'amount' => 5000,
            'payment_method' => 'sandbox',
        ]);

        $donation = Donation::firstOrFail();

        $this->post(route('donations.confirm', $donation));

        $donation->refresh();
        $this->assertSame('paid', $donation->status);
    }
}
