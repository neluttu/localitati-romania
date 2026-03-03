<?php

namespace Tests\Feature\Api;

use App\Models\County;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountyApiTest extends TestCase
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

    public function test_get_counties_returns_list(): void
    {
        County::factory()->count(3)->create();

        $response = $this->getJson('/v1/counties', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'siruta_code',
                        'name',
                        'name_ascii',
                        'abbr',
                        'region' => ['id', 'label'],
                    ],
                ],
                'meta' => ['total'],
            ])
            ->assertJsonPath('meta.total', 3);
    }

    public function test_get_counties_returns_empty_when_no_counties(): void
    {
        $response = $this->getJson('/v1/counties', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 0)
            ->assertJsonPath('data', []);
    }

    public function test_get_single_county_by_abbr(): void
    {
        $county = County::factory()->create([
            'abbr' => 'MS',
            'name' => 'Mureș',
        ]);

        $response = $this->getJson('/v1/counties/ms', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'siruta_code',
                    'name',
                    'abbr',
                    'region',
                ],
                'meta' => [
                    'localities_endpoint',
                    'localities_endpoint_lite',
                    'localities_endpoint_grouped',
                ],
            ])
            ->assertJsonPath('data.abbr', 'MS')
            ->assertJsonPath('data.name', 'Mureș');
    }

    public function test_get_nonexistent_county_returns_404(): void
    {
        $response = $this->getJson('/v1/counties/XX', $this->apiHeaders());

        $response->assertStatus(404);
    }

    public function test_county_abbr_is_case_insensitive(): void
    {
        County::factory()->create([
            'abbr' => 'CJ',
            'name' => 'Cluj',
        ]);

        $responseLower = $this->getJson('/v1/counties/cj', $this->apiHeaders());
        $responseUpper = $this->getJson('/v1/counties/CJ', $this->apiHeaders());

        $responseLower->assertStatus(200);
        $responseUpper->assertStatus(200);
    }
}
