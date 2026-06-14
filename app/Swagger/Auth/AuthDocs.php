<?php

namespace App\Swagger\Auth;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth', description: 'Endpoint autentikasi via SSO')]
class AuthDocs
{
    #[OA\Get(
        path: '/api/v1/auth/sso/callback',
        summary: 'SSO Callback',
        description: 'Dipanggil oleh frontend setelah redirect dari SSO Pusat. Mengembalikan token Passport dan data user.',
        tags: ['Auth'],
        parameters: [
            new OA\Parameter(
                name: 'code',
                in: 'query',
                required: true,
                description: 'Authorization code dari SSO Pusat',
                schema: new OA\Schema(type: 'string', example: 'abc123xyz')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login berhasil',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Login berhasil.'),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1Qi...'),
                            new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                        ]
                    ),
                ])
            ),
            new OA\Response(
                response: 400,
                description: 'Code tidak valid atau SSO gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function ssoCallback(): void {}

    #[OA\Get(
        path: '/api/v1/auth/profile',
        summary: 'Ambil profil user',
        description: 'Mengembalikan data profil user yang sedang login beserta relasi dapil.',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profil berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Profil berhasil dimuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/UserWithRelations'),
                ])
            ),
            new OA\Response(
                response: 401,
                description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function profile(): void {}

    #[OA\Post(
        path: '/api/v1/auth/logout',
        summary: 'Logout',
        description: 'Mencabut token Passport lokal dan logout dari SSO Pusat.',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout berhasil',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Logout berhasil.'),
                    new OA\Property(property: 'data', nullable: true, example: null),
                ])
            ),
            new OA\Response(
                response: 401,
                description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function logout(): void {}
}