<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SsoCallbackRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * GET /api/v1/auth/sso/callback?code={code}
     * Dipanggil oleh frontend E-Pokir setelah redirect dari SSO Pusat.
     */
    public function ssoCallback(SsoCallbackRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->handleSsoCallback($request->validated()['code']);

            return ApiResponse::success([
                'token' => $result['token'],
                'user' => $result['user'],
            ], 'Login berhasil.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * GET /api/v1/auth/profile
     * Kembalikan profil user yang sedang login.
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            // dapil_id/dapil_nama sudah kolom langsung di User (diisi saat SSO login) —
            // jangan load relasi dapil() di sini, nanti key "dapil" di JSON ketiban objek
            // Dapil penuh dan bentrok dengan field User.dapil (string, khusus mock mode).
            return ApiResponse::success($request->user(), 'Profil berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * POST /api/v1/auth/logout
     * Revoke token Passport lokal + logout dari SSO Pusat.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());

            return ApiResponse::success(null, 'Logout berhasil.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
