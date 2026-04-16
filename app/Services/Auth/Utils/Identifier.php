<?php

declare(strict_types=1);

namespace App\Services\Auth\Utils;

class Identifier
{
    private ?string $identifier = null;

    public function __construct($identifier = null)
    {
        $this->identifier = $identifier;
    }

    public static function make($identifier = null): self
    {
        return new self($identifier);
    }

    public function isEmail(): bool
    {
        return filter_var($this->identifier, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function isPhone(): bool
    {
        return preg_match(PHONE_NUMBER_REGEX, $this->identifier) === 1;
    }
}
