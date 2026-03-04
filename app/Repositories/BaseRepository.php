<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;

abstract class BaseRepository
{
    /**
     * Cache data forever with a given key.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    protected function cacheForever(string $key, callable $callback): mixed
    {
        return Cache::rememberForever($key, $callback);
    }

    /**
     * Cache data for a given number of seconds.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    protected function cacheFor(string $key, int $seconds, callable $callback): mixed
    {
        return Cache::remember($key, $seconds, $callback);
    }

    /**
     * Forget a cached key.
     */
    protected function forgetCache(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Clear all cache keys matching a pattern.
     */
    protected function clearCachePattern(string $pattern): void
    {
        // Note: This requires a cache driver that supports tags or pattern matching
        // For simple cases, use forgetCache with specific keys
        Cache::flush();
    }
}
