<?php

namespace App\Swagger\Opd;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'OPD', description: 'Endpoint untuk manajemen data Organisasi Perangkat Daerah')]
class OpdDocs
{
    #[OA\Get(
        path: '/api/v1/opds',
        summary: 'Ambil daftar OPD',
        description: 'Menampilkan seluruh OPD. Secara default hanya menampilkan OPD yang aktif.',
        security: [['bearerAuth' => []]],
        tags: ['OPD'],
        parameters: [
            new OA\Parameter(name: 'active_only', in: 'query', required: false,
                description: 'Filter hanya OPD aktif (default: true)',
                schema: new OA\Schema(type: 'boolean', example: true)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar OPD berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Daftar OPD berhasil dimuat.'),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Opd')),
                ])
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/v1/opds',
        summary: 'Buat OPD baru',
        security: [['bearerAuth' => []]],
        tags: ['OPD'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreOpdRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'OPD berhasil dibuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'OPD berhasil dibuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Opd'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/v1/opds/{id}',
        summary: 'Ambil detail OPD',
        security: [['bearerAuth' => []]],
        tags: ['OPD'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID OPD',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail OPD berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Detail OPD berhasil dimuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Opd'),
                ])
            ),
            new OA\Response(response: 404, description: 'OPD tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/v1/opds/{id}',
        summary: 'Perbarui OPD',
        security: [['bearerAuth' => []]],
        tags: ['OPD'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID OPD',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateOpdRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OPD berhasil diperbarui',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'OPD berhasil diperbarui.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Opd'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 404, description: 'OPD tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/v1/opds/{id}',
        summary: 'Hapus OPD',
        security: [['bearerAuth' => []]],
        tags: ['OPD'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID OPD',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OPD berhasil dihapus',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'OPD berhasil dihapus.'),
                    new OA\Property(property: 'data', nullable: true, example: null),
                ])
            ),
            new OA\Response(response: 404, description: 'OPD tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function destroy(): void {}
}