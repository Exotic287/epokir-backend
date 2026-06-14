<?php

namespace App\Swagger\Dapil;

use OpenApi\Attributes as OA;

class Schemas
{
    #[OA\Schema(
        schema: 'Dapil',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'number', type: 'integer', example: 1),
            new OA\Property(property: 'name', type: 'string', example: 'Dapil I - Kota X'),
            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Meliputi kecamatan A dan B'),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function dapilSchema() {}

    #[OA\Schema(
        schema: 'StoreDapilRequest',
        type: 'object',
        required: ['number', 'name'],
        properties: [
            new OA\Property(property: 'number', type: 'integer', example: 1),
            new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Dapil I - Kota X'),
            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Meliputi kecamatan A dan B'),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
        ]
    )]
    public function storeDapilRequest() {}

    #[OA\Schema(
        schema: 'UpdateDapilRequest',
        type: 'object',
        properties: [
            new OA\Property(property: 'number', type: 'integer', example: 2),
            new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Dapil II - Kota Y'),
            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Meliputi kecamatan C dan D'),
            new OA\Property(property: 'is_active', type: 'boolean', example: false),
        ]
    )]
    public function updateDapilRequest() {}
}