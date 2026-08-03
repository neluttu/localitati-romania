<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ion Popescu',
            'email' => 'ion@example.com',
            'password' => 'Parola-Sigura1!',
            'password_confirmation' => 'Parola-Sigura1!',
            'terms' => true,
        ], $overrides);
    }

    public function test_registration_returns_a_token(): void
    {
        $response = $this->postJson('/v1/auth/register', $this->validPayload());

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'user' => ['id', 'email'], 'token']);
    }

    /**
     * The endpoint demanded a name and then dropped it: the request failed
     * without one, and the value never reached the database.
     */
    public function test_the_name_it_asks_for_is_actually_stored(): void
    {
        $this->postJson('/v1/auth/register', $this->validPayload());

        $user = User::where('email', 'ion@example.com')->firstOrFail();

        $this->assertNotNull($user->profile, 'Contul creat prin API trebuie să aibă profil.');
        $this->assertSame('Ion', $user->profile->first_name);
        $this->assertSame('Popescu', $user->profile->last_name);
    }

    public function test_a_single_word_name_is_stored_as_the_first_name(): void
    {
        $this->postJson('/v1/auth/register', $this->validPayload(['name' => 'Madonna']));

        $user = User::where('email', 'ion@example.com')->firstOrFail();

        $this->assertSame('Madonna', $user->profile->first_name);
        $this->assertSame('', $user->profile->last_name);
    }

    /**
     * The published policies state that consent is taken when an account is
     * created, so a second registration path must not skip it.
     */
    public function test_registration_requires_accepting_the_terms(): void
    {
        $response = $this->postJson('/v1/auth/register', $this->validPayload(['terms' => false]));

        $response->assertStatus(422)->assertJsonValidationErrors('terms');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_acceptance_is_recorded_with_a_timestamp(): void
    {
        $this->postJson('/v1/auth/register', $this->validPayload());

        $user = User::where('email', 'ion@example.com')->firstOrFail();

        $this->assertNotNull($user->terms_accepted_at);
    }

    public function test_login_returns_a_token_for_valid_credentials(): void
    {
        $this->postJson('/v1/auth/register', $this->validPayload());

        $response = $this->postJson('/v1/auth/login', [
            'email' => 'ion@example.com',
            'password' => 'Parola-Sigura1!',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message', 'user', 'token']);
    }

    public function test_login_rejects_wrong_credentials(): void
    {
        $this->postJson('/v1/auth/register', $this->validPayload());

        $this->postJson('/v1/auth/login', [
            'email' => 'ion@example.com',
            'password' => 'parola-gresita',
        ])->assertStatus(401);
    }

    /**
     * A deleted account must not be able to trade its password back for a
     * fresh API token.
     */
    public function test_a_deleted_account_cannot_log_in(): void
    {
        $this->postJson('/v1/auth/register', $this->validPayload());
        User::where('email', 'ion@example.com')->firstOrFail()->delete();

        $this->postJson('/v1/auth/login', [
            'email' => 'ion@example.com',
            'password' => 'Parola-Sigura1!',
        ])->assertStatus(401);
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/v1/auth/me')->assertStatus(401);
    }
}
