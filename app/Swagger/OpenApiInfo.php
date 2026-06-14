<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'API EPOKIR',
    version: '1.0.0',
    description: 'Dokumentasi REST API E-Pokir Kabupaten Pekalongan'
)]
#[OA\Server(
    url: 'http://127.0.0.1:8001',
    description: 'API Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Masukkan token Passport: Bearer {token}'
)]
class OpenApiInfo {}