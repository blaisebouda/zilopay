<?php

namespace App\Swagger\Annotaions;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
        new OA\Property(property: 'pseudo', type: 'string', example: 'johndoe'),
        new OA\Property(property: 'email_verified_at', type: 'string', format: 'date-time', example: '2021-01-01 10:00:00'),
        new OA\Property(property: 'phone', type: 'string', example: '+22500000000'),
        new OA\Property(property: 'first_name', type: 'string', example: 'John'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
        new OA\Property(property: 'full_name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'is_verified', type: 'boolean', example: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]

class AuthAnot
{
    #[OA\Post(
        path: '/auth/login',
        summary: 'Login a user',
        tags: ['Authentification'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['identifier', 'password'],
                properties: [
                    new OA\Property(property: 'identifier', type: 'string', example: 'user@zilopay.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                    new OA\Property(property: 'remember', type: 'boolean', example: false),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            ref: '#/components/schemas/User'
                        ),
                        new OA\Property(
                            property: 'token',
                            type: 'string',
                            example: '1|abcdef...'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function login() {}
}
