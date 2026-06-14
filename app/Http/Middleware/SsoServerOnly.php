<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SsoServerOnly
{
    /**
     * Middleware untuk membatasi akses hanya dari server SSO Pusat.
     * Validasi menggunakan header X-SSO-Secret.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secret = $request->header('X-SSO-Secret');

        if (empty($secret) || $secret !== config('services.sso_pusat.exchange_secret')) {
            return response()->json([
                'success' => false,
                'message' => 'Akses tidak diizinkan.',
            ], 403);
        }

        // Opsional: validasi IP server SSO Pusat
        $allowedIps = config('services.sso_pusat.allowed_ips');
        if (!empty($allowedIps)) {
            $ips = array_filter(array_map('trim', explode(',', $allowedIps)));
            if (!empty($ips) && !in_array($request->ip(), $ips)) {
                return response()->json([
                    'success' => false,
                    'message' => 'IP tidak diizinkan.',
                ], 403);
            }
        }

        return $next($request);
    }
}
