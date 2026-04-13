<?php

// declare(strict_types=1);

namespace App\Swagger\Annotaions;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Merchants')]
#[OA\Schema(
    schema: 'Merchant',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'uuid', type: 'string', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'business_name', type: 'string', example: 'ZiloShop'),
        new OA\Property(property: 'business_email', type: 'string', example: 'contact@ziloshop.com'),
        new OA\Property(property: 'phone', type: 'string', example: '+22501234567'),
        new OA\Property(property: 'country', type: 'string', example: 'CI'),
        new OA\Property(property: 'fee_fixed', type: 'number', format: 'float', example: 100.00),
        new OA\Property(property: 'fee_percentage', type: 'number', format: 'float', example: 2.5),
        new OA\Property(property: 'status', type: 'integer', example: 0),
        new OA\Property(property: 'status_label', type: 'string', example: 'Pending'),
        new OA\Property(property: 'approved_at', type: 'string', format: 'date-time', nullable: true, example: null),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
    ]
)]
#[OA\Schema(
    schema: 'PaymentLink',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'uuid', type: 'string', example: '550e8400-e29b-41d4-a716-446655440001'),
        new OA\Property(property: 'merchant_id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Product Payment'),
        new OA\Property(property: 'description', type: 'string', example: 'Payment for product XYZ'),
        new OA\Property(property: 'amount', type: 'number', format: 'float', nullable: true, example: 5000.00),
        new OA\Property(property: 'currency', type: 'string', example: 'XOF'),
        new OA\Property(property: 'status', type: 'integer', example: 1),
        new OA\Property(property: 'status_label', type: 'string', example: 'Active'),
        new OA\Property(property: 'max_uses', type: 'integer', nullable: true, example: 100),
        new OA\Property(property: 'uses_count', type: 'integer', example: 5),
        new OA\Property(property: 'remaining_uses', type: 'integer', nullable: true, example: 95),
        new OA\Property(property: 'expires_at', type: 'string', format: 'date-time', nullable: true, example: '2024-12-31 23:59:59'),
        new OA\Property(property: 'is_expired', type: 'boolean', example: false),
        new OA\Property(property: 'payment_url', type: 'string', example: 'https://api.zilopay.com/merchant/pay/550e8400-e29b-41d4-a716-446655440001'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
    ]
)]
#[OA\Schema(
    schema: 'MerchantApiKey',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'uuid', type: 'string', example: '550e8400-e29b-41d4-a716-446655440002'),
        new OA\Property(property: 'merchant_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Production Key'),
        new OA\Property(property: 'key', type: 'string', example: 'mk_live_abc123xyz456'),
        new OA\Property(property: 'public_key', type: 'string', example: 'mk_pub_live_def789uvw012'),
        new OA\Property(property: 'secret', type: 'string', example: 'sec_abc123xyz789', description: 'Only shown once when created'),
        new OA\Property(property: 'secret_warning', type: 'string', example: 'This secret will only be displayed once. Please save it securely.'),
        new OA\Property(property: 'is_live', type: 'boolean', example: true),
        new OA\Property(property: 'is_active', type: 'boolean', example: true),
        new OA\Property(property: 'last_used_at', type: 'string', format: 'date-time', nullable: true, example: '2024-01-15 14:30:00'),
        new OA\Property(property: 'expires_at', type: 'string', format: 'date-time', nullable: true, example: null),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
    ]
)]
#[OA\Schema(
    schema: 'MerchantTransaction',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'uuid', type: 'string', example: '550e8400-e29b-41d4-a716-446655440003'),
        new OA\Property(property: 'merchant_id', type: 'integer', example: 1),
        new OA\Property(property: 'payment_link_id', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 5000.00),
        new OA\Property(property: 'currency', type: 'string', example: 'XOF'),
        new OA\Property(property: 'status', type: 'string', example: 'pending'),
        new OA\Property(property: 'customer_email', type: 'string', nullable: true, example: 'customer@example.com'),
        new OA\Property(property: 'customer_phone', type: 'string', nullable: true, example: '+22501234567'),
        new OA\Property(property: 'customer_name', type: 'string', nullable: true, example: 'John Doe'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Payment for order #123'),
        new OA\Property(property: 'reference', type: 'string', example: 'ZPAY_abc123'),
        new OA\Property(property: 'metadata', type: 'object', nullable: true),
        new OA\Property(property: 'paid_at', type: 'string', format: 'date-time', nullable: true, example: null),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
    ]
)]
#[OA\Schema(
    schema: 'MerchantDashboard',
    type: 'object',
    properties: [
        new OA\Property(property: 'merchant', ref: '#/components/schemas/Merchant'),
        new OA\Property(
            property: 'statistics',
            type: 'object',
            properties: [
                new OA\Property(property: 'total_revenue', type: 'number', format: 'float', example: 150000.00),
                new OA\Property(property: 'pending_payments_count', type: 'integer', example: 3),
                new OA\Property(property: 'payment_links_count', type: 'integer', example: 10),
                new OA\Property(property: 'active_payment_links_count', type: 'integer', example: 7),
            ]
        ),
        new OA\Property(
            property: 'recent_payments',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/MerchantTransaction')
        ),
    ]
)]
#[OA\Schema(
    schema: 'FeeCalculation',
    type: 'object',
    properties: [
        new OA\Property(property: 'original_amount', type: 'number', format: 'float', example: 10000.00),
        new OA\Property(property: 'fixed_fee', type: 'number', format: 'float', example: 100.00),
        new OA\Property(property: 'percentage_fee', type: 'number', format: 'float', example: 2.50),
        new OA\Property(property: 'percentage_fee_amount', type: 'number', format: 'float', example: 250.00),
        new OA\Property(property: 'total_fee', type: 'number', format: 'float', example: 350.00),
        new OA\Property(property: 'net_amount', type: 'number', format: 'float', example: 9650.00),
    ]
)]
class MerchantAnot
{
    #[OA\Post(
        path: '/merchant',
        summary: 'Create merchant profile',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['business_name', 'business_email', 'country'],
                    properties: [
                        new OA\Property(property: 'business_name', type: 'string', example: 'My Shop'),
                        new OA\Property(property: 'business_email', type: 'string', example: 'contact@myshop.com'),
                        new OA\Property(property: 'phone_number', type: 'string', example: '+22501234567'),
                        new OA\Property(property: 'country', type: 'string', example: 'CI'),
                        new OA\Property(property: 'documents[id_card]', type: 'string', format: 'binary', description: 'ID card PDF file (max 5MB)'),
                        new OA\Property(property: 'documents[business_license]', type: 'string', format: 'binary', description: 'Business license PDF file (max 5MB)'),
                        new OA\Property(property: 'documents[tax_certificate]', type: 'string', format: 'binary', description: 'Tax certificate PDF file (max 5MB)'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Merchant profile created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 201),
                        new OA\Property(property: 'message', type: 'string', example: 'Merchant profile created successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Merchant'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/merchant',
        summary: 'Get merchant by UUID',
        tags: ['Merchants'],
        security: [['sanctum' => []]],

        responses: [
            new OA\Response(
                response: 200,
                description: 'Merchant retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Merchant retrieved successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Merchant'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Merchant not found'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function show() {}

    #[OA\Get(
        path: '/merchant/documents/{path}',
        summary: 'Download merchant document (PDF)',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'path',
                in: 'path',
                required: true,
                description: 'Path of the merchant document',
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'PDF file downloaded successfully',
                content: new OA\MediaType(
                    mediaType: 'application/pdf',
                    schema: new OA\Schema(type: 'string', format: 'binary')
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Unauthorized - document does not belong to merchant'),
            new OA\Response(response: 404, description: 'Document or file not found'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function downloadDocument() {}

    #[OA\Get(
        path: '/merchant/dashboard',
        summary: 'Get merchant dashboard statistics',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dashboard statistics retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Dashboard statistics retrieved successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/MerchantDashboard'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Merchant not approved'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function dashboard() {}

    #[OA\Get(
        path: '/merchant/payment-links',
        summary: 'List all payment links',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment links retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment links retrieved successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/PaymentLink')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Merchant not approved'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function paymentLinksIndex() {}

    #[OA\Post(
        path: '/merchant/payment-links',
        summary: 'Create payment link',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Product Payment'),
                    new OA\Property(property: 'description', type: 'string', example: 'Payment for product XYZ'),
                    new OA\Property(property: 'amount', type: 'number', nullable: true, example: 5000.00),
                    new OA\Property(property: 'currency', type: 'string', example: 'XOF'),
                    new OA\Property(property: 'max_uses', type: 'integer', example: 100),
                    new OA\Property(property: 'expires_at', type: 'string', format: 'date-time', example: '2024-12-31 23:59:59'),
                    new OA\Property(property: 'metadata', type: 'object'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Payment link created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 201),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment link created successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/PaymentLink'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Merchant not approved'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function paymentLinksStore() {}

    #[OA\Get(
        path: '/merchant/payment-links/{paymentLink}',
        summary: 'Get payment link details',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'paymentLink', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment link retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment link retrieved successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/PaymentLink'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Merchant not approved or unauthorized'),
            new OA\Response(response: 404, description: 'Payment link not found'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function paymentLinksShow() {}

    #[OA\Put(
        path: '/merchant/payment-links/{paymentLink}',
        summary: 'Update payment link',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'paymentLink', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Updated Payment'),
                    new OA\Property(property: 'description', type: 'string', example: 'Updated description'),
                    new OA\Property(property: 'amount', type: 'number', nullable: true, example: 6000.00),
                    new OA\Property(property: 'status', type: 'integer', example: 1),
                    new OA\Property(property: 'max_uses', type: 'integer', example: 200),
                    new OA\Property(property: 'expires_at', type: 'string', format: 'date-time', example: '2024-12-31 23:59:59'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment link updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment link updated successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/PaymentLink'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Merchant not approved or unauthorized'),
            new OA\Response(response: 404, description: 'Payment link not found'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function paymentLinksUpdate() {}

    #[OA\Delete(
        path: '/merchant/payment-links/{paymentLink}',
        summary: 'Delete payment link',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'paymentLink', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment link deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment link deleted successfully'),
                        new OA\Property(property: 'data', type: 'null'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Merchant not approved or unauthorized'),
            new OA\Response(response: 404, description: 'Payment link not found'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function paymentLinksDestroy() {}

    #[OA\Post(
        path: '/merchant/api-keys',
        summary: 'Create API key',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Production Key'),
                    new OA\Property(property: 'is_live', type: 'boolean', example: true),
                    new OA\Property(property: 'expires_at', type: 'string', format: 'date-time', example: '2025-12-31 23:59:59'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'API key created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 201),
                        new OA\Property(property: 'message', type: 'string', example: 'API key created successfully. Please save the secret, it will not be shown again.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/MerchantApiKey'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Merchant not approved'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function apiKeysStore() {}

    #[OA\Delete(
        path: '/merchant/api-keys/{api_key}',
        summary: 'Delete API key',
        tags: ['Merchants'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'api_key', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'API key deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'API key deleted successfully'),
                        new OA\Property(property: 'data', type: 'null'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Merchant not approved or unauthorized'),
            new OA\Response(response: 404, description: 'API key not found'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function apiKeysDestroy() {}

    #[OA\Post(
        path: '/merchant/payments/initiate',
        summary: 'Initiate payment via API',
        tags: ['Merchants'],
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['amount'],
                properties: [
                    new OA\Property(property: 'amount', type: 'number', example: 10000.00),
                    new OA\Property(property: 'currency', type: 'string', example: 'XOF'),
                    new OA\Property(property: 'customer_email', type: 'string', example: 'customer@example.com'),
                    new OA\Property(property: 'customer_phone', type: 'string', example: '+22501234567'),
                    new OA\Property(property: 'customer_name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'description', type: 'string', example: 'Payment for order #123'),
                    new OA\Property(property: 'reference', type: 'string', example: 'REF_123'),
                    new OA\Property(property: 'metadata', type: 'object'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Payment initiated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 201),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment initiated successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'transaction', ref: '#/components/schemas/MerchantTransaction'),
                                new OA\Property(property: 'fees', ref: '#/components/schemas/FeeCalculation'),
                                new OA\Property(property: 'payment_url', type: 'string', example: 'https://api.zilopay.com/merchant/payments/550e8400-e29b-41d4-a716-446655440003'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid API credentials'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    #[OA\SecurityScheme(
        securityScheme: 'api_key',
        type: 'apiKey',
        in: 'header',
        name: 'X-API-Key',
        description: 'API Key authentication'
    )]
    public function paymentsInitiate() {}

    #[OA\Get(
        path: '/merchant/payments/{uuid}',
        summary: 'Get payment details via API',
        tags: ['Merchants'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(name: 'uuid', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment retrieved successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'transaction', ref: '#/components/schemas/MerchantTransaction'),
                                new OA\Property(property: 'fees', ref: '#/components/schemas/FeeCalculation'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid API credentials'),
            new OA\Response(response: 403, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Payment not found'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function paymentsShow() {}

    #[OA\Get(
        path: '/merchant/pay/{uuid}',
        summary: 'Get payment link details (public)',
        tags: ['Merchants'],
        parameters: [
            new OA\Parameter(name: 'uuid', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment link retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment link retrieved successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'payment_link', ref: '#/components/schemas/PaymentLink'),
                                new OA\Property(property: 'is_valid', type: 'boolean', example: true),
                                new OA\Property(property: 'validation_message', type: 'string', nullable: true, example: null),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Payment link not found'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function payShow() {}

    #[OA\Post(
        path: '/merchant/pay/{uuid}',
        summary: 'Process payment via payment link (public)',
        tags: ['Merchants'],
        parameters: [
            new OA\Parameter(name: 'uuid', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'amount', type: 'number', nullable: true, example: 5000.00),
                    new OA\Property(property: 'customer_email', type: 'string', example: 'customer@example.com'),
                    new OA\Property(property: 'customer_phone', type: 'string', example: '+22501234567'),
                    new OA\Property(property: 'customer_name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'metadata', type: 'object'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Payment initiated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'status', type: 'integer', example: 201),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment initiated successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/MerchantTransaction'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Payment link not found'),
            new OA\Response(response: 422, description: 'Validation error or payment link invalid'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    public function payProcess() {}
}
