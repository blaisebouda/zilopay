# Enum Rules

## General Rules

1. **Naming Convention**: Enum names should be in PascalCase and singular (e.g., `UserStatus`, not `UserStatuses`).
2. **Values**: Enum values should be in UPPER_SNAKE_CASE (e.g., `ACTIVE`, `INACTIVE`).
3. **Type Safety**: Use typed enums when possible to ensure type safety.
4. **Backward Compatibility**: When adding new values, ensure they don't break existing code.

## Laravel Specific Rules

1. **Location**: Enums should be placed in the `app/Models/Enums` directory.
2. **Namespace**: Use the `App\Models\Enums` namespace.
3. **Interface**: Enums should implement the `AdvancedEnumInterface` interface, in `app/Models/Enums/Contracts/AdvancedEnumInterface.php`
   **Cast Usage**: When casting enums in models, use the `enum class name`.
4. **Database**: For database storage, use string or integer types depending on the enum type.
5. **Trait**: Enums should use the `AdvancedEnum` trait, in `app/Models/Enums/Contracts/AdvancedEnum.php`
6. **Labels**: For labels, use the `label()` method, and the translation key should be `enums.{enum_name}.{value}` if Enum is string type, or `enums.{enum_name}.{value}` if Enum is integer type.
7. **Translation**: Translation keys should be in the `lang/en/enums.php` file.
8. **Usage**: When using enums in controllers, models, or other parts of the application, use the enum class name directly and methods existing in the `AdvancedEnum` trait. example: `UserStatus::ACTIVE->label(),UserStatus::ACTIVE->equals(UserStatus::ACTIVE)`

## Example

```php
<?php

namespace App\Models\Enums;

use App\Models\Enums\Contracts\AdvancedEnumInterface;
use App\Models\Enums\Contracts\AdvancedEnum;

enum UserStatus: string implements AdvancedEnumInterface
{
    use AdvancedEnum;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';

     public function label(): string
    {
        return __('enums.user_status.'.$this->value);
    }
}
```
