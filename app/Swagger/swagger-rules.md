# Annotations Rules

## General Rules

1. All annotations must be in the `App\Swagger\Annotaions` namespace
2. The annotation class name must be the same as the api groupe route name and suffixed with `Anot`
3. Each endpoint must have a corresponding method in the annotation class
4. Add Schema required based on resource model in each Annotation class.
5. The pathName must be the same as the endpoint route name without api
6. Use anotation with #[OA\Tag(name: "Tag Name")] not @OA\Tag see file @OpenAPI.php for exemple
7. For context read @App\Swagger\Annotaions\AuthAnot.php to ispire you

example for:

```php
Route::groupe('auth', function () {
    Route::get('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
```

will generate:

- AuthAnot class -> with two methods: login and logout Annotated
