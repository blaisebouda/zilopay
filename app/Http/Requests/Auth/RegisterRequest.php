<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required_without:phone_number', 'email', 'unique:users,email'],
            'phone_number' => ['required_without:email', 'string', 'regex:/^[0-9]{8,15}$/', 'unique:users,phone_number'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],

            'policy_accepted' => ['required', 'boolean', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.unique' => 'Le numéro de téléphone est déjà utilisé',
            'email.unique' => 'L\'email est déjà utilisé',
            'name.required' => 'Le nom est requis',
            'name.string' => 'Le nom doit être une chaîne',
            'name.max' => 'Le nom doit contenir au maximum 100 caractères',
            'phone_number.required_without' => 'Le numéro de téléphone est requis sans email',
            'phone_number.string' => 'Le numéro de téléphone doit être une chaîne',
            'phone_number.regex' => 'Le numéro de téléphone doit contenir 8 à 15 chiffres',
            'email.required_without' => 'L\'email est requis sans numéro de téléphone',
            'email.email' => 'L\'email doit être valide',
            'password.required' => 'Le mot de passe est requis',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre',
            'password.mixedCase' => 'Le mot de passe doit contenir au moins une lettre majuscule et une lettre minuscule',
            'password.numbers' => 'Le mot de password doit contenir au moins un chiffre',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole',
            'policy_accepted.required' => 'Vous devez accepter la charte islamique',
            'policy_accepted.boolean' => 'Le champ policy_accepted doit être un booléen',
            'policy_accepted.accepted' => 'Vous devez accepter la charte islamique',
        ];
    }
}
