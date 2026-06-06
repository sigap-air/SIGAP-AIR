<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Quick database connectivity check using socket timeout
        $dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $dbPort = config('database.connections.mysql.port', 3306);
        $dbAvailable = @fsockopen($dbHost, $dbPort, $errno, $errstr, 1);
        if ($dbAvailable) {
            fclose($dbAvailable);
        } else {
            // Database not available, skip auth check
            return $next($request);
        }

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            try {
                if (Auth::guard($guard)->check()) {
                    $user = Auth::guard($guard)->user();
                    $dashboardPath = $user instanceof User
                        ? $user->dashboardPath()
                        : RouteServiceProvider::HOME;

                    return redirect($dashboardPath);
                }
            } catch (\Exception $e) {
                // Fallback jika database error
                \Log::warning('Auth check failed: ' . $e->getMessage());
                continue;
            }
        }

        return $next($request);
    }
}
