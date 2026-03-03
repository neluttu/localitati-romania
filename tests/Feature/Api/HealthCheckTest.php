<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_health_endpoint_returns_ok(): void
    {
        $response = $this->getJson('/v1/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
            ])
            ->assertJson([
                'status' => 'ok',
            ]);
    }

    public function test_health_endpoint_does_not_require_authentication(): void
    {
        // No X-Site-Token header
        $response = $this->getJson('/v1/health');

        $response->assertStatus(200);
    }
}
