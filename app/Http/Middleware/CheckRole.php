<?php
// TANGGUNG JAWAB: Farisha Huwaida Shofha (PBI-16)
// Middleware untuk validasi role pengguna sebelum mengakses halaman

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Contoh penggunaan di routes/web.php:
     *   Route::middleware(['auth', 'role:supervisor'])->group(...)
     *   Route::middleware(['auth', 'role:admin,supervisor'])->group(...)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
