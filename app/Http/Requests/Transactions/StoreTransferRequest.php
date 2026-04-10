<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'sender_wallet_id' => $this->user()->defaultWallet->code,
        ]);
    }

    public function rules(): array
    {
        return [
            'sender_wallet_id' => ['required', 'string', 'exists:wallets,code'],
            'receiver_wallet_id' => ['required', 'string', 'exists:wallets,code', 'different:sender_wallet_id'],
            'amount' => ['required', 'numeric', 'min:100', 'max:10000000'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'sender_wallet_id.required' => 'Veuillez sélectionner un portefeuille source',
            'sender_wallet_id.exists' => 'Le portefeuille source est invalide',
            'receiver_wallet_id.required' => 'Veuillez sélectionner un portefeuille destinataire',
            'receiver_wallet_id.exists' => 'Le portefeuille destinataire est invalide',
            'receiver_wallet_id.different' => 'Les portefeuilles source et destinataire doivent être différents',
            'amount.required' => 'Le montant est requis',
            'amount.min' => 'Le montant minimum est de 100 CFA',
            'amount.max' => 'Le montant maximum est de 10,000,000 CFA',
        ];
    }
}
