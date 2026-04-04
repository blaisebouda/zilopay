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
    ]
)]

#[OA\Schema(
    schema: 'Wallet',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', example: 'WALLET123'),
        new OA\Property(property: 'balance', type: 'number', format: 'float', example: 1000.50),
        new OA\Property(property: 'currency', type: 'string', example: 'XOF'),
        new OA\Property(property: 'currency_symbol', type: 'string', example: 'CFA'),
    ]
)]

class AuthAnot
{
    #[OA\Post(
        path: '/auth/register',
        summary: 'Register a new user',
        tags: ['Authentification'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation', 'phone'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@zilopay.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'Password123!'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'Password123!'),
                    new OA\Property(property: 'phone', type: 'string', example: '+22500000000'),
                    new OA\Property(property: 'policy_accepted', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful registration',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Inscription réussie.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'user',
                                    ref: '#/components/schemas/User'
                                ),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function register() {}

    #[OA\Post(
        path: '/auth/login',
        summary: 'Login a user',
        tags: ['Authentification'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'user@zilopay.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                    new OA\Property(property: 'remember', type: 'boolean', example: false),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful login',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Connexion réussie.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'token', type: 'string', example: '1|abcdef...'),
                                new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                                new OA\Property(property: 'wallet', ref: '#/components/schemas/Wallet'),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function login() {}

    #[OA\Post(
        path: '/auth/logout',
        summary: 'Logout user',
        tags: ['Authentification'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful logout',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Déconnexion réussie.'),
                        new OA\Property(property: 'data', type: 'object', example: []),
                    ]
                )
            ),
        ]
    )]
    public function logout() {}

    #[OA\Post(
        path: '/auth/logout-all',
        summary: 'Logout user from all devices',
        tags: ['Authentification'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful logout from all devices',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Déconnexion réussie de tous les appareils.'),
                        new OA\Property(property: 'data', type: 'object', example: []),
                    ]
                )
            ),
        ]
    )]
    public function logoutAll() {}

    #[OA\Get(
        path: '/auth/me',
        summary: 'Get current user',
        tags: ['Authentification'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Current user data',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/User'),
                    ]
                )
            ),
        ]
    )]
    public function me() {}

    #[OA\Post(
        path: '/auth/refresh',
        summary: 'Refresh authentication token',
        tags: ['Authentification'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token refreshed successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Token rafraîchi avec succès.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'token', type: 'string', example: '1|newtoken...'),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function refresh() {}
}
