<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Signing up through Google or Facebook never creates a profile row, so
     * the relation is null for those accounts and the page used to die on the
     * avatar accessor.
     */
    public function test_the_profile_page_renders_for_an_account_without_a_profile_row(): void
    {
        $user = User::factory()->create();

        $this->assertNull($user->profile, 'Testul are sens doar fără profil.');

        $this->actingAs($user)->get(route('dashboard.profile.edit'))->assertStatus(200);
    }

    public function test_saving_the_profile_creates_the_missing_row(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('dashboard.profile.update'), [
            'first_name' => 'Ion',
            'last_name' => 'Popescu',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'first_name' => 'Ion',
            'last_name' => 'Popescu',
        ]);
    }
}
