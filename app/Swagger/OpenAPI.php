<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'ZiloPay API',
    version: '1.0.0',
    description: 'Payment Processing and Money Transfer API',
    contact: new OA\Contact(
        name: 'ZiloPay Support',
        email: 'support@zilopay.com'
    ),
    license: new OA\License(
        name: 'MIT License',
        url: 'https://opensource.org/licenses/MIT'
    )
)]

#[OA\Server(
    url: 'http://localhost:8000/api',
    description: 'API Server'
)]

#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    description: 'Login with username and password to get the authentication token',
    name: 'Token based auth',
    in: 'header',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]

class OpenAPI
{
    // This class is used for OpenAPI documentation
}
