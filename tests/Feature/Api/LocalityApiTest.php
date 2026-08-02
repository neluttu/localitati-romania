<?php

namespace Tests\Feature\Api;

use App\Enums\LocalityType;
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

    public function test_lite_endpoint_returns_a_simplified_list(): void
    {
        $county = County::factory()->create(['abbr' => 'MS']);
        Locality::factory()->count(2)->create([
            'county_id' => $county->id,
            'type' => LocalityType::SAT->value,
        ]);

        $response = $this->getJson('/v1/localities/lite?county=MS', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'siruta_code', 'name', 'name_ascii', 'postal_code'],
                ],
                'meta' => ['county', 'total'],
            ]);
    }

    public function test_lite_endpoint_requires_a_county(): void
    {
        $response = $this->getJson('/v1/localities/lite', $this->apiHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors('county');
    }

    public function test_grouped_endpoint_groups_localities_by_type(): void
    {
        $county = County::factory()->create(['abbr' => 'MS']);
        Locality::factory()->create([
            'county_id' => $county->id,
            'type' => LocalityType::MUNICIPIU->value,
        ]);
        Locality::factory()->create([
            'county_id' => $county->id,
            'type' => LocalityType::SAT->value,
        ]);

        $response = $this->getJson('/v1/localities/grouped?county=MS', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.municipii')
            ->assertJsonCount(1, 'data.sate')
            ->assertJsonPath('meta.counts.municipii', 1)
            ->assertJsonPath('meta.counts.sate', 1);
    }

    public function test_grouped_endpoint_requires_a_county(): void
    {
        $response = $this->getJson('/v1/localities/grouped', $this->apiHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors('county');
    }

    /**
     * The named sub-paths must win over the {siruta} placeholder, otherwise
     * they are read as locality codes and can never resolve.
     */
    public function test_named_sub_paths_are_not_swallowed_by_the_siruta_route(): void
    {
        County::factory()->create(['abbr' => 'MS']);

        $this->getJson('/v1/localities/lite?county=MS', $this->apiHeaders())
            ->assertStatus(200);
        $this->getJson('/v1/localities/grouped?county=MS', $this->apiHeaders())
            ->assertStatus(200);
    }

    public function test_a_single_locality_is_returned_by_siruta_code(): void
    {
        $county = County::factory()->create(['abbr' => 'MS']);
        Locality::factory()->create([
            'county_id' => $county->id,
            'siruta_code' => 114458,
            'name' => 'Municipiul Târgu Mureș',
            'name_ascii' => 'targu mures',
            'type' => LocalityType::MUNICIPIU_RESEDINTA->value,
        ]);

        $response = $this->getJson('/v1/localities/114458', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonPath('data.siruta_code', 114458)
            ->assertJsonPath('data.name', 'Târgu Mureș')
            ->assertJsonPath('data.type', LocalityType::MUNICIPIU_RESEDINTA->value)
            ->assertJsonPath('meta.county.abbr', 'MS');
    }

    public function test_a_locality_exposes_its_parent(): void
    {
        $county = County::factory()->create(['abbr' => 'MS']);
        Locality::factory()->create([
            'county_id' => $county->id,
            'siruta_code' => 114458,
            'name' => 'Municipiul Târgu Mureș',
            'type' => LocalityType::MUNICIPIU_RESEDINTA->value,
        ]);
        Locality::factory()->create([
            'county_id' => $county->id,
            'siruta_code' => 114467,
            'siruta_parent' => 114458,
            'name' => 'Remetea',
            'type' => LocalityType::COMPONENTA_MUNICIPIU->value,
        ]);

        $response = $this->getJson('/v1/localities/114467', $this->apiHeaders());

        $response->assertStatus(200)
            ->assertJsonPath('data.parent.siruta_code', 114458)
            ->assertJsonPath('data.parent.name', 'Târgu Mureș');
    }

    public function test_unknown_siruta_code_returns_404(): void
    {
        $response = $this->getJson('/v1/localities/999999', $this->apiHeaders());

        $response->assertStatus(404)
            ->assertJsonPath('error', 'Locality not found.');
    }

    /**
     * A non-numeric segment is what an undocumented path such as
     * /v1/localities/foo collapses to. It must read as "no such locality",
     * never as a server error.
     */
    public function test_non_numeric_siruta_code_returns_404(): void
    {
        $response = $this->getJson('/v1/localities/not-a-code', $this->apiHeaders());

        $response->assertStatus(404)
            ->assertJsonPath('error', 'Locality not found.');
    }
}
