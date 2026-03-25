<?php

namespace App\Swagger\Annotaions;

use OpenApi\Attributes as OA;

//     post: new OA\Post(
//         summary: 'Create a new payment method',
//         tags: ['Payment Methods'],
//         security: [['sanctum' => []]],
//         requestBody: new OA\RequestBody(
//             required: true,
//             content: new OA\JsonContent(
//                 required: ['contry_id', 'name', 'type', 'code', 'min_amount', 'max_amount', 'fee_percent', 'fee_fixed'],
//                 properties: [
//                     new OA\Property(property: 'contry_id', type: 'integer', example: 1),
//                     new OA\Property(property: 'name', type: 'string', example: 'Orange Money'),
//                     new OA\Property(property: 'logo', type: 'string', example: 'orange_money.png'),
//                     new OA\Property(property: 'type', type: 'string', enum: ['mobile_money', 'card', 'bank_transfer', 'cash'], example: 'mobile_money'),
//                     new OA\Property(property: 'code', type: 'string', example: 'OM'),
//                     new OA\Property(property: 'min_amount', type: 'number', example: 100),
//                     new OA\Property(property: 'max_amount', type: 'number', example: 1000000),
//                     new OA\Property(property: 'fee_percent', type: 'number', example: 2.5),
//                     new OA\Property(property: 'fee_fixed', type: 'number', example: 50)
//                 ]
//             )
//         ),
//         responses: [
//             new OA\Response(
//                 response: 201,
//                 description: 'Payment method created successfully',
//                 content: new OA\JsonContent(ref: '#/components/schemas/PaymentMethod')
//             )
//         ]
//     )
// )]

// #[OA\PathItem(
//     path: '/api/payment-methods/{paymentMethod}',
//     get: new OA\Get(
//         summary: 'Get a specific payment method',
//         tags: ['Payment Methods'],
//         security: [['sanctum' => []]],
//         parameters: [
//             new OA\Parameter(
//                 name: 'paymentMethod',
//                 in: 'path',
//                 required: true,
//                 schema: new OA\Schema(type: 'integer')
//             )
//         ],
//         responses: [
//             new OA\Response(
//                 response: 200,
//                 description: 'Successful operation',
//                 content: new OA\JsonContent(ref: '#/components/schemas/PaymentMethod')
//             ),
//             new OA\Response(
//                 response: 404,
//                 description: 'Payment method not found'
//             )
//         ]
//     ),
//     put: new OA\Put(
//         summary: 'Update a payment method',
//         tags: ['Payment Methods'],
//         security: [['sanctum' => []]],
//         parameters: [
//             new OA\Parameter(
//                 name: 'paymentMethod',
//                 in: 'path',
//                 required: true,
//                 schema: new OA\Schema(type: 'integer')
//             )
//         ],
//         requestBody: new OA\RequestBody(
//             required: true,
//             content: new OA\JsonContent(
//                 properties: [
//                     new OA\Property(property: 'contry_id', type: 'integer', example: 1),
//                     new OA\Property(property: 'name', type: 'string', example: 'Orange Money'),
//                     new OA\Property(property: 'logo', type: 'string', example: 'orange_money.png'),
//                     new OA\Property(property: 'type', type: 'string', enum: ['mobile_money', 'card', 'bank_transfer', 'cash'], example: 'mobile_money'),
//                     new OA\Property(property: 'code', type: 'string', example: 'OM'),
//                     new OA\Property(property: 'min_amount', type: 'number', example: 100),
//                     new OA\Property(property: 'max_amount', type: 'number', example: 1000000),
//                     new OA\Property(property: 'fee_percent', type: 'number', example: 2.5),
//                     new OA\Property(property: 'fee_fixed', type: 'number', example: 50)
//                 ]
//             )
//         ),
//         responses: [
//             new OA\Response(
//                 response: 200,
//                 description: 'Payment method updated successfully',
//                 content: new OA\JsonContent(ref: '#/components/schemas/PaymentMethod')
//             ),
//             new OA\Response(
//                 response: 404,
//                 description: 'Payment method not found'
//             )
//         ]
//     ),
//     delete: new OA\Delete(
//         summary: 'Delete a payment method',
//         tags: ['Payment Methods'],
//         security: [['sanctum' => []]],
//         parameters: [
//             new OA\Parameter(
//                 name: 'paymentMethod',
//                 in: 'path',
//                 required: true,
//                 schema: new OA\Schema(type: 'integer')
//             )
//         ],
//         responses: [
//             new OA\Response(
//                 response: 204,
//                 description: 'Payment method deleted successfully'
//             ),
//             new OA\Response(
//                 response: 404,
//                 description: 'Payment method not found'
//             )
//         ]
//     )

#[OA\Schema(
    schema: 'PaymentMethod',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'contry_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Orange Money'),
        new OA\Property(property: 'logo', type: 'string', example: 'orange_money.png'),
        new OA\Property(property: 'type', type: 'string', enum: ['mobile_money', 'card', 'bank_transfer', 'cash'], example: 'mobile_money'),
        new OA\Property(property: 'code', type: 'string', example: 'OM'),
        new OA\Property(property: 'min_amount', type: 'number', example: 100),
        new OA\Property(property: 'max_amount', type: 'number', example: 1000000),
        new OA\Property(property: 'fee_percent', type: 'number', example: 2.5),
        new OA\Property(property: 'fee_fixed', type: 'number', example: 50),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time')
    ]
)]
class PaymentMethodAnnotations
{


    #[OA\PathItem(
        path: '/payment-methods',
        get: new OA\Get(
            summary: 'Get list of payment methods',
            tags: ['Payment Methods'],
            // security: [['sanctum' => []]],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Successful operation',
                    content: new OA\JsonContent(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(ref: '#/components/schemas/PaymentMethod')
                            )
                        ]
                    )
                )
            ]
        )
    )]
    public function index() {}
}
