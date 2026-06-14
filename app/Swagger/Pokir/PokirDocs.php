<?php

namespace App\Swagger\Pokir;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Pokir', description: 'Endpoint untuk manajemen Pokok-Pokok Pikiran DPRD')]
class PokirDocs
{
    // ─── CRUD ─────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/v1/pokir',
        summary: 'Ambil daftar pokir',
        description: 'Menampilkan daftar pokir dengan filter dan pagination.',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'status', in: 'query', required: false,
                description: 'Filter berdasarkan status',
                schema: new OA\Schema(type: 'string', enum: ['draft', 'submitted', 'verified', 'finalized', 'cancelled'])
            ),
            new OA\Parameter(name: 'opd_id', in: 'query', required: false,
                description: 'Filter berdasarkan ID OPD',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(name: 'dapil_id', in: 'query', required: false,
                description: 'Filter berdasarkan ID Dapil',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(name: 'search', in: 'query', required: false,
                description: 'Kata kunci pencarian',
                schema: new OA\Schema(type: 'string', example: 'jalan')
            ),
            new OA\Parameter(name: 'per_page', in: 'query', required: false,
                description: 'Jumlah data per halaman',
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar Pokir berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Daftar Pokir berhasil dimuat.'),
                    new OA\Property(
                        property: 'data', type: 'object',
                        properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'total', type: 'integer', example: 20),
                            new OA\Property(property: 'per_page', type: 'integer', example: 10),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Pokir')),
                        ]
                    ),
                ])
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/v1/pokir',
        summary: 'Buat pokir baru',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StorePokirRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Pokir berhasil dibuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Pokir berhasil dibuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
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
        path: '/api/v1/pokir/{id}',
        summary: 'Ambil detail pokir',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail Pokir berhasil dimuat',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Detail Pokir berhasil dimuat.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
                ])
            ),
            new OA\Response(response: 404, description: 'Pokir tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Put(
        path: '/api/v1/pokir/{id}',
        summary: 'Perbarui pokir',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdatePokirRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pokir berhasil diperbarui',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Pokir berhasil diperbarui.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 404, description: 'Pokir tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/v1/pokir/{id}',
        summary: 'Hapus pokir',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pokir berhasil dihapus',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Pokir berhasil dihapus.'),
                    new OA\Property(property: 'data', nullable: true, example: null),
                ])
            ),
            new OA\Response(response: 404, description: 'Pokir tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function destroy(): void {}

    // ─── Workflow Actions ─────────────────────────────────────────────────────

    #[OA\Post(
        path: '/api/v1/pokir/{id}/submit',
        summary: 'Submit pokir',
        description: 'Mengubah status pokir dari draft menjadi submitted.',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pokir berhasil disubmit',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Pokir berhasil disubmit.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
                ])
            ),
            new OA\Response(response: 400, description: 'Status tidak valid untuk submit',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 404, description: 'Pokir tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function submit(): void {}

    #[OA\Post(
        path: '/api/v1/pokir/{id}/verify',
        summary: 'Verifikasi pokir',
        description: 'Mengubah status pokir dari submitted menjadi verified. Hanya bisa dilakukan oleh setwan.',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pokir berhasil diverifikasi',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Pokir berhasil diverifikasi.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
                ])
            ),
            new OA\Response(response: 400, description: 'Status tidak valid untuk verifikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 403, description: 'Tidak memiliki akses',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function verify(): void {}

    #[OA\Post(
        path: '/api/v1/pokir/{id}/request-revision',
        summary: 'Minta revisi pokir',
        description: 'Mengirimkan flag revisi pada field tertentu. Hanya bisa dilakukan oleh setwan.',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RequestRevisionRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Permintaan revisi berhasil dikirim',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Permintaan revisi berhasil dikirim.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 403, description: 'Tidak memiliki akses',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function requestRevision(): void {}

    #[OA\Post(
        path: '/api/v1/pokir/{id}/finalize',
        summary: 'Finalisasi pokir',
        description: 'Mengubah status pokir dari verified menjadi finalized. Hanya bisa dilakukan oleh admin.',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pokir berhasil difinalisasi',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Pokir berhasil difinalisasi.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
                ])
            ),
            new OA\Response(response: 400, description: 'Status tidak valid untuk finalisasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 403, description: 'Tidak memiliki akses',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function finalize(): void {}

    // ─── Aspirasi Management ──────────────────────────────────────────────────

    #[OA\Post(
        path: '/api/v1/pokir/{id}/aspirasi',
        summary: 'Tambah aspirasi ke pokir',
        description: 'Menambahkan aspirasi ke dalam pokir beserta posisinya.',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/AddAspirasiRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Aspirasi berhasil ditambahkan ke Pokir',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Aspirasi berhasil ditambahkan ke Pokir.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validasi gagal',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 404, description: 'Pokir tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function addAspirasi(): void {}

    #[OA\Delete(
        path: '/api/v1/pokir/{id}/aspirasi/{aspirasiId}',
        summary: 'Lepas aspirasi dari pokir',
        description: 'Menghapus relasi aspirasi dari pokir.',
        security: [['bearerAuth' => []]],
        tags: ['Pokir'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true,
                description: 'ID pokir',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(name: 'aspirasiId', in: 'path', required: true,
                description: 'ID aspirasi',
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Aspirasi berhasil dilepas dari Pokir',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Aspirasi berhasil dilepas dari Pokir.'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Pokir'),
                ])
            ),
            new OA\Response(response: 404, description: 'Pokir atau aspirasi tidak ditemukan',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 401, description: 'Tidak terautentikasi',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
        ]
    )]
    public function removeAspirasi(): void {}
}