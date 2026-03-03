<?php

namespace App\Console\Commands;

use App\Models\ApiLog;
use Illuminate\Console\Command;

class PruneApiLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-logs:prune {--days=90 : Number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete API logs older than the specified number of days (default: 90)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');

        $deleted = ApiLog::where('created_at', '<', now()->subDays($days))->delete();

        $this->info("Deleted {$deleted} API log entries older than {$days} days.");

        return Command::SUCCESS;
    }
}
