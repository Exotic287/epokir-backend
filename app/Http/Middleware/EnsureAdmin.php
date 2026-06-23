<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Batasi akses hanya untuk user dengan role admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'admin') {
            return ApiResponse::error('Anda tidak memiliki akses untuk fitur ini.', 403);
        }

        return $next($request);
    }
}
