# Global Development Rules - ZyloPay

## Tech Stack Standards

- **Backend:** Laravel 12+ (PHP 8.2+).
- **API:** RESTful, Sanctum for auth, L5-Swagger for documentation.
- **Database:** PostgreSQL.
- **Default Language:** fr (French).

---

## Backend Architecture (Laravel)

### 1. Separation of Concerns (SOC)

- **Controllers:** Must remain "lean" (Skinny Controllers). Their role is strictly limited to handling the request, calling the appropriate **Service**, and returning the **Resource**.

### 2. Services

- All business logic resides here. A Service must **not** directly manipulate the HTTP Request object; instead, it should handle typed data or objects passed from the controller.

### 3. Utils

- Reusable logic (financial calculations, token generation, string helpers) must be placed in `app/Services/[Module]/Utils/`.

### 4. Models

- Use **Casts** for data types and **Scopes** for reusable queries. **Strictly no business logic allowed in Models.**

### 5. Security & Data

- **UUID:** Always use UUIDs for public-facing routes and IDs.

### 6. Validation

- Systematically use **FormRequest** classes. Never perform validation directly within the Controller.

### 7. Mass Assignment

- Always explicitly define the `$fillable` property in models.

### 8. Swagger Documentation

- Every endpoint must be documented using OpenApi annotations, strictly following the rules defined in `./swagger-rules`.8. Swagger: Chaque endpoint doit être documenté avec les annotations OpenApi en suivants ses regles `./swagger-rules`.
