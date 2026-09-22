<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Beneficiary;
use App\Models\Campaign;
use App\Models\Hospital;
use App\Models\Donation;
use App\Models\Report;
use App\Models\PaymentDestination;
use Tests\TestCase;

/**
 * Renders every page touched by the frontend audit/fix pass so a Blade
 * syntax error, missing variable, or bad route() call fails loudly here
 * instead of only showing up when a real visitor hits it.
 */
class PageRenderSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function makeCampaign(array $overrides = []): Campaign
    {
        $user = User::factory()->create(['role' => 'applicant']);
        $beneficiary = Beneficiary::create([
            'user_id' => $user->id,
            'full_name' => 'Beneficiário Teste',
            'age_range' => '18-35 anos',
            'relation_to_applicant' => 'O próprio',
            'location_province' => 'Luanda',
        ]);

        return Campaign::create(array_merge([
            'user_id' => $user->id,
            'beneficiary_id' => $beneficiary->id,
            'title' => 'Campanha de Teste de Renderização',
            'slug' => 'campanha-teste-render-' . uniqid(),
            'short_description' => 'Descrição curta de teste',
            'story' => str_repeat('História de teste. ', 10),
            'category' => 'cirurgia',
            'target_amount' => 500000,
            'raised_amount' => 250000,
            'status' => 'published',
            'location_province' => 'Luanda',
            'published_at' => now(),
            'verification_badge' => true,
        ], $overrides));
    }

    public function test_public_pages_render(): void
    {
        $campaign = $this->makeCampaign();
        Hospital::create([
            'name' => 'Hospital Teste',
            'province' => 'Luanda',
            'is_verified' => true,
        ]);

        $this->get('/')->assertOk();
        $this->get('/como-funciona')->assertOk();
        $this->get('/campanhas')->assertOk();
        $this->get('/campanhas?sort=urgent')->assertOk();
        $this->get('/campanhas?sort=most_raised')->assertOk();
        $this->get('/campanhas/' . $campaign->slug)->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/registo')->assertOk();
        $this->get('/esqueci-palavra-passe')->assertOk();
        $this->get('/redefinir-palavra-passe/fake-token?email=teste@example.com')->assertOk();
    }

    public function test_campaign_creation_wizard_renders_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        Hospital::create(['name' => 'Hospital Teste', 'province' => 'Luanda', 'is_verified' => true]);

        $this->actingAs($user)->get('/pedir-ajuda')->assertOk();
    }

    public function test_campaign_creation_requires_and_persists_terms_acceptance(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/pedir-ajuda', [
            'beneficiary_name' => 'Paciente Teste',
            'age_range' => '18-35 anos',
            'relation_to_applicant' => 'O próprio',
            'location_province' => 'Luanda',
            'title' => 'Cirurgia de Teste',
            'category' => 'cirurgia',
            'treatment_location' => 'angola',
            'short_description' => 'Resumo curto de teste para a campanha.',
            'story' => str_repeat('Detalhe da história clínica de teste. ', 5),
            'target_amount' => 200000,
            // terms_accepted intentionally omitted
        ]);

        $response->assertSessionHasErrors('terms_accepted');
        $this->assertDatabaseCount('campaigns', 0);

        $response = $this->actingAs($user)->post('/pedir-ajuda', [
            'beneficiary_name' => 'Paciente Teste',
            'age_range' => '18-35 anos',
            'relation_to_applicant' => 'O próprio',
            'location_province' => 'Luanda',
            'title' => 'Cirurgia de Teste',
            'category' => 'cirurgia',
            'treatment_location' => 'angola',
            'short_description' => 'Resumo curto de teste para a campanha.',
            'story' => str_repeat('Detalhe da história clínica de teste. ', 5),
            'target_amount' => 200000,
            'terms_accepted' => '1',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $campaign = Campaign::firstOrFail();
        $this->assertNotNull($campaign->terms_accepted_at);
    }

    public function test_dashboard_pages_render_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/dashboard/doacoes')->assertOk();
        $this->actingAs($user)->get('/dashboard/campanhas')->assertOk();
        $this->actingAs($user)->get('/dashboard/perfil')->assertOk();
    }

    public function test_donation_checkout_renders(): void
    {
        $campaign = $this->makeCampaign();

        $this->post(route('donations.store', $campaign), [
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
        ]);

        $donation = Donation::firstOrFail();

        $this->get(route('donations.checkout', $donation))->assertOk();
    }

    public function test_admin_pages_render_for_admin_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $campaign = $this->makeCampaign(['status' => 'goal_reached']);

        PaymentDestination::create([
            'campaign_id' => $campaign->id,
            'destination_type' => 'hospital_direct',
            'institution_or_payee_name' => 'Hospital Teste',
            'authorized_amount' => $campaign->target_amount,
        ]);

        Report::create([
            'campaign_id' => $campaign->id,
            'reason' => 'suspected_fraud',
            'description' => str_repeat('Detalhe da denúncia de teste. ', 3),
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/campanhas')->assertOk();
        $this->actingAs($admin)->get('/admin/campanhas/' . $campaign->id)->assertOk();
        $this->actingAs($admin)->get('/admin/doacoes')->assertOk();
        $this->actingAs($admin)->get('/admin/pagamentos')->assertOk();
        $this->actingAs($admin)->get('/admin/denuncias')->assertOk();
        $this->actingAs($admin)->get('/admin/documentos')->assertOk();
        $this->actingAs($admin)->get('/admin/logs')->assertOk();
        $this->actingAs($admin)->get('/admin/utilizadores')->assertOk();
    }
}
