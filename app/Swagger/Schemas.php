<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

// ─── Global Responses ─────────────────────────────────────────────────────────

#[OA\Schema(
    schema: 'ErrorResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: false),
        new OA\Property(property: 'message', type: 'string', example: 'Terjadi kesalahan.'),
        new OA\Property(property: 'data', nullable: true, example: null),
    ]
)]


class Schemas {}