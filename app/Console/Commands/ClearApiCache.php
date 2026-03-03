<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\County;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearApiCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:cache-clear {--county= : Clear cache for a specific county (abbr)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear API cache for localities data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $countyAbbr = $this->option('county');

        if ($countyAbbr) {
            $county = County::where('abbr', strtoupper($countyAbbr))->first();

            if (! $county) {
                $this->error("County with abbreviation '{$countyAbbr}' not found.");

                return Command::FAILURE;
            }

            $this->clearCountyCache($county);
            $this->info("Cache cleared for county: {$county->name} ({$county->abbr})");

            return Command::SUCCESS;
        }

        // Clear all county caches
        $counties = County::all();
        $count = 0;

        foreach ($counties as $county) {
            $this->clearCountyCache($county);
            $count++;
        }

        $this->info("Cache cleared for {$count} counties.");

        return Command::SUCCESS;
    }

    private function clearCountyCache(County $county): void
    {
        $patterns = [
            "api:v1:county:{$county->abbr}:localities",
            "api:v1:county:{$county->abbr}:localities-lite",
            "api:v1:counties:{$county->abbr}:localities",
        ];

        foreach ($patterns as $key) {
            Cache::forget($key);
        }
    }
}
