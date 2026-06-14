<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SsoPusatService
{
    private string $baseUrl;
    private string $secret;

    public function __construct()
    {
        $this->baseUrl = config('services.sso_pusat.url');
        $this->secret  = config('services.sso_pusat.exchange_secret');
    }

    /**
     * Tukar short-lived code dengan sso_token dan data user dari SSO Pusat.
     * Dilakukan server-to-server dengan X-SSO-Secret header.
     *
     * @param  string $code
     * @return array{sso_token: string, user: array}
     * @throws \Exception
     */
    public function exchangeCode(string $code): array
    {
        try {
            $response = Http::withHeaders([
                'X-SSO-Secret' => $this->secret,
                'Accept'       => 'application/json',
            ])->post("{$this->baseUrl}/v1/auth/sso/exchange", [
                'code' => $code,
                'app'  => 'epokir',
            ]);
        } catch (ConnectionException $e) {
            throw new \Exception('Tidak dapat terhubung ke SSO Pusat. Silakan coba lagi.', 503);
        }

        if ($response->status() === 401 || $response->status() === 403) {
            throw new \Exception('Konfigurasi SSO tidak valid.', 500);
        }

        if ($response->status() === 422 || $response->status() === 400) {
            $message = $response->json('message', 'Kode SSO tidak valid atau sudah kedaluwarsa.');
            throw new \Exception($message, 400);
        }

        if (!$response->successful()) {
            throw new \Exception('Gagal melakukan verifikasi SSO. Silakan coba lagi.', 502);
        }

        $data = $response->json('data');

        if (empty($data['sso_token']) || empty($data['user'])) {
            throw new \Exception('Respons SSO Pusat tidak valid.', 502);
        }

        return [
            'sso_token' => $data['sso_token'],
            'user'      => $data['user'],
        ];
    }

    /**
     * Logout dari SSO Pusat (invalidate sso_token di server pusat).
     *
     * @param  string $ssoToken
     * @return void
     */
    public function logout(string $ssoToken): void
    {
        try {
            Http::withHeaders([
                'Authorization' => "Bearer {$ssoToken}",
                'Accept'        => 'application/json',
            ])->post("{$this->baseUrl}/v1/auth/logout");
        } catch (\Throwable) {
            // Silent fail — logout lokal tetap berjalan
        }
    }
}
