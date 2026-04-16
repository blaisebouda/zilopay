<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['nullable', 'required_without:phone_number', 'email'],
            'phone_number' => ['nullable', 'required_without:email', 'string', 'regex:'.PHONE_NUMBER_REGEX],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_without' => 'L\'email est requis sans numéro de téléphone',
            'email.email' => 'L\'email doit être valide',
            'password.required' => 'Le mot de passe est requis',
            'password.string' => 'Le mot de passe doit être une chaîne',
            'remember.boolean' => 'Le champ remember doit être un booléen',
            'phone_number.required_without' => 'Le numéro de téléphone est requis sans email',
            'phone_number.string' => 'Le numéro de téléphone doit être une chaîne',
            'phone_number.regex' => 'Le numéro de téléphone doit contenir 8 à 15 chiffres',
        ];
    }
}
