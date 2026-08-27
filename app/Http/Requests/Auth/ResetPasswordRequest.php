<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'email' => ['nullable', 'required_without:phone_number', 'email', 'exists:users,email'],
            'phone_number' => ['nullable', 'required_without:email', 'string', 'regex:' . PHONE_NUMBER_REGEX, 'exists:users,phone_number'],
            'otp_code' => ['required', 'string', 'size:6'],

            'token' => ['required', 'string'],
            'password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8),
                // ->letters()
                // ->mixedCase()
                // ->numbers()
                // ->symbols(),
            ],
        ];
    }
}
