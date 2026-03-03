<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ApiLog;
use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiAccessLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $requestId = bin2hex(random_bytes(6));

        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        $duration = (int) round((microtime(true) - $start) * 1000);
        $status = $response->getStatusCode();

        // Mask IP (ex: 192.168.1.123 -> 192.168.1.0)
        $ip = $request->ip();
        $ipMasked = preg_replace('/\.\d+$/', '.0', $ip);

        // Get site from request attributes (set by ValidateSiteToken middleware)
        /** @var Site|null $site */
        $site = $request->attributes->get('site');

        // Log to database if site is identified
        if ($site) {
            ApiLog::create([
                'site_id' => $site->id,
                'endpoint' => '/'.$request->path(),
                'method' => $request->method(),
                'status_code' => $status,
                'ip' => $ipMasked,
                'user_agent' => substr($request->userAgent() ?? '-', 0, 255),
                'response_time_ms' => $duration,
            ]);
        }

        // Also log to file (skip if disabled via env)
        if (config('logging.api_enabled', true)) {
            $context = [
                'request_id' => $requestId,
                'ip' => $ipMasked,
                'method' => $request->method(),
                'path' => $request->path(),
                'status' => $status,
                'duration' => $duration.'ms',
                'ua' => substr($request->userAgent() ?? '-', 0, 120),
                'site_id' => $site?->id,
            ];

            $logger = Log::channel('api');

            if ($status === 429) {
                $logger->notice('API throttled', $context);
            } elseif ($status >= 400) {
                $logger->warning('API error', $context);
            } else {
                $logger->info('API request', $context);
            }
        }

        return $response;
    }
}
