<?php

namespace App\Swagger\Desa;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Desa', description: 'Endpoint untuk manajemen data Desa')]
class DesaDocs
{
    #[OA\Get(
        path: '/api/v1/desas',
        summary: 'Ambil daftar desa',
        description: 'Menampilkan seluruh desa, opsional filter berdasarkan kecamatan atau dapil.',
        security: [['bearerAuth' => []]],
        tags: ['Desa'],
        parameters: [
            new OA\Parameter(name: 'kecamatan_id', in: 'query', required: false,
                description: 'Filter berdasarkan ID Kecamatan',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(name: 'dapil_id', in: 'query', required: false,
                description: 'Filter berdasarkan ID Dapil',
                schema: new OA\Schema(type: 'integer', example: 2)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar Desa berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Daftar Desa berhasil dimuat.'),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Desa')),
                ])
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/v1/desas',
        summary: 'Buat desa baru',
        security: [['bearerAuth' => []]],
        tags: ['Desa'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreDesaRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Desa berhasil dibuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Desa berhasil dibuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Desa'),
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
        path: '/api/v1/desas/{id}',
        summary: 'Ambil detail desa',
        security: [['bearerAuth' => []]],
        tags: ['Desa'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID desa',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail Desa berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Detail Desa berhasil dimuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Desa'),
                ])
            ),
            new OA\Response(response: 404, description: 'Desa tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/v1/desas/{id}',
        summary: 'Perbarui desa',
        security: [['bearerAuth' => []]],
        tags: ['Desa'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID desa',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateDesaRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Desa berhasil diperbarui',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Desa berhasil diperbarui.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Desa'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 404, description: 'Desa tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/v1/desas/{id}',
        summary: 'Hapus desa',
        security: [['bearerAuth' => []]],
        tags: ['Desa'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID desa',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Desa berhasil dihapus',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Desa berhasil dihapus.'),
                    new OA\Property(property: 'data', nullable: true, example: null),
                ])
            ),
            new OA\Response(response: 404, description: 'Desa tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function destroy(): void {}
}