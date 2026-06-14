<?php

namespace App\Swagger\Kecamatan;

use OpenApi\Attributes as OA;

class Schemas
{
    #[OA\Schema(
        schema: 'Kecamatan',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'code', type: 'string', example: '3326010'),
            new OA\Property(property: 'name', type: 'string', example: 'Kecamatan Karanganyar'),
            new OA\Property(property: 'dapil_id', type: 'integer', example: 1),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function kecamatanSchema() {}

    #[OA\Schema(
        schema: 'StoreKecamatanRequest',
        type: 'object',
        required: ['code', 'name', 'dapil_id'],
        properties: [
            new OA\Property(property: 'code', type: 'string', maxLength: 255, example: '3326010'),
            new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Kecamatan Karanganyar'),
            new OA\Property(property: 'dapil_id', type: 'integer', example: 1),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
        ]
    )]
    public function storeKecamatanRequest() {}

    #[OA\Schema(
        schema: 'UpdateKecamatanRequest',
        type: 'object',
        properties: [
            new OA\Property(property: 'code', type: 'string', maxLength: 255, example: '3326010'),
            new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Kecamatan Karanganyar Baru'),
            new OA\Property(property: 'dapil_id', type: 'integer', example: 1),
            new OA\Property(property: 'is_active', type: 'boolean', example: false),
        ]
    )]
    public function updateKecamatanRequest() {}
}