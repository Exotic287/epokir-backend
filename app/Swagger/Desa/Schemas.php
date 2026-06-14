<?php

namespace App\Swagger\Desa;

use OpenApi\Attributes as OA;

class Schemas
{
    #[OA\Schema(
        schema: 'Desa',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'code', type: 'string', example: '3326010001'),
            new OA\Property(property: 'name', type: 'string', example: 'Desa Karanganyar'),
            new OA\Property(property: 'kecamatan_id', type: 'integer', example: 1),
            new OA\Property(property: 'dapil_id', type: 'integer', example: 2),
            new OA\Property(property: 'latitude', type: 'number', format: 'float', nullable: true, example: -6.8897),
            new OA\Property(property: 'longitude', type: 'number', format: 'float', nullable: true, example: 109.6753),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function desaSchema() {}

    #[OA\Schema(
        schema: 'StoreDesaRequest',
        type: 'object',
        required: ['code', 'name', 'kecamatan_id', 'dapil_id'],
        properties: [
            new OA\Property(property: 'code', type: 'string', maxLength: 255, example: '3326010001'),
            new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Desa Karanganyar'),
            new OA\Property(property: 'kecamatan_id', type: 'integer', example: 1),
            new OA\Property(property: 'dapil_id', type: 'integer', example: 2),
            new OA\Property(property: 'latitude', type: 'number', format: 'float', nullable: true, example: -6.8897),
            new OA\Property(property: 'longitude', type: 'number', format: 'float', nullable: true, example: 109.6753),
        ]
    )]
    public function storeDesaRequest() {}

    #[OA\Schema(
        schema: 'UpdateDesaRequest',
        type: 'object',
        properties: [
            new OA\Property(property: 'code', type: 'string', maxLength: 255, example: '3326010001'),
            new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Desa Karanganyar Baru'),
            new OA\Property(property: 'kecamatan_id', type: 'integer', example: 1),
            new OA\Property(property: 'dapil_id', type: 'integer', example: 2),
            new OA\Property(property: 'latitude', type: 'number', format: 'float', nullable: true, example: -6.8897),
            new OA\Property(property: 'longitude', type: 'number', format: 'float', nullable: true, example: 109.6753),
        ]
    )]
    public function updateDesaRequest() {}
}