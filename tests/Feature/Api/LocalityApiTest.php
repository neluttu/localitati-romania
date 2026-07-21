<?php

namespace Tests\Feature\Api;

use App\Models\County;
use App\Models\Locality;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalityApiTest extends TestCase
{
    use RefreshDatabase;

    private Site $site;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->site = Site::factory()->create([
            'user_id' => $user->id,
            'domain' => 'example.com',
        ]);
    }

    private function apiHeaders(): array
    {
        return [
            'X-Site-Token' => $this->site->token,
            'Origin' => 'https://example.com',
        ];
    }

    public function test_localities_are_returned_for_a_two_letter_county(): void
    {
        $county = County::factory()->create(['abbr' => 'MS']);
        Locality::factory()->count(3)->create(['county_id' => $county->id]);

        $response = $this->getJson('/v1/localities?county=MS', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * București is the only county whose abbreviation is a single character,
     * so a fixed two-character rule silently hides every locality it has.
     */
    public function test_localities_are_returned_for_bucharest_single_letter_abbr(): void
    {
        $county = County::factory()->create(['abbr' => 'B', 'name' => 'București']);
        Locality::factory()->count(4)->create(['county_id' => $county->id]);

        $response = $this->getJson('/v1/localities?county=B', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data');
    }

    public function test_bucharest_abbr_is_accepted_in_lowercase(): void
    {
        $county = County::factory()->create(['abbr' => 'B', 'name' => 'București']);
        Locality::factory()->count(2)->create(['county_id' => $county->id]);

        $response = $this->getJson('/v1/localities?county=b', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_unknown_county_abbr_is_rejected(): void
    {
        $response = $this->getJson('/v1/localities?county=XX', $this->apiHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors('county');
    }

    public function test_overlong_county_abbr_is_rejected(): void
    {
        County::factory()->create(['abbr' => 'MS']);

        $response = $this->getJson('/v1/localities?county=MSX', $this->apiHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors('county');
    }

    public function test_missing_county_is_rejected(): void
    {
        $response = $this->getJson('/v1/localities', $this->apiHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors('county');
    }
}
