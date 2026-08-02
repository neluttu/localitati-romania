<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTermsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Ion',
            'last_name' => 'Popescu',
            'email' => 'ion.popescu@example.com',
            'password' => 'Parola-Sigura1!',
            'password_confirmation' => 'Parola-Sigura1!',
            'terms' => '1',
        ], $overrides);
    }

    public function test_registration_requires_accepting_the_terms(): void
    {
        $response = $this->post('/register', $this->validPayload(['terms' => null]));

        $response->assertSessionHasErrors('terms');
        $this->assertDatabaseCount('users', 0);
    }

    /**
     * Consent has to be demonstrable later, so the moment it was given is
     * recorded rather than only the fact that a box was ticked.
     */
    public function test_accepting_the_terms_is_recorded_with_a_timestamp(): void
    {
        $this->post('/register', $this->validPayload());

        $user = User::where('email', 'ion.popescu@example.com')->first();

        $this->assertNotNull($user, 'Contul trebuia creat.');
        $this->assertNotNull($user->terms_accepted_at, 'Momentul acceptării trebuia salvat.');
    }

    public function test_the_registration_form_links_to_all_three_documents(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
            ->assertSee(route('legal.terms'), false)
            ->assertSee(route('legal.privacy'), false)
            ->assertSee(route('legal.cookies'), false);
    }
}
