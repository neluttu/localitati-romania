<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ApiLog;
use App\Models\Site;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeDeletedAccounts extends Command
{
    protected $signature = 'accounts:purge';

    protected $description = 'Permanently remove accounts soft-deleted longer than the retention window';

    /**
     * How long a deleted account can still be restored. Deleting is meant to
     * be final, so this window is short - long enough to undo a misclick, not
     * long enough to be storage of data someone asked us to erase.
     */
    private const RETENTION_DAYS = 30;

    public function handle(): int
    {
        $cutoff = now()->subDays(self::RETENTION_DAYS);

        $users = User::onlyTrashed()
            ->where('deleted_at', '<=', $cutoff)
            ->get();

        foreach ($users as $user) {
            DB::transaction(function () use ($user): void {
                $siteIds = Site::withTrashed()
                    ->where('user_id', $user->id)
                    ->pluck('id');

                if ($siteIds->isNotEmpty()) {
                    // Sever the traffic history from its owner before the sites
                    // go. The rows stay so past usage totals remain correct;
                    // what leaves is everything pointing back at a person.
                    ApiLog::whereIn('site_id', $siteIds)->update([
                        'site_id' => null,
                        'user_agent' => '-',
                    ]);

                    Site::withTrashed()->whereIn('id', $siteIds)->forceDelete();
                }

                $user->forceDelete();
            });
        }

        $this->info($users->isEmpty()
            ? 'Niciun cont de purjat.'
            : "Conturi purjate definitiv: {$users->count()}.");

        return self::SUCCESS;
    }
}
