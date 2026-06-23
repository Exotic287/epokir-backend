<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminOrSetwan
{
    /**
     * Batasi akses hanya untuk user dengan role admin atau setwan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->user()?->role, ['admin', 'setwan'], true)) {
            return ApiResponse::error('Anda tidak memiliki akses untuk fitur ini.', 403);
        }

        return $next($request);
    }
}
