<?php

namespace App\Swagger\KamusPokir;

use OpenApi\Attributes as OA;

class Schemas
{
    #[OA\Schema(
        schema: 'KamusPokir',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'kamus_version', type: 'string', example: '2026.1'),
            new OA\Property(property: 'level', type: 'integer', example: 1),
            new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
            new OA\Property(property: 'opd_id', type: 'integer', example: 1),
            new OA\Property(property: 'program_sipd_id', type: 'integer', example: 1),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
        ]
    )]
    public function kamusSchema() {}

    #[OA\Schema(
        schema: 'StoreKamusPokirRequest',
        type: 'object',
        required: ['kamus_version', 'level', 'opd_id', 'program_sipd_id'],
        properties: [
            new OA\Property(property: 'kamus_version', type: 'string', example: '2026.1'),
            new OA\Property(property: 'level', type: 'integer', example: 1),
            new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
            new OA\Property(property: 'opd_id', type: 'integer', example: 1),
            new OA\Property(property: 'program_sipd_id', type: 'integer', example: 1),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
        ]
    )]
    public function storeRequest() {}

    #[OA\Schema(
        schema: 'UpdateKamusPokirRequest',
        type: 'object',
        properties: [
            new OA\Property(property: 'kamus_version', type: 'string', example: '2026.1'),
            new OA\Property(property: 'level', type: 'integer', example: 1),
            new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
            new OA\Property(property: 'opd_id', type: 'integer', example: 1),
            new OA\Property(property: 'program_sipd_id', type: 'integer', example: 1),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
        ]
    )]
    public function updateRequest() {}
}