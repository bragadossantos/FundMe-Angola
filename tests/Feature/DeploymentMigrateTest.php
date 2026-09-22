<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The /deploy/migrate route is the only way to run migrations on a
 * serverless host (Vercel) with no shell access — it must stay locked down
 * by default and only work with the exact configured token.
 */
class DeploymentMigrateTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrate_route_refuses_access_when_no_token_is_configured(): void
    {
        config(['app.deploy_token' => null]);

        $this->get('/deploy/migrate?token=anything')->assertForbidden();
    }

    public function test_migrate_route_refuses_a_wrong_token(): void
    {
        config(['app.deploy_token' => 'the-real-token']);

        $this->get('/deploy/migrate?token=wrong-token')->assertForbidden();
    }

    public function test_migrate_route_runs_migrations_with_the_correct_token(): void
    {
        config(['app.deploy_token' => 'the-real-token']);

        // RefreshDatabase already migrated the test database, so running
        // migrate again should simply report there's nothing left to do —
        // proving the route executed the artisan command without error.
        $response = $this->get('/deploy/migrate?token=the-real-token');

        $response->assertOk();
        $response->assertSee('Nothing to migrate');
    }
}
