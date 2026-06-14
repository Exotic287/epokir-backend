<?php

namespace App\Swagger\Pokir;

use OpenApi\Attributes as OA;

class Schemas
{
    // Model

    #[OA\Schema(
        schema: 'Pokir',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'user_id', type: 'integer', example: 1),
            new OA\Property(property: 'number', type: 'string', example: 'PKR-2026-0001'),
            new OA\Property(property: 'title', type: 'string', example: 'Pembangunan Jalan Desa'),
            new OA\Property(property: 'kamus_pokir_id', type: 'integer', nullable: true, example: 1),
            new OA\Property(property: 'opd_id', type: 'integer', nullable: true, example: 2),
            new OA\Property(property: 'dapil_id', type: 'integer', nullable: true, example: 1),
            new OA\Property(property: 'status', type: 'string', enum: ['draft', 'submitted', 'verified', 'finalized', 'cancelled'], example: 'draft'),
            new OA\Property(property: 'submitted_by', type: 'integer', nullable: true, example: 1),
            new OA\Property(property: 'verified_by', type: 'integer', nullable: true, example: 2),
            new OA\Property(property: 'finalized_by', type: 'integer', nullable: true, example: 3),
            new OA\Property(property: 'kecamatan_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer'), example: [1, 2]),
            new OA\Property(property: 'desa_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
            new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Catatan tambahan'),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'deleted_at', type: 'string', format: 'date-time', nullable: true, example: null),
        ]
    )]
    public function pokirSchema() {}

    #[OA\Schema(
        schema: 'PokirActivity',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'pokir_id', type: 'integer', example: 1),
            new OA\Property(property: 'user_id', type: 'integer', example: 1),
            new OA\Property(property: 'action', type: 'string', example: 'submitted'),
            new OA\Property(property: 'changes', type: 'object', nullable: true, example: ['status' => ['old' => 'draft', 'new' => 'submitted']]),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function pokirActivitySchema() {}

    #[OA\Schema(
        schema: 'PokirAspirasi',
        type: 'object',
        properties: [
            new OA\Property(property: 'pokir_id', type: 'integer', example: 1),
            new OA\Property(property: 'aspirasi_id', type: 'integer', example: 1),
            new OA\Property(property: 'position', type: 'integer', example: 1),
            new OA\Property(property: 'added_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function pokirAspirasiSchema() {}

    #[OA\Schema(
        schema: 'PokirAttachment',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'pokir_id', type: 'integer', example: 1),
            new OA\Property(property: 'file_name', type: 'string', example: 'dokumen.pdf'),
            new OA\Property(property: 'file_path', type: 'string', example: 'attachments/dokumen.pdf'),
            new OA\Property(property: 'type', type: 'string', example: 'document'),
            new OA\Property(property: 'file_type', type: 'string', example: 'application/pdf'),
            new OA\Property(property: 'file_size', type: 'integer', example: 204800),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function pokirAttachmentSchema() {}

        #[OA\Schema(
        schema: 'PokirRevision',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'pokir_id', type: 'integer', example: 1),
            new OA\Property(property: 'pokir_activity_id', type: 'integer', example: 1),
            new OA\Property(property: 'field_name', type: 'string', example: 'title'),
            new OA\Property(property: 'old_value', type: 'string', nullable: true, example: 'Judul Lama'),
            new OA\Property(property: 'new_value', type: 'string', nullable: true, example: 'Judul Baru'),
            new OA\Property(property: 'changed_by', type: 'integer', example: 1),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function pokirRevisionSchema() {}

    #[OA\Schema(
        schema: 'PokirRevisionFlagged',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'pokir_id', type: 'integer', example: 1),
            new OA\Property(property: 'field_name', type: 'string', example: 'title'),
            new OA\Property(property: 'note', type: 'string', nullable: true, example: 'Judul kurang spesifik'),
            new OA\Property(property: 'is_resolved', type: 'boolean', example: false),
            new OA\Property(property: 'flagged_by', type: 'integer', example: 2),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function pokirRevisionFlaggedSchema() {}

    // Request

    #[OA\Schema(
        schema: 'AddAspirasiRequest',
        type: 'object',
        required: ['aspirasi_id'],
        properties: [
            new OA\Property(property: 'aspirasi_id', type: 'integer', example: 5),
            new OA\Property(property: 'position', type: 'integer', nullable: true, example: 0),
        ]
    )]
    public function addAspirasiRequestSchema() {}

    #[OA\Schema(
        schema: 'RequestRevisionRequest',
        type: 'object',
        required: ['flags'],
        properties: [
            new OA\Property(
                property: 'flags',
                type: 'array',
                minItems: 1,
                items: new OA\Items(
                    type: 'object',
                    required: ['field_name'],
                    properties: [
                        new OA\Property(property: 'field_name', type: 'string', example: 'title'),
                        new OA\Property(property: 'note', type: 'string', nullable: true, example: 'Judul kurang spesifik'),
                    ]
                )
            ),
        ]
    )]
    public function requestRevisionRequestSchema() {}

    #[OA\Schema(
        schema: 'StorePokirRequest',
        type: 'object',
        required: ['title'],
        properties: [
            new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Pembangunan Jalan Desa'),
            new OA\Property(property: 'kamus_pokir_id', type: 'integer', nullable: true, example: 1),
            new OA\Property(property: 'opd_id', type: 'integer', nullable: true, example: 2),
            new OA\Property(property: 'dapil_id', type: 'integer', nullable: true, example: 1),
            new OA\Property(property: 'kecamatan_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer'), example: [1, 2]),
            new OA\Property(property: 'desa_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
            new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Catatan tambahan'),
        ]
    )]
    public function storePokirRequestSchema() {}

    #[OA\Schema(
        schema: 'UpdatePokirRequest',
        type: 'object',
        properties: [
            new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Pembangunan Jalan Desa Diperbarui'),
            new OA\Property(property: 'kamus_pokir_id', type: 'integer', nullable: true, example: 1),
            new OA\Property(property: 'opd_id', type: 'integer', nullable: true, example: 2),
            new OA\Property(property: 'dapil_id', type: 'integer', nullable: true, example: 1),
            new OA\Property(property: 'kecamatan_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer'), example: [1, 2]),
            new OA\Property(property: 'desa_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
            new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Catatan diperbarui'),
        ]
    )]
    public function updatePokirRequestSchema() {}
}