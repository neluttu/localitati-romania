<?php

namespace Tests\Feature\Dashboard;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_a_site(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('dashboard.sites.store'), [
            'name' => 'Site de test',
            'domain' => 'example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sites', [
            'user_id' => $user->id,
            'domain' => 'example.com',
        ]);
    }

    /**
     * The domain is a label for the owner's own reporting, not a claim on that
     * domain, so two unrelated people signing up must not collide. A global
     * unique index here turns the second registration into a 500.
     */
    public function test_two_users_can_register_the_same_domain(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();

        $this->actingAs($first)->post(route('dashboard.sites.store'), [
            'name' => 'Primul site',
            'domain' => 'example.com',
        ]);

        $response = $this->actingAs($second)->post(route('dashboard.sites.store'), [
            'name' => 'Al doilea site',
            'domain' => 'example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertSame(2, Site::where('domain', 'example.com')->count());
    }

    public function test_the_same_user_cannot_register_one_domain_twice(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('dashboard.sites.store'), [
            'name' => 'Primul site',
            'domain' => 'example.com',
        ]);

        $response = $this->actingAs($user)->post(route('dashboard.sites.store'), [
            'name' => 'Acelasi domeniu',
            'domain' => 'example.com',
        ]);

        $response->assertSessionHasErrors('domain');
        $this->assertSame(1, Site::where('user_id', $user->id)->count());
    }

    public function test_every_site_gets_its_own_token(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();

        $this->actingAs($first)->post(route('dashboard.sites.store'), [
            'name' => 'Primul site',
            'domain' => 'example.com',
        ]);
        $this->actingAs($second)->post(route('dashboard.sites.store'), [
            'name' => 'Al doilea site',
            'domain' => 'example.com',
        ]);

        $tokens = Site::pluck('token');

        $this->assertCount(2, $tokens);
        $this->assertSame(2, $tokens->unique()->count(), 'Tokens must be unique per site.');
    }
}
