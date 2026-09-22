<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\AuditLog;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Regression test for the bug where AuditLog::log() was called with the
     * user's class name (a string) instead of their id, which crashed every
     * registration with a TypeError because AuditLog::log() expects ?int.
     */
    public function test_registration_succeeds_and_records_the_new_users_id_in_the_audit_log(): void
    {
        $response = $this->post('/registo', [
            'name' => 'Teste Utilizador',
            'email' => 'teste@example.com',
            'phone' => '+244900000000',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'teste@example.com')->firstOrFail();

        $log = AuditLog::where('action', 'user_registered')->first();
        $this->assertNotNull($log);
        $this->assertSame($user->id, $log->entity_id);
    }
}
