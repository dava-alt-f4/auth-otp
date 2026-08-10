<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('sanctum');

        if (! $user) {
            abort(401, 'Akses DitolakMJHDV.');
        }

        if (! $user || $user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        return $next($request);
    }
}
