<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Regression test for the privilege-escalation bug where every /admin/*
     * route only required the 'verifier' middleware (which also accepts
     * admins), so a Verifier account could promote any user — including
     * itself — to Admin via /admin/utilizadores/{user}/role.
     */
    public function test_verifier_cannot_promote_a_user_to_admin(): void
    {
        $verifier = User::factory()->create(['role' => 'verifier']);
        $target = User::factory()->create(['role' => 'donor']);

        $this->actingAs($verifier)->post(route('admin.users.update_role', $target), [
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->assertSame('donor', $target->fresh()->role);
    }

    public function test_verifier_cannot_access_fund_disbursement_screen(): void
    {
        $verifier = User::factory()->create(['role' => 'verifier']);

        $response = $this->actingAs($verifier)->get(route('admin.payments'));

        $response->assertRedirect();
    }

    public function test_admin_can_update_a_user_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'donor']);

        $this->actingAs($admin)->post(route('admin.users.update_role', $target), [
            'role' => 'verifier',
            'status' => 'active',
        ]);

        $this->assertSame('verifier', $target->fresh()->role);
    }
}
