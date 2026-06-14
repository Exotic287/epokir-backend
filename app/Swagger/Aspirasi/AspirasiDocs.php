<?php

namespace App\Swagger\Aspirasi;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Aspirasi', description: 'Endpoint untuk manajemen data aspirasi')]
class AspirasiDocs
{
    #[OA\Get(
        path: '/api/v1/aspirasi',
        summary: 'Ambil daftar aspirasi',
        description: 'Menampilkan daftar aspirasi dengan filter dan pagination',
        security: [['bearerAuth' => []]],
        tags: ['Aspirasi'],
        parameters: [
            new OA\Parameter(name: 'opd_id', in: 'query', required: false, description: 'Filter berdasarkan ID OPD', schema: new OA\Schema(type: 'integer', example: 1)),
            new OA\Parameter(name: 'dapil_id', in: 'query', required: false, description: 'Filter berdasarkan ID Dapil', schema: new OA\Schema(type: 'integer', example: 2)),
            new OA\Parameter(name: 'source', in: 'query', required: false, description: 'Filter berdasarkan sumber', schema: new OA\Schema(type: 'string', enum: ['reses', 'tatap_muka', 'surat', 'lainnya'])),
            new OA\Parameter(name: 'is_complete', in: 'query', required: false, description: 'Filter status selesai', schema: new OA\Schema(type: 'integer', enum: [0, 1])),
            new OA\Parameter(name: 'search', in: 'query', required: false, description: 'Kata kunci pencarian', schema: new OA\Schema(type: 'string', example: 'jalan rusak')),
            new OA\Parameter(name: 'tahun', in: 'query', required: false, description: 'Filter berdasarkan tahun aspirasi', schema: new OA\Schema(type: 'integer', example: 2026)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, description: 'Jumlah data per halaman', schema: new OA\Schema(type: 'integer', example: 10)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar aspirasi berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Daftar aspirasi berhasil dimuat.'),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'total', type: 'integer', example: 50),
                            new OA\Property(property: 'per_page', type: 'integer', example: 10),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Aspirasi')),
                        ]
                    ),
                ])
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/v1/aspirasi',
        summary: 'Buat aspirasi baru',
        security: [['bearerAuth' => []]],
        tags: ['Aspirasi'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreAspirasiRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Aspirasi berhasil dibuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Aspirasi berhasil dibuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Aspirasi'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/v1/aspirasi/{id}',
        summary: 'Ambil detail aspirasi',
        security: [['bearerAuth' => []]],
        tags: ['Aspirasi'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID aspirasi', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail aspirasi berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Detail aspirasi berhasil dimuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Aspirasi'),
                ])
            ),
            new OA\Response(response: 404, description: 'Tidak ditemukan', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/v1/aspirasi/{id}',
        summary: 'Perbarui aspirasi',
        security: [['bearerAuth' => []]],
        tags: ['Aspirasi'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID aspirasi', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateAspirasiRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Aspirasi berhasil diperbarui',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Aspirasi berhasil diperbarui.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Aspirasi'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 404, description: 'Tidak ditemukan', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/v1/aspirasi/{id}',
        summary: 'Hapus aspirasi',
        security: [['bearerAuth' => []]],
        tags: ['Aspirasi'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID aspirasi', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Aspirasi berhasil dihapus',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Aspirasi berhasil dihapus.'),
                    new OA\Property(property: 'data', nullable: true, example: null),
                ])
            ),
            new OA\Response(response: 404, description: 'Tidak ditemukan', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OA\Response(response: 401, description: 'Tidak terautentikasi', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function destroy(): void {}
}