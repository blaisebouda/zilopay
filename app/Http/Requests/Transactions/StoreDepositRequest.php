<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'wallet_id' => $this->user()->defaultWallet->code,
        ]);
    }

    public function rules(): array
    {
        return [
            'wallet_id' => ['required', 'string', 'exists:wallets,code'],
            'amount' => ['required', 'numeric', 'min:100', 'max:10000000'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{8,15}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'wallet_id.required' => 'Veuillez sélectionner un portefeuille',
            'wallet_id.exists' => 'Le portefeuille sélectionné est invalide',
            'amount.required' => 'Le montant est requis',
            'amount.min' => 'Le montant minimum est de 100 CFA',
            'amount.max' => 'Le montant maximum est de 10,000,000 CFA',
            'payment_method_id.required' => 'Veuillez sélectionner un mode de paiement',
            'phone_number.required' => 'Le numéro de téléphone est requis',
            'phone_number.regex' => 'Le numéro de téléphone est invalide',
            'phone_code.required' => 'Le code du téléphone est requis',
            'phone_code.regex' => 'Le code du téléphone est invalide',
        ];
    }
}
