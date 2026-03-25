<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wallet_id' => ['required', 'integer', 'exists:wallets,id'],
            'amount' => ['required', 'numeric', 'min:100', 'max:500000'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{8,15}$/'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'wallet_id.required' => 'Veuillez sélectionner un portefeuille',
            'wallet_id.exists' => 'Le portefeuille sélectionné est invalide',
            'amount.required' => 'Le montant est requis',
            'amount.min' => 'Le montant minimum est de 100 CFA',
            'amount.max' => 'Le montant maximum est de 500,000 CFA',
            'payment_method_id.required' => 'Veuillez sélectionner un mode de retrait',
            'phone_number.required' => 'Le numéro de téléphone est requis',
            'phone_number.regex' => 'Le numéro de téléphone est invalide',
        ];
    }
}
