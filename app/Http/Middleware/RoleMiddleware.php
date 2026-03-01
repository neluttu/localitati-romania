<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user()) {
            abort(403);
        }

        // rolul userului (string din enum)
        $userRole = $request->user()->role->value;

        // verificare dacă rolul userului e în lista de roluri permise
        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
