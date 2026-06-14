<?php

namespace App\Swagger\Dapil;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Dapil', description: 'Endpoint untuk manajemen data Daerah Pemilihan')]
class DapilDocs
{
    #[OA\Get(
        path: '/api/v1/dapils',
        summary: 'Ambil daftar dapil',
        description: 'Secara default hanya menampilkan dapil yang aktif.',
        security: [['bearerAuth' => []]],
        tags: ['Dapil'],
        parameters: [
            new OA\Parameter(name: 'active_only', in: 'query', required: false, description: 'Filter hanya dapil aktif (default: true)', schema: new OA\Schema(type: 'boolean', example: true)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar Dapil berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Daftar Dapil berhasil dimuat.'),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Dapil')),
                ])
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/v1/dapils',
        summary: 'Buat dapil baru',
        security: [['bearerAuth' => []]],
        tags: ['Dapil'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreDapilRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Dapil berhasil dibuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Dapil berhasil dibuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Dapil'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/v1/dapils/{id}',
        summary: 'Ambil detail dapil',
        security: [['bearerAuth' => []]],
        tags: ['Dapil'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID dapil', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail Dapil berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Detail Dapil berhasil dimuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Dapil'),
                ])
            ),
            new OA\Response(response: 404, description: 'Tidak ditemukan', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/v1/dapils/{id}',
        summary: 'Perbarui dapil',
        security: [['bearerAuth' => []]],
        tags: ['Dapil'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID dapil', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateDapilRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dapil berhasil diperbarui',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Dapil berhasil diperbarui.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Dapil'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 404, description: 'Tidak ditemukan', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/v1/dapils/{id}',
        summary: 'Hapus dapil',
        security: [['bearerAuth' => []]],
        tags: ['Dapil'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID dapil', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dapil berhasil dihapus',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Dapil berhasil dihapus.'),
                    new OA\Property(property: 'data', nullable: true, example: null),
                ])
            ),
            new OA\Response(response: 404, description: 'Tidak ditemukan', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function destroy(): void {}
}