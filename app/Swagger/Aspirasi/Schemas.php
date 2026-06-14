<?php

namespace App\Swagger\Aspirasi;

use OpenApi\Attributes as OA;

class Schemas
{
    #[OA\Schema(
        schema: 'Aspirasi',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'user_id', type: 'integer', example: 1),
            new OA\Property(property: 'code', type: 'string', example: 'ASP-2026-0001'),
            new OA\Property(property: 'title', type: 'string', example: 'Perbaikan Jalan'),
            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Jalan di daerah X rusak parah'),
            new OA\Property(property: 'desa_id', type: 'integer', nullable: true, example: 5),
            new OA\Property(property: 'kecamatan_id', type: 'integer', nullable: true, example: 2),
            new OA\Property(property: 'dapil_id', type: 'integer', nullable: true, example: 3),
            new OA\Property(property: 'opd_id', type: 'integer', nullable: true, example: 4),
            new OA\Property(property: 'tanggal', type: 'string', format: 'date', example: '2026-06-11'),
            new OA\Property(property: 'source', type: 'string', enum: ['reses', 'tatap_muka', 'surat', 'lainnya'], example: 'reses'),
            new OA\Property(property: 'is_complete', type: 'boolean', example: false),
            new OA\Property(property: 'is_used_in_pokir', type: 'boolean', example: false),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'deleted_at', type: 'string', format: 'date-time', nullable: true, example: null),
        ]
    )]
    public function aspirasiSchema() {}

    #[OA\Schema(
        schema: 'AspirasiActivity',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'aspirasi_id', type: 'integer', example: 1),
            new OA\Property(property: 'user_id', type: 'integer', example: 1),
            new OA\Property(property: 'action', type: 'string', example: 'created'),
            new OA\Property(
                property: 'changes', 
                type: 'object', 
                nullable: true, 
                example: ['title' => ['old' => 'Jalan Rusak', 'new' => 'Perbaikan Jalan']]
            ),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function aspirasiActivitySchema() {}

    #[OA\Schema(
        schema: 'AspirasiAttachment',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'aspirasi_id', type: 'integer', example: 1),
            new OA\Property(property: 'file_name', type: 'string', example: 'foto_jalan.jpg'),
            new OA\Property(property: 'file_path', type: 'string', example: 'attachments/foto_jalan.jpg'),
            new OA\Property(property: 'file_type', type: 'string', example: 'image/jpeg'),
            new OA\Property(property: 'file_size', type: 'integer', example: 204800),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function aspirasiAttachmentSchema() {}

    #[OA\Schema(
        schema: 'StoreAspirasiRequest',
        type: 'object',
        required: ['title', 'tanggal', 'source'],
        properties: [
            new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Perbaikan Jalan'),
            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Jalan di daerah X rusak parah'),
            new OA\Property(property: 'desa_id', type: 'integer', nullable: true, example: 5),
            new OA\Property(property: 'kecamatan_id', type: 'integer', nullable: true, example: 2),
            new OA\Property(property: 'dapil_id', type: 'integer', nullable: true, example: 3),
            new OA\Property(property: 'opd_id', type: 'integer', nullable: true, example: 4),
            new OA\Property(property: 'tanggal', type: 'string', format: 'date', example: '2026-06-11'),
            new OA\Property(property: 'source', type: 'string', enum: ['reses', 'tatap_muka', 'surat', 'lainnya'], example: 'reses'),
        ]
    )]
    public function storeRequest() {}

    #[OA\Schema(
        schema: 'UpdateAspirasiRequest',
        type: 'object',
        properties: [
            new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Perbaikan Jalan Diperbarui'),
            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Kondisi semakin memburuk'),
            new OA\Property(property: 'desa_id', type: 'integer', nullable: true, example: 5),
            new OA\Property(property: 'kecamatan_id', type: 'integer', nullable: true, example: 2),
            new OA\Property(property: 'dapil_id', type: 'integer', nullable: true, example: 3),
            new OA\Property(property: 'opd_id', type: 'integer', nullable: true, example: 4),
            new OA\Property(property: 'tanggal', type: 'string', format: 'date', example: '2026-06-11'),
            new OA\Property(property: 'source', type: 'string', enum: ['reses', 'tatap_muka', 'surat', 'lainnya'], example: 'tatap_muka'),
            new OA\Property(property: 'is_complete', type: 'boolean', example: true),
        ]
    )]
    public function updateRequest() {}
}
