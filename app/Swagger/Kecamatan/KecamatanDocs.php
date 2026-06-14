<?php

namespace App\Swagger\Kecamatan;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Kecamatan', description: 'Endpoint untuk manajemen data Kecamatan')]
class KecamatanDocs
{
    #[OA\Get(
        path: '/api/v1/kecamatans',
        summary: 'Ambil daftar kecamatan',
        description: 'Menampilkan seluruh kecamatan, opsional filter berdasarkan dapil.',
        security: [['bearerAuth' => []]],
        tags: ['Kecamatan'],
        parameters: [
            new OA\Parameter(name: 'dapil_id', in: 'query', required: false,
                description: 'Filter berdasarkan ID Dapil',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar Kecamatan berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Daftar Kecamatan berhasil dimuat.'),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Kecamatan')),
                ])
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/v1/kecamatans',
        summary: 'Buat kecamatan baru',
        security: [['bearerAuth' => []]],
        tags: ['Kecamatan'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreKecamatanRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Kecamatan berhasil dibuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Kecamatan berhasil dibuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Kecamatan'),
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
        path: '/api/v1/kecamatans/{id}',
        summary: 'Ambil detail kecamatan',
        security: [['bearerAuth' => []]],
        tags: ['Kecamatan'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID kecamatan',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail Kecamatan berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Detail Kecamatan berhasil dimuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Kecamatan'),
                ])
            ),
            new OA\Response(response: 404, description: 'Kecamatan tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/v1/kecamatans/{id}',
        summary: 'Perbarui kecamatan',
        security: [['bearerAuth' => []]],
        tags: ['Kecamatan'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID kecamatan',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateKecamatanRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Kecamatan berhasil diperbarui',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Kecamatan berhasil diperbarui.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Kecamatan'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 404, description: 'Kecamatan tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/v1/kecamatans/{id}',
        summary: 'Hapus kecamatan',
        security: [['bearerAuth' => []]],
        tags: ['Kecamatan'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID kecamatan',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Kecamatan berhasil dihapus',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Kecamatan berhasil dihapus.'),
                    new OA\Property(property: 'data', nullable: true, example: null),
                ])
            ),
            new OA\Response(response: 404, description: 'Kecamatan tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function destroy(): void {}
}