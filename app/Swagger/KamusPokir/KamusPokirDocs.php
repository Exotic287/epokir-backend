<?php

namespace App\Swagger\KamusPokir;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Kamus Pokir', description: 'Endpoint untuk manajemen data kamus pokir')]
class KamusPokirDocs
{
    #[OA\Get(
        path: '/api/v1/kamus-pokir',
        summary: 'Ambil daftar kamus pokir',
        security: [['bearerAuth' => []]],
        tags: ['Kamus Pokir'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar kamus pokir berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Daftar Kamus Pokir berhasil dimuat.'),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/KamusPokir')),
                ])
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/v1/kamus-pokir',
        summary: 'Buat kamus pokir baru',
        security: [['bearerAuth' => []]],
        tags: ['Kamus Pokir'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreKamusPokirRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Kamus pokir berhasil dibuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Kamus Pokir berhasil dibuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/KamusPokir'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/v1/kamus-pokir/{id}',
        summary: 'Ambil detail kamus pokir',
        security: [['bearerAuth' => []]],
        tags: ['Kamus Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID kamus pokir', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail kamus pokir berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Detail Kamus Pokir berhasil dimuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/KamusPokir'),
                ])
            ),
            new OA\Response(response: 404, description: 'Tidak ditemukan', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/v1/kamus-pokir/{id}',
        summary: 'Perbarui kamus pokir',
        security: [['bearerAuth' => []]],
        tags: ['Kamus Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID kamus pokir', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreKamusPokirRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Kamus pokir berhasil diperbarui',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Kamus Pokir berhasil diperbarui.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/KamusPokir'),
                ])
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/v1/kamus-pokir/{id}',
        summary: 'Hapus kamus pokir',
        security: [['bearerAuth' => []]],
        tags: ['Kamus Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID kamus pokir', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Kamus pokir berhasil dihapus',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Kamus Pokir berhasil dihapus.'),
                ])
            ),
        ]
    )]
    public function destroy(): void {}
}