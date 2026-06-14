<?php

namespace App\Swagger\Opd;

use OpenApi\Attributes as OA;

class Schemas
{
    #[OA\Schema(
        schema: 'Opd',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'code', type: 'string', example: '1.01.01'),
            new OA\Property(property: 'name', type: 'string', example: 'Dinas Pendidikan dan Kebudayaan'),
            new OA\Property(property: 'short_name', type: 'string', nullable: true, example: 'Disdikbud'),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function opdSchema() {}

    #[OA\Schema(
        schema: 'StoreOpdRequest',
        type: 'object',
        required: ['code', 'name'],
        properties: [
            new OA\Property(property: 'code', type: 'string', maxLength: 255, example: '1.01.01'),
            new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Dinas Pendidikan dan Kebudayaan'),
            new OA\Property(property: 'short_name', type: 'string', maxLength: 255, nullable: true, example: 'Disdikbud'),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
        ]
    )]
    public function storeOpdRequest() {}

    #[OA\Schema(
        schema: 'UpdateOpdRequest',
        type: 'object',
        properties: [
            new OA\Property(property: 'code', type: 'string', maxLength: 255, example: '1.01.02'),
            new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Dinas Pendidikan dan Kebudayaan'),
            new OA\Property(property: 'short_name', type: 'string', maxLength: 255, nullable: true, example: 'Disdikbud'),
            new OA\Property(property: 'is_active', type: 'boolean', example: false),
        ]
    )]
    public function updateOpdRequest() {}
}