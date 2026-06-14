<?php
namespace App\Services;

use App\Models\User;
use Laravel\Passport\Token;

class AuthService
{
    public function __construct(
        private SsoPusatService $ssoPusatService
    ) {}

    /**
     * Handle SSO callback: tukar code → upsert user → buat token Passport lokal.
     *
     * @param  string $code
     * @return array{token: string, user: User}
     * @throws \Exception
     */
    public function handleSsoCallback(string $code): array
    {
        // 1. Exchange code ke SSO Pusat
        $ssoData  = $this->ssoPusatService->exchangeCode($code);
        $ssoToken = $ssoData['sso_token'];
        $ssoUser  = $ssoData['user'];

        // 2. Validasi data dari SSO Pusat
        if (empty($ssoUser['id']) || empty($ssoUser['email'])) {
            throw new \Exception('Data user dari SSO Pusat tidak lengkap.', 502);
        }

        // 3. Upsert user berdasarkan sso_id
        $existingUser = User::withTrashed()->where('sso_id', $ssoUser['id'])->first();

        if ($existingUser?->trashed()) {
            throw new \Exception('Akun Anda telah dinonaktifkan. Hubungi administrator.', 403);
        }

        // $userData = [
        //     'name'      => $ssoUser['name'] ?? 'Pengguna',
        //     'email'     => $ssoUser['email'],
        //     'avatar'    => $ssoUser['avatar'] ?? null,
        //     'role'      => $ssoUser['role'],
        //     'dapil_id'  => $ssoUser['dapil_id'] ?? null,
        //     'sso_token' => $ssoToken,
        // ];

        $userData = [
            'name'        => $ssoUser['name'] ?? 'Pengguna',
            'email'       => $ssoUser['email'],
            'avatar'      => $ssoUser['avatar'] ?? null,
            'role'        => $ssoUser['role'] ?? 'dewan',
            'dapil_id'    => $ssoUser['dapil_id'] ?? null,
            'dapil_nama'  => $ssoUser['dapil']['nama'] ?? null,
            'fraksi_id'   => $ssoUser['fraksi_id'] ?? null,
            'fraksi_nama' => $ssoUser['fraksi']['nama'] ?? null,
            'sso_token'   => $ssoToken,
        ];

        if ($existingUser) {
            // Cek is_active SEBELUM update, bukan setelah
            if (! $existingUser->is_active) {
                throw new \Exception('Akun Anda tidak aktif. Hubungi administrator.', 403);
            }
            $existingUser->update($userData);
            $user = $existingUser;
        } else {
            $user = User::create(array_merge($userData, [
                'sso_id'    => $ssoUser['id'],
                'is_active' => true,
            ]));
        }

        // 4. Revoke semua token lama (cara Laravel 13)
        $user->tokens()->each(function (Token $token) {
            $token->revoke();
            $token->refreshToken?->revoke();
        });

        // 5. Buat token Passport lokal (expire 8 jam)
        $localToken = $user->createToken('epokir-access')->accessToken;

        return [
            'token' => $localToken,
            'user'  => $user,
        ];
    }

    /**
     * Logout: revoke semua token Passport lokal + logout remote SSO Pusat.
     *
     * @param  User $user
     * @return void
     */
    public function logout(User $user): void
    {
        // Simpan sso_token sebelum dihapus
        $ssoToken = $user->sso_token;

        // Revoke semua token Passport lokal (cara Laravel 13)
        $user->tokens()->each(function (Token $token) {
            $token->revoke();
            $token->refreshToken?->revoke();
        });

        // Clear sso_token lokal
        $user->update(['sso_token' => null]);

        // Logout remote ke SSO Pusat (silent fail)
        if ($ssoToken) {
            $this->ssoPusatService->logout($ssoToken);
        }
    }
}
