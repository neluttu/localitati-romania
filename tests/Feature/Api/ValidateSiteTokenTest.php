<?php

namespace Tests\Feature\Api;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * A site token identifies who is calling so usage can be attributed; it is
 * deliberately not an access restriction. Anyone who registers gets one and
 * may call the API from anywhere, so every domain-shaped case below asserts
 * the call goes through. Only a missing, unknown or deactivated token is
 * turned away.
 */
class ValidateSiteTokenTest extends TestCase
{
    use RefreshDatabase;

    private function siteWithDomain(string $domain): Site
    {
        return Site::factory()->create([
            'user_id' => User::factory()->create()->id,
            'domain' => $domain,
        ]);
    }

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
        $site = Site::factory()->inactive()->create([
            'user_id' => User::factory()->create()->id,
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
        $site = $this->siteWithDomain('example.com');

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://example.com',
        ]);

        $response->assertStatus(200);
    }

    /**
     * The registered domain is a label for the owner's own reporting, so a
     * call from somewhere else is still a valid call.
     */
    public function test_token_works_from_a_completely_different_domain(): void
    {
        $site = $this->siteWithDomain('example.com');

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://different-domain.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_token_works_from_a_lookalike_domain(): void
    {
        $site = $this->siteWithDomain('example.com');

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://notexample.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_token_works_from_a_subdomain(): void
    {
        $site = $this->siteWithDomain('example.com');

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://sub.example.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_token_registered_with_a_wildcard_domain_works(): void
    {
        $site = $this->siteWithDomain('*.example.com');

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://any.subdomain.example.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_token_works_from_localhost(): void
    {
        $site = $this->siteWithDomain('example.com');

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'http://localhost:3000',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Server-to-server callers send no Origin at all.
     */
    public function test_request_without_origin_or_referer_is_allowed(): void
    {
        $site = $this->siteWithDomain('example.com');

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
        ]);

        $response->assertStatus(200);
    }

    public function test_token_works_when_only_a_foreign_referer_is_sent(): void
    {
        $site = $this->siteWithDomain('example.com');

        $response = $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Referer' => 'https://different-domain.com/some/page',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Every call that carries a usable token must reach the access log,
     * otherwise usage cannot be attributed to the site that made it.
     */
    public function test_a_successful_call_is_attributed_to_the_site(): void
    {
        $site = $this->siteWithDomain('example.com');

        $this->getJson('/v1/counties', [
            'X-Site-Token' => $site->token,
            'Origin' => 'https://anywhere.example',
        ])->assertStatus(200);

        $this->assertDatabaseHas('api_logs', [
            'site_id' => $site->id,
            'endpoint' => '/v1/counties',
            'status_code' => 200,
        ]);
    }
}
