<?php

namespace App\Services\Concerns;

use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait MakesAuthenticatedSsoRequests
{
    /**
     * HTTP client yang sudah disisipi Bearer sso_token milik admin yang sedang login.
     * Setiap request server-to-server ini bertindak "atas nama" admin tersebut.
     *
     * @throws \Exception
     */
    private function request(User $admin): PendingRequest
    {
        if (empty($admin->sso_token)) {
            throw new \Exception('Sesi SSO Anda telah berakhir. Silakan login ulang.', 401);
        }

        try {
            return Http::withToken($admin->sso_token)
                ->acceptJson()
                ->baseUrl(config('services.sso_pusat.url'))
                ->throw(function ($response, $e) {
                    if ($response->status() === 401 || $response->status() === 403) {
                        throw new \Exception('Sesi SSO Anda telah berakhir. Silakan login ulang.', 401);
                    }

                    if ($response->status() === 422) {
                        $firstError = collect($response->json('errors', []))->flatten()->first();
                        throw new \Exception(
                            $firstError ?? $response->json('message', 'Validasi gagal.'),
                            422
                        );
                    }

                    if ($response->status() === 404) {
                        throw new \Exception('Data tidak ditemukan di SSO Pusat.', 404);
                    }

                    throw new \Exception(
                        $response->json('message', 'Gagal menghubungi SSO Pusat.'),
                        502
                    );
                });
        } catch (ConnectionException) {
            throw new \Exception('Tidak dapat terhubung ke SSO Pusat. Silakan coba lagi.', 503);
        }
    }
}
