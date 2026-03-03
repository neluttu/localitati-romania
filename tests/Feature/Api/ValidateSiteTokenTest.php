<?php

namespace Tests\Feature\Api;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidateSiteTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_without_token_returns_401(): void
    {
        $response = $this->getJson('/v1/counties');

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Missing X-Site-Token header.',
            ]);
    }

    public function test_request_with_invalid_token_returns_401(): void
    {
        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => 'invalid-token-that-does-not-exist',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid or inactive site token.',
            ]);
    }

    public function test_request_with_inactive_site_token_returns_401(): void
    {
        $user = User::factory()->create();
        $site = Site::factory()->inactive()->create([
            'user_id' => $user->id,
            'domain' => 'example.com',
        ]);

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid or inactive site token.',
            ]);
    }

    public function test_request_with_valid_token_succeeds(): void
    {
        $user = User::factory()->create();
        $site = Site::factory()->create([
            'user_id' => $user->id,
            'domain' => 'example.com',
        ]);

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://example.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_request_from_mismatched_domain_returns_403(): void
    {
        $user = User::factory()->create();
        $site = Site::factory()->create([
            'user_id' => $user->id,
            'domain' => 'example.com',
        ]);

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://different-domain.com',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Domain mismatch.',
            ]);
    }

    public function test_subdomain_is_allowed_for_registered_domain(): void
    {
        $user = User::factory()->create();
        $site = Site::factory()->create([
            'user_id' => $user->id,
            'domain' => 'example.com',
        ]);

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://sub.example.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_wildcard_domain_allows_all_subdomains(): void
    {
        $user = User::factory()->create();
        $site = Site::factory()->create([
            'user_id' => $user->id,
            'domain' => '*.example.com',
        ]);

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://any.subdomain.example.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_localhost_domain_allows_localhost_requests(): void
    {
        $user = User::factory()->create();
        $site = Site::factory()->create([
            'user_id' => $user->id,
            'domain' => 'localhost',
        ]);

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'http://localhost:3000',
        ]);

        $response->assertStatus(200);
    }
}
