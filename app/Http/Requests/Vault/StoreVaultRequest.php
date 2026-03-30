<?php

namespace App\Http\Requests\Vault;

use App\Models\Enums\VaultType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreVaultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'currency' => ['required', 'string', new Enum(\App\Models\Enums\Currency::class)],
            'type' => ['required', 'string', new Enum(VaultType::class)],
            'maturity_date' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du coffre-fort est requis',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'currency.required' => 'La devise est requise',
            'currency.enum' => 'La devise doit être l\'un des suivants : XOF, USD',
            'type.required' => 'Le type de coffre-fort est requis',
            'type.enum' => 'Le type doit être l\'un des suivants : savings, investment, emergency',
            'maturity_date.after' => 'La date de maturité doit être dans le futur',
        ];
    }
}
