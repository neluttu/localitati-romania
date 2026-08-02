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

        // Store site in request for later use (logging, etc.)
        $request->attributes->set('site', $site);

        return $next($request);
    }
}
