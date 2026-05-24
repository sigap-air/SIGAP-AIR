<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Flatten roles if they are passed as a single string with commas
        $parsedRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $parsedRoles = array_merge($parsedRoles, explode(',', $role));
            } else {
                $parsedRoles[] = $role;
            }
        }

        $parsedRoles = array_map(function ($role) {
            return strtolower(trim($role));
        }, $parsedRoles);

        $userRole = $request->user() ? strtolower(trim($request->user()->role)) : null;

        if (!$userRole || !in_array($userRole, $parsedRoles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
