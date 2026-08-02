<?php

namespace Tests\Feature\Api;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LookupApiTest extends TestCase
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

    public function test_locality_types_are_returned(): void
    {
        $response = $this->getJson('/v1/lookups/locality-types', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'code', 'label', 'group'],
                ],
                'meta' => ['total'],
            ]);
    }

    public function test_locality_types_expose_the_siruta_code_and_its_label(): void
    {
        $response = $this->getJson('/v1/lookups/locality-types', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => 3,
                'code' => 'COMUNA',
                'label' => 'Comună',
            ]);
    }

    /**
     * UNKNOWN is a parsing fallback, not a real SIRUTA classification, so it
     * would only pollute the dropdowns this endpoint exists to populate.
     */
    public function test_locality_types_exclude_the_unknown_fallback(): void
    {
        $response = $this->getJson('/v1/lookups/locality-types', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonMissing(['id' => 99]);
    }

    public function test_locality_types_are_ordered_by_administrative_rank(): void
    {
        $response = $this->getJson('/v1/lookups/locality-types', $this->apiHeaders());

        $ids = array_column($response->json('data'), 'id');

        $this->assertSame(1, $ids[0], 'Municipiu reședință de județ must come first.');
        $this->assertLessThan(
            array_search(23, $ids, true),
            array_search(2, $ids, true),
            'Orașe must be listed before sate.'
        );
    }

    public function test_regions_are_returned(): void
    {
        $response = $this->getJson('/v1/lookups/regions', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonCount(8, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'code', 'label', 'counties'],
                ],
                'meta' => ['total'],
            ]);
    }

    public function test_regions_list_the_counties_they_contain(): void
    {
        $response = $this->getJson('/v1/lookups/regions', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => 8,
                'code' => 'BUCURESTI_ILFOV',
                'label' => 'București-Ilfov',
                'counties' => ['B', 'IF'],
            ]);
    }

    public function test_lookups_require_a_site_token(): void
    {
        $this->getJson('/v1/lookups/regions')->assertStatus(401);
        $this->getJson('/v1/lookups/locality-types')->assertStatus(401);
    }
}
