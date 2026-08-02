<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSiteToken
{
    /**
     * Handle an incoming request.
     * Validates that the X-Site-Token is valid and active.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $siteToken = $request->header('X-Site-Token');

        if (! $siteToken) {
            return response()->json([
                'error' => 'Missing X-Site-Token header.',
                'message' => 'You must provide a valid site token in the X-Site-Token header.',
            ], 401);
        }

        $site = Site::where('token', $siteToken)
            ->where('is_active', true)
            ->first();

        if (! $site) {
            return response()->json([
                'error' => 'Invalid or inactive site token.',
                'message' => 'The provided site token is invalid or the site has been deactivated.',
            ], 401);
        }

        $host = $this->originHost($request);

        if ($host !== null && ! $this->hostMatchesDomain($host, $site->domain)) {
            return response()->json([
                'error' => 'Domain mismatch.',
                'message' => 'This site token is not authorised for the requesting domain.',
            ], 403);
        }

        // Store site in request for later use (logging, etc.)
        $request->attributes->set('site', $site);

        return $next($request);
    }

    /**
     * The host the call claims to come from, or null when the client sends
     * neither header. Server-to-server callers (cURL, backend jobs) have no
     * Origin to send, so a missing host is deliberately not a mismatch - the
     * token alone authorises them.
     */
    private function originHost(Request $request): ?string
    {
        $source = $request->header('Origin') ?: $request->header('Referer');

        if (! $source) {
            return null;
        }

        return parse_url($source, PHP_URL_HOST) ?: null;
    }

    /**
     * A registered domain also covers its subdomains, so "example.com" accepts
     * "sub.example.com". The dot in the suffix check is what keeps
     * "notexample.com" from passing as a subdomain of "example.com".
     */
    private function hostMatchesDomain(string $host, string $domain): bool
    {
        $host = strtolower($host);
        $domain = strtolower($domain);

        if (str_starts_with($domain, '*.')) {
            $domain = substr($domain, 2);
        }

        return $host === $domain || str_ends_with($host, '.'.$domain);
    }
}
