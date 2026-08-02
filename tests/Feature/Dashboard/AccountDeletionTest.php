<?php

namespace Tests\Feature\Dashboard;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    private function userWithPassword(string $password = 'password'): User
    {
        return User::factory()->create(['password' => Hash::make($password)]);
    }

    public function test_a_user_can_delete_their_own_account(): void
    {
        $user = $this->userWithPassword();

        $response = $this->actingAs($user)->delete(route('dashboard.account.destroy'), [
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertGuest();
    }

    public function test_deleting_an_account_also_removes_its_sites(): void
    {
        $user = $this->userWithPassword();
        $site = Site::factory()->create(['user_id' => $user->id, 'domain' => 'example.com']);

        $this->actingAs($user)->delete(route('dashboard.account.destroy'), [
            'password' => 'password',
        ]);

        $this->assertSoftDeleted('sites', ['id' => $site->id]);
    }

    /**
     * The whole point of deleting an account is that it stops working, so the
     * token must not survive the click even for a moment.
     */
    public function test_the_api_token_stops_working_immediately(): void
    {
        $user = $this->userWithPassword();
        $site = Site::factory()->create(['user_id' => $user->id, 'domain' => 'example.com']);

        $this->getJson('/v1/counties', ['X-Site-Token' => $site->token])->assertStatus(200);

        $this->actingAs($user)->delete(route('dashboard.account.destroy'), [
            'password' => 'password',
        ]);

        $this->getJson('/v1/counties', ['X-Site-Token' => $site->token])->assertStatus(401);
    }

    public function test_a_deleted_user_cannot_log_in(): void
    {
        $user = $this->userWithPassword();

        $this->actingAs($user)->delete(route('dashboard.account.destroy'), [
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_deletion_requires_the_current_password(): void
    {
        $user = $this->userWithPassword();

        $response = $this->actingAs($user)->delete(route('dashboard.account.destroy'), [
            'password' => 'alta-parola',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
        $this->assertAuthenticated();
    }

    public function test_the_confirmation_screen_states_what_will_happen(): void
    {
        $user = $this->userWithPassword();

        $response = $this->actingAs($user)->get(route('dashboard.account.delete'));

        $response->assertStatus(200)
            ->assertSee('30 de zile')
            ->assertSee('Șterge contul definitiv', false);
    }

    public function test_the_profile_page_links_to_account_deletion(): void
    {
        $user = $this->userWithPassword();

        $this->actingAs($user)->get(route('dashboard.profile.edit'))
            ->assertStatus(200)
            ->assertSee(route('dashboard.account.delete'), false);
    }

    public function test_a_guest_cannot_reach_the_deletion_route(): void
    {
        $this->delete(route('dashboard.account.destroy'), ['password' => 'password'])
            ->assertRedirect(route('login'));
    }
}
