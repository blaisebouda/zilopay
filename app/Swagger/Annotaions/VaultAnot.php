<?php

namespace App\Swagger\Annotaions;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Vaults')]
#[OA\Schema(
    schema: 'Vault',
    type: 'object',
    properties: [
        new OA\Property(property: 'uuid', type: 'string', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'name', type: 'string', example: 'Épargne vacances'),
        new OA\Property(property: 'description', type: 'string', example: 'Coffre-fort pour les vacances'),
        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 150000.50),
        new OA\Property(property: 'currency', type: 'string', example: 'XOF'),
        new OA\Property(property: 'type', type: 'string', example: 'savings'),
        new OA\Property(property: 'type_label', type: 'string', example: 'Épargne'),
        new OA\Property(property: 'type_color', type: 'string', example: '#28a745'),
        new OA\Property(property: 'status', type: 'string', example: 'active'),
        new OA\Property(property: 'status_label', type: 'string', example: 'Actif'),
        new OA\Property(property: 'status_color', type: 'string', example: '#28a745'),
        new OA\Property(property: 'maturity_date', type: 'string', format: 'date-time', example: '2025-12-31 23:59:59'),
        new OA\Property(property: 'is_locked', type: 'boolean', example: false),
        new OA\Property(property: 'is_active', type: 'boolean', example: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
    ]
)]
#[OA\Schema(
    schema: 'VaultTransaction',
    type: 'object',
    properties: [
        new OA\Property(property: 'uuid', type: 'string', example: '550e8400-e29b-41d4-a716-446655440001'),
        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 50000.00),
        new OA\Property(property: 'type', type: 'string', example: 'deposit'),
        new OA\Property(property: 'type_label', type: 'string', example: 'Dépôt'),
        new OA\Property(property: 'type_color', type: 'string', example: '#28a745'),
        new OA\Property(property: 'description', type: 'string', example: 'Dépôt dans le coffre-fort'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
    ]
)]
class VaultAnot
{
    #[OA\Get(
        path: '/vaults',
        summary: 'List user vaults',
        tags: ['Vaults'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vaults retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Coffres-forts récupérés avec succès'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Vault')
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 401),
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 500),
                        new OA\Property(property: 'message', type: 'string', example: 'Échec de la récupération des coffres-forts'),
                    ]
                )
            ),
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: '/vaults',
        summary: 'Create a new vault',
        tags: ['Vaults'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'currency', 'type'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Épargne vacances', description: 'Vault name'),
                    new OA\Property(property: 'description', type: 'string', example: 'Coffre-fort pour les vacances', description: 'Vault description (optional)'),
                    new OA\Property(property: 'currency', type: 'string', example: 'XOF', description: 'Currency code (3 letters)'),
                    new OA\Property(property: 'type', type: 'string', example: 'savings', description: 'Vault type: savings, investment, emergency'),
                    new OA\Property(property: 'maturity_date', type: 'string', format: 'date-time', example: '2026-12-31', description: 'Maturity date (optional)'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vault created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Coffre-fort créé avec succès'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Vault'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 422),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 500),
                        new OA\Property(property: 'message', type: 'string', example: 'Échec de la création du coffre-fort'),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/vaults/{uuid}',
        summary: 'Show vault details with balance and transactions',
        tags: ['Vaults'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'uuid',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
                description: 'Vault UUID'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vault retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Coffre-fort récupéré avec succès'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Vault'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 403),
                        new OA\Property(property: 'message', type: 'string', example: 'Accès non autorisé'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 404),
                        new OA\Property(property: 'message', type: 'string', example: 'Coffre-fort non trouvé'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 500),
                        new OA\Property(property: 'message', type: 'string', example: 'Échec de la récupération du coffre-fort'),
                    ]
                )
            ),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/vaults/{uuid}/deposit',
        summary: 'Deposit money into vault',
        tags: ['Vaults'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'uuid',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
                description: 'Vault UUID'
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['amount', 'wallet_id'],
                properties: [
                    new OA\Property(property: 'amount', type: 'number', example: 5000, description: 'Amount to deposit (minimum: 1)'),
                    new OA\Property(property: 'wallet_id', type: 'string', example: "ZP00000000", description: 'Wallet ID'),
                    new OA\Property(property: 'description', type: 'string', example: 'Dépôt mensuel', description: 'Transaction description (optional)'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Deposit completed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Dépôt effectué avec succès'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/VaultTransaction'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 403),
                        new OA\Property(property: 'message', type: 'string', example: 'Accès non autorisé'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 422),
                        new OA\Property(property: 'message', type: 'string', example: 'Le coffre-fort doit être actif pour effectuer un dépôt'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 500),
                        new OA\Property(property: 'message', type: 'string', example: 'Échec du dépôt'),
                    ]
                )
            ),
        ]
    )]
    public function deposit() {}

    #[OA\Post(
        path: '/vaults/{uuid}/withdraw',
        summary: 'Withdraw money from vault (check if not locked)',
        tags: ['Vaults'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'uuid',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
                description: 'Vault UUID'
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['amount', 'wallet_id'],
                properties: [
                    new OA\Property(property: 'amount', type: 'number', example: 10000, description: 'Amount to withdraw (minimum: 1)'),
                    new OA\Property(property: 'wallet_id', type: 'string', example: "ZP00000000", description: 'Wallet ID'),
                    new OA\Property(property: 'description', type: 'string', example: 'Retrait VaultAnot achat', description: 'Transaction description (optional)'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Withdrawal completed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Retrait VaultAnoté avec succès'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/VaultTransaction'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 403),
                        new OA\Property(property: 'message', type: 'string', example: 'Accès non autorisé'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 422),
                        new OA\Property(property: 'message', type: 'string', example: 'Le coffre-fort est verrouillé ou solde insuffisant'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 500),
                        new OA\Property(property: 'message', type: 'string', example: 'Échec du retrait'),
                    ]
                )
            ),
        ]
    )]
    public function withdraw() {}

    #[OA\Post(
        path: '/vaults/{uuid}/toggle',
        summary: 'Lock or unlock vault',
        tags: ['Vaults'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'uuid',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
                description: 'Vault UUID'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vault lock status toggled successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Coffre-fort verrouillé/déverrouillé avec succès'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Vault'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 403),
                        new OA\Property(property: 'message', type: 'string', example: 'Accès non autorisé'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 404),
                        new OA\Property(property: 'message', type: 'string', example: 'Coffre-fort non trouvé'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 500),
                        new OA\Property(property: 'message', type: 'string', example: 'Échec du changement de statut'),
                    ]
                )
            ),
        ]
    )]
    public function toggle() {}
}
