<?php

namespace App\Swagger\Auth;

use OpenApi\Attributes as OA;

class Schemas
{
    #[OA\Schema(
        schema: 'User',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'sso_id', type: 'string', example: 'SSO-00123'),
            new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
            new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
            new OA\Property(property: 'avatar', type: 'string', nullable: true, example: 'https://cdn.example.com/avatar.jpg'),
            new OA\Property(property: 'role', type: 'string', enum: ['admin', 'setwan', 'dewan'], example: 'dewan'),
            new OA\Property(property: 'dapil_id', type: 'integer', nullable: true, example: 1),
            new OA\Property(property: 'is_active', type: 'boolean', example: true),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-01T00:00:00Z'),
        ]
    )]
    public function userSchema() {}

    #[OA\Schema(
        schema: 'UserWithRelations',
        allOf: [
            new OA\Schema(ref: '#/components/schemas/User'),
            new OA\Schema(
                properties: [
                    new OA\Property(property: 'dapil', nullable: true, ref: '#/components/schemas/Dapil'),
                ]
            ),
        ]
    )]
    public function userWithRelationsSchema() {}
}
