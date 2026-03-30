# Annotations Rules

## General Rules

1. All annotations must be in the `App\Swagger\Annotaions` namespace
2. The annotation class name must be the same as the api groupe route name and suffixed with `Anot`
3. Each endpoint must have a corresponding method in the annotation class
4. Add Schema required based on resource model in each Annotation class.
5. The pathName must be the same as the endpoint route name without api
6. Use anotation with #[OA\Tag(name: "Tag Name")] not @OA\Tag see file @OpenAPI.php for exemple
7. The pathName must be the same as the endpoint route name without api

example for:

```php
Route::groupe('auth', function () {
    Route::get('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
```

will generate:

```php
// Generer Schemas basase sur le Resource if exists
#[OA\Schema(
    schema: 'User',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
        ...Annoter properties from resource model
    ]
)]
class AuthAnot
{
    #[OA\Get(
        path: '/auth/login',
        tags: ['Auth'],
        summary: 'Login',
        description: 'Login',
        operationId: 'login',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: '#/components/schemas/User'),
                    ]
                )
            )
        ]
    )]
    public function login()
    {
    }

    #[OA\Post(
        path: '/auth/logout',
        tags: ['Auth'],
        summary: 'Logout',
        description: 'Logout',
        operationId: 'logout',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: '`ob`ect'),
                    ]
                )
            )
        ]
    )]
    public function logout()
    {
    }
}
```
