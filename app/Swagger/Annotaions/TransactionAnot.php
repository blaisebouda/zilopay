<?php

namespace App\Swagger\Annotaions;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Transactions')]

#[OA\Schema(
    schema: 'Transaction',
    type: 'object',

    properties: [
        new OA\Property(property: 'type', type: 'string', example: 'deposit', description: 'Transaction type'),
        new OA\Property(property: 'target', type: 'string', example: 'Wallet #1', description: 'Transaction target'),
        new OA\Property(property: 'reference', type: 'string', example: 'deposit-123456', description: 'Unique transaction reference'),
        new OA\Property(property: 'amount', type: 'string', example: '5,000 CFA', description: 'Formatted amount'),
        new OA\Property(property: 'status', type: 'string', example: 'pending', description: 'Transaction status'),
        new OA\Property(property: 'status_color', type: 'string', example: '#FFA500', description: 'Status color code'),
        new OA\Property(property: 'date', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00', description: 'Transaction date'),
        new OA\Property(property: 'operator', type: 'string', example: 'Orange Money', description: 'Payment operator'),
        new OA\Property(property: 'is_deposit', type: 'boolean', example: true, description: 'Is deposit transaction'),
        new OA\Property(property: 'is_withdrawal', type: 'boolean', example: false, description: 'Is withdrawal transaction'),
        new OA\Property(property: 'is_transfer', type: 'boolean', example: false, description: 'Is transfer transaction')
    ]
)]
class TransactionAnot
{
    #[OA\Post(
        path: '/transactions/init-deposit',
        summary: 'Initiate a new deposit',
        tags: ['Transactions'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['wallet_id', 'amount', 'payment_method_id', 'phone_number'],
                properties: [
                    new OA\Property(property: 'wallet_id', type: 'string', example: 'ZP00000000', description: 'Wallet code to credit'),
                    new OA\Property(property: 'amount', type: 'number', example: 5000, minimum: 100, maximum: 10000000, description: 'Deposit amount'),
                    new OA\Property(property: 'payment_method_id', type: 'integer', example: 1, description: 'Payment method ID'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '1234567890', pattern: '^[0-9]{8,15}$', description: 'Phone number for payment')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Deposit initiated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Deposit created successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Transaction')
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
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed')
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
                        new OA\Property(property: 'message', type: 'string', example: 'Failed to create deposit')
                    ]
                )
            )
        ]
    )]
    public function initDeposit() {}

    #[OA\Post(
        path: '/transactions/confirm-deposit/{reference}',
        summary: 'Confirm a deposit from gateway callback',
        tags: ['Transactions'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'reference',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
                description: 'Deposit reference'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                description: 'Gateway callback data'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Deposit confirmed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Deposit confirmed'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'reference', type: 'string', example: 'deposit-123456'),
                                new OA\Property(property: 'status', type: 'string', example: 'confirmed')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'status', type: 'integer', example: 400),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid reference')
                    ]
                )
            )
        ]
    )]
    public function confirmDeposit() {}

    #[OA\Get(
        path: '/transactions/history',
        summary: 'Get transaction history',
        tags: ['Transactions'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaction history retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Transaction')
                        ),
                    ]
                )
            ),

        ]
    )]
    public function history() {}

    #[OA\Post(
        path: '/transactions/transfer',
        summary: 'Create a new transfer',
        tags: ['Transactions'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['sender_wallet_id', 'receiver_wallet_id', 'amount'],
                properties: [
                    new OA\Property(property: 'sender_wallet_id', type: 'string', example: 'ZP00000000', description: 'Sender wallet code'),
                    new OA\Property(property: 'receiver_wallet_id', type: 'string', example: 'ZP00000001', description: 'Receiver wallet code'),
                    new OA\Property(property: 'amount', type: 'number', example: 5000, minimum: 100, maximum: 10000000, description: 'Transfer amount'),
                    new OA\Property(property: 'note', type: 'string', example: 'Payment for services', description: 'Transfer note (optional)')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transfer completed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Request successful'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Transaction')
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
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed')
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
                        new OA\Property(property: 'message', type: 'string', example: 'Failed to complete transfer')
                    ]
                )
            )
        ]
    )]
    public function transfer() {}
}
