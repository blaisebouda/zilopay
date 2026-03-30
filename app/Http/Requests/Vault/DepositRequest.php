<?php

namespace App\Http\Requests\Vault;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1', 'max:100000000'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Le montant est requis',
            'amount.numeric' => 'Le montant doit être un nombre',
            'amount.min' => 'Le montant minimum est de 1',
            'amount.max' => 'Le montant maximum est de 100,000,000',
        ];
    }
}
