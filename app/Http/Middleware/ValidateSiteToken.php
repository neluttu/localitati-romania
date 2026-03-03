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
     * Validates that the X-Site-Token matches the Origin/Referer domain.
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

        // Validate domain from Origin or Referer header
        $requestDomain = $this->extractDomain($request);

        if ($requestDomain && ! $this->domainMatches($site->domain, $requestDomain)) {
            return response()->json([
                'error' => 'Domain mismatch.',
                'message' => 'This API token is not authorized for use on this domain.',
            ], 403);
        }

        // Store site in request for later use (logging, etc.)
        $request->attributes->set('site', $site);

        return $next($request);
    }

    private function extractDomain(Request $request): ?string
    {
        // Try Origin header first (CORS requests)
        $origin = $request->header('Origin');
        if ($origin) {
            $parsed = parse_url($origin);

            return $parsed['host'] ?? null;
        }

        // Fallback to Referer header
        $referer = $request->header('Referer');
        if ($referer) {
            $parsed = parse_url($referer);

            return $parsed['host'] ?? null;
        }

        return null;
    }

    private function domainMatches(string $siteDomain, string $requestDomain): bool
    {
        // Normalize domains (lowercase, remove www.)
        $siteDomain = strtolower(preg_replace('/^www\./', '', $siteDomain));
        $requestDomain = strtolower(preg_replace('/^www\./', '', $requestDomain));

        // Handle explicit wildcard pattern (*.example.com)
        if (str_starts_with($siteDomain, '*.')) {
            $baseDomain = substr($siteDomain, 2); // Remove "*."

            // Match exact base domain or any subdomain
            return $requestDomain === $baseDomain
                || str_ends_with($requestDomain, '.'.$baseDomain);
        }

        // Exact match
        if ($siteDomain === $requestDomain) {
            return true;
        }

        // Allow subdomains (e.g., site registered as "example.com" allows "sub.example.com")
        if (str_ends_with($requestDomain, '.'.$siteDomain)) {
            return true;
        }

        // Allow localhost for development (if site domain contains localhost)
        if (str_contains($siteDomain, 'localhost') && str_contains($requestDomain, 'localhost')) {
            return true;
        }

        return false;
    }
}
