<?php

namespace Tests\Feature\Dashboard;

use App\Models\ApiLog;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurgeDeletedAccountsTest extends TestCase
{
    use RefreshDatabase;

    private function deletedUser(int $daysAgo): User
    {
        $user = User::factory()->create();
        $user->delete();
        $user->forceFill(['deleted_at' => now()->subDays($daysAgo)])->saveQuietly();

        return $user;
    }

    public function test_it_removes_accounts_deleted_more_than_thirty_days_ago(): void
    {
        $user = $this->deletedUser(31);

        $this->artisan('accounts:purge')->assertSuccessful();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * The 30 days are the window in which someone can still ask for their
     * account back, so a purge that fires early defeats the whole point.
     */
    public function test_it_keeps_accounts_deleted_within_the_window(): void
    {
        $user = $this->deletedUser(29);

        $this->artisan('accounts:purge')->assertSuccessful();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_it_never_touches_live_accounts(): void
    {
        $user = User::factory()->create();

        $this->artisan('accounts:purge')->assertSuccessful();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_it_removes_the_sites_of_a_purged_account(): void
    {
        $user = $this->deletedUser(31);
        $site = Site::factory()->create(['user_id' => $user->id, 'domain' => 'example.com']);
        $site->delete();

        $this->artisan('accounts:purge')->assertSuccessful();

        $this->assertDatabaseMissing('sites', ['id' => $site->id]);
    }

    /**
     * Usage totals for past months must stay correct after someone leaves, so
     * the log rows outlive the account with the link to it severed.
     */
    public function test_it_keeps_the_api_logs_but_detaches_them_from_the_site(): void
    {
        $user = $this->deletedUser(31);
        $site = Site::factory()->create(['user_id' => $user->id, 'domain' => 'example.com']);
        $log = ApiLog::create([
            'site_id' => $site->id,
            'endpoint' => '/v1/counties',
            'method' => 'GET',
            'status_code' => 200,
            'ip' => '192.168.1.0',
            'user_agent' => 'Test/1.0',
            'response_time_ms' => 42,
        ]);
        $site->delete();

        $this->artisan('accounts:purge')->assertSuccessful();

        $this->assertDatabaseHas('api_logs', ['id' => $log->id, 'site_id' => null]);
    }

    public function test_it_strips_the_user_agent_from_detached_logs(): void
    {
        $user = $this->deletedUser(31);
        $site = Site::factory()->create(['user_id' => $user->id, 'domain' => 'example.com']);
        $log = ApiLog::create([
            'site_id' => $site->id,
            'endpoint' => '/v1/counties',
            'method' => 'GET',
            'status_code' => 200,
            'ip' => '192.168.1.0',
            'user_agent' => 'Mozilla/5.0 (identifiabil)',
            'response_time_ms' => 42,
        ]);
        $site->delete();

        $this->artisan('accounts:purge')->assertSuccessful();

        $this->assertDatabaseHas('api_logs', ['id' => $log->id, 'user_agent' => '-']);
    }
}
