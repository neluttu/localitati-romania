<?php
declare(strict_types=1);
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

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

        $duration = round((microtime(true) - $start) * 1000, 2);
        $status = $response->getStatusCode();

        // Mask IP (ex: 192.168.1.123 -> 192.168.1.0)
        $ip = $request->ip();
        $ipMasked = preg_replace('/\.\d+$/', '.0', $ip);

        $context = [
            'request_id' => $requestId,
            'ip' => $ipMasked,
            'method' => $request->method(),
            'path' => $request->path(),
            'status' => $status,
            'duration' => $duration . 'ms',
            'ua' => substr($request->userAgent() ?? '-', 0, 120),
        ];

        $logger = Log::channel('api');

        if ($status === 429) {
            $logger->notice('API throttled', $context);
        } elseif ($status >= 400) {
            $logger->warning('API error', $context);
        } else {
            $logger->info('API request', $context);
        }

        return $response;
    }



}
